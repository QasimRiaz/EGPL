<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
       
    include 'cm_header.php';
    include 'cm_left_menu_bar.php';
    
      ?>


<div class="page-content">
        
            
				<iframe frameborder="0" scrolling="no" id="adminfloorplaniframe" src="http://demo2017.staging.wpengine.com/floor-plan-2/" width="100%"></iframe>
                  
           
        
</div>


<script>
 jQuery(document).ready(function() {
      console.log(jQuery( "#admindemosettings" ).height());
    var iframeheight = jQuery( "#admindemosettings" ).height();
    jQuery("#adminfloorplaniframe").css("height",iframeheight);
  });
    
 
</script>
       	 <?php   
  
    include 'cm_footer.php';
    
   }else{
       
       $redirect = get_site_url();
       wp_redirect( $redirect );exit;
   
   }
   ?>