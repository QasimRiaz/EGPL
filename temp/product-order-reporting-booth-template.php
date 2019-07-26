<?php
// Template Name: Bulk Edit Task 
if (current_user_can('administrator') || current_user_can('contentmanager')) {
  
    

    
    
        $order_reportsaved_list = get_option('ContenteManager_Orderreport_settings');
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        
       $woocommerce_rest_api_keys = get_option( 'ContenteManager_Settings' );
       $wooconsumerkey = $woocommerce_rest_api_keys['ContentManager']['wooconsumerkey'];
       $wooseceretkey = $woocommerce_rest_api_keys['ContentManager']['wooseceretkey'];
      
      
    
        $args = array(
         'numberposts' => -1,
         'post_type'   => 'booth_review',
         'post_status'   => 'draft'
       );
 
      $latest_boothlist = get_posts( $args );
     
    
   ?>
    
    


    <?php include 'cm_header.php';
    
    if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    ?>
    <!--    order-reporting jQuery Querybuilder css-->
   
    


    <?php
        }
   include 'cm_left_menu_bar.php';
    if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
        
        if(isset($_GET['orderreport'])){
            
            
            $orderreportload_settings  = $order_reportsaved_list[$_GET['orderreport']];
    ?>
       
        
        <?php } ?>
    <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Review Booth Purchase</h3>

                        </div>
                    </div>
                </div>
            </header>

             <div class="box-typical box-typical-padding">
               
                
                            
                            <table id="boothreview" class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%">
                              
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Order ID</th>
                                        <th>Product Name</th>
                                        <th>Company Name</th>
                                        <th>Email</th>
                                        <th>Booth Number</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                <?php  foreach ($latest_boothlist as $boothOrderIndex=>$boothOrderValue){ 
                                    
                                    
                                        $PostID = $boothOrderValue->ID;
                                        $ProductID = get_post_meta( $PostID, 'porductID', true );
                                        $OrderID = get_post_meta( $PostID, 'orderID', true );
                                        $OrderUserID = get_post_meta( $PostID, 'OrderUserID', true );
                                        $BoothStatus = get_post_meta( $PostID, 'boothStatus', true );
                                        
        
                                        $get_product_detail = wc_get_product( $ProductID );
                                        $get_user_detail = get_user_meta( $OrderUserID );
                                        $companyName =get_user_option('company_name',$OrderUserID);
                                       
                                        
                                        
                                        $userdataEmail = get_userdata($OrderUserID);
                                        if($BoothStatus == 'Pending'){
                                            echo '<tr>
                                                <td><div style="width: 140px !important;"  class="hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><a onclick="approvethisbooth(this)" id="'.$PostID.'" name="'.$OrderID.'" title="" data-toggle="tooltip" data-original-title="Approved"><i class="hi-icon fusion-li-icon fa fa-check-circle-o"></i></a><a onclick="declinethisbooth(this)" id="'.$PostID.'" name="delete-sponsor" data-toggle="tooltip" title="" data-original-title="Declined"><i class="hi-icon fusion-li-icon fa fa-times-circle"></i></a></div></td>
                                                <td>'.$OrderID.'</td>
                                                <td>'.$get_product_detail->name.'</td>
                                                <td>'.$companyName.'</td>
                                                <td>'.$userdataEmail->data->user_email.'</td>
                                                <td>'.$get_product_detail->tax_class.'</td>
                                                </tr>';
                                        }
                                }
                                ?>
                                    
                                    
                                </tbody>
                                
                                
                                
                            </table>
               </div>    



          
        </div>
    </div>
   


    <?php
    }else{?>
    
    <div class="page-content">
        <div class="container-fluid">
            <header class="section-header" id="bulkimport">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Orders Report</h3>
                           
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

    
    <?php
    }
    include 'cm_footer.php';
     if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    ?>
    
   
  
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/booth-product-order-report.js?v=2.1"></script>
    
    
   <?php
     }
} else {
    $redirect = get_site_url();
    wp_redirect($redirect);
    exit;
}
?>