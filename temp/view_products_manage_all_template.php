<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
       
    
   $woocommerce_rest_api_keys = get_option( 'ContenteManager_Settings' );
    $wooconsumerkey = $woocommerce_rest_api_keys['ContentManager']['wooconsumerkey'];
    $wooseceretkey = $woocommerce_rest_api_keys['ContentManager']['wooseceretkey'];
    include 'cm_header.php';
    include 'cm_left_menu_bar.php';
if(!empty($wooconsumerkey) && !empty($wooseceretkey)){
    
      ?>


  <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Manage Products</h3>
                           
                        </div>
                    </div>
                </div>
            </header>

            <div class="box-typical box-typical-padding">
                <p>
                
              This is a list of all the products currently available for your users to purchase. Here you can delete existing products or create new product. 
                </p>

                <h5 class="m-t-lg with-border"></h5>
                 <div class="form-group row">
                                 
                                    <div class="col-sm-6" >
                                            <a class="btn btn-lg mycustomwidth btn-success" href="/add-new-product/">Add New Product</a>
                                        
                                        
                                    </div>
                                </div>
                <div class="card-block" style='margin-left: -24px;'>
                        
                    <table id="manageproduct" class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%">
                              
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
                            <h3>Product</h3>
                           
                        </div>
                    </div>
                </div>
            </header>
            

            <div class="box-typical box-typical-padding" >
                <div class="form-group row">
                
                    <p class="col-sm-12 "><strong>Shop is not configured for this site. Please contact ExpoGenie.</strong></p>
               
                </div>
            </div>
        </div>
    </div>

    <?php }include 'cm_footer.php'; ?>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/manage-products.js?v=2.17"></script>
   <?php }else{
       
       $redirect = get_site_url();
       wp_redirect( $redirect );exit;
   
   }
   ?>