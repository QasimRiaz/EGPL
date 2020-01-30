<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
       
    
   $woocommerce_rest_api_keys = get_option( 'ContenteManager_Settings' );
    $wooconsumerkey = $woocommerce_rest_api_keys['ContentManager']['wooconsumerkey'];
    $wooseceretkey = $woocommerce_rest_api_keys['ContentManager']['wooseceretkey'];
    include 'cm_header.php';
    include 'cm_left_menu_bar.php';
    $site_url  = get_site_url();
if(!empty($wooconsumerkey) && !empty($wooseceretkey)){
    
      ?>


  <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Manage Shop</h3>
                           
                        </div>
                    </div>
                </div>
            </header>

            <div class="box-typical box-typical-padding">
                <p>
               This is where you can manage and create various items that your users can purchase. An "Add-On" is anything you want to make available for purchase for all your users in the Add-On area of the portal.</p>

                <h5 class="m-t-lg with-border"></h5>
                 <div class="form-group row">
                                 
                                    <div class="col-sm-3" >
                                            <a class="btn btn-lg mycustomwidth btn-success" href="<?php echo $site_url;?>/add-new-product/?producttype=addons">Create Add-On</a>
                                        
                                        
                                    </div>
<!--                                    <div class="col-sm-6" >
                                            
                                        <a class="btn btn-lg mycustomwidth btn-success" href="<?php echo $site_url;?>/add-new-product/?producttype=package">Create Package</a>
                                        
                                        
                                    </div>-->
                                  
                                    <div class="col-sm-6"></div>
<!--                                    <div class="col-sm-1" ><label style="font-weight: normal;margin-right: -13px;float: right;margin-top: 7px;">Filter:</label></div>
                                    
                                    <div class="col-sm-2">
                                   
                                        <select style="margin-left: -8px;width: 94%;" class="form-control input-sm" id="filterdropdown" onchange="customefilterapplyontable()">
                                                    <option value="">All</option>
                                                    <option value="Add-ons">Add-ons</option>
                                                    
                                                   
                                        </select> 
                                    </div>-->
                    
                                </div>
                <div class="card-block" style='margin-left: -24px;'>
                    
                    <table  id="manageproduct" class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%">
                              
                    </table>
                </div>
                
                
                
            </div>
        </div>
</div>
 <?php  }else{?>
   <div class="page-content">
        <div class="container-fluid">
            <header class="section-header" id="bulkimport">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Shop Not Enabled</h3>
                           
                        </div>
                    </div>
                </div>
            </header>
            

            <div class="box-typical box-typical-padding" >
                <div class="form-group row">
                
                    <p class="col-sm-12 "><strong>Hi There! It looks like the Shop module is not configured for this event. If you have questions about this, please contact us at support@expo-genie.com. Thank you!  </strong></p>
               
                </div>
            </div>
        </div>
    </div>

    <?php }include 'cm_footer.php'; ?>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/manage-products.js?v=2.27"></script>
   <?php }else{
       
       $redirect = get_site_url();
       wp_redirect( $redirect );exit;
   
   }
   ?>