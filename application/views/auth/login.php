<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php

$bootstrap_combined_url = "http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css";
$jquery_minified_url = "http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js";
$bootstrap_minified_url = "http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js";

if (defined('ENVIRONMENT') && ENVIRONMENT=="development") {
  $bootstrap_combined_url = base_url('assets/skeleton/css/bootstrap-combined.min.css');
  $jquery_minified_url = base_url('assets/skeleton/js/jquery.min.js');
  $bootstrap_minified_url = base_url('assets/skeleton/js/bootstrap.min.js');
}

?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
      <title><?php echo $login_appname . ". " . $login_entity;?></title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="Login form. <?php echo $login_appname;?>">
      <meta name="author" content="<?php echo $copyright_authors_text; ?>">
      
      <!-- FAVICON-->    
	  <link rel="shortcut icon" href="assets/icon/favicon.png">
	  
   <!-- CSS PROPIS -->

       <link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/skeleton/css/bootstrap-select.min.css')?>"/>

       
       <link type="text/css" rel="stylesheet" href="<?php echo $bootstrap_combined_url;?>" /> 
   

   <!-- JS PROPIS -->
       <script src="<?php echo $jquery_minified_url;?>"></script>
       <script src="<?php echo $bootstrap_minified_url;?>"></script>
       <script src="<?php echo base_url('assets/skeleton/js/bootstrap-select.min.js')?>"></script>
   
   <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

   </style>
   
   <script type="text/javascript" id="bootstrap_select">
        $(window).on('load', function () {
            $('.selectpicker').selectpicker();
		});
   </script>


  </head>

<body>
	
<div class="container">	

     <center><div id="maintenance-mode-message" class="text-error"></div></center>
     
     <center><div class="text-error"><?php echo $message;?></div></center>
     
<?php if ($this->session->flashdata('spam_alert')): ?>
 <center>
	<div class="alert">
     <button type="button" class="close" data-dismiss="alert">&times;</button>
     <strong><?php echo lang('Atention');?>! </strong> <?php echo lang('SPAM_Message_Part1');?><br/>
     <?php echo lang('SPAM_Message_Part2');?>
    </div>
 </center>
<?php endif; ?>
     
     <center><h1><?php echo $login_appname . ". " . $login_entity;?></h1></center>
     <br>
       
       <?php 
       echo form_open($login_url .$redirect, array('id' => 'loginform', 'class' => 'form-signin' )); ?>
        
        <h3 class="form-signin-heading"><?php echo lang('login-form-greetings');?></h3>

         <input id="identity" class="input-block-level" type="text" placeholder="<?php echo lang('User');?>" name="identity">
         <input id="password" class="input-block-level" type="password" placeholder="<?php echo lang('Password');?>" name="password">
         
         <select id="realms" class="selectpicker" name="realm">
  		  <?php foreach( (array) $realms as $realm): ?>
		   <?php if( $realm == $default_realm): ?>
            <option value="<?php echo $realm; ?>" selected="selected"><?php echo $realm; ?></option>
           <?php else: ?> 
            <option value="<?php echo $realm; ?>" ><?php echo $realm; ?></option>
           <?php endif; ?> 
          <?php endforeach; ?>	
         </select>
         
         <!--
         <br/>
         
         <label class="checkbox">
           <input type="checkbox" value="remember-me"> <?php echo lang('remember');?> 
         </label>
         -->

        <br/>
        <button class="btn btn-large btn-primary" type="submit"><?php echo lang('Login');?></button>
       
       <?php echo form_close(); ?>
       <!--<center><p><a href="<?php echo $register_url;?>"><?php echo lang('Register');?></a></p></center>-->
       <center><p><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></p></center>
       <br/>
       
    	   <?php include("auth_footer.html"); ?>      
       
</div>

</body>

</html>
