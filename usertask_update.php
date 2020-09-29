<?php

if ($_GET['usertask_update'] == "update_submission_status") {

    require_once('../../../wp-load.php');
    $sponsorid = $_POST['sponsorid'];
    $submissiontaskstatuskey=$_POST['submissiontaskstatuskey'];
    $tasktype=$_POST['tasktype'];
    $status = 'Pending';
    $timezone = json_decode(stripslashes($_POST['usertimezone']));
    
    
    update_submission_status($sponsorid,$submissiontaskstatuskey,$status,$tasktype,$timezone);
    die();
}else if ($_GET['usertask_update'] == "update_user_meta_custome") {

    
    require_once('../../../wp-load.php');
    $keyvalue = $_POST['action'];
   
    $updatevalue=$_POST['updatevalue'];
    
    $reg_value = $updatevalue;
    $status=$_POST['status'];
    $sponsorid=$_POST['sponsorid'];
    $timezone = json_decode(stripslashes($_POST['usertimezone']));
    $type = $_POST['typeoftask'];
    
   
    
    if($type == "multivaluedtask"){
        
        $reg_value =  json_decode(stripslashes($updatevalue));
        $reg_value = json_encode($reg_value);
    }
    
    update_user_meta_custome($keyvalue,$reg_value,$status,$sponsorid,$_POST,$timezone);
    
     
    
   
    
    
}else if ($_GET['usertask_update'] == 'user_file_upload') {

    require_once('../../../wp-load.php');
    
   
    
    
    $keyvalue = $_POST['action'];
    
    
    if(!empty($_FILES)){
        
       $updatevalue=$_FILES['file']; 
    }else{
        
       $filestatus = "emptyfile"; 
    }
    
    $status=$_POST['status'];
    $oldvalue=$_POST['lastvalue'];
    $sponsorid=$_POST['sponsorid'];
    $updatevalue['post_request']=$_POST;
	$timezone = json_decode(stripslashes($_POST['usertimezone']));
	$postid = get_current_user_id();
	if($sponsorid !='undefined'){
            $postid = $sponsorid;
        }else{
         $postid = $postid;
	
        }
       global  $wpdb;
       $site_prefix = $wpdb->get_blog_prefix();
       $company_name = get_user_meta($postid, $site_prefix.'company_name', true);
      
       
       $user_info = get_userdata($postid);
       $lastInsertId = contentmanagerlogging('Save Task Uploaded File',"User Action",serialize($updatevalue),$postid,$user_info->user_email,"pre_action_data");
       
      
       $updatevalue['name']=$company_name.'_'.$updatevalue['name'];
       user_file_upload($keyvalue,$updatevalue,$status,$oldvalue,$postid,$lastInsertId,$filestatus,$timezone);
      
    
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
function user_file_upload($keyvalue,$updatevalue,$status,$oldvalue,$postid,$lastInsertId,$filestatus,$timezone) {
    
    //$key = $_POST['value'];
    
   try {
    $user_info = get_userdata($postid);
    $old_meta_value=get_user_meta($postid, $keyvalue); 
    
    global  $wpdb;
       $site_prefix = $wpdb->get_blog_prefix();
   
    if($filestatus !="emptyfile"){
         
        
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
    
   $PreviousSubmissionDate = get_user_meta($postid, $keyvalue.'_datetime',true);
    
   if($movefile && !isset( $movefile['error'])) {
       
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
    $email_body_message_for_admin['ErrorMsg']=$movefile;
    $site_url = get_option('siteurl');
   
    $args = array(
	'posts_per_page'   => -1,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'egpl_custome_tasks',
	'post_status'      => 'draft',
	
    );
    
    $listOFtaskArray = get_posts( $args );
    foreach($listOFtaskArray as $taskKey=>$tasksObject){
            $tasksID = $tasksObject->ID;
            $tasksKey = get_post_meta( $tasksID, 'key' , true);
            if($tasksKey == $keyvalue){
                $tasklabel= get_post_meta( $tasksID, 'label' , true);
                $checkemailnotification = get_post_meta( $tasksID, 'emailnotification' , true);
                if($checkemailnotification == "checked"){
                    
                    $EmailsListnotifications = rtrim(get_post_meta( $tasksID, 'emailnotificationaddress' , true), ',');
                }
            }
          
    }
    $blog_title = get_bloginfo('name'); 
    global $wpdb;
    $companyname = get_user_meta($postid, $site_prefix.'company_name',true);
     $current_date_time = date('d-M-Y H:i:s');
     if ($timezone > 0) {

            $login_date_time = (new DateTime($current_date_time))->sub(new DateInterval('PT' . abs($timezone) . 'H'))->format('d-M-Y H:i:s');
        } else {

            $login_date_time = (new DateTime($current_date_time))->add(new DateInterval('PT' . abs($timezone) . 'H'))->format('d-M-Y H:i:s');
        }
        
        $data = array();                                                                    
        $getsiteurl = get_site_url();
        
        $getcodeuro = str_replace("https://","",$getsiteurl);
        $subscribersID = str_replace("/","-",$getcodeuro);
        
        $taskreporturl = $getsiteurl.'/custom_task_report/';
         
        $tasknotificationurl = "https://api.ravenhub.io/company/ahWkagLbTC/subscribers/".$subscribersID."/events/8K5E67vhBe" ;//$sponsor_info['ContentManager']['ravenhuburls']['tasknotificationtemplates']['url'];
        $data = array("company_name" => $companyname, "task_label" => $tasklabel,"taskreporturl"=>$taskreporturl); 
        $parameter_json = json_encode($data);
        require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/ravenhub_api_request.php';
        $ravenhubapirequest = new Revenhubapi();
        $result_send_notification = $ravenhubapirequest->sendnotifaciton($tasknotificationurl,$parameter_json);
        
        $email_body_message_for_admin['ravenhub']['responce'] = $result_send_notification;
        $email_body_message_for_admin['ravenhub']['requestdata'] = $parameter_json;
        $email_body_message_for_admin['ravenhub']['requestedurl'] = $tasknotificationurl;
        
       $emailBoday ='<p>This is an automatic notification letting you know that a user in your event portal <a href="'.get_site_url().'" >'.$blog_title.'</a> has completed the following task:</p></br><table>
        <tr><td><strong>Event Name:</strong></td><td><a href="'.get_site_url().'" >'.$blog_title.'</a></td></tr>
        <tr><td><strong>Task Name:</strong></td><td>'.$tasklabel.'</td></tr>
        <tr><td><strong>Company:</strong></td><td>'.$companyname.'</td></tr>
        <tr><td><strong>Submitter Email:</strong></td><td>'.$user_info->user_email.'</td></tr>
        <tr><td><strong>Submission Value:</strong></td><td>'.$movefile['url'].'</td></tr>
        <tr><td><strong>Submission Date:</strong></td><td>'.$login_date_time.'</td></tr>
        </table>';
    
    if($checkemailnotification == "checked"){
        $subject = "Notification - New User Task Submission";
        
        if($EmailsListnotifications !=""){
            $sendTaskSubmissionEmail = sendtasksubmissionEmail($emailBoday,$subject,$EmailsListnotifications);
        }
        
    }
    
   
       
        
   
    
    contentmanagerlogging_file_upload ($lastInsertId,serialize($email_body_message_for_admin));
    updatetocvent($postid,$movefile['url'],$keyvalue);
    
    
    
    //wp_mail($to, $subject, $email_body_message_for_admin,$headers);
   } catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    die();
  
}


function update_user_meta_custome($keyvalue,$updatevalue,$status,$sponsorid,$log_obj,$timezone) {
    
   
    
    //$key = $_POST['value'];
  try{  
    $date = new DateTime();
    $blog_title = get_bloginfo('name'); 
    global $wpdb;
    $site_prefix = $wpdb->get_blog_prefix();
    $datetime = $date->format('d-M-Y H:i:A');
    
     
    $request_value.="Task Name : " . $keyvalue. "\n";
    $request_value.="Requested Value : " . $updatevalue. "\n";
    $request_value.="Task Status : " . $status. "\n";
    $request_value.="Task Update Date : " . $datetime. "\n";
    $args = array(
	'posts_per_page'   => -1,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'egpl_custome_tasks',
	'post_status'      => 'draft',
	
    );
    $listOFtaskArray = get_posts( $args );
    foreach($listOFtaskArray as $taskKey=>$tasksObject){
            $tasksID = $tasksObject->ID;
            $tasksKey = get_post_meta( $tasksID, 'key' , true);
            if($tasksKey == $keyvalue){
                $tasklabel= get_post_meta( $tasksID, 'label' , true);
                $checkemailnotification = get_post_meta( $tasksID, 'emailnotification' , true);
                if($checkemailnotification == "checked"){
                    
                    $EmailsListnotifications = rtrim(get_post_meta( $tasksID, 'emailnotificationaddress' , true), ',');
                }
            }
          
     }
    
   if(!empty($sponsorid)){
         
       $postid = $sponsorid;
   
   }else{
        
       $postid = get_current_user_id();
   }
    
    $user_info = get_userdata($postid);
    
    
    
    $lastInsertId = contentmanagerlogging('Save Task',"User Action",serialize($request_value),$postid,$user_info->user_email,"pre_action_data");
    
    
    
    $PreviousSubmissionDate = get_user_meta($postid, $keyvalue.'_datetime',true);
    
    
    $companyname = get_user_meta($postid, $site_prefix.'company_name',true);  
    $old_meta_value=get_user_meta($postid, $keyvalue, $single); 
    
    if($old_meta_value[0] != $updatevalue){
        $result = update_user_meta($postid, $keyvalue, $updatevalue);
    }
    update_user_meta($postid, $keyvalue.'_status', $status);
    if($status == "Complete"){
         $result = update_user_meta($postid, $keyvalue.'_datetime', $datetime);
    }
     
     $current_date_time = date('d-M-Y H:i:s');
     if ($timezone > 0) {

            $login_date_time = (new DateTime($current_date_time))->sub(new DateInterval('PT' . abs($timezone) . 'H'))->format('d-M-Y H:i:s');
        } else {

            $login_date_time = (new DateTime($current_date_time))->add(new DateInterval('PT' . abs($timezone) . 'H'))->format('d-M-Y H:i:s');
        }

        $getsiteurl = get_site_url();
        $getcodeuro = str_replace("https://","",$getsiteurl);
        $subscribersID = str_replace("/","-",$getcodeuro);
      
         $taskreporturl = $getsiteurl.'/custom_task_report/';
        
        
        $tasknotificationurl = "https://api.ravenhub.io/company/ahWkagLbTC/subscribers/".$subscribersID."/events/8K5E67vhBe" ;//$sponsor_info['ContentManager']['ravenhuburls']['tasknotificationtemplates']['url'];
        $data = array("company_name" => $companyname, "task_label" => $tasklabel,"taskreporturl"=>$taskreporturl); 
        $parameter_json = json_encode($data);
        require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/ravenhub_api_request.php';
        $ravenhubapirequest = new Revenhubapi();
        $result_send_notification = $ravenhubapirequest->sendnotifaciton($tasknotificationurl,$parameter_json);
        
        $email_body_message_for_admin['ravenhub']['responce'] = $result_send_notification;
        $email_body_message_for_admin['ravenhub']['requestdata'] = $parameter_json;
        $email_body_message_for_admin['ravenhub']['requestedurl'] = $tasknotificationurl;
        
        
        $emailBoday .='<p>This is an automatic notification letting you know that a user in your event portal <a href="'.get_site_url().'" >'.$blog_title.'</a> has completed the following task:</p></br><table><tr><td><strong>Event Name:</strong></td><td><a href="'.get_site_url().'" >'.$blog_title.'</a></td></tr>
    <tr><td><strong>Task Name:</strong></td><td>'.$tasklabel.'</td></tr>
    <tr><td><strong>Company:</strong></td><td>'.$companyname.'</td></tr>
    <tr><td><strong>Submitter Email:</strong></td><td>'.$user_info->user_email.'</td></tr>
  
    <tr><td><strong>Submission Value:</strong></td><td>'.$updatevalue.'</td></tr>
    <tr><td><strong>Submission Date:</strong></td><td>'.$login_date_time.'</td></tr>
    </table>';
    
    if($checkemailnotification == "checked"){
        $subject = "Notification - New User Task Submission";
        
        if($EmailsListnotifications !=""){
            $sendTaskSubmissionEmail = sendtasksubmissionEmail($emailBoday,$subject,$EmailsListnotifications);
        }
        
    }
   
        
       
    
    contentmanagerlogging_file_upload ($lastInsertId,serialize($email_body_message_for_admin));
    updatetocvent($postid,$updatevalue,$keyvalue);
     
     
     
     
     
  } catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    die();
}

function update_submission_status($sponsorid,$submissiontaskstatuskey,$status,$tasktype,$timezone) {
    //$key = $_POST['value'];
  try{  
   
      
      //echo $status;exit;
    $date = new DateTime();
    $blog_title = get_bloginfo('name'); 
    global $wpdb;
    $site_prefix = $wpdb->get_blog_prefix();
    $datetime = $date->format('d-M-Y H:i:A');
    
     $args = array(
	'posts_per_page'   => -1,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'egpl_custome_tasks',
	'post_status'      => 'draft',
	
    );
    $listOFtaskArray = get_posts( $args );
    foreach($listOFtaskArray as $taskKey=>$tasksObject){
            $tasksID = $tasksObject->ID;
            $tasksKey = get_post_meta( $tasksID, 'key' , true);
            if($tasksKey == $submissiontaskstatuskey){
                $tasklabel= get_post_meta( $tasksID, 'label' , true);
                $checkemailnotification = get_post_meta( $tasksID, 'emailnotification' , true);
                if($checkemailnotification == "checked"){
                    
                    $EmailsListnotifications = rtrim(get_post_meta( $tasksID, 'emailnotificationaddress' , true), ',');
                }
            }
          
     }
    if($sponsorid != 'undefined'){
         $postid = $sponsorid;
     
        
    }else{
          $postid = get_current_user_id();
    }
     $user_info = get_userdata($postid);
    
    
    
     $lastInsertId = contentmanagerlogging('Remove Task Status',"User Action",serialize($submissiontaskstatuskey),$postid,$user_info->user_email,"pre_action_data");
       
    $old_meta_value=get_user_meta($postid, $submissiontaskstatuskey, true); 
    $companyname = get_user_meta($postid, $site_prefix.'company_name',true); 
    
    if(is_array($old_meta_value)){
        
        $old_value = $old_meta_value['url'];
        
    }else{
        
        $old_value = $old_meta_value;
    }
    
    $old_meta_value=get_user_meta($postid, $keyvalue, $single); 
   
    if(!empty($tasktype)){
        
       update_user_meta($postid, $submissiontaskstatuskey, '');
    }
    update_user_meta($postid, $submissiontaskstatuskey.'_status', $status);
   
    update_user_meta($postid, $submissiontaskstatuskey.'_datetime', '');
    
    
    $current_date_time = date('d-M-Y H:i:s');
     if ($timezone > 0) {

            $login_date_time = (new DateTime($current_date_time))->sub(new DateInterval('PT' . abs($timezone) . 'H'))->format('d-M-Y H:i:s');
        } else {

            $login_date_time = (new DateTime($current_date_time))->add(new DateInterval('PT' . abs($timezone) . 'H'))->format('d-M-Y H:i:s');
        }
       $emailBoday ='<p>This is an automatic notification letting you know that a user in your event portal <a href="'.get_site_url().'" >'.$blog_title.'</a> has removed a previously submitted value from the task below:</p></br><table>
    <tr><td><strong>Event Name:</strong></td><td><a href="'.get_site_url().'" >'.$blog_title.'</a></td></tr>
    <tr><td><strong>Task Name:</strong></td><td>'.$tasklabel.'</td></tr>
    <tr><td><strong>Company:</strong></td><td>'.$companyname.'</td></tr>
    <tr><td><strong>Email:</strong></td><td>'.$user_info->user_email.'</td></tr>
  
    <tr><td><strong>Removed Submission:</strong></td><td>'.$old_value.'</td></tr>
    <tr><td><strong>Removed Submission Date:</strong></td><td>'.$login_date_time.'</td></tr>
    </table>';
    
    if($checkemailnotification == "checked"){
        $subject = "Notification - Task Submission Removed";
        
        if($EmailsListnotifications !=""){
            $sendTaskSubmissionEmail = sendtasksubmissionEmail($emailBoday,$subject,$EmailsListnotifications);
        }
        
    }
     
     
     
     
    
   
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

function sendtasksubmissionEmail($emailBoday,$subject,$EmailsListnotifications){
    
    require_once('../../../wp-load.php');
    require_once 'Mandrill.php';
   
    try {
        
        $get_currentsiteURl = get_site_url();
        $sitetitle = get_bloginfo( 'name' );
        $oldvalues = get_option( 'ContenteManager_Settings' );
        $formemail = $oldvalues['ContentManager']['formemail'];
        $mandrill = $oldvalues['ContentManager']['mandrill'];
        $mandrill = new Mandrill($mandrill);
        
        if(empty($formemail)){
            $formemail = 'events@expo-genie.com';
        }
        if (strpos($EmailsListnotifications, ',') !== false) {
            
            $emailaddressArray = explode(",",$EmailsListnotifications);
            foreach($emailaddressArray as $key=>$email){
                
                 $to_message_array[]=array('email'=>$email,'name'=>'','type'=>'to');
            }
            
        }else{
            
            $to_message_array[]=array('email'=>$EmailsListnotifications,'name'=>'','type'=>'to');
            
        }
        
        $message = array(
        
        'html' => $emailBoday,
        'text' => '',
        'subject' => $subject,
        'from_email' => $formemail,
        'from_name' => $sitetitle,
        'to' => $to_message_array,
        'track_opens' => true,
        'track_clicks' => true,
        'merge' => true,
        'merge_language' => 'mailchimp',
        
         "tags" => [$get_currentsiteURl]
        
        );
        
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        $lastInsertId = contentmanagerlogging('Task Submission Notification',"User Action",serialize($message),$user_ID,$user_info->user_email,"pre_action_data");
     
        $async = false;
        $ip_pool = 'Main Pool';
       // $send_at = 'example send_at';
        $result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
        contentmanagerlogging_file_upload($lastInsertId,serialize($result));
        
        
    } catch (Exception $e) {
       
         //contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
         return $e;
    }
    
    die();
    
    
}




?>

