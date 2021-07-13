<?php
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function – $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
    
   
       
      
       require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/egpl-custome-functions.php';
       $GetAllcustomefields = new EGPLCustomeFunctions();
       $additional_fields = $GetAllcustomefields->getAllcustomefields();
       
        
        usort($additional_fields, function($a, $b) {
            return $a['fieldIndex'] <=> $b['fieldIndex'];
        });
        
        //usort($additional_fields, 'sortByOrder');
        
//            unset( $fields['billing']['billing_company'] );
          //  unset( $fields['billing']['billing_country'] );
//            unset( $fields['billing']['_billing_first_name'] );
//            unset( $fields['billing']['_billing_last_name'] );
//            unset( $fields['billing']['_billing_email'] );
//            unset( $fields['billing']['_billing_company'] );
//            
           
            unset( $fields['billing']['billing_phone'] );
            unset( $fields['billing']['billing_address_2'] );
//            unset( $fields['billing']['billing_first_name'] );
//            unset( $fields['billing']['billing_last_name'] );
          //  unset( $fields['billing']['billing_address_1'] );
          //  unset( $fields['billing']['billing_address_2'] );
          //  unset( $fields['billing']['billing_city'] );
          //  unset( $fields['billing']['billing_postcode'] );
      
        $fields['billing']['billing_first_name']['label'] = "First Name";
        $fields['billing']['billing_first_name']['priority'] = 1;
        $fields['billing']['billing_last_name']['label'] = "Last Name";
        $fields['billing']['billing_last_name']['priority'] = 2;
        $fields['billing']['billing_email']['label'] = "Email";
        $fields['billing']['billing_email']['priority'] = 3;
        $fields['billing']['billing_company']['label'] = "Company Name";
        $fields['billing']['billing_company']['priority'] = 4;
        $fields['billing']['billing_city']['label'] = "City";
        $fields['billing']['billing_address_1']['label'] = "Street Address";
        $fields['billing']['billing_company']['required'] = true;
        
        
      
       
        
        
        
        
        
        if (!is_user_logged_in() ) { 
            
            
        foreach ($additional_fields as $key=>$value){ 
            
            $requiredStatus = $additional_fields[$key]['fieldrequriedstatus'];
            $label = $additional_fields[$key]['fieldName'];
            $tooltipandplaceholder = $additional_fields[$key]['fieldtooltiptext'];
            $fieldType = $additional_fields[$key]['fieldType'];
            $uniquerKey = $additional_fields[$key]['fielduniquekey'];
            $placeholder = $additional_fields[$key]['fieldplaceholder'];
            
            
           
                
               
                if($additional_fields[$key]['fielduniquekey'] !="Semail" && $additional_fields[$key]['fielduniquekey'] !="first_name" && $additional_fields[$key]['fielduniquekey'] !="last_name" && $additional_fields[$key]['fielduniquekey'] !="company_name" && $additional_fields[$key]['fieldType'] != 'checkbox' && $additional_fields[$key]['fieldType'] != 'display' && $additional_fields[$key]['displayonapplicationform'] == "checked"){ 
                  
                    $filedrequriedonoff = false;
                    if($requiredStatus == "checked"){
                        
                        $filedrequriedonoff  = true;
                    }
                    
                    $fields['billing'][$uniquerKey] = array(
                        'label'     => __($label, 'woocommerce'),
                        'placeholder'   => _x($placeholder, 'placeholder', 'woocommerce'),
                        'required'  => $filedrequriedonoff,
                        'class'     => array('custome'),
                        'clear'     => true
                    );
                    
                   
                    
                }
                
                
            
            
            
        }
       
        }
       
    
        
   //}
        //echo '<pre>';
   //print_r($fields);exit;
   return $fields;
}

add_action( 'woocommerce_checkout_update_order_meta', 'custom_checkout_fields_update_order_meta' );
function custom_checkout_fields_update_order_meta( $order_id ) {
    
    
    
    global $wpdb;
    $site_prefix = $wpdb->get_blog_prefix();
    
    if (is_user_logged_in() ) {
        
         $CreatedUserID = get_current_user_id();
    }else{
        
        $CreatedUserID = $_REQUEST['created_userid'];
        //update_post_meta($order_id,'_billing_email',sanitize_text_field( $_POST['Semail'] ));
    }
    
    
    
    update_post_meta($order_id,'_customer_user',$CreatedUserID);
    
//    update_post_meta($order_id,'_billing_phone',sanitize_text_field( $_POST['user_phone_1'] ));
//    update_post_meta($order_id,'_billing_state',sanitize_text_field( $_POST['userstate'] ));
//    update_post_meta($order_id,'_billing_address_1',sanitize_text_field( $_POST['address_line_1'] ));
//    update_post_meta($order_id,'_billing_address_2',sanitize_text_field( $_POST['address_line_2'] ));
//    update_post_meta($order_id,'_billing_city',sanitize_text_field( $_POST['usercity'] ));
//    update_post_meta($order_id,'_billing_postcode',sanitize_text_field( $_POST['userstate'] ));
    
    
    
    
    
    
}


//add_action( 'user_register', 'woocommerceuserreisgter', 10, 1 );

function woocommerceuserreisgter( $user_id ) {
    
    
    custome_email_send_woocomerce($CreatedUserID,$email,"welcome_email_template");
   $t=time();
    update_user_option($CreatedUserID, 'profile_updated', $t*1000);
   
    
  

}




function custome_email_send_woocomerce($user_id,$userlogin='',$welcomeemailtemplatename=''){
    
//    require_once('../../../wp-load.php');
    //require_once 'Mandrill.php';
    
    
 try {

    global $wpdb, $wp_hasher;
    $site_prefix = $wpdb->get_blog_prefix();
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $mandrill = $oldvalues['ContentManager']['mandrill'];
    //$mandrill = new Mandrill($mandrill);
    
   
    
        $user = get_userdata($user_id);
        
        //if(empty($userlogin)){
            
          //$user_login = stripslashes($user->user_email);
          //$user_email = stripslashes($user->user_email);
          
        //}else{
            
            $user_email = $userlogin;
            $user_login = $userlogin;
        //}
        if(empty($welcomeemailtemplatename)){
            
           $welcomeemailtemplatename = "welcome_email_template"; 
            
        }
        
        //$plaintext_pass=wp_generate_password( 8, false, false );
        //wp_set_password( $plaintext_pass, $user_id );
        
        $settitng_key='AR_Contentmanager_Email_Template_welcome';
        $sponsor_info = get_option($settitng_key);
        $site_url = get_option('siteurl' );
        $data=  date("Y-m-d");
        $time=  date('H:i:s');
        $site_title=get_option( 'blogname' );
        $oldvalues = get_option( 'ContenteManager_Settings' );
        $formemail = $oldvalues['ContentManager']['formemail'];
        if(empty($formemail)){
            $formemail = 'noreply@expo-genie.com';
        
        }
        
       
      
        $formemail = $oldvalues['ContentManager']['formemail'];
        $fromname = stripslashes ($sponsor_info[$welcomeemailtemplatename]['fromname']);
        if(empty($formemail)){

            $formemail = 'noreply@expo-genie.com';

        }
        
        $subject = $sponsor_info[$welcomeemailtemplatename]['welcomesubject'];
	$bcc =  $sponsor_info[$welcomeemailtemplatename]['BCC'];
       // $cc =  $sponsor_info[$welcomeemailtemplatename]['CC'];
	$formname = $sponsor_info[$welcomeemailtemplatename]['fromname'];
        $replaytoemailadd = $sponsor_info[$welcomeemailtemplatename]['replaytoemailadd'];
        $bcc_array = $bcc;
      //  $cc_array = explode(',',$cc);
        
       

    $subject = $sponsor_info[$welcomeemailtemplatename]['welcomesubject'];
    $body=stripslashes ($sponsor_info[$welcomeemailtemplatename]['welcomeboday']);
   
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    
    $field_key_string =  getInbetweenStrings('{', '}', $body);
    $field_key_subject = getInbetweenStrings('{', '}', $subject);
    
  
    $site_url = get_option('siteurl' );

    $login_url = get_option('siteurl' );
    $admin_email= get_option('admin_email');
    $data=  date("Y-m-d");
    $time=  date('H:i:s');
    $sitetitle = get_bloginfo( 'name' );
    if(empty($fromname)){
        $fromname = get_bloginfo( 'name' );
    }
   // $body = str_replace('[site_url]', $site_url, $body);
   // $body = str_replace('[login_url]', $site_url, $body);
   // $body = str_replace('[admin_email]', $admin_email, $body);
    $subject = str_replace('{', '*|', $subject);
    $subject = str_replace('}', '|*', $subject);
    $body = str_replace('{', '*|', $body);
    $body = str_replace('}', '|*', $body);
    
    

    $goble_data_array =array(
        array('name'=>'date','content'=>$data),
        array('name'=>'time','content'=>$time),
        array('name'=>'site_url','content'=>$site_url),
        array('name'=>'site_title','content'=>$sitetitle)
        );

  

       
       $data_field_array= array();
       $t=time();
       update_user_option($user_id, 'convo_welcomeemail_datetime', $t*1000);
       
       foreach($field_key_subject as $index_subject=>$keyvalue_subject){

                      if($keyvalue_subject == 'wp_user_id' ||$keyvalue_subject == 'Role' || $keyvalue_subject == 'site_title' || $keyvalue_subject == 'date' || $keyvalue_subject == 'time' || $keyvalue_subject == 'site_url' || $keyvalue_subject == 'user_pass'|| $keyvalue_subject == 'user_login'){


                      if($keyvalue_subject == 'user_pass'){


                            
                            $plaintext_pass=wp_generate_password( 8, false, false );
                            wp_set_password( $plaintext_pass, $user_id );
                            $data_field_array[] = array('name'=>$index_subject,'content'=>$plaintext_pass);

                      }else if($keyvalue_subject == 'user_login'){

                          $data_field_array[] = array('name'=>$index_subject,'content'=>$user_login);
                      }else if($keyvalue_subject == 'Role'){

                         
                          $getcurrentuserdata = get_userdata( $user_id );
                          $blog_id = get_current_blog_id();
                          $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
                          $get_all_roles = get_option($get_all_roles_array);
                          foreach ($get_all_roles as $key => $name) {

                              if(implode(', ', $getcurrentuserdata->roles) == $key){

                                  $currentuserRole = $name['name'];


                              }



                          }


                          $data_field_array[] = array('name'=>$index_subject,'content'=>$currentuserRole);
                      }elseif($keyvalue_subject == 'wp_user_id'){
                          
                          $data_field_array[] = array('name'=>$index_subject,'content'=>$user_id);
                          
                      }



                   }else{


                       $get_meta_value = get_user_meta_merger_field_value($user_id,$keyvalue_subject);
                       
                       
                       
                       
                    if($get_meta_value!=""){
                        
                       
                        $getfieldType = getcustomefieldKeyValue($keyvalue_subject,"fieldType");
                        
                       if($getfieldType == "date"){
                            
                            $date_value =   date('d-m-Y' , intval($all_meta_for_user[$keyvalue_subject][0])/1000);
                            $data_field_array[] = array('name'=>$index_subject,'content'=>$date_value);
                           
                       }else{
                           
                            $data_field_array[] = array('name'=>$index_subject,'content'=>$get_meta_value);
                           
                       }
                             
                      
                             
                        
                       
                   }else{
                       
                       $data_field_array[] = array('name'=>$index_subject,'content'=>'');
                   }




                  }



             }
       foreach($field_key_string as $index=>$keyvalue){

                      if($keyvalue == 'wp_user_id' || $keyvalue == 'Role' || $keyvalue == 'site_title' || $keyvalue == 'date' || $keyvalue == 'time' || $keyvalue == 'site_url' || $keyvalue == 'user_pass'|| $keyvalue == 'user_login'){


                      if($keyvalue == 'user_pass'){


                            
                            $plaintext_pass=wp_generate_password( 8, false, false );
                            wp_set_password( $plaintext_pass, $user_id );
                            $data_field_array[] = array('name'=>$index,'content'=>$plaintext_pass);

                      }else if($keyvalue == 'user_login'){

                          $data_field_array[] = array('name'=>$index,'content'=>$user_login);
                      }else if($keyvalue == 'Role'){

                         
                          $getcurrentuserdata = get_userdata( $user_id );
                          $blog_id = get_current_blog_id();
                          $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
                          $get_all_roles = get_option($get_all_roles_array);
                          foreach ($get_all_roles as $key => $name) {

                              if(implode(', ', $getcurrentuserdata->roles) == $key){

                                  $currentuserRole = $name['name'];


                              }



                          }


                          $data_field_array[] = array('name'=>$index,'content'=>$currentuserRole);
                      }elseif($keyvalue == 'wp_user_id'){
                          
                          $data_field_array[] = array('name'=>$index,'content'=>$user_id);
                          
                      }



                   }else{


                       $get_meta_value = get_user_meta_merger_field_value($user_id,$keyvalue);
                       if($get_meta_value!=""){

                            $getfieldType = getcustomefieldKeyValue($keyvalue,"fieldType");

                           if($getfieldType == "date"){

                                $date_value =   date('d-m-Y' , intval($get_meta_value)/1000);
                                $data_field_array[] = array('name'=>$index,'content'=>$date_value);

                           }else{

                                $data_field_array[] = array('name'=>$index,'content'=>$get_meta_value);

                           }





                       }else{

                           $data_field_array[] = array('name'=>$index,'content'=>'');
                       }




                  }



             }
       $to_message_array[]=array('email'=>$user_email,'name'=>$first_name,'type'=>'to');
           $user_data_array[] =array(
                'rcpt'=>$user_email,
                'vars'=>$data_field_array
           );

       



        
        $mainheaderbackground = $oldvalues['ContentManager']['mainheader'];
        $mainheaderlogo = $oldvalues['ContentManager']['mainheaderlogo'];
        
       
        
        $logourl = '';

        if(!empty($mainheaderlogo)){

            $logourl = '<img style="margin-top: 16px;" src="'.$mainheaderlogo.'" alt="" width="250" />';

        }else if(!empty($mainheaderbackground)){

            $logourl = '<img style="margin-top: 16px;" src="'.$mainheaderbackground.'" alt="" height="100" />';


        }

        $html_body_message = '<table width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff">
<tbody>
<tr>
<td align="left">
<div style="border: solid 1px #d9d9d9;">
<table id="header" style="line-height: 1.6;" border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
<tbody>
<tr>
<td style="text-align: center;">'.$logourl.'</td>
</tr>
</tbody>
</table>
<table id="content" style="padding-right: 30px;padding-left: 30px;" border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
<tbody>
<tr>
<td style="border-top: solid 1px #d9d9d9;" colspan="2">
<div style="padding: 1em 0;">
'.$body.'
</div>
</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>';


       
        
   $get_currentsiteURl = get_site_url();
   $uri = 'https://mandrillapp.com/api/1.0/messages/send.json';
   $postString['key'] = $mandrill;
   
   
   
   $message = array(

        'html' => $html_body_message,
        'text' => '',
        'subject' => $subject,
        'from_email' => $formemail,
        'from_name' => $fromname,
        'to' => $to_message_array,
        'headers' => array('Reply-To' => $replaytoemailadd),
        'bcc_address'=>$bcc_array,
        'track_opens' => true,
        'track_clicks' => true,
         
        'merge' => true,
        'merge_language' => 'mailchimp',
        'global_merge_vars' => $goble_data_array,
        'merge_vars' => $user_data_array,
        "tags" => [$get_currentsiteURl]

    );

    $postString['message'] = $message; 
    $postString['async'] = false;
    
    $lastInsertId = contentmanagerlogging('Welcome Email',"Admin Action",serialize($message),$user_id,$user_info->user_email,"pre_action_data");

    
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $uri);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postString));

    $result = curl_exec($ch);

   // echo $result;
    
    
   // $result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
    contentmanagerlogging_file_upload($lastInsertId,serialize($result));
   


}catch(Mandrill_Error $e) {
    // Mandrill errors are thrown as exceptions
    $error_msg = 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
    // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'


    contentmanagerlogging_file_upload($lastInsertId,$error_msg);
     echo   $e->getMessage();
    //throw $e;
}
    
}

//add_action( 'woocommerce_thankyou', 'njengah_woocommerce_redirect_after_checkout');
 
function njengah_woocommerce_redirect_after_checkout( $order_id ){
 
    //$order = wc_get_order( $order_id );
    
    if(isset($_GET['refer']) == "woo"){
        
        
    }else{
    $orderkey = get_post_meta($order_id,'_order_key',true);
    $url = get_option('siteurl' ).'/checkout/order-received/'.$order_id.'/?key='.$orderkey.'&refer=woo';
 
    echo "<script>top.window.location.href = '".$url."'</script>";exit;
    //header("Location: "+$url);
 
        //wp_safe_redirect( $url );
        exit;
    }
    
 
}


add_filter( 'woocommerce_after_checkout_validation', 'createuserwoocommerce', 10,2 );
function createuserwoocommerce ($fields, $errors) {
    
    
    
if(empty($errors->errors)){    
if (!is_user_logged_in() ) {
    
    $user_id = username_exists($_REQUEST['Semail']);
    $useremail = $_REQUEST['Semail'];
    $role = "subscriber";
    $email = $_REQUEST['Semail'];
    $CreatedUserID = $user_id;
    
    
    if (!$user_id and email_exists($_REQUEST['Semail']) == false) {
        
       
       $user_id = myregisterrequest_new_user($useremail, $useremail) ;
       if( ! is_wp_error( $user_id ) ) {
       
           $result=$user_id;
           $loggin_data['created_id']=$result;
           $message['user_id'] = $user_id;
           $message['msg'] = 'User created';
           $message['userrole'] = $role;
       
           add_user_to_blog(1, $user_id, $role);
           custome_email_send_woocomerce($user_id,$useremail,'welcome_email_template');
           $t=time();
           update_user_option($user_id, 'convo_welcomeemail_datetime', $t*1000);
            
            
       }else{
           
           
                   $userregister_responce = (array)$user_id;
		  
		   if(empty($userregister_responce['errors']['invalid_username'][0])){
			   
			   $message['error'] = $userregister_responce['errors']['invalid_email'][0];
		   }else{
			   
			   $message['error'] = $userregister_responce['errors']['invalid_username'][0];
		   }
           
           
           
       }
        
        
    }else {
        
        $blogid = get_current_blog_id() ;
        $blogusers = get_blogs_of_user($user_id);
        $errormessg = "";
        foreach($blogusers as $blogkey=>$blogdata){
            
            if($blogdata->userblog_id == $blogid){
                
                $errormessg = "UserAlreadyExist";
            }
            
        }
        if ($errormessg !== "UserAlreadyExist"){
        
        if (add_user_to_blog($blogid, $user_id, $role)) {
                
                switch_to_blog($blogid);
                
                add_user_to_blog(1, $user_id, $role);
                
                custome_email_send_woocomerce($user_id,$email,'welcome_email_template');
                $t=time();
                update_user_option($user_id, 'convo_welcomeemail_datetime', $t*1000);
                  
                
                
               
                $message['msg'] = 'User added to this blog.';
            } else {
                $message['error'] = 'Failed to add user ' . $user_id . ' as ' . $role . ' to blog ' . $blogid . '.';
            }
        }else{
            
           $message['error'] = 'Already registerd this user.'; 
            
        }
        
    }
    
    
    $_REQUEST['created_userid'] = $user_id;
    
    
    global $wpdb;
    $site_prefix = $wpdb->get_blog_prefix();
    require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/egpl-custome-functions.php';
    $GetAllcustomefields = new EGPLCustomeFunctions();
    $additional_fields = $GetAllcustomefields->getAllcustomefields();
    
    
    //update_user_option($CreatedUserID, 'first_name', sanitize_text_field( $_POST['first_name'] ) );
    //update_user_option($CreatedUserID, 'last_name', sanitize_text_field( $_POST['last_name'] ) );
    //update_user_option($CreatedUserID, 'company_name', sanitize_text_field( $_POST['company_name'] ) );
    
    
    $email = sanitize_text_field( $_POST['Semail'] );
   
    
    
    foreach ($additional_fields as $key=>$value){ 
       
        if($additional_fields[$key]['fielduniquekey'] !="Semail" && $additional_fields[$key]['fieldType'] != 'checkbox' && $additional_fields[$key]['fieldType'] != 'display' && $additional_fields[$key]['displayonapplicationform'] == "checked"){ 
            
            if(!empty($_POST[$additional_fields[$key]['fielduniquekey']])){
                
               //update_post_meta($order_id,$additional_fields[$key]['fielduniquekey'],sanitize_text_field( $_POST[$additional_fields[$key]['fielduniquekey']] ));
               update_user_option($user_id, $additional_fields[$key]['fielduniquekey'], sanitize_text_field( $_POST[$additional_fields[$key]['fielduniquekey']] ) );
             
                
            }
            
            
            
        }
        
        
    }
    
    
   
    if(!empty($message['error'])){
        
       
        $site_url = get_option('siteurl' );
        $messagenew = 'An account is already registered with your email address.';
        //wc_clear_notices();
        //wc_add_notice( $messagenew, 'error' );
        $errors->add( 'validation', 'An account is already registered with your email address.' );
        
        
            
        
        
    }
    
    
}}
   
    
}



