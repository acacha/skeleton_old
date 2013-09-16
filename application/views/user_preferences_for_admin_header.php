<div style="height:5px;"></div>
<div id="message-box" class="span12">
 <div class="alert alert-sucess ">
  <a class="close" href="#" data-dismiss="alert"> x </a>
   <?php echo lang('user_preferences_admin message1');?><br/>
   <?php echo lang('user_preferences_admin message2');?>
    <a class="go-to-edit-form" href="<?php echo base_url('index.php/skeleton_main/user_preferences/read') . "/" 
    . $user_preferences_id; ?>">
	 <?php echo lang('here');?>
	</a>
	<br/>
	<?php echo lang('user_preferences_admin message3');?>
	<a class="go-to-edit-form" href="<?php echo base_url('index.php/skeleton_main/user_preferences/edit') . "/"
	. $user_preferences_id; ?>">
	 <?php echo lang('here');?>
	</a>
 </div>
</div>
<div style="clear:both"></div>
