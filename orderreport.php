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
   
}else if ($_GET['contentManagerRequest'] == "productclone") {

    require_once('../../../wp-load.php');

    productclone($_POST);
   
}else if ($_GET['contentManagerRequest'] == "updateproducts") {

    require_once('../../../wp-load.php');
    $send_array_data_ofreg = $_POST;
    updateproducts($send_array_data_ofreg);
    die();
   
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


        $argsss = array(
            'numberposts' => -1,
            'post_type' => 'shop_order'
        );

        $all_posts = get_posts($argsss);

      //  echo '<pre>';
      //  print_r($all_posts);exit;
        
        $columns_headers = [];
        $columns_rows_data = [];




        $columns_list_order_report[0]['title'] = 'Order ID';
        $columns_list_order_report[0]['type'] = 'string';
        $columns_list_order_report[0]['key'] = 'ID';

        $columns_list_order_report[1]['title'] = 'Order Date';
        $columns_list_order_report[1]['type'] = 'date';
        $columns_list_order_report[1]['key'] = 'post_date';

        $columns_list_order_report[2]['title'] = 'Order Status';
        $columns_list_order_report[2]['type'] = 'string';
        $columns_list_order_report[2]['key'] = 'post_status';

        $columns_list_order_report_postmeta[1]['title'] = 'Email';
        $columns_list_order_report_postmeta[1]['type'] = 'string';
        $columns_list_order_report_postmeta[1]['key'] = '_billing_email';

        $columns_list_order_report_postmeta[2]['title'] = 'First Name';
        $columns_list_order_report_postmeta[2]['type'] = 'string';
        $columns_list_order_report_postmeta[2]['key'] = '_billing_first_name';

        $columns_list_order_report_postmeta[3]['title'] = 'Last Name';
        $columns_list_order_report_postmeta[3]['type'] = 'string';
        $columns_list_order_report_postmeta[3]['key'] = '_billing_last_name';


        $columns_list_order_report_postmeta[4]['title'] = 'Company Name';
        $columns_list_order_report_postmeta[4]['type'] = 'string';
        $columns_list_order_report_postmeta[4]['key'] = '_billing_company';


        $columns_list_order_report_postmeta[5]['title'] = 'Order Currency';
        $columns_list_order_report_postmeta[5]['type'] = 'string';
        $columns_list_order_report_postmeta[5]['key'] = '_order_currency';

        $columns_list_order_report_postmeta[6]['title'] = 'User IP Address';
        $columns_list_order_report_postmeta[6]['type'] = 'string';
        $columns_list_order_report_postmeta[6]['key'] = '_customer_ip_address';


        $columns_list_order_report_postmeta[7]['title'] = 'Payment Method';
        $columns_list_order_report_postmeta[7]['type'] = 'string';
        $columns_list_order_report_postmeta[7]['key'] = '_payment_method_title';


        $columns_list_order_report_postmeta[8]['title'] = 'Order Discount';
        $columns_list_order_report_postmeta[8]['type'] = 'num-fmt';
        $columns_list_order_report_postmeta[8]['key'] = '_cart_discount';


        $columns_list_order_report_postmeta[9]['title'] = 'Order Total Amount';
        $columns_list_order_report_postmeta[9]['type'] = 'num-fmt';
        $columns_list_order_report_postmeta[9]['key'] = '_order_total';


        $columns_list_order_report_postmeta[10]['title'] = 'Phone Number';
        $columns_list_order_report_postmeta[10]['type'] = 'string';
        $columns_list_order_report_postmeta[10]['key'] = '_billing_phone';

        $columns_list_order_report_postmeta[11]['title'] = 'Address Line 1';
        $columns_list_order_report_postmeta[11]['key'] = '_billing_address_1';
        $columns_list_order_report_postmeta[11]['type'] = 'string';

        $columns_list_order_report_postmeta[12]['title'] = 'Address Line 2';
        $columns_list_order_report_postmeta[12]['key'] = '_billing_address_2';
        $columns_list_order_report_postmeta[12]['type'] = 'string';

        $columns_list_order_report_postmeta[13]['title'] = 'Zipcode';
        $columns_list_order_report_postmeta[13]['key'] = '_billing_postcode';
        $columns_list_order_report_postmeta[13]['type'] = 'string';

        $columns_list_order_report_postmeta[14]['title'] = 'City';
        $columns_list_order_report_postmeta[14]['key'] = '_billing_city';
        $columns_list_order_report_postmeta[14]['type'] = 'string';

        $columns_list_order_report_postmeta[15]['title'] = 'State';
        $columns_list_order_report_postmeta[15]['key'] = '_billing_state';
        $columns_list_order_report_postmeta[15]['type'] = 'string';

        $columns_list_order_report_postmeta[16]['title'] = 'Country';
        $columns_list_order_report_postmeta[16]['key'] = '_billing_country';
        $columns_list_order_report_postmeta[16]['type'] = 'string';

        $columns_list_order_report_postmeta[17]['title'] = 'Stripe Fee';
        $columns_list_order_report_postmeta[17]['type'] = 'num-fmt';
        $columns_list_order_report_postmeta[17]['key'] = 'Stripe Fee';

        $columns_list_order_report_postmeta[18]['title'] = 'Net Revenue From Stripe';
        $columns_list_order_report_postmeta[18]['type'] = 'num-fmt';
        $columns_list_order_report_postmeta[18]['key'] = 'Net Revenue From Stripe';

        $columns_list_order_report_postmeta[19]['title'] = 'Paymnet Date';
        $columns_list_order_report_postmeta[19]['type'] = 'date';
        $columns_list_order_report_postmeta[19]['key'] = '_paid_date';

        $columns_list_order_report_postmeta[20]['title'] = 'Transaction ID';
        $columns_list_order_report_postmeta[20]['type'] = 'string';
        $columns_list_order_report_postmeta[20]['key'] = '_transaction_id';

        $columns_list_order_report_postmeta[21]['title'] = 'Products';
        $columns_list_order_report_postmeta[21]['type'] = 'string';
        $columns_list_order_report_postmeta[21]['key'] = 'Products';

        $columns_list_order_report_postmeta[22]['title'] = 'Account Holder Email';
        $columns_list_order_report_postmeta[22]['type'] = 'string';
        $columns_list_order_report_postmeta[22]['key'] = 'Account Holder Email';



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
            $column_row;
            ksort($post_meta);
            foreach ($columns_list_order_report as $col_keys_index => $col_keys_title) {

                if ($columns_list_order_report[$col_keys_index]['key'] == 'post_date') {

                    if (!empty($header_array[$columns_list_order_report[$col_keys_index]['key']])) {
                        $time = strtotime($header_array[$columns_list_order_report[$col_keys_index]['key']]);
                        $newformat = $time * 1000; // date('d-M-Y  H:i:s', $time);
                    } else {
                        $newformat = '';
                    }
                    $column_row[$columns_list_order_report[$col_keys_index]['title']] = $newformat;
                    // echo '<pre>';
                    //print_r($column_row);exit;
                } else {


                    $column_row[$columns_list_order_report[$col_keys_index]['title']] = $header_array[$columns_list_order_report[$col_keys_index]['key']];
                }
            }
            foreach ($columns_list_order_report_postmeta as $col_keys_index => $col_keys_title) {
                if ($columns_list_order_report_postmeta[$col_keys_index]['key'] == '_paid_date') {

                    if (!empty($post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0])) {
                        $time = strtotime($post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0]);
                        $newformat = $time * 1000; //date('d-M-Y H:i:s', $time);
                    } else {
                        $newformat = '';
                    }
                    $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = $newformat;
                } else if ($columns_list_order_report_postmeta[$col_keys_index]['key'] == 'Products' || $columns_list_order_report_postmeta[$col_keys_index]['key'] == 'Account Holder Email') {
                    
                } else {
                    if ($columns_list_order_report_postmeta[$col_keys_index]['type'] == 'num' || $columns_list_order_report_postmeta[$col_keys_index]['type'] == 'num-fmt') {

                        $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = round($post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0]);
                    } else {
                        $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = $post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0];
                    }
                }
            }



            $userdata = get_userdata($post_meta['_customer_user'][0]);
            $accountholder_email = $userdata->user_email;

            $get_items_sql = "SELECT items.order_item_id,items.order_item_name,Pid.meta_value as Pid,Qty.meta_value as Qty FROM wp_woocommerce_order_items AS items LEFT JOIN wp_woocommerce_order_itemmeta AS Pid ON(items.order_item_id = Pid.order_item_id)LEFT JOIN wp_woocommerce_order_itemmeta AS Qty ON(items.order_item_id = Qty.order_item_id) WHERE items.order_id = " . $header_array['ID'] . " AND Qty.meta_key IN ( '_qty' )AND Pid.meta_key IN ( '_product_id' ) ORDER BY items.order_item_id";
            $products = $wpdb->get_results($get_items_sql);
            $order_productsnames = "";
            foreach ($products as $single_product => $productname) {

                $order_productsnames.= $productname->order_item_name . '<br>';
            }
            $column_row['Products'] = $order_productsnames;
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
    require_once( 'temp/lib/woocommerce-api.php' );
    try {

        global $wpdb;
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        $lastInsertId = contentmanagerlogging('Manage Products  Report Date', "Admin Action", '', $user_ID, $user_info->user_email, "pre_action_data");
        $url = 'https://'.$_SERVER['SERVER_NAME'];
        
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

        $columns_list_order_report[1]['title'] = 'Publish Date';
        $columns_list_order_report[1]['type'] = 'date';
        $columns_list_order_report[1]['key'] = 'created_at';


        

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
        
        $columns_list_order_report[6]['title'] = 'Assign Level';
        $columns_list_order_report[6]['type'] = 'string';
        $columns_list_order_report[6]['key'] = 'tax_class';


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

           
           $action_data = '<div style="width: 140px !important;"class = "hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><i data-toggle="tooltip" class="hi-icon fa fa-clone saveeverything" id = "' . $single_product->id . '" onclick="createproductclone(this)" title="" data-original-title="Create a clone"></i><a href="/add-new-product/?productid='. $single_product->id .'"  ><i data-toggle = "tooltip" title = ""  id = "' . $single_product->id . '" class = "hi-icon fusion-li-icon fa fa-pencil-square fa-2x" data-original-title = "Edit Product"></i></a><i   id = "' . $single_product->id . '" data-toggle = "tooltip" title = "" onclick="deleteproduct(this)" class = "hi-icon fusion-li-icon fa fa-times-circle fa-2x" data-original-title = "Delete Product"></i><a href="'.$single_product->permalink.'" target="_blank" ><i onclick = "delete_product(this)" id = "' . $single_product->id . '" data-toggle = "tooltip" title = "" class = "hi-icon fusion-li-icon fa fa-eye fa-2x" data-original-title = "View Product" ></i></a></div>';
           $column_row['Action'] = $action_data;
            
            $url = wp_get_attachment_thumb_url($single_product->images[0]->id);
           
           
            if(!empty($url)){
               $column_row['Icon'] = '<img width="40" height ="40" src="'.  $url .'" />'; 
            }else{
                
                $column_row['Icon'] ="";
            }
            

            foreach ($columns_list_order_report as $col_keys_index => $col_keys_title) {
                $findingvaluekey = $columns_list_order_report[$col_keys_index]['key'];
                if ($columns_list_order_report[$col_keys_index]['key'] == 'created_at') {

                    if (!empty($single_product->$findingvaluekey)) {
                        $time = strtotime($single_product->$findingvaluekey);
                        $newformat = $time * 1000; // date('d-M-Y  H:i:s', $time);
                    } else {
                        $newformat = '';
                    }
                    $column_row[$columns_list_order_report[$col_keys_index]['title']] = $newformat;
                    
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

    require_once('../../../wp-load.php');
    require_once( 'temp/lib/woocommerce-api.php' );
   
    try {

        global $wpdb;
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        $lastInsertId = contentmanagerlogging('Add new Product', "Admin Action", $addnewproduct_data, $user_ID, $user_info->user_email, "pre_action_data");
        $productimage=$_FILES['productimage'];
        $price = $addnewproduct_data['pprice'];
        $roleassign = $addnewproduct_data['roleassign'];
        $url = 'https://'.$_SERVER['SERVER_NAME'];
        
        
        if(!empty($productimage)){
            $productpicrul = resource_file_upload($productimage);
        
            
        }else{
                
            $productpicrul=$url."/wp-content/plugins/woocommerce/assets/images/placeholder.png";
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
        $woocommerce_rest_api_keys = get_option( 'ContenteManager_Settings' );
        $wooconsumerkey = $woocommerce_rest_api_keys['ContentManager']['wooconsumerkey'];
        $wooseceretkey = $woocommerce_rest_api_keys['ContentManager']['wooseceretkey'];
        $woocommerce_object = new WC_API_Client( $url, $wooconsumerkey, $wooseceretkey, $options );
       
            $data = [
                'title' => $addnewproduct_data['ptitle'],
                'manage_stock' => true,
                'regular_price' => $price,
                'tax_class' =>$roleassign,
                'managing_stock'=>true,
                'stock_quantity' => $addnewproduct_data['pquanitity'],
                'in_stock' => $instock,
                'status' => $addnewproduct_data['pstatus'],
                'name' => $productName,
                'type' => 'simple',
                'description' => $addnewproduct_data['pdescrpition'],
                'short_description' => $addnewproduct_data['pshortdescrpition'],
                'enable_html_description'=> true,
                'enable_html_short_description'=> true,
                'categories' => [$addnewproduct_data['pcategories']],
                'images' => [
                    [
                        'src' => $productpicrul,
                        'position' => 0
                    ]
                ]
            ];
            $result = $woocommerce_object->products->create($data);
            contentmanagerlogging_file_upload($lastInsertId, serialize($result));
            echo 'created successfully';

        
    } catch (Exception $e) {

        contentmanagerlogging_file_upload($lastInsertId, serialize($e));

        return $e;
    }

    die();
}

function updateproducts($updateproducts_data) {

    require_once('../../../wp-load.php');
    require_once( 'temp/lib/woocommerce-api.php' );
   
    try {

      
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        
        $lastInsertId = contentmanagerlogging('Update Product', "Admin Action", serialize($updateproducts_data), $user_ID, $user_info->user_email, "pre_action_data");
        
        $url = 'https://'.$_SERVER['SERVER_NAME'];
        $productimage=$_FILES['updateproductimage'];
        $price = $updateproducts_data['pprice'];
        $productid = $updateproducts_data['productid'];
        $roleassign = $updateproducts_data['roleassign'];
       
        if(!empty($productimage)){
        $productpicrul = resource_file_upload($productimage);
        
        }else{
            if(empty($updateproducts_data['productimageurl'])){
                
               
                $productpicrul=$url."/wp-content/plugins/woocommerce/assets/images/placeholder.png";
               
            
            }else{
              $productpicrul= $updateproducts_data['productimageurl'];
              
            }

            
        }
        
        if($updateproducts_data['stockstatus'] == 'instock'){
            $instock = true;
        }else{
            $instock=false;
        }
        $data = [
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
                'images' => [
                    [
                        'src' => $productpicrul,
                        'position' => 0
                    ]
                ]
            ];
        
       
        
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
       
            
            $result = $woocommerce_object->products->update( $productid, $data );
            contentmanagerlogging_file_upload($lastInsertId, serialize($result));
            echo 'update successfully';

        
    } catch (Exception $e) {

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
        
        
        $url = 'https://'.$_SERVER['SERVER_NAME'];
        
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

    die();
}


function productclone($productcloneid) {

    require_once('../../../wp-load.php');
    require_once( 'temp/lib/woocommerce-api.php' );
   
    try {

        global $wpdb;
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        $lastInsertId = contentmanagerlogging('Clone Product', "Admin Action", $productcloneid, $user_ID, $user_info->user_email, "pre_action_data");
        
        $postid = $productcloneid['postid'];
      
        
        
        $url = 'https://'.$_SERVER['SERVER_NAME'];
        
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
       $get_product_clone = $woocommerce_object->products->get($postid);
        
        $product_cat_list = $woocommerce_object->products->get_categories() ;
        foreach ($product_cat_list->product_categories as $key => $value) {
             
             if($get_product_clone->product->categories[0] == $value->name){
                  $catid =  $value->id;
             }
         }
       
       
        $data = [
                'title' => $get_product_clone->product->title,
                'manage_stock' => true,
                'tax_class' =>$get_product_clone->product->tax_class,
                'regular_price' => $get_product_clone->product->regular_price,
                'managing_stock'=>true,
                "stock_quantity"=> $get_product_clone->product->stock_quantity,
                "in_stock"=> $get_product_clone->product->in_stock,
                'name' => $get_product_clone->product->name,
                'type' => 'simple',
                'enable_html_description'=> true,
                'enable_html_short_description'=> true,
                'description' => $get_product_clone->product->description,
                'short_description' => $get_product_clone->product->short_description,
                'categories' => [$catid],
                
                'images' => [
                    [
                        'src' =>  $get_product_clone->product->images[0]->src,
                        'position' => 0
                    ]
                ]
            ];
        
        
        
        $result = $woocommerce_object->products->create($data);     
        contentmanagerlogging_file_upload($lastInsertId, serialize($result));
        echo 'successfully Cloned';

        
    } catch (Exception $e) {

        contentmanagerlogging_file_upload($lastInsertId, serialize($e));

        return $e;
    }

    die();
}

