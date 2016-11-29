<?php



if ($_GET['usertask_update'] == "update_user_meta_custome") {

    require_once('../../../wp-load.php');
    $keyvalue = $_POST['action'];
   
    $updatevalue=$_POST['updatevalue'];
    
    $reg_value = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $updatevalue);
    //echo $reg_value;
  
 
    
 
    $status=$_POST['status'];
    $sponsorid=$_POST['sponsorid'];
    update_user_meta_custome($keyvalue,$reg_value,$status,$sponsorid,$_POST);
}else if ($_GET['usertask_update'] == 'user_file_upload') {

    require_once('../../../wp-load.php');
    $keyvalue = $_POST['action'];
    $updatevalue=$_FILES['file'];
    $status=$_POST['status'];
    $oldvalue=$_POST['lastvalue'];
    $sponsorid=$_POST['sponsorid'];
	
	
	$postid = get_current_user_id();
	if($sponsorid !='undefined'){
            $postid = $sponsorid;
        }else{
         $postid = $postid;
	
        }
       
       $user_info = get_userdata($postid);
       $lastInsertId = contentmanagerlogging('Save Task File',"User Action",serialize($_POST),$postid,$user_info->user_email,"pre_action_data");
       user_file_upload($keyvalue,$updatevalue,$status,$oldvalue,$postid,$lastInsertId);
    
    
}

function user_file_upload($keyvalue,$updatevalue,$status,$oldvalue,$postid,$lastInsertId) {
    
    //$key = $_POST['value'];
    
   try {
    $user_info = get_userdata($postid);
    $old_meta_value=get_user_meta($postid, $keyvalue); 
  
   
    if(!empty($updatevalue)){
    if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
    //$upload_overrides = array( 'test_form' => false, 'mimes' => array('eps'=>'application/postscript','ai' => 'application/postscript','jpg|jpeg|jpe' => 'image/jpeg','gif' => 'image/gif','png' => 'image/png','bmp' => 'image/bmp','pdf'=>'text/pdf','doc'=>'application/msword','docx'=>'application/msword','xlsx'=>'application/msexcel') );
    
    $upload_overrides = array( 'test_form' => false);
    $movefile = wp_handle_upload( $updatevalue, $upload_overrides );
    
    
    $date = new DateTime();
    $datetime = $date->format('d-M-Y H:i:A');
   if ( $movefile && !isset( $movefile['error'] ) ) {
       
            $date = new DateTime();
    $datetime = $date->format('d-M-Y H:i:A');
    update_user_meta($postid, $keyvalue.'_status', $status);
    if($status == "Complete"){
         update_user_meta($postid, $keyvalue.'_datetime', $datetime);
    }
           $utl_value = str_replace('\\', '/', $movefile['file']);
           $fileurl['file'] =$utl_value ;
           $fileurl['type'] = $movefile['type'];
           $fileurl['user_id'] = $postid;
           $fileurl['url'] = $movefile['url'];;
           
           //var_dump($fileurl); exit;
         $result =  update_user_meta($postid, $keyvalue , $fileurl);
           //$email_body_message_for_admin.="Task Name ::".$task_id."\n File Name::".$fileurl['url']."\n File Url::".$fileurl['file']."\n ------------------ \n";
         
          
      }else{
           if(empty($oldvalue)){
            $result =   update_user_meta($postid, $keyvalue , "");
          }
          
      }
       echo '////'.json_encode($movefile);
    }else{
       if(empty($oldvalue)){
           $result =    update_user_meta($postid, $keyvalue , "");
          }
           $date = new DateTime();
    $datetime = $date->format('d-M-Y H:i:A');
    update_user_meta($postid, $keyvalue.'_status', $status);
    if($status == "Complete"){
         update_user_meta($postid, $keyvalue.'_datetime', $datetime);
    }
        $movefile['error']="Empty File";
        $email_body_message_for_admin['result_move_file_error']="Empty File";
        echo '////'.json_encode($movefile);
    }
    
    $email_body_message_for_admin['Task Name']=$keyvalue;
   if (array_key_exists('url', $old_meta_value)) {
    $email_body_message_for_admin['Old Value']=$old_meta_value[0]['url'];
    }
    $email_body_message_for_admin['Updated Value']= $movefile['url'];
    $email_body_message_for_admin['Task Status']= $status;
    $email_body_message_for_admin['Task Update Date']=$datetime;
    
    $headers[] = 'Cc: Qasim Riaz <qasim.riaz@e2esp.com>';
    $site_url = get_option('siteurl');
    $to = "azhar.ghias@e2esp.com";
    $subject = $postid . ' <' . $site_url . '>';
    
    contentmanagerlogging_file_upload ($lastInsertId,serialize($email_body_message_for_admin));
    //wp_mail($to, $subject, $email_body_message_for_admin,$headers);
   } catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    die();
  
}


function update_user_meta_custome($keyvalue,$updatevalue,$status,$sponsorid,$log_obj) {
    //$key = $_POST['value'];
  try{  
    $date = new DateTime();
    $datetime = $date->format('d-M-Y H:i:A');
    $request_value.="Task Name : " . $keyvalue. "\n";
    $request_value.="Requested Value : " . $updatevalue. "\n";
    $request_value.="Task Status : " . $status. "\n";
    $request_value.="Task Update Date : " . $datetime. "\n";
    
    
    
   if(!empty($sponsorid)){
         $postid = $sponsorid;
     
        
    }else{
          $postid = get_current_user_id();
    }
     $user_info = get_userdata($postid);
    
    
    
     $lastInsertId = contentmanagerlogging('Save Task',"User Action",serialize($request_value),$postid,$user_info->user_email,"pre_action_data");
       
    
    $old_meta_value=get_user_meta($postid, $keyvalue, $single); 
    if($old_meta_value[0] != $updatevalue){
        $result = update_user_meta($postid, $keyvalue, $updatevalue);
    }
    update_user_meta($postid, $keyvalue.'_status', $status);
    if($status == "Complete"){
         $result = update_user_meta($postid, $keyvalue.'_datetime', $datetime);
    }
    $email_body_message_for_admin.="Task Name : " . $keyvalue. "\n";
    $email_body_message_for_admin.="Old Value : " . $old_meta_value[0]. "\n";
    $email_body_message_for_admin.="Updated Value : " . $updatevalue. "\n";
    $email_body_message_for_admin.="Task Status : " . $status. "\n";
    $email_body_message_for_admin.="Task Update Date : " . $datetime. "\n";
    $site_url = get_option('siteurl');
     $to = "azhar.ghias@e2esp.com";
       $headers[] = 'Cc: Qasim Riaz <qasim.riaz@e2esp.com>';
    $subject = $postid . ' <' . $site_url . '>';
    
     contentmanagerlogging_file_upload ($lastInsertId,serialize($email_body_message_for_admin));
    // contentmanagerlogging ('Save Task',"User Action",serialize($log_obj),$postid,$user_info->user_email,$result);
    wp_mail($to, $subject, $email_body_message_for_admin,$headers);
 
  } catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    die();
}







?>

