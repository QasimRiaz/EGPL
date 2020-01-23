<?php 

add_action('rest_api_init', function() {
	register_rest_route('w1/v1', 'createuser', [
		'methods' => 'POST',
		'callback' => 'createuser',
	]);
        
        register_rest_route('w1/v1', 'updateuser', [
		'methods' => 'POST',
		'callback' => 'updateuser',
	]);
        
        register_rest_route('w1/v1', 'updatetask', [
		'methods' => 'POST',
		'callback' => 'updatetask',
	]);
        
        register_rest_route('w1/v1', 'getuserfields', [
		'methods' => 'GET',
		'callback' => 'getuserfields',
	]);
        
        register_rest_route('w1/v1', 'getorders', [
		'methods' => 'GET',
		'callback' => 'getorders',
	]);
	
});
function getuserfields(){
    
    
    try {
    
        require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/egpl-custome-functions.php';
        $GetAllcustomefields = new EGPLCustomeFunctions();
        $additional_fields = $GetAllcustomefields->getAllcustomefields();
        usort($additional_fields, 'sortByOrder');
    
        $index_count=0;
        foreach ($additional_fields as $key=>$value){
            
           
            if($value['fieldType']!="html" && $value['SystemfieldInternal']!="checked"){
                
                
                $requiredStatus = $value['fieldrequriedstatus'];
                if($requiredStatus == "checked"){
                    
                   $columns_list_attitional[$index_count]['required']  = true; 
                }
                $columns_list_attitional[$index_count]['key']  = $site_prefix.$value['fielduniquekey'];
                $columns_list_attitional[$index_count]['type']= $value['fieldType'];
                $columns_list_attitional[$index_count]['label']= $value['fieldName'];
               
                
                $index_count++;
            }
            
        }
     
        
    
    
    
     
     echo json_encode($columns_list_attitional);
     die();
    }catch (Exception $e) {

      

        return $e;
    }
    
    
    
    
}



function getorders(){
    
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
        
        
        
          
        $columns_list_order_report_postmeta[5]['title'] = 'Product Details';
        $columns_list_order_report_postmeta[5]['type'] = 'string';
        $columns_list_order_report_postmeta[5]['key'] = 'Products';
        
        
        $columns_list_order_report_postmeta[6]['title'] = 'Payment Method';
        $columns_list_order_report_postmeta[6]['type'] = 'string';
        $columns_list_order_report_postmeta[6]['key'] = '_payment_method_title';
        
        
        $columns_list_order_report_postmeta[7]['title'] = 'Payment Date';
        $columns_list_order_report_postmeta[7]['type'] = 'date';
        $columns_list_order_report_postmeta[7]['key'] = '_paid_date';
        
        $columns_list_order_report_postmeta[8]['title'] = 'Order Note';
        $columns_list_order_report_postmeta[8]['type'] = 'string';
        $columns_list_order_report_postmeta[8]['key'] = '_order_custome_note';
        
        
        $columns_list_order_report_postmeta[9]['title'] = 'Age';
        $columns_list_order_report_postmeta[9]['type'] = 'string';
        $columns_list_order_report_postmeta[9]['key'] = '_product_age_calculate';
        
        $columns_list_order_report_postmeta[10]['title'] = 'First Name';
        $columns_list_order_report_postmeta[10]['type'] = 'string';
        $columns_list_order_report_postmeta[10]['key'] = '_billing_first_name';
        
        $columns_list_order_report_postmeta[11]['title'] = 'Last Name';
        $columns_list_order_report_postmeta[11]['type'] = 'string';
        $columns_list_order_report_postmeta[11]['key'] = '_billing_last_name';
        
        $columns_list_order_report_postmeta[12]['title'] = 'Email';
        $columns_list_order_report_postmeta[12]['type'] = 'string';
        $columns_list_order_report_postmeta[12]['key'] = '_billing_email';
        
        
        $columns_list_order_report_postmeta[13]['title'] = 'Phone Number';
        $columns_list_order_report_postmeta[13]['type'] = 'string';
        $columns_list_order_report_postmeta[13]['key'] = '_billing_phone';
        
        


        $columns_list_order_report_postmeta[14]['title'] = 'Order Currency';
        $columns_list_order_report_postmeta[14]['type'] = 'string';
        $columns_list_order_report_postmeta[14]['key'] = '_order_currency';

        $columns_list_order_report_postmeta[15]['title'] = 'User IP Address';
        $columns_list_order_report_postmeta[15]['type'] = 'string';
        $columns_list_order_report_postmeta[15]['key'] = '_customer_ip_address';


        $columns_list_order_report_postmeta[16]['title'] = 'Address Line 1';
        $columns_list_order_report_postmeta[16]['key'] = '_billing_address_1';
        $columns_list_order_report_postmeta[16]['type'] = 'string';

        $columns_list_order_report_postmeta[17]['title'] = 'Address Line 2';
        $columns_list_order_report_postmeta[17]['key'] = '_billing_address_2';
        $columns_list_order_report_postmeta[17]['type'] = 'string';

        $columns_list_order_report_postmeta[18]['title'] = 'Zipcode';
        $columns_list_order_report_postmeta[18]['key'] = '_billing_postcode';
        $columns_list_order_report_postmeta[18]['type'] = 'string';

        $columns_list_order_report_postmeta[19]['title'] = 'City';
        $columns_list_order_report_postmeta[19]['key'] = '_billing_city';
        $columns_list_order_report_postmeta[19]['type'] = 'string';

        $columns_list_order_report_postmeta[20]['title'] = 'State';
        $columns_list_order_report_postmeta[20]['key'] = '_billing_state';
        $columns_list_order_report_postmeta[20]['type'] = 'string';

        $columns_list_order_report_postmeta[21]['title'] = 'Country';
        $columns_list_order_report_postmeta[21]['key'] = '_billing_country';
        $columns_list_order_report_postmeta[21]['type'] = 'string';

        $columns_list_order_report_postmeta[22]['title'] = 'Stripe Fee';
        $columns_list_order_report_postmeta[22]['type'] = 'num-fmt';
        $columns_list_order_report_postmeta[22]['key'] = '_stripe_fee';

        $columns_list_order_report_postmeta[23]['title'] = 'Net Revenue From Stripe';
        $columns_list_order_report_postmeta[23]['type'] = 'num-fmt';
        $columns_list_order_report_postmeta[23]['key'] = '_stripe_net';

        $columns_list_order_report_postmeta[24]['title'] = 'Order ID';
        $columns_list_order_report_postmeta[24]['type'] = 'string';
        $columns_list_order_report_postmeta[24]['key'] = 'ID';
        
        
        $columns_list_order_report_postmeta[25]['title'] = 'Initial Order ID';
        $columns_list_order_report_postmeta[25]['type'] = 'string';
        $columns_list_order_report_postmeta[25]['key'] = '_initial_payment_order_id';
        

        $columns_list_order_report_postmeta[26]['title'] = 'Transaction ID';
        $columns_list_order_report_postmeta[26]['type'] = 'string';
        $columns_list_order_report_postmeta[26]['key'] = '_transaction_id';

        

        $columns_list_order_report_postmeta[27]['title'] = 'Account Holder Email';
        $columns_list_order_report_postmeta[27]['type'] = 'string';
        $columns_list_order_report_postmeta[27]['key'] = 'Account Holder Email';
        
          $columns_list_order_report_postmeta[28]['title'] = 'Order Discount';
        $columns_list_order_report_postmeta[28]['type'] = 'num-fmt';
        $columns_list_order_report_postmeta[28]['key'] = '_cart_discount';

        
       

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
            $column_row['Product Details'] = '<a style="cursor: pointer;" onclick="getOrderproductdetail('.$header_array['ID'].')">Product Details</a>';//$order_productsnames;
            $column_row['Account Holder Email'] = $accountholder_email;
            array_push($columns_rows_data, $column_row);
        }

        $orderreport_all_col_rows_data['columns'] = $columns_headers;
        $orderreport_all_col_rows_data['data'] = $columns_rows_data;

        contentmanagerlogging_file_upload($lastInsertId, serialize($orderreport_all_col_rows_data));

        echo json_encode($columns_rows_data) . '//' . json_encode($columns_headers);
    }catch (Exception $e) {

      

        return $e;
    }
    
    
    
    
}

function createuser(){
    
    try {
    
      
        
    $newContactUserData =  json_decode(file_get_contents('php://input')) ;
    $lastInsertId = contentmanagerlogging('Zapier Action Create User', "Admin Action", "", "", "", $newContactUserData);
    
    if(!empty($newContactUserData)){
        $resultRegistratedUser = CreateNewUser($newContactUserData);
    }else{
        
        $resultRegistratedUser["error"] = "Something went going wrong. Please Connect with App administrative.";
        $resultRegistratedUser = json_encode($resultRegistratedUser);
    }
    return $resultRegistratedUser;
    
    }catch (Exception $e) {

      

        return $e;
    }
    
    
    
    
}


function updateuser(){
    
    try {
    
      
        
    $updateContactUserData =  json_decode(file_get_contents('php://input')) ;
    $lastInsertId = contentmanagerlogging('Zapier Action Update User', "Admin Action", "", "", "", $updateContactUserData);
    
    if(!empty($updateContactUserData)){
        
        $resultRegistratedUser = UpdateNewUser($updateContactUserData);
        
    }else{
        
        $resultRegistratedUser["error"] = "Something went going wrong. Please Connect with App administrative.";
        $resultRegistratedUser = json_encode($resultRegistratedUser);
    }
    return $resultRegistratedUser;
    
    }catch (Exception $e) {

      

        return $e;
    }
    
    
    
    
}


function UpdateNewUser($updateContactUserData){
    
     try{
         
         global $wpdb;
         $site_prefix = $wpdb->get_blog_prefix();
         
        
         
        $external_reference_id_zapier = $updateContactUserData->external_reference_id_zapier;
          
        $args = array(
	'order'          => 'ASC',
	'orderby'        => 'display_name',
	'meta_query'     => array(
		'relation' => 'AND',
		array(
			'key'     => $site_prefix.'external_reference_id_zapier',
			'value'   => $external_reference_id_zapier,
			'compare' => '=',
		),
		
	)
        );


        
        $user_query = new WP_User_Query( $args );
        $authors = $user_query->get_results();
         
        
        if(!empty($authors)){
            
            
            $user_id = $authors[0]->ID;
            $user_data = get_userdata($authors[0]->ID);
            
             $role = $updateContactUserData->Role;
             $roleKeyValue = "";
            
    
                if (is_multisite()) {
                    $blog_id = get_current_blog_id();
                    $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
                }else{
                    $get_all_roles_array = 'wp_user_roles';
                }
                $get_all_roles = get_option($get_all_roles_array);
                foreach ($get_all_roles as $key => $item) {
                     if($role == $item['name']){
                         $role = $key;
                         $roleKeyValue = $key;

                     }
                }
            if(empty($role) || empty($roleKeyValue)){
        
                $message['message'] = 'Failed to update user due to User Level not vaild.';
                
            }else{
                
            if($updateContactUserData->Semail != $user_data->user_email){
            
                
                $lastInsertId = contentmanagerlogging('Update user email Zapier Request',"Admin Action",serialize($updateContactUserData),''.$user_id,$user_data->user_email,"pre_action_data");
                $newemail = $updateContactUserData->Semail;
                
               
                $email_status = isValidEmail($newemail);
                if($email_status){
                    if( email_exists( $newemail )) {

                        $responce['emai-update-message'] = 'A user with that email address already exists Please try another email address.';
                        


                    }else{

                        //$result_update = wp_update_user( array ( 'ID' => $userid, 'user_login' => $newemail,'user_email'=>$newemail) ) ;
                       global $wpdb;
                        $tablename = $wpdb->prefix . "users";
                        $sql = $wpdb->prepare( "UPDATE `wp_users` SET `display_name`='".$newemail."' , `user_login`='".$newemail."',`user_email`='".$newemail."' WHERE `ID`=".$user_id."", $tablename );
                        $result_update = $wpdb->query($sql);
                        //echo '<pre>';
                        //print_r($result_update);exit;
                        update_user_option($user_id, 'nickname', $newemail);
                        //echo $result_update;
                        //echo  "UPDATE ".$tablename." SET user_login=".$newemail.",user_email=".$newemail." WHERE ID=".$userid."";
                        
                        $t = time();
                        update_user_option($user_id, 'profile_updated', $t*1000);

                    }

                }else{

                    $responce['emai-update-message'] = 'Email address is invalid. Please try again and enter a valid email.';
                   
                }

                contentmanagerlogging_file_upload ($lastInsertId,serialize($responce));
                
                
            }
            update_user_option($user_id, 'profile_updated', $t*1000);
            updateregistredUserMeta($user_id,$updateContactUserData,$role);
            
            $responce['id'] = time()*1000;
            $responce['message'] = "Requested data has been updated successfully.";
            }
        }else{
            
            
            $responce['message'] = "There is no record exist in expo-genie for this request.Please Connect with App administrative. Reference id".$external_reference_id_zapier;
            $responce['id'] = time()*1000;
            
            
        }
         
        echo json_encode($responce);
        die();
        
     }  catch (Exception $e){
        
        return $e;
    }
    
    
}


function CreateNewUser($newContactUserData){
    
    try{
        
    $blogid = get_current_blog_id() ;
    $user_id = username_exists($newContactUserData->username);
    $role = $newContactUserData->Role;
    $email = $newContactUserData->username;
    $roleKeyValue = "";
            if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }
            $get_all_roles = get_option($get_all_roles_array);
            foreach ($get_all_roles as $key => $item) {
                 if($role == $item['name']){
                     $role = $key;
                     $roleKeyValue = $key;
                     
                 }
            }
    
            
    if(empty($role) || empty($roleKeyValue)){
        
        $message['message'] = 'Failed to add user due to User Level not vaild.';
        
    }else{
    
    if (!$user_id and email_exists($newContactUserData->username) == false) {
        
       $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
       $user_id = myregisterrequest_new_user($newContactUserData->username, $newContactUserData->Semail) ;//register_new_user( $username, $email );//wp_create_user($username, $random_password, $email);
       if ( ! is_wp_error( $user_id ) ) {
       
        $result=$user_id;
        $loggin_data['created_id']=$result;

        $useremail='';
        // custome_email_send($user_id,$newContactUserData->Semail,"welcome_email_template");
        // $t=time();
         update_user_option($user_id, 'profile_updated', $t*1000);
         updateregistredUserMeta($user_id,$newContactUserData,$role);
         if (add_user_to_blog($blogid, $user_id, $role)) {

                 add_user_to_blog(1, $user_id, $role);
                 $message['user_id'] = $user_id;
                 $message['msg'] = 'User created';
                 $message['userrole'] = $newContactUserData->Role;


                 $message['message'] =  'User added to this blog.';

             } else {

                 $message['message'] = 'Failed to add user ' . $user_id . ' as ' . $role . ' to blog ' . $blogid . '.';
             }
        
    }else{
        
                  $userregister_responce = (array)$user_id;
		  
		   if(empty($userregister_responce['errors']['invalid_username'][0])){
			   
			   $message['message'] = $userregister_responce['errors']['invalid_email'][0];
		   }else{
			   
			   $message['message'] = $userregister_responce['errors']['invalid_username'][0];
		   }
        
        } 
    } else {
        
        
        $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
        $currentblogid = get_current_blog_id() ;
        $user_blogs = get_blogs_of_user( $user_id );
        $user_status_for_this_site = 'not_exist';
        foreach ($user_blogs as $blog_id) { 
               
               if($blog_id->userblog_id == $currentblogid ){
                   
                   $user_status_for_this_site = 'alreadyexist';
                   break;
               }
               
        }
        
  
    if($user_status_for_this_site == 'alreadyexist'){
        
        $message['message'] =  'User already exists for this site.';
        update_user_option($user_id, 'profile_updated', $t*1000);
    }else{    
        
        
        if (add_user_to_blog($blogid, $user_id, $role)) {
                add_user_to_blog(1, $user_id, $role);
                $message['user_id'] = $user_id;
                $message['msg'] = 'User created';
                $message['userrole'] = $role;
               
                
               
                    $useremail='';
                   // custome_email_send($user_id,$email,"welcome_email_template");
                   // $t=time();
                    update_user_option($user_id, 'profile_updated', $t*1000);
                    updateregistredUserMeta($user_id,$newContactUserData,$role);
                
                $message['message'] =  'User added to this blog.';
            
            } else {
                
                $message['message'] = 'Failed to add user ' . $user_id . ' as ' . $role . ' to blog ' . $blogid . '.';
            }
        }
    }
    }
    
    
        echo json_encode($message);
        die();    
        
    }  catch (Exception $e){
        
        
        return $e;
    }
    
}

function updateregistredUserMeta($userID,$userMetaData,$role){
    
    try {
    
        foreach($userMetaData as $keyIndex=>$valueDataIndex){
            
            
            update_user_option($userID, $keyIndex, $valueDataIndex);
            
            
        }
        
        
        $leavel[strtolower($role)] = 1;
        $result = update_user_option($userID, 'capabilities',  $leavel);
    
     }catch (Exception $e) {

        contentmanagerlogging_file_upload($lastInsertId, serialize($e));

        return $e;
    }
}

function updatetask(){
    
    try {
            
        
        $newContactUserData =  json_decode(file_get_contents('php://input')) ;
        
        $taskkey = $newContactUserData->taskkey;
        $getkeyinformation  = gettasktype($taskkey);
        
        
        global $wpdb;
        
        $site_prefix = $wpdb->get_blog_prefix();
        $user_query = new WP_User_Query( array( 'role__not_in' => 'Administrator' ) );
        $authors = $user_query->get_results();
        
        
        $mainArrayIndex = 0;
        $contactIDkey = $site_prefix."external_reference_id_zapier";
                    
                    $arrayMonth['JAN']='01';
                    $arrayMonth['Feb']='02';
                    $arrayMonth['MAR']='03';
                    $arrayMonth['APR']='04';
                    $arrayMonth['MAY']='05';
                    $arrayMonth['JUN']='06';
                    $arrayMonth['JUL']='07';
                    $arrayMonth['AUG']='08';
                    $arrayMonth['SEP']='09';
                    $arrayMonth['OCT']='10';
                    $arrayMonth['Nov']='11';
                    $arrayMonth['DEC']='12';
                    
        if($getkeyinformation['responce'] != "invaild"){
        foreach ($authors as $userKey=>$aid) {
             
            
             $user_data = get_userdata($aid->ID);
             $all_meta_for_user = get_user_meta($aid->ID);
             $fiekldlable = str_replace(' ', '-', strtolower($taskkey));
            
                   
             if($getkeyinformation['fieldtype'] == 'customfield'){
                 
                $dataandtime = $all_meta_for_user[$site_prefix.'profile_updated'][0]/1000;
               
                
                $createuniqueKey = date('YmdHis', $dataandtime);
              
               
                
             if($getkeyinformation['type'] == 'fileupload'){
                 
                 $file_info   = unserialize($all_meta_for_user[$getkeyinformation['key']][0]);
                
                
                 
                
                 
                 if(!empty($file_info)&& !empty($all_meta_for_user[$contactIDkey][0])){
                     
                     $columns_rows_dataFinalData[$mainArrayIndex]['id'] = $aid->ID.$createuniqueKey;
                     $columns_rows_dataFinalData[$mainArrayIndex][$fiekldlable] = $file_info['url'];
                     $columns_rows_dataFinalData[$mainArrayIndex]['external_reference_id_zapier'] = $all_meta_for_user[$contactIDkey][0];
                     $columns_rows_dataFinalData[$mainArrayIndex]['date-time'] = date('d-M-Y H:i:s', $dataandtime);;
                     $mainArrayIndex++;
                 }
                 
                }else{
                    
                        if(!empty($dataandtime) && !empty($all_meta_for_user[$contactIDkey][0])){
                            
                            $dataandtime = $all_meta_for_user[$site_prefix.'profile_updated'][0]/1000;
                            $createuniqueKey = date('YmdHis', $dataandtime);
                            
                            $columns_rows_dataFinalData[$mainArrayIndex]['id'] = $aid->ID.$createuniqueKey;
                            $columns_rows_dataFinalData[$mainArrayIndex][$fiekldlable] = $all_meta_for_user[$getkeyinformation['key']][0];
                            $columns_rows_dataFinalData[$mainArrayIndex]['external_reference_id_zapier'] = $all_meta_for_user[$contactIDkey][0];
                            $columns_rows_dataFinalData[$mainArrayIndex]['date-time'] = date('d-M-Y H:i:s', $dataandtime);
                            $mainArrayIndex++;
                        }
                 
                 
                }
             }else{
                 
                  if($getkeyinformation['type'] == 'color'){
                      
                      
                      
                      
                      $file_info = unserialize($all_meta_for_user[$getkeyinformation['key']][0]);
                      $dateandtime =$all_meta_for_user[$getkeyinformation['key'].'_datetime'][0];
                      $dateData1 = explode(" ",$dateandtime);
                      $dateData2 = explode("-",$dateData1[0]);
                      $dateData3 = explode(":",$dateData1[1]);
                      $updateDateformat = $dateData2[2].$arrayMonth[$dateData2[1]].$dateData2[0].$dateData3[0].$dateData3[1]."15";
                      
                      if (!empty($file_info)&& !empty($all_meta_for_user[$contactIDkey][0])) {
                          
                           $columns_rows_dataFinalData[$mainArrayIndex]['id'] = $aid->ID.$updateDateformat;
                           $columns_rows_dataFinalData[$mainArrayIndex][$fiekldlable] = $file_info['url'];
                           $columns_rows_dataFinalData[$mainArrayIndex]['external_reference_id_zapier'] = $all_meta_for_user[$contactIDkey][0];
                           $columns_rows_dataFinalData[$mainArrayIndex]['date-time'] = $dateandtime;
                           $mainArrayIndex++;
                          
                      }
                      
                      
                  }else{
                      
                     $dateandtime =$all_meta_for_user[$getkeyinformation['key'].'_datetime'][0];
                     $createuniqueKey = strtotime($dataandtime);
                     if(!empty($dateandtime) && !empty($all_meta_for_user[$contactIDkey][0])){
                         
                        $dateData1 = explode(" ",$dateandtime);
                        $dateData2 = explode("-",$dateData1[0]);
                        $dateData3 = explode(":",$dateData1[1]);
                        $updateDateformat = $dateData2[2].$arrayMonth[$dateData2[1]].$dateData2[0].$dateData3[0].$dateData3[1]."15";

                        $columns_rows_dataFinalData[$mainArrayIndex]['id'] = $aid->ID.$updateDateformat;
                        $columns_rows_dataFinalData[$mainArrayIndex][$fiekldlable] = $all_meta_for_user[$getkeyinformation['key']][0];
                        $columns_rows_dataFinalData[$mainArrayIndex]['external_reference_id_zapier'] = $all_meta_for_user[$contactIDkey][0];
                        $columns_rows_dataFinalData[$mainArrayIndex]['date-time'] = $dateandtime;
                        $mainArrayIndex++;
                     }
                    }
                }
            }
        
        if(empty($columns_rows_dataFinalData)){
            
              $resutl ="[]";
              echo $resutl;
        }else{
            
            $resutl =  json_encode($columns_rows_dataFinalData);
            echo $resutl;
        }
        
        }else{
            
            $resutl ="[]";
            echo $resutl;
           
        }
        die();
        
    }catch (Exception $e) {

        contentmanagerlogging_file_upload($lastInsertId, serialize($e));

        return $e;
    }

 
    
       
    
}

function gettasktype($taskkey){
    
        
        
        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_type'        => 'egpl_custome_tasks',
            'post_status'      => 'draft',

        );
        global $wpdb;
        $site_prefix = $wpdb->get_blog_prefix();
        $result_task_array_list = get_posts( $args );
        $columns_rows_data = [];
        $columns_list=[];
        
       
        require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/egpl-custome-functions.php';
        $GetAllcustomefields = new EGPLCustomeFunctions();
        $additional_fields = $GetAllcustomefields->getAllcustomefields();
        usort($additional_fields, 'sortByOrder');
        
        
       
        
        foreach ($additional_fields as $key=>$value){
            
            if($value['fieldName'] == $taskkey){
                
                $getOrginalData['key'] = $site_prefix.$value['fielduniquekey'];
                $getOrginalData['type'] = $value['fieldType'];
                $getOrginalData['fieldtype'] = 'customfield';
                $getOrginalData['responce'] = "ok";
            }
            
        }
        
        
        foreach ($result_task_array_list as $taskIndex => $taskObject) {
            
            $tasksID=$taskObject->ID;
            
            $value_type = get_post_meta( $tasksID, 'type' , true);
            $value_key = get_post_meta( $tasksID, 'key', true);
            $label = get_post_meta( $tasksID, 'label', true);
            
            if($label == $taskkey){
                
                $getOrginalData['key'] = $value_key;
                $getOrginalData['type'] = $value_type;
                $getOrginalData['fieldtype'] = 'task';
                $getOrginalData['responce'] = "ok";
                
            }
        }
    if(empty($getOrginalData)){
        
        $getOrginalData['responce'] = "invaild";
    }
    return $getOrginalData;
}

//[siturl]
function currentsiteurl_func( $atts ){
	
    $site_url = get_option('siteurl' );
    return $site_url;
}
add_shortcode( 'siturl', 'currentsiteurl_func' );
?>