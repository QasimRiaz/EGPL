<?php


if($_GET['contentManagerRequest'] == "order_report_savefilters") {        
    require_once('../../../wp-load.php');
    
    //echo '<pre>';
    //print_r($_POST);exit;
    $orderreportname = $_POST['orderreportname'];
    $orderreportfilterdata = stripslashes($_POST['orderreportfiltersdata']);
    $showcolumnslist = stripslashes($_POST['showcolumnslist']);
    $ordercolunmtype = $_POST['orderbytype'];
    $ordercolunmname = $_POST['orderbycolname'];
    order_report_savefilters($orderreportname,$orderreportfilterdata,$showcolumnslist,$ordercolunmtype,$ordercolunmname);
   
  
}else if($_GET['contentManagerRequest'] == "order_report_removefilter") {
    
    require_once('../../../wp-load.php');
    $orderreportname = $_POST['orderreportname'];
    order_report_removefilter($orderreportname);
    
}else if($_GET['contentManagerRequest'] == "get_orderreport_detail") {
    
    require_once('../../../wp-load.php');
    $orderreportname = $_POST['reportname'];
    get_orderreport_detail($orderreportname);
    
}else if($_GET['contentManagerRequest'] == "loadorderreport") {
    
    require_once('../../../wp-load.php');
   
    loadorderreport();
    
}
 function order_report_savefilters($orderreportname,$orderreportfilterdata,$showcolumnslist,$ordercolunmtype,$ordercolunmname){
    
    require_once('../../../wp-load.php');
    
    try{
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('Saved Order Report',"Admin Action",$orderreportfilterdata,$user_ID,$user_info->user_email,"pre_action_data");
        
        $settitng_key = 'ContenteManager_Orderreport_settings';
        
        $orderreportfilterdata = stripslashes($orderreportfilterdata);
        
        $order_reportsaved_list = get_option( $settitng_key );
        $order_reportsaved_list[$orderreportname][0] = $orderreportfilterdata;
        $order_reportsaved_list[$orderreportname][1] = $showcolumnslist;
        $order_reportsaved_list[$orderreportname][2] = $ordercolunmtype;
        $order_reportsaved_list[$orderreportname][3] = $ordercolunmname;
        
        update_option($settitng_key, $order_reportsaved_list);
        $order_reportsaved_list = get_option( $settitng_key );
        contentmanagerlogging_file_upload ($lastInsertId,serialize($order_reportsaved_list));
         foreach ($order_reportsaved_list as $key => $value) {
          $orderlist[]= $key;   
         }
        
        echo   json_encode($orderlist);
        
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
 
 die();  
}

function  order_report_removefilter($orderreportname){
    
    require_once('../../../wp-load.php');
    
    try{
        
        
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('Remove Order Report',"Admin Action",$orderreportdata,$user_ID,$user_info->user_email,"pre_action_data");
        
        
        $settitng_key = 'ContenteManager_Orderreport_settings';
        $order_reportsaved_list = get_option( $settitng_key );
       
        unset($order_reportsaved_list[$orderreportname]);
        //echo '<pre>';
        //print_r($order_reportsaved_list);exit;
        update_option($settitng_key, $order_reportsaved_list);
        
        $order_reportsaved_list = get_option( $settitng_key );
        contentmanagerlogging_file_upload ($lastInsertId,serialize($order_reportsaved_list));
         foreach ($order_reportsaved_list as $key => $value) {
          $orderlist[]= $key;   
         }
        
        echo   json_encode($orderlist);
        
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
 
 die();  
}

function  get_orderreport_detail($orderreportname){
    
    require_once('../../../wp-load.php');
    
    try{
        
        
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('Load Order Report',"Admin Action",$orderreportdata,$user_ID,$user_info->user_email,"pre_action_data");
        
        
        $settitng_key = 'ContenteManager_Orderreport_settings';
        $order_reportsaved_list = get_option( $settitng_key );
       
        
        contentmanagerlogging_file_upload ($lastInsertId,serialize($order_reportsaved_list));
        
        echo   json_encode($order_reportsaved_list[$orderreportname]);
        
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
 
 die();  
}

function  loadorderreport(){
    
    require_once('../../../wp-load.php');
    
    try{
        
        global $wpdb;
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('Get Order Report Date',"Admin Action",$orderreportdata,$user_ID,$user_info->user_email,"pre_action_data");
        
        
        $args = array(
            'numberposts' => -1,
            'post_type' => 'shop_order'
        );

        $all_posts = get_posts($args);

        //echo '<pre>';
        // print_r($all_posts);exit;
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


            $colums_array_data['title'] =  $columns_list_order_report[$col_keys]['title'];
            $colums_array_data['type']  =  $columns_list_order_report[$col_keys]['type'];
            $colums_array_data['data']  =  $columns_list_order_report[$col_keys]['title'];
            array_push($columns_headers, $colums_array_data);
        }
        foreach ($columns_list_order_report_postmeta as $col_keys => $col_keys_title) {


            $colums_array_data['title'] = $columns_list_order_report_postmeta[$col_keys]['title'];
            $colums_array_data['data']  = $columns_list_order_report_postmeta[$col_keys]['title'];
            $colums_array_data['type']  = $columns_list_order_report_postmeta[$col_keys]['type'];
            
            array_push($columns_headers, $colums_array_data);
        }
        foreach ($all_posts as $single_post) {

            $header_array = get_object_vars($single_post);
            $post_meta = get_post_meta($header_array['ID']);
            $column_row;
            ksort($post_meta);
            foreach ($columns_list_order_report as $col_keys_index => $col_keys_title) {

                if ($columns_list_order_report[$col_keys_index]['key'] == 'post_date') {

                    if(!empty($header_array[$columns_list_order_report[$col_keys_index]['key']])){
                        $time = strtotime($header_array[$columns_list_order_report[$col_keys_index]['key']]);
                        $newformat =$time*1000;// date('d-M-Y  H:i:s', $time);
                    }else{
                       $newformat=''; 
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
                    
                    if(!empty($post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0])){
                        $time = strtotime($post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0]);
                        $newformat = $time*1000;//date('d-M-Y H:i:s', $time);
                    }else{
                        $newformat='';
                    }
                    $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = $newformat;
                } else if ($columns_list_order_report_postmeta[$col_keys_index]['key'] == 'Products' || $columns_list_order_report_postmeta[$col_keys_index]['key'] == 'Account Holder Email') {
                    
                } else {
                    if($columns_list_order_report_postmeta[$col_keys_index]['type'] == 'num' || $columns_list_order_report_postmeta[$col_keys_index]['type'] == 'num-fmt'){
                        
                        $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = round($post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0]);
                    }else{
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

        contentmanagerlogging_file_upload ($lastInsertId,serialize($orderreport_all_col_rows_data));
        
        echo   json_encode($columns_rows_data).'//'.json_encode($columns_headers);
        
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
 
 die();  
}
