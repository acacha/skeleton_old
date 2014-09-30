<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
      <title><?php echo $login_appname . ". " . $login_entity;?></title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="Change password form. <?php echo $login_appname;?>">
      <meta name="author" content="<?php echo $copyright_authors_text; ?>">      
      
      <!-- CSS PROPIS -->

       <link type="text/css" rel="stylesheet" href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" />
   

   <!-- JS PROPIS -->
       <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
       <script src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
      
  </head>    
  
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

<body>
 <div class="container">	
 
 <center><div id="maintenance-mode-message" class="text-error"></div></center>
 
 <center><h1><?php echo $login_appname . ". " . $login_entity;?></h1></center>
     <br>

 <center><div class="text-error"><div id="infoMessage"><?php echo $message;?></div></div>
 
 <?php echo form_open($reset_form_submit_url . $code, array('class' => 'form-signin', 'style' => 'max-width: 600px' )); ?>
  <h3><?php echo lang('reset_password_heading');?></h3>
  <p><?php echo lang('introduce_new_password');?></p>
      
      <p>
          <label for="new_password"><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?></label>
          <input type="password" pattern="^.{<?php echo $min_password_length; ?>}.*$" id="new" value="" name="new"/>
      </p>
      <p>
		  <?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm');?> 
		  <input id="new_confirm" type="password" pattern="^.{<?php echo $min_password_length; ?>}.*$" value="" name="new_confirm">
      </p>
      
      <input id="user_id" type="hidden" value="<?php echo $user_id; ?>" name="user_id">
	 	  
      <?php echo form_hidden($csrf); ?>

      <p><button class="btn btn-large btn-primary" type="submit"><?php echo lang('reset_password_submit_btn');?></button></p>
             
 <?php echo form_close();?>
 
 <center><p><a href="<?php echo base_url('');?>"><?php echo lang('come_back');?></a></p></center>

  <br/>
  	   <?php include("auth_footer.html"); ?>      

 </div>
</body>
</html>
