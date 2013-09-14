<!DOCTYPE html>
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="Inventory app">
   <meta name="keywords" content="Inventory Ebretic acacha">
   <meta name="author" content="Ebretic Enginyeria SL. Sergi Tur Badenas i Josep Llaó Angelats">

   <title><?php echo lang('inventory') . " " . $institution_name ;?> . Estat:
   <?php 
   if (isset($grocerycrudstate)) { 
    echo $grocerycrudstate_text;
   }
   ?>
   </title>

<!-- ICONS FOR APPLE: TODO -->
   <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
   <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
   <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
    
<!-- FAVICON: TODO-->    
    <link rel="shortcut icon" href="assets/icon/favicon.png">
   
 
<?php if (isset($css_files)): ?>
 <!-- CSS GROCERY CRUD-->     
 <?php foreach($css_files as $file): ?>
  <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
 <?php endforeach; ?>
<?php endif; ?>
       
<?php if (isset($js_files)): ?>
 <!-- JS GROCERY CRUD -->
 <?php foreach($js_files as $file): ?>
  <script src="<?php echo $file; ?>"></script>
 <?php endforeach; ?>
<?php endif; ?>

<!-- CSS PROPIS -->

<?php foreach($inventory_css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>

<!-- JS PROPIS userinfo-->
<?php if (isset($inventory_userinfo_js_files)): ?>
<?php foreach($inventory_userinfo_js_files as $file): ?>
    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<?php endif; ?>

<!-- JS PROPIS -->
<?php foreach($inventory_js_files as $file): ?>
    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>

<style type="text/css">
     body {
        padding-top: 60px;
        padding-bottom: 60px;
		font-family: Arial;
                font-size: 14px;
               
		min-height:900
		
     }
    
.navbar-text img {
  max-height:30px;
  width:auto;
  vertical-align:middle;
}
		
</style>

</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top">
 <div class="navbar-inner">
   <div class="container">
    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="brand" href="#"> <i class="icon-home"> </i><?php echo lang('inventory');?></a>
  
    <div class="nav-collapse collapse">
     
            
     <ul class="nav">
      <li class="active"> <a href='<?php echo site_url('main/inventory_object')?>'><?php echo lang('inventory');?></a> </li>
    <!--    
       <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-toogle="tab"><?php echo lang('devices');?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
         <li><a href='<?php echo site_url('main/devices')?>'><?php echo lang('computers');?></a></li>
         <li><a href='<?php echo site_url('main/todo')?>'><?php echo lang('others');?></a></li>
        </ul>
       </li>
     -->  
      <?php if ($show_maintenace_menu): ?>                   
 
      <li class="dropdown">
       <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-toogle="tab"><?php echo lang('maintenances');?> <b class="caret"></b></a>
       <ul class="dropdown-menu">
		 <li><a href='<?php echo site_url('main/externalIDType')?>'><?php echo lang('externalid_menu');?></a></li>
         <li><a href='<?php echo site_url('main/organizational_unit')?>'><?php echo lang('organizationalunit_menu');?></a></li>
         <li><a href='<?php echo site_url('main/location')?>'><?php echo lang('location_menu');?></a></li>
         <li><a href='<?php echo site_url('main/material')?>'><?php echo lang('material_menu');?></a></li>
         <li><a href='<?php echo site_url('main/brand')?>'><?php echo lang('brand_menu');?></a></li>
         <li><a href='<?php echo site_url('main/model')?>'><?php echo lang('model_menu');?></a></li>
         <li><a href='<?php echo site_url('main/provider')?>'><?php echo lang('provider_menu');?></a></li>    
         <li><a href='<?php echo site_url('main/money_source')?>'><?php echo lang('money_source_menu');?></a></li>              
         <li><a href='<?php echo site_url('main/barcode')?>'><?php echo lang('barcode_menu');?></a></li>
       </ul>                                                                                                                                                                                                                                                                                                                                      
      </li>
      <?php endif; ?>

      <li class="dropdown">
       <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo lang('reports');?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href='<?php echo site_url('reports')?>'><?php echo lang('global_reports');?></a></li>
          <li><a href='<?php echo site_url('reports/todo1')?>'><?php echo lang('reports_by_organizationalunit');?></a></li>
          <li><a href='<?php echo site_url('reports/todo2')?>'>Informes per ...</a></li>                                            
        </ul>
      </li>
      
      
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo lang('managment');?> <b class="caret"></b></a>
          <ul class="dropdown-menu">
			  <?php if ($show_managment_menu): ?>
            <li><a href='<?php echo site_url('main/users')?>'><?php echo lang('users');?></a></li>
            <li><a href='<?php echo site_url('main/groups')?>'><?php echo lang('groups');?></a></li>
			  <?php endif; ?>
            <li><a href='<?php echo site_url('main/preferences')?>'><?php echo lang('preferences');?></a></li>                                            
          </ul>
      </li>
      

                                                                                              
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo lang('language');?> <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="<?=base_url()?>index.php/main/change_language/catalan"><?php echo lang('catalan');?></a></li>
            <li><a href="<?=base_url()?>index.php/main/change_language/spanish"><?php echo lang('spanish');?></a></li>
            <li><a href="<?=base_url()?>index.php/main/change_language/english"><?php echo lang('english');?></a></li>
          </ul>
      </li>
     </ul>               
   </div>
   <div class="pull-right navbar-text">
	   (<?php echo lang('language')." : ".lang($this->session->userdata('current_language'));?>)
     <img src="http://placehold.it/30x30">
      <a href="<?=base_url()?>index.php/main/user_info" style="color:grey"><?php echo $this->session->userdata('username');?></a>      
      <a href="<?=base_url()?>index.php/inventory_auth/logout"><?php echo lang('CloseSession');?></a>              
   </div>
  </div>
 </div>
</div>

<?php 

if (isset($debug)) {
		print_r($session_data);
}
?>

<?php if (!isset($not_show_header2)): ?>

<script>

$(document).ready(function(){

$("#accordion").accordion();
$("#accordion").accordion( "option", "collapsible", true );
$("#accordion").accordion("option", "active", false );
$("#accordion").accordion({ heightStyle: "fill" });
$(".chosen").chosen().chosenSortable();

/* Below Code Matches current object's (i.e. option) value with the array values */
/* Returns -1 if match not found */

<?php

$current_table_current_fields_to_show= $current_table_name . "_current_fields_to_show";
$js_array = json_encode($this->session->userdata($current_table_current_fields_to_show));
echo "var selectedFields = ". $js_array . ";\n";

$js1_array = json_encode($fields_in_table);
echo "var allFields = ". $js1_array . ";\n";

?>

$('option').each( 
     function() {
           if (jQuery.inArray(this.value, selectedFields) !=-1) {
             $(this).attr('selected', true);
             $('select').trigger('liszt:updated');
           }
     });
   
$('.reset').click(function(){
    $('option').prop('selected', false);
    $('select').trigger('liszt:updated');   
    
<?php
$current_table_current_fields_to_show= $current_table_name . "_current_fields_to_show";
$js_array2 = json_encode($this->session->userdata($current_table_current_fields_to_show));
echo "var selectedFields1 = ". $js_array2 . ";\n";     
?>
    
    $('option').each( 
     function() {
           if (jQuery.inArray(this.value, selectedFields1) !=-1) {
             $(this).attr('selected', true);
             $('select').trigger('liszt:updated');
           }
     });
});         


$('.select').click(function(){
    $('option').prop('selected', true);
    $('select').trigger('liszt:updated');
});

$('.deselect').click(function(){
    $('option').prop('selected', false);
    $('select').trigger('liszt:updated');
});
});


</script>

<style type="text/css">
.chzn-container .chzn-results {
height: 150px;
font-size: xx-small;
}

</style>

<!-- JQUERY UI ACORDION -->

<div id="accordion">
 <h3 style="font-size: x-small"><?php echo lang('fields_tho_show'); ?></h3>
  <div style="font-size: xx-small; vertical-align:middle;">
   <form action="<?=base_url()?>index.php/main/update_displayed_fields" method="post" accept-charset="utf-8">	
   
   <select id="table_fields" name="current_selected_table_fields[]" data-placeholder="<?php echo lang('choose_fields'); ?>" style="width:100%" class="chosen chzn-sortable" multiple>
    <?php foreach($sorted_fields_in_table as $field): ?>
      <?php if ($field==$table_id): ?>                   
          <option value="<?php echo $field; ?>">Id</option>
      <?php else: ?>  
		  <option value="<?php echo $field; ?>"><?php echo lang($field); ?></option>     
      <?php endif; ?>  
  
    <?php endforeach; ?>
   </select>
   <br/>
   <button class="reset" type="button"><?php echo lang('reset'); ?></button>
   <button class="select" type="button"><?php echo lang('select_all'); ?></button>
   <button class="deselect" type="button"><?php echo lang('unselect_all'); ?></button>        
   <button class="apply"><?php echo lang('apply'); ?></button>      
   <input type="hidden" name="table_name" value="<?php echo $current_table_name?>">
   </form>
   <div style='height:100px;'></div>
  </div> 

<?php if ($show_organizational_units): ?>                   
<?php if (@$organizational_units): ?>                   
 <h3 style="font-size: x-small"><?php echo lang('Filter by organizational units'); ?></h3>
  <div style="font-size: xx-small;">
   <form action="<?=base_url()?>index.php/main/update_current_organizational_unit" method="post" accept-charset="utf-8">	
   <select id="organizational_units" data-placeholder="<?php echo lang('choose_organization_unit'); ?>" style="width:500px" class="chosen" name="current_selected_organizational_unit">
    <option value="all" ><?php echo lang('all_organizational_units'); ?></option>
    <?php foreach($organizational_units as $row): ?>
	  <?php if ( $current_organizational_unit == $row['organizational_unitId']): ?>                   
	    <option value="<?php echo $row['organizational_unitId']; ?>" selected="selected"><?php echo $row['name']; ?></option>
	  <?php else: ?>
		<option value="<?php echo $row['organizational_unitId']; ?>" ><?php echo $row['name']; ?></option>
	  <?php endif; ?>  	
    <?php endforeach; ?>
   </select>
   <br/><button class="apply"><?php echo lang('apply'); ?></button>    
   </form>
  <div style='height:100px;'></div>
 </div>
<?php endif; ?>  
<?php endif; ?>
</div> 

</div>
<?php endif; ?>

<!-- End of header-->
