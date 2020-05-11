<?php

if ($_GET['contentManagerRequest'] == "order_report_savefilters") {
    require_once('../../../wp-load.php');

    //echo '<pre>';
    //print_r($_POST);exit;
    $orderreportname = $_POST['orderreportname'];
    $orderreportfilterdata = stripslashes($_POST['orderreportfiltersdata']);
    $showcolumnslist = stripslashes($_POST['showcolumnslist']);
    $ordercolunmtype = $_POST['orderbytype'];
    $ordercolunmname = $_POST['orderbycolname'];
    order_report_savefilters($orderreportname, $orderreportfilterdata, $showcolumnslist, $ordercolunmtype, $ordercolunmname);
} else if ($_GET['contentManagerRequest'] == "order_report_removefilter") {

    require_once('../../../wp-load.php');
    $orderreportname = $_POST['orderreportname'];
    order_report_removefilter($orderreportname);
} else if ($_GET['contentManagerRequest'] == "get_orderreport_detail") {

    require_once('../../../wp-load.php');
    $orderreportname = $_POST['reportname'];
    get_orderreport_detail($orderreportname);
} else if ($_GET['contentManagerRequest'] == "loadorderreport") {

    require_once('../../../wp-load.php');

    loadorderreport();
} else if ($_GET['contentManagerRequest'] == "manageproducts") {

    require_once('../../../wp-load.php');

    manageproducts();
}else if ($_GET['contentManagerRequest'] == "addnewproducts") {

    require_once('../../../wp-load.php');

    addnewproducts($_POST);
   
}else if ($_GET['contentManagerRequest'] == "deleteproduct") {

    require_once('../../../wp-load.php');

    deleteproduct($_POST);
    die();
   
}else if ($_GET['contentManagerRequest'] == "productclone") {

    require_once('../../../wp-load.php');

    productclone($_POST);
   
}else if ($_GET['contentManagerRequest'] == "updateproducts") {

    require_once('../../../wp-load.php');

    updateproducts($_POST);
   
}else if($_GET['contentManagerRequest'] == "uploadproductimage"){
    
    
    require_once('../../../wp-load.php');
    
    uploadproductimage($_POST);
    die();
    
}else if($_GET['contentManagerRequest'] == "bulkproductgenrate"){
    
    
    require_once('../../../wp-load.php');
    
    bulkproductgenrate($_POST);
    die();
    
}else if($_GET['floorplanRequest'] == "autogenerateproducts"){
    
    
    require_once('../../../wp-load.php');
    
    autogenerateproducts();
    die();
    
}else if($_GET['floorplanRequest'] == "updateorderstatus"){
    
    
    require_once('../../../wp-load.php');
    
    updateorderstatus($_POST);
    die();
    
}else if($_GET['floorplanRequest'] == "getOrderProductsdetails"){
    
    
    require_once('../../../wp-load.php');
    
    
  
    getOrderProductsdetails($_POST);
    
    
    
    die();
    
}else if($_GET['floorplanRequest'] == "getcurrentOrderNote"){
    
    
    require_once('../../../wp-load.php');
    
    
  
    getcurrentOrderNote($_POST);
    
    
    
    die();
    
}else if($_GET['floorplanRequest'] == "updatedcurrentordernote"){
    
    
    require_once('../../../wp-load.php');
    
    
  
    updatedcurrentordernote($_POST);
    
    
    
    die();
    
}

function updatedcurrentordernote($request){
    
    
    try {
        
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);  
    $lastInsertId = floorplan_contentmanagerlogging('Update Order Note',"Admin Action",serialize($request),$user_ID,$user_info->user_email,"");
     
    
    $OrderID = $request['orderID'];
    $OrderNote = $request['OrderNote'];
    
    update_post_meta( $OrderID, '_order_custome_note', $OrderNote );
    
    
    
    }catch (Exception $e) {
       
     
        return $e;
        
    }
}
function getcurrentOrderNote($request){
    
    
    try {
        
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);  
    $lastInsertId = floorplan_contentmanagerlogging('Get Order Note',"Admin Action",serialize($request),$user_ID,$user_info->user_email,"");
     
    
    $OrderID = $request['orderID'];
    $message['status']="success";
    $message['ordernote']="";
    $OrderNote = get_post_meta( $OrderID, '_order_custome_note', true );
    $message['ordernote']=$OrderNote;
    
    if(empty($OrderNote)){
        
     $message['ordernote']=""; 
    }
    echo json_encode($message);
    
    }catch (Exception $e) {
       
     
        return $e;
        
    }
    
}


function getOrderProductsdetails($request){
    
    
    try {
        
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);  
    $lastInsertId = floorplan_contentmanagerlogging('GetProduct Detail',"User Action",serialize($request),$user_ID,$user_info->user_email,"");
     
    
    $OrderID = $request['ID'];
   
    $order = wc_get_order($OrderID);
   
    
    $blog_id = get_current_blog_id();
    global $wpdb;
     $get_items_sql = "SELECT items.order_item_id,items.order_item_name,Pid.meta_value as Pid,Qty.meta_value as Qty,Subtotal.meta_value as Subtotal FROM wp_".$blog_id."_woocommerce_order_items AS items LEFT JOIN wp_".$blog_id."_woocommerce_order_itemmeta AS Pid ON(items.order_item_id = Pid.order_item_id)LEFT JOIN wp_".$blog_id."_woocommerce_order_itemmeta AS Qty ON(items.order_item_id = Qty.order_item_id)LEFT JOIN wp_".$blog_id."_woocommerce_order_itemmeta AS Subtotal ON(items.order_item_id = Subtotal.order_item_id) WHERE items.order_id = " . $order->get_order_number() . " AND Qty.meta_key IN ( '_qty' )AND Pid.meta_key IN ( '_product_id' )AND Subtotal.meta_key IN ( '_line_subtotal' ) ORDER BY items.order_item_id";
    
    $products = $wpdb->get_results($get_items_sql);
    
    
    $tableHTML = "";
    foreach ($products as $single_product => $productname) {
            
            
            $_product = wc_get_product( $productname->Pid );
            
            
            
            
            
            $prodcut_prfixname = "";
            
            $args = array(
                'taxonomy'   => "product_cat",
            );
            $product_categories = get_terms($args);
            
            foreach($product_categories as $catIndex=>$catData){
                
                
                if($_product->category_ids[0] == $catData->term_id){
                    
                    
                    $catName = $catData->name;
                }
                
            }
            if($catName == "Uncategorized"){
                
                $prodcut_prfixname = "Booth-";
                
            }else if($catName == "Add-ons"){
                
                $prodcut_prfixname = "Add-ons-";
            }else{
                
                $prodcut_prfixname = "Packages";
            }
           
           
            
            $product_price =  wc_price($_product->regular_price);
            $image_ID = $_product->image_id;
            $image = "";
            $productQuntity = "";
            if(!empty($image_ID)){
                
                $url = wp_get_attachment_thumb_url($image_ID);
                $image = '<img src="'.$url.'" width="50" />';
            }
            if($productname->Qty == 0){
                
                $productQuntity = "";
            }else{
                
                $productQuntity = $productname->Qty;
            }
            if(esc_html( wc_get_order_status_name($order->status)) == 'Pending Deposit Payment' ){
                
                $subtotalAmount = "-";
                $OrderTotal = "-";
                
            }else{
                
                $subtotalAmount =   wc_price($productname->Subtotal);
                $OrderTotal = wc_price($order->total);
            }
            $product_title = str_replace("Payment #2 for","",$productname->order_item_name); 
            $tableHTML .= '<tr><td>'.$image.'</td><td>'.$prodcut_prfixname.$product_title.'</td><td>'.$product_price.'</td><td>'.$productQuntity.'</td><td>'.$subtotalAmount.'</td></tr>'; 
           

                                                                        
    }
    
    
    
        $tableHTML .= '<tr><td></td><td><strong style="font-style: italic;">Total</strong></td><td></td><td></td><td><strong style="font-style: italic;">'.$OrderTotal.'</strong</td></tr>'; 
           
    
    echo json_encode($tableHTML);
    
    }catch (Exception $e) {
       
     
        return $e;
        
    }
    
    
    
    
    
}

function updateorderstatus($request){
    
    
    try {
        
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);  
    $lastInsertId = floorplan_contentmanagerlogging('Update Order Status',"Admin Action",serialize($request),$user_ID,$user_info->user_email,"");
     
    
    $OrderID = $request['orderID'];
    $status = $request['status'];

    $order = wc_get_order($OrderID);
    $order->update_status($status);
    
  
    
   // $emails = WC_Emails::instance();
   // $emails->customer_invoice( wc_get_order( $OrderID ) );
   
    
    }catch (Exception $e) {
       
     
        return $e;
        
    }
    
    
    
    
    
}


function bulkproductgenrate($request){
    
    
    try {
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);  
    $lastInsertId = floorplan_contentmanagerlogging('Manage Bulk Products',"Admin Action",serialize($request),$user_ID,$user_info->user_email,"");
     
    
    
    $productdata =json_decode(stripslashes($request['bulkproductsdata']));
    
    
    
    
    
    foreach ($productdata as $productID=>$prodcutObject){
        
           
            //$objProduct = new WC_Product();
        
        if($productID == 'removeproducts'){
            
            
            
            
            
            foreach ($prodcutObject as $removeproductIndex=>$removeprodcutID){
                
                $removeIDObject['postid'] = $removeprodcutID;
                
               
                deleteproduct($removeIDObject);
            }
            
            
            
        }else{
            
        
        
        $objProduct = wc_get_product($prodcutObject->id);
        $objProduct->set_name($prodcutObject->prodcuttitle); //Set product name.
        $objProduct->set_status($prodcutObject->prodcutstatus); //Set product status.
        $objProduct->set_description($prodcutObject->prodcutlongdescripition); //Set product description.
        $objProduct->set_price($prodcutObject->prodcutprice); //Set the product's active price.
        $objProduct->set_regular_price($prodcutObject->prodcutprice); //Set the product's regular price.
        $objProduct->set_tax_class($prodcutObject->prodcutlevel); 
        
        if(!empty($prodcutObject->prodcutlevel)){
            
          
                     $objProduct->update_meta_data('productlevel', $prodcutObject->prodcutlevel);
            
                }
                
        $term_ids =[$prodcutObject->prodcutcatID];
        $objProduct->set_category_ids($term_ids); //Set the product categories.                   | array $term_ids List of terms IDs.
        $objProduct->set_tag_ids($term_ids); //Set the product tags.                              | array $term_ids List of terms IDs.
        $objProduct->set_image_id($prodcutObject->prodcutfileupload); //Set main image ID.                                         | int|string $image_id Product image id.
        //Set gallery attachment ids.                       | array $image_ids List of image ids.
        $new_product_id = $objProduct->save();
        
        }
     
        
        
    }
    
    
    
    }catch (Exception $e) {
       
     
        return $e;
        
    }
    
    
    
    
    
}
function uploadproductimage($request){
    
     try {
    
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);  
    $lastInsertId = floorplan_contentmanagerlogging('Request Image Upload Bulk Product',"Admin Action",serialize($request),$user_ID,$user_info->user_email,"");
      
    $productimage=$_FILES['productpic'];
    
    if(!empty($productimage)){
            
        
        $productpicID = product_file_upload($productimage);
           
            
        }
    if(!empty($productpicID)){
            
        $url['id'] = $productpicID;
        $url['url'] = wp_get_attachment_thumb_url($productpicID);
           
            
        }
     echo json_encode($url);
    contentmanagerlogging_file_upload ($lastInsertId,serialize($url));  
   
    
    }catch (Exception $e) {
       
     
        return $e;
        
    }
}

function autogenerateproducts(){
    
    try{
	
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $FloorplanXml = stripslashes($_REQUEST['floorXml']);
        $lastInsertId = floorplan_contentmanagerlogging('AutoGenrate Product Request',"Admin Action",unserialize($FloorplanXml),$user_ID,$user_info->user_email,"");
      
        
        $FloorplanXml = str_replace('"n<','<',$FloorplanXml);
        $FloorplanXml= str_replace('>n"','>',$FloorplanXml);
        
        $boothTypesLegend = json_decode(get_post_meta($_REQUEST['post_id'], 'pricetegs', true ));
        $taxonomy     = 'product_cat';
        $orderby      = 'name';  
        $show_count   = 0;      // 1 for yes, 0 for no
        $pad_counts   = 0;      // 1 for yes, 0 for no
        $hierarchical = 1;      // 1 for yes, 0 for no  
        $title        = '';  
        $empty        = 0;

        $args = array(
               'taxonomy'     => $taxonomy,
               'orderby'      => $orderby,
               'show_count'   => $show_count,
               'pad_counts'   => $pad_counts,
               'hierarchical' => $hierarchical,
               'title_li'     => $title,
               'hide_empty'   => $empty
        );
       $all_categories = get_categories( $args );
        
        foreach ($all_categories as $catIndex=>$catValue){
            
            
            if($catValue->name == "Booths"){
                
                $catID = $catValue->cat_ID;
                
            }
            
            
        }
        
        
        
        $default_settings = get_option( 'ContenteManager_Settings' );
        $default_booth_price = $default_settings['ContentManager']['defaultboothprice'];
        
        $xml=simplexml_load_string($FloorplanXml) or die("Error: Cannot create object");
        $currentIndex = 0;
        
        $att = "boothproductid";
        
       
       
        
        
        
        foreach ($xml->root->MyNode as $cellIndex=>$CellValue){
            
          
        
          
            $cellboothlabelvalue = $CellValue->attributes();
            $getCellStylevalue = $xml->root->MyNode[$currentIndex]->mxCell->attributes();
            $createdproductLevel = "";
            
            
            $boothtitle = $cellboothlabelvalue['mylabel'];
            $boothid = $cellboothlabelvalue['id'];
           
            
            $prdoucttitlepostfix = " - ";
            if(!empty($boothtitle) && $boothtitle !=""){
                
                $prdoucttitlepostfix.=$boothtitle;
            }else{
                $prdoucttitlepostfix.=$boothid;
            }
            
           
            
            
        if((!isset($cellboothlabelvalue['boothOwner']) || $cellboothlabelvalue['boothOwner'] == "none") &&  (!isset($cellboothlabelvalue['boothproductid']) || $cellboothlabelvalue['boothproductid'] == "none")){    
            
          
               
                if(isset($cellboothlabelvalue['pricetegid']) && !empty($cellboothlabelvalue['pricetegid'])){
                    
                   
                    $getpricetegID = $cellboothlabelvalue['pricetegid'];
                    
                    
                    foreach ($boothTypesLegend as $boothlabelIndex=>$boothlabelValue){
                        if($boothlabelValue->ID ==  $getpricetegID){
                            
                            $createdproductPrice = $boothlabelValue->price;
                            $createdproductName = $boothlabelValue->name;
                            $createdproductLevel = $boothlabelValue->level;
                            
                            
                        }
                    }
                
                    
                    
                    
                    
                   
                    
                    
                    
                    
                    
                
                $objProduct = new WC_Product();
                
                
                
                
                $objProduct->set_slug($cellboothlabelvalue['id']);
                $objProduct->set_name($createdproductName.$prdoucttitlepostfix); 
                
                $objProduct->set_status('publish'); //Set product status.
                $objProduct->set_featured(TRUE); //Set if the product is featured.                          | bool
                $objProduct->set_catalog_visibility('visible'); //Set catalog visibility.                   | string $visibility Options: 'hidden', 'visible', 'search' and 'catalog'.
                $objProduct->set_description(''); //Set product description.
                $objProduct->set_short_description(''); //Set product short description.

                $objProduct->set_price($createdproductPrice); //Set the product's active price.
                $objProduct->set_regular_price($createdproductPrice); //Set the product's regular price.

                $objProduct->set_manage_stock(TRUE); //Set if product manage stock.                         | bool
                $objProduct->set_stock_quantity(1); //Set number of items available for sale.
                $objProduct->set_stock_status('instock'); //Set stock status.                               | string $status 'instock', 'outofstock' and 'onbackorder'
                $objProduct->set_backorders('no'); //Set backorders.                                        | string $backorders Options: 'yes', 'no' or 'notify'.
                $objProduct->set_sold_individually(FALSE);
               // $objProduct->set_tax_class($createdproductLevel); 
                
                 if(!empty($roleassign)){
            
          
                     $objProduct->update_meta_data('productlevel', $createdproductLevel);
            
                }
                
                //  $objProduct->set_menu_order($menu_order); 

                $objProduct->set_reviews_allowed(TRUE); //Set if reviews is allowed.                        | bool

                $term_ids =[$catID];
                $objProduct->set_category_ids($term_ids); //Set the product categories.                   | array $term_ids List of terms IDs.
                $objProduct->set_tag_ids($term_ids); //Set the product tags.                              | array $term_ids List of terms IDs.
               // $objProduct->set_image_id($productpicrul); //Set main image ID.                                         | int|string $image_id Product image id.
                //Set gallery attachment ids.                       | array $image_ids List of image ids.
                $new_product_id = $objProduct->save(); //Saving the data to create new product, it will return product ID.

                $xml->root->MyNode[$currentIndex]->attributes()->$att = $new_product_id;   
                    
                    
                    
                }
           
                
               
            
        }   
        $currentIndex++;
        
    
    
        }
        
        $FloorplanXml = str_replace('<?xml version="1.0"?>',"",$xml->asXML());
        
        $FloorplanXml = str_replace('"n<','<',$FloorplanXml);
        $FloorplanXml = str_replace('>n"','>',$FloorplanXml);
        
        
        //echo '<pre>';
        //print_r($FloorplanXml);exit;
        
        
        update_post_meta( $_REQUEST['post_id'], 'floorplan_xml', json_encode($FloorplanXml));
        
        contentmanagerlogging_file_upload ($lastInsertId,serialize($FloorplanXml));
        
       
       echo 'updated';
       exit;
        
        
        
        
        
    }catch (Exception $e) {
       
     
        return $e;
        
    }
    
}
function order_report_savefilters($orderreportname, $orderreportfilterdata, $showcolumnslist, $ordercolunmtype, $ordercolunmname) {

    require_once('../../../wp-load.php');

    try {
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        $lastInsertId = contentmanagerlogging('Saved Order Report', "Admin Action", $orderreportfilterdata, $user_ID, $user_info->user_email, "pre_action_data");

        $settitng_key = 'ContenteManager_Orderreport_settings';

        $orderreportfilterdata = stripslashes($orderreportfilterdata);

        $order_reportsaved_list = get_option($settitng_key);
        $order_reportsaved_list[$orderreportname][0] = $orderreportfilterdata;
        $order_reportsaved_list[$orderreportname][1] = $showcolumnslist;
        $order_reportsaved_list[$orderreportname][2] = $ordercolunmtype;
        $order_reportsaved_list[$orderreportname][3] = $ordercolunmname;

        update_option($settitng_key, $order_reportsaved_list);
        $order_reportsaved_list = get_option($settitng_key);
        contentmanagerlogging_file_upload($lastInsertId, serialize($order_reportsaved_list));
        foreach ($order_reportsaved_list as $key => $value) {
            $orderlist[] = $key;
        }

        echo json_encode($orderlist);
    } catch (Exception $e) {

        contentmanagerlogging_file_upload($lastInsertId, serialize($e));

        return $e;
    }

    die();
}

function order_report_removefilter($orderreportname) {

    require_once('../../../wp-load.php');

    try {


        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        $lastInsertId = contentmanagerlogging('Remove Order Report', "Admin Action", $orderreportdata, $user_ID, $user_info->user_email, "pre_action_data");


        $settitng_key = 'ContenteManager_Orderreport_settings';
        $order_reportsaved_list = get_option($settitng_key);

        unset($order_reportsaved_list[$orderreportname]);
        //echo '<pre>';
        //print_r($order_reportsaved_list);exit;
        update_option($settitng_key, $order_reportsaved_list);

        $order_reportsaved_list = get_option($settitng_key);
        contentmanagerlogging_file_upload($lastInsertId, serialize($order_reportsaved_list));
        foreach ($order_reportsaved_list as $key => $value) {
            $orderlist[] = $key;
        }

        echo json_encode($orderlist);
    } catch (Exception $e) {

        contentmanagerlogging_file_upload($lastInsertId, serialize($e));

        return $e;
    }

    die();
}

function get_orderreport_detail($orderreportname) {

    require_once('../../../wp-load.php');

    try {


        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        $lastInsertId = contentmanagerlogging('Load Order Report', "Admin Action", $orderreportdata, $user_ID, $user_info->user_email, "pre_action_data");


        $settitng_key = 'ContenteManager_Orderreport_settings';
        $order_reportsaved_list = get_option($settitng_key);


        contentmanagerlogging_file_upload($lastInsertId, serialize($order_reportsaved_list));

        echo json_encode($order_reportsaved_list[$orderreportname]);
    } catch (Exception $e) {

        contentmanagerlogging_file_upload($lastInsertId, serialize($e));

        return $e;
    }

    die();
}

function loadorderreport() {

    require_once('../../../wp-load.php');

    try {

        global $wpdb;
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        $lastInsertId = contentmanagerlogging('Get Order Report Date', "Admin Action", $orderreportdata, $user_ID, $user_info->user_email, "pre_action_data");
        
     
        
        $query = new WP_Query( array( 'post_type' => 'shop_order' ,'post_status'=>array('wc-pending-deposit','wc-scheduled-payment','wc-partial-payment','wc-failed','wc-refunded','wc-processing','wc-pending','wc-cancelled','wc-completed','wc-on-hold','wc-pending'),'posts_per_page' => -1) );
        $all_posts = $query->posts;
        
        $columns_headers = [];
        $columns_rows_data = [];

        $columns_list_order_report[0]['title'] = 'Action';
        $columns_list_order_report[0]['type'] = 'string';
        $columns_list_order_report[0]['key'] = 'action';
        
        $columns_list_order_report[1]['title'] = 'Order Date';
        $columns_list_order_report[1]['type'] = 'date';
        $columns_list_order_report[1]['key'] = 'post_date';
     
        $columns_list_order_report_postmeta[2]['title'] = 'Company Name';
        $columns_list_order_report_postmeta[2]['type'] = 'string';
        $columns_list_order_report_postmeta[2]['key'] = '_billing_company';
        
        $columns_list_order_report[3]['title'] = 'Order Status';
        $columns_list_order_report[3]['type'] = 'string';
        $columns_list_order_report[3]['key'] = 'post_status';
        
        
        $columns_list_order_report_postmeta[4]['title'] = 'Amount';
        $columns_list_order_report_postmeta[4]['type'] = 'num-fmt';
        $columns_list_order_report_postmeta[4]['key'] = '_order_total';
        
        
         $columns_list_order_report_postmeta[5]['title'] = 'Products List';
        $columns_list_order_report_postmeta[5]['type'] = 'string';
        $columns_list_order_report_postmeta[5]['key'] = 'Productslistexcel';
        
        
        $columns_list_order_report_postmeta[6]['title'] = 'Product Details';
        $columns_list_order_report_postmeta[6]['type'] = 'string';
        $columns_list_order_report_postmeta[6]['key'] = 'Products';
        
        
          
       
        
        
        
        
        
        $columns_list_order_report_postmeta[7]['title'] = 'Payment Method';
        $columns_list_order_report_postmeta[7]['type'] = 'string';
        $columns_list_order_report_postmeta[7]['key'] = '_payment_method_title';
        
        
        $columns_list_order_report_postmeta[8]['title'] = 'Payment Date';
        $columns_list_order_report_postmeta[8]['type'] = 'date';
        $columns_list_order_report_postmeta[8]['key'] = '_paid_date';
        
        $columns_list_order_report_postmeta[8]['title'] = 'Order Note';
        $columns_list_order_report_postmeta[8]['type'] = 'string';
        $columns_list_order_report_postmeta[8]['key'] = '_order_custome_note';
        
        
        $columns_list_order_report_postmeta[10]['title'] = 'Age';
        $columns_list_order_report_postmeta[10]['type'] = 'string';
        $columns_list_order_report_postmeta[10]['key'] = '_product_age_calculate';
        
        $columns_list_order_report_postmeta[11]['title'] = 'First Name';
        $columns_list_order_report_postmeta[11]['type'] = 'string';
        $columns_list_order_report_postmeta[11]['key'] = '_billing_first_name';
        
        $columns_list_order_report_postmeta[12]['title'] = 'Last Name';
        $columns_list_order_report_postmeta[12]['type'] = 'string';
        $columns_list_order_report_postmeta[12]['key'] = '_billing_last_name';
        
        $columns_list_order_report_postmeta[13]['title'] = 'Email';
        $columns_list_order_report_postmeta[13]['type'] = 'string';
        $columns_list_order_report_postmeta[13]['key'] = '_billing_email';
        
        
        $columns_list_order_report_postmeta[14]['title'] = 'Phone Number';
        $columns_list_order_report_postmeta[14]['type'] = 'string';
        $columns_list_order_report_postmeta[14]['key'] = '_billing_phone';
        
        


        $columns_list_order_report_postmeta[15]['title'] = 'Order Currency';
        $columns_list_order_report_postmeta[15]['type'] = 'string';
        $columns_list_order_report_postmeta[15]['key'] = '_order_currency';

        $columns_list_order_report_postmeta[16]['title'] = 'User IP Address';
        $columns_list_order_report_postmeta[16]['type'] = 'string';
        $columns_list_order_report_postmeta[16]['key'] = '_customer_ip_address';


       


      

        

        

        $columns_list_order_report_postmeta[17]['title'] = 'Address Line 1';
        $columns_list_order_report_postmeta[17]['key'] = '_billing_address_1';
        $columns_list_order_report_postmeta[17]['type'] = 'string';

        $columns_list_order_report_postmeta[18]['title'] = 'Address Line 2';
        $columns_list_order_report_postmeta[18]['key'] = '_billing_address_2';
        $columns_list_order_report_postmeta[18]['type'] = 'string';

        $columns_list_order_report_postmeta[19]['title'] = 'Zipcode';
        $columns_list_order_report_postmeta[19]['key'] = '_billing_postcode';
        $columns_list_order_report_postmeta[19]['type'] = 'string';

        $columns_list_order_report_postmeta[20]['title'] = 'City';
        $columns_list_order_report_postmeta[20]['key'] = '_billing_city';
        $columns_list_order_report_postmeta[20]['type'] = 'string';

        $columns_list_order_report_postmeta[21]['title'] = 'State';
        $columns_list_order_report_postmeta[21]['key'] = '_billing_state';
        $columns_list_order_report_postmeta[21]['type'] = 'string';

        $columns_list_order_report_postmeta[22]['title'] = 'Country';
        $columns_list_order_report_postmeta[22]['key'] = '_billing_country';
        $columns_list_order_report_postmeta[22]['type'] = 'string';

        $columns_list_order_report_postmeta[23]['title'] = 'Stripe Fee';
        $columns_list_order_report_postmeta[23]['type'] = 'num-fmt';
        $columns_list_order_report_postmeta[23]['key'] = '_stripe_fee';

        $columns_list_order_report_postmeta[24]['title'] = 'Net Revenue From Stripe';
        $columns_list_order_report_postmeta[24]['type'] = 'num-fmt';
        $columns_list_order_report_postmeta[24]['key'] = '_stripe_net';

        $columns_list_order_report_postmeta[25]['title'] = 'Order ID';
        $columns_list_order_report_postmeta[25]['type'] = 'string';
        $columns_list_order_report_postmeta[25]['key'] = 'ID';
        
        
        $columns_list_order_report_postmeta[26]['title'] = 'Initial Order ID';
        $columns_list_order_report_postmeta[26]['type'] = 'string';
        $columns_list_order_report_postmeta[26]['key'] = '_initial_payment_order_id';
        

        $columns_list_order_report_postmeta[27]['title'] = 'Transaction ID';
        $columns_list_order_report_postmeta[27]['type'] = 'string';
        $columns_list_order_report_postmeta[27]['key'] = '_transaction_id';

        

        $columns_list_order_report_postmeta[28]['title'] = 'Account Holder Email';
        $columns_list_order_report_postmeta[28]['type'] = 'string';
        $columns_list_order_report_postmeta[28]['key'] = 'Account Holder Email';
        
        $columns_list_order_report_postmeta[29]['title'] = 'Order Discount';
        $columns_list_order_report_postmeta[29]['type'] = 'num-fmt';
        $columns_list_order_report_postmeta[29]['key'] = '_cart_discount';


        
       

        $custom_field = "";

        foreach ($columns_list_order_report as $col_keys => $col_keys_title) {


            $colums_array_data['title'] = $columns_list_order_report[$col_keys]['title'];
            $colums_array_data['type'] = $columns_list_order_report[$col_keys]['type'];
            $colums_array_data['data'] = $columns_list_order_report[$col_keys]['title'];
            array_push($columns_headers, $colums_array_data);
        }
        foreach ($columns_list_order_report_postmeta as $col_keys => $col_keys_title) {


            $colums_array_data['title'] = $columns_list_order_report_postmeta[$col_keys]['title'];
            $colums_array_data['data'] = $columns_list_order_report_postmeta[$col_keys]['title'];
            $colums_array_data['type'] = $columns_list_order_report_postmeta[$col_keys]['type'];

            array_push($columns_headers, $colums_array_data);
        }
        foreach ($all_posts as $single_post) {

            $header_array = get_object_vars($single_post);
            $post_meta = get_post_meta($header_array['ID']);
            $order = wc_get_order( $header_array['ID'] );
         // if($header_array['ID'] == 5236){   
           foreach ( $order->get_items() as $item_id => $item ) {
                
                $custom_field = wc_get_order_item_meta( $item_id, '_remaining_balance_order_id', true );
                
                
            }
            
            
         // }
            
            
            $column_row;
            ksort($post_meta);
            foreach ($columns_list_order_report as $col_keys_index => $col_keys_title) {
                
                if($columns_list_order_report[$col_keys_index]['key'] == 'action'){
                    
                    $orderID = $header_array['ID'];
                    
                    if($header_array['post_status'] !="wc-completed" && $header_array['post_status'] !="wc-partial-payment"){
                        
                        
                        if($header_array['post_status'] == "wc-cancelled"){
                        
                            $column_row[$columns_list_order_report[$col_keys_index]['title']] = '<div style="width: 110px !important;"class = "hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><a onclick="markorderstatuscompleted('.$orderID.')"  data-toggle="tooltip" title="Complete Order"><i  class="hi-icon fusion-li-icon fa fa-check-circle" ></i></a><i style="color:#e5e6e8" title="Cancel Order" class="hi-icon fusion-li-icon fa fa-times-circle" ></i><a onclick="updatecurrentordernotes('.$orderID.')"  name="order-notes" data-toggle="tooltip"  title="Add Note" ><i class="hi-icon fusion-li-icon fa fa-clipboard" ></i></a></div>';
                    
                        }else{
                        
                            $column_row[$columns_list_order_report[$col_keys_index]['title']] = '<div style="width: 110px !important;"class = "hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><a onclick="markorderstatuscompleted('.$orderID.')"  data-toggle="tooltip" title="Complete Order"><i  class="hi-icon fusion-li-icon fa fa-check-circle" ></i></a><a onclick="markorderstatuscancel('.$orderID.')"  name="cancel-order" data-toggle="tooltip"  title="Cancel Order" ><i class="hi-icon fusion-li-icon fa fa-times-circle" ></i></a><a onclick="updatecurrentordernotes('.$orderID.')"  name="order-notes" data-toggle="tooltip"  title="Add Note" ><i class="hi-icon fusion-li-icon fa fa-clipboard" ></i></a></div>';
                        
                        
                        }
                        
                    }else{
                        
                        $column_row[$columns_list_order_report[$col_keys_index]['title']] = '<div style="width: 110px !important;"class = "hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><i  style="color:#e5e6e8" title="Complete Order" class="hi-icon fusion-li-icon fa fa-check-circle"></i><a onclick="markorderstatuscancel('.$orderID.')"  name="cancel-order" data-toggle="tooltip"  title="Cancel Order" ><i class="hi-icon fusion-li-icon fa fa-times-circle" ></i></a><a onclick="updatecurrentordernotes('.$orderID.')"  name="order-notes" data-toggle="tooltip"  title="Add Note" ><i class="hi-icon fusion-li-icon fa fa-clipboard" ></i></a></div>';
                    
                    }
                   
                }else if ($columns_list_order_report[$col_keys_index]['key'] == 'post_date') {

                    if (!empty($header_array[$columns_list_order_report[$col_keys_index]['key']])) {
                        $time = strtotime($header_array[$columns_list_order_report[$col_keys_index]['key']]);
                        $newformat = $time * 1000; // date('d-M-Y  H:i:s', $time);
                    } else {
                        $newformat = '';
                    }
                    $column_row[$columns_list_order_report[$col_keys_index]['title']] = $newformat;
                    // echo '<pre>';
                    //print_r($column_row);exit;
                }else if ($columns_list_order_report[$col_keys_index]['key'] == 'post_status') {
                        
                    
                    if(esc_html( wc_get_order_status_name( $header_array[$columns_list_order_report[$col_keys_index]['key']])) == 'Partially Paid'){
                        
                        
                        $column_row[$columns_list_order_report[$col_keys_index]['title']] =  'Initial Deposit Completed';//esc_html( wc_get_order_status_name( $header_array[$columns_list_order_report[$col_keys_index]['key']]));
                 
                        
                    }else if(esc_html( wc_get_order_status_name( $header_array[$columns_list_order_report[$col_keys_index]['key']])) == 'Pending Deposit Payment'){
                        
                        
                        $column_row[$columns_list_order_report[$col_keys_index]['title']] =  'Balance Due';//esc_html( wc_get_order_status_name( $header_array[$columns_list_order_report[$col_keys_index]['key']]));
                 
                        
                    }else{
                        
                        $column_row[$columns_list_order_report[$col_keys_index]['title']] =  esc_html( wc_get_order_status_name( $header_array[$columns_list_order_report[$col_keys_index]['key']]));
                 
                        
                    }
                    
                    
                   
                    
                }else if ($columns_list_order_report[$col_keys_index]['key'] == '_initial_payment_order_id') {
                        
                     
                    $column_row[$columns_list_order_report[$col_keys_index]['title']] = $custom_field;
               
                    
                }else if ($columns_list_order_report[$col_keys_index]['key'] == 'ID') {
                        
                     
                    $column_row[$columns_list_order_report[$col_keys_index]['title']] = (int)$header_array[$columns_list_order_report[$col_keys_index]['key']];
               
                    
                }else {

                     
                    $column_row[$columns_list_order_report[$col_keys_index]['title']] = $header_array[$columns_list_order_report[$col_keys_index]['key']];
                
                    
                }
            }
            
            
            
            
            foreach ($columns_list_order_report_postmeta as $col_keys_index => $col_keys_title) {
                
                 if($columns_list_order_report_postmeta[$col_keys_index]['key'] == '_product_age_calculate'){
                    
                    $now = time(); // or your date as well
                    $your_date = strtotime($header_array['post_date']);
                    $datediff = $now - $your_date;
                    if(esc_html( wc_get_order_status_name( $header_array['post_status'])) == "Pending Deposit Payment"){
                        
                        $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = round($datediff / (60 * 60 * 24));
                    
                        
                    }else{
                        
                        $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = "";
                    
                        
                    }
                    
                }else if($columns_list_order_report_postmeta[$col_keys_index]['key'] == 'Productslistexcel'){
                    
                        
                    
                    
                }else if($columns_list_order_report_postmeta[$col_keys_index]['key'] == '_initial_payment_order_id'){
                    
                    $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = $custom_field;
                    
                }else if($columns_list_order_report_postmeta[$col_keys_index]['key'] == 'ID'){
                    
                    $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = (int)$header_array[$columns_list_order_report_postmeta[$col_keys_index]['key']];
                    
                }else if ($columns_list_order_report_postmeta[$col_keys_index]['key'] == '_paid_date') {

                    if (!empty($post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0])) {
                        $time = strtotime($post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0]);
                        $newformat = $time * 1000; //date('d-M-Y H:i:s', $time);
                    } else {
                        $newformat = '';
                    }
                    $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = $newformat;
                } else if ($columns_list_order_report_postmeta[$col_keys_index]['key'] == 'Products' || $columns_list_order_report_postmeta[$col_keys_index]['key'] == 'Account Holder Email') {
                    
                }else if ($columns_list_order_report_postmeta[$col_keys_index]['key'] == '_order_total' ) {
                    
                     $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = round($post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0]);
                     $totalAmountOrder = round($post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0]);
                     
                } else {
                    if ($columns_list_order_report_postmeta[$col_keys_index]['type'] == 'num' || $columns_list_order_report_postmeta[$col_keys_index]['type'] == 'num-fmt') {
                        
                        if($columns_list_order_report_postmeta[$col_keys_index]['title'] == 'Stripe Fee'){
                            
                           if (array_key_exists($columns_list_order_report_postmeta[$col_keys_index]['key'],$post_meta)){
                               
                               $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = round($post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0]);
                       
                               
                           }else{
                               
                             $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = round($post_meta['Stripe Fee'][0]);
                        
                               
                           }
                            
                            
                            
                        }else if($columns_list_order_report_postmeta[$col_keys_index]['title'] == 'Net Revenue From Stripe'){
                            
                            if (array_key_exists($columns_list_order_report_postmeta[$col_keys_index]['key'],$post_meta)){
                               
                               $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = round($post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0]);
                       
                               
                           }else{
                               
                             $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = round($post_meta['Net Revenue From Stripe'][0]);
                        
                               
                           }
                            
                            
                        }else{
                            
                          $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = round($post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0]);
                      
                            
                        }
                        
                        
                        
                    } else {
                        $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = $post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0];
                    }
                }
            }



            $userdata = get_userdata($post_meta['_customer_user'][0]);
            $accountholder_email = $userdata->user_email;
            $blog_id = get_current_blog_id();
            
            $get_items_sql = "SELECT items.order_item_id,items.order_item_name,Pid.meta_value as Pid,Qty.meta_value as Qty FROM wp_".$blog_id."_woocommerce_order_items AS items LEFT JOIN wp_".$blog_id."_woocommerce_order_itemmeta AS Pid ON(items.order_item_id = Pid.order_item_id)LEFT JOIN wp_".$blog_id."_woocommerce_order_itemmeta AS Qty ON(items.order_item_id = Qty.order_item_id) WHERE items.order_id = " . $header_array['ID'] . " AND Qty.meta_key IN ( '_qty' )AND Pid.meta_key IN ( '_product_id' ) ORDER BY items.order_item_id";
            $products = $wpdb->get_results($get_items_sql);
            $order_productsnames = "";
            foreach ($products as $single_product => $productname) {
                
                
                
                $order_productsnames.= $productname->order_item_name.' (x'.$productname->Qty.')<br>';
            }
            $column_row['Products List'] = $order_productsnames;
            $column_row['Product Details'] = '<a style="cursor: pointer;" onclick="getOrderproductdetail('.$header_array['ID'].')">Product Details</a>';//$order_productsnames;
            $column_row['Account Holder Email'] = $accountholder_email;
            array_push($columns_rows_data, $column_row);
        }

        $orderreport_all_col_rows_data['columns'] = $columns_headers;
        $orderreport_all_col_rows_data['data'] = $columns_rows_data;

        contentmanagerlogging_file_upload($lastInsertId, serialize($orderreport_all_col_rows_data));

        echo json_encode($columns_rows_data) . '//' . json_encode($columns_headers);
    } catch (Exception $e) {

        contentmanagerlogging_file_upload($lastInsertId, serialize($e));

        return $e;
    }

    die();
}

function manageproducts() {

    require_once('../../../wp-load.php');
    require_once('temp/lib/woocommerce-api.php');
    
    try {

        global $wpdb;
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        $site_url  = get_site_url();
        $lastInsertId = contentmanagerlogging('Manage Products  Report Date', "Admin Action", '', $user_ID, $user_info->user_email, "pre_action_data");
        $url = get_site_url();
      
        $options = array(
            'debug' => true,
            'return_as_array' => false,
            'validate_url' => false,
            'timeout' => 30,
            'ssl_verify' => false,
        );
        $woocommerce_rest_api_keys = get_option( 'ContenteManager_Settings' );
        $wooconsumerkey = $woocommerce_rest_api_keys['ContentManager']['wooconsumerkey'];
        $wooseceretkey = $woocommerce_rest_api_keys['ContentManager']['wooseceretkey'];
        $woocommerce_object = new WC_API_Client( $url, $wooconsumerkey, $wooseceretkey, $options );
        $all_products= $woocommerce_object->products->get( '', ['filter[limit]' => -1,'filter[post_status]' => 'any']);
        global $wp_roles;
        $get_all_roles = $wp_roles->roles;
       // $get_all_roles = get_option($get_all_roles_array);
        
       // echo '<pre>';
      //  print_r($get_all_roles);exit;
        
      //  echo '<pre>';
      //  print_r($all_products);exit;
        
        
        $columns_headers = [];
        $columns_rows_data = [];




//        $columns_list_order_report[0]['title'] = 'ID';
//        $columns_list_order_report[0]['type'] = 'string';
//        $columns_list_order_report[0]['key'] = 'ID';

        $columns_list_order_report[0]['title'] = 'Name';
        $columns_list_order_report[0]['type'] = 'string';
        $columns_list_order_report[0]['key'] = 'title';

        

        $columns_list_order_report[1]['title'] = 'Category';
        $columns_list_order_report[1]['type'] = 'string';
        $columns_list_order_report[1]['key'] = 'product_category';
        

        $columns_list_order_report[2]['title'] = 'Stock';
        $columns_list_order_report[2]['type'] = 'string';
        $columns_list_order_report[2]['key'] = 'in_stock';

        $columns_list_order_report[3]['title'] = 'Price';
        $columns_list_order_report[3]['type'] = 'num';
        $columns_list_order_report[3]['key'] = 'price';
        
        $columns_list_order_report[4]['title'] = 'Total Sales';
        $columns_list_order_report[4]['type'] = 'string';
        $columns_list_order_report[4]['key'] = 'total_sales';
        
        $columns_list_order_report[5]['title'] = 'Status';
        $columns_list_order_report[5]['type'] = 'string';
        $columns_list_order_report[5]['key'] = 'status';
        
//        $columns_list_order_report[6]['title'] = 'Assign Level';
//        $columns_list_order_report[6]['type'] = 'string';
//        $columns_list_order_report[6]['key'] = 'tax_class';
        
       $columns_list_order_report[6]['title'] = 'Publish Date';
        $columns_list_order_report[6]['type'] = 'date';
        $columns_list_order_report[6]['key'] = 'created_at';


        $colums_array_data['title'] = 'Action';
        $colums_array_data['type'] = 'html';
        $colums_array_data['data'] = 'action';
        array_push($columns_headers, $colums_array_data);
       
        $colums_array_data['title'] = 'Icon';
        $colums_array_data['type'] = 'html';
        $colums_array_data['data'] = '_thumbnail_id';
        array_push($columns_headers, $colums_array_data);

        foreach ($columns_list_order_report as $col_keys => $col_keys_title) {


            $colums_array_data['title'] = $columns_list_order_report[$col_keys]['title'];
            $colums_array_data['type'] = $columns_list_order_report[$col_keys]['type'];
            $colums_array_data['data'] = $columns_list_order_report[$col_keys]['title'];
            array_push($columns_headers, $colums_array_data);
        }
//        foreach ($columns_list_order_report_postmeta as $col_keys => $col_keys_title) {
//
//
//            $colums_array_data['title'] = $columns_list_order_report_postmeta[$col_keys]['title'];
//            $colums_array_data['data'] = $columns_list_order_report_postmeta[$col_keys]['title'];
//            $colums_array_data['type'] = $columns_list_order_report_postmeta[$col_keys]['type'];
//
//            array_push($columns_headers, $colums_array_data);
//        }
        foreach ($all_products->products as $single_product) {

           
           
          if($single_product->categories[0] == "Packages" || $single_product->categories[0] == "Add-ons" || $single_product->categories[0] == "Bundles"){
            
            
           $action_data = '<div style="width: 140px !important;"class = "hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><i data-toggle="tooltip" class="hi-icon fa fa-clone saveeverything" id = "' . $single_product->id . '" onclick="createproductclone(this)" title="" data-original-title="Create a clone"></i><a href="'.$site_url.'/add-new-product/?productid='. $single_product->id .'"  ><i data-toggle = "tooltip" title = ""  id = "' . $single_product->id . '" class = "hi-icon fusion-li-icon fa fa-pencil-square fa-2x" data-original-title = "Edit Product"></i></a><i   id = "' . $single_product->id . '" data-toggle = "tooltip" title = "" onclick="deleteproduct(this)" class = "hi-icon fusion-li-icon fa fa-times-circle fa-2x" data-original-title = "Delete Product"></i><a href="'.$single_product->permalink.'" target="_blank" ><i onclick = "delete_product(this)" id = "' . $single_product->id . '" data-toggle = "tooltip" title = "" class = "hi-icon fusion-li-icon fa fa-eye fa-2x" data-original-title = "View Product" ></i></a></div>';
           $column_row['Action'] = $action_data;
            
            $url = wp_get_attachment_thumb_url($single_product->images[0]->id);
           
           
            if(!empty($url)){
               $column_row['Icon'] = '<img width="40" height ="40" src="'.  $url .'" />'; 
            }else{
                
                $column_row['Icon'] ="";
            }
            

            foreach ($columns_list_order_report as $col_keys_index => $col_keys_title) {
                
                
                
                
              
                
                $findingvaluekey = $columns_list_order_report[$col_keys_index]['key'];
                
                if ($columns_list_order_report[$col_keys_index]['key'] == 'tax_class') {
                     
                     $column_row[$columns_list_order_report[$col_keys_index]['title']] = $get_all_roles[$single_product->$findingvaluekey]['name'];
                     
                 }else if ($columns_list_order_report[$col_keys_index]['key'] == 'created_at') {

                    if (!empty($single_product->$findingvaluekey)) {
                        $time = strtotime($single_product->$findingvaluekey);
                        $newformat = $time * 1000; // date('d-M-Y  H:i:s', $time);
                    } else {
                        $newformat = '';
                    }
                    $column_row[$columns_list_order_report[$col_keys_index]['title']] = $newformat;
                   
                }else  if ($columns_list_order_report[$col_keys_index]['key'] == 'product_category') {
                    
                    
                    $column_row[$columns_list_order_report[$col_keys_index]['title']] = $single_product->categories[0];
                    
                    
                    
                }else  if ($columns_list_order_report[$col_keys_index]['key'] == 'in_stock') {
                       
                    if ($single_product->$findingvaluekey == '1') {
                        // echo $post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0].'<br>';
                        $column_row[$columns_list_order_report[$col_keys_index]['title']] = '<mark class="instock">In Stock</mark>';
                    }else{
                        // echo $post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0].'<br>';
                        $column_row[$columns_list_order_report[$col_keys_index]['title']] = '<mark class="outofstock">Out of stock</mark>';
                    
                       
                    }
                    
                }else if ($columns_list_order_report[$col_keys_index]['type'] == 'num' || $columns_list_order_report[$col_keys_index]['type'] == 'num-fmt') {
                    
                    $column_row[$columns_list_order_report[$col_keys_index]['title']] = round($single_product->$findingvaluekey);
                
                }else {
                    
                    $column_row[$columns_list_order_report[$col_keys_index]['title']] = $single_product->$findingvaluekey;
                }
            }
          


            array_push($columns_rows_data, $column_row);
          }
        }

        $orderreport_all_col_rows_data['columns'] = $columns_headers;
        $orderreport_all_col_rows_data['data'] = $columns_rows_data;

        contentmanagerlogging_file_upload($lastInsertId, serialize($orderreport_all_col_rows_data));
//exit;
        echo json_encode($columns_rows_data) . '//' . json_encode($columns_headers);
    } catch (Exception $e) {

        contentmanagerlogging_file_upload($lastInsertId, serialize($e));

        return $e;
    }

    die();
}

function addnewproducts($addnewproduct_data) {

  //  require_once('../../../wp-load.php');
   // require_once( 'temp/lib/woocommerce-api.php' );
   
    try {

        global $wpdb;
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        $selectedtaskArray['selectedtasks'] = json_decode(stripslashes($_POST['selectedtaskvalues']), true);
        $lastInsertId = contentmanagerlogging('Add new Product', "Admin Action", $addnewproduct_data, $user_ID, $user_info->user_email, "pre_action_data");
        $productimage=$_FILES['productimage'];
        $price = $addnewproduct_data['pprice'];
        $roleassign = $addnewproduct_data['roleassign'];
        $menu_order = $addnewproduct_data['menu_order'];
        $depositstype = $addnewproduct_data['depositstype'];
        $depositsamount = $addnewproduct_data['depositsamount'];
        
      
       
        
        $url = get_site_url();
        
        
        if(!empty($productimage)){
            $productpicrul = product_file_upload($productimage);
           
            
        }else{
                
                $productpicrul = 0;
                
        }
        
        if($addnewproduct_data['stockstatus'] == 'instock'){
            $instock = true;
        }else{
            $instock=false;
        }
        
        
        
        
        $options = array(
            'debug' => true,
            'return_as_array' => false,
            'validate_url' => false,
            'timeout' => 30,
            'ssl_verify' => false,
        );
        
            
        $objProduct = new WC_Product();
        
        
        if($addnewproduct_data['getcatname'] == "Booth"){
            $objProduct->set_name($addnewproduct_data['ptitle']); //Set product name.
            

            $objProduct->set_stock_quantity(1); //Set number of items available for sale.
            $objProduct->set_stock_status('instock');
        }else{
            $objProduct->set_name($addnewproduct_data['ptitle']); //Set product name.
            $objProduct->set_short_description($addnewproduct_data['pshortdescrpition']); //Set product short description.
            $objProduct->set_stock_quantity($addnewproduct_data['pquanitity']); //Set number of items available for sale.
            $objProduct->set_stock_status($instock);
            $objProduct->set_menu_order($menu_order); 
        
            
        }
        
        
        
        $objProduct->set_status($addnewproduct_data['pstatus']); //Set product status.
        $objProduct->set_featured(TRUE); //Set if the product is featured.                          | bool
        $objProduct->set_catalog_visibility('visible'); //Set catalog visibility.                   | string $visibility Options: 'hidden', 'visible', 'search' and 'catalog'.
        $objProduct->set_description($addnewproduct_data['pdescrpition']); //Set product description.
        
        $objProduct->set_price($price); //Set the product's active price.
        $objProduct->set_regular_price($price); //Set the product's regular price.
      
        $objProduct->set_manage_stock(TRUE); //Set if product manage stock.                         | bool
        $objProduct->set_backorders('no'); //Set backorders.                                        | string $backorders Options: 'yes', 'no' or 'notify'.
        $objProduct->set_sold_individually(FALSE);
        
        if(!empty($depositstype) && !empty($depositsamount)){
            
              
             
            
            $objProduct->update_meta_data('_wc_deposit_type', $depositstype);
            $objProduct->update_meta_data('_wc_deposit_amount', $depositsamount);
            $objProduct->update_meta_data('_wc_deposit_enabled', 'forced');
            
        }
        
        
         if(!empty($roleassign)){
            
          
                     $objProduct->update_meta_data('productlevel', $roleassign);
            
                }
        
       // $objProduct->set_tax_class($roleassign); 
        
        
        
        $objProduct->set_reviews_allowed(TRUE); //Set if reviews is allowed.                        | bool
        
                     
        
        $term_ids =[$addnewproduct_data['pcategories']];
        $objProduct->set_category_ids($term_ids); //Set the product categories.                   | array $term_ids List of terms IDs.
        $objProduct->set_tag_ids($term_ids); //Set the product tags.                              | array $term_ids List of terms IDs.
        $objProduct->set_image_id($productpicrul); //Set main image ID.                                         | int|string $image_id Product image id.
        //Set gallery attachment ids.                       | array $image_ids List of image ids.
        $new_product_id = $objProduct->save(); //Saving the data to create new product, it will return product ID.
        if(!empty($selectedtaskArray)){
            update_post_meta( $new_product_id, 'seletedtaskKeys', $selectedtaskArray );
        }
            contentmanagerlogging_file_upload($lastInsertId, serialize($new_product_id));
            echo 'created successfully';

        
    } catch (Exception $e) {

        contentmanagerlogging_file_upload($lastInsertId, serialize($e));

        return $e;
    }

    die();
}

function updateproducts($updateproducts_data) {

   // require_once('../../../wp-load.php');
    //require_once( 'temp/lib/woocommerce-api.php' );
   
    try {

        
        
        
        
        
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        $selectedtaskArray['selectedtasks'] = json_decode(stripslashes($_POST['selectedtaskvalues']), true);
        
        
      
        
        
        $lastInsertId = contentmanagerlogging('Update Product', "Admin Action", serialize($updateproducts_data), $user_ID, $user_info->user_email, "pre_action_data");
        
        $url = get_site_url();
        $productimage=$_FILES['updateproductimage'];
        $price = $updateproducts_data['pprice'];
        $productid = $updateproducts_data['productid'];
        $roleassign = $updateproducts_data['roleassign'];
        $menu_order = $updateproducts_data['menu_order'];
        $depositstype = $updateproducts_data['depositstype'];
        $depositsamount = $updateproducts_data['depositsamount'];
        
        
        
        $rootsite_url =  network_site_url();
        if(!empty($productimage)){
        $productpicrul = product_file_upload($productimage);
        
        //$productpicrul = str_replace($url.'/',"",$productpicrul);
      
        }else{
            if(empty($updateproducts_data['productimageurl'])){
                
                 $productpicrul = 0;
                
               
               
            }else{
                
                $productpicrul = $updateproducts_data['productimageurl'];
              
            }

            
        }
        
        
        
        
        
        
        if($updateproducts_data['stockstatus'] == 'instock'){
            $instock = true;
        }else{
            $instock=false;
        }
      /*  $data = [
                'title' => $updateproducts_data['ptitle'],
                'manage_stock' => true,
                'regular_price' => $price,
                'tax_class' =>$roleassign,
                'managing_stock'=>true,
                'stock_quantity' => $updateproducts_data['pquanitity'],
                'in_stock' => $instock,
                'status' => $updateproducts_data['pstatus'],
                'name' => $productName,
                'type' => 'simple',
                'description' => $updateproducts_data['pdescrpition'],
                'short_description' => $updateproducts_data['pshortdescrpition'],
                'enable_html_description'=> true,
                'enable_html_short_description'=> true,
                'categories' => [$updateproducts_data['pcategories']],
                'images' => Array ( '0' => Array( 'src' => $productpicrul['file'], 'title' => '21', 'position' => '0' ) )      
        
            ];
    
        
        $options = array(
            'debug' => true,
            'return_as_array' => false,
            'validate_url' => false,
            'timeout' => 30,
            'ssl_verify' => false,
        ); */
        
              
        //$objProduct = new WC_Product();
        $objProduct = wc_get_product( $productid );
       
        global $post;
        $terms = get_the_terms( $productid, 'product_cat' );
        
        
        if($terms[0]->slug == 'booth'){
            
            $objProduct->set_name($updateproducts_data['ptitle']); //Set product name.
        
                       
        }else{
            
            $objProduct->set_name($updateproducts_data['ptitle']); //Set product name.
            $objProduct->set_short_description($updateproducts_data['pshortdescrpition']); //Set product short description.
            $objProduct->set_stock_quantity($updateproducts_data['pquanitity']); //Set number of items available for sale.
            $objProduct->set_stock_status($instock); 
            $objProduct->set_menu_order($menu_order); 
       
        
            
        }
        
        
        
        $objProduct->set_status($updateproducts_data['pstatus']); //Set product status.
        $objProduct->set_description($updateproducts_data['pdescrpition']); //Set product description.
        
       
        $objProduct->set_price($price); //Set the product's active price.
        $objProduct->set_regular_price($price); //Set the product's regular price.
        
      //  $objProduct->set_tax_class($roleassign); 
       
        
        
        
        $term_ids =[$updateproducts_data['pcategories']];
        $objProduct->set_category_ids($term_ids); //Set the product categories.                   | array $term_ids List of terms IDs.
        $objProduct->set_tag_ids($term_ids); //Set the product tags.                              | array $term_ids List of terms IDs.
        $objProduct->set_image_id($productpicrul); //Set main image ID.                                         | int|string $image_id Product image id.
        //Set gallery attachment ids.                       | array $image_ids List of image ids.
        if(!empty($depositstype) && !empty($depositsamount)){
            $objProduct->update_meta_data('_wc_deposit_type', $depositstype);
            $objProduct->update_meta_data('_wc_deposit_amount', $depositsamount);
            $objProduct->update_meta_data('_wc_deposit_enabled', 'forced');
        }else{
            
            $objProduct->update_meta_data('_wc_deposit_type', "");
            $objProduct->update_meta_data('_wc_deposit_amount', "");
            $objProduct->update_meta_data('_wc_deposit_enabled', '');
        }
        if(!empty($roleassign)){
            
          
                     $objProduct->update_meta_data('productlevel', $roleassign);
            
        }
        $new_product_id = $objProduct->save();
        
        
        if(!empty($selectedtaskArray)){
            update_post_meta( $new_product_id, 'seletedtaskKeys', $selectedtaskArray );
        }
        
        
        
            contentmanagerlogging_file_upload($lastInsertId, serialize($new_product_id));
            $message = 'update successfully';
            echo $message;
            
          
        
    } catch (Exception $e) {
            
          
        print_r($e);
        
        contentmanagerlogging_file_upload($lastInsertId, serialize($e));
       
        return $e;
    }

    die();
}
function deleteproduct($deletproductid) {

    require_once('../../../wp-load.php');
    require_once( 'temp/lib/woocommerce-api.php' );
   
    try {

        global $wpdb;
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        $lastInsertId = contentmanagerlogging('Delete Product', "Admin Action", $deletproductid, $user_ID, $user_info->user_email, "pre_action_data");
        
        $postid = $deletproductid['postid'];
        
        
        $url = get_site_url();
        $options = array(
            'debug' => true,
            'return_as_array' => false,
            'validate_url' => false,
            'timeout' => 30,
            'ssl_verify' => false,
        );
        
        $woocommerce_rest_api_keys = get_option( 'ContenteManager_Settings' );
        $wooconsumerkey = $woocommerce_rest_api_keys['ContentManager']['wooconsumerkey'];
        $wooseceretkey = $woocommerce_rest_api_keys['ContentManager']['wooseceretkey'];
        $woocommerce_object = new WC_API_Client( $url, $wooconsumerkey, $wooseceretkey, $options );
        
        $result = $woocommerce_object->products->delete( $postid, true );
            
        contentmanagerlogging_file_upload($lastInsertId, serialize($result));
        echo 'successfully Delete';

        
    } catch (Exception $e) {

        contentmanagerlogging_file_upload($lastInsertId, serialize($e));

        return $e;
    }

   
}


function productclone($productcloneid) {

   // require_once('../../../wp-load.php');
   // require_once( 'temp/lib/woocommerce-api.php' );
   
    try {

        global $wpdb;
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        $lastInsertId = contentmanagerlogging('Clone Product', "Admin Action", $productcloneid, $user_ID, $user_info->user_email, "pre_action_data");
        
        $postid = $productcloneid['postid'];
        $url = get_site_url();
        
       
        
        $oldproduct = wc_get_product( $postid );
       
        $objProduct = new WC_Product();
            
        $objProduct->set_name($oldproduct->get_name().' (Copy)'); //Set product name.
        $objProduct->set_status($oldproduct->get_status()); //Set product status.
        $objProduct->set_featured(TRUE); //Set if the product is featured.                          | bool
        $objProduct->set_catalog_visibility('visible'); //Set catalog visibility.                   | string $visibility Options: 'hidden', 'visible', 'search' and 'catalog'.
        $objProduct->set_description($oldproduct->get_description()); //Set product description.
        $objProduct->set_short_description($oldproduct->get_short_description()); //Set product short description.
       
        $objProduct->set_price($oldproduct->get_price()); //Set the product's active price.
        $objProduct->set_regular_price($oldproduct->get_regular_price()); //Set the product's regular price.
      
        $objProduct->set_manage_stock(TRUE); //Set if product manage stock.                         | bool
        $objProduct->set_stock_quantity($oldproduct->get_stock_quantity()); //Set number of items available for sale.
        $objProduct->set_stock_status($oldproduct->get_stock_status()); //Set stock status.                               | string $status 'instock', 'outofstock' and 'onbackorder'
        $objProduct->set_backorders('no'); //Set backorders.                                        | string $backorders Options: 'yes', 'no' or 'notify'.
        $objProduct->set_sold_individually(FALSE);
        $get_results = get_post_meta($postid, "productlevel",true);
        $get_levels= get_post_meta($postid, "seletedtaskKeys",true);
        
        $get_deposit_type = get_post_meta($postid, "_wc_deposit_type",true);
        $get_deposit_amount = get_post_meta($postid, "_wc_deposit_amount",true);
        
        if(!empty($get_deposit_type) && !empty($get_deposit_amount)){
            
            $objProduct->update_meta_data('_wc_deposit_type', $get_deposit_type);
            $objProduct->update_meta_data('_wc_deposit_amount', $get_deposit_amount);
            $objProduct->update_meta_data('_wc_deposit_enabled', 'forced');
            
        }
        
        
        if (!empty($get_results)) {


            $objProduct->update_meta_data('productlevel', $get_results);
        }
        if(!empty($get_levels)){
           
            $objProduct->update_meta_data('seletedtaskKeys', $get_levels);
        }
     
        $objProduct->set_reviews_allowed(TRUE); //Set if reviews is allowed.                        | bool
        
      
       
        $objProduct->set_category_ids($oldproduct->get_category_ids()); //Set the product categories.                   | array $term_ids List of terms IDs.
        $objProduct->set_tag_ids($oldproduct->get_category_ids()); //Set the product tags.                              | array $term_ids List of terms IDs.
        $objProduct->set_image_id($oldproduct->get_image_id()); //Set main image ID.                                         | int|string $image_id Product image id.
        //Set gallery attachment ids.                       | array $image_ids List of image ids.
        $new_product_id = $objProduct->save(); //Saving the data to create new product, it will return product ID.
        
         contentmanagerlogging_file_upload($lastInsertId, serialize($new_product_id));
        echo 'successfully Cloned';

        
    } catch (Exception $e) {

        contentmanagerlogging_file_upload($lastInsertId, serialize($e));

        return $e;
    }

    die();
}

function product_file_upload($updatevalue){
   
    if(!empty($updatevalue)){
        if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
            //$upload_overrides = array( 'test_form' => false, 'mimes' => array('zip'=>'application/zip','eps'=>'application/postscript','ai' => 'application/postscript','jpg|jpeg|jpe' => 'image/jpeg','gif' => 'image/gif','png' => 'image/png','bmp' => 'image/bmp','pdf'=>'text/pdf','doc'=>'application/msword','docx'=>'application/msword','xlsx'=>'application/msexcel') );
        $mime_type = array(
	// Image formats
	'jpg|jpeg|jpe'                 => 'image/jpeg',
	'gif'                          => 'image/gif',
	'png'                          => 'image/png',
	'bmp'                          => 'image/bmp',
	'tif|tiff'                     => 'image/tiff',
	'ico'                          => 'image/x-icon',
        'eps'                          => 'application/postscript',
        'ai'                           =>  'application/postscript',
	// Video formats
	'asf|asx'                      => 'video/x-ms-asf',
	'wmv'                          => 'video/x-ms-wmv',
	'wmx'                          => 'video/x-ms-wmx',
	'wm'                           => 'video/x-ms-wm',
	'avi'                          => 'video/avi',
	'divx'                         => 'video/divx',
	'flv'                          => 'video/x-flv',
	'mov|qt'                       => 'video/quicktime',
	'mpeg|mpg|mpe'                 => 'video/mpeg',
	'mp4|m4v'                      => 'video/mp4',
	'ogv'                          => 'video/ogg',
	'webm'                         => 'video/webm',
	'mkv'                          => 'video/x-matroska',
	
	// Text formats
	'txt|asc|c|cc|h'               => 'text/plain',
	'csv'                          => 'text/csv',
	'tsv'                          => 'text/tab-separated-values',
	'ics'                          => 'text/calendar',
	'rtx'                          => 'text/richtext',
	'css'                          => 'text/css',
	'htm|html'                     => 'text/html',
	
	// Audio formats
	'mp3|m4a|m4b'                  => 'audio/mpeg',
	'ra|ram'                       => 'audio/x-realaudio',
	'wav'                          => 'audio/wav',
	'ogg|oga'                      => 'audio/ogg',
	'mid|midi'                     => 'audio/midi',
	'wma'                          => 'audio/x-ms-wma',
	'wax'                          => 'audio/x-ms-wax',
	'mka'                          => 'audio/x-matroska',
	
	// Misc application formats
	'rtf'                          => 'application/rtf',
	'js'                           => 'application/javascript',
	'pdf'                          => 'application/pdf',
	'swf'                          => 'application/x-shockwave-flash',
	'class'                        => 'application/java',
	'tar'                          => 'application/x-tar',
	'zip'                          => 'application/zip',
	'gz|gzip'                      => 'application/x-gzip',
	'rar'                          => 'application/rar',
	'7z'                           => 'application/x-7z-compressed',
	'exe'                          => 'application/x-msdownload',
	
	// MS Office formats
	'doc'                          => 'application/msword',
	'pot|pps|ppt'                  => 'application/vnd.ms-powerpoint',
	'wri'                          => 'application/vnd.ms-write',
	'xla|xls|xlt|xlw'              => 'application/vnd.ms-excel',
	'mdb'                          => 'application/vnd.ms-access',
	'mpp'                          => 'application/vnd.ms-project',
	'docx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	'docm'                         => 'application/vnd.ms-word.document.macroEnabled.12',
	'dotx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
	'dotm'                         => 'application/vnd.ms-word.template.macroEnabled.12',
	'xlsx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
	'xlsm'                         => 'application/vnd.ms-excel.sheet.macroEnabled.12',
	'xlsb'                         => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
	'xltx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
	'xltm'                         => 'application/vnd.ms-excel.template.macroEnabled.12',
	'xlam'                         => 'application/vnd.ms-excel.addin.macroEnabled.12',
	'pptx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
	'pptm'                         => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
	'ppsx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
	'ppsm'                         => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
	'potx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.template',
	'potm'                         => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
	'ppam'                         => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
	'sldx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
	'sldm'                         => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
	'onetoc|onetoc2|onetmp|onepkg' => 'application/onenote',
	
	// OpenOffice formats
	'odt'                          => 'application/vnd.oasis.opendocument.text',
	'odp'                          => 'application/vnd.oasis.opendocument.presentation',
	'ods'                          => 'application/vnd.oasis.opendocument.spreadsheet',
	'odg'                          => 'application/vnd.oasis.opendocument.graphics',
	'odc'                          => 'application/vnd.oasis.opendocument.chart',
	'odb'                          => 'application/vnd.oasis.opendocument.database',
	'odf'                          => 'application/vnd.oasis.opendocument.formula',
	
	// WordPerfect formats
	'wp|wpd'                       => 'application/wordperfect',
	
	// iWork formats
	'key'                          => 'application/vnd.apple.keynote',
	'numbers'                      => 'application/vnd.apple.numbers',
	'pages'                        => 'application/vnd.apple.pages',
);    
        $upload_overrides = array( 'test_form' => false,$mime_type);
        $file = wp_handle_upload( $updatevalue, $upload_overrides );
        if(!empty($file['file'])){
          
          
            
        
       
    $name = $updatevalue['name'];
    $ext  = pathinfo( $name, PATHINFO_EXTENSION );
    $name = wp_basename( $name, ".$ext" );
 
    $url = $file['url'];
    $type = $file['type'];
    $file = $file['file'];
    $title = sanitize_text_field( $name );
    $content = '';
    $excerpt = '';
 
   
    $attachment = array(
        'post_mime_type' => $type,
        'guid' => $url,
        'post_parent' => '',
        'post_title' => $title,
        'post_content' => $content,
        'post_excerpt' => $excerpt,
    );
    
    
  
 
    // This should never be set as it would then overwrite an existing attachment.
    unset( $attachment['ID'] );
 
    // Save the data
    $id = wp_insert_attachment( $attachment, $file, '', true );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );

// Generate the metadata for the attachment, and update the database record.
    $attach_data = wp_generate_attachment_metadata( $id, $file );
    wp_update_attachment_metadata( $id, $attach_data );
    
    return $id;
        }  
        
  }
    
}

