<?php 
// [showuserfield field='COMPANY_NAME']
function showuserfield_func($atts) {
    $fieldname = $atts['field'];
    $postid = get_current_user_id();
    $value = get_user_option($fieldname,$postid);
   
    return $value;
   
}

add_shortcode('showuserfield', 'showuserfield_func');

// [sponsor_roles]
function sponsor_roles_fun() {
    $role = '';
    if (is_user_logged_in()) { 
    
        global $wp_roles;
        global $current_user, $wpdb;
        $all_roles = $wp_roles->roles;
        $editable_roles = apply_filters('editable_roles', $all_roles);
        $role = $wpdb->prefix . 'capabilities';
        $current_user->role = array_keys($current_user->$role);
        $role = $editable_roles[$current_user->role[0]]['name'];
       }
    
    
    return $role;
}

add_shortcode('sponsor_roles', 'sponsor_roles_fun');


function mycustomelogin($user_login, $user) {
    
    global $wpdb;
    $postid = $user->ID;
    $blog_id = get_current_blog_id();
    
    if (is_multisite()) {
    
    
    $user_blogs = get_blogs_of_user( $postid );
    
    if (array_key_exists($blog_id,$user_blogs)){
        
        // echo '<pre>';
        // print_r($user_blogs);exit;
         
    }else{
        
        
        //wp_logout();
        wp_redirect( '/warning' );
        exit();
        
    }
    }
    $t=time();
    $result = update_user_meta($postid , 'wp_user_login_date_time',  $t);
    
    if(get_current_blog_id() == 1){
        $tablename = 'contentmanager_log';
    }else{
    
        $tablename = 'contentmanager_'.$blog_id.'_log';
    } 
    
   // $query = "INSERT INTO ".$tablename." (action_name, action_type,pre_action_data,user_id,user_email,result) VALUES (%s,%s,%s,%s,%s,%s)";
  //  $wpdb->query($wpdb->prepare($query, "Login", "User Action",serialize($user),$user->ID,$user->user_email,$result));
    $activitylog = array(
        'post_title'    => wp_strip_all_tags( 'Login' ),
        'post_content'  => "",
        'post_status'   => 'publish',
        'post_author'   => $user->ID,
        'post_type'=>'expo_genie_log'
    );
    $logID = wp_insert_post( $activitylog );
    $_SERVER['currentuseremail'] = $email;
    update_post_meta( $logID, 'actiontype', 'User Action' );
    update_post_meta( $logID, 'preactiondata', $user );
    update_post_meta( $logID, 'currentuserinfo', $_SERVER );
    update_post_meta( $logID, 'email', $user->user_email );
    update_post_meta( $logID, 'ip', $_SERVER['REMOTE_ADDR'] );
    update_post_meta( $logID, 'browseragent', $_SERVER['HTTP_USER_AGENT'] );
    update_post_meta( $logID, 'result', $result );

}
add_action('wp_login', 'mycustomelogin', 10, 2);



//add_action( 'loop_start', 'personal_message_when_logged_in' );

function personal_message_when_logged_in() {

if ( is_user_logged_in() ) :
 
    global $wpdb;
    $current_user = wp_get_current_user();
    $postid = get_current_user_id();
    $t=time();
    $result = update_user_meta($postid , 'wp_user_login_date_time',  $t);
    $blog_id =get_current_blog_id();
    if(get_current_blog_id() == 1){
        $tablename = 'contentmanager_log';
    }else{
    
        $tablename = 'contentmanager_'.$blog_id.'_log';
    }
    
   // $query = "INSERT INTO ".$tablename." (action_name, action_type,pre_action_data,user_id,user_email,result) VALUES (%s,%s,%s,%s,%s,%s)";
//$wpdb->query($wpdb->prepare($query, "Login", "User Action",serialize($current_user),$postid,$current_user->user_email,$result));
  $activitylog = array(
        'post_title'    => wp_strip_all_tags( 'Login' ),
        'post_content'  => "",
        'post_status'   => 'publish',
        'post_author'   => $postid,
        'post_type'=>'expo_genie_log'
    );
    $logID = wp_insert_post( $activitylog );
    $_SERVER['currentuseremail'] = $email;
    update_post_meta( $logID, 'actiontype', 'User Action' );
    update_post_meta( $logID, 'preactiondata', $current_user );
    update_post_meta( $logID, 'currentuserinfo', $_SERVER );
    update_post_meta( $logID, 'email', $current_user->user_email );
    update_post_meta( $logID, 'ip', $_SERVER['REMOTE_ADDR'] );
    update_post_meta( $logID, 'browseragent', $_SERVER['HTTP_USER_AGENT'] );
    update_post_meta( $logID, 'result', $result );

    endif;
}

add_action( 'authenticate', 'my_front_end_login_fail',10,2);  // hook failed login

function my_front_end_login_fail($error,$user) {
     // where did the post submission come from?
 
   $message['error'] = $error;
   $message['username'] = $user;
   //$message['pass'] = $pass;
   $blog_id =get_current_blog_id();
   if(get_current_blog_id() == 1){
        $tablename = 'contentmanager_log';
    }else{
    
        $tablename = 'contentmanager_'.$blog_id.'_log';
    }
$_SERVER['currentuser'] = $user;
 
    global $wpdb;
   // $query = "INSERT INTO ".$tablename." (action_name, action_type,pre_action_data,user_id,user_email,result) VALUES (%s,%s,%s,%s,%s,%s)";
   // $wpdb->query($wpdb->prepare($query, "Login Failed", "User Action",serialize($message),'',$_SERVER['currentuser'],''));
     $activitylog = array(
        'post_title'    => wp_strip_all_tags( 'Login Failed' ),
        'post_content'  => "",
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type'=>'expo_genie_log'
    );
    $logID = wp_insert_post( $activitylog );
    $_SERVER['currentuseremail'] = $email;
    update_post_meta( $logID, 'actiontype', 'User Action' );
    update_post_meta( $logID, 'preactiondata', $message );
    update_post_meta( $logID, 'currentuserinfo', $_SERVER );
    update_post_meta( $logID, 'email', $user );
    update_post_meta( $logID, 'ip', $_SERVER['REMOTE_ADDR'] );
    update_post_meta( $logID, 'browseragent', $_SERVER['HTTP_USER_AGENT'] );
    update_post_meta( $logID, 'result', '' );

}


function afterlogoutredirect() {
    // your code
  
     wp_redirect( home_url('/') );
     exit();
}
add_action('wp_logout', 'afterlogoutredirect');

// [customelogout ]
function customelogout() {
       

    global $wpdb;
    global $switched;
    
    $current_user = wp_get_current_user();
    $postid = get_current_user_id();
    $blog_id =get_current_blog_id();
    
   if(get_current_blog_id() == 1){
        $tablename = 'contentmanager_log';
    }else{
    
        $tablename = 'contentmanager_'.$blog_id.'_log';
    }
    $_SERVER['currentuser'] = $current_user->user_email;
    $result="1";
    
    $activitylog = array(
        'post_title'    => wp_strip_all_tags( 'Logout' ),
        'post_content'  => "",
        'post_status'   => 'publish',
        'post_author'   => $postid,
        'post_type'=>'expo_genie_log'
    );
    $logID = wp_insert_post( $activitylog );
    $_SERVER['currentuseremail'] = $email;
    update_post_meta( $logID, 'actiontype', 'User Action' );
    update_post_meta( $logID, 'preactiondata', $current_user );
    update_post_meta( $logID, 'currentuserinfo', $_SERVER );
    update_post_meta( $logID, 'email', $current_user->user_email );
    update_post_meta( $logID, 'ip', $_SERVER['REMOTE_ADDR'] );
    update_post_meta( $logID, 'browseragent', $_SERVER['HTTP_USER_AGENT'] );
    update_post_meta( $logID, 'result', $result );
    //$query = "INSERT INTO ".$tablename." (action_name, action_type,pre_action_data,user_id,user_email,result) VALUES (%s,%s,%s,%s,%s,%s)";
    //$wpdb->query($wpdb->prepare($query, "Logout", "User Action",serialize($current_user),$postid,$_SERVER,$result));
    
    //switch_to_blog(1);
    wp_logout();
    //restore_current_blog();
    //switch_to_blog($blog_id);
   // wp_logout();
   // restore_current_blog();
    exit;
   
}
add_shortcode( 'customelogout', 'customelogout' );

function contentmanagerlogging($acction_name,$action_type,$pre_action_data,$user_id,$email,$result){

    


// Create post object
$activitylog = array(
  'post_title'    => wp_strip_all_tags( $acction_name ),
  'post_content'  => "",
  'post_status'   => 'publish',
  'post_author'   => $user_id,
  'post_type'=>'expo_genie_log'
);
 

 $logID = wp_insert_post( $activitylog );
 $_SERVER['currentuseremail'] = $email;
 update_post_meta( $logID, 'actiontype', $action_type );
 update_post_meta( $logID, 'preactiondata', $pre_action_data );
 update_post_meta( $logID, 'currentuserinfo', $_SERVER );
 update_post_meta( $logID, 'email', $email );
 update_post_meta( $logID, 'ip', $_SERVER['REMOTE_ADDR'] );
 update_post_meta( $logID, 'browseragent', $_SERVER['HTTP_USER_AGENT'] );
 update_post_meta( $logID, 'result', $result );
 
 return $logID;
 
}
function contentmanagerlogging_file_upload($lastInsertId,$result){

    

    update_post_meta( $lastInsertId, 'result', $result );



}

function custome_email_send($user_id,$userlogin='',$welcomeemailtemplatename=''){
    
//    require_once('../../../wp-load.php');
    require_once 'Mandrill.php';
    
    
 try {

    global $wpdb, $wp_hasher;
    $site_prefix = $wpdb->get_blog_prefix();
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $mandrill = $oldvalues['ContentManager']['mandrill'];
    $mandrill = new Mandrill($mandrill);
    
   
    
        $user = get_userdata($user_id);
        
        if(empty($userlogin)){
            
          $user_login = stripslashes($user->user_login);
          $user_email = stripslashes($user->user_email);
          
        }else{
            
            $user_email = $userlogin;
            $user_login = $userlogin;
        }
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

                          $data_field_array[] = array('name'=>$index_subject,'content'=>$user->user_login);
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
                       
                       
                       
                       
                    if(!empty($get_meta_value)){
                        
                       
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

                          $data_field_array[] = array('name'=>$index,'content'=>$user->user_login);
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
                       if(!empty($get_meta_value)){

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

       


       //$result = send_email($to,$subject,$body_message);

//        if(sizeof($bcc_array) > 1){
//
//            foreach ($bcc_array as $key => $value) {
//                $to_message_array[] = array('email' => $value, 'name' => '', 'type' => 'bcc');
//                $user_data_array[] =array(
//                'rcpt'=>$value,
//                'vars'=>$data_field_array
//                );
//            }
//        }else{
//
//            if(!empty($bcc_array)){
//
//                $to_message_array[]=array('email'=>$bcc_array[0],'name'=>'','type'=>'bcc');
//                $user_data_array[] =array(
//                'rcpt'=>$bcc_array[0],
//                'vars'=>$data_field_array
//                );
//            }
//        }
//        if(sizeof($cc_array) > 1){
//
//            foreach ($cc_array as $key => $value) {
//                $to_message_array[] = array('email' => $value, 'name' => '', 'type' => 'cc');
//                $user_data_array[] =array(
//                'rcpt'=>$value,
//                'vars'=>$data_field_array
//                );
//            }
//        }else{
//
//            if(!empty($cc_array)){
//
//                $to_message_array[]=array('email'=>$cc_array[0],'name'=>'','type'=>'cc');
//                $user_data_array[] =array(
//                'rcpt'=>$cc_array[0],
//                'vars'=>$data_field_array
//                );
//            }
//        }



        
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

    // exit;

    $lastInsertId = contentmanagerlogging('Welcome Email',"Admin Action",serialize($message),$user_id,$user_info->user_email,"pre_action_data");

    $async = false;
    $ip_pool = 'Main Pool';
   // $send_at = 'example send_at';
    $result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
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


function set_html_content_type_utf8() {
return 'test/html';
}

function getInbetweenStrings($start, $end, $str){
    
    require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/egpl-custome-functions.php';
    $GetAllcustomefields = new EGPLCustomeFunctions();
    $listOFcustomfieldsArray = $GetAllcustomefields->getAllcustomefields();
    global $wpdb;
    $matches = array();
    $regex = "/$start([a-zA-Z0-9_]*)$end/";
    preg_match_all($regex, $str, $matches);
    $site_prefix = $wpdb->get_blog_prefix();
    
    
  
    foreach ($matches[1] as $key=>$keyMatch){
        
      
        foreach($listOFcustomfieldsArray as $keyMatchvalue=>$kayMatchName){
            
           
         
            if(str_replace('_', ' ', $keyMatch) == strtolower($kayMatchName['fieldName'])){
                
                if($kayMatchName['fieldName'] == "Email" || $kayMatchName['fieldName'] == "Level" || $kayMatchName['fieldName'] == "User ID" || $kayMatchName['fieldName'] == "Action"  || $kayMatchName['fieldName'] == "Last login" ){
                   
                    $returnDataKeys[$keyMatch] =  $kayMatchName['fielduniquekey'];
                
                }else{
                    
                    $returnDataKeys[$keyMatch] =  $site_prefix.$kayMatchName['fielduniquekey'];
                    //$columns_list_defult_user_report[$index_count]['key'] = $site_prefix.$value['fielduniquekey'];
                }
                
            }
            
            
        }
        
    }
    if(in_array("site_url", $matches[1]) ){
        
        $returnDataKeys['site_url'] =  'site_url';
    }
    if(in_array("user_id", $matches[1]) ){
        
        $returnDataKeys['user_id'] =  'wp_user_id';
    }
    if(in_array("user_login", $matches[1]) ){
        
        $returnDataKeys['user_login'] =  'user_login';
    }
    if(in_array("user_pass", $matches[1]) ){
        
        $returnDataKeys['user_pass'] =  'user_pass';
    }
    if(in_array("date", $matches[1]) ){
        
        $returnDataKeys['date'] =  'date';
    }
    if(in_array("time", $matches[1]) ){
        
        $returnDataKeys['time'] =  'time';
    }
    
    if(in_array("site_title", $matches[1]) ){
        
        $returnDataKeys['site_title'] =  'site_title';
    }
    
    
    
    
    //echo '<pre>';
    //print_r($returnDataKeys);
    return $returnDataKeys;
}

function get_user_meta_merger_field_value($userid,$key){
    
    
      $value = get_user_option($key, $userid);
      
      return $value;
    
    
}
 function cmp($a, $b) {
    if ($a == $b) return 0;
      
    return (strtotime($a) < strtotime($b))? -1 : 1;
}

function gettaskduesoon(){
 
   
    $args = array(
	'posts_per_page'   => -1,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'egpl_custome_tasks',
	'post_status'      => 'draft',
	
        );
        $assign_new_role = get_posts( $args );
        
   
    
        
    foreach($assign_new_role as $taskIndex => $tasksObject) {
        
        $tasksID = $tasksObject->ID;
        $keyvalue = get_post_meta( $tasksID, 'key' , false);
        $label = get_post_meta( $tasksID, 'label' , false);
        $attrs = get_post_meta( $tasksID, 'attrs' , false);
        $value['label'] = $label[0];
        $value['attrs'] = $attrs[0];
        if (strpos($keyvalue[0], "task") !== false) { 
         if (strpos($value['label'], 'Status') !== false || strpos($value['label'], 'Date-Time') !== false) {
            
        }else{
             $arrDates[] = array($key=>$value['attrs']);
        }
        
        } 
     }
    
    
 $html_task_due_soon ="";
 $flat =array_reduce($arrDates, 'array_merge', array());
 uasort($flat, "cmp");
 $duetaskcount= 0;
 

 
    foreach ($flat as $index=>$taskdate){
     
       $time = strtotime($taskdate);
       $currenttime = strtotime(date('Y-m-d'));                                      //echo $index;
                                              //  echo $taskdate;
    if($time>= $currenttime) {                                         
    $html_task_due_soon .= '<tr><td>'.$result['profile_fields'][$index]['label'].'</td><td nowrap align="center"><span class="semibold">'.$taskdate.'</span></td></tr>';
    $duetaskcount++;
    }                  
                                               
                                         
    }
    
   if($duetaskcount == 0){
      $html_task_due_soon .= 'No Task Due Soon.';
    }  
    
 return  $html_task_due_soon;
//echo '<pre>';
//print_r($taskduesoon);exit;
    
    
    
    
}

function cmp2($a, $b) {
    if ($a['attrs'] == $b['attrs']) {
        return 0;
    }
    return (strtotime($a['attrs']) < strtotime($b['attrs'])) ? -1 : 1;
}


// [contentmanagersettings key='infocontent']
function settings_key_data($atts) {
    
    $fieldname = $atts['key'];
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $key_data_return=$oldvalues['ContentManager'][$fieldname];

    return $key_data_return;
   
}

add_shortcode('contentmanagersettings', 'settings_key_data');

function bulkimport_mappingdata($fileurl){
    
   
   
 require_once 'third_party/PHPExcel.php';
    
    $tempname = 'import/'.$fileurl;
 
            
    
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            $objReader->setReadDataOnly(true);

            $objPHPExcel = $objReader->load($tempname);
            $objWorksheet = $objPHPExcel->getActiveSheet();

            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();

            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            
            if($highestRow == 1 ){
                
                $createdusercount = 0;
                $errorcount = 1;
                $data_column_array['data']='your sheet is empty.';
        
        
            }else{
               
                for ($colname = 0; $colname <= $highestColumnIndex; $colname++) {
                
              
                    $data_column_array[$colname]['colindex'] =  $colname ;
                    $data_column_array[$colname]['colname'] = $objWorksheet->getCellByColumnAndRow($colname, 1)->getValue();
                
                  
                }
                
                $data_column_array['uploadedfileurl'] = $tempname;
                $data_column_array['totalnumberofrows'] = $highestRow;
                
            }
           
            return $data_column_array;
          
            
        
}


function createuserlist_after_mapping($fileurl,$colmapping_list,$welcomeemailstatus,$selectwelcomeemailtempname){
    
   
   
 require_once 'third_party/PHPExcel.php';
    
            $tempname = $fileurl;
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($tempname);
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            
            
  
       
    //echo $welcomeemailstatus;exit;
    $createdusercount=0;
    $errorcount = 0;
    
    for ($row = 2; $row <= $highestRow; ++$row) {
     
        $data_field_array= array();
        
        
        foreach ($colmapping_list as $colmappingKey=>$colmappingdata){
         
            if($colmappingdata['fieldname'] == 'Semail' ){
                
                $email = $objWorksheet->getCellByColumnAndRow($colmappingdata['fieldvalue'], $row)->getValue();
                
            }else if($colmappingdata['fieldname'] == 'first_name' ){
                
                $firstname = $objWorksheet->getCellByColumnAndRow($colmappingdata['fieldvalue'], $row)->getValue();
                
            }else if($colmappingdata['fieldname'] == 'last_name' ){
                
                $lastname = $objWorksheet->getCellByColumnAndRow($colmappingdata['fieldvalue'], $row)->getValue();
                
            }else if($colmappingdata['fieldname'] == 'Role' ){
                
                $role = $objWorksheet->getCellByColumnAndRow($colmappingdata['fieldvalue'], $row)->getValue();
                
            }else if($colmappingdata['fieldname'] == 'company_name' ){
                
                $company_name = $objWorksheet->getCellByColumnAndRow($colmappingdata['fieldvalue'], $row)->getValue();
            }
            
        }
        
        $username =$email;
        
        
        
        
        $status = checkimportrowstatus($username,$email,$firstname,$lastname,$role,$company_name);
        
        
       
       if(empty($email)){
           $email="";
       }
       if(empty($company_name)){
           $company_name="";
       }
       // $message[$row]['username'] = $username;
        $message['data'][$row]['email'] = $email;
        $message['data'][$row]['companyname'] = $company_name;
        
      
        if($status == 'clear'){
        
      
        
            $statusresponce = importbulkuseradd(str_replace("+","",$username),$email,$firstname,$lastname,$role,$company_name,$welcomeemailstatus);
           
            
            $message['data'][$row]['status']=$statusresponce['msg'];
            $message['data'][$row]['created_id']=$statusresponce['created_id'];
            
         
            $user_pass=$statusresponce['userpass'];
            
            
          if($message['data'][$row]['status'] == 'User created successfully.' || $message['data'][$row]['status'] == 'User added to this site Successfully.'){
              
              $createdusercount++;
            
              
              
           foreach ($colmapping_list as $colmappingKey=>$colmappingdata){
               
               if($colmappingdata['fieldname'] != 'Semail' && $colmappingdata['fieldname'] != 'first_name' && $colmappingdata['fieldname'] != 'last_name' && $colmappingdata['fieldname'] != 'Role' && $colmappingdata['fieldname'] != 'company_name' ){
                   
                   
                   if(!empty($colmappingdata['fieldvalue'])){
                       
                       
                     $getrow_value = $objWorksheet->getCellByColumnAndRow($colmappingdata['fieldvalue'], $row)->getValue();
                     
                     update_user_option($statusresponce['created_id'], $colmappingdata['fieldname'], $getrow_value);
                     //$data_field_array[] = array('name'=>$colmappingdata['fieldname'],'content'=>$getrow_value);
                     $user_data_array[$statusresponce['created_id']][$colmappingdata['fieldname']] = $getrow_value;
                   }
                  
                   
                   
               }
            }
              
            $user_data_array[$message['data'][$row]['created_id']]['Semail'] = $email;
            $user_data_array[$message['data'][$row]['created_id']]['user_login'] = $username;
            $user_data_array[$message['data'][$row]['created_id']]['user_pass'] = $user_pass;
            $user_data_array[$message['data'][$row]['created_id']]['first_name'] = $firstname;
            $user_data_array[$message['data'][$row]['created_id']]['last_name'] = $lastname;
            $user_data_array[$message['data'][$row]['created_id']]['Role'] = $role;
           
            
          
            }else{
		
                $errorcount++;
		
                
            }
            
        }else{
            
            $message['data'][$row]['status'] = $status;
            $message['data'][$row]['created_id']='';
            $errorcount++;
        } 
        
     
    }
  
  
if($welcomeemailstatus == 'send'){ 
        
      // echo $selectwelcomeemailtempname;
      
       
       $welcomeemail_status = send_bulk_import_welcome_email($to_message_array,$user_data_array,$selectwelcomeemailtempname,$otherfields_array); 
      // echo $welcomeemail_status;exit;
       
   }else{
       
       $welcomeemail_status="Do not send welcome email's."; 
   }
   
   $message['createdcount']=$createdusercount;
   $message['errorcount']=$errorcount;
   $message['result']=$welcomeemail_status;
  
   
  
       
    
  
   
   return $message;
}


function wpse_183245_upload_dir( $dirs ) {
    
    $dirs['subdir'] = '/import';
    $dirs['path'] = dirname(__FILE__).'/import';
    $dirs['url'] =  get_site_url().'/wp-content/plugins/EGPL/import';
    
    
    return $dirs; 
}

function importbulkuseradd($username,$email,$firstname,$lastname,$role,$company_name,$welcomeemailstatus){
    
    require_once('../../../wp-load.php');
    
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
                     
                 }
              }
    
    
    $user_id = username_exists($username);
        if (!$user_id and email_exists($email) == false) {
        
            $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
            $user_id = myregisterrequest_new_user($username, $email);//register_new_user( $username, $email );//wp_create_user($username, $random_password, $email);
            
            $type = gettype($user_id);
          
           // echo $type;exit;
        if($type == 'object'){
            
             if(empty($user_id->errors['invalid_username'][0])){
                 
                $status['msg'] = $user_id->errors['invalid_email'][0];
             
             }else{
                 
                $status['msg'] = $user_id->errors['invalid_username'][0];  
             
             }
              
                
                $status['created_id'] = '';
        
                
            }else{
             
              
              $status['created_id'] = $user_id;
              $status['msg'] = 'User created successfully.';
              $meta_array['first_name']=$firstname;
              $meta_array['last_name']=$lastname;
              $meta_array['company_name']=$company_name;
              add_user_to_blog(1, $user_id, $role);
               
              if($welcomeemailstatus == 'send'){
                
                  $t=time();
                  $meta_array['convo_welcomeemail_datetime']=$t*1000;
                  $plaintext_pass=wp_generate_password( 8, false, false );
                  wp_set_password( $plaintext_pass, $user_id );
                  $status['userpass'] = $plaintext_pass;
              
              }
              
            
              add_new_sponsor_metafields($user_id,$meta_array,$role);
              
              
              
              
            }
            
            
            
        } else {
             
            $currentblogid = get_current_blog_id();
            $user_blogs = get_blogs_of_user( $user_id );
            $user_status_for_this_site = 'not_exist';
            foreach ($user_blogs as $blog_id) { 
               
               if($blog_id->userblog_id == $currentblogid ){
                   
                   $user_status_for_this_site = 'alreadyexist';
                   break;
               }
               
            }
            if($user_status_for_this_site == 'alreadyexist'){
        
               $status['msg'] = 'A user with this email already exists. User not created.';
               $status['created_id'] ='';
        
            }else{
                
               $currentblogid = get_current_blog_id();
               switch_to_blog($currentblogid); 
               
              
               
               $status['created_id'] = $user_id;
               $status['msg'] = 'User added to this site Successfully.';
               $meta_array['first_name']=$firstname;
               $meta_array['last_name']=$lastname;
               $meta_array['company_name']=$company_name;
               
               
               if($welcomeemailstatus == 'send'){
                
                  $t=time();
                  $meta_array['convo_welcomeemail_datetime']=$t*1000;
                  $plaintext_pass=wp_generate_password( 8, false, false );
                  wp_set_password( $plaintext_pass, $user_id );
                  $status['userpass'] = $plaintext_pass;
              
              }
              
              add_user_to_blog($currentblogid, $user_id, $role);
              add_new_sponsor_metafields($user_id,$meta_array,$role);
             
             
              
            }    
            
      }
       
       
       
       return $status;
}


function checkimportrowstatus($username,$email,$firstname,$lastname,$role,$company_name){
    global $wp_roles;
     
    $all_roles = $wp_roles->get_names();
   
    
    $all_roles = array_map('strtolower', $all_roles);//edit new add 
    
    if(!empty($username)&&!empty($email)&&!empty($firstname)&&!empty($lastname)&&!empty($role)&&!empty($company_name)){
        //$role = ucwords($role);
		$role =	strtolower($role);//edit
        if (in_array($role, $all_roles)) {
            $status = 'clear';
           
           
        }else{
        $status= "User level does not exist. User not created.";
       
       }
        
    }else{
        $status= 'A required field such as email, first name, etc. is missing. User not created.';
       
    }
    
    return $status; 
}

function send_bulk_import_welcome_email($to_message_array,$user_data_array,$selectwelcomeemailtempname,$otherfields_array){
    
    require_once('../../../wp-load.php');
    require_once 'Mandrill.php';
    global $wpdb, $wp_hasher;
    
   
    
    
   
    if(!empty($to_message_array)||!empty($user_data_array)){
try { 
    
    
  
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $mandrill = $oldvalues['ContentManager']['mandrill'];
    
    $mandrill = new Mandrill($mandrill);
    $settitng_key='AR_Contentmanager_Email_Template_welcome';
    $sponsor_info = get_option($settitng_key);
        
    $subject = $sponsor_info[$selectwelcomeemailtempname]['welcomesubject'];
    $body=stripslashes ($sponsor_info[$selectwelcomeemailtempname]['welcomeboday']);
    
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $replay_to = $sponsor_info[$selectwelcomeemailtempname]['replaytoemailadd'];
    $formname =$sponsor_info[$selectwelcomeemailtempname]['fromname'];
    
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $formemail = $oldvalues['ContentManager']['formemail'];
    if(empty($formemail)){
        $formemail = 'noreply@expo-genie.com';
        
    }
    $bcc = $sponsor_info[$selectwelcomeemailtempname]['BCC'];
    //$cc = $sponsor_info[$selectwelcomeemailtempname]['CC'];
    $bcc_array = $bcc;
    //$cc_array = explode(',',$cc);
   
   
    $site_url = get_option('siteurl' );
    $login_url = get_option('siteurl' );
    $admin_email= get_option('admin_email');
    $data=  date("Y-m-d");
    $time=  date('H:i:s');
    
    if(empty($fromname)){
        $fromname = get_bloginfo( 'name' );
    }
     $field_key_string = getInbetweenStrings('{', '}', $body);
     $field_key_subject = getInbetweenStrings('{', '}', $subject);
          
   
    $subject = str_replace('{', '*|', $subject);
    $subject = str_replace('}', '|*', $subject);
    $body = str_replace('{', '*|', $body);
    $body = str_replace('}', '|*', $body);
    
    $goble_data_array =array(
        array('name'=>'date','content'=>$data),
        array('name'=>'time','content'=>$time),
        array('name'=>'site_url','content'=>$site_url),
        array('name'=>'site_title','content'=>$fromname)
        );
        
    
        foreach($user_data_array as $userID=>$Onerowvalue){
        
            $data_field_array= array();
           
            
            
            $userdata = get_user_by_email($Onerowvalue['Semail']);
            $t=time();
            update_user_option($userdata->ID, 'convo_welcomeemail_datetime', $t*1000);
            $email_address = $Onerowvalue['Semail'];
            $first_name = $Onerowvalue['first_name'];
            $all_meta_for_user = get_user_meta($userdata->ID);
            
          
              
              
              
             
           
             foreach($field_key_subject as $index_subject=>$keyvalue_subject){
                  
                      if($keyvalue_subject == 'Role' || $keyvalue_subject == 'site_title' || $keyvalue_subject == 'date' || $keyvalue_subject == 'time' || $keyvalue_subject == 'site_url' || $keyvalue_subject == 'user_pass'|| $keyvalue_subject == 'user_login'){
                      
                       
                      if($keyvalue_subject == 'user_pass'){
                          
                            
                            $user_id = $userdata->ID;
                            $plaintext_pass=wp_generate_password( 8, false, false );
                            wp_set_password( $plaintext_pass, $user_id );
                            $data_field_array[] = array('name'=>$index_subject,'content'=>$plaintext_pass);  
                          
                      }elseif($keyvalue_subject == 'user_login'){
                          
                         
                          
                          
                          
                          $data_field_array[] = array('name'=>$index_subject,'content'=>$userdata->user_login);  
                      }elseif($keyvalue_subject == 'Role'){
                          
                          $user_id = $userdata->ID;
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
                      }
                      
                      
                      
                   }else{
                       
                       
                       
                   
                        
                       if (!empty($all_meta_for_user[$keyvalue_subject][0])) {
                           
                           $result = multidimensional_search($colsdatatype, array('colkey' => $keyvalue_subject)); // 1 
                           
                           
                            $getfieldType = getcustomefieldKeyValue($keyvalue_subject,"fieldType");
                        
                            
                           
                        
                        if($getfieldType == 'date') {
                            
                            
                          
                          $date_value =   date('d-m-Y' , intval($all_meta_for_user[$keyvalue_subject][0])/1000);
                          $data_field_array[] = array('name'=>$index_subject,'content'=>$date_value);
                          
                        } else{
                             
                                 
                                 
                                $data_field_array[] = array('name'=>$index_subject,'content'=>$all_meta_for_user[$keyvalue_subject][0]);  
                             
                        }
                       }else{
                           
                                $data_field_array[] = array('name'=>$index_subject,'content'=>''); 
                          
                       }
                   
                      
                      
                      
                      
                  }
                 
                 
                 
             }
            foreach($field_key_string as $index=>$keyvalue){
                
                        
                     
                      
                      if($keyvalue == 'wp_user_id' || $keyvalue == 'Semail' || $keyvalue == 'Role' || $keyvalue == 'site_title' || $keyvalue == 'date' || $keyvalue == 'time' || $keyvalue == 'site_url' || $keyvalue == 'user_pass'|| $keyvalue == 'user_login'){
                      
                          
                      if($keyvalue == 'user_pass'){
                          
                           
                            $user_id = $userdata->ID;
                            $plaintext_pass=wp_generate_password( 8, false, false );
                            wp_set_password( $plaintext_pass, $user_id );
                            $data_field_array[] = array('name'=>$index,'content'=>$plaintext_pass);  
                          
                      }elseif($keyvalue == 'user_login'){
                          
                       
                          $data_field_array[] = array('name'=>$index,'content'=>$userdata->user_login);  
                          
                         
                          
                      }elseif($keyvalue == 'Role'){
                          
                        
                          $user_id = $userdata->ID;
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
                      }elseif($keyvalue == 'Semail'){
                          
                        
                          $data_field_array[] = array('name'=>$index,'content'=>$email_address); 
                      }elseif($keyvalue == 'wp_user_id'){
                          
                        
                          $data_field_array[] = array('name'=>$index,'content'=>$userdata->ID); 
                      }
                      
                      
                      
                   }else{
                       
                       
                       
                        
                       if (!empty($all_meta_for_user[$keyvalue][0])) {
                           
                           $result = multidimensional_search($colsdatatype, array('colkey' => $all_meta_for_user[$keyvalue][0])); // 1 
                           
                           $getfieldType = getcustomefieldKeyValue($keyvalue,"fieldType");
                           
                        if($getfieldType == 'date') {
                            
                          $date_value =   date('d-m-Y', intval($all_meta_for_user[$keyvalue][0]/1000));
                          $data_field_array[] = array('name'=>$index,'content'=>$date_value);
                          
                        } else{
                             
                                 
                                $data_field_array[] = array('name'=>$index,'content'=> $all_meta_for_user[$keyvalue][0]);  
                             
                        }
                       }else{
                           
                                $data_field_array[] = array('name'=>$index,'content'=>''); 
                          
                       }
                  
                      
                      
                      
                      
                  }
                 
                 
                 
             }
              
          
              
                
           $to_message_array[]=array('email'=>$email_address,'name'=>$first_name,'type'=>'to');
           $user_data_array[] =array(
                'rcpt'=>$email_address,
                'vars'=>$data_field_array
           );
 
        }
    
    
    
    
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
    
   $body_message =    $body ;
//   if(sizeof($bcc_array) > 1){
//       
//            foreach ($bcc_array as $key => $value) {
//                $to_message_array[] = array('email' => $value, 'name' => '', 'type' => 'bcc');
//            }
//        }else{
//       
//            if(!empty($bcc_array)){
//
//                $to_message_array[]=array('email'=>$bcc_array[0],'name'=>'','type'=>'bcc');
//            }
//        }
//   if(sizeof($cc_array) > 1){
//       
//            foreach ($cc_array as $key => $value) {
//                $to_message_array[] = array('email' => $value, 'name' => '', 'type' => 'cc');
//            }
//        }else{
//       
//            if(!empty($cc_array)){
//
//                $to_message_array[]=array('email'=>$cc_array[0],'name'=>'','type'=>'cc');
//            }
//        }
   $get_currentsiteURl = get_site_url();
   $message = array(
        
        'html' => $html_body_message,
        'text' => '',
        'subject' => $subject,
        'from_email' => $formemail,
        'from_name' => $formname,
        'to' => $to_message_array,
        'headers' => array('Reply-To' => $replay_to),
        'bcc_address'=>$bcc_array,
        'track_opens' => true,
        'track_clicks' => true,
        
        'merge' => true,
        'merge_language' => 'mailchimp',
        'global_merge_vars' => $goble_data_array,
        'merge_vars' => $user_data_array,
         "tags" => [$get_currentsiteURl]
        
    );
   
    // exit;
  
    $lastInsertId = contentmanagerlogging('Import Welcome Email',"Admin Action",serialize($message),$user_ID,$user_info->user_email,"pre_action_data");
     
    $async = false;
    $ip_pool = 'Main Pool';
   
    $send_at = '';
    $result['send_at_date'] =  '';
    $result['result_send_mail'] = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
    
    contentmanagerlogging_file_upload($lastInsertId,serialize($result));
    return $result;
    
   
    
}catch(Mandrill_Error $e) {
    // Mandrill errors are thrown as exceptions
    $error_msg = 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
    // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
    
 
    contentmanagerlogging_file_upload($lastInsertId,$error_msg);
     echo   $e->getMessage();
    //throw $e;
}

}  
    
}

/// child theme code just like short code and hide menu bar 
function theme_enqueue_styles() {
    wp_enqueue_style( 'avada-parent-stylesheet', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
}
add_action( 'after_setup_theme', 'avada_lang_setup' );


add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
   if (!current_user_can('administrator')) {
         show_admin_bar(false);
    }
}

function no_admin_access()
{
 if( !current_user_can( 'administrator' ) ) {
     wp_redirect( home_url() );
     die();
  }
}
add_action( 'admin_init', 'no_admin_access', 1 );



function wpse_lost_password_redirect() {

    // Check if have submitted
    $confirm = ( isset($_GET['action'] ) && $_GET['action'] == resetpass );

    if( $confirm ) {
        wp_redirect( home_url() );
        exit;
    }
}
add_action('login_headerurl', 'wpse_lost_password_redirect');





// ShortCode For Display Name
function displayname_func( $atts ){
	  global $current_user;
      get_currentuserinfo();
      return $current_user->display_name;
}
add_shortcode( 'user_name', 'displayname_func' );


function specialtext_shortcode( $atts, $content = null ) {
    
    global $current_user, $wpdb;
    if ( is_user_logged_in() ) {
    $role = $wpdb->prefix . 'capabilities';
    $current_user->role = array_keys($current_user->$role);
    $role = $current_user->role[0];
    $role_list =explode(",",$atts['invisiblefor']);
    if (in_array($role, $role_list)) {
        
        
    }else{
        
        return $content;
    }
   
    } 
   
        
        
}
add_shortcode( 'specialtext', 'specialtext_shortcode' );


function auth_with_map_dynamics($request_call){
    
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $mapapikey = $oldvalues['ContentManager']['mapapikey'];
    $mapsecretkey = $oldvalues['ContentManager']['mapsecretkey'];
    $access_hash = md5($mapsecretkey.$request_call);
    
    //ASSEMBLE THE POST VALUES ARRAY
    $post_values = array('key'=>$mapapikey, 'access_hash'=>$access_hash, 'call'=>$request_call, 'format'=>'json');
    
    $ch = curl_init('http://api.map-dynamics.com/services/auth/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_values);
    $result = curl_exec($ch);
    curl_close($ch);
    $results = json_decode($result);
   
    if($results->status == 'success'){
        
        $output  =  $results->results->hash;
        
    }else{
        
       $output  = 'error'; 
        
    }
    
    return $output;
    
}


function insert_exhibitor_map_dynamics($data_array){
    
    
    $hsah = auth_with_map_dynamics('exhibitors/insert');
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $mapapikey = $oldvalues['ContentManager']['mapapikey'];
    $mapsecretkey = $oldvalues['ContentManager']['mapsecretkey'];
    $post_values = array('key'=>$mapapikey, 'call'=>'exhibitors/insert', 'hash'=>$hsah, 'format'=>'json');
    
    
    $dataarray =  array_merge($post_values, $data_array);
    //echo '<pre>';
   // print_r($dataarray);
    
   // exit;
  
    $ch = curl_init('http://api.map-dynamics.com/services/exhibitors/insert/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataarray);
    $result = curl_exec($ch);
    curl_close($ch);
    $results = json_decode($result);
    
     
    return $results;
    
    
    
    
}
function update_exhibitor_map_dynamics($data_array){
    
    
    $hsah = auth_with_map_dynamics('exhibitors/update');
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $mapapikey = $oldvalues['ContentManager']['mapapikey'];
    $mapsecretkey = $oldvalues['ContentManager']['mapsecretkey'];
    $post_values = array('key'=>$mapapikey, 'call'=>'exhibitors/update' ,'hash'=>$hsah, 'format'=>'json');
    $dataarray = array_merge($post_values, $data_array);
    
    //echo '<pre>';
    //print_r($dataarray);
    
   // exit;
    
    
    $ch = curl_init('http://api.map-dynamics.com/services/exhibitors/update/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataarray);
    $result = curl_exec($ch);
    curl_close($ch);
    $results = json_decode($result);
    
     
    return $results;
    
    
    
    
}
// auto upload plugin from github
function changeuseremailaddress($request){
    
     try{
      
        
         
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('Edit user email',"Admin Action",serialize($request),''.$user_ID,$user_info->user_email,"pre_action_data");
        $newemail = $request['newemailaddress'];
        $welcome_email_status = $request['welcomememailstatus'];
        $welcome_selected_email_template = $request['selectedtemplateemailname'];
        $userid = $request['userid'];
        $email_status = isValidEmail($newemail);
        if($email_status){
            if( email_exists( $newemail )) {
                
                $result_status['msg'] = 'A user with that email address already exists Please try another email address.';
            
                
            }else{
                
                //$result_update = wp_update_user( array ( 'ID' => $userid, 'user_login' => $newemail,'user_email'=>$newemail) ) ;
               global $wpdb;
                $tablename = $wpdb->prefix . "users";
                $sql = $wpdb->prepare( "UPDATE `wp_users` SET `display_name`='".$newemail."' , `user_login`='".$newemail."',`user_email`='".$newemail."' WHERE `ID`=".$userid."", $tablename );
                $result_update = $wpdb->query($sql);
                //echo '<pre>';
                //print_r($result_update);exit;
                update_user_option($userid, 'nickname', $newemail);
                //echo $result_update;
                //echo  "UPDATE ".$tablename." SET user_login=".$newemail.",user_email=".$newemail." WHERE ID=".$userid."";
                $result_status['msg'] = 'update';
               
                if($result_update == 1 && $welcome_email_status == 'checked'){
                    custome_email_send($userid,$newemail,$welcome_selected_email_template);
                }
               
            }
            
        }else{
            
            $result_status['msg'] = 'Email address is invalid. Please try again and enter a valid email.';
        }
        
        contentmanagerlogging_file_upload ($lastInsertId,serialize($result_status));
        
       echo json_encode($result_status);
         
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
 
 die();  
    
    
}

function checkwelcomealreadysend($request){
    
     try{
      
        
         
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        
        $lastInsertId = contentmanagerlogging('Check Welcome Email Send',"Admin Action",serialize($request),''.$user_ID,$user_info->user_email,"pre_action_data");
        $emailaddress_array=explode(",", $request['emailAddress']);
        $usertimezone=intval($request['usertimezone']);
        foreach($emailaddress_array as $key=>$emailaddress){
            
            $user = get_user_by( 'email', $emailaddress );
            $welcome_email_date = get_user_option('convo_welcomeemail_datetime', $user->ID);
            if(!empty($welcome_email_date)){
                
                $last_send_welcome_email= date('d-M-Y H:i:s', $welcome_email_date/1000);
                if($usertimezone > 0){
                    $last_send_welcome_date_time = (new DateTime($last_send_welcome_email))->sub(new DateInterval('PT'.abs($usertimezone).'H'))->format('d-M-Y H:i:s');
                }else{
                    $last_send_welcome_date_time = (new DateTime($last_send_welcome_email))->add(new DateInterval('PT'.abs($usertimezone).'H'))->format('d-M-Y H:i:s');
                
                }
                $responce[$emailaddress]=$last_send_welcome_date_time;
            }
            
        }
        contentmanagerlogging_file_upload ($lastInsertId,serialize($responce));
        
       echo json_encode($responce);
         
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
 
 die();  
    
    
}

function isValidEmail($email){ 
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

include_once('updater.php');


if (is_admin()) { // note the use of is_admin() to double check that this is happening in the admin
        $config = array(
            'slug' => plugin_basename(__FILE__), // this is the slug of your plugin
            'proper_folder_name' => 'EGPL', // this is the name of the folder your plugin lives in
            'api_url' => 'https://api.github.com/repos/QasimRiaz/EGPL', // the GitHub API url of your GitHub repo
            'raw_url' => 'https://raw.github.com/QasimRiaz/EGPL/master', // the GitHub raw url of your GitHub repo
            'github_url' => 'https://github.com/QasimRiaz/EGPL', // the GitHub url of your GitHub repo
            'zip_url' => 'https://github.com/QasimRiaz/EGPL/zipball/master', // the zip url of the GitHub repo
            'sslverify' => true, // whether WP should check the validity of the SSL cert when getting an update, see https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/2 and https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/4 for details
            'requires' => '3.0', // which version of WordPress does your plugin require?
            'tested' => '3.3', // which version of WordPress is your plugin tested up to?
            'readme' => 'README.md', // which file to use as the readme for the version number
            'access_token' => '', // Access private repositories by authorizing under Appearance > GitHub Updates when this example plugin is installed
        );
        new WP_GitHub_Updater($config);
    }

add_filter('woocommerce_payment_complete_order_status', 'exp_autocomplete_paid_orders', 10, 2);
add_action('woocommerce_thankyou', 'exp_autocomplete_all_orders');

function exp_autocomplete_all_orders($order_id) {
        
        if (!$order_id)
                return;
        $orderstatus = "completed";
        //$order = new WC_Order($order_id);
        $order = wc_get_order($order_id);
        $user_ID = get_current_user_id();
        $payment_method = get_post_meta($order->id, '_payment_method', true);
        
        foreach( $order->get_items() as $item ) {
                      
                               
				if ( 'line_item' === $item['type'] && ! empty( $item['is_deposit'] ) ) {
					$deposit_full_amount       = (float) $item['_deposit_full_amount_ex_tax'];
					$deposit_deposit_amount    = (float) $item['_deposit_deposit_amount_ex_tax'];
					$deposit_deferred_discount = (float) $item['_deposit_deferred_discount'];
					if ( ( $deposit_full_amount - $deposit_deposit_amount ) > $deposit_deferred_discount ) {
                                                $productremaningProductsID[] = $item['product_id'];
						$remmaningamount =  $deposit_full_amount - $deposit_deposit_amount;
					}
				}
                    }
        if($remmaningamount !=0){
            
            $original_order = wc_get_order( $order_id );
            
        
           
            $items     = false;
            $status = "";
		foreach ( $original_order->get_items() as $order_item_id => $order_item ) {
                    
                        
                         
                         $order_item_pro_id = wc_get_order_item_meta($order_item_id, '_product_id', true);
                    
                        
			if (in_array($order_item_pro_id, $productremaningProductsID)) {
                            
                             
                                $order_item_id_update = $order_item_id;
				$items[] = $order_item;
                                $itemscheck = $order_item;
			}
                        
		}
               
                
                    
               
                
		$new_order      = wc_create_order( array(
			'status'        => $status,
			'customer_id'   => $original_order->get_user_id(),
			'customer_note' => $original_order->customer_note,
			'created_via'   => 'wc_deposits',
		) );
                
                
                
               
                    
                   
             
               
                
		if ( is_wp_error( $new_order ) ) {
                    
                    $original_order->add_order_note( sprintf( __( 'Error: Unable to create follow up payment (%s)', 'woocommerce-deposits' ), $scheduled_order->get_error_message() ) );
		
                    
                } else {
                    
                       
                        //echo 'checkoutstatus';
                        $new_order->set_address( array(
				'first_name' => $original_order->billing_first_name,
				'last_name'  => $original_order->billing_last_name,
				'company'    => $original_order->billing_company,
				'address_1'  => $original_order->billing_address_1,
				'address_2'  => $original_order->billing_address_2,
				'city'       => $original_order->billing_city,
				'state'      => $original_order->billing_state,
				'postcode'   => $original_order->billing_postcode,
				'country'    => $original_order->billing_country,
				'email'      => $original_order->billing_email,
				'phone'      => $original_order->billing_phone,
			), 'billing' );
                        
			$new_order->set_address( array(
				'first_name' => $original_order->shipping_first_name,
				'last_name'  => $original_order->shipping_last_name,
				'company'    => $original_order->shipping_company,
				'address_1'  => $original_order->shipping_address_1,
				'address_2'  => $original_order->shipping_address_2,
				'city'       => $original_order->shipping_city,
				'state'      => $original_order->shipping_state,
				'postcode'   => $original_order->shipping_postcode,
				'country'    => $original_order->shipping_country,
			), 'shipping' );

			// Handle items
			
			 foreach($items as $itemKey=>$itemData){
                            
                                if ( ! $itemData || empty( $itemData['is_deposit'] ) ) {
                                    return;
                                }
                                $full_amount_excl_tax = floatval( $itemData['deposit_full_amount_ex_tax'] );

                                    // Next, get the initial deposit already paid, excluding tax
                                $amount_already_paid = floatval( $itemData['deposit_deposit_amount_ex_tax'] );
                                         // Then, set the item subtotal that will be used in create order to the full amount less the amount already paid
				$subtotal = $full_amount_excl_tax - $amount_already_paid;
				
				if( version_compare( WC_VERSION, '3.2', '>=' ) ){
					// Lastly, subtract the deferred discount from the subtotal to get the total to be used to create the order
					$discount_excl_tax = isset($items['deposit_deferred_discount_ex_tax']) ? floatval( $items['deposit_deferred_discount_ex_tax'] ) : 0;
					$total = $subtotal - $discount_excl_tax;
				} else {
					$discount = floatval( $items['deposit_deferred_discount'] );
					$total = empty( $discount ) ? $subtotal : $subtotal - $discount;
				}
                             
                             
                            $item = array(
                                    'product'   => $original_order->get_product_from_item( $itemData ),
                                    'qty'       => 0,
                                    'subtotal'  => $subtotal,
                                    'total'     => $total
                            );
                            
                            $item_id = $new_order->add_product( $item['product'], $item['qty'], array(
				'totals' => array(
					'subtotal'     => $item['subtotal'], // cost before discount (for line quantity, not just unit)
					'total'        => $item['total'], // item cost (after discount) (for line quantity, not just unit)
					'subtotal_tax' => 0, // calculated within (WC_Abstract_Order) $new_order->calculate_totals
					'tax'          => 0, // calculated within (WC_Abstract_Order) $new_order->calculate_totals
				)
                            ) );
                            
                            wc_add_order_item_meta( $item_id, '_original_order_id', $order_id );

			   /* translators: Payment number for product's title */
			    wc_update_order_item( $item_id, array( 'order_item_name' => sprintf( __( 'Payment #%d for %s', 'woocommerce-deposits' ), 2, $item['product']->get_title() ) ) );
                       
                            
                        }
                        
                        
			// (WC_Abstract_Order) Calculate totals by looking at the contents of the order. Stores the totals and returns the orders final total.
			$new_order->calculate_totals( wc_tax_enabled() );

			// Set future date and parent
			$new_order_post = array(
				'ID'          => $new_order->id,
				'post_date'   => date( 'Y-m-d H:i:s', current_time( 'timestamp' ) ),
				'post_parent' => $order_id,
			);
			wp_update_post( $new_order_post );
                        
			do_action( 'woocommerce_deposits_create_order', $new_order->id );
                        $new_order->update_status('wc-pending-deposit');
                        
                        
                        
                        
                        foreach ( $new_order->get_items() as $order_item_id => $order_item ) {
                    
                        
                         
                         $order_item_pro_id = wc_get_order_item_meta($order_item_id, '_product_id', true);
                    
                        
                           
                            if (in_array($order_item_pro_id, $productremaningProductsID)) {

                                    $order_item_id_update = $order_item_id;
                                    
                                    wc_add_order_item_meta( $order_item_id_update, '_remaining_balance_order_id', $order_id );
                                    
                            }
                        
                        }
                        
                        
                        
			$new_order_ID =  $new_order->id;
		}
            
   
                
                
            
            $emails = WC_Emails::instance();
            $emails->customer_invoice( wc_get_order( $new_order_ID ) );
            $orderstatus = "partial-payment";
            
            
            
        }
        
        
        if($payment_method == 'cheque'){
                  
            
            
            
                  foreach( $order->get_items() as $item ) {
                      
                                $porduct_ids_array[] = $item['product_id'];
				
                    }
           
            
            //exp_updateuser_role_onmpospurches($order,$porduct_ids_array);
            exp_updateuser_role_onmpospurches($order->id,$porduct_ids_array);
            
            
            
           
            
            $order->update_status($orderstatus);
        }
     
}
function exp_autocomplete_paid_orders($order_status, $order_id) {
        
       
        if (!$order_id)
                return;
        $order = wc_get_order($order_id);
        
       
        
        $payment_method = get_post_meta($order->id, '_payment_method', true);
        
        
            if (count($order->get_items()) > 0) {
                foreach ($order->get_items() as $item_id => $item_obj) {
                        
                       
                        $result_check = wc_get_order_item_meta($item_id, '_bundled_by', true);
                        if(empty($result_check)){
                            
                            $porduct_ids_array[] = wc_get_order_item_meta($item_id, '_product_id', true);
                            
                        }
                        
                        
                   
                }
            }
            
             
         
            exp_updateuser_role_onmpospurches($order->id,$porduct_ids_array);
        
            
           
          
            if ($order_status == 'processing' && ($order->status == 'on-hold' || $order->status == 'pending' || $order->status == 'failed')) {
                return 'completed';
            }
            return 'completed';
}




add_action( 'woocommerce_checkout_process', 'reviewboothproducts', 10 );



function reviewboothproducts($order){
    
    
    
    require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/floorplan-manager.php';
    $floorplanObject = new FloorPlanManager();
    $items = WC()->cart->get_cart();
     
      $contentmanager_settings = get_option( 'ContenteManager_Settings' );
      $FloorpLanid = $contentmanager_settings['ContentManager']['floorplanactiveid'];
      
      
   
    
    
    foreach ($items as $item => $values)
    {
        $_product = $values['data']->post;
        $product_ID = $_product->ID;
        $product_title = $_product->post_title;
       
        $getthisproductdetailinfloorplan = $floorplanObject->getProductstauts($product_ID);
        
        $get_BoothCellID = $getthisproductdetailinfloorplan['BoothID'];
        
        if(!empty($get_BoothCellID)){
            
         $ViewerLockstatus = $floorplanObject->getFloorplanStatus($FloorpLanid);
         if($ViewerLockstatus != 'lock'){
             
            $getBoothOwner = $getthisproductdetailinfloorplan['BoothOwner'];
        
            if($getBoothOwner != 'none' && $getBoothOwner != ''){
            
            
                wc_add_notice( __( 'Booth number '.$product_title.' in your cart is no longer available for purchase. Please try another booth.' ), 'error' );
            }
            
         }else{
             
            wc_add_notice( __( 'The floorplan is currently locked by the Administrators so checkout is not possible. Please try again later.' ), 'error' );
         
            
         }
        }
    }
  
   
    
}

function exp_updateuser_role_onmpospurches($order,$porduct_ids_array){
        
       
           
        if(is_array($order)){
            
            $order_ID = $order->id;
            
        }else{
            
            $order_ID = $order;
        }
 
        $current_user = wp_get_current_user();
       // $lastInsertId = contentmanagerlogging('Purches MPOs',"User Action",serialize($order),''.$current_user->id,$current_user->user_email,"pre_action_data");
        require_once( 'temp/lib/woocommerce-api.php' );
        
        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_type'        => 'egpl_custome_tasks',
            'post_status'      => 'draft',
	
        );
        $taskkeyContent = get_posts( $args );
        
        
        
        $url = get_site_url();//'https://'.$_SERVER['SERVER_NAME'];
        $options = array(
            'debug' => true,
            'return_as_array' => false,
            'validate_url' => false,
            'timeout' => 30,
            'ssl_verify' => false,
        );
        
        $woocommerce_rest_api_keys = get_option( 'ContenteManager_Settings' );
        
        $boothpurchaseenablestatus = $woocommerce_rest_api_keys['ContentManager']['boothpurchasestatus'];
        $wooconsumerkey = $woocommerce_rest_api_keys['ContentManager']['wooconsumerkey'];
        $wooseceretkey = $woocommerce_rest_api_keys['ContentManager']['wooseceretkey'];
        $woocommerce = new WC_API_Client( $url, $wooconsumerkey, $wooseceretkey, $options );
        
        
        
        
        if (count($porduct_ids_array) > 0) {
                foreach ($porduct_ids_array as $item=>$ids) {
                   
                    
                    $getproduct_detail = $woocommerce->products->get( $ids );
                    
                    $productID =  $ids;
                   
                    
                  
                    
                    
                    
                    if($getproduct_detail->product->categories[0] != 'Package' && $getproduct_detail->product->categories[0] != 'Add-on'){
                        
                        
                        $id = wp_insert_post(array('post_title'=>'Booth Purchase Review_'.$order_ID, 'post_type'=>'booth_review', 'post_content'=>''));
                        
                       
                        
                      
                        update_post_meta( $id, 'porductID', $ids );
                        update_post_meta( $id, 'orderID', $order_ID );
                        update_post_meta( $id, 'OrderUserID', $current_user->ID);
                       
                        
                        
                        if(!empty($boothpurchaseenablestatus) && $boothpurchaseenablestatus == "enabled"){
                            
                         
                          
                           $OrderUserID = $current_user->ID;
                           $foolrplanID = $woocommerce_rest_api_keys['ContentManager']['floorplanactiveid'];
                           $boothTypesLegend = json_decode(get_post_meta($foolrplanID, 'legendlabels', true )); 
                           
                          
                           
                           $FloorplanXml = get_post_meta( $foolrplanID, 'floorplan_xml', true );
                           
                         
                            
                           $FloorplanXml = str_replace('"n<','<',$FloorplanXml);
                           $FloorplanXml= str_replace('>n"','>',$FloorplanXml);
                          
                           
                           $xml=simplexml_load_string($FloorplanXml) or die("Error: Cannot create object");
                           $currentIndex = 0;
                           
                         
                           
                           foreach ($xml->root->MyNode as $cellIndex=>$CellValue){
                              
                                
                                $cellboothlabelvalue = $CellValue->attributes();
                                $getCellStylevalue = $xml->root->MyNode[$currentIndex]->mxCell->attributes();
                               
                                if($cellboothlabelvalue['boothproductid'] == $productID){

                                 
                                    $att = "boothOwner";
                                    $styleatt = 'style';
                                    $xml->root->MyNode[$currentIndex]->attributes()->$att = $OrderUserID;

                                    $getCellStyle = $getCellStylevalue['style'];

                                    $getCellStyle = str_replace($oldfillcolortext,'fillColor='.$NewfillColor,$getCellStyle);
                                    $xml->root->MyNode[$currentIndex]->mxCell->attributes()->$styleatt = $getCellStyle;
                                    
                                  
                                    
                                    if(isset($cellboothlabelvalue['legendlabels']) && !empty($cellboothlabelvalue['legendlabels'])){


                                        $getlabelID = $cellboothlabelvalue['pricetegid'];

                                        foreach ($boothTypesLegend as $boothlabelIndex=>$boothlabelValue){
                                            if($boothlabelValue->ID ==  $getlabelID){

                                                $createdproductPrice = $boothlabelValue->colorcode;
                                                if($createdproductPrice != "none"){

                                                    $NewfillColor = $createdproductPrice;

                                                }else{
                                                    $getCellStyleArray = explode(';',$getCellStyle);
                                                    foreach ($getCellStyleArray as $styleIndex=>$styleValue){


                                                        if($styleValue != 'DefaultStyle1'){

                                                            $styledeepCheck = explode('=',$styleValue);

                                                            if($styledeepCheck[0] == 'occ'){

                                                                $NewfillColor = $styledeepCheck[1];

                                                            }else if($styledeepCheck[0] == 'fillColor'){

                                                                $oldfillcolortext = $styleValue;
                                                            }


                                                        }


                                                    }

                                                }


                                            }
                                        }
                                    
                                    }else{

                                            $getCellStyleArray = explode(';',$getCellStyle);
                                            foreach ($getCellStyleArray as $styleIndex=>$styleValue){


                                                if($styleValue != 'DefaultStyle1'){

                                                    $styledeepCheck = explode('=',$styleValue);

                                                    if($styledeepCheck[0] == 'occ'){

                                                        $NewfillColor = $styledeepCheck[1];

                                                    }else if($styledeepCheck[0] == 'fillColor'){

                                                        $oldfillcolortext = $styleValue;
                                                    }


                                                }


                                            }
                                    }


                                   $getCellStyle = str_replace($oldfillcolortext,'fillColor='.$NewfillColor,$getCellStyle);
                                   $xml->root->MyNode[$currentIndex]->mxCell->attributes()->$styleatt = $getCellStyle;

                                }
                                $currentIndex++;
    
                            }
                                
                                $getresultforupdat = str_replace('<?xml version="1.0"?>',"",$xml->asXML());
                                update_post_meta( $foolrplanID, 'floorplan_xml', json_encode($getresultforupdat));
                                update_post_meta( $id, 'boothStatus', 'Completed' );
                            
                        }else{
                            
                                update_post_meta( $id, 'boothStatus', 'Pending' );
                           
                        }
                        
                        
                        
                        
                        
                        
                        
                    }
                    
                    $get_productlevel = get_post_meta( $productID, 'productlevel', true );
                    
                    
                    if(!empty($get_productlevel)){
                        
                         $seletedroleValue = $get_productlevel;
                         $assign_role[] = $seletedroleValue;
                        
                    }
                    
                    $selectedTaskListData = get_post_meta( $ids);
                    $selectedTaskList = unserialize($selectedTaskListData['seletedtaskKeys'][0]);
                    
                    if(!empty($selectedTaskList['selectedtasks'])){
                        
                        
                        
                      
                        $latestProductsValue = $selectedTaskList;
                       
                    
                        
                    }
                }
            }
            
            
           
            
                $user_info = get_userdata($current_user->id);
            
                if($user_info->roles[0] !='administrator' && $user_info->roles[0] !='contentmanager'){
                    foreach ($assign_role as $key=>$rolename){
                       if(!empty($rolename)){
                           
                            $u = new WP_User($current_user->id);
                            $u->set_role( $rolename );
                           $responce['assignrole'] = $rolename;
                       } 
                        
                    }
                }
                  if(!empty($latestProductsValue['selectedtasks'])){  
                   foreach ($latestProductsValue['selectedtasks'] as $taskindex=>$taskKey){
                       
                       
                       $value_usersids = get_post_meta( $taskKey, 'usersids' , false);
                       
                       
                       
                       
                       if(!empty($value_usersids[0])){
                           
                           array_push($value_usersids[0], $current_user->id);
                           update_post_meta( $taskKey, 'usersids' , $value_usersids[0]);
                           
                       }else{
                           
                           $newindex[]=$current_user->id;
                            update_post_meta( $taskKey, 'usersids' , $newindex);
                           
                       }
                       
                       
                   }
                   
                  }
                  
                   
                   
               
           
            
            $responce['paymentmethod'] = $payment_method;
            $responce['paymentstatus'] = 'completed';
            $responce['assignrole'] = $assign_role[0];
           // contentmanagerlogging_file_upload ($lastInsertId,serialize($responce));
}

function multidimensional_search($parents, $searched) { 
  if (empty($searched) || empty($parents)) { 
    return false; 
  } 

  foreach ($parents as $key => $value) { 
    $exists = true; 
    foreach ($searched as $skey => $svalue) { 
      $exists = ($exists && IsSet($parents[$key][$skey]) && $parents[$key][$skey] == $svalue); 
    } 
    if($exists){ return $key; } 
  } 

  return false; 
} 


function registrtionlink_func( $atts ) {
    
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $selfsignstatus = $oldvalues['ContentManager']['selfsignstatus'];
     if($selfsignstatus == 'enable'){
         
         $button_text = '<a href="/registration/" class ="fusion-button fusion-button-default fusion-button-large fusion-button-round fusion-button-flat" >Registration</a>';
     }else{
         
         $button_text = "";
     }
    return $button_text;
}
add_shortcode( 'registrtionlink', 'registrtionlink_func' );

function myregisterrequest_new_user($username, $email){
    
    
      $username = sanitize_user($username);
      $user_id = register_new_user( $username, $email );
      return $user_id;
    
    
}

add_action( 'wp_footer','checkloginuserstatus_fun' );
function checkloginuserstatus_fun() {
    
    
     $site_url  = get_site_url();
     $oldvalues = get_option( 'ContenteManager_Settings' );
     $mainheader = $oldvalues['ContentManager']['mainheader'];
     $mainheaderlogo = $oldvalues['ContentManager']['mainheaderlogo'];
     $redirectname = $oldvalues['ContentManager']['redirectcatname'];
     
     if(!empty($mainheader)){
                      $headerbanner =  "url('".$mainheader."')";
                      echo '<style> .fusion-header{background-image:'.$headerbanner.'}; </style>';
                }
     
     $redirectURL = "";
    if ( is_user_logged_in() ) {
     
     $current_user = wp_get_current_user();
     $user_id = get_current_user_id();
     $currentSiteID = get_current_blog_id();
     
     $current_user_blogs = get_blogs_of_user( $user_id );
     foreach($current_user_blogs as $BlogIndex=>$BlogData){
         
         
         $currentuserArray[]=$BlogData->userblog_id;
         
         
     }
     
     if (in_array($currentSiteID, $currentuserArray)) {
    
     
                if($redirectname == 'boothpurchase'){

                    $redirectURL = $site_url.'/floor-plan/';
                    $valuename = "booth";

                }else{

                    $redirectURL = $site_url.'/product-category/packages/';
                    $valuename = "package";
                }

                

               $current_user = wp_get_current_user();
               $roles = $current_user->roles;

               $newvalue = time();
               $custome_login_time_site = update_user_option( $current_user->ID, 'custom_login_time_as_site',$newvalue );



               if ( class_exists( 'WooCommerce' ) ) {	
                   if (is_user_logged_in()) {

                               if ($roles[0] == 'subscriber') {
                                   
                              
                                   $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                  
                                   if($actual_link != "https://" . $_SERVER['SERVER_NAME'].'/home/'){
                                       if (strpos($actual_link, 'task-list/') !== false || strpos($actual_link, 'home/') !== false  || strpos($actual_link, 'resources/') !== false || strpos($actual_link, 'registration-codes/') !== false) {



                                            echo '<script type="text/javascript">swal({title: "Welcome!", type: "success", html:true,showConfirmButton:false,text: "<p>This will serve as your portal for managing all of your pre-show logistics. Before gaining access, you\'ll need to first select and purchase a '.$valuename.'.</p><p style=\'margin-top:18px\'><a href='.$redirectURL.' class=\'fusion-button fusion-button-default fusion-button-large fusion-button-round fusion-button-flat\'>Next</a></p>"});</script>';

                                       }
                                    }
                               }
                   } 
               }
    }else{
        
       
        wp_redirect("https://" . $_SERVER['SERVER_NAME'].'/home/'); 
      
        die();
        
        
    }
    }
}


// ShortCode For Display View Floor Plan Button

function viewfloorplanbutton( $atts ){
	  
      
      return '<button id="floorplanpopup" onclick="openpopup()" class="button fusion-button fusion-button-default button-square fusion-button-xlarge button-xlarge button-flat  fusion-mobile-button continue-center">View Floor Plan</button>';
      
}
add_shortcode( 'viewfloorplanbutton', 'viewfloorplanbutton' );
add_filter('manage_expo_genie_log_posts_columns', 'bs_event_table_head');
function bs_event_table_head( $defaults ) {
    
    
    
    
    
    $defaults['action-type-name']  = 'Action Type';
    $defaults['currentuseremail']    = 'User Email';
    $defaults['ip-address']   = 'IP Address';
    $defaults['browser-agent'] = 'Browser Agent';
    $defaults['request-data-and-time'] = 'Date & Time';
    return $defaults;
}

add_action( 'manage_expo_genie_log_posts_custom_column', 'bs_event_table_content', 10, 2 );

function bs_event_table_content( $column_name, $post_id ) {
    if ($column_name == 'actiontype') {
    $event_date = get_post_meta( $post_id, 'actiontype', true );
      echo   $event_date ;
    }
    if ($column_name == 'preactiondata') {
    $event_date = print_r(get_post_meta( $post_id, 'preactiondata', true ));
      echo   $event_date ;
    }
    if ($column_name == 'email') {
    $event_date = get_post_meta( $post_id, 'email', true );
      echo   $event_date ;
    }
    if ($column_name == 'ip') {
    $event_date = get_post_meta( $post_id, 'ip', true );
      echo   $event_date ;
    }
    if ($column_name == 'browseragent') {
    $event_date = get_post_meta( $post_id, 'browseragent', true );
      echo   $event_date ;
    }
    if ($column_name == 'result') {
    $event_date = print_r(get_post_meta( $post_id, 'result', true ));
      echo   $event_date ;
    }
    

}


function myplugin_plugin_path() {

  // gets the absolute path to this plugin directory

  return untrailingslashit( plugin_dir_path( __FILE__ ) );
}
add_filter( 'woocommerce_locate_template', 'myplugin_woocommerce_locate_template', 10,10);



function myplugin_woocommerce_locate_template( $template, $template_name, $template_path ) {
    
    
  global $woocommerce;
  $_template = $template;

  if ( ! $template_path ) $template_path = $woocommerce->template_url;

  $plugin_path  = myplugin_plugin_path() . '/woocommerce/';

  // Look within passed path within the theme - this is priority
  $template = locate_template(

    array(
      $template_path . $template_name,
      $template_name
    )
  );

  // Modification: Get the template from this plugin, if it exists
  if ( ! $template && file_exists( $plugin_path . $template_name ) )
    $template = $plugin_path . $template_name;

  // Use default template
  if ( ! $template )
    $template = $_template;

  // Return what we found
  
//  echo $template;
  
  return $template;
}


add_filter('woocommerce_cart_item_permalink','__return_false');
add_filter( 'woocommerce_order_item_permalink', '__return_false' );



add_filter('woocommerce_get_availability_text', function($text, $product) {
    if (!$product->is_in_stock()) {
        $text = 'No Longer Available';
    }
 
    return $text;
}, 10, 2);

add_filter( 'woocommerce_my_account_my_orders_query', 'custom_my_account_orders_query', 20, 1 );
function custom_my_account_orders_query( $args ) {
    $args['limit'] = -1;

    return $args;
}


add_filter( 'woocommerce_return_to_shop_redirect', 'wc_empty_cart_redirect_url' );
function wc_empty_cart_redirect_url() {
	$site_url  = get_site_url();
	return $site_url.'/product-category/add-ons/';
}
add_filter( 'gettext', 'woocommerce_rename_coupon_field_on_cart', 10, 3 );
function woocommerce_rename_coupon_field_on_cart( $translated_text, $text, $text_domain ) {
	// bail if not modifying frontend woocommerce text
	
	if ( 'Apply coupon' === $text ) {
		$translated_text = 'Apply Discount';
	}


	return $translated_text;
}


add_filter( 'woocommerce_coupon_error', 'rename_coupon_label', 10, 3 );
add_filter('woocommerce_coupon_message', 'rename_coupon_label', 10, 3);
add_filter('woocommerce_checkout_coupon_message', 'rename_coupon_label', 10, 3);

function rename_coupon_label( $translated_text, $text, $text_domain ) {
	
	$text = str_replace("Coupon","Discount",$translated_text);
        return  $text;
        
        
        
}


function getcustomefieldKeyValue($fieldKey,$getKeyValue){

	require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/egpl-custome-functions.php';
    $GetAllcustomefields = new EGPLCustomeFunctions();
    $additional_fields = $GetAllcustomefields->getAllcustomefields();
    $blog_id = get_current_blog_id();
    $site_prefix = 'wp_'.$blog_id.'_';
    
    $fieldKey = str_replace($site_prefix,"",$fieldKey);
	foreach ($additional_fields as $key=>$value){ 
	
	
		if($fieldKey == $value['fielduniquekey']){
		
			$FieldType = $value[$getKeyValue];
		
		
		}
 
 
 
	}

	return $FieldType;


}?>