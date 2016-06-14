<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller 
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->database();
		$this->load->model('AdminModel');
		$this->load->helper('custom');
		$this->load->library('session');
    $this->load->library('upload');
	}

   public function index()
   {   
          if ($this->session->userdata('adminid'))
          {
              
              $fbreak['date']=date('Y-m-d');
              $fbreak['type']=1;
              $fbreak['status']=1;

              $sbreak['date']=date('Y-m-d');
              $sbreak['type']=2;
              $sbreak['status']=1;

              $lbreak['date']=date('Y-m-d');
              $lbreak['type']=3;
              $lbreak['status']=1;
             
              $con['activation_status']=0;
              $con1['date']=date('Y-m-d');
              $datee=date('Y-m-d');
              $data['employee']=$this->AdminModel->AllEmployee();
              $data['total_employee']=$this->AdminModel->fetchinfo('employee',$con,'count');
              $data['total_present']=$this->AdminModel->fetchinfo('attendance',$con1,'count');
              $data['total_fbreak']=$this->AdminModel->onfirstbreak($datee);
              $data['total_sbreak']=$this->AdminModel->fetchinfo('break_track',$sbreak,'count');
              $data['total_lbreak']=$this->AdminModel->fetchinfo('break_track',$lbreak,'count');
              $data['sideber']=$this->load->view('admin/includes/sideber','',true);
              $data['header']=$this->load->view('admin/includes/header','',true);
              $this->load->view('admin/admin_dashboard.php',$data);
          }      
    }

    public function addbadges()
    {
        
      $files = $_FILES['user_file'];
     
      $time=time();
      // next we pass the upload path for the images
      $config['upload_path'] = 'images/badges';
      $config['file_name']=$time;
      $config['overwrite']='TRUE';
      $config['allowed_types']='jpg|jpeg|gif|png|PNG';
      $config['max_size']='1';
      $config['max_width'] = '35';
      $config['max_height'] = '35';
        
        $_FILES['user_file']['name'] = $files['name'];
        $_FILES['user_file']['type'] = $files['type'];
        $_FILES['user_file']['tmp_name'] = $files['tmp_name'];
        $_FILES['user_file']['error'] = $files['error'];
        $_FILES['user_file']['size'] = $files['size'];
        //now we initialize the upload library
        $this->upload->initialize($config);
        // we retrieve the number of files that were uploaded
        if ($this->upload->do_upload('user_file'))
        {
          $data['uploads']= $this->upload->data();
          $f_resize=$data['uploads']['file_name'];
          $data1['badge']=$this->input->post('bname');
          $data1['tpoint']=$this->input->post('tpoint');
          $data1['icon']=$f_resize;
          $data1['status']=0;
          if($data1['badge'] && $data1['tpoint'] && $data1['badge'])
          {
            $update=$this->AdminModel->insert('badges',$data1);
            if($update)
            {
               $this->session->set_userdata('succ_msg','Badges Added Successfully!!');
               redirect(base_url().'admin_control/admin/badges');
            }
            else
            {
              $this->session->set_userdata('err_msg','Try Again');
              redirect(base_url().'admin_control/admin/badges');
            }
          }
          else
          {
            $this->session->set_userdata('err_msg','All Fields Are Needed');
            redirect(base_url().'admin_control/admin/badges');
          }
          //print_r($data);
        }
        else
        {
          $data['upload_errors'] = $this->upload->display_errors();
          $this->session->set_userdata('err_msg',$this->upload->display_errors());
          redirect(base_url().'admin_control/admin/badges');
          
        }
            
        
              
        
         
          
      //print_r($_POST);
    }

    public function changepass()
    {
        if ($this->session->userdata('adminid'))
        {
          $username=$this->session->userdata('adminid');
          $password=$this->input->post('oldpass');
          $check=$this->AdminModel->admincheck($username,$password);
          if($check)
          {
             $new=$this->input->post('newpass');
             $conf=$this->input->post('confpass');
             if($new && $conf)
             {
                 $con['Eid']=$this->session->userdata('adminid');
                 $data['password']=$this->input->post('newpass');
                 $update=$this->AdminModel->update('emp_details',$con,$data);
                 if($update)
                 {
                    $this->session->set_userdata('succ_msg','Password Changed Successfully!!!');
                    redirect(base_url().'admin_control/admin');
                 }
                 else
                 {
                   $this->session->set_userdata('err_msg','Try Again');
                   redirect(base_url().'admin_control/admin');
                 }
             }
             else
             {
                $this->session->set_userdata('err_msg','New Password and Confirm Are Different');
                redirect(base_url().'admin_control/admin');
             }
          }
          else
          {
            $this->session->set_userdata('err_msg','Password Wrong!!');
            redirect(base_url().'admin_control/admin');
          }

        }
        else
        {
           redirect(base_url());
        }

    }

    public function changebudget()
    {
      $budget['badges_id']=$this->input->post('budget');
      $get=$this->AdminModel->fetchinfo('badges',$budget,'row');
      //echo ($budget['badges_id']);
      if($get['status']==0)
      {
         $data['status']="1";
      }
      else if($get['status']==1)
      {
         $data['status']="0";
      }
      else
      {
        $data['status']="0";
      }
      //print_r($data1['status']);
      $update=$this->AdminModel->update('badges',$budget,$data);
      return $update;

    }

    public function badgesemployee()
    {
        $data['allempbadge']=$this->AdminModel->allempbadge();
        $data['allbadges']=$this->AdminModel->allbadges();
        $data['allemployee']=$this->AdminModel->AllEmployee();
        $data['sideber']=$this->load->view('admin/includes/sideber','',true);
        $data['header']=$this->load->view('admin/includes/header','',true);
        $this->load->view('admin/badgesemployee.php',$data);
    }

    public function addempbadges()
    {
       $data['Eid']=$this->input->post('empid');
       $data['Bid']=$this->input->post('badges');
       $data['status']=0;
       $update=$this->AdminModel->insert('empbadge',$data);
       if($update)
       {
             $this->session->set_userdata('succ_msg','Badges Are Added');
             redirect(base_url().'admin_control/admin/badgesemployee');
        }
        else
        {
            $this->session->set_userdata('err_msg','Try Again');
            redirect(base_url().'admin_control/admin/badgesemployee');

        }
    }

    public function delete_epmbad()
    {
      $con['E_b_id']=$this->input->post('budget_id');
      $delete=$this->AdminModel->delete($con,'empbadge');
      if($delete)
      {
        return $delete;
      }
      else
      {
        return false;
      }
    }

    public function delete_badge()
    {
      $con['badges_id']=$this->input->post('budget_id');
      $delete=$this->AdminModel->delete($con,'badges');
      if($delete)
      {
        return $delete;
      }
      else
      {
        return false;
      }

    }

    public function ChatHistory()
    {
       if ($this->session->userdata('adminid'))
          {
              
             
              $data=array();
              $data['history']=$this->AdminModel->FnChatHistory();
              $data['sideber']=$this->load->view('admin/includes/sideber','',true);
              $data['header']=$this->load->view('admin/includes/header','',true);
              $this->load->view('admin/chat_history.php',$data);
          }
          else
          {
             redirect(base_url());

          }
    }

    public function lbadd()
    {
      //print_r($_POST);
      $point=$this->input->post('bonus');
      $action=$this->input->post('action_taken');
      $bonus['Lb_id']=$this->input->post('b_id');
      if($point && $action )
      {
        $gpoint=$this->AdminModel->fetchinfo('lunch_bonus',$bonus,'row');
      
        if($action==1)
        {
           $newpoint=$gpoint['Lunch_bonus']+$point;
        }
        else
        {
          $newpoint=$gpoint['Lunch_bonus']-$point;
        }
        $data['Lunch_bonus']=$newpoint;
        $update=$this->AdminModel->update('lunch_bonus',$bonus,$data);
        if($update)
        {
             $data1['Eid']=$gpoint['Eid'];
             $data1['action']=$action;
             $data1['field']='2';
             $data1['point']=$point;
             $data1['date']=date('Y-m-d');
             $data1['time']=date('H:i:s');
             $updatelog=$this->AdminModel->insert('log_book',$data1);
             $this->session->set_userdata('succ_msg','Lunch Bonus Edited Successfully');
             redirect(base_url().'admin_control/admin/addlunchbonus');
        }
        else
        {
            $this->session->set_userdata('err_msg','Try Again');
            redirect(base_url().'admin_control/admin/addlunchbonus');

        }
      }
      else
      {

          $this->session->set_userdata('err_msg','All Fields Needed');
          redirect(base_url().'admin_control/admin/addlunchbonus');

      }

    }

    public function addlunchbonus()
    {     
          $con=date('Y-m-d');
          $data['alllunchbonus']=$this->AdminModel->alllunchbonus($con);
          
          $data['sideber']=$this->load->view('admin/includes/sideber','',true);
          $data['header']=$this->load->view('admin/includes/header','',true);
          $this->load->view('admin/addlunchbonus.php',$data);

    }

    public function badges()
    {
      if ($this->session->userdata('adminid'))
      {
          $data['badges']=$this->AdminModel->getbadges();
          $data['sideber']=$this->load->view('admin/includes/sideber','',true);
          $data['header']=$this->load->view('admin/includes/header','',true);
          $this->load->view('admin/badges.php',$data);
      
      }
      else
      {
          redirect(base_url());
      }
    }
    public function bdmactivity()
    {

        $con=date('Y-m-d');
        $data['bdmac']=$this->AdminModel->getactivity($con);
      $data['post_search']='';
      $data['bdm']=$this->AdminModel->get_bdm();
      $data['sideber']=$this->load->view('admin/includes/sideber','',true);
      $data['header']=$this->load->view('admin/includes/header','',true);
      $this->load->view('admin/bdmactivity.php',$data);
    }

    public function Postshowcover()
    {
      //echo '<pre>';print_r($_POST);exit;
      if($_POST && $this->input->post('search')!='')
      {
      $searchitem=$this->input->post('search');
      $srch=$this->AdminModel->search($searchitem);
      $data['bdmac']=$srch;
      $data['post_search']=$searchitem;
      $data['sideber']=$this->load->view('admin/includes/sideber','',true);
      $data['header']=$this->load->view('admin/includes/header','',true);
      $this->load->view('admin/bdmactivity.php',$data);
      }
      else
      {
        $token=$this->input->post('token');
        redirect(base_url().'admin_control/admin/show_cover/'.$token);
      }
    }

    public function getbdmbydate()
    {
        $con=$this->input->post('getdate');
        $activity=$this->AdminModel->getactivity($con);
        $result="";
        foreach ($activity as $key)
        {

          $date =date('m/d/Y', strtotime($key['date']));
          $time =date('h:i:s A', strtotime($key['time']));
          if ($key['cover_letter']){$cov="<i class='fa fa-check'></i>";}else{$cov="<i class='fa fa-times'></i>";}
          if($key['step1']==1){ $step1="Contacted";}
                        else if($key['step1']==2){ $step1="Rejected";}
                        else if($key['step1']==3){ $step1="Offer";}
                        else if($key['step1']==0){ $step1="pending";}
                        else { $step1=" ";}

                        if($key['step2']=="1_1"){ $step2="Offer";}
                        else if($key['step2']=="1_2"){ $step2="Rejected";}
                        else if($key['step2']=="3_1"){ $step2="Accepted";}
                        else if($key['step2']=="3_2"){ $step2="Rejected";}
                        else { $step2=" ";}

                        if($key['step3']=="1_2_1"){ $step3="Offer";}
                        else if($key['step3']=="1_2_2"){ $step3="Rejected";}
                        else { $step3=" ";}
                         $result.="<tr><td>". $date."</td>
                                   <td>".$time."</td>
                                   <td>".$key['name']."</td>
                                   <td><a href='".$key['posted_url']."' target='_blank'>Click To View</a></td>
                                   <td><a href='".$key['proposed_url']."' target='_blank'>Click To View</a></td>
                                   <td>".$cov."</td>
                                   <td><a href='admin_control/Admin/show_cover/".$key['b_ac_id']."'>View Details</a></td>
                                   <td>".$step1 ."</td>
                                   <td>".$step2 ."</td>
                                   <td>". $step3 ."</td></tr>";
                         
    }
    echo $result;
    }

    public function changestep1()
    {
      $con['b_ac_id']=$this->input->post('id');
      $data['step1']=$this->input->post('step1');
      $data['step2']=0;
      $data['step3']=0;
      $updatestatus=$this->AdminModel->update('bdm_activity',$con,$data);
      if($updatestatus)
      {
        return true;
      }
    }
    public function changestep2()
    {
      $con['b_ac_id']=$this->input->post('id');
      $data['step2']=$this->input->post('step2');
      $data['step3']=0;
      $updatestatus=$this->AdminModel->update('bdm_activity',$con,$data);
      if($updatestatus)
      {
        return true;
      }
    }

    public function changestep3()
    {
      $con['b_ac_id']=$this->input->post('id');
      $data['step3']=$this->input->post('step3');
      $updatestatus=$this->AdminModel->update('bdm_activity',$con,$data);
      if($updatestatus)
      {
        return true;
      }
    }


    public function getbdmbyname()
    { 
      $con=$this->input->post('getname');
        $activity=$this->AdminModel->getactivitybyname($con);
        $result="";
        foreach ($activity as $key)
        {
          if($key['step1']==1){ $step1="Contacted";}
                        else if($key['step1']==2){ $step1="Rejected";}
                        else if($key['step1']==3){ $step1="Offer";}
                        else { $step1="No Status";}

                        if($key['step2']=="1_1"){ $step2="Offer";}
                        else if($key['step2']=="1_2"){ $step2="Rejected";}
                        else if($key['step2']=="3_1"){ $step2="Accepted";}
                        else if($key['step2']=="3_2"){ $step2="Rejected";}
                        else { $step2="No Status";}

                        if($key['step3']=="1_2_1"){ $step3="Offer";}
                        else if($key['step3']=="1_2_2"){ $step3="Rejected";}
                        else { $step3="No Status";}
          $result.="<tr><td>". $key['date']."</td>
                         <td>".$key['time']."</td>
                         <td>".$key['name']."</td>
                         <td>". $key['project']."</td>
                         <td>".$key['url']."</td>
                         <td>".$key['posted_url']."</td>
                         <td>".$key['proposed_url']."</td>
                         <td>View Details</td>
                         <td>".$step1 ."</td>
                         <td>".$step2 ."</td>
                         <td>". $step3 ."</td></tr>";
                         
    }
    echo $result;
    }

     

    public function logout()
    {
      $this->session->unset_userdata('adminid');
       $_SESSION['username']='';
      $this->session->sess_destroy();
      redirect(base_url());
      
    }
    
    public function setbonus()
    {
      $data['shownoemp']=$this->AdminModel->shownoemp();
      $data['showallemp']=$this->AdminModel->showallemp();
      $data['sideber']=$this->load->view('admin/includes/sideber','',true);
      $data['header']=$this->load->view('admin/includes/header','',true);
      $this->load->view('admin/setbonus.php',$data);
    }

     public function show_cover($id)
    {

      $con['b_ac_id']=$id;
      $data['get']=$this->AdminModel->fetchinfo('bdm_activity',$con,'row');
      $data['sideber']=$this->load->view('admin/includes/sideber','',true);
      $data['header']=$this->load->view('admin/includes/header','',true);
      $this->load->view('admin/showcover.php',$data);
    }

    public function editprof($id)
    {

      $con['id']=$id;
      $data['emp_info']=$this->AdminModel->fetchinfo('employee',$con,'row');
      $data['sideber']=$this->load->view('admin/includes/sideber','',true);
      $data['header']=$this->load->view('admin/includes/header','',true);
      $this->load->view('admin/editprof.php',$data);
    }

    public function editoldemployee()
    {


               $files = $_FILES['user_file'];

               if( $_FILES['user_file']['name']!='')
               {
                 $time=time();
                 // next we pass the upload path for the images
                 $config['upload_path'] = 'images/profile';
                 $config['file_name']=$time;
                 $config['overwrite']='TRUE';
                 $config['allowed_types']='jpg|jpeg|gif|png|PNG';
                 $config['max_size']='2048';
                 $config['max_width'] = '350';
                 $config['max_height'] = '250';
            
                 $_FILES['user_file']['name'] = $files['name'];
                 $_FILES['user_file']['type'] = $files['type'];
                 $_FILES['user_file']['tmp_name'] = $files['tmp_name'];
                 $_FILES['user_file']['error'] = $files['error'];
                 $_FILES['user_file']['size'] = $files['size'];
                 //now we initialize the upload library
                 $this->upload->initialize($config);
                 // we retrieve the number of files that were uploaded
                 if ($this->upload->do_upload('user_file'))
                 {
                   $data1['uploads']= $this->upload->data();
                   $f_resize=$data1['uploads']['file_name'];
                   $data['pic']=$f_resize;
                 }
                 else
                 {
                    $data1['upload_errors'] = $this->upload->display_errors();
                    $this->session->set_userdata('err_msg',$this->upload->display_errors());
                    redirect(base_url().'admin_control/admin/allemp'); 
                  }
                }
                else
                {
                  $data['pic']=$this->input->post('picture');
                }
        $con['id']=$this->input->post('empid');
    
        $data['name']=$this->input->post('name');
        $data['personal_email']=$this->input->post('peremail');
        $data['address']=$this->input->post('address');
        $data[' phon_no']=$this->input->post('phno');
        $data['alt_ph_no']=$this->input->post('altphno');
        $data['gender']=$this->input->post('gender');
        $data[' m_status']=$this->input->post('marrige');
        $data['dob']=$this->input->post('dob');
        $data['joining_date']=$this->input->post('doj');
        $data['comemail']=$this->input->post('coemail');
        $data['designation']=$this->input->post('deg');
        $data['salary']=$this->input->post('salary');
        $update=$this->AdminModel->update('employee',$con,$data);
        if($update)
        {
             $this->session->set_userdata('succ_msg','Employee Edited Successfully');
             redirect(base_url().'admin_control/admin/allemp');
        }
        else
        {
             $this->session->set_userdata('succ_msg','Employee Edited Successfully');
             redirect(base_url().'admin_control/admin/allemp');

        }

     // print_r($_POST);

    }

    public function createuser()
    {
      $data['Eid']=$this->input->post('emp_ide');
      $data['username']=$this->input->post('uname');
      $data['password']="Tier5";
      $data['role']=$this->input->post('roleid');

      $con['username']=$this->input->post('uname');
      $checkuser=$this->AdminModel->fetchinfo('emp_details',$con,'count');
      if($checkuser>0)
      {
         $this->session->set_userdata('err_msg','This Username Already Used');
             redirect(base_url().'admin_control/admin/setbonus');
      }
      else
      {
        $insert_item=$this->AdminModel->insert('emp_details',$data);
         if($insert_item)
         {
            $con1['id']=$data['Eid'];
            $data1['activation_status']='0';
            $update=$this->AdminModel->update('employee',$con1,$data1);
            if($update)
            {

              $logbook['Eid']=$data['Eid'];
              $logbook['action']='1';
              $logbook['field']='3';
              $logbook['date']=date('Y-m-d');
              $logbook['time']=date('H:i:s');
              $log=$this->AdminModel->insert('log_book',$logbook);
              $this->session->set_userdata('succ_msg','User Created Successfully!!The Employee Is Active Now');
              redirect(base_url().'admin_control/admin/setbonus');
            }
            else
            {
              $this->session->set_userdata('err_msg','User Createted But Not Active');
             redirect(base_url().'admin_control/admin/setbonus');
            }
         }
         else
         {
              $this->session->set_userdata('err_msg','Try Again');
             redirect(base_url().'admin_control/admin/setbonus');
         }
       }
    }

    public function deleteshop()
    {
      $shopid=$this->input->post('shopid');
      $con['parent_id']=$shopid;
      $delete_item_of_shop=$this->AdminModel->delete($con,'items');
       
          $con1['Lnid']=$shopid;
          $delete_shop=$this->AdminModel->delete($con1,'items');
          if($delete_shop)
          {
            return true;
          }
          else
          {
            return false;
          }
    }
     public function setpointnewemp()
     {
       $data['Eid']=$this->input->post('emp_idd');
       $data['points']=$this->input->post('newapoint');
       $data['last_update']=date('Y-m-d');
       $insert_item=$this->AdminModel->insert('point_history',$data);
       if($insert_item)
       {
          $this->session->set_userdata('succ_msg','Point Added Successfully!!');
           redirect(base_url().'admin_control/admin/setbonus');
       }
       else
       {
            $this->session->set_userdata('err_msg','Try Again');
           redirect(base_url().'admin_control/admin/setbonus');
       }
     }

     public function setlbonus()
     { 
      $data['Eid']=$this->input->post('emp_id');
       $data['Lunch_bonus']=$this->input->post('newpoint');
       $data['last_update']=date('Y-m-d');
       $insert_item=$this->AdminModel->insert('lunch_bonus',$data);
       if($insert_item)
       {
          $this->session->set_userdata('succ_msg','Lunch Bonus Successfully!!');
           redirect(base_url().'admin_control/admin/setbonus');
       }
       else
       {
            $this->session->set_userdata('err_msg','Try Again');
           redirect(base_url().'admin_control/admin/setbonus');
       }

     }
    public function showallitem()
    {
       $shopid=$this->input->post('shopid');
       $con['parent_id']=$shopid;
       $getitem=$this->AdminModel->fetchinfo('items',$con,'result');

         $result="";
         foreach ($getitem as $row)
          {
          echo "<tr><td>".$row['item']."</td><td>".$row['cost']."</td><td>".$row['limit1']."</td><td><input type='button' class='btn btn-danger glyphicon glyphicon-trash' value='Delete' onclick='deleteitem(".$row['Lnid'].",".$shopid.")'></td></tr>";
          }
         echo $result;
       
    }

    public function additem()
    {
      $data['item']=$this->input->post('itemname');
      $data['cost']=$this->input->post('itemcost');
      $data['limit1']=$this->input->post('itemlimit');
      $data['parent_id']=$this->input->post('shopselect');
      $data['status']=0;

      if($data['item'] && $data['cost'] && $data['limit1'] && $data['parent_id'] )
      {
        $insert_item=$this->AdminModel->insert('items',$data);
        if($insert_item)
        {
           $this->session->set_userdata('succ_msg','Item Added Successfully,Check Item List');
           redirect(base_url().'admin_control/admin/addlunchitem');
        }
      }
      
    }

    public function pointadd()
    {
        $start_date=date('Y-m-d',strtotime('first day of this month'));
        $end_date=date('Y-m-d',strtotime('last day of this month'));
        $data['allpoints']=$this->AdminModel->fnallpoint($start_date,$end_date);
        $data['sideber']=$this->load->view('admin/includes/sideber','',true);
        $data['header']=$this->load->view('admin/includes/header','',true);
       
        $this->load->view('admin/pointadd.php',$data);
    }
    
    public function deleteitem()
    {
      $itemid['Lnid']=$this->input->post('itemid');
      $delete_item=$this->AdminModel->delete($itemid,'items');
      if($delete_item)
      {
        return true;
      }
      else
      {
        return false;
      }

    }
    public function show_allholyday()
    {
       if($_POST)
       {
          if($this->input->post('yearselect'))
          {
            $con=$this->input->post('yearselect');
          }
          else
          {
             $con=date('Y');
          }
          

       }
       else
       {
          $con=date('Y');
       }
       
       $data['allholiday']=$this->AdminModel->getholiday($con);
      $data['sideber']=$this->load->view('admin/includes/sideber','',true);
      $data['header']=$this->load->view('admin/includes/header','',true);
      $this->load->view('admin/show_allholyday.php',$data);

    }
    public function addholyday()
    {

      
      $data['sideber']=$this->load->view('admin/includes/sideber','',true);
      $data['header']=$this->load->view('admin/includes/header','',true);
      $this->load->view('admin/addholyday.php',$data);
    }

    public function add_holi()
    {
      $data['date']=$this->input->post('datepicker');
      $data['occation']=$this->input->post('reason');
      $insert_holi=$this->AdminModel->insert('holiday',$data);
      if($insert_holi)
      {
            $this->session->set_userdata('succ_msg','HolidayAdded Successfully,Check Holiday List');
           redirect(base_url().'admin_control/admin/addholyday');
      }
      else
      {
           $this->session->set_userdata('succ_msg','Try Again');
           redirect(base_url().'admin_control/admin/addholyday');
      }
    }
    
    public function delete_holiday()
    {
      $con['h_list']=$this->input->post('ho');
      $delete=$this->AdminModel->delete($con,'holiday');
      return $delete;

    }
    public function delete_spholiday()
    {
      $con['sp_h']=$this->input->post('ho');
      $delete=$this->AdminModel->delete($con,'specialholiday');
      return $delete;

    }
    
    public function specialholiday()
    {
      
       if($_POST)
       {
          if($this->input->post('yearselect'))
          {
            $con=$this->input->post('yearselect');
          }
          else
          {
             $con=date('Y');
          }
          

       }
       else
       {
          $con=date('Y');
       }
      $data['allspecialholiday']=$this->AdminModel->fetchallspc($con);
      $data['showallemp']=$this->AdminModel->showallemp();
      $data['sideber']=$this->load->view('admin/includes/sideber','',true);
      $data['header']=$this->load->view('admin/includes/header','',true);
      $this->load->view('admin/specialholiday.php',$data);
    }
    
    public function addspholiday()
    {
        if($_POST)
      {
            $data['Eid']=$this->input->post('name');
            $data['date']=$this->input->post('datepicker');
            $data['reason']=$this->input->post('reason');
            if($data['Eid'] && $data['date'] && $data['reason'])
            {
              $insert_spholiday=$this->AdminModel->insert('specialholiday',$data);
              if($insert_spholiday)
              {
                 $this->session->set_userdata('succ_msg','Special Holiday Added Successfully');
                     redirect(base_url().'admin_control/admin/specialholiday');
              }
              else
              {
                $this->session->set_userdata('err_msg','Try Again!!!');
                     redirect(base_url().'admin_control/admin/specialholiday');
              }
            }
            else
            {
              $this->session->set_userdata('err_msg','All Fields Are Needed!!!');
                   redirect(base_url().'admin_control/admin/specialholiday');
            }
      }


    }

    public function expendature_attend()
    {
         if($_POST)
      {
           $start_date=$this->input->post('datecheck');
           $end_date=$this->input->post('endofmonth');
           if($start_date && $end_date)
           {
              $start_date=$this->input->post('datecheck');
              $end_date=$this->input->post('endofmonth');
              $data['current']=$this->input->post('myDate');
           }
           else
           {
              
              $data['current']=$this->input->post('myDate');
              $datee=$data['current'];
              $start_date= date('Y-m-01', strtotime($datee));
              $end_date=date('Y-m-t', strtotime($datee));
           }
      }
      else
      {
        $start_date=date('Y-m-d',strtotime('first day of this month'));
        $end_date=date('Y-m-d',strtotime('last day of this month'));
        $data['current']=date('M Y');
      }
        
        $data['allpoints']=$this->AdminModel->fnallpointexp($start_date,$end_date);
        $data['sideber']=$this->load->view('admin/includes/sideber','',true);
        $data['header']=$this->load->view('admin/includes/header','',true);
       
        $this->load->view('admin/expendature_attend.php',$data);
    }
    
     public function getitembyshop()
   {
      extract($_POST);
      $items="";
      //print_r($_POST);
      $data['parent_id']=$this->input->post('shopid');
      $result=$this->AdminModel->fetchinfo('items',$data,'result');
      foreach ($result as $value)
      {
            $condition="";
            $condition=($value['limit1']+$value['item']);
        $options="";
        for($y=1;$y<=$value['limit1'];$y++)
        {
                         
      $options.='<option id="limit_'.$value['Lnid'].'" value="'.$y.'">'.$y.'</option>';
                          
      }
        //print_r($value['item']);
        $items.= "<tr><td id='item_name_".$value['Lnid']."'>".$value['item']."</td><td id='item_cost_".$value['Lnid']."'>".$value['cost']."</td><td><select id='item_limit_".$value['Lnid']."'>".$options."</select></td><td><input type='button' value='Add' id='btnadd_".$value['Lnid']."' onclick='addorremove(".$value['Lnid'].",".$value['cost'].")' ></td></tr>";
      }
      echo $items;
   }
    public function allpoint()
    {   

       if($_POST)
      {   
           $start_date=$this->input->post('datecheck');
           $end_date=$this->input->post('endofmonth');
           if($start_date && $end_date)
           {
              $start_date=$this->input->post('datecheck');
              $end_date=$this->input->post('endofmonth');
              $data['current']=$this->input->post('myDate');
           }
           else
           {
              
              $data['current']=$this->input->post('myDate');
              $datee=$data['current'];
              $start_date= date('Y-m-01', strtotime($datee));
              $end_date=date('Y-m-t', strtotime($datee));
           }
      }
      else
      {
        $start_date=date('Y-m-d',strtotime('first day of this month'));
        $end_date=date('Y-m-d',strtotime('last day of this month'));
        $data['current']=date('M Y');
      }
        
        $data['allpoints']=$this->AdminModel->fnallpoint($start_date,$end_date);
        $data['sideber']=$this->load->view('admin/includes/sideber','',true);
        $data['header']=$this->load->view('admin/includes/header','',true);
       
        $this->load->view('admin/allpoint.php',$data);
    }

    public function allemp()
    {

          $data['allemployee']=$this->AdminModel->fnallemp();
          $data['sideber']=$this->load->view('admin/includes/sideber','',true);
          $data['header']=$this->load->view('admin/includes/header','',true);
       
          $this->load->view('admin/allemp.php',$data);
    }
    
    public function add_point()
    {

      $point['points']=$this->input->post('finalpoint');
      $point['last_update']=date("Y-m-d");
      $data1['P_id']=$this->input->post('point_id');
      $update=$this->AdminModel->update('point_history',$data1,$point);
      
      //return $update;
      if($update)
      {
          $data2['Eid']=$this->input->post('empid');
          $data2['action']=$this->input->post('action');
          $data2['point']=$this->input->post('npoint');
          $data2['date']=date('Y-m-d');
          $data2['time']=date('H:i:s');
          $data2['field']='1';
          //print_r($data2);
          $update_log=$this->AdminModel->insert('log_book',$data2);
          if($update_log)
          {
            return $update_log;
          }
          else
          {
            return false;
          }
      }
      
    }


    public function logactivity()
    {
          $data['all_log']=$this->AdminModel->logactivity();
          $data['sideber']=$this->load->view('admin/includes/sideber','',true);
          $data['header']=$this->load->view('admin/includes/header','',true);
       
          $this->load->view('admin/logactivity.php',$data);
    }

    public function shownotice()
    {      
          $data['notice']=$this->AdminModel->allnotice();
          $data['sideber']=$this->load->view('admin/includes/sideber','',true);
          $data['header']=$this->load->view('admin/includes/header','',true);
       
          $this->load->view('admin/shownotice.php',$data);
    }

    public function showempofmonth()
    {     
          $data['showemp']=$this->AdminModel->showemp();
          $data['emp_of_month']=$this->AdminModel->showempofmonth();
          $data['sideber']=$this->load->view('admin/includes/sideber','',true);
          $data['header']=$this->load->view('admin/includes/header','',true);
          
          $this->load->view('admin/showempofmonth.php',$data);
    }

    public function addempofmon()
    {
      $data['Eid']=$this->input->post('nameselect');
      $data['month']=$this->input->post('myDate');
      if($data['Eid'] && $data['month'])
      {
        $insert_notice=$this->AdminModel->insert('emp_of_month',$data);
        if($insert_notice)
        {
          redirect(base_url().'admin_control/admin/showempofmonth');
        }
        else
        {
           redirect(base_url().'admin_control/admin/showempofmonth');
        }
      }
    }
    public function empinfo()
    {
          $data['showemp']=$this->AdminModel->showemp();
          $data['sideber']=$this->load->view('admin/includes/sideber','',true);
          $data['header']=$this->load->view('admin/includes/header','',true);
       
          $this->load->view('admin/empinfo.php',$data);
    }

    public function reset_password()
    {
      alert('HI');
    }

    public function allbreak()
    {



      $data['showallbreak']=$this->AdminModel->showallbreak();
      $data['header']=$this->load->view('admin/includes/header','',true);
      $data['sideber']=$this->load->view('admin/includes/sideber','',true);
      $this->load->view('admin/allbreak.php',$data);
    }

    public function addnotice()
    {
      $data['header']=$this->load->view('admin/includes/header','',true);
      $data['sideber']=$this->load->view('admin/includes/sideber','',true);
      $this->load->view('admin/addnotice.php',$data);
    }
    public function add_notice()
    {
      $data['subject']=$this->input->post('subject');
      $data['notice']=$this->input->post('notice');
      $data['date']=date("Y-m-d");
      $data['status']=0;
      $insert_notice=$this->AdminModel->insert('notice_board',$data);
      if($insert_notice)
      {
        $this->session->set_userdata('succ_msg','Notice Added Successfully');
                   redirect(base_url().'admin_control/admin/addnotice');

      }
      else
      {
        $this->session->set_userdata('err_msg','Try Again');
                   redirect(base_url().'admin_control/admin/addnotice');

      }

    }

    public function add_break()
    {
      if($_POST)
      {

        $hour=$this->input->post('brk_hour');
        if($hour<10)
        {
          $hour='0'.$hour;
        }
       
        $min=$this->input->post('brk_min');
        if($min<10)
        {
          $min='0'.$min;
        }
        $sec=$this->input->post('brk_sec');
        if($sec<10)
        {
          $sec='0'.$sec;
        }
        $breakname=$this->input->post('break_name');
        $data['break_name']=$breakname;
        $data['status']=0;
        $data['duration']=$hour.":".$min.":".$sec;
        if($hour && $min && $sec && $breakname)
        {
              if($hour<24 && $min<60 && $sec<60)
              {
                $insert_break=$this->AdminModel->insert('break',$data);
                if($insert_break)
                {
                   $this->session->set_userdata('succ_msg','Break Added Successfully');
                   redirect(base_url().'admin_control/admin/allbreak');
                }
                else
                {
                    $this->session->set_userdata('err_msg','Try Again');
                    redirect(base_url().'admin_control/admin/allbreak');
                }
              }
              else
              {
                  $this->session->set_userdata('err_msg','Fill All Fields With Proper Value');
                  redirect(base_url().'admin_control/admin/allbreak');
              }
        }
        else
        {
          $this->session->set_userdata('err_msg','All Fields Are Needed');
          redirect(base_url().'admin_control/admin/allbreak');
        }
       
      }

    }

    public function deletebrk()
    { 
      if($_POST)
        {
         $con['break_id']=$this->input->post('b_id');
         $delete=$this->AdminModel->delete($con,'break');
         if($delete>0)
         {
           return true;
           
         }
        
       }
    }
   
    public function deletenotice()
    {
        if($_POST)
        {
         $con['n_id']=$this->input->post('noticeid');
         $delete=$this->AdminModel->delete($con,' notice_board');
         if($delete>0)
         {
           return true;
           
         }
        
       }

       

    }

    public function fetchnotice()
       {
         $con['n_id']=$this->input->post('noticeid');
         $notice=$this->AdminModel->fetchinfo('notice_board',$con,'row');
         print_r($notice) ;
       }

       public function change_notice()
       {
         $con['n_id']=$this->input->post('noticeid');
         $data['status']=$this->input->post('newstatus');
         $update=$this->AdminModel->update('notice_board',$con,$data);
          if($update)
          {
          
            redirect(base_url().'admin_control/admin/shownotice');
          }
          else
          {
            redirect(base_url().'admin_control/admin/shownotice');
           
          }
       }
       public function edit_notice()
       {
          $data['subject']=$this->input->post('subject');
          $data['notice']=$this->input->post('notice');
          $con['n_id']=$this->input->post('noticeid');
          $update=$this->AdminModel->update('notice_board',$con,$data);
          if($update)
          {
            $this->session->set_userdata('succ_msg','Notice Updated Successfully');
            redirect(base_url().'admin_control/admin/shownotice');
          }
          else
          {
            redirect(base_url().'admin_control/admin/shownotice');
            $this->session->set_userdata('err_msg','Try Again!!!!');
          }
       }

    public function add_employee()
    { 
        
      $data['header']=$this->load->view('admin/includes/header','',true);
      $data['sideber']=$this->load->view('admin/includes/sideber','',true);
      $this->load->view('admin/add_employee.php',$data);

    }

    public function firstbreaktimer()
    {
      $datee=date('Y-m-d');
      $break1['rank']=1;
      $fbreakduration=$this->AdminModel->fetchinfo('break',$break1,'row');
      $total_fbreak=$this->AdminModel->onfirstbreak($datee);
      $result="";
      foreach ($total_fbreak as $key)
      {
            $default_time=$fbreakduration['duration'];

            $nowtime = new DateTime('now');
            $diff = $nowtime->diff(new DateTime($key['starttime']));
            $time_spend = ((($diff->h*60)+$diff->i)*60)+$diff->s;
                          
            $time = explode(':', $default_time);
            $default=($time[0]*3600) + ($time[1]*60) + $time[2]; 
            if($default>$time_spend)
            {
              $remainingtime = $default - $time_spend;
              $sec=($remainingtime % 60);
              if($sec<10){ $sec="0".$sec;}
              $minutes = ($remainingtime / 60) % 60;
              if($minutes<10){ $minutes="0".$minutes;}
              $hours = floor($remainingtime / (60 * 60));
              if($hours<10){ $hours="0".$hours;}

              $time_left="<span  style='color:green;'>$hours:$minutes:$sec</span>";
            }
            else
            {
              $remainingtime = $time_spend - $default;
              $sec=($remainingtime % 60);
              if($sec<10){ $sec="0".$sec;}
              $minutes = ($remainingtime / 60) % 60;
              if($minutes<10){ $minutes="0".$minutes; }
              $hours = floor($remainingtime / (60 * 60));
              if($hours<10){ $hours="0".$hours;}

              $time_left="<span  style='color:red;'>$hours:$minutes:$sec</span>";                    
            }

            $result .="<tr><td>".$key['name']."</td><td>".$time_left."</td></tr>";
           
      }
      echo $result;
    }

    public function secondbreaktimer()
    {
      $datee=date('Y-m-d');
      $break1['rank']=2;
      $fbreakduration=$this->AdminModel->fetchinfo('break',$break1,'row');
      $total_fbreak=$this->AdminModel->onsecondbreak($datee);
      $result="";
      foreach ($total_fbreak as $key)
      {
            $default_time=$fbreakduration['duration'];

            $nowtime = new DateTime('now');
            $diff = $nowtime->diff(new DateTime($key['starttime']));
            $time_spend = ((($diff->h*60)+$diff->i)*60)+$diff->s;
                          
            $time = explode(':', $default_time);
            $default=($time[0]*3600) + ($time[1]*60) + $time[2]; 
            if($default>$time_spend)
            {
              $remainingtime = $default - $time_spend;
              $sec=($remainingtime % 60);
              if($sec<10){ $sec="0".$sec;}
              $minutes = ($remainingtime / 60) % 60;
              if($minutes<10){ $minutes="0".$minutes;}
              $hours = floor($remainingtime / (60 * 60));
              if($hours<10){ $hours="0".$hours;}

              $time_left="<span  style='color:green;'>$hours:$minutes:$sec</span>";
            }
            else
            {
              $remainingtime = $time_spend - $default;
              $sec=($remainingtime % 60);
              if($sec<10){ $sec="0".$sec;}
              $minutes = ($remainingtime / 60) % 60;
              if($minutes<10){ $minutes="0".$minutes; }
              $hours = floor($remainingtime / (60 * 60));
              if($hours<10){ $hours="0".$hours;}

              $time_left="<span  style='color:red;'>$hours:$minutes:$sec</span>";                    
            }

            $result .="<tr><td>".$key['name']."</td><td>".$time_left."</td></tr>";
           
      }
      echo $result;

    }

    public function thirdbreaktimer()
    {
      $datee=date('Y-m-d');
      $break1['rank']=3;
      $fbreakduration=$this->AdminModel->fetchinfo('break',$break1,'row');
      $total_fbreak=$this->AdminModel->onthirdbreak($datee);
      $result="";
      foreach ($total_fbreak as $key)
      {
            $default_time=$fbreakduration['duration'];

            $nowtime = new DateTime('now');
            $diff = $nowtime->diff(new DateTime($key['starttime']));
            $time_spend = ((($diff->h*60)+$diff->i)*60)+$diff->s;
                          
            $time = explode(':', $default_time);
            $default=($time[0]*3600) + ($time[1]*60) + $time[2]; 
            if($default>$time_spend)
            {
              $remainingtime = $default - $time_spend;
              $sec=($remainingtime % 60);
              if($sec<10){ $sec="0".$sec;}
              $minutes = ($remainingtime / 60) % 60;
              if($minutes<10){ $minutes="0".$minutes;}
              $hours = floor($remainingtime / (60 * 60));
              if($hours<10){ $hours="0".$hours;}

              $time_left="<span  style='color:green;'>$hours:$minutes:$sec</span>";
            }
            else
            {
              $remainingtime = $time_spend - $default;
              $sec=($remainingtime % 60);
              if($sec<10){ $sec="0".$sec;}
              $minutes = ($remainingtime / 60) % 60;
              if($minutes<10){ $minutes="0".$minutes; }
              $hours = floor($remainingtime / (60 * 60));
              if($hours<10){ $hours="0".$hours;}

              $time_left="<span  style='color:red;'>$hours:$minutes:$sec</span>";                    
            }

            $result .="<tr><td>".$key['name']."</td><td>".$time_left."</td></tr>";
           
      }
      echo $result;

    }

    public function edit_break()
    {
      if($_POST)
      {

        $hour=$this->input->post('brk_hour_edit');
        if($hour<10)
        {
          $hour='0'.$hour;
        }
       
        $min=$this->input->post('brk_min_edit');
        if($min<10)
        {
          $min='0'.$min;
        }
        $sec=$this->input->post('brk_sec_edit');
        if($sec<10)
        {
          $sec='0'.$sec;
        }
        $breakname=$this->input->post('break_name_edit');
        $con['break_id']=$this->input->post('brk_id');
        $data['break_name']=$breakname;
        $data['status']=0;
        $data['duration']=$hour.":".$min.":".$sec;
        if($hour && $min && $sec && $breakname)
        {


              if($hour<24 && $min<60 && $sec<60)
              {
                $update=$this->AdminModel->update('break',$con,$data);
                if($update)
                {
                   $this->session->set_userdata('succ_msg','Break Updated Successfully');
                   redirect(base_url().'admin_control/admin/allbreak');
                }
                else
                {
                    $this->session->set_userdata('err_msg','Try Again');
                    redirect(base_url().'admin_control/admin/allbreak');
                }
              }
              else
              {
                  $this->session->set_userdata('err_msg','Fill All Fields With Proper Value');
                  redirect(base_url().'admin_control/admin/allbreak');
              }
        }
        else
        {
          $this->session->set_userdata('err_msg','All Fields Are Needed');
          redirect(base_url().'admin_control/admin/allbreak');
        }
       
      }

    }

    public function change_break_status()
    {
      if($_POST)
      {

        $con['break_id']=$this->input->post('b_id');
        $data['status']=$this->input->post('bstatus');
        $update=$this->AdminModel->update('break',$con,$data);
        return $update;
      }



    }

    public function restpass()
    {
      if($_POST)
      {
        
        $con['Eid']=$this->input->post('emp_id');
        $data['password']=$this->input->post('new_password');
        if($data['password'])
        {
      
            $update=$this->AdminModel->update('emp_details',$con,$data);
            if($update)
            {
                return true;
            }
            else
            {
                 return false;
            }
        }
        
      }

    }

    public function edit_emp()
    {
      if($_POST)
      {

          $data['username']=$this->input->post('username');
        

          $con['Eid']=$this->input->post('emid');
          if($data['username'] &&  $con['Eid'] )
          {
              $update=$this->AdminModel->update('emp_details',$con,$data);
              if($update)
              {
                 $this->session->set_userdata('succ_msg','Employee Details Updated Successfully');
                 redirect(base_url().'admin_control/admin/empinfo');
              }
              else
              {
                $this->session->set_userdata('err_msg','Try Again');
                 redirect(base_url().'admin_control/admin/empinfo');
              }
          }
          else
          {
               $this->session->set_userdata('err_msg','Try Again');
               redirect(base_url().'admin_control/admin/empinfo');

          }

      }

    }

    public function lunchorder()
    {
        
        $con=date('Y-m-d');
        $data['allorder']=$this->AdminModel->lunchinfo($con);
        $data['header']=$this->load->view('admin/includes/header','',true);
        $data['sideber']=$this->load->view('admin/includes/sideber','',true);
        $this->load->view('admin/lunchorder.php',$data);

    }

    public function prevlunchorder()
    {
       $con=$this->input->post('date');
       $get_order=$this->AdminModel->lunchinfo($con);
  
       $result="";
      foreach ($get_order as $value) 
       { 
       
        $result.="<tr>
                     <td>".$value['date']."</td>
                     <td>".$value['name']."</td>
                     <td>".$value['shopname']."</td>
                     <td>".$value['items']."</td>
                     <td>".$value['cost']."</td>
                     <td></td>
                     <td></td>
                     <td></td>
                    </tr>";
       }
      print_r($result) ;
    }

    public function manageclockin()
    {  
        $data['alltime']=$this->AdminModel->alltime();
        $data['header']=$this->load->view('admin/includes/header','',true);
        $data['sideber']=$this->load->view('admin/includes/sideber','',true);
        $this->load->view('admin/clockinmanage.php',$data);
    }

    public function expand()
    {  

       if($_POST)
       {
          
           $starting_date=$this->input->post('datecheck');
           $ending_date=$this->input->post('endofmonth');
           $data['current']=$this->input->post('myDate');
            if( $starting_date && $ending_date)
            {
                $start_date=$this->input->post('datecheck');
                $end_date=$this->input->post('endofmonth');
            }
            else
            {
                $data['current']=$this->input->post('myDate');
                $datee=$data['current'];
                $start_date= date('Y-m-01', strtotime($datee));
               $end_date=date('Y-m-t', strtotime($datee));
            }
        }
        else
        {
            $start_date=date("m/d/Y", strtotime(date('m').'/01/'.date('Y')));
            $end_date=date("Y-m-d");
            $data['current']=date("M Y");
        }

          $total_lunchbonus="";
          $total_vendor_cost="";
          $con=array('parent_id'=>0);
          $data['allemp']=$this->AdminModel->fetchallemployee();
          $allemp=$this->AdminModel->fetchallemployee();
          $data['allshop']=$this->AdminModel->fetchinfo('items',$con,'result');
        
          $allshop=$this->AdminModel->fetchinfo('items',$con,'result');
          $result="";
          foreach ($allshop as $value) 
              {
                  $per_vendor_cost=$this->AdminModel->allordercost($value['Lnid'],$start_date,$end_date);
                  if($per_vendor_cost)
                  {
                                $total_vendor_cost=$total_vendor_cost+$per_vendor_cost;
                                           
                                $result.="<tr>
                               <td>".$value['item']."</td>
                               <td>".$per_vendor_cost."</td>
                               </tr>";
                  }
              }
        $data['result']=$result;
        $data['total_vendor_cost']=$total_vendor_cost;


        $lunch_bonus="";
        foreach ($allemp as $employee) 
        {
          $bonus=$this->AdminModel->emp_lunch_bonus($employee['id'],$start_date,$end_date);
            
            if($bonus)
            {
              $total_lunchbonus=$total_lunchbonus+$bonus; 
            $lunch_bonus.="<tr>
                             <td>".$employee['name']."</td>
                             <td>".$bonus."</td>
                             </tr>";
            }
        }
        $data['bonus']=$lunch_bonus;
        $data['total_lunchbonus']=$total_lunchbonus;

        $data['header']=$this->load->view('admin/includes/header','',true);
        $data['sideber']=$this->load->view('admin/includes/sideber','',true);
        $this->load->view('admin/expandlunch.php',$data);
    }


    public function dltordr()
    {
      
      $data['Liid']=$this->input->post('orderid');
      //print_r($data);
      $result=$this->AdminModel->delete($data,'lunchorder');
      if($result)
      {
          return true;
      }
      
    }
    public function sublorder()
    {
      $data['Eid']=$this->input->post('emp_id');
      $data['date']=date('Y-m-d');
      $data['shopname']=$this->input->post('shop_name');
      $data['shop_id']=$this->input->post('shop_id');
      $data['items']=$this->input->post('total_item');
      $data['cost']=$this->input->post('total_cost');
      $data['status']='0';
      
      if($data['cost']<=100)
      {
        $con['Eid']=$data['Eid'];
        $con['date']=$data['date'];
        $check=$this->AdminModel->fetchinfo('lunchorder',$con,'count');
        if($check>0)
        {
          $this->session->set_userdata('err_msg','This Employee Already Placed Lunch Order');
          redirect(base_url().'admin_control/admin/placelunch');
        }
        else
        {
          $insert_lorder=$this->AdminModel->insert('lunchorder',$data);
          if($insert_lorder)
          {
             $this->session->set_userdata('succ_msg','Lunch Order Placed Successfully');
             redirect(base_url().'admin_control/admin/placelunch');
          }
          else
          {
            $this->session->set_userdata('err_msg','Try Again');
           redirect(base_url().'admin_control/admin/placelunch');
          }
        }
        
      }
      else
      {
          $this->session->set_userdata('err_msg','Cost More Than Rs 100/-');
          redirect(base_url().'admin_control/admin/placelunch');
      }


    }

    public function edittpoint()
    {
      $con['badges_id']=$this->input->post('bid');
      $data['tpoint']=$this->input->post('newinput');
      $update=$this->AdminModel->update('badges',$con,$data);
      
      if($update)
      {
          redirect(base_url().'admin_control/admin/badges');
          $this->session->set_userdata('succ_msg','');
      }
      else
      {
         redirect(base_url().'admin_control/admin/badges');
          $this->session->set_userdata('err_msg','Try Again');
      }


    }
    public function placelunch()
    {   

        $con['activation_status']=0;
        $data['allemployee']=$this->AdminModel->fetchinfo('employee',$con,'result');

        $con1['status']=0;
        $con1['parent_id']=0;
        $data['allshop']=$this->AdminModel->fetchinfo('items',$con1,'result');
        $data['header']=$this->load->view('admin/includes/header','',true);
        $data['sideber']=$this->load->view('admin/includes/sideber','',true);
        $this->load->view('admin/placelunch.php',$data);
    }
    public function changeworkingstatus()
    {

      $con['id']=$this->input->post('emp_id');

      $data['activation_status']=1;
      $data['resign_date']=$this->input->post('date');
      $data['reason']=$this->input->post('reason');
 
      if($con['id'] && $data['activation_status'] && $data['resign_date'] && $data['reason'])
      {
        $update=$this->AdminModel->update('employee',$con,$data);
        if($update)
        {
          return true;
        }
      }
    }


    public function edit_clock()
      {
        $time['Time']=$this->input->post('time');
        $clock['Clid']=$this->input->post('cl_id');
        if($time && $clock)
        {


          $update=$this->AdminModel->update('clockintime',$clock,$time);
          if($update)
          {
            return true;
          }
        }
      }

      public function addlunchitem()
      { 
        if($_POST)
        { 
          $add['item']=$this->input->post('shopname');
          $add['cost']="0:00";
          $add['limit1']="0";
          $add['parent_id']="0";
          $add['status']="0";
          if($add['item'])
          {
          $insert_item=$this->AdminModel->insert('items',$add);
          }
        }

        $con['parent_id']=0;
        $data['allshop']=$this->AdminModel->fetchinfo('items',$con,'result');
        $data['header']=$this->load->view('admin/includes/header','',true);
        $data['sideber']=$this->load->view('admin/includes/sideber','',true);
        $this->load->view('admin/addlunchitem.php',$data);
      }

      public function showallevent()
      {
       
        $data['allevent']=$this->AdminModel->showevent();
        $data['header']=$this->load->view('admin/includes/header','',true);
        $data['sideber']=$this->load->view('admin/includes/sideber','',true);
        $this->load->view('admin/showallevent.php',$data);
      }

      public function deleteevent()
      {
        if($_POST)
        {
           $con['EventId']=$this->input->post('ev_id');
           $delete=$this->AdminModel->delete($con,'tbl_event_informations');
           if($delete>0)
           {
            return true;  
           } 
        }
      }

      public function addevent()
      {
        $data['showemp']=$this->AdminModel->showemp();
        $data['header']=$this->load->view('admin/includes/header','',true);
        $data['sideber']=$this->load->view('admin/includes/sideber','',true);
        $this->load->view('admin/addevent.php',$data);

      }

      public function add_event()
      {
          if($_POST)
          {
            $data['Eid']=$this->input->post('emp_name');
            $data['date']=$this->input->post('date');
            $data['event_informations']=$this->input->post('newevent');
             print_r($data);
            if($data['Eid'] && $data['date'] && $data['event_informations'])
            {
               $insert_break=$this->AdminModel->insert('tbl_event_informations',$data);
                if($insert_break)
                {
                   $this->session->set_userdata('succ_msg','Event Added Successfully');
                   redirect(base_url().'admin_control/admin/addevent');
                }
                else
                {
                    $this->session->set_userdata('err_msg','Try Again');
                    redirect(base_url().'admin_control/admin/addevent');
                }
            }
            else
            {
                    $this->session->set_userdata('err_msg','All Fields Are Needed');
                    redirect(base_url().'admin_control/admin/addevent');
            }
          }


      }

      

      public function add_new_employee()
      {
         if($_POST)
         {
               $files = $_FILES['user_file'];
               if($files)
               {
                 $time=time();
                 // next we pass the upload path for the images
                 $config['upload_path'] = 'images/profile';
                 $config['file_name']=$time;
                 $config['overwrite']='TRUE';
                 $config['allowed_types']='jpg|jpeg|gif|png|PNG';
                 $config['max_size']='2048';
                 $config['max_width'] = '350';
                 $config['max_height'] = '250';
            
                 $_FILES['user_file']['name'] = $files['name'];
                 $_FILES['user_file']['type'] = $files['type'];
                 $_FILES['user_file']['tmp_name'] = $files['tmp_name'];
                 $_FILES['user_file']['error'] = $files['error'];
                 $_FILES['user_file']['size'] = $files['size'];
                 //now we initialize the upload library
                 $this->upload->initialize($config);
                 // we retrieve the number of files that were uploaded
                 if ($this->upload->do_upload('user_file'))
                 {
                   $data1['uploads']= $this->upload->data();
                   $f_resize=$data1['uploads']['file_name'];
                   $data['pic']=$f_resize;
                 }
                 else
                 {
                    $data1['upload_errors'] = $this->upload->display_errors();
                    $this->session->set_userdata('err_msg',$this->upload->display_errors());
                    redirect(base_url().'admin_control/admin/add_employee'); 
                  }
                }
                $data['name']=$this->input->post('name');
                $data['personal_email']=$this->input->post('peremail');
                $data['address']=$this->input->post('address');
                $data['phon_no']=$this->input->post('phno');
                $data['alt_ph_no']=$this->input->post('altphno');
                $data['gender']=$this->input->post('gender');
                $data['m_status']=$this->input->post('marrige');
                $data['dob']=$this->input->post('dob');
                $data['joining_date']=$this->input->post('doj');
                $data['comemail']=$this->input->post('coemail');
                $data['designation']=$this->input->post('deg');
                $data['salary']=$this->input->post('salary');
                $data['activation_status ']='2';


            if($data['name'])
            {

              $new_emp=$this->AdminModel->insert('employee',$data);
              if($new_emp)
              {
                 $this->session->set_userdata('succ_msg','Employee Added Successfully');
                 redirect(base_url().'admin_control/admin/add_employee');
              }
              else
              {
                $this->session->set_userdata('err_msg','Employee Cannot Added');
                redirect(base_url().'admin_control/admin/add_employee');
              }
            
            }
            else
            {

                $this->session->set_userdata('err_msg','All Fields Are Required');
                redirect(base_url().'admin_control/admin/add_employee');
            }
            
         }

      }
       
      public function dailyactivity()
      {

         $con=date('Y-m-d');
         $data['present_employee']=$this->AdminModel->empclock($con);
          


         
         
         $data['firstbreak']=$this->AdminModel->firstbreak($con);
         $data['onfirstbreak']=$this->AdminModel->onfirstbreak($con);
         $con1['rank']=1;
         $data['firstduration']=$this->AdminModel->fetchinfo('break',$con1,'row');

         
         $data['secondbreak']=$this->AdminModel->secondbreak($con);
         $data['onsecondbreak']=$this->AdminModel->onsecondbreak($con);
         $con2['rank']=2;
         $data['secondduration']=$this->AdminModel->fetchinfo('break',$con2,'row');
         
         $data['thirdbreak']=$this->AdminModel->thirdbreak($con);
         $data['onthirdbreak']=$this->AdminModel->onthirdbreak($con);

         $con3['rank']=3;
         $data['thirdduration']=$this->AdminModel->fetchinfo('break',$con3,'row');

         $data['header']=$this->load->view('admin/includes/header','',true);
         $data['sideber']=$this->load->view('admin/includes/sideber','',true);
         $this->load->view('admin/dailyactivity.php',$data);

      }

      public function searchbox()
      {
        $searchitem=$this->input->post('search');
        $srch=$this->AdminModel->search($searchitem);
        $result="";
        //print_r($srch);
        foreach ($srch as $value) 
        {
          $date =date('m/d/Y', strtotime($value['date']));
          $time =date('h:i:s A', strtotime($value['time']));
          if ($value['cover_letter']){$cov="<i class='fa fa-check'></i>";}else{$cov="<i class='fa fa-times'></i>";}
          if($value['step1']==1){ $step1="Contacted";}
                        else if($value['step1']==2){ $step1="Rejected";}
                        else if($value['step1']==3){ $step1="Offer";}
                        else if($value['step1']==0){ $step1="pending";}
                        else { $value=" ";}

                        if($value['step2']=="1_1"){ $step2="Offer";}
                        else if($value['step2']=="1_2"){ $step2="Rejected";}
                        else if($value['step2']=="3_1"){ $step2="Accepted";}
                        else if($value['step2']=="3_2"){ $step2="Rejected";}
                        else { $step2=" ";}

                        if($value['step3']=="1_2_1"){ $step3="Offer";}
                        else if($value['step3']=="1_2_2"){ $step3="Rejected";}
                        else { $step3=" ";}

          $result.="<tr>
                      <td>".$date."</td>
                      <td>".$time."</td>
                      <td>".$value['name']."</td>
                      <td><a href='".$value['posted_url']."' target='_blank'>Click To View</a></td>
                      <td><a href='".$value['proposed_url']."' target='_blank'>Click To View</a></td>
                      <td>".$cov."</td>
                      <td><a href='admin_control/Admin/show_cover/".$value['b_ac_id']."'>View Details</a></td>
                      <td>".$step1 ."</td>
                      <td>".$step2 ."</td>
                      <td>". $step3 ."</td>
                   </tr>";
        }
        echo  $result;
      }

      public function emplate()
      {
        $con=date('Y-m-d');
        $data['empabsent']=$this->AdminModel->empabsent($con);
        $data['empearlyclockout']=$this->AdminModel->empearlyclockout($con);
        $data['emplateclockin']=$this->AdminModel->emplateclockin($con);
        $data['emplatebrk']=$this->AdminModel->emplatebrk($con);
        $data['header']=$this->load->view('admin/includes/header','',true);
        $data['sideber']=$this->load->view('admin/includes/sideber','',true);
        $this->load->view('admin/emplate.php',$data);

      }

      public function emplatedatecin()
      {
        $con=$this->input->post('date');
        $getattend=$this->AdminModel->emplateclockin($con);
        $result="";
        foreach ($getattend as $attend)
        {
          $result.="<tr><td>".$attend['name']."</td><td>".$attend['late_time']."</td></tr>";
        }
        echo $result;
      }

      public function empearlyclockout()
      {
        $con=$this->input->post('date');
        $getattend=$this->AdminModel->empearlyclockout($con);
        $result="";
        foreach ($getattend as $attend)
        {
          $result.="<tr><td>".$attend['name']."</td><td>".$attend['early_time']."</td></tr>";
        }
        echo $result;
      }

      public function breaklatedate()
      {
        $con=$this->input->post('date');
        $getattend=$this->AdminModel->emplatebrk($con);
        $result="";
        foreach ($getattend as $attend)
        {
          if($attend['type']==1)
          {
            $break="First Break";
          }
          else if($attend['type']==2)
          {
            $break="Second Break";
          }
          else 
          {
            $break="Third Break";
          }
          $result.="<tr><td>".$attend['name']."</td><td>".$break."</td><td>".$attend['time']."</td></tr>";
        }
        echo $result;
      }
      public function empabsentdate()
      {
        $con=$this->input->post('date');
        $getattend=$this->AdminModel->empabsent($con);
        $result="";
        foreach ($getattend as $attend)
        {
          $result.="<tr><td>".$attend['name']."</td><td>9:00:00</td></tr>";
        }
        echo $result;
      }

      public function getattendence()
      {
        extract($_POST);
        $con=$this->input->post('showdate');
        $getattend=$this->AdminModel->empclock($con);
        $result="";
        foreach ($getattend as $key)
        {
          $result.="<tr><td>".$key['name']."</td><td>".$key['clockin']."</td><td>".$key['clockout']."</td></tr>";
        }
        //$attend="<tr><td>".$getattend['name']."</td><td>""</td><td>""</td></tr>";
        print_r($result);
      }

      public function getfbreak()
      {
        extract($_POST);
        $con=$this->input->post('showdate');
        $getafbreak=$this->AdminModel->firstbreak($con);
        $result="";
        foreach ($getafbreak as $key)
        {
          $time1 = $key['starttime'];
              $time2 = $key['endtime'];

              list($hours, $minutes, $seconds) = explode(':', $time1);
              $startTimestamp = mktime($hours, $minutes, $seconds);
 
              list($hours, $minutes, $seconds) = explode(':', $time2);
              $endTimestamp = mktime($hours, $minutes, $seconds);

              $letseconds = $endTimestamp - $startTimestamp;
              $sec=($letseconds % 60);
               if($sec<10)
               {
               $sec="0".$sec;
               }
              $minutes = ($letseconds / 60) % 60;
                if($minutes<10)
               {
               $minutes="0".$minutes;
               }
              $hours = floor($letseconds / (60 * 60));
          $result.="<tr><td>".$key['name']."</td><td>".$hours.":".$minutes.":".$sec."</td></tr>";
        }
        //$attend="<tr><td>".$getattend['name']."</td><td>""</td><td>""</td></tr>";
        print_r($result);

      }

      public function getsbreak()
      {
        extract($_POST);
        $con=$this->input->post('showdate');
        $getafbreak=$this->AdminModel->secondbreak($con);
        $result="";
        foreach ($getafbreak as $key)
        {
          $time1 = $key['starttime'];
              $time2 = $key['endtime'];

              list($hours, $minutes, $seconds) = explode(':', $time1);
              $startTimestamp = mktime($hours, $minutes, $seconds);
 
              list($hours, $minutes, $seconds) = explode(':', $time2);
              $endTimestamp = mktime($hours, $minutes, $seconds);

              $letseconds = $endTimestamp - $startTimestamp;
              $sec=($letseconds % 60);
               if($sec<10)
               {
               $sec="0".$sec;
               }
              $minutes = ($letseconds / 60) % 60;
                if($minutes<10)
               {
               $minutes="0".$minutes;
               }
              $hours = floor($letseconds / (60 * 60));
          $result.="<tr><td>".$key['name']."</td><td>".$hours.":".$minutes.":".$sec."</td></tr>";
        }
        //$attend="<tr><td>".$getattend['name']."</td><td>""</td><td>""</td></tr>";
        print_r($result);

      }

       public function getlbreak()
      {
        extract($_POST);
        $con=$this->input->post('showdate');
        $getafbreak=$this->AdminModel->thirdbreak($con);
        $result="";
        foreach ($getafbreak as $key)
        {
             $time1 = $key['starttime'];
              $time2 = $key['endtime'];

              list($hours, $minutes, $seconds) = explode(':', $time1);
              $startTimestamp = mktime($hours, $minutes, $seconds);
 
              list($hours, $minutes, $seconds) = explode(':', $time2);
              $endTimestamp = mktime($hours, $minutes, $seconds);

              $letseconds = $endTimestamp - $startTimestamp;
              $sec=($letseconds % 60);
               if($sec<10)
               {
               $sec="0".$sec;
               }
              $minutes = ($letseconds / 60) % 60;
                if($minutes<10)
               {
               $minutes="0".$minutes;
               }
              $hours = floor($letseconds / (60 * 60));
          $result.="<tr><td>".$key['name']."</td><td>".$hours.":".$minutes.":".$sec."</td></tr>";
        }
        //$attend="<tr><td>".$getattend['name']."</td><td>""</td><td>""</td></tr>";
        print_r($result);

      }

      public function Fnsingleprint()
      {
        extract($_POST);
        $result='';
        $Fetch_Info=$this->AdminModel->selectprint($orderid);
          foreach($Fetch_Info as $orders)
          {
            if($orders['ord_emp']=='')
            {
        $result.= '<div class="col-sm-10"   style="border: 2px solid black;" >
                
                      <div align="left"><img src="'.base_url().'images/logo.png" alt="" width="200px" /></div><div align="right" style="padding-right:10px;">Shop Name:<span id="empshop"> '.$orders['shopname'].'</span></div>
                      </br>
                      <div style="padding-left:10px;"> Employee Name:<span id="empname"> '.$orders['name'].'</span></div>
                      <br>
                      <br>
                      <div style="padding-left:10px;"> Lunch Items:<span id="emplunch"> '.$orders['items'].'</span></div><div align="right" style="padding-right:10px;">Total Cost:<span id="empcost"> '.$orders['cost'].'</span></div>
                  
                     <div style="padding-left:10px;">  Date:<span id="empdate"> '.date('d/m/Y',strtotime($orders['date'])).'</span></div>
                      </br>
                      </br>
                      </br>

                      <div align="right"> Authorized Signature...............................................<img src="'.base_url().'images/logo.png" alt="" width="50px"  /></div>
               
                       </div>';
                   }
                   else
                   {

                    $n_exp=explode(',',$orders['ord_emp']);
            $n_arr=array();
            $str='';
            /*for($i=0; $i<count($n_exp);$i++)
            {
                  $name=$this->AdminModel->FngetName($n_exp[$i]);
                array_push($n_arr,$name['name']);
              
            }*/
            $str=implode(',',$n_arr);
            $cost=count($n_exp)*$orders['cost'];
                    $result.= '<div class="col-sm-10"   style="border: 2px solid black;" >
                
                      <div align="left"><img src="'.base_url().'images/logo.png" alt="" width="200px" /></div><div align="right" style="padding-right:10px;">Shop Name:<span id="empshop"> '.$orders['shopname'].'</span></div>
                      </br>
                      <div style="padding-left:10px;"> Employee Name:<span id="empname"> '.$str.'</span></div>
                      <br>
                      <br>
                      <div style="padding-left:10px;"> Lunch Items:<span id="emplunch"> '.$orders['items'].'</span></div><div align="right" style="padding-right:10px;">Per head Cost:<span id="empcost"> '.$orders['cost'].'</span></div><div align="right" style="padding-right:10px;">Total Cost:<span id="empcost"> '.$cost.'</span></div>
                  
                     <div style="padding-left:10px;">  Date:<span id="empdate"> '.date('d/m/Y',strtotime($orders['date'])).'</span></div>
                      </br>
                      </br>
                      </br>

                      <div align="right"> Authorized Signature...............................................<img src="'.base_url().'images/logo.png" alt="" width="50px"  /></div>
               
                       </div>';
                   }

            }
            
            echo $result;
    }

    public function FnfetchAllOrder()
    {


      $data['date']=$this->input->post('date');
        $data['status']=0;


      $all_order=$this->AdminModel->FnAllorder($data);

      //echo '<pre>';print_r($all_order);
      $result='';
      if(!empty($all_order))
      {
      foreach($all_order as $orders)
      {
        if($orders['ord_emp']=='')
        {
         $result.= '<div class="col-sm-10"   style="border: 2px solid black;" >
                
                      <div align="left"><img src="'.base_url().'images/logo.png" alt="" width="200px" /></div><div align="right" style="padding-right:10px;">Shop Name:<span id="empshop"> '.$orders['shopname'].'</span></div>
                      </br>
                      <div style="padding-left:10px;"> Employee Name:<span id="empname"> '.$orders['name'].'</span></div>
                    
                      <br>
                      <br>
                      <div style="padding-left:10px;"> Lunch Items:<span id="emplunch"> '.$orders['items'].'</span></div><div align="right" style="padding-right:10px;">Total Cost:<span id="empcost"> '.$orders['cost'].'</span></div>
                  
                      <div style="padding-left:10px;"> Date:<span id="empdate"> '.date('d/m/Y',strtotime($orders['date'])).'</span></div>
                      </br>
                      </br>
                      </br>

                      <div align="right"> Authorized Signature...............................................<img src="'.base_url().'images/logo.png" alt="" width="50px"  /></div>
               
                       </div>';

                 $result.= '&nbsp;&nbsp;<div style="margin-top:5px;"></div>';
             }
             else
             {
              if(strpos($orders['ord_emp'], ',') !== false)
              {
                 $n_exp=explode(',',$orders['ord_emp']);
            $n_arr=array();
            $str='';
            for($i=0; $i<count($n_exp);$i++)
            {
                  $name=$this->AdminModel->FngetName($n_exp[$i]);
                array_push($n_arr,$name['name']);
              
            }
            $str=implode(',',$n_arr);
            $cost=count($n_exp)*$orders['cost'];
                $result.= '<div class="col-sm-10"   style="border: 2px solid black;" >
                
                      <div align="left"><img src="'.base_url().'images/logo.png" alt="" width="200px" /></div><div align="right" style="padding-right:10px;">Shop Name:<span id="empshop"> '.$orders['shopname'].'</span></div>
                      </br>
                      <div style="padding-left:10px;"> Employee Name:<span id="empname"> '.$str.'</span></div>
                    
                      <br>
                      <br>
                      <div style="padding-left:10px;"> Lunch Items:<span id="emplunch"> '.$orders['items'].'</span></div><div align="right" style="padding-right:10px;">Per Head Cost:<span id="empcost"> '.$orders['cost'].'</span></div><div align="right" style="padding-right:10px;">Total Cost:<span id="empcost"> '.$cost.'</span></div>
                  
                      <div style="padding-left:10px;"> Date:<span id="empdate"> '.date('d/m/Y',strtotime($orders['date'])).'</span></div>
                      </br>
                      </br>
                      </br>

                      <div align="right"> Authorized Signature...............................................<img src="'.base_url().'images/logo.png" alt="" width="50px"  /></div>
               
                       </div>';

                 $result.= '&nbsp;&nbsp;<div style="margin-top:5px;"></div>';
              }
          
             }

      }
        //$result.='<a id="printfinalAll" class="btn btn-danger btn-md glyphicon glyphicon-print" >Print</a>';
        }
      echo $result;

    }


    public function selectprint()
    {
      extract($_POST);
      //$data=$orderid;
      //print_r($orderid);
      $result='';
      
      for($i=0;$i<count($orderid);$i++)
      {
      $all_order=$this->AdminModel->selectprint($orderid[$i]);
        if(!empty($all_order))
      {

      foreach($all_order as $orders)
      {

        if($orders['ord_emp']!='' && strpos($orders['ord_emp'], ',') !== false)
        {
          
        
                    $n_exp=explode(',',$orders['ord_emp']);
            $n_arr=array();
            $str1='';
            for($k=0; $k<count($n_exp);$k++)
            {
                  $name=$this->AdminModel->FngetName($n_exp[$k]);
                array_push($n_arr,$name['name']);
              
            }
            $str1=implode(',',$n_arr);
            $cost=count($n_exp)*$orders['cost'];
            
            
                 $result.= '<div class="col-sm-10" style="border: 2px solid black;" >
                
                      <div align="left"><img src="'.base_url().'images/logo.png" alt="" width="200px" /></div><div align="right" style="padding-right:10px;">Shop Name:<span id="empshop"> '.$orders['shopname'].'</span></div>
                      </br>
                      <div style="padding-left:10px;"> Employee Name:<span id="empname"> '.$str1.'</span></div>
                    
                      <br>
                      <br>
                      <div style="padding-left:10px;"> Lunch Items:<span id="emplunch"> '.$orders['items'].'</span></div><div align="right" style="padding-right:10px;">Per Head Cost:<span id="empcost"> '.$orders['cost'].'</span></div><div align="right" style="padding-right:10px;">Total Cost:<span id="empcost"> '.$cost.'</span></div>
                  
                      <div style="padding-left:10px;"> Date:<span id="empdate"> '.date('d/m/Y',strtotime($orders['date'])).'</span></div>
                      </br>
                      </br>
                      </br>

                      <div align="right"> Authorized Signature...............................................<img src="'.base_url().'application/views/img/logo.png" alt="" width="50px"  /></div>
               
                       </div>';

                  $result.= '&nbsp;&nbsp;<div style="margin-top:5px;"></div>';
          

             }
             else
             {
              
                  $result.= '<div class="col-sm-10"   style="border: 2px solid black;" >
                
                      <div align="left"><img src="'.base_url().'images/logo.png" alt="" width="200px" style="-webkit-print-color-adjust: exact;"/></div><div align="right" style="padding-right:10px;">Shop Name:<span id="empshop"> '.$orders['shopname'].'</span></div>
                      </br>
                      <div style="padding-left:10px;"> Employee Name:<span id="empname"> '.$orders['name'].'</span></div>
                      <br>
                      <br>
                      <div style="padding-left:10px;">  Lunch Items:<span id="emplunch"> '.$orders['items'].'</span></div><div align="right" style="padding-right:10px;">Total Cost:<span id="empcost"> '.$orders['cost'].'</span></div>
                  
                     <div style="padding-left:10px;">  Date:<span id="empdate"> '.date('d/m/Y',strtotime($orders['date'])).'</span></div>
                      </br>
                      </br>
                      </br>

                      <div align="right"> Authorized Signature...............................................<img src="'.base_url().'images/logo.png" alt="" width="50px"  /></div>
               
                       </div>';
                                          
                 $result.= '&nbsp;&nbsp;<div style="margin-top:5px;"></div>';
                
          

              
             }

      }
        //$result.='<a id="printfinalAll" class="btn btn-danger btn-md glyphicon glyphicon-print" >Print</a>';
        }

      }
        echo $result;
       
    
    }

}
?>