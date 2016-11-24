<?php

/*
  Plugin Name: Content Manager
  Description: Manage The Admin Panel
  Version: 1.1
  Author: E2ESP
  Author URI:

 */


//get all the plugin settings

if ($_GET['contentManagerRequest'] == 'getavailablemergefields') {
    
    require_once('../../../wp-load.php');
    
    $test = 'user_meta_manager_data';
    $result = get_option($test);
    $keys_string[] = 'first_name';
    $keys_string[]= 'last_name';
    $keys_string[]= 'date';
    $keys_string[]= 'time';
    $keys_string[]= 'user_pass';
    $keys_string[]= 'site_url';
    $keys_string[]= 'site_title';
    $keys_string[]= 'create_password_url';
    $keys_string[]= 'user_login';
    
    $bodytext_id = 'welcomebodytext';
    
    foreach($result['custom_meta'] as $key=>$value){
      
      if (preg_match('/task/',$key)){
          
      }else{
     
        $keys_string[]= $key; 
      }
      
    }
    
    
 // echo '<pre>';
 // print_r( $result['sort_order'] );
    
    
    echo  json_encode($keys_string);
    
 
   die();

}else if ($_GET['contentManagerRequest'] == 'getpageContent') {
    
    require_once('../../../wp-load.php');
    
    $content_ID=$_POST['pageID'];
    $page_data = get_page($content_ID);
    $data_array['pagecontent'] = $page_data->post_content;
    $data_array['pagetitle'] = $page_data->post_title;
    
    
    echo   json_encode($data_array);
    
 
   die();

}else if ($_GET['contentManagerRequest'] == 'updatepagecontent') {
    
    require_once('../../../wp-load.php');
    
    $content_ID=$_POST['contentbodyID'];
    $content_Title=$_POST['contenttitle'];
    $content_body_message=$_POST['contentbody'];
    $my_post = array(
      'ID'           => $content_ID,
      'post_title'   => $content_Title,
      'post_content' => $content_body_message,
  );
    
 $post_id = wp_update_post( $my_post, true );						  
 if (is_wp_error($post_id)) {
	$errors = $post_id->get_error_messages();
	foreach ($errors as $error) {
		echo $error;
	}
}
$user_ID = get_current_user_id();
$user_info = get_userdata($user_ID);
}

if ($_GET['contentManagerRequest'] == 'changepassword') {
    
    require_once('../../../wp-load.php');
   
     
    
    $newpassword = $_POST['newpassword'];
    
    setpasswordcustome($newpassword);
    
     
   die();

}else if ($_GET['contentManagerRequest'] == 'plugin_settings') {
    
    require_once('../../../wp-load.php');
    
     plugin_settings();
     
   die();

}else if ($_GET['contentManagerRequest'] == 'remove_post_resource') {
    
      require_once('../../../wp-load.php');
      
    try{
        
     $post_id = $_POST['id'];
     $large_image_url = get_post_meta($post_id, 'port-descr', 1);
     
     $user_ID = get_current_user_id();
     $user_info = get_userdata($user_ID);
     $lastInsertId = contentmanagerlogging('Delete Resource',"Admin Action",serialize($large_image_url),$postid,$user_info->user_email,"pre_action_data");
     $result = remove_post_resource($post_id);
     contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
     die();
}else if ($_GET['contentManagerRequest'] == 'remove_sponsor_metas') {
    
    require_once('../../../wp-load.php');
    
     $user_id = $_POST['id'];
  
     remove_sponsor_metas($user_id);
     
    
}else if ($_GET['contentManagerRequest'] == 'update_new_sponsor_metafields') {
     require_once('../../../wp-load.php');
   
   try{
       
     $user_ID = get_current_user_id();
     $user_info = get_userdata($user_ID); 
     $lastInsertId = contentmanagerlogging('Admin Edits User',"User Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
       
    $userid=$_POST['sponsorid'];
    $password=$_POST['password'];
    $role =$_POST['sponsorlevel'];
    $loggin_data=$_POST;
    unset($_POST['sponsorlevel']);
    unset($_POST['sponsorid']);
    unset($_POST['password']);
    
    $meta_array=$_POST;
    if(!empty($password)){ wp_set_password( $password, $userid );}
    
   
           $result =  add_new_sponsor_metafields($userid,$meta_array,$role);
           contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
           
   }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
   die();
    
}else if ($_GET['contentManagerRequest'] == 'add_new_sponsor_metafields') {
    require_once('../../../wp-load.php');
    
    
    try{
    
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);  
    $lastInsertId = contentmanagerlogging('New User',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
      
    $username = str_replace("+","",$_POST['username']);;
    $email = $_POST['email'];
    $role =$_POST['sponsorlevel'];
    $loggin_data=$_POST;
    
    
    unset($_POST['username']);
    unset($_POST['email']);
    unset($_POST['sponsorlevel']);
    
  //  print_r($_POST);


    $user_id = username_exists($username);
    
    $message['username'] = $username;
    
   
    if (!$user_id and email_exists($email) == false) {
        
       $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
       $user_id = register_new_user( $username, $email );//wp_create_user($username, $random_password, $email);
       $result=$user_id;
       $loggin_data['created_id']=$result;
       $message['user_id'] = $user_id;
       $message['msg'] = 'User created';
       $message['userrole'] = $role;
       $meta_array=$_POST;
       
       add_new_sponsor_metafields($user_id,$meta_array,$role);
       
       custome_email_send($user_id);
       
    } else {
        
        $message['msg'] = 'User already exists';
        
    }
    
    $loggin_data['msg']=$message['msg'];
    
    contentmanagerlogging_file_upload ($lastInsertId,serialize($loggin_data));
    echo json_encode($message);
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    die();

    //
}else if ($_GET['contentManagerRequest'] == 'resource_new_post') {

    require_once('../../../wp-load.php');
  try{
      
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $lastInsertId = contentmanagerlogging('New Resource',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
      
    $title=$_POST['title'];
    $file=$_FILES['file'];
    $resourceurl = resource_file_upload($file);
    
    $loggin_data['title']=$title;
    $loggin_data['fileurl']=$resourceurl;
   
    
    if($resourceurl != null){    
     $result = resource_new_post($title,$resourceurl);
    }
    echo   json_encode($resourceurl);
    contentmanagerlogging('New Resource',"Admin Action",serialize($loggin_data),$user_ID,$user_info->user_email,$result);
    contentmanagerlogging_file_upload ($lastInsertId,serialize($loggin_data));   
  }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    die();
}else if($_GET['contentManagerRequest'] == 'getReportsdatanew'){ 
    require_once('../../../wp-load.php');
     try{
      
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $lastInsertId = contentmanagerlogging('Load Report',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
    $report_name=$_POST['reportName'];
    getReportsdatanew($report_name); 
    $result='Report Loaded';
    
    contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
    
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
}else if($_GET['contentManagerRequest'] == 'updatecmanagersettings'){ 
    require_once('../../../wp-load.php');
    
   
    updatecmanagersettings($_POST); 
   
    
    
    
}else if ($_GET['contentManagerRequest'] == 'update_admin_report') {
    
    require_once('../../../wp-load.php');
    
    
    $report_name =$_POST['reportName'];
    unset($_POST['reportName']);
    updateadminreport($_POST,$report_name);
     
     die();

}else if ($_GET['contentManagerRequest'] == 'getsavedReportvalues') {
    
    require_once('../../../wp-load.php');
    
    
    $report_name =$_POST['reportName'];
   
    getthereportsavalues($report_name);
     
     die();

}else if ($_GET['contentManagerRequest'] == 'sendbulkemail') {
    
    require_once('../../../wp-load.php');
    require_once 'Mandrill.php';
   
try { 
    
    
     $oldvalues = get_option( 'ContenteManager_Settings' );
     $mandrill = $oldvalues['ContentManager']['mandrill'];
    
    $mandrill = new Mandrill($mandrill);
    
    
    $subject =$_POST['emailSubject'];
    $body=stripslashes ($_POST['emailBody']);
    $emailAddress=$_POST['emailAddress'];
    $emailaddress_array=explode(",", $emailAddress);
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $attendeefields_data=json_decode(stripslashes($_POST['attendeeallfields']), true);
    $colsdatatype=json_decode(stripslashes($_POST['datacollist']), true);
    $field_key_string = getInbetweenStrings('{', '}', $body);
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $formemail = $oldvalues['ContentManager']['formemail'];
    if(empty($formemail)){
        $formemail = 'noreply@convospark.com';
        
    }
   $bcc = $_POST['BCC'];
 
   $fromname = $_POST['fromname'];
  
//print_r($attendeefields_data);;
    
    
    $site_url = get_option('siteurl' );
    
    $login_url = get_option('siteurl' );
    $admin_email= get_option('admin_email');
    $data=  date("Y-m-d");
    $time=  date('H:i:s');
    
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
        array('name'=>'siteurl','content'=>$site_url)
        );
    
   // foreach($emailaddress_array as $email=>$to){
       
       $body_message =    $body ;
      // $user = get_user_by( 'email', $to );
      // $firstname=$user->first_name;
      // $lastname=$user->last_name;
      // $user_email=$to;
       
       
       foreach($attendeefields_data as $key=>$value){
       
                $data_field_array= array();
                foreach($field_key_string as $index=>$keyvalue){
                  if($keyvalue == 'date' || $keyvalue == 'time' || $keyvalue == 'siteurl'){
                       
                   }else{
                       if(!empty($value[$keyvalue])){
                        if($colsdatatype[$keyvalue]['type'] == 'date') {
                            
                          $date_value =   date('d-m-Y', intval($value[$keyvalue])/1000);
                          $data_field_array[] = array('name'=>$keyvalue,'content'=>$date_value);
                          
                        } else{
                            
                          $data_field_array[] = array('name'=>$keyvalue,'content'=>$value[$keyvalue]);  
                        }
                       }else{
                         $data_field_array[] = array('name'=>$keyvalue,'content'=>'');  
                       }
                   }
                  
                }
                
           $to_message_array[]=array('email'=>$value['Email'],'name'=>$value['first_name'],'type'=>'to');
           $user_data_array[] =array(
                'rcpt'=>$value['Email'],
                'vars'=>$data_field_array
           );
 
        }
       
       
       //$result = send_email($to,$subject,$body_message);

    
  
   
  // echo '<pre>';
 //  print_r($bcc);exit;
   $message = array(
        
        'html' => $body,
        'text' => '',
        'subject' => $subject,
        'from_email' => $formemail,
        'from_name' => $fromname,
        'to' => $to_message_array,
        'headers' => array('Reply-To' => $formemail),
        
        'track_opens' => true,
        'track_clicks' => true,
        'bcc_address' => $bcc,
        'merge' => true,
        'merge_language' => 'mailchimp',
        'global_merge_vars' => $goble_data_array,
        'merge_vars' => $user_data_array
        
        
    );
   
    // exit;
       
    $lastInsertId = contentmanagerlogging('Bulk Email',"Admin Action",serialize($message),$user_ID,$user_info->user_email,"pre_action_data");
     
    $async = false;
    $ip_pool = 'Main Pool';
   // $send_at = 'example send_at';
    $result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
    contentmanagerlogging_file_upload($lastInsertId,serialize($result));
    echo 'successfully send';
   
    
}catch(Mandrill_Error $e) {
    // Mandrill errors are thrown as exceptions
    $error_msg = 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
    // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
    
 
    contentmanagerlogging_file_upload($lastInsertId,$error_msg);
     echo   $e->getMessage();
    //throw $e;
}
 die();
}else if ($_GET['contentManagerRequest'] == 'sendadmintestemail') {
    
    require_once('../../../wp-load.php');
    
    try{
        
         
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $lastInsertId = contentmanagerlogging('Admin Test Email',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
       
    
        
    $subject =$_POST['emailSubject'];
    $body=stripslashes ($_POST['emailBody']);
    
   
    
    
    $site_url = get_option('siteurl' );
    $login_url = get_option('siteurl' );
    $admin_email= get_option('admin_email');
    $data=  date("Y-m-d");
    $time=  date('H:i:s');
    $site_title=get_option( 'blogname' );
    
    
    
    $body = str_replace('[site_url]', $site_url, $body);
    $body = str_replace('[login_url]', $site_url, $body);
    $body = str_replace('[admin_email]', $admin_email, $body);
    $body = str_replace('[date]', $data, $body);
    $body = str_replace('[time]', $time, $body);
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $formemail = $oldvalues['ContentManager']['formemail'];
    if(empty($formemail)){
        $formemail='noreply@convospark.com';
    }
       
      $body_message =    $body ;
      $subject_body =$subject;
      $site_url = get_option('siteurl' );
      $data=  date("Y-m-d");
      $time=  date('H:i:s');
      $user = get_user_by( 'email', $user_info->user_email );
      $firstname=$user->first_name;
      $lastname=$user->last_name;
      $headers = 'From: '.$site_title.' <'.$formemail.'>' . "\r\n";
       $body_message = str_replace('[user_email]', $user_email, $body_message);
       $body_message = str_replace('[first_name]', $firstname, $body_message);
       $body_message = str_replace('[last_name]', $lastname, $body_message);
       $body_message = str_replace('[site_title]', $site_title, $body_message);
       $body_message = str_replace('[date]', $data, $body_message);
       $body_message = str_replace('[time]', $time, $body_message);
       $body_message = str_replace('[site_url]', $site_url, $body_message);
       
       $subject_body = str_replace('[user_email]', $user_email, $subject_body);
       $subject_body = str_replace('[first_name]', $firstname, $subject_body);
       $subject_body = str_replace('[last_name]', $lastname, $subject_body);
       $subject_body = str_replace('[site_title]', $site_title, $subject_body);
       $subject_body = str_replace('[user_pass]', $plaintext_pass, $subject_body);
         $subject_body = str_replace('[date]', $data, $subject_body);
         $subject_body = str_replace('[time]', $time, $subject_body);
         $subject_body = str_replace('[site_url]', $site_url, $subject_body);
       
       
       
       $result = send_email($user_info->user_email,$subject_body,$body_message,$headers);

    
   
     //contentmanagerlogging('Admin Test Email',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,$result);
      contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
    }
    catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
 die();

}else if ($_GET['contentManagerRequest'] == 'sendadmintestemailwelcome') {
    
    require_once('../../../wp-load.php');
    
    try{
    $subject =$_POST['emailSubject'];
    $body=$_POST['emailBody'];
    $welcomeemailfromname = $_POST['welcomeemailfromname'];
    $replaytoemailadd = $_POST['replaytoemailadd'];
    
    
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
   $lastInsertId = contentmanagerlogging('Admin Test Email Welcome',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
      
    
    
    $site_url = get_option('siteurl' );
    $login_url = get_option('siteurl' );
    $admin_email= get_option('admin_email');
    $data=  date("Y-m-d");
    $time=  date('H:i:s');
    $site_title=get_option( 'blogname' );
    
    
    
    $body = str_replace('[site_url]', $site_url, $body);
    $body = str_replace('[login_url]', $site_url, $body);
    $body = str_replace('[admin_email]', $admin_email, $body);
    $body = str_replace('[date]', $data, $body);
    $body = str_replace('[time]', $time, $body);
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $formemail = $oldvalues['ContentManager']['formemail'];
    if(empty($formemail)){
        $formemail = 'noreply@convospark.com';
        
    }
   
       
      $body_message =    $body ;
      $subject_body =$subject;
      $site_url = get_option('siteurl' );
      $data=  date("Y-m-d");
      $time=  date('H:i:s');
      $user = get_user_by( 'email', $user_info->user_email );
      $firstname=$user->first_name;
      $lastname=$user->last_name;
      $headers = 'From: '.$welcomeemailfromname.' <'.$formemail.'>' . "\r\n";
      $headers .= 'Reply-To: '.$replaytoemailadd;
      
      $body_message = str_replace('[user_email]', $user_email, $body_message);
      $body_message = str_replace('[first_name]', $firstname, $body_message);
       $body_message = str_replace('[last_name]', $lastname, $body_message);
       $body_message = str_replace('[site_title]', $site_title, $body_message);
       $body_message = str_replace('[date]', $data, $body_message);
       $body_message = str_replace('[time]', $time, $body_message);
       $body_message = str_replace('[site_url]', $site_url, $body_message);
       
       $subject_body = str_replace('[user_email]', $user_email, $subject_body);
       $subject_body = str_replace('[first_name]', $firstname, $subject_body);
       $subject_body = str_replace('[last_name]', $lastname, $subject_body);
       $subject_body = str_replace('[site_title]', $site_title, $subject_body);
       $subject_body = str_replace('[user_pass]', $plaintext_pass, $subject_body);
         $subject_body = str_replace('[date]', $data, $subject_body);
         $subject_body = str_replace('[time]', $time, $subject_body);
         $subject_body = str_replace('[site_url]', $site_url, $subject_body);
       
       
       
       $result = send_email($user_info->user_email,$subject_body,$body_message,$headers);

    
   
      contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
    }
    catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
     die();

}else if ($_GET['contentManagerRequest'] == 'update_admin_email_template') {
    
    require_once('../../../wp-load.php');
    
    
    $report_name =$_POST['emailtemplatename'];
    unset($_POST['emailtemplatename']);
    updateadminemailtemplate($_POST,$report_name);
     
     die();

}else if ($_GET['contentManagerRequest'] == 'get_email_template') {
    
    require_once('../../../wp-load.php');
    
    
    $report_name =$_POST['emailtemplatename'];
    $settitng_key='AR_Contentmanager_Email_Template';
    $get_email_template_date = get_option($settitng_key);
    
   
    $template_data['emailsubject'] = $get_email_template_date[$report_name]['emailsubject'];
    $template_data['emailboday'] = $get_email_template_date[$report_name]['emailboday'];
    $template_data['BCC'] = $get_email_template_date[$report_name]['BCC'];
    $template_data['fromname'] = $get_email_template_date[$report_name]['fromname'];
   
     
    echo   json_encode($template_data);
     
     die();
     

}else if ($_GET['contentManagerRequest'] == 'remove_email_template') {
    
    require_once('../../../wp-load.php');
    
    try{
       $user_ID = get_current_user_id();
          $user_info = get_userdata($user_ID); 
       $lastInsertId = contentmanagerlogging('Remove Email Template',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
       
    $report_name =$_POST['emailtemplatename'];
    $settitng_key='AR_Contentmanager_Email_Template';
    $get_email_template_date = get_option($settitng_key);
    
    unset($get_email_template_date[$report_name]);
    update_option($settitng_key, $get_email_template_date);
    $report_info = get_option($settitng_key);
      
      $i=0;
     foreach ($report_info as $key=>$value){
        
              
              $lis[$i] = $key;
              $i++;
         
          
      }
      
      
    echo   json_encode($lis);
    $update_list['new_update_list_after_remove']=$lis;
    
    contentmanagerlogging_file_upload ($lastInsertId,serialize($update_list));
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
     die();
     

}else if ($_GET['contentManagerRequest'] == 'updatewelocmecontent') {
    
    require_once('../../../wp-load.php');
    
   try{ 
       $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
       $lastInsertId = contentmanagerlogging('Welcome Email Template',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
       
    $welcome_subject =$_POST['welcomeemailSubject'];
    $welcome_body =$_POST['welcomeemailBody'];
    $replaytoemailadd =$_POST['replaytoemailadd'];
    $welcomeemailfromname =$_POST['welcomeemailfromname'];
    $settitng_key='AR_Contentmanager_Email_Template_welcome';
    $sponsor_info = get_option($settitng_key);
    
    $result='';
      
    
    $sponsor_info['welcome_email_template']['welcomesubject'] = $welcome_subject;
    $sponsor_info['welcome_email_template']['fromname'] = $welcomeemailfromname;
    $sponsor_info['welcome_email_template']['replaytoemailadd'] = $replaytoemailadd;
    $sponsor_info['welcome_email_template']['welcomeboday'] = stripslashes($welcome_body);
     $sponsor_info['welcome_email_template']['BCC'] = $_POST['BCC'];
     
     //contentmanagerlogging('Welcome Email Template',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,$result);
    
    $result= update_option($settitng_key, $sponsor_info);
    contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
    
   } catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
     die();
	 
}else if ($_GET['contentManagerRequest'] == 'remove_save_report_template') {
    
    require_once('../../../wp-load.php');
    
    try{
    $savereport_name =$_POST['savereportname'];
    $report_seetingkey='AR_Contentmanager_Reports_Filter';
    $report_data = get_option($report_seetingkey);
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
      
     $lastInsertId = contentmanagerlogging('Remove Report Template',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
       
    unset($report_data[$savereport_name]);
    
    $result = update_option( $report_seetingkey, $report_data );
    
    $get_new_report_data = get_option($report_seetingkey);
    echo   json_encode($get_new_report_data);

   // $result['new_report_data']=$get_new_report_data;
    contentmanagerlogging_file_upload ($lastInsertId,serialize($get_new_report_data));
    
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    die(); 

}else if ($_GET['contentManagerRequest'] == 'addnewrole') {
    
    require_once('../../../wp-load.php');
    
    try{
    $newrolename =$_POST['rolename'];
    
     $user_ID = get_current_user_id();
     $user_info = get_userdata($user_ID);
     $lastInsertId = contentmanagerlogging('Add New Role',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
     $role_key=strtolower($newrolename);
     $remove_space_role_kye=str_replace(" ","_",$role_key);
     $result = add_role( $remove_space_role_kye, ucfirst($newrolename), array( 'read' => true, ) );
     if ( null !== $result ) {
        $msg['msg'] = 'New Level created';
     }
      else {
        
        $msg['msg'] = ucfirst($newrolename).' Level already exists.';
       }
     echo   json_encode($msg);
    contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
    
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
         return $e;
 }
    die(); 

}
else if ($_GET['contentManagerRequest'] == 'removerole') {
    
    require_once('../../../wp-load.php');
    
    try{
     
     $remove_role_name =$_POST['rolename'];
     $user_ID = get_current_user_id();
     $user_info = get_userdata($user_ID);
     $lastInsertId = contentmanagerlogging('Remove Level',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
     
     
     $result = remove_role($remove_role_name);
     
        $msg['msg'] = 'Level Removed Successfuly.';
     
     echo   json_encode($msg);
    contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
    
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
         return $e;
 }
    die(); 

}else if ($_GET['contentManagerRequest'] == 'adminsettings') {
    
    require_once('../../../wp-load.php');

    updateadmin_frontend_settings($_POST);

}else if ($_GET['contentManagerRequest'] == 'bulkimportuser') {

    require_once('../../../wp-load.php');
  try{
      
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $lastInsertId = contentmanagerlogging('Bulk Import User',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
      
   
    $file=$_FILES['file'];
    add_filter( 'upload_dir', 'wpse_183245_upload_dir' );
    $resourceurl = bulk_import_user_file($file);
    $loggin_data['fileurl']=$resourceurl;
    remove_filter( 'upload_dir', 'wpse_183245_upload_dir' );
   
    $responce="";
    if(!empty($resourceurl)){
     // echo $resourceurl;
      $filename_import = basename($resourceurl);      
      $responce  =  bulkimport_userfile($filename_import);
        
    }else{
        
         $responce =null; 
    }
    
    
    echo   json_encode($responce);
    
    
    
    
    
    
    
    contentmanagerlogging('Bulk Import User',"Admin Action",serialize($loggin_data),$user_ID,$user_info->user_email,$result);
    contentmanagerlogging_file_upload ($lastInsertId,serialize($loggin_data));   
  }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    die();
}


function updateadminemailtemplate($data_array,$email_template_name){
    
      try{
          
          $user_ID = get_current_user_id();
          $user_info = get_userdata($user_ID);
    
    $data_submit['data_array']=$data_array;
    $data_submit['template_name']=$email_template_name;
    $lastInsertId = contentmanagerlogging('Updated Report Template',"Admin Action",serialize($data_submit),$user_ID,$user_info->user_email,"pre_action_data");
       
      $settitng_key='AR_Contentmanager_Email_Template';
      $sponsor_info = get_option($settitng_key);
    
      
    
      $sponsor_info[$email_template_name]['emailsubject'] = $data_array['emailsubject'];
      $sponsor_info[$email_template_name]['emailboday'] = stripslashes($data_array['emailboday']);
      $sponsor_info[$email_template_name]['BCC'] = $data_array['BCC'];
      $sponsor_info[$email_template_name]['fromname'] = $data_array['fromname'];
   
      update_option($settitng_key, $sponsor_info);
    
      
     
      $report_info = get_option($settitng_key);
      
      $i=0;
     foreach ($report_info as $key=>$value){
        
              
              $lis[$i] = $key;
              $i++;
         
          
      }
      
      
    echo   json_encode($lis);
    $updated_list['updated_list']=$lis;
      contentmanagerlogging_file_upload ($lastInsertId,serialize($updated_list));
    //  print_r($report_info);
} catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    
    
    
}




function setpasswordcustome($password){
      
    
    
      try{
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $lastInsertId = contentmanagerlogging('Change Passowrd',"User Action",serialize($password),$user_ID,$user_info->user_email,"pre_action_data");
       
    $result = wp_set_password( $password, $user_ID );
    contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
    
      }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
}



function send_email($to,$subject,$body,$headers){
    
   $result = wp_mail($to, $subject, $body,$headers);
    return $result;
    
}





function getthereportsavalues($report_name){
    
    $settitng_key='AR_Contentmanager_Reports_Filter';
    $sponsor_info = get_option($settitng_key);
     echo   json_encode($sponsor_info[$report_name]);
    
}
function updateadminreport($data_array,$report_name){
    
      try{
          
    $new_data_array['report_name']=$report_name;
    $new_data_array['report_filter_value']=$data_array;
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $lastInsertId = contentmanagerlogging('Save Filter Report',"Admin Action",serialize($new_data_array),$user_ID,$user_info->user_email,"pre_action_data");
      
      $settitng_key='AR_Contentmanager_Reports_Filter';
      $sponsor_info = get_option($settitng_key);
    
      
    
      $sponsor_info[$report_name] = $data_array;
      update_option($settitng_key, $sponsor_info);
    
      
     
      $report_info = get_option($settitng_key);
      
      $i=0;
     foreach ($report_info as $key=>$value){
        
              
              $lis[$i] = $key;
              $i++;
         
          
      }
      
      
    echo   json_encode($lis);
    $new_list['new_updated_list']=$lis;
    contentmanagerlogging_file_upload ($lastInsertId,serialize($new_list));
    
      }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    //  print_r($report_info);
    
    
    
    
}



function plugin_settings(){
    
    $settitng_key='ContenteManager_Settings';
    $sponsor_info = get_option($settitng_key);
    echo   json_encode($sponsor_info);
    
}
// start remove sponsor resource

function remove_post_resource($post_id){
   
    
    $responce = wp_delete_post($post_id);
    return $responce;
    //print_r($responce);
    
}


// start create sponsor remove


function remove_sponsor_metas($user_id){
    //You should check nonces and user permissions at this point.
    //echo  $user_id;exit;
    

   
    $path =  dirname(__FILE__);
   $hom_path = str_replace("/wp-content/plugins/contentmanager","",$path);
   
    
    if(!function_exists('wp_delete_user')) {
          
    include($hom_path."/wp-admin/includes/user.php");
    
    }
  
    try{
    
    $all_meta_for_user = get_user_meta( $user_id );
    $all_meta_for_user['user_info'] = get_userdata( $user_id );
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $lastInsertId = contentmanagerlogging('Delete User',"Admin Action",serialize($all_meta_for_user),$user_ID,$user_info->user_email,"pre_action_data");
      
    $responce = wp_delete_user($user_id);
    contentmanagerlogging_file_upload ($lastInsertId,serialize($responce));
    //print_r($responce);
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
       die();
      
 }
  die();   
}

// start create sponsor update



function add_new_sponsor_metafields($user_id,$meta_array,$role){
    
    
    
    foreach ($meta_array as $key =>$value){
        
        update_user_meta($user_id, $key, $value);
    }
    $leavel[strtolower($role)] = 1;
  
   $result = update_user_meta($user_id, 'wp_capabilities',  $leavel);
   
   return $result;
}

// start create resourse file upload






function resource_new_post($title,$resourceurl){
    
    
 
    $my_post = array(
     'post_title' => $title,
     'post_date' => '',
     'post_content' => '',
     'post_status' => 'publish',
     'post_type' => 'avada_portfolio',
       
  );
  $post_id = wp_insert_post( $my_post );
  
    if ($post_id) {
        // insert post meta
        $result = add_post_meta($post_id, 'excerpt', $resourceurl);
        return $result;
    }
  
}

function resource_file_upload($updatevalue){
   
    if(!empty($updatevalue)){
        if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
            $upload_overrides = array( 'test_form' => false, 'mimes' => array('zip'=>'application/zip','eps'=>'application/postscript','ai' => 'application/postscript','jpg|jpeg|jpe' => 'image/jpeg','gif' => 'image/gif','png' => 'image/png','bmp' => 'image/bmp','pdf'=>'text/pdf','doc'=>'application/msword','docx'=>'application/msword','xlsx'=>'application/msexcel') );
            $movefile = wp_handle_upload( $updatevalue, $upload_overrides );
        if(!empty($movefile['file'])){
          
            return $movefile['url'];
            
        }
  }
    
}

function bulk_import_user_file($updatevalue){
   
    if(!empty($updatevalue)){
        if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
            $upload_overrides = array('test_form' => false, 'mimes' => array('xlsx'=>'application/msexcel'));
            $movefile = wp_handle_upload( $updatevalue, $upload_overrides );
        if(!empty($movefile['file'])){
          
            return $movefile['url'];
            
        }
  }
    
}


// start load report



function getReportsdatanew($report_name){
    
    
    if($report_name != "defult"){
       
    $settitng='AR_Contentmanager_Reports_Filter';
    $sponsor_report_data = get_option($settitng);
   
            
   }
    $test = 'user_meta_manager_data';
    $result = get_option($test);
    $settitng_key = 'ContenteManager_Settings';
    $sponsor_info = get_option($settitng_key);
    $sponsor_name = $sponsor_info['ContentManager']['sponsor_name'];
    //  echo '<pre>';
    // print_r($result);
    $idx = 0;
    $labelArray = null;
    $file_upload_list.='<select id="file_upload" ><option value="">Select a Download Field</option>';
    $task_drop_down = '<select style="width:400px;" name="taske_fields" id="taske_fields" ><option value="">-----------   Select a Task    --------</option>';
    foreach ($result['profile_fields'] as $profile_field_name => $profile_field_settings) {

        if ($profile_field_settings['type'] == 'color') {

            $file_upload_list.='<option value="' . $profile_field_name . '">' . $profile_field_settings['label'] . '</option>';
        }

        if (strpos($profile_field_name, "status") !== false) {


            if ($profile_field_settings['type'] == "select") {
                $task_drop_down.='<option value="' . $profile_field_settings['label'] . '">' . $profile_field_settings['label'] . '</option>';
            }
        }
    }
    $file_upload_list.='</select>';
    $task_drop_down.='</select>';

    global $wpdb;
    $tasklable = $_POST['tasklabel'];
    $taskestatus = $_POST['taskestatus'];
    $sponsorrole = $_POST['sponsorrole'];

    $test = 'user_meta_manager_data';
    $get_keys_array_result = get_option($test);

    $query = "SELECT DISTINCT user_id
    FROM " . $wpdb->usermeta;

    $query_th = "SELECT meta_key
     FROM " . $wpdb->usermeta . " WHERE  `user_id` = 1 AND  `meta_key` LIKE  'task_%'";

    $table_head = $wpdb->get_results($query_th);


    $k = 9;
    $unique_id=0;
    $showhideMYFieldsArray = array();
     $Rname = "";
     $Fname = "";
     $Lname = "";
     $Remail = "";
     $Remail = "";
     $Rtype = "";
     $Rlastlogin = "";
     $Rdate = "";
     $RRole = "";
     $shoerolefiltervalue=true;
      if($report_name != "defult"){
    
         if (array_key_exists("sponsor_name", $sponsor_report_data[$report_name])){
                $Rname = $sponsor_report_data[$report_name]['sponsor_name'];
          }
          if (array_key_exists("Email", $sponsor_report_data[$report_name])){
                
                  $Remail = $sponsor_report_data[$report_name]['Email'];
          }  
          if (array_key_exists("company_name", $sponsor_report_data[$report_name])){
                $CompanyName = $sponsor_report_data[$report_name]['company_name'];
          } 
           
          if (array_key_exists("last_login", $sponsor_report_data[$report_name])){
                $Rlastlogin = $sponsor_report_data[$report_name]['last_login'];
          } 
          if (array_key_exists("user_register_date", $sponsor_report_data[$report_name])){
                $Rdate = $sponsor_report_data[$report_name]['user_register_date'];
          }
          if (array_key_exists("Role", $sponsor_report_data[$report_name])){
                $RRole = $sponsor_report_data[$report_name]['Role'];
                $shoerolefiltervalue=false;
          }
           if (array_key_exists("first_name", $sponsor_report_data[$report_name])){
                $Fname = $sponsor_report_data[$report_name]['first_name'];
                $shoerolefiltervalue=false;
          }
           if (array_key_exists("last_name", $sponsor_report_data[$report_name])){
                $Lname = $sponsor_report_data[$report_name]['last_name'];
                $shoerolefiltervalue=false;
          }
        
   }

    $showhideMYFieldsArray['action_edit_delete'] = array('index' => 1, 'type' => 'string','unique' => true, 'hidden' => false, 'friendly'=> "Action" ,'filter'=>false);
    $showhideMYFieldsArray['company_name'] = array('index' => 2, 'type' => 'string','unique' => true, 'hidden' => false,'friendly'=> "Company Name",'filter'=>$CompanyName);
    $showhideMYFieldsArray['Role'] = array('index' => 3, 'type' => 'string','unique' => true, 'hidden' => false,'friendly'=> "Role",'filter'=>$RRole);
    $showhideMYFieldsArray['last_login'] = array('index' => 4, 'type' => 'date','unique' => true, 'hidden' => false,'friendly'=> "Last login",'filter'=>$Rlastlogin);
    
    $showhideMYFieldsArray['first_name'] = array('index' => 5, 'type' => 'string','unique' => true, 'hidden' => false, 'friendly'=> "First Name",'filter'=>$Fname);
    $showhideMYFieldsArray['last_name'] = array('index' => 6, 'type' => 'string','unique' => true, 'hidden' => false, 'friendly'=> "Last Name",'filter'=>$Lname);
    
    $showhideMYFieldsArray['sponsor_name'] = array('index' => 7, 'type' => 'string','unique' => true, 'hidden' => false, 'friendly'=> $sponsor_name." Name",'filter'=>$Rname);
    
    $showhideMYFieldsArray['Email'] = array('index' => 8, 'type' => 'string','unique' => true, 'hidden' => false,'friendly'=> "Email",'filter'=>$Remail);
    
   // uasort($get_keys_array_result['profile_fields'], "cmp2");
    foreach ($get_keys_array_result['profile_fields'] as $profile_field_name => $profile_field_settings) {
        $report_key_value = "";
        $showhidevalue = true;

        if ($report_name != "defult") {
            if (array_key_exists($profile_field_name, $sponsor_report_data[$report_name])) {

                $report_key_value = $sponsor_report_data[$report_name][$profile_field_name];
                $showhidevalue = false;
            }
        }
        if (strpos($profile_field_name, "task") !== false) {

            if ($profile_field_settings['type'] == 'datetime') {
                $showhideMYFieldsArray[$profile_field_name] = array('index' => $k, 'type' => 'date', 'unique' => true, 'hidden' => $showhidevalue,'friendly'=> $profile_field_settings['label'],'filter'=>$report_key_value);
                $k++;
            } else if ($profile_field_settings['type'] == 'color') {
                $showhideMYFieldsArray[$profile_field_name] = array('index' => $k, 'type' => 'string', 'unique' => true, 'hidden' => $showhidevalue,'friendly'=> $profile_field_settings['label'],'filter'=>$report_key_value);
                $k++;
            } else if ($profile_field_settings['type'] == 'text') {
                $showhideMYFieldsArray[$profile_field_name] = array('index' => $k, 'type' => 'string', 'unique' => true, 'hidden' => $showhidevalue,'friendly'=> $profile_field_settings['label'],'filter'=>$report_key_value);
                $k++;
            } else if ($profile_field_settings['type'] == 'textarea') {
                $showhideMYFieldsArray[$profile_field_name] = array('index' => $k, 'type' => 'string', 'unique' => true, 'hidden' => $showhidevalue,'friendly'=> $profile_field_settings['label'],'filter'=>$report_key_value);
                $k++;
            } else {
                $showhideMYFieldsArray[$profile_field_name] = array('index' => $k, 'type' => 'string', 'unique' => true, 'hidden' => $showhidevalue,'friendly'=> $profile_field_settings['label'],'filter'=>$report_key_value);
                $k++;
            }
        }
    }
        $column_name_uppercase = $showhideMYFieldsArray;//array_change_key_case($showhideMYFieldsArray, CASE_UPPER);
        $newStr = strtoupper($showhidefields);
        //print_r ($newStr);
        $base_url = "http://" . $_SERVER['SERVER_NAME'];
        $result = $wpdb->get_results($query);
        $allMetaForAllUsers = array();
        $myNewArray = array();
        $test = 'user_meta_manager_data';
        $get_keys_array_result = get_option($test);
        $zee = 0;
        $new = 0;
     foreach ($result as $aid) {


        $user_data = get_userdata($aid->user_id);
       
        
        if( $user_data->roles[0]!='administrator' ){
        $all_meta_for_user = get_user_meta($aid->user_id);
       
        if (!empty($all_meta_for_user['wp_user_login_date_time'][0])) {


            $login_date_time = date('d-M-Y H:i:s', $all_meta_for_user['wp_user_login_date_time'][0]);
            $timestamp = strtotime($login_date_time) * 1000;
        } else {
            $timestamp = "";
        }
       $company_name = $all_meta_for_user['company_name'][0];
        // echo $company_name;
        // echo '<pre>';
        // print_r($user_data);
        // exit;
       $myNewArray['action_edit_delete'] = '<p style="width:83px !important;"><a href="/edit-user/?sponsorid='.$aid->user_id.'" title="Edit Speaker Profile"><span class="icon-wrapper circle-no"><i class="fusion-li-icon fa fa-pencil-square-o" style="color:#262626;"></i></span></a><a style="margin-left: 10px;" href="/edit-sponsor-task/?sponsorid='.$aid->user_id.'" title="Speaker Tasks"><span class="icon-wrapper circle-no"><i class="fusion-li-icon fa fa-th-list" style="color:#262626;"></i></span></a><a onclick="view_profile(this)" id="'.$unique_id.'" name="viewprofile"  style="cursor: pointer;color:red;margin-left: 10px;" title="View Profile" ><span class="icon-wrapper circle-no"><i class="fusion-li-icon fa fa-eye" style="color:#262626;"></i></a><a onclick="delete_sponsor_meta(this)" id="'.$aid->user_id.'" name="delete-sponsor"  style="cursor: pointer;color:red;margin-left: 10px;" title="Remove Speaker" ><span class="icon-wrapper circle-no"><i class="fusion-li-icon fa fa-times-circle" style="color:#262626;"></i></a></p>';
	   
	   	$unique_id++;
        $myNewArray['company_name'] = $company_name;
        $myNewArray['Role'] = $user_data->roles[0];
        $myNewArray['last_login'] = $timestamp;
     
        $myNewArray['first_name'] = $user_data->first_name;
        $myNewArray['last_name'] = $user_data->last_name;
           $myNewArray['sponsor_name'] = $user_data->display_name;
        $myNewArray['Email'] = $user_data->user_email;
       
        // echo $login_date_time; 

        
        
  //uasort($get_keys_array_result['profile_fields'], "cmp2");
        foreach ($get_keys_array_result['profile_fields'] as $profile_field_name => $profile_field_settings) {
            if (strpos($profile_field_name, "task") !== false) {
                if ($profile_field_settings['type'] == 'color') {
                    $file_info = unserialize($all_meta_for_user[$profile_field_name][0]);
                    
                    if (!empty($file_info)) {
                        $myNewArray[$profile_field_name] = '<a href="'.$base_url.'/wp-content/plugins/contentmanager/download-lib.php?userid=' . $aid->user_id . '&fieldname=' . $profile_field_name . '" >Download</a>';
                    } else {
                        $myNewArray[$profile_field_name] = '';
                    }
                } else {

                    // echo 'Qasimriaz';

                   
                        //  echo 'Qasimriaz1';
                        if ($profile_field_settings['type'] == 'datetime') {
                            //$mynewdate = new DateTime($all_meta_for_user[$profile_field_name][0]);
                            if(!empty($all_meta_for_user[$profile_field_name][0])){
                            if (strpos($all_meta_for_user[$profile_field_name][0], 'AM') !== false) {

                                $datevalue = str_replace(":AM", "", $all_meta_for_user[$profile_field_name][0]);
                                 $datemy = strtotime($datevalue) * 1000;
                            } else {
                                $datevalue = str_replace(":PM", "", $all_meta_for_user[$profile_field_name][0]);
                                 $datemy = strtotime($datevalue) * 1000;
                            }}else{
                                $datemy="";
                            }
                           

                            $myNewArray[$profile_field_name] = $datemy;
                        } 
                        else if ($profile_field_settings['type'] == 'text') {
                             

                            $myNewArray[$profile_field_name] = $all_meta_for_user[$profile_field_name][0];
                        } 
                        else if ($profile_field_settings['type'] == 'textarea') {

                            $myNewArray[$profile_field_name] =  $all_meta_for_user[$profile_field_name][0];
                            //$newarray[$new]=$all_meta_for_user[$profile_field_name][0];
                            // $new++;
                        }
                        else if ($profile_field_settings['type'] == 'select') {

                            $myNewArray[$profile_field_name] =  $all_meta_for_user[$profile_field_name][0];
                          
                            if($all_meta_for_user[$profile_field_name][0] == "Pending"){
                                $myNewArray[$profile_field_name.'Cls'] =  "red";
                            }else if($all_meta_for_user[$profile_field_name][0] == "Complete"){
                                $myNewArray[$profile_field_name.'Cls'] =  "green";
                            }else{
                                $myNewArray[$profile_field_name.'Cls'] =  "blue";
                            }
                            //$newarray[$new]=$all_meta_for_user[$profile_field_name][0];
                            // $new++;
                        }  else if ($profile_field_settings['type'] == 'select-2') {
                            $myNewArray[$profile_field_name] =  $all_meta_for_user[$profile_field_name][0];
                          
                        }else {
                           

                            $myNewArray[$profile_field_name] = $all_meta_for_user[$profile_field_name][0];
                        }
                    } 
                }
            }
        
       // $row_name_uppercase = array_change_key_case($myNewArray, CASE_UPPER);
        $allMetaForAllUsers[$zee] = $myNewArray;
       // echo $zee.'<br>';
        $zee++;
    }
   
}
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $current_admin_email =$user_info->user_email;
    $oldvalues = get_option( 'ContenteManager_Settings' );
     
    $attendytype=$oldvalues['ContentManager']['attendytype_key'];
    $eventdate = $oldvalues['ContentManager']['eventdate'];
    $sitename=get_bloginfo();
    $settings['attendytype_key'] =$attendytype;
    $settings['Currentadminemail'] =$current_admin_email;
    $settings['sitename'] =$sitename;
    $settings['eventdate'] =$eventdate;
    
     
   // echo '<pre>';
   // print_r($allMetaForAllUsers);exit;
    
    
    
     echo json_encode($column_name_uppercase) . '//' . json_encode($allMetaForAllUsers) .'//'.json_encode($settings) ;
     die();
}

add_action('wp_enqueue_scripts', 'add_contentmanager_js');
function add_contentmanager_js(){
      wp_enqueue_script('safari4', plugins_url('/js/my_task_update.js', __FILE__), array('jquery'));
    
     wp_enqueue_script( 'jquery.alerts', plugins_url() . '/contentmanager/js/jquery.alerts.js', array(), '1.1.0', true );
     wp_enqueue_script( 'boot-date-picker', plugins_url() . '/contentmanager/js/bootstrap-datepicker.js', array(), '1.2.0', true );
     wp_enqueue_script( 'jquerydatatable', plugins_url() . '/contentmanager/js/jquery.dataTables.js', array(), '1.2.0', true );
     wp_enqueue_script( 'shCore', plugins_url() . '/contentmanager/js/shCore.js', array(), '1.2.0', true );
     wp_enqueue_script( 'demo', plugins_url() . '/contentmanager/js/demo.js', array(), '1.2.0', true );
     wp_enqueue_script( 'bootstrap.min', plugins_url() . '/contentmanager/js/bootstrap.min.js', array(), '1.2.0', true );
    
     wp_enqueue_script('safari1', plugins_url('/js/modernizr.custom.js', __FILE__), array('jquery'));
     wp_enqueue_script('safari2', plugins_url('/js/classie.js', __FILE__), array('jquery'));
     wp_enqueue_script('safari3', plugins_url('/js/progressButton.js', __FILE__), array('jquery'));
   
    // wp_enqueue_script('bulk-email', plugins_url('/js/bulk-email.js', __FILE__), array('jquery'));
     wp_enqueue_script('sweetalert', plugins_url('/contentmanager/cmtemplate/js/lib/bootstrap-sweetalert/sweetalert.min.js'), array('jquery'));
     wp_enqueue_script('password_strength_cal', plugins_url('/js/passwordstrength.js', __FILE__), array('jquery'));
      //wp_enqueue_script('rolejs', plugins_url('/js/role.js', __FILE__), array('jquery'));
     
   
}

add_action('wp_enqueue_scripts', 'my_contentmanager_style');

function my_contentmanager_style() {
    wp_enqueue_style('my-mincss', plugins_url() .'/contentmanager/css/bootstrap.min.css');
    wp_enqueue_style('my-sweetalert', plugins_url() .'/contentmanager/cmtemplate/css/lib/bootstrap-sweetalert/sweetalert.css');
    wp_enqueue_style('my-datepicker', plugins_url().'/contentmanager/css/datepicker.css');
    wp_enqueue_style('jquery.dataTables', plugins_url().'/contentmanager/css/jquery.dataTables.css');
    wp_enqueue_style('shCore', plugins_url().'/contentmanager/css/shCore.css');
  
    wp_enqueue_style('my-datatable-tools', plugins_url().'/contentmanager/css/dataTables.tableTools.css');
   // wp_enqueue_style('cleditor-css', plugins_url() .'/contentmanager/css/jquery.cleditor.css');
   // wp_enqueue_style('contentmanager-css', plugins_url() .'/contentmanager/css/forntend.css');
    wp_enqueue_style('my-admin-theme1', plugins_url('css/component.css', __FILE__));
    wp_enqueue_style('my-admin-theme', plugins_url('css/normalize.css', __FILE__));
  
   
}
function my_plugin_activate() {

  $create_pages_list[0]['title'] = 'New User';
  $create_pages_list[0]['name'] = 'create-user';
  $create_pages_list[0]['temp'] = 'temp/addsponsor-template.php';
  
  $create_pages_list[1]['title'] = 'Create Resources';
  $create_pages_list[1]['name'] = 'create-resource';
  $create_pages_list[1]['temp'] = 'temp/create-resource-template.php';
  
 
  
  
  $create_pages_list[2]['title'] = 'User Report';
  $create_pages_list[2]['name'] = 'user-report';
  $create_pages_list[2]['temp'] = 'temp/sponsor-reports-template.php';
  
  
   $create_pages_list[3]['title'] = 'Edit User';
  $create_pages_list[3]['name'] = 'edit-user';
  $create_pages_list[3]['temp'] = 'temp/edit_sponsor-template.php' ;
  
  
   $create_pages_list[4]['title'] = 'Edit User Task';
  $create_pages_list[4]['name'] = 'edit-task';
  $create_pages_list[4]['temp'] = 'temp/editponsor-task-update-template.php';
  
  $create_pages_list[5]['title'] = 'All Resources';
  $create_pages_list[5]['name'] = 'all-resources';
  $create_pages_list[5]['temp'] = 'temp/view_resource-template.php';
  
  $create_pages_list[6]['title'] = 'Create New Task';
  $create_pages_list[6]['name'] = 'create-task';
  $create_pages_list[6]['temp'] = 'temp/createponsor-task-template.php';
  
  $create_pages_list[7]['title'] = 'Admin Change Password';
  $create_pages_list[7]['name'] = 'admin-change-password';
  $create_pages_list[7]['temp'] = 'temp/change_password_template.php';
  
  
    
  $create_pages_list[8]['title'] = 'Welcome Email Content';
  $create_pages_list[8]['name'] = 'welcome-email';
  $create_pages_list[8]['temp'] = 'temp/welcome_email_template.php';
  
    
  $create_pages_list[9]['title'] = 'Manage users Level';
  $create_pages_list[9]['name'] = 'add-new-level';
  $create_pages_list[9]['temp'] = 'temp/create-role-template.php';
  
  $create_pages_list[10]['title'] = 'Add Content Manager';
  $create_pages_list[10]['name'] = 'add-content-manager-user';
  $create_pages_list[10]['temp'] = 'temp/addcontentmanager-template.php';
  
  $create_pages_list[11]['title'] = 'Content Editor';
  $create_pages_list[11]['name'] = 'content-editor';
  $create_pages_list[11]['temp'] = 'temp/edit_content_page.php';
  
  $create_pages_list[12]['title'] = 'Dashboard';
  $create_pages_list[12]['name'] = 'dashboard';
  $create_pages_list[12]['temp'] = 'temp/admin_dashboard.php';
  
  $create_pages_list[13]['title'] = 'Bulk Download';
  $create_pages_list[13]['name'] = 'bulk-download-files';
  $create_pages_list[13]['temp'] = 'temp/bulk_download_task_files_template.php';
  
  $create_pages_list[14]['title'] = 'User Change Password';
  $create_pages_list[14]['name'] = 'change-password';
  $create_pages_list[14]['temp'] = 'temp/user_change_password_template.php';
  
  $create_pages_list[15]['title'] = 'Admin Settings';
  $create_pages_list[15]['name'] = 'admin-settings';
  $create_pages_list[15]['temp'] = 'temp/settings_templates.php';
  
  
  
  foreach($create_pages_list as $key=>$value){
      
     
      $page_path= $create_pages_list[$key]['name'];
      $page = get_page_by_path($page_path);
       if(!$page){
        
        $my_post = array(
            'post_title' => wp_strip_all_tags($create_pages_list[$key]['title']),
            
            'post_status' => 'publish',
            'post_author' => get_current_user_id(),
            'post_type' => 'page',
            'post_name'=>$page_path
           
        );

// Insert the post into the database
       $returnpage_ID = wp_insert_post($my_post);
       update_post_meta( $returnpage_ID, '_wp_page_template', $create_pages_list[$key]['temp'] );
      
        
    } 
      
  }

    $settings_array['ContentManager']['sponsor_name']='User';
    $settings_array['ContentManager']['attendyTypeKey']='Role';
    
    $task_input_type[0]['lable'] = 'None';
    $task_input_type[0]['type'] = 'none';
    $task_input_type[1]['lable'] = 'Text';
    $task_input_type[1]['type'] = 'text';
    $task_input_type[2]['lable'] = 'Link';
    $task_input_type[2]['type'] = 'link';
    $task_input_type[3]['lable'] = 'Date';
    $task_input_type[3]['type'] = 'date';
    $task_input_type[4]['lable'] = 'URL';
    $task_input_type[4]['type'] = 'url';
    $task_input_type[5]['lable'] = 'Email';
    $task_input_type[5]['type'] = 'email';
    $task_input_type[6]['lable'] = 'Drop Down';
    $task_input_type[6]['type'] = 'select-2';
    $task_input_type[7]['lable'] = 'Number';
    $task_input_type[7]['type'] = 'number';
    $task_input_type[8]['lable'] = 'File Upload';
    $task_input_type[8]['type'] = 'color';
    $task_input_type[9]['lable'] = 'Text Area';
    $task_input_type[9]['type'] = 'textarea';
    $task_input_type[10]['lable'] = 'Coming soon';
    $task_input_type[10]['type'] = 'comingsoon';
    
    
    
    $settings_array['ContentManager']['taskmanager']['input_type']=$task_input_type;
    update_option( 'ContenteManager_Settings', $settings_array);
    
   
$table_name ="contentmanager_logging";

                     
                     global $wpdb;

$charset_collate = $wpdb->get_charset_collate();

$sql = "CREATE TABLE contentmanager_log (
 id bigint(20) NOT NULL AUTO_INCREMENT,
 action_name varchar(60) NOT NULL,
 action_type varchar(60) NOT NULL,
 pre_action_data longtext NOT NULL,
 user_email varchar(60) NOT NULL,
 user_id varchar(60) NOT NULL,
 result longtext NOT NULL,
 action_time datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (id)
) ENGINE=MyISAM;";














require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
  
    
  

}
register_activation_hook( __FILE__, 'my_plugin_activate' );

add_action( 'init', 'add_contentmanager_settings' );

function add_contentmanager_settings() {
    
    wp_register_script('adminjs', plugins_url('js/admin-cmanager.js', __FILE__), array('jquery'));
    wp_enqueue_script('adminjs');
    //$settings_array['ContentManager']['sponsor-name']='Exhibitor';
    //update_option( 'ContenteManager_Settings', $settings_array);
    
}
function register_contentmanger_menu() {
    //add_menu_page('Exclude Sponsor Meta Fields', 'Content Manager Settings', 'manage_options', 'cmanager-settings', 'excludes_sponsor_meta');
    add_menu_page(__('exclude-sponsor-meta-fields'), __('Content Manager Settings'), 'edit_themes', 'excludes_sponsor_meta', 'excludes_sponsor_meta', '', 7); 
 
}
function register_contentmanager_sub_menu() {
   // add_submenu_page('cmanager-settings', 'Exclude Sponsor Meta Fields', 'Exclude Sponsor Meta Fields', 'manage_options', 'excludes-sponsor-meta', 'excludes_sponsor_meta');
    add_submenu_page('my_new_menu', __('My SubMenu Page'), __('My SubMenu'), 'edit_themes', 'my_new_submenu', 'my_submenu_render');
    add_submenu_page('my_new_menu', __('Manage Menu Page'), __('Manage New Menu'), 'edit_themes', 'my_new_menu', 'my_menu_render');
    //add_submenu_page_3 ... and so on
}
add_action('admin_menu', 'register_contentmanger_menu');
add_action('wp_ajax_give_update_content_settings', 'updatecmanagersettings');
//add_action('admin_menu', 'register_contentmanager_sub_menu');



function updatecmanagersettings($object_data){
    
   try{
    
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);     
    $lastInsertId = contentmanagerlogging('Update Contentmanager Settings',"Admin Action",serialize($object_data),$user_ID,$user_info->user_email,"pre_action_data");
      
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $sponsor_name=$oldvalues['ContentManager']['sponsor_name'];
    $values_create=$object_data['excludemetakeyscreate'];
    $sponsor_name=$object_data['sponsorname'];
    $attendytypeKey=$object_data['attendyTypeKey'];
    $eventdate = $object_data['eventdate'];
    $formemail = $object_data['formemail'];
    $mandrill = $object_data['mandrill'];
    $infocontent = $object_data['infocontent'];
    $addresspoints = $object_data['addresspoints'];
    
    $values_edit=$object_data['excludemetakeysedit'];
    $remove_spaces_create = preg_replace('/\s+/', '', $values_create);
    $remove_spaces_edit = preg_replace('/\s+/', '', $values_edit);
    $meta_create = explode(",", $remove_spaces_create);
    $meta_edit = explode(",", $remove_spaces_edit);
   
    foreach ($meta_create as $metas=>$keys){
        
       $oldvalues['ContentManager']['exclude_sponsor_meta_create'][$metas]= $keys;
      
    }
     foreach ($meta_edit as $metas=>$keys){
        
       $oldvalues['ContentManager']['exclude_sponsor_meta_edit'][$metas]= $keys;
      
    }


    
    $oldvalues['ContentManager']['sponsor_name']=$sponsor_name;
    $oldvalues['ContentManager']['attendytype_key']=$attendytypeKey;
    $oldvalues['ContentManager']['eventdate']=$eventdate;
    $oldvalues['ContentManager']['formemail']=$formemail;
    $oldvalues['ContentManager']['mandrill']=$mandrill;
    $oldvalues['ContentManager']['infocontent']=$infocontent;
    $oldvalues['ContentManager']['addresspoints']=$addresspoints;
    
    $result=update_option('ContenteManager_Settings', $oldvalues);
    
    contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
   }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    die();
}

function updateadmin_frontend_settings($object_data){
    
   try{
    
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);     
    $lastInsertId = contentmanagerlogging('Update Contentmanager Settings Front End',"Admin Action",serialize($object_data),$user_ID,$user_info->user_email,"pre_action_data");
      
    
    $eventdate = $object_data['eventdate'];
   // $formemail = $object_data['formemail'];
   // $mandrill = $object_data['mandrill'];
  //  $infocontent = $object_data['infocontent'];
  //  $addresspoints = $object_data['addresspoints'];
    
    $oldvalues = get_option( 'ContenteManager_Settings' );
    
    $oldvalues['ContentManager']['eventdate']=$eventdate;
   // $oldvalues['ContentManager']['formemail']=$formemail;
   // $oldvalues['ContentManager']['mandrill']=$mandrill;
  //  $oldvalues['ContentManager']['infocontent']=$infocontent;
  //  $oldvalues['ContentManager']['addresspoints']=$addresspoints;
    
    $result=update_option('ContenteManager_Settings', $oldvalues);
    
    contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
   }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    die();
}
function excludes_sponsor_meta(){
    
    
     $oldvalues = get_option( 'ContenteManager_Settings' );
 
     $sponsor_name=$oldvalues['ContentManager']['sponsor_name'];
     $attendytype=$oldvalues['ContentManager']['attendytype_key'];
     $eventdate = $oldvalues['ContentManager']['eventdate'];
     $formemail = $oldvalues['ContentManager']['formemail'];
     $mandrill = $oldvalues['ContentManager']['mandrill'];
     $infocontent = $oldvalues['ContentManager']['infocontent'];
     $addresspoints = $oldvalues['ContentManager']['addresspoints'];
      
     //echo'<pre>';
    // print_r($oldvalues);
     if(!empty($oldvalues['ContentManager']['exclude_sponsor_meta_create'])){
         foreach($oldvalues['ContentManager']['exclude_sponsor_meta_create'] as $keys => $key){
             $string_value.= $key.',';
         }
     }
     if(!empty($oldvalues['ContentManager']['exclude_sponsor_meta_edit'])){
         foreach($oldvalues['ContentManager']['exclude_sponsor_meta_edit'] as $keys => $key){
             $string_value_edit.= $key.',';
         }
     }
     $bodayContent;
     $header = '<p id="successmsg" style="display:none;background-color: #00F732;padding: 11px;margin-top: 20px;width: 300px;font-size: 18px;"></p><h4></h4>';
     $bodayContent.=$header;
     
     $maincontent='<table style="">
      
       <tr>
       <td><h4>Exclude Meta Fields For Create Sponsor Screen</h4></td>
        <td><textarea name="listofmeta"  id="listofmeta" rows="5" cols="40">'.rtrim($string_value, ",").'</textarea><p>Add meta keys with spreated comma</p></td>
       </tr>
       <tr>
            <td><h4>Exclude Meta Fields For Edit Sponsor Screen</h4></td>
            
       
        <td><textarea name="listofmetaedit"  id="listofmetaedit" rows="5" cols="40">'.rtrim($string_value_edit, ",").'</textarea><p>Add meta keys with spreated comma</p></td>
       </tr>
       <tr><td><h4>Add Sponsor Name</h4></td>
       
        <td><input type="text" name="spnsorname"  id="spnsorname" value='.$sponsor_name.'></td>
       </tr>
  <tr><td><h4>Add Key For Attendee Type (Graph)</h4></td>
 
        <td><input type="text" name="attendytype"  id="attendytype" value='.$attendytype.'></td>
       </tr>
       
<tr><td><h4>Event Date</h4></td>
 
        <td><input type="date" name="eventdate"  id="eventdate" value='.$eventdate.'></td>
       </tr>
       <tr><td><h4>Form Email Address</h4></td>
 
        <td><input type="text" name="formemail"  id="formemail" value='.$formemail.'></td>
       </tr>
        <tr><td><h4>Mandrill API key</h4></td>
 
        <td><input type="text" name="mandrill"  id="mandrill" value='.$mandrill.'></td>
       </tr>
        <tr><td><h4>Infobox Content</h4></td>
 
        <td><textarea type="text" name="infocontent"  id="infocontent" >'.$infocontent.'</textarea></td>
       </tr>
        <tr><td><h4>Event Address</h4></td>

        <td>
        <input type="text" name="addresspoints"  id="addresspoints" value='.$addresspoints.'>
<p>Add your address to the location you wish to show on the map.</br>If the location is off, please try to use long/lat coordinates with latlng=. ex: latlng=12.381068,-1.492711. </p>
         
</td>
       </tr>
       <tr>
       <td style="text-align: center;"><a style="margin-top: 20px;
" onclick="updatecontentsettings()" class="button">Save</a></td>
     </tr>
     </table>';
     
     $bodayContent.=$maincontent;
     
     
     echo $bodayContent;
}

class ContentManager {

		/**
         * A Unique Identifier
         */
		 protected $plugin_slug;

        /**
         * A reference to an instance of this class.
         */
        private static $instance;

        /**
         * The array of templates that this plugin tracks.
         */
        protected $templates;


        /**
         * Returns an instance of this class. 
         */
        public static function get_instance() {

                if( null == self::$instance ) {
                        self::$instance = new ContentManager();
                } 

                return self::$instance;

        } 

        /**
         * Initializes the plugin by setting filters and administration functions.
         */
        private function __construct() {

                $this->templates = array();


                // Add a filter to the attributes metabox to inject template into the cache.
                add_filter(
					'page_attributes_dropdown_pages_args',
					 array( $this, 'register_project_templates' ) 
				);


                // Add a filter to the save post to inject out template into the page cache
                add_filter(
					'wp_insert_post_data', 
					array( $this, 'register_project_templates' ) 
				);


                // Add a filter to the template include to determine if the page has our 
				// template assigned and return it's path
                add_filter(
					'template_include', 
					array( $this, 'view_project_template') 
				);


                // Add your templates to this array.
                $this->templates = array(
                        'temp/addsponsor-template.php'     => 'Add new sponsor',
                        'temp/create-resource-template.php'     => 'Create resource',
                        'temp/sponsor-reports-template.php'     => 'Sponsor Reports',
                        'temp/edit_sponsor-template.php'     => 'Edit Sponsor', 
                        'temp/edit_sponsor-task-template.php'     => 'Edit Sponsor Task',
                        'temp/view_resource-template.php'     => 'Resource list view',
                        'temp/createponsor-task-template.php'     => 'Create Sponsor Task',
                        'temp/editponsor-task-update-template.php' =>  'Edit Sponsor Task Update',
                        'temp/change_password_template.php' =>  'Change Password',
                        'temp/welcome_email_template.php' =>  'Welcome Email',
                        'temp/create-role-template.php' =>  'Create New Role',
                        'temp/addcontentmanager-template.php' =>  'Add Content Manager',
			'temp/edit_content_page.php'     => 'Edit Content',
                        'temp/admin_dashboard.php'     => 'Dashboard',
                         'temp/bulk_download_task_files_template.php'     => 'Download Bulk Email',
                   
                     'temp/user_change_password_template.php'     => 'User Change Password',
                   'temp/settings-template.php'     => 'Admin Settings',
                    'temp/bulkuser_import.php'     => 'Bulk Import Users',
                    
                );
				
        } 


        /**
         * Adds our template to the pages cache in order to trick WordPress
         * into thinking the template file exists where it doens't really exist.
         *
         */

        public function register_project_templates( $atts ) {

                // Create the key used for the themes cache
                $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

                // Retrieve the cache list. 
				// If it doesn't exist, or it's empty prepare an array
				$templates = wp_get_theme()->get_page_templates();
                if ( empty( $templates ) ) {
                        $templates = array();
                } 

                // New cache, therefore remove the old one
                wp_cache_delete( $cache_key , 'themes');

                // Now add our template to the list of templates by merging our templates
                // with the existing templates array from the cache.
                $templates = array_merge( $templates, $this->templates );

                // Add the modified cache to allow WordPress to pick it up for listing
                // available templates
                wp_cache_add( $cache_key, $templates, 'themes', 1800 );

                return $atts;

        } 

        /**
         * Checks if the template is assigned to the page
         */
        public function view_project_template( $template ) {

                global $post;

                if (!isset($this->templates[get_post_meta( 
					$post->ID, '_wp_page_template', true 
				)] ) ) {
					
                        return $template;
						
                } 

                $file = plugin_dir_path(__FILE__). get_post_meta( 
					$post->ID, '_wp_page_template', true 
				);
				
                // Just to be safe, we check if the file exist first
                if( file_exists( $file ) ) {
                        return $file;
                } 
				else { echo $file; }

                return $template;

        } 


} 

add_action( 'plugins_loaded', array( 'ContentManager', 'get_instance' ) );


// [showuserfield field='COMPANY_NAME']
function showuserfield_func($atts) {
    $fieldname = $atts['field'];
    $postid = get_current_user_id();
    $value = get_user_meta($postid, $fieldname);
   
    return $value[0];
   
}

add_shortcode('showuserfield', 'showuserfield_func');

// [sponsor_roles]
function sponsor_roles_fun() {
    global $wp_roles;
    global $current_user, $wpdb;
    $all_roles = $wp_roles->roles;
    $editable_roles = apply_filters('editable_roles', $all_roles);
    
    $role = $wpdb->prefix . 'capabilities';
    $current_user->role = array_keys($current_user->$role);
    $role = $editable_roles[$current_user->role[0]]['name'];
    return $role;
}

add_shortcode('sponsor_roles', 'sponsor_roles_fun');


function mycustomelogin($user_login, $user) {
    
    
   
    global $wpdb;
   
   $postid = $user->ID;
    $t=time();
    $result = update_user_meta($postid , 'wp_user_login_date_time',  $t);
   
    $query = "INSERT INTO contentmanager_log (action_name, action_type,pre_action_data,user_id,user_email,result) VALUES (%s,%s,%s,%s,%s,%s)";
$wpdb->query($wpdb->prepare($query, "Login", "User Action",serialize($user),$user->ID,$user->user_email,$result));

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
   
    $query = "INSERT INTO contentmanager_log (action_name, action_type,pre_action_data,user_id,user_email,result) VALUES (%s,%s,%s,%s,%s,%s)";
$wpdb->query($wpdb->prepare($query, "Login", "User Action",serialize($current_user),$postid,$current_user->user_email,$result));


    endif;
}

add_action( 'wp_login_failed', 'my_front_end_login_fail' );  // hook failed login

function my_front_end_login_fail( $username ) {
   $referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
 

// if there's a valid referrer, and it's not the default log-in screen
   if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
       
       
    global $wpdb;
    $query = "INSERT INTO contentmanager_log (action_name, action_type,pre_action_data,user_id,user_email,result) VALUES (%s,%s,%s,%s,%s,%s)";
    $wpdb->query($wpdb->prepare($query, "Login Failed", "User Action",serialize($username),'','',''));

      
wp_redirect( $referrer . '?login=failed' );  // let's append some information (login=failed) to the URL for the theme to use
      exit;
   }
}


function afterlogoutredirect() {
    // your code
  
     wp_redirect( home_url() );
     exit();
}
add_action('wp_logout', 'afterlogoutredirect');

// [customelogout ]
function customelogout(  ) {
      global $wpdb;
    $current_user = wp_get_current_user();
    $postid = get_current_user_id();
    $result="1";
     $query = "INSERT INTO contentmanager_log (action_name, action_type,pre_action_data,user_id,user_email,result) VALUES (%s,%s,%s,%s,%s,%s)";
$wpdb->query($wpdb->prepare($query, "Logout", "User Action",serialize($current_user),$postid,$current_user->user_email,$result));
    
    wp_logout();
}
add_shortcode( 'customelogout', 'customelogout' );

function contentmanagerlogging($acction_name,$action_type,$pre_action_data,$user_id,$email,$result){

    
require_once('../../../wp-load.php');
    
global $wpdb;
    

$query = "INSERT INTO contentmanager_log (action_name, action_type,pre_action_data,user_id,user_email,result) VALUES (%s,%s,%s,%s,%s,%s)";
$wpdb->query($wpdb->prepare($query, $acction_name, $action_type,$pre_action_data,$user_id,$email,$result));
$lastInsertId = $wpdb->insert_id;
return $lastInsertId;
}
function contentmanagerlogging_file_upload($lastInsertId,$result){

    
require_once('../../../wp-load.php');
    
global $wpdb;
 $wpdb->update( 
    'contentmanager_log', 
    array( 
        'result' => $result  // string
       
    ), 
    array( 'id' => $lastInsertId )
);

//$query = "UPDATE `contentmanager_log` SET `result`=".$result." WHERE 'id'=".$lastInsertId;
//echo $query; exit;
//$wpdb->query($wpdb->prepare($query, $acction_name, $action_type,$pre_action_data,$user_id,$email,$result));


}





function custome_email_send($user_id){
        global $wpdb, $wp_hasher;
        $user = get_userdata($user_id);
        $user_login = stripslashes($user->user_login);
        $user_email = stripslashes($user->user_email);
        
        $plaintext_pass=wp_generate_password( 8, false, false );
        wp_set_password( $plaintext_pass, $user_id );
        
        $settitng_key='AR_Contentmanager_Email_Template_welcome';
        $sponsor_info = get_option($settitng_key);
        $site_url = get_option('siteurl' );
        $data=  date("Y-m-d");
        $time=  date('H:i:s');
        $site_title=get_option( 'blogname' );
        $oldvalues = get_option( 'ContenteManager_Settings' );
        $formemail = $oldvalues['ContentManager']['formemail'];
        if(empty($formemail)){
            $formemail = 'noreply@convospark.com';
        
        }
        
        $subject = $sponsor_info['welcome_email_template']['welcomesubject'];
		$bcc =  $sponsor_info['welcome_email_template']['BCC'];
		
       // $headers = 'From: '.$sponsor_info['welcome_email_template']['fromname'].' <noreply@convospark.com>' . "\r\n";
       // $headers .= 'Reply-To: '.$sponsor_info['welcome_email_template']['replaytoemailadd'];
        
         $headers []= 'From: '.$sponsor_info['welcome_email_template']['fromname'].' <'.$formemail.'>' . "\r\n";
         $headers []= 'Reply-To: '.$sponsor_info['welcome_email_template']['replaytoemailadd'];
       
        $headers []= 'Bcc:'.$bcc;
        
        
      //  $message  = sprintf(__('New user registration on your blog %s:'), get_option('blogname')) . "\r\n\r\n";
      //  $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
      //  $message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";

      //  @wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), get_option('blogname')), $message);
       // Generate something random for a password reset key.
	$key = wp_generate_password( 20, false );

	/** This action is documented in wp-login.php */
	do_action( 'retrieve_password_key', $user->user_login, $key );

	// Now insert the key, hashed, into the DB.
	if ( empty( $wp_hasher ) ) {
		require_once ABSPATH . WPINC . '/class-phpass.php';
		$wp_hasher = new PasswordHash( 8, true );
	}
	$hashed = time() . ':' . $wp_hasher->HashPassword( $key );
	$wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user->user_login ) );
        $create_rest_password_link .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . ">\r\n\r\n";
        
        
         $message=$sponsor_info['welcome_email_template']['welcomeboday'];
         
         $subject_body = $subject ; 
         $body_message =stripslashes ($message) ;
         
         $field_key_string = getInbetweenStrings('{', '}', $body_message);
          foreach($field_key_string as $index=>$keyvalue){
             
             if($keyvalue == 'user_login' ||$keyvalue == 'date' || $keyvalue == 'issues_passes' || $keyvalue == 'create_password_url' || $keyvalue == 'time'|| $keyvalue == 'user_pass'|| $keyvalue == 'site_url'|| $keyvalue == 'site_title'){
                 
             }else{
                 
                 $get_meta_value = get_user_meta_merger_field_value($user_id,$keyvalue);
                 $body_message = str_replace('{'.$keyvalue.'}', $get_meta_value, $body_message);
                 $subject_body = str_replace('{'.$keyvalue.'}', $get_meta_value, $subject_body);
             }
             
         }
         
            $body_message = str_replace('{issues_passes}', $pass_code_array_list, $body_message);
       //  $body_message = str_replace('{user_email]', $user_email, $body_message);
         $body_message = str_replace('{user_login}', $user_email, $body_message);
        // $body_message = str_replace('[first_name]', $user->first_name,$body_message );
        // $body_message = str_replace('[last_name]', $user->last_name,$body_message );
        // $body_message = str_replace('[agency]', $account_name,$body_message );
         $body_message = str_replace('{user_pass}', $plaintext_pass, $body_message);
         $body_message = str_replace('{date}', $data, $body_message);
         $body_message = str_replace('{time}', $time, $body_message);
         $body_message = str_replace('{site_url}', $site_url, $body_message);
         $body_message = str_replace('{site_title}', $site_title, $body_message);
         $body_message = str_replace('{create_password_url}', $create_rest_password_link, $body_message);
         //$body_message = str_replace('[total_amount]', '$'.$get_amount.'.00', $body_message);
         //$body_message = str_replace('[total_num_reg_in_group]', $total_number_in_group, $body_message);
         //$body_message = str_replace('[event_name]', $event_name, $body_message);
         //$body_message = str_replace('[reference_num]', $reference_num, $body_message);
         
         
        // $subject_body = str_replace('[user_email]', $user_email, $subject_body);
         $subject_body = str_replace('{user_login}', $user_login, $subject_body);
        // $subject_body = str_replace('[first_name]', $user->first_name,$subject_body );
        // $subject_body = str_replace('[last_name]', $user->last_name,$subject_body );
         $subject_body = str_replace('{user_pass}', $plaintext_pass, $subject_body);
         $subject_body = str_replace('{date}', $data, $subject_body);
         $subject_body = str_replace('{time}', $time, $subject_body);
         $subject_body = str_replace('{site_url}', $site_url, $subject_body);
         $subject_body = str_replace('{site_title}', $site_title, $subject_body);
          
         
         add_filter( 'wp_mail_content_type', 'set_html_content_type_utf8' );
         wp_mail($user_email, $subject_body, $body_message,$headers);
         remove_filter( 'wp_mail_content_type', 'set_html_content_type_utf8' );
      
    
}


function set_html_content_type_utf8() {
return 'test/html';
}



function getInbetweenStrings($start, $end, $str){
    $matches = array();
    $regex = "/$start([a-zA-Z0-9_]*)$end/";
    preg_match_all($regex, $str, $matches);
    return $matches[1];
}

function get_user_meta_merger_field_value($userid,$key){
    
    
      $value = get_user_meta($userid, $key, true);
      
      return $value;
    
    
}
 function cmp($a, $b) {
    if ($a == $b) return 0;
      
    return (strtotime($a) < strtotime($b))? -1 : 1;
}

function gettaskduesoon(){
 
   
    $test = 'user_meta_manager_data';
    $result = get_option($test);
   
    foreach($result['profile_fields'] as $key=>$value){
        if (strpos($key, "task") !== false) { 
         if (strpos($value['label'], 'Status') !== false || strpos($value['label'], 'Date-Time') !== false) {
            
        }else{
             $arrDates[] = array($value['label']=>$value['attrs']);
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
    $html_task_due_soon .= '<tr><td>'.$index.'</td><td nowrap align="center"><span class="semibold">'.$taskdate.'</span></td></tr>';
    $duetaskcount++;
    }                  
                                               
    if($duetaskcount == 5){
        break;
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
    
 
  
   
  //  echo '<pre>';
 //   print_r($page_data);exit;
        
  
   // print_r($responce);exit;
    
    $fieldname = $atts['key'];
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $key_data_return=$oldvalues['ContentManager'][$fieldname];

    return $key_data_return;
   
}

add_shortcode('contentmanagersettings', 'settings_key_data');


function bulkimport_userfile($fileurl){
    
   
 require_once 'Classes/PHPExcel.php';
    
    $tempname = 'import/'.$fileurl;
 

            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            $objReader->setReadDataOnly(true);

            $objPHPExcel = $objReader->load($tempname);
            $objWorksheet = $objPHPExcel->getActiveSheet();

            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();

            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

          //  echo $objWorksheet->getCellByColumnAndRow(0, 1)->getValue();
          //  exit;

       
    
      
    for ($row = 2; $row <= $highestRow; ++$row) {
     
        $data_field_array= array();
        
        $email = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
        $firstname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
        $lastname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
        $role = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
        $username =$email;
        $status = checkimportrowstatus($username,$email,$firstname,$lastname,$role);
        
        
       
        if(empty($email)){
           $email="";
       }
       // $message[$row]['username'] = $username;
        $message[$row]['email'] = $email;
        
       
        if($status == 'clear'){
        
            $statusresponce = importbulkuseradd(str_replace("+","",$username),$email,$firstname,$lastname,$role);
            $message[$row]['status']=$statusresponce['msg'];
            $message[$row]['created_id']=$statusresponce['created_id'];
            
          //  echo '<pre>';
            //print_r($message);exit;
            $user_pass=$statusresponce['userpass'];
            
            
          if($message[$row]['status'] == 'User created successfully.' ){
            $data_field_array[] = array('name'=>'email','content'=>$email);
            $data_field_array[] = array('name'=>'user_login','content'=>$username);
            $data_field_array[] = array('name'=>'user_pass','content'=>$user_pass);
            $data_field_array[] = array('name'=>'first_name','content'=>$firstname);
            $data_field_array[] = array('name'=>'last_name','content'=>$lastname);
            $to_message_array[]=array('email'=>$email,'name'=>$firstname,'type'=>'to');
            $user_data_array[] =array(
                'rcpt'=>$email,
                'vars'=>$data_field_array
            );
            
            }
            
        }else{
            
            $message[$row]['status'] = $status;
            $message[$row]['created_id']='';
        } 
        
     
    }
   
   
  
    
   $welcomeemail_status = send_bulk_import_welcome_email($to_message_array,$user_data_array);        
    
   return $message;
}


function wpse_183245_upload_dir( $dirs ) {
    //echo '<pre>';
   // print_r($dirs);exit;
    
    
    $dirs['subdir'] = '/import';
    $dirs['path'] = dirname(__FILE__).'/import';
    $dirs['url'] =  get_site_url().'/wp-content/plugins/contentmanager/import';
    
    
    return $dirs; 
}

function importbulkuseradd($username,$email,$firstname,$lastname,$role){
    
    require_once('../../../wp-load.php');
    
    $user_id = username_exists($username);
   
    
   
        if (!$user_id and email_exists($email) == false) {
        
            $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
            $user_id = register_new_user( $username, $email );//wp_create_user($username, $random_password, $email);
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
              $role = str_replace(' ','_',strtolower($role));
              add_new_sponsor_metafields($user_id,$meta_array,$role);
              $plaintext_pass=wp_generate_password( 8, false, false );
              wp_set_password( $plaintext_pass, $user_id );
              $status['userpass'] = $plaintext_pass;
              
              
            }
            
            
            
        } else {
        
           $status['msg'] = 'A user with this email already exists. User not created.';
           $status['created_id'] ='';
        
       }
       return $status;
}


function checkimportrowstatus($username,$email,$firstname,$lastname,$role){
    global $wp_roles;
     
    $all_roles = $wp_roles->get_names();
    
  
    
    
    if(!empty($username)&&!empty($email)&&!empty($firstname)&&!empty($lastname)&&!empty($role)){
        $role = ucfirst($role);
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

function send_bulk_import_welcome_email($to_message_array,$user_data_array){
    
    
    
  
    
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
        
		
      
        
        
       
    $subject = $sponsor_info['welcome_email_template']['welcomesubject'];
    $body=stripslashes ($sponsor_info['welcome_email_template']['welcomeboday']);
    
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $replay_to = $sponsor_info['welcome_email_template']['replaytoemailadd'];
    $formname =$sponsor_info['welcome_email_template']['fromname'];
    
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $formemail = $oldvalues['ContentManager']['formemail'];
    if(empty($formemail)){
        $formemail = 'noreply@convospark.com';
        
    }
    $bcc = $sponsor_info['welcome_email_template']['BCC'];
   
    
    $site_url = get_option('siteurl' );
    $login_url = get_option('siteurl' );
    $admin_email= get_option('admin_email');
    $data=  date("Y-m-d");
    $time=  date('H:i:s');
    
    if(empty($fromname)){
        $fromname = get_bloginfo( 'name' );
    }
     $field_key_string = getInbetweenStrings('{', '}', $body);
          foreach($field_key_string as $index=>$keyvalue){
             
             if($keyvalue == 'date' || $keyvalue == 'time' || $keyvalue == 'user_login' ||$keyvalue == 'user_pass' || $keyvalue == 'first_name' || $keyvalue == 'last_name'|| $keyvalue == 'site_url'){
                 
             }else{
                 
                 $get_meta_value = get_user_meta_merger_field_value($user_id,$keyvalue);
                 $body = str_replace('{'.$keyvalue.'}', '', $body);
               
             }
             
         }
         
    $subject = str_replace('{', '*|', $subject);
    $subject = str_replace('}', '|*', $subject);
    $body = str_replace('{', '*|', $body);
    $body = str_replace('}', '|*', $body);
    
    $goble_data_array =array(
        array('name'=>'date','content'=>$data),
        array('name'=>'time','content'=>$time),
        array('name'=>'site_url','content'=>$site_url)
        );
    
 
       
       $body_message =    $body ;
    
       
      
   $message = array(
        
        'html' => $body,
        'text' => '',
        'subject' => $subject,
        'from_email' => $formemail,
        'from_name' => $formname,
        'to' => $to_message_array,
        'headers' => array('Reply-To' => $replay_to),
        
        'track_opens' => true,
        'track_clicks' => true,
        'bcc_address' => $bcc,
        'merge' => true,
        'merge_language' => 'mailchimp',
        'global_merge_vars' => $goble_data_array,
        'merge_vars' => $user_data_array
        
        
    );
   
    // exit;
       
    $lastInsertId = contentmanagerlogging('Import Welcome Email',"Admin Action",serialize($message),$user_ID,$user_info->user_email,"pre_action_data");
     
    $async = false;
    $ip_pool = 'Main Pool';
   // $send_at = 'example send_at';
    $result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
    contentmanagerlogging_file_upload($lastInsertId,serialize($result));
   // echo '<pre>';
   // print_r($result);exit;
    
   
    
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
