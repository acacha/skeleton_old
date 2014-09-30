<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
      <title><?php echo $login_appname . ". " . $login_entity;?></title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="Forgot password. <?php echo $login_appname;?>">
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
 
 <?php echo form_open(base_url($forgot_password_submit_url.$identity), array('class' => 'form-signin', 'style' => 'max-width: 600px' )); ?>
  <h3><?php echo lang('forgot_password_heading');?></h3>
  <p><?php echo sprintf(lang('forgot_password_subheading'),strtolower(lang($identity)));?></p>
      <p>
       <select id="realms" class="selectpicker" name="realm">
  		  <?php foreach( (array) $realms as $realm): ?>
		   <?php if( $realm == $default_realm): ?>
            <option value="<?php echo $realm; ?>" selected="selected"><?php echo $realm; ?></option>
           <?php else: ?> 
            <option value="<?php echo $realm; ?>" ><?php echo $realm; ?></option>
           <?php endif; ?> 
          <?php endforeach; ?>	
       </select>
      </p>
      <p>
          <input id="<?php echo $identity;?>" name="<?php echo $identity;?>" class="input-block-level" type="text" placeholder="<?php $lang_string="forgot_password_" . $identity . "_identity_label" ;echo lang($lang_string);?>">
      </p>

      <p><button class="btn btn-large btn-primary" type="submit"><?php echo lang('forgot_password_submit_btn');?></button></p>
      
      <?php echo sprintf(lang("do_you_not_remember_your_identity"),strtolower(lang($identity)));?> 
      <br/>
	  <a href="<?php echo base_url('index.php/skeleton_auth/ebre_escool_auth/forgot_password_' . $alternative_identity)?>">
		   <?php echo sprintf(lang("try_with_your_identity"),strtolower(lang($alternative_identity)));?>
       </a>
             
 <?php echo form_close();?>
 
 <center><p><a href="<?php echo base_url('');?>"> < <?php echo lang('come_back');?></a></p></center>

  <br/>
	   <?php include("auth_footer.html"); ?>      
 </center>
 </div>
</body>
</html>
