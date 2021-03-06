<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Tier5</title>
    <link rel=icon href="http://tier5.us/images/favicon.ico">
    <base href="<?php echo base_url();?>">
    <!-- Bootstrap -->

    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- jVectorMap -->
    <link href="css/maps/jquery-jvectormap-2.0.3.css" rel="stylesheet"/>
    
    <!-- jQuery -->
    <script src="vendors/jquery/dist/jquery.min.js"></script>

    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script type="text/javascript" src="js/event.js"></script>
    <!-- Custom Theme Style -->
    <link href="css/custom.css" rel="stylesheet">
    <style>
    .hilighted-text
    {
      font-weight: bold;
      color: red;
      font-style: italic;
    }
    </style>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
             
            </div>

            <div class="clearfix"></div>

            

            <br />

            <!-- sidebar menu -->
            <?php echo $sideber;?>
            <!-- /sidebar menu -->

            
          </div>
        </div>

        <!-- top navigation -->
        <?php echo $header;?>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
        

            <div class="clearfix"></div>

            <div class="row">
             <div class="title_left">
               <?php 
if($this->session->userdata('succ_msg')!=''){?>
                      <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <h4>  <i class="icon fa fa-check"></i> Success!</h4>
                    <?php echo $this->session->userdata('succ_msg');$this->session->set_userdata('succ_msg','');?>
                  </div>

<?php } if($this->session->userdata('err_msg')!=''){ ?>

<div class="alert alert-danger alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <h4><i class="icon fa fa-ban"></i> Sorry!</h4>
                  <?php echo  $this->session->userdata('err_msg');$this->session->set_userdata('err_msg','');?>
                  </div>
<?php }?>
 
              
              </div>

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <h3>Add New Employee</h3>
                  <div class="x_content">
                    <div class="ln_solid"></div>
                          
                   <form class="form-horizontal form-label-left" data-parsley-validate="" id="demo-form2" novalidate="" method="post" action="admin_control/Admin/add_new_employee">

                      <div class="form-group">
                        <label for="name" class="control-label col-md-3 col-sm-3 col-xs-12" >Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" class="col-md-7 col-xs-12" id='name' name='name' required="required">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="last-name" class="control-label col-md-3 col-sm-3 col-xs-12" >Personal Email Id <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" class="form-control col-md-7 col-xs-12 parsley-success" required="required" id='peremail' name='peremail'>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="last-name" class="control-label col-md-3 col-sm-3 col-xs-12" >Address <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea class="form-control col-md-7 col-xs-12"  rows="4" cols="50" id='address' name='address' required="required" ></textarea>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phon-number" >Phone Number<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" class="form-control col-md-7 col-xs-12" id='phno' name='phno' >
                        </div>
                      </div>

                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phon-number" >Alternative Phone Number</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" class="form-control col-md-7 col-xs-12" id='altphno' name='altphno'>
                        </div>
                      </div>

                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Gender<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input type="radio" name="gender" value="Male"> Male<br>
                                  <input type="radio" name="gender" value="Female"> Female<br>
                        </div>
                      </div>


                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Marital Status<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input type="radio" name="marrige" value="Married"> Married<br>
                                  <input type="radio" name="marrige" value="Unmarried"> Unmarried<br>
                                 
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Date Of Birth <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id='dob' name='dob' required="required" class="date-picker form-control col-md-7 col-xs-12 parsley-success"><ul class="parsley-errors-list" ></ul>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Date Of Joining <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id='doj' name='doj' required="required" class="date-picker form-control col-md-7 col-xs-12 parsley-success" id="birthday" data-parsley-id="16"><ul class="parsley-errors-list" id="parsley-id-16"></ul>
                        </div>
                      </div>
                      <br>
                      <br>
                      <br>

                      <h3>Create User </h3>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <label for="uname" class="control-label col-md-3 col-sm-3 col-xs-12">Username <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id='uname' name='uname' class="form-control col-md-7 col-xs-12 parsley-success" required="required" id="first-name" data-parsley-id="5"><ul class="parsley-errors-list" id="parsley-id-5"></ul>
                        </div>
                      </div>

                       <div class="form-group">
                        <label for="uname" class="control-label col-md-3 col-sm-3 col-xs-12">Company Email Id <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id='coemail' name='coemail' class="form-control col-md-7 col-xs-12 parsley-success" required="required" id="first-name" data-parsley-id="5"><ul class="parsley-errors-list" id="parsley-id-5"></ul>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="uname" class="control-label col-md-3 col-sm-3 col-xs-12">Designation <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id='deg' name='deg' class="form-control col-md-7 col-xs-12 parsley-success" required="required" id="first-name" data-parsley-id="5"><ul class="parsley-errors-list" id="parsley-id-5"></ul>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="uname" class="control-label col-md-3 col-sm-3 col-xs-12">Salary <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text"  id='salary' name='salary' class="form-control col-md-7 col-xs-12 parsley-success" required="required" id="first-name" data-parsley-id="5"><ul class="parsley-errors-list" id="parsley-id-5"></ul>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                         <label>Default Password For This User Is <span class="hilighted-text">Tier5</span></label>
                        </div>
                      </div>



                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
              
                          <button class="btn btn-success" type="submit">Submit</button>
                        </div>
                      </div>

                    </form>
                    
                  </div>
                </div>
              </div>


            </div>
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>


    <!-- Bootstrap -->
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="vendors/nprogress/nprogress.js"></script>
    <!-- iCheck -->
    <script src="vendors/iCheck/icheck.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="js/custom.js"></script>
  </body>
</html>