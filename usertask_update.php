<?php

if ($_GET['usertask_update'] == "update_submission_status") {

    require_once('../../../wp-load.php');
    $sponsorid = $_POST['sponsorid'];
    $submissiontaskstatuskey=$_POST['submissiontaskstatuskey'];
    $tasktype=$_POST['tasktype'];
    $status = 'Pending';
    update_submission_status($sponsorid,$submissiontaskstatuskey,$status,$tasktype);
    die();
}else if ($_GET['usertask_update'] == "update_user_meta_custome") {

    
    require_once('../../../wp-load.php');
    $keyvalue = $_POST['action'];
   
    $updatevalue=$_POST['updatevalue'];
    
    $reg_value = $updatevalue;
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

function updatetocvent($postid,$updatevalue,$keyvalue){
    
    
     $oldvalues = get_option( 'ContenteManager_Settings' );
     $cventAccountNo = $oldvalues['ContentManager']['cventaccountname'];
     $cventUsername = $oldvalues['ContentManager']['cventusername'];
     $cventAPiName = $oldvalues['ContentManager']['cventapipassword'];
     
    
     
     
     
    if(!empty($cventAccountNo) && !empty($cventUsername) && !empty($cventAPiName)){
       
        
       
        require('temp/php-cvent-master/CventClient.class.php');
        include 'defult-content.php';
        
        $bar = get_user_option( 'contactStub', $postid );
        
        if(!empty($bar)){
        $cventID[0] = $bar;
        
        
        
        
        $cc = new CventClient();
        $cc->Login($cventAccountNo, $cventUsername, $cventAPiName);
        $type = 'Update';
        $getContact = $cc->RetrieveContacts($cventID);
        
        
        
        
        $getContact['request_input_value_expogenie'] = $request_value;
        
        $lastInsertId = contentmanagerlogging('Update Cvent Custome Field Retrieve',"User Action",serialize($getContact),$postid,$user_info->user_email,"pre_action_data");
       
        
        
        if($cventmappingarray[$keyvalue]['type']  == 'custome'){
        
            foreach($getContact[0]->CustomFieldDetail as $key=>$value){



                    if($cventmappingarray[$keyvalue]['id'] == $value->FieldId){

                        $contactUpdate[0]->CustomFieldDetail[0]->FieldName = $value->FieldName;
                        $contactUpdate[0]->CustomFieldDetail[0]->FieldType = $value->FieldType;
                        $contactUpdate[0]->CustomFieldDetail[0]->FieldValue = $updatevalue;
                        $contactUpdate[0]->CustomFieldDetail[0]->FieldId = $value->FieldId;


                    }




            }
        }
        
        $contactUpdate[0]->Id = $cventID[0];
        
       
        
        
        $lastInsertId = contentmanagerlogging('Update Cvent Custome Field Pre Request',"User Action",serialize($contactUpdate),$postid,$user_info->user_email,"pre_action_data");
     
      
        
        $result = $cc->CreateUpdateContacts($type, $contactUpdate);
        
        contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
        }
    
}
}
function user_file_upload($keyvalue,$updatevalue,$status,$oldvalue,$postid,$lastInsertId) {
    
    //$key = $_POST['value'];
    
   try {
    $user_info = get_userdata($postid);
    $old_meta_value=get_user_meta($postid, $keyvalue); 
  
   
    if(!empty($updatevalue)){
    if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
    //$upload_overrides = array( 'test_form' => false, 'mimes' => array('eps'=>'application/postscript','ai' => 'application/postscript','jpg|jpeg|jpe' => 'image/jpeg','gif' => 'image/gif','png' => 'image/png','bmp' => 'image/bmp','pdf'=>'text/pdf','doc'=>'application/msword','docx'=>'application/msword','xlsx'=>'application/msexcel') );
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
            'svg'                          => 'image/svg+xml',
        
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
    updatetocvent($postid,$movefile['url'],$keyvalue);
    
    
    
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
    //wp_mail($to, $subject, $email_body_message_for_admin,$headers);
 
     updatetocvent($postid,$updatevalue,$keyvalue);
     
     
     
     
     
  } catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    die();
}

function update_submission_status($sponsorid,$submissiontaskstatuskey,$status,$tasktype) {
    //$key = $_POST['value'];
  try{  
   
    if($sponsorid != 'undefined'){
         $postid = $sponsorid;
     
        
    }else{
          $postid = get_current_user_id();
    }
     $user_info = get_userdata($postid);
    
    
    
     $lastInsertId = contentmanagerlogging('Remove Task Status',"User Action",serialize($submissiontaskstatuskey),$postid,$user_info->user_email,"pre_action_data");
       
    
    $old_meta_value=get_user_meta($postid, $keyvalue, $single); 
    if(!empty($tasktype)){
        update_user_meta($postid, $submissiontaskstatuskey, '');
    }
    update_user_meta($postid, $submissiontaskstatuskey.'_status', $status);
   
    update_user_meta($postid, $submissiontaskstatuskey.'_datetime', '');
   
    $email_body_message_for_admin.="Task Name : " . $keyvalue. "\n";
    $email_body_message_for_admin.="Old Value : " . $old_meta_value[0]. "\n";
    $email_body_message_for_admin.="Updated Value : " . $updatevalue. "\n";
    $email_body_message_for_admin.="Task Status : " . $status. "\n";
    $email_body_message_for_admin.="Task Update Date : " . $datetime. "\n";
    
    contentmanagerlogging_file_upload ($lastInsertId,serialize($email_body_message_for_admin));
    // contentmanagerlogging ('Save Task',"User Action",serialize($log_obj),$postid,$user_info->user_email,$result);
    //wp_mail($to, $subject, $email_body_message_for_admin,$headers);
 
  } catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    die();
}





?>

