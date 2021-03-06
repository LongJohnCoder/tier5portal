<!DOCTYPE html>
<html lang="en">
<head>
	
	<?php include 'welcomeheader.php';?>
      <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Tier5</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

 <!--  For clock  --> 

<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
// Create two variable with the names of the months and days in an array
var monthNames = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ]; 
var dayNames= ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]

// Create a newDate() object
var newDate = new Date();
// Extract the current date from Date object
newDate.setDate(newDate.getDate());
// Output the day, date, month and year    
$('#Date').html(dayNames[newDate.getDay()] + " " + newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());

setInterval( function() {
    // Create a newDate() object and extract the seconds of the current time on the visitor's
    var seconds = new Date().getSeconds();
    // Add a leading zero to seconds value
    $("#sec").html(( seconds < 10 ? "0" : "" ) + seconds);
    },1000);
    
setInterval( function() {
    // Create a newDate() object and extract the minutes of the current time on the visitor's
    var minutes = new Date().getMinutes();
    // Add a leading zero to the minutes value
    $("#min").html(( minutes < 10 ? "0" : "" ) + minutes);
    },1000);
    
setInterval( function() {
    // Create a newDate() object and extract the hours of the current time on the visitor's
    var hours = new Date().getHours();
    // Add a leading zero to the hours value
    $("#hours").html(( hours < 10 ? "0" : "" ) + hours);
    }, 1000);
    
}); 
</script>

<!--  For clock  --> 
	
</head>
<body>

  <header>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                Welcome Buddy!
            </div>
            <div class="col-md-6">
                <div class="clock">
<div id="Date"></div>

<ul>
    <li id="hours"> </li>
    <li id="point">:</li>
    <li id="min"> </li>
    <li id="point">:</li>
    <li id="sec"> </li>
</ul>

</div>
            </div>    
        </div>    
    </div>  
    </header>  

    <div class="bodypart">
        <div class="container">
         <div class="col-md-12">   
         <div class="row">   
        <div class="logo"><img src="images/tier5.png" alt="img"></div>
        <ul>
            <li data-toggle="modal" data-target="#admin-login"><a href="#">ADMIN LOGIN</a></li>
          <!--   <li data-toggle="modal" data-target="#hr-login"><a href="#">HR LOGIN</a></li> -->
            <li data-toggle="modal" data-target="#employee-login"><a href="#">EMPLOYEE LOGIN</a></li>
        </ul>    
    </div> 

</div>
    </div>


 

           <!-- Modal -->
<div id="admin-login" class="modal fade" role="dialog">
  <div class="login-modal"> <!-- modal-sm -->

    <!-- Modal content-->
    <div class="modal-content">
  
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4>Admin Login</h4>

        <form role="form" method="post" action="admin_control/Admin" novalidate="novalidate">
<div class="form-group">
<input type="text" placeholder="user_name" class="form-control input-lg required" id="adminid" name="adminid" aria-required="true">
</div>
<div class="form-group">
<input type="password" placeholder="Password" class="form-control input-lg required" id="adminpass" name="adminpass" aria-required="true">
</div>
<div class="form-group">
<input type="submit" class="btn btn-lg btn-block login-btn" value="Login">
</div>
</form>

      </div>
     
    </div>

  </div>
</div>





           <!-- Modal -->
<div id="hr-login" class="modal fade" role="dialog">
  <div class="login-modal"> <!-- modal-sm -->

    <!-- Modal content-->
    <div class="modal-content">
  
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4>HR Login</h4>
        <form id="emp_login" method="post" action="http://portal.tier5.in/Dashboard/employeelogin" novalidate="novalidate">
<div class="form-group">
<input type="text" placeholder="user_name" class="form-control input-lg required" name="name" id="employeename" aria-required="true">
</div>
<div class="form-group">
<input type="password" placeholder="Password" class="form-control input-lg required" name="password" password="employeepassword" aria-required="true">
</div>
<div class="form-group">
<input type="submit" class="btn btn-lg btn-block login-btn" id="employeesign" value="Login">
</div>
</form>
  

      </div>
     
    </div>

  </div>
</div>



           <!-- Modal -->
<div id="employee-login" class="modal fade" role="dialog">
  <div class="login-modal"> <!-- modal-sm -->

    <!-- Modal content-->
    <div class="modal-content">
  
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4>Employee Login</h4>
        <form role="form" method="post" action="employee_control/Employee">
<div class="form-group">
<input type="text" placeholder="user_name" class="form-control input-lg required" id="empid" name="empid" aria-required="true">
</div>
<div class="form-group">
<input type="password" placeholder="Password" class="form-control input-lg required" id="emppass" name="emppass" aria-required="true">
</div>
<div class="form-group">
<input type="submit" class="btn btn-lg btn-block login-btn" value="Login">
</div>
</form>


  

      </div>
     
    </div>

  </div>
</div>




    </div>  
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    Copyright © 2015 Tier5. All Rights Reserved
                </div>
            </div>
    </div>
    </footer> 


  <link rel="stylesheet" href="css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>




</body>
</html>