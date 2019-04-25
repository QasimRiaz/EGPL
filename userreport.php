<?php


if ($_GET['contentManagerRequest'] == 'updateuserforthissite') {
    
    require_once('../../../wp-load.php');
    
    
    updateuserforthissite($_POST);
    die();
    
  
     
    
}else if ($_GET['contentManagerRequest'] == 'checkuseralreadyexist') {
    
    require_once('../../../wp-load.php');
    
    
    check_useremail_exist($_POST);
    die();
    
  
     
    
}else if ($_GET['contentManagerRequest'] == 'get_all_selected_users_files') {
    
    require_once('../../../wp-load.php');
    
    
    selecteduser_getuploadfiles_download($_POST);
    die();
    
  
     
    
}else if ($_GET['contentManagerRequest'] == 'approve_selfsign_user') {
    
    require_once('../../../wp-load.php');
    
     $user_id = $_POST['id'];
     $user_role_assignment = $_POST['userassignrole'];
  
     approve_selfsign_user($user_id,$user_role_assignment);
     
    
}else if ($_GET['contentManagerRequest'] == 'decline_selfsign_user') {
    
    require_once('../../../wp-load.php');
    
     $user_id = $_POST['id'];
  
     decline_selfsignuser_metas($user_id);
     
    
}else if ($_GET['contentManagerRequest'] == 'selfsignadd_new_sponsor_metafields') {
    require_once('../../../wp-load.php');
    try{
    
      
    $lastInsertId = contentmanagerlogging('New User Register Self Signup',"User Action",serialize($_POST),'','',"pre_action_data");
      
    $username = str_replace("+","",$_POST['username']);;
    $email = $_POST['email'];
    $role =$_POST['sponsorlevel'];
    $loggin_data=$_POST;
    
    
    unset($_POST['username']);
    unset($_POST['email']);
    unset($_POST['sponsorlevel']);
    

    

    $user_id = username_exists($username);
    $blogid = get_current_blog_id() ;
    $message['username'] = $username;
    $profilepic=$_FILES['profilepic'];
    $picprofileurl = resource_file_upload($profilepic);
    $meta_array=$_POST;
    
  
   
    if (!$user_id and email_exists($email) == false) {
        
       $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
       $username = sanitize_user($username);
       $user_id = register_new_user( $username, $email );//wp_create_user($username, $random_password, $email);
       
       if ( ! is_wp_error( $user_id ) ) {
        
       $result=$user_id;
       $loggin_data['created_id']=$result;
       $message['user_id'] = $user_id;
       $message['msg'] = 'User created';
       $message['showmsg'] = 'Your submission has been received and is being reviewed';
       
       $message['userrole'] = $role;
      
       
       update_user_option($user_id, 'user_profile_url', $picprofileurl);
       
       add_user_to_blog(1, $user_id, $role);
       add_user_to_blog($blogid, $user_id, $role);
       
       add_new_sponsor_metafields($user_id,$meta_array,$role);
       $send_email_type = 'selfsignuprequest';
       $responce = selfsign_registration_emails($user_id,$send_email_type);
       
       
            
             
    }else{
           $userregister_responce = (array)$user_id;
			//print_r($userregister_responce);
		   if(empty($userregister_responce['errors']['invalid_username'][0])){
			   
			   $message['msg'] = $userregister_responce['errors']['invalid_email'][0];
		   }else{
			   
			   $message['msg'] = $userregister_responce['errors']['invalid_username'][0];
		   }
    } 
    } else {
        
        
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
        
            $message['msg'] = 'A user with this Email address already exists. If you already have an approved account in the system, please go to the Login screen.';
        
        }else{
            
                switch_to_blog($currentblogid); 
                add_user_to_blog($currentblogid, $user_id, $role);
                update_user_option($user_id, 'user_profile_url', $picprofileurl);
                add_new_sponsor_metafields($user_id,$meta_array,$role);
                $send_email_type = 'selfsignuprequest';
                selfsign_registration_emails($user_id,$send_email_type);
               
                $message['msg'] = 'User created';
                $message['showmsg'] =  'Your submission has been received and is being reviewed.';
                
                
           
            
        }
        
        
       
        
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
}else if ($_GET['contentManagerRequest'] == "user_report_savefilters") {
    require_once('../../../wp-load.php');

    //echo '<pre>';
    //print_r($_POST);exit;
    $userreportname = $_POST['userreportname'];
    $userreportfilterdata = stripslashes($_POST['userreportfiltersdata']);
    $showcolumnslist = stripslashes($_POST['showcolumnslist']);
    //$showroleslist = stripslashes($_POST['showroleslist']);
    $usercolunmtype = $_POST['userbytype'];
    $usercolunmname = $_POST['userbycolname'];
    user_report_savefilters($userreportname, $userreportfilterdata, $showcolumnslist, $ordercolunmtype, $usercolunmname);
    
}else if ($_GET['contentManagerRequest'] == "getusersreport") {
    require_once('../../../wp-load.php');

    getusersreport($_POST);
}else if ($_GET['contentManagerRequest'] == "user_report_removefilter") {

    require_once('../../../wp-load.php');
    $userreportname = $_POST['userreportname'];
    user_report_removefilter($userreportname);
}else if ($_GET['contentManagerRequest'] == "get_userreport_detail") {

    require_once('../../../wp-load.php');
    $orderreportname = $_POST['reportname'];
    get_userreport_detail($orderreportname);
}else if ($_GET['contentManagerRequest'] == "setsessioninphp") {
    require_once('../../../wp-load.php');
    
    session_start();
    
    $_SESSION['usertimezone'] = $_POST['usertimezone'];
    $_SESSION['filterdata'] = $_POST['filterdata'];
    $_SESSION['selectedcolumnskeys'] = $_POST['selectedcolumnskeys'];
    $_SESSION['userbytype'] = $_POST['userbytype'];
    $_SESSION['userbycolname'] = $_POST['userbycolname'];
    $_SESSION['selectedcolumnslebel'] = $_POST['selectedcolumnslebel'];
    $_SESSION['loadreportname'] = $_POST['loadreportname'];
    
    echo 'sessionstart';
    die();
   
}else if ($_GET['contentManagerRequest'] == "userreportresultdraw") {
    require_once('../../../wp-load.php');
    
   
    userreportresultdraw();
    
    die();
   
}else if ($_GET['contentManagerRequest'] == 'multitemplatewelcomeemail') {
    
    require_once('../../../wp-load.php');
    
   try{ 
       
       $user_ID = get_current_user_id();
       $user_info = get_userdata($user_ID);
       $lastInsertId = contentmanagerlogging('Welcome Email Template',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
       
    $welcome_subject =$_POST['emailSubject'];
    $welcome_body =$_POST['emailBody'];
    $replaytoemailadd =$_POST['replaytoemailadd'];
    $welcomeemailfromname =$_POST['welcomeemailfromname'];
    $template_name = $_POST['welcomeemailtemplatename'];
    
    if($template_name == 'Welcome Email'){
        $templatestringname = "welcome_email_template";
    }else{
     
     
     
     $templatestringname = preg_replace("/[^a-zA-Z0-9-\s]+/", "", html_entity_decode($template_name, ENT_QUOTES));
     
    }
    
    
    $settitng_key='AR_Contentmanager_Email_Template_welcome';
    $sponsor_info = get_option($settitng_key);
    
    $result='';
      
   
    $sponsor_info[$templatestringname]['welcomesubject'] = $welcome_subject;
    $sponsor_info[$templatestringname]['fromname'] = $welcomeemailfromname;
    $sponsor_info[$templatestringname]['replaytoemailadd'] = $replaytoemailadd;
    $sponsor_info[$templatestringname]['welcomeboday'] = stripslashes($welcome_body);
    $sponsor_info[$templatestringname]['BCC'] = $_POST['BCC'];
    //$sponsor_info[$templatestringname]['CC'] = $_POST['CC'];
     
     //contentmanagerlogging('Welcome Email Template',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,$result);
    
    $result= update_option($settitng_key, $sponsor_info);
    contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
    
   } catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
     die();
	 
}else if ($_GET['contentManagerRequest'] == 'multitemplatewelcomeemailremoved') {
    
    require_once('../../../wp-load.php');
    
   try{ 
       
       $user_ID = get_current_user_id();
       $user_info = get_userdata($user_ID);
       $lastInsertId = contentmanagerlogging('Remove Welcome Email Template',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
       
    
    $template_name = $_POST['welcomeemailtemplatename'];
    echo $template_name;
    $settitng_key='AR_Contentmanager_Email_Template_welcome';
    $sponsor_info = get_option($settitng_key);
    
    unset($sponsor_info[$template_name]);
    
    
    
    $result= update_option($settitng_key, $sponsor_info);
    contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
    
   } catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
     die();
	 
}


function getusersreport($data) {

    require_once('../../../wp-load.php');

    try {

        global $wpdb;
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        $site_prefix = $wpdb->get_blog_prefix();
        $lastInsertId = contentmanagerlogging('Get User Report Date', "Admin Action", $orderreportdata, $user_ID, $user_info->user_email, "pre_action_data");
        $usertimezone = $data['usertimezone'];
        $additional_settings = get_option( 'EGPL_Settings_Additionalfield' );
        
        
        
        //$test = 'custome_task_manager_data';
       // $result_task_array_list = get_option($test);
       
        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_type'        => 'egpl_custome_tasks',
            'post_status'      => 'draft',

        );
        $result_task_array_list = get_posts( $args );
    
        
        $columns_headers = [];
        $columns_rows_data = [];

        $columns_list_defult_user_report[0]['title'] = 'User ID';
        $columns_list_defult_user_report[0]['type'] = 'string';
        $columns_list_defult_user_report[0]['key'] = 'wp_user_id';
        
        
        $columns_list_defult_user_report[1]['title'] = 'Action';
        $columns_list_defult_user_report[1]['type'] = 'string';
        $columns_list_defult_user_report[1]['key'] = 'action_edit_delete';

        $columns_list_defult_user_report[2]['title'] = 'Company Name';
        $columns_list_defult_user_report[2]['type'] = 'string';
        $columns_list_defult_user_report[2]['key'] = $site_prefix.'company_name';

        $columns_list_defult_user_report[3]['title'] = 'Level';
        $columns_list_defult_user_report[3]['type'] = 'string';
        $columns_list_defult_user_report[3]['key'] = 'Role';
        
        $columns_list_defult_user_report[4]['title'] = 'Last login';
        $columns_list_defult_user_report[4]['type'] = 'date';
        $columns_list_defult_user_report[4]['key'] = 'last_login';
        
        $columns_list_defult_user_report[5]['title'] = 'First Name';
        $columns_list_defult_user_report[5]['type'] = 'string';
        $columns_list_defult_user_report[5]['key'] = $site_prefix.'first_name';
        
        $columns_list_defult_user_report[6]['title'] = 'Last Name';
        $columns_list_defult_user_report[6]['type'] = 'string';
        $columns_list_defult_user_report[6]['key'] = $site_prefix.'last_name';
        
        $columns_list_defult_user_report[7]['title'] = 'Email';
        $columns_list_defult_user_report[7]['type'] = 'string';
        $columns_list_defult_user_report[7]['key'] = 'Email';
        
        $columns_list_defult_user_report[8]['title'] = 'Welcome Email Sent On';
        $columns_list_defult_user_report[8]['type'] = 'date';
        $columns_list_defult_user_report[8]['key'] = $site_prefix.'convo_welcomeemail_datetime';
        
        $columns_list_defult_user_report[9]['title'] = 'Status';
        $columns_list_defult_user_report[9]['type'] = 'string';
        $columns_list_defult_user_report[9]['key'] = $site_prefix.'selfsignupstatus'; 
        
        $columns_list_defult_user_report[10]['title'] = 'User Company Logo';
        $columns_list_defult_user_report[10]['type'] = 'string';
        $columns_list_defult_user_report[10]['key'] = $site_prefix.'user_profile_url';
        
        $columns_list_defult_user_report[11]['title'] = 'Booth Number';
        $columns_list_defult_user_report[11]['type'] = 'string';
        $columns_list_defult_user_report[11]['key'] = 'booth_number';
       
        
        
        $index_count = 11;
        if(!empty($additional_settings)){
            
            foreach ($additional_settings as $key=>$valuename){
                
                if($additional_settings[$key]['type']!="html"){
                    $columns_list_defult_user_report[$index_count]['title'] = $additional_settings[$key]['name'];
                    $columns_list_defult_user_report[$index_count]['type'] = 'string';
                    $columns_list_defult_user_report[$index_count]['key'] = $site_prefix.$additional_settings[$key]['key'];
                }
                $index_count++;  
            
            }
        
            
        }
        

      
        
       
    if(!empty($result_task_array_list)){
        
     
        //asort($result_task_array_list['profile_fields']);
       foreach ($result_task_array_list as $taskIndex => $taskObject) {
           
                                    $tasksID=$taskObject->ID;
                                    $profile_field_settings = [];
                                    $value_value = get_post_meta( $tasksID, 'value' , false);
                                    $value_unique = get_post_meta( $tasksID, 'unique' , false);
                                    $value_class = get_post_meta( $tasksID, 'class' , false);
                                    $value_after = get_post_meta( $tasksID, 'after', false);
                                    $value_required = get_post_meta( $tasksID, 'required' , false);
                                    $value_allow_tags = get_post_meta( $tasksID, 'allow_tags' , false);
                                    $value_add_to_profile = get_post_meta( $tasksID, 'add_to_profile' , false);
                                    $value_allow_multi = get_post_meta( $tasksID, 'allow_multi', false);
                                    $value_label = get_post_meta( $tasksID, 'label' , false);
                                    $value_type = get_post_meta( $tasksID, 'type' , false);
                                    $value_lin_url = get_post_meta( $tasksID, 'link_url' , false);
                                    $value_linkname = get_post_meta( $tasksID, 'linkname', false);
                                    $value_attr = get_post_meta( $tasksID, 'duedate', false);
                                    
                                   
                                    
                                    
                                    $value_taskattrs = get_post_meta( $tasksID, 'taskattrs', false);
                                    $value_taskMWC = get_post_meta( $tasksID, 'taskMWC' , false);
                                    $value_taskMWDDP = get_post_meta( $tasksID, 'taskMWDDP' , false);
                                    $value_roles = get_post_meta( $tasksID, 'roles' , false);
                                    $value_usersids = get_post_meta( $tasksID, 'usersids' , false);
                                    $value_descrpition = get_post_meta( $tasksID, 'descrpition', false);
                                    $value_key = get_post_meta( $tasksID, 'key', false);
                                    $profile_field_name  = $value_key[0];
                                    $profile_field_settings['value'] = $value_value[0];
                                    $profile_field_settings['unique'] = $value_unique[0];
                                    $profile_field_settings['class'] =$value_class[0];
                                    $profile_field_settings['after'] =$value_after[0];
                                    $profile_field_settings['required'] =$value_required[0];
                                    $profile_field_settings['allow_tags'] =$value_allow_tags[0];
                                    $profile_field_settings['add_to_profile'] =$value_add_to_profile[0];
                                    $profile_field_settings['allow_multi'] =$value_allow_multi[0];
                                    $profile_field_settings['label'] =$value_label[0];
                                    $profile_field_settings['type'] =$value_type[0];
                                    $profile_field_settings['lin_url'] =$value_lin_url[0];
                                    $profile_field_settings['linkname'] =$value_linkname[0];
                                    $profile_field_settings['attrs'] =$value_attr[0];
                                    $profile_field_settings['taskattrs'] =$value_taskattrs[0];
                                    $profile_field_settings['taskMWC'] =$value_taskMWC[0];
                                    $profile_field_settings['taskMWDDP'] =$value_taskMWDDP[0];
                                    $profile_field_settings['roles'] =$value_roles[0];
                                    $profile_field_settings['usersids'] =$value_usersids[0];
                                    $profile_field_settings['descrpition'] =$value_descrpition[0];
                                    
                                  
                                    
                                    
                                    if($profile_field_settings['type'] == "select-2"){
                                        
                                            $getarraysValue = get_post_meta( $tasksID, 'options', false);
                                            
                                            if(!empty($getarraysValue[0])){

                                                
                                                 $profile_field_settings['options'] =$getarraysValue[0];
                                                 
                                             }
                                   }
           
            if ($profile_field_settings['type'] == 'datetime') {
                
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'];
                $columns_list_defult_user_report[$index_count]['type'] = 'date';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name;
                $index_count++;
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'].' Datetime';
                $columns_list_defult_user_report[$index_count]['type'] = 'customedate';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name.'_datetime';
                $index_count++;
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'].' Status';
                $columns_list_defult_user_report[$index_count]['type'] = 'string';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name.'_status';
                $index_count++;
                
                
                
            } else if ($profile_field_settings['type'] == 'color') {
                
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'];
                $columns_list_defult_user_report[$index_count]['type'] = 'html';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name;
                $index_count++;
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'].' Datetime';
                $columns_list_defult_user_report[$index_count]['type'] = 'customedate';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name.'_datetime';
                $index_count++;
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'].' Status';
                $columns_list_defult_user_report[$index_count]['type'] = 'string';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name.'_status';
                $index_count++;
            
                
            } else if ($profile_field_settings['type'] == 'text' || $profile_field_settings['type'] == 'textarea') {
                
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'];
                $columns_list_defult_user_report[$index_count]['type'] = 'string';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name;
                $index_count++;
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'].' Datetime';
                $columns_list_defult_user_report[$index_count]['type'] = 'customedate';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name.'_datetime';
                $index_count++;
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'].' Status';
                $columns_list_defult_user_report[$index_count]['type'] = 'string';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name.'_status';
                $index_count++;
                
            }  else {
                
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'];
                $columns_list_defult_user_report[$index_count]['type'] = 'string';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name;
                $index_count++;
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'].' Datetime';
                $columns_list_defult_user_report[$index_count]['type'] = 'customedate';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name.'_datetime';
                $index_count++;
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'].' Status';
                $columns_list_defult_user_report[$index_count]['type'] = 'string';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name.'_status';
                $index_count++;
            }
        
            
            
    }
    }

         foreach ($columns_list_defult_user_report as $col_keys => $col_keys_title) {


            $colums_array_data['title'] = $columns_list_defult_user_report[$col_keys]['title'];
            $colums_array_data['type'] = $columns_list_defult_user_report[$col_keys]['type'];
            $colums_array_data['key'] = $columns_list_defult_user_report[$col_keys]['key'];
            array_push($columns_headers, $colums_array_data);
        }
    $columns_filter_array_data = [];
    
    
     foreach ($columns_headers as $rows=>$row){
          
             if ($row['title'] != 'Action' && $row['type'] != "html" ) {
                 
                 
                if ($row['title'] == 'User ID') {
                    
                    $pusheaderfilter = array(
                            'id' => $row['key'],
                            'unique'=> true,
                            'label'=> $row['title'],
                            'operators'=> ['equal','is_not_empty'],
                            'type'=> 'integer',
                            'size'=> 20

                    );
                    
                }else if ($row['title'] == 'Email' || $row['title'] == 'Level') {
                     
                    $pusheaderfilter = array(
                            'id' => $row['key'],
                            'unique'=> true,
                            'label'=> $row['title'],
                            'operators'=> ['equal','is_not_empty'],
                            'type'=> 'string',
                            'size'=> 20

                    );
                     
                 }else if ($row['type'] == 'date') {
                     
                     $pusheaderfilter = array(
                            'id'            => $row['key'],
                            'unique'        => true,
                            'type'          => 'date',
                            'label'         => $row['title'],
                            'operators'     => ['is_empty','is_not_empty','equal', 'less', 'greater', 'between'],
                            'validation'    => ['format'=> 'DD-MMM-YYYY'],
                            'plugin'=> 'datepicker',
                            'plugin_config' => ['format'=> 'dd-M-yyyy', 'todayBtn'=> 'linked', 'todayHighlight'=> true, 'autoclose'=> true],
                            'size' => 20
                        );
                 }else if ($row['type'] == 'num' || $row['type'] == 'num-fmt') {
                     $pusheaderfilter = array(
                            'id' => $row['key'],
                            'unique'=> true,
                            'label'=> $row['title'],
                            'operators'=> ['equal', 'less', 'greater','is_empty','is_not_empty'],
                            'type'=> 'integer',
                            'size'=> 20

                    );
                 }else if ($row['type'] == 'customedate') {
                     $pusheaderfilter = array(
                            'id' => $row['key'],
                            'unique'=> true,
                            'label'=> $row['title'],
                            'operators'=> ['equal','is_not_empty'],
                            'plugin'=> 'datepicker',
                            'plugin_config' => ['format'=> 'dd-M-yyyy', 'todayBtn'=> 'linked', 'todayHighlight'=> true, 'autoclose'=> true],
                            'validation'    => ['format'=> 'DD-MMM-YYYY'],
                            'type'=> 'date',
                            'size'=> 20

                    );
                 }else{
                     
                    $pusheaderfilter = array(
                            'id' => $row['key'],
                            'unique'=> true,
                            'label'=> $row['title'],
                            'operators'=> ['contains', 'equal','is_empty','is_not_empty'],
                            'type'=> 'string',
                            'size'=> 20

                    ); 
                 }
              array_push($columns_filter_array_data, $pusheaderfilter);    
                 
             }
             
           
        }
        
        $blog_id = get_current_blog_id();
        $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
        $all_roles = get_option($get_all_roles_array);
        $counter = 0;
        foreach ($all_roles as $key => $name) {
            
            if($name['name'] != "Administrator"){
                
                $user_roles_list[$counter]['name'] = $name['name'];
                $user_roles_list[$counter]['key'] = $key;
                $counter++;
                
            }
        }
        
        
        
        echo json_encode($columns_headers) . '//' . json_encode($columns_filter_array_data). '//' . json_encode($user_roles_list);
    
        
    } catch (Exception $e) {

        contentmanagerlogging_file_upload($lastInsertId, serialize($e));

        return $e;
    }

    die();
}

function user_report_savefilters($userreportname, $userreportfilterdata, $showcolumnslist, $ordercolunmtype, $usercolunmname) {

    require_once('../../../wp-load.php');

    try {
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        $lastInsertId = contentmanagerlogging('Saved User Report', "Admin Action", $orderreportfilterdata, $user_ID, $user_info->user_email, "pre_action_data");

        $settitng_key = 'ContenteManager_usersreport_settings';
        $userreportname =  preg_replace("/[^a-zA-Z0-9-\s]+/", "", html_entity_decode($userreportname, ENT_QUOTES));
        
        $orderreportfilterdata = stripslashes($orderreportfilterdata);

        $user_reportsaved_list = get_option($settitng_key);
        $user_reportsaved_list[$userreportname][0] = $userreportfilterdata;
        $user_reportsaved_list[$userreportname][1] = $showcolumnslist;
        $user_reportsaved_list[$userreportname][2] = $usercolunmtype;
        $user_reportsaved_list[$userreportname][3] = $usercolunmname;
        //$user_reportsaved_list[$userreportname][4] = $showroleslist;

        update_option($settitng_key, $user_reportsaved_list);
        $order_reportsaved_list = get_option($settitng_key);
        contentmanagerlogging_file_upload($lastInsertId, serialize($user_reportsaved_list));
        foreach ($user_reportsaved_list as $key => $value) {
            $userlist[] = $key;
        }

        echo json_encode($userlist);
    } catch (Exception $e) {

        contentmanagerlogging_file_upload($lastInsertId, serialize($e));

        return $e;
    }

    die();
}

function user_report_removefilter($orderreportname) {

    require_once('../../../wp-load.php');

    try {


        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        $lastInsertId = contentmanagerlogging('Remove User Report', "Admin Action", $orderreportdata, $user_ID, $user_info->user_email, "pre_action_data");


        $settitng_key = 'ContenteManager_usersreport_settings';
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
function get_userreport_detail($orderreportname) {

    require_once('../../../wp-load.php');

    try {


        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        $lastInsertId = contentmanagerlogging('Load Order Report', "Admin Action", $orderreportdata, $user_ID, $user_info->user_email, "pre_action_data");


        $settitng_key = 'ContenteManager_usersreport_settings';
        $order_reportsaved_list = get_option($settitng_key);


        contentmanagerlogging_file_upload($lastInsertId, serialize($order_reportsaved_list));

        echo json_encode($order_reportsaved_list[$orderreportname]);
    } catch (Exception $e) {

        contentmanagerlogging_file_upload($lastInsertId, serialize($e));

        return $e;
    }

    die();
}


function userreportresultdraw() {
   
    require_once('../../../wp-load.php');
     require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/floorplan-manager.php';
    global $wpdb;
    try {
        if(isset($_POST['filterdata'])){
            
            $search_filter_array   =  json_decode(stripslashes($_POST['filterdata']));
            $search_filter_collabel      = json_decode(stripslashes($_POST['selectedcolumnslebel']));
            $search_filter_colarray      = json_decode(stripslashes($_POST['selectedcolumnskeys']));
            $search_filter_Ordercolname  = $_POST['userbycolname'];
            $search_filter_Order         = $_POST['userbytype'];
        }
        
      
        
        
        $search_filter_usertimezone  = json_decode(stripslashes($_POST['usertimezone']));
        $base_url = "https://" . $_SERVER['SERVER_NAME'];
        
        $args['role__not_in']= 'Administrator';
        $site_prefix = $wpdb->get_blog_prefix();
        
       if(isset($_POST['filterdata'])){
        $args['meta_query']['relation']= 'AND';
        foreach($search_filter_array as $filter){
        
            if($filter->operator == 'is_not_empty'){
                $compare_operator = '!=';
            }else if($filter->operator == 'equal'){
                $compare_operator = '=';
            }else if($filter->operator == 'contains'){
                $compare_operator = 'LIKE';
            }else if($filter->operator == 'is_empty'){
                
               // $args['meta_query']['relation']= 'OR';
               // $compare_operator = 'NOT EXISTS';
                //$sub_query['key']=$filter->id;
               // $sub_query['compare']='NULL';
               // $sub_query['value']='';
                
                //array_push($args['meta_query'],$sub_query);
               if($filter->id == 'last_login'){
                   $sub_query['key']=$site_prefix.'custom_login_time_as_site';
               }else{
                   $sub_query['key']=$filter->id;
               }
               
                $sub_query['compare']='NOT EXISTS';
                $sub_query['value']='';
              
                array_push($args['meta_query'],$sub_query);
               
                
                
               
                
            }else if($filter->operator == 'less'){
                $compare_operator = '<';
            }else if($filter->operator == 'greater'){
                $compare_operator = '>';
            }else if($filter->operator == 'between'){
                $compare_operator = 'BETWEEN';
            }
       if($filter->operator != 'is_empty'){     
        if($filter->type == 'date'){
            
            $filter_apply_array['type']='numeric';
            
            if($filter->id == "last_login" ){
                if($filter->operator == 'between'){
                    $filter_apply_array['value']=array(strtotime($filter->value[0]), strtotime($filter->value[1]));
                }else{
                    if(!empty($filter->value)){
                        $filter_apply_array['value']=strtotime($filter->value);
                    }
                    
                }
                $filter_apply_array['key']= $site_prefix.'custom_login_time_as_site';
                if($filter->operator == 'equal'){
                     $filter_apply_array['value']=array(strtotime($filter->value.' 00:00'), strtotime($filter->value.' 23:59'));
                     $compare_operator = "BETWEEN";
                }
             }else if($filter->id == $site_prefix."convo_welcomeemail_datetime" ){
                 
                if($filter->operator == 'between'){
                    
                    
                    $filter_apply_array['value']=array(strtotime($filter->value[0])*1000, strtotime($filter->value[1])*1000);
                }else{
                    if(!empty($filter->value)){
                        $filter_apply_array['value']=strtotime($filter->value)*1000;
                    }
                    
                }
                $filter_apply_array['key']= $filter->id; 
                if($filter->operator == 'equal'){
                     $filter_apply_array['value']=array(strtotime($filter->value.' 00:00')*1000, strtotime($filter->value.' 23:59')*1000);
                     $compare_operator = "BETWEEN";
                }
             }else if(strpos($filter->id, '_datetime') !== false){
               
                $filter_apply_array['key']=$filter->id;
                $filter_apply_array['value']=$filter->value;
                $filter_apply_array['type']='CHAR';
                if($filter->operator == 'equal'){
                    $compare_operator = 'LIKE';
                }
            }
        
            $filter_apply_array['compare']=$compare_operator;
            
        }else{
            
            if($filter->id == 'Email'){
                
                
                $args['search']= $filter->value;
                $args['search_columns']= array('user_email');
                
            }else if($filter->id == 'Role'){
                $zname_clean = str_replace(" ","_",$filter->value);
               
                
                $args['role']=  strtolower($zname_clean);
                
                
            }else if($filter->id == 'wp_user_id'){
                
                $args['include']= $filter->value;
                
                
            }else{
                
                $filter_apply_array['key']=$filter->id;
                $filter_apply_array['value']=$filter->value;
                $filter_apply_array['type']='CHAR';
                $filter_apply_array['compare']=$compare_operator;
            }
        }   
        
        array_push($args['meta_query'],$filter_apply_array);
       }
    }
 }
 
 
        $user_query = new WP_User_Query( $args );
        $authors = $user_query->get_results();
        
      //echo '<pre>';
       //print_r($args);
      //echo sizeof($authors);exit;
        
        
        
        if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }
        $get_all_roles = get_option($get_all_roles_array);
        

        global $wpdb;
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        $lastInsertId = contentmanagerlogging('Get User Report Result', "Admin Action", $orderreportdata, $user_ID, $user_info->user_email, "pre_action_data");
        $usertimezone = $data['usertimezone'];
        $additional_settings = get_option( 'EGPL_Settings_Additionalfield' );
       // $test = 'custome_task_manager_data';
       // $result_task_array_list = get_option($test);
       
        
        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_type'        => 'egpl_custome_tasks',
            'post_status'      => 'draft',

        );
        $result_task_array_list = get_posts( $args );
        
        
        $columns_headers = [];
        $columns_rows_data = [];
        
        $columns_list_defult_user_report[0]['title'] = 'User ID';
        $columns_list_defult_user_report[0]['type'] = 'string';
        $columns_list_defult_user_report[0]['key'] = 'wp_user_id';
        
        
        $columns_list_defult_user_report[1]['title'] = 'Action';
        $columns_list_defult_user_report[1]['type'] = 'string';
        $columns_list_defult_user_report[1]['key'] = 'action_edit_delete';

        $columns_list_defult_user_report[2]['title'] = 'Company Name';
        $columns_list_defult_user_report[2]['type'] = 'string';
        $columns_list_defult_user_report[2]['key'] = $site_prefix.'company_name';

        $columns_list_defult_user_report[3]['title'] = 'Level';
        $columns_list_defult_user_report[3]['type'] = 'string';
        $columns_list_defult_user_report[3]['key'] = 'Role';
        
        $columns_list_defult_user_report[4]['title'] = 'Last login';
        $columns_list_defult_user_report[4]['type'] = 'date';
        $columns_list_defult_user_report[4]['key'] = 'last_login';
        
        $columns_list_defult_user_report[5]['title'] = 'First Name';
        $columns_list_defult_user_report[5]['type'] = 'string';
        $columns_list_defult_user_report[5]['key'] = $site_prefix.'first_name';
        
        $columns_list_defult_user_report[6]['title'] = 'Last Name';
        $columns_list_defult_user_report[6]['type'] = 'string';
        $columns_list_defult_user_report[6]['key'] = $site_prefix.'last_name';
        
        $columns_list_defult_user_report[7]['title'] = 'Email';
        $columns_list_defult_user_report[7]['type'] = 'string';
        $columns_list_defult_user_report[7]['key'] = 'Email';
        
        $columns_list_defult_user_report[8]['title'] = 'Welcome Email Sent On';
        $columns_list_defult_user_report[8]['type'] = 'date';
        $columns_list_defult_user_report[8]['key'] = $site_prefix.'convo_welcomeemail_datetime';
        
        
        $columns_list_defult_user_report[9]['title'] = 'Status';
        $columns_list_defult_user_report[9]['type'] = 'string';
        $columns_list_defult_user_report[9]['key'] = $site_prefix.'selfsignupstatus'; 
        
        
        
        $columns_list_defult_user_report[10]['title'] = 'User Company Logo';
        $columns_list_defult_user_report[10]['type'] = 'string';
        $columns_list_defult_user_report[10]['key'] = $site_prefix.'user_profile_url';
        
    $columns_list_defult_user_report[11]['title'] = 'Booth Number';
        $columns_list_defult_user_report[11]['type'] = 'string';
        $columns_list_defult_user_report[11]['key'] = 'booth_number';
        
        
        $index_count = 12;
        if(!empty($additional_settings)){
            
            foreach ($additional_settings as $key=>$valuename){
                if($additional_settings[$key]['type']!="html"){
                    
                    $columns_list_defult_user_report[$index_count]['title'] = $additional_settings[$key]['name'];
                    $columns_list_defult_user_report[$index_count]['type'] = 'string';
                    $columns_list_defult_user_report[$index_count]['key'] = $site_prefix.$additional_settings[$key]['key'];
                  
                    
                }
                $index_count++;  
            
            }
        
            
        }
     
     
    if(!empty($result_task_array_list)){
        //asort($result_task_array_list['profile_fields']);
         foreach ($result_task_array_list as $taskIndex => $taskObject) {
           
                                    $tasksID=$taskObject->ID;
                                    $profile_field_settings = [];
                                    $value_value = get_post_meta( $tasksID, 'value' , false);
                                    $value_unique = get_post_meta( $tasksID, 'unique' , false);
                                    $value_class = get_post_meta( $tasksID, 'class' , false);
                                    $value_after = get_post_meta( $tasksID, 'after', false);
                                    $value_required = get_post_meta( $tasksID, 'required' , false);
                                    $value_allow_tags = get_post_meta( $tasksID, 'allow_tags' , false);
                                    $value_add_to_profile = get_post_meta( $tasksID, 'add_to_profile' , false);
                                    $value_allow_multi = get_post_meta( $tasksID, 'allow_multi', false);
                                    $value_label = get_post_meta( $tasksID, 'label' , false);
                                    $value_type = get_post_meta( $tasksID, 'type' , false);
                                    $value_lin_url = get_post_meta( $tasksID, 'link_url' , false);
                                    $value_linkname = get_post_meta( $tasksID, 'linkname', false);
                                    $value_attr = get_post_meta( $tasksID, 'duedate', false);
                                    
                                   
                                    
                                    
                                    $value_taskattrs = get_post_meta( $tasksID, 'taskattrs', false);
                                    $value_taskMWC = get_post_meta( $tasksID, 'taskMWC' , false);
                                    $value_taskMWDDP = get_post_meta( $tasksID, 'taskMWDDP' , false);
                                    $value_roles = get_post_meta( $tasksID, 'roles' , false);
                                    $value_usersids = get_post_meta( $tasksID, 'usersids' , false);
                                    $value_descrpition = get_post_meta( $tasksID, 'descrpition', false);
                                    $value_key = get_post_meta( $tasksID, 'key', false);
                                    $profile_field_name  = $value_key[0];
                                    $profile_field_settings['value'] = $value_value[0];
                                    $profile_field_settings['unique'] = $value_unique[0];
                                    $profile_field_settings['class'] =$value_class[0];
                                    $profile_field_settings['after'] =$value_after[0];
                                    $profile_field_settings['required'] =$value_required[0];
                                    $profile_field_settings['allow_tags'] =$value_allow_tags[0];
                                    $profile_field_settings['add_to_profile'] =$value_add_to_profile[0];
                                    $profile_field_settings['allow_multi'] =$value_allow_multi[0];
                                    $profile_field_settings['label'] =$value_label[0];
                                    $profile_field_settings['type'] =$value_type[0];
                                    $profile_field_settings['lin_url'] =$value_lin_url[0];
                                    $profile_field_settings['linkname'] =$value_linkname[0];
                                    $profile_field_settings['attrs'] =$value_attr[0];
                                    $profile_field_settings['taskattrs'] =$value_taskattrs[0];
                                    $profile_field_settings['taskMWC'] =$value_taskMWC[0];
                                    $profile_field_settings['taskMWDDP'] =$value_taskMWDDP[0];
                                    $profile_field_settings['roles'] =$value_roles[0];
                                    $profile_field_settings['usersids'] =$value_usersids[0];
                                    $profile_field_settings['descrpition'] =$value_descrpition[0];
                                    
                                  
                                    
                                    
                                    if($profile_field_settings['type'] == "select-2"){
                                        
                                            $getarraysValue = get_post_meta( $tasksID, 'options', false);
                                            
                                            if(!empty($getarraysValue[0])){

                                                
                                                 $profile_field_settings['options'] =$getarraysValue[0];
                                                 
                                             }
                                   }
        
            if ($profile_field_settings['type'] == 'datetime') {
                
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'];
                $columns_list_defult_user_report[$index_count]['type'] = 'date';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name;
                $index_count++;
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'].' Datetime';
                $columns_list_defult_user_report[$index_count]['type'] = 'date';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name.'_datetime';
                $index_count++;
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'].' Status';
                $columns_list_defult_user_report[$index_count]['type'] = 'string';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name.'_status';
                $index_count++;
                
                
                
            } else if ($profile_field_settings['type'] == 'color') {
                
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'];
                $columns_list_defult_user_report[$index_count]['type'] = 'html';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name;
                $index_count++;
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'].' Datetime';
                $columns_list_defult_user_report[$index_count]['type'] = 'date';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name.'_datetime';
                $index_count++;
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'].' Status';
                $columns_list_defult_user_report[$index_count]['type'] = 'string';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name.'_status';
                $index_count++;
            
                
            } else if ($profile_field_settings['type'] == 'text' || $profile_field_settings['type'] == 'textarea') {
                
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'];
                $columns_list_defult_user_report[$index_count]['type'] = 'string';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name;
                $index_count++;
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'].' Datetime';
                $columns_list_defult_user_report[$index_count]['type'] = 'date';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name.'_datetime';
                $index_count++;
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'].' Status';
                $columns_list_defult_user_report[$index_count]['type'] = 'string';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name.'_status';
                $index_count++;
                
            }  else {
                
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'];
                $columns_list_defult_user_report[$index_count]['type'] = 'string';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name;
                $index_count++;
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'].' Datetime';
                $columns_list_defult_user_report[$index_count]['type'] = 'date';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name.'_datetime';
                $index_count++;
                $columns_list_defult_user_report[$index_count]['title'] = $profile_field_settings['label'].' Status';
                $columns_list_defult_user_report[$index_count]['type'] = 'string';
                $columns_list_defult_user_report[$index_count]['key'] = $profile_field_name.'_status';
                $index_count++;
            }
        
            
            
    }
    }

        


         $site_url  = get_site_url();
        foreach ($columns_list_defult_user_report as $col_keys => $col_keys_title) {


            $colums_array_data['title'] = $columns_list_defult_user_report[$col_keys]['title'];
            $colums_array_data['type'] = $columns_list_defult_user_report[$col_keys]['type'];
            $colums_array_data['key'] = $columns_list_defult_user_report[$col_keys]['key'];
            array_push($columns_headers, $colums_array_data);
        }
        $query = "SELECT DISTINCT ID as user_id FROM " . $wpdb->users;
        $result_user_id = $wpdb->get_results($query);
        if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }
        $get_all_roles = get_option($get_all_roles_array);
//        foreach ($columns_list_defult_user_report_postmeta as $col_keys => $col_keys_title) {
//
//
//            $colums_array_data['title'] = $columns_list_defult_user_report_postmeta[$col_keys]['title'];
//            $colums_array_data['data'] = $columns_list_defult_user_report_postmeta[$col_keys]['title'];
//            $colums_array_data['type'] = $columns_list_defult_user_report_postmeta[$col_keys]['type'];
//
//            array_push($columns_headers, $colums_array_data);
//        }
        
       // echo '<pre>';
      //  print_r($get_all_roles);
       // echo $get_all_roles['sliver']['name'];exit;
       foreach ($authors as $aid) {

            $user_data = get_userdata($aid->ID);
            $demo = new FloorPlanManager();
            $AllBoothsList = $demo->getAllbooths();
            
            
            $thisBoothNumber ="";
            
            if(!empty($AllBoothsList)){
            
            foreach ($AllBoothsList as $boothIndex=>$boothValue ){
                
                if($boothValue['bootheOwnerID'] == $aid->ID){
                    
                    
                    $thisBoothNumber = $boothValue['boothNumber'];
                    
                }
                
                
            }
            }else{
                $thisBoothNumber = "";
            }
          // echo $user_data->roles[0].'</br>';
            $all_meta_for_user = get_user_meta($aid->ID);
            
            if (!empty($all_meta_for_user) && !in_array("administrator", $user_data->roles)) {

                
                if (!empty($all_meta_for_user[$site_prefix.'custom_login_time_as_site'][0])) {


                    $login_date = date('d-M-Y H:i:s', $all_meta_for_user[$site_prefix.'custom_login_time_as_site'][0]);
                    // echo strtotime($login_date_time);exit;
                    if ($usertimezone > 0) {
                        $login_date_time = (new DateTime($login_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                    } else {
                        $login_date_time = (new DateTime($login_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                    }
                    $timestamp = strtotime($login_date_time) * 1000;
                    // echo $timestamp; 
                    // echo date('m/d/Y H:i:s', $timestamp);exit;
                } else {
                    $timestamp = "";
                }
                if (!empty($all_meta_for_user[$site_prefix.'convo_welcomeemail_datetime'][0])) {


                    $last_send_welcome_email = date('d-M-Y H:i:s', $all_meta_for_user[$site_prefix.'convo_welcomeemail_datetime'][0] / 1000);

                    if ($usertimezone > 0) {
                        $last_send_welcome_date_time = (new DateTime($last_send_welcome_email))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                    } else {
                        $last_send_welcome_date_time = (new DateTime($last_send_welcome_email))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                    }
                    $last_send_welcome_timestamp = strtotime($last_send_welcome_date_time) * 1000;
                    // echo $timestamp; 
                    // echo date('m/d/Y H:i:s', $timestamp);exit;
                } else {
                    $last_send_welcome_timestamp = "";
                }
                $company_name = $all_meta_for_user[$site_prefix.'company_name'][0];
                $column_row['Action'] = '<div style="width: 140px !important;"class = "hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><a href="'.$site_url.'/edit-user/?sponsorid=' . $aid->ID . '" target="_blank" data-toggle="tooltip" title="Edit User Profile"><i  class="hi-icon fusion-li-icon fa fa-pencil-square-o" ></i></a><a  target="_blank" href="'.$site_url.'/edit-sponsor-task/?sponsorid=' . $aid->ID . '" data-toggle="tooltip" title="User Tasks"><i class="hi-icon fusion-li-icon fa fa-th-list" ></i></a><a onclick="new_userview_profile(this)" id="' . $unique_id . '" name="viewprofile"   title="View Profile" data-toggle="tooltip" ><i class="hi-icon fusion-li-icon fa fa-eye" ></i></a><a onclick="delete_sponsor_meta(this)" id="' . $aid->ID . '" name="delete-sponsor" data-toggle="tooltip"  title="Remove User" ><i class="hi-icon fusion-li-icon fa fa-times-circle" ></i></a></div>';

                $unique_id++;

                
                $column_row['Company Name'] = $company_name;
                $column_row['Level'] = $get_all_roles[$user_data->roles[0]]['name'];
                $column_row['Last login'] = $timestamp;

                $column_row['First Name'] = $all_meta_for_user[$site_prefix.'first_name'][0];//$user_data->first_name;
                $column_row['Last Name']  = $all_meta_for_user[$site_prefix.'last_name'][0];//$user_data->last_name;
               
                $column_row['Email'] = $user_data->user_email;
                $column_row['Welcome Email Sent On'] = $last_send_welcome_timestamp;
                $column_row['Status'] = $all_meta_for_user[$site_prefix.'selfsignupstatus'][0];
            $column_row['Booth Number'] = $thisBoothNumber;//$all_meta_for_user[$site_prefix.'exhibitor_map_dynamics_ID'][0];
                
//                if(!empty($all_meta_for_user['user_profile_url'][0])){
//                    
//                    $image_src = '<img src="'.$all_meta_for_user['user_profile_url'][0].'" width="100" />';
//                }else{
//                    $image_src = '';
//                }
                
                $column_row['User Company Logo'] = $all_meta_for_user[$site_prefix.'user_profile_url'][0];
                $column_row['User ID'] = $aid->ID;
                if (!empty($additional_settings)) {

                    foreach ($additional_settings as $key => $valuename) {
                        
                        
                        $additionfield = $additional_settings[$key]['key'];
                        $column_row[$additional_settings[$key]['name']] = $all_meta_for_user[$site_prefix.$additionfield][0];
                    }
                }
            
             foreach ($result_task_array_list as $taskIndex => $taskObject) {
           
                                    $tasksID=$taskObject->ID;
                                    $profile_field_settings = [];
                                    $value_value = get_post_meta( $tasksID, 'value' , false);
                                    $value_unique = get_post_meta( $tasksID, 'unique' , false);
                                    $value_class = get_post_meta( $tasksID, 'class' , false);
                                    $value_after = get_post_meta( $tasksID, 'after', false);
                                    $value_required = get_post_meta( $tasksID, 'required' , false);
                                    $value_allow_tags = get_post_meta( $tasksID, 'allow_tags' , false);
                                    $value_add_to_profile = get_post_meta( $tasksID, 'add_to_profile' , false);
                                    $value_allow_multi = get_post_meta( $tasksID, 'allow_multi', false);
                                    $value_label = get_post_meta( $tasksID, 'label' , false);
                                    $value_type = get_post_meta( $tasksID, 'type' , false);
                                    $value_lin_url = get_post_meta( $tasksID, 'link_url' , false);
                                    $value_linkname = get_post_meta( $tasksID, 'linkname', false);
                                    $value_attr = get_post_meta( $tasksID, 'duedate', false);
                                    
                                   
                                    
                                    
                                    $value_taskattrs = get_post_meta( $tasksID, 'taskattrs', false);
                                    $value_taskMWC = get_post_meta( $tasksID, 'taskMWC' , false);
                                    $value_taskMWDDP = get_post_meta( $tasksID, 'taskMWDDP' , false);
                                    $value_roles = get_post_meta( $tasksID, 'roles' , false);
                                    $value_usersids = get_post_meta( $tasksID, 'usersids' , false);
                                    $value_descrpition = get_post_meta( $tasksID, 'descrpition', false);
                                    $value_key = get_post_meta( $tasksID, 'key', false);
                                    $profile_field_name  = $value_key[0];
                                    $profile_field_settings['value'] = $value_value[0];
                                    $profile_field_settings['unique'] = $value_unique[0];
                                    $profile_field_settings['class'] =$value_class[0];
                                    $profile_field_settings['after'] =$value_after[0];
                                    $profile_field_settings['required'] =$value_required[0];
                                    $profile_field_settings['allow_tags'] =$value_allow_tags[0];
                                    $profile_field_settings['add_to_profile'] =$value_add_to_profile[0];
                                    $profile_field_settings['allow_multi'] =$value_allow_multi[0];
                                    $profile_field_settings['label'] =$value_label[0];
                                    $profile_field_settings['type'] =$value_type[0];
                                    $profile_field_settings['lin_url'] =$value_lin_url[0];
                                    $profile_field_settings['linkname'] =$value_linkname[0];
                                    $profile_field_settings['attrs'] =$value_attr[0];
                                    $profile_field_settings['taskattrs'] =$value_taskattrs[0];
                                    $profile_field_settings['taskMWC'] =$value_taskMWC[0];
                                    $profile_field_settings['taskMWDDP'] =$value_taskMWDDP[0];
                                    $profile_field_settings['roles'] =$value_roles[0];
                                    $profile_field_settings['usersids'] =$value_usersids[0];
                                    $profile_field_settings['descrpition'] =$value_descrpition[0];
                                    
                                  
                                    
                                    
                                    if($profile_field_settings['type'] == "select-2"){
                                        
                                            $getarraysValue = get_post_meta( $tasksID, 'options', false);
                                            
                                            if(!empty($getarraysValue[0])){

                                                
                                                 $profile_field_settings['options'] =$getarraysValue[0];
                                                 
                                             }
                                   }
        
         
               
                if ($profile_field_settings['type'] == 'color') {
                    $file_info = unserialize($all_meta_for_user[$profile_field_name][0]);
                   
                   
                    if (!empty($file_info)) {
                        $column_row[$profile_field_settings['label']] = '<a href="'.$base_url.'/wp-content/plugins/EGPL/download-lib.php?cname='.$company_name.'&userid=' . $aid->ID . '&fieldname=' . $profile_field_name . '" >Download</a>';
                       // $column_row[$profile_field_settings['label']] = '';
                        
                        
                    } else {
                        $column_row[$profile_field_settings['label']] = '';
                    }
                    if (!empty($all_meta_for_user[$profile_field_name . '_datetime'][0])) {
                        if (strpos($all_meta_for_user[$profile_field_name . '_datetime'][0], 'AM') !== false) {
                            

                            $datevalue = str_replace(":AM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                            
                            
                        } else {
                            $datevalue = str_replace(":PM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                        }
                    } else {
                        $datemy = "";
                    }
                    $column_row[$profile_field_settings['label'].' Datetime'] =$datemy;
                    $column_row[$profile_field_settings['label'].' Status'] = $all_meta_for_user[$profile_field_name.'_status'][0];
                    
                   
                    
                    
                } else {

                 
                      if ($profile_field_settings['type'] == 'text' || $profile_field_settings['type'] == 'textarea') {
                             

                        $column_row[$profile_field_settings['label']] = $all_meta_for_user[$profile_field_name][0];
                        if (!empty($all_meta_for_user[$profile_field_name . '_datetime'][0])) {
                            if (strpos($all_meta_for_user[$profile_field_name . '_datetime'][0], 'AM') !== false) {
                            

                            $datevalue = str_replace(":AM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                            
                            
                        } else {
                            $datevalue = str_replace(":PM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                        }
                    } else {
                        $datemy = "";
                    }
                            $column_row[$profile_field_settings['label'].' Datetime'] = $datemy;
                            $column_row[$profile_field_settings['label'].' Status'] = $all_meta_for_user[$profile_field_name . '_status'][0];
                            
                            
                       

                       
                    }  else if ($profile_field_settings['type'] == 'select') {

                            $column_row[$profile_field_settings['label']] =  $all_meta_for_user[$profile_field_name][0];
                          
                           if (!empty($all_meta_for_user[$profile_field_name . '_datetime'][0])) {
                        if (strpos($all_meta_for_user[$profile_field_name . '_datetime'][0], 'AM') !== false) {
                            

                            $datevalue = str_replace(":AM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                            
                            
                        } else {
                            $datevalue = str_replace(":PM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                        }
                            } else {
                                $datemy = "";
                            }
                            $column_row[$profile_field_settings['label'].' Datetime'] =$datemy;
                            $column_row[$profile_field_settings['label'].' Status'] = $all_meta_for_user[$profile_field_name.'_status'][0];
                          
                        }else if ($profile_field_settings['type'] == 'select-2') {
                            $column_row[$profile_field_settings['label']] =  $all_meta_for_user[$profile_field_name][0];
                            if (!empty($all_meta_for_user[$profile_field_name . '_datetime'][0])) {
                        if (strpos($all_meta_for_user[$profile_field_name . '_datetime'][0], 'AM') !== false) {
                            

                            $datevalue = str_replace(":AM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                            
                            
                        } else {
                            $datevalue = str_replace(":PM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                        }
                    } else {
                        $datemy = "";
                    }
                            $column_row[$profile_field_settings['label'].' Datetime']  =$datemy;
                            $column_row[$profile_field_settings['label'].' Status'] = $all_meta_for_user[$profile_field_name.'_status'][0];
                            
                          
                        }
                        else {
                           

                             $column_row[$profile_field_settings['label']] = $all_meta_for_user[$profile_field_name][0];
                            if (!empty($all_meta_for_user[$profile_field_name . '_datetime'][0])) {
                        if (strpos($all_meta_for_user[$profile_field_name . '_datetime'][0], 'AM') !== false) {
                            

                            $datevalue = str_replace(":AM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                            
                            
                        } else {
                            $datevalue = str_replace(":PM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                        }
                    } else {
                        $datemy = "";
                    }
                             $column_row[$profile_field_settings['label'].' Datetime'] =$datemy;
                             $column_row[$profile_field_settings['label'].' Status'] = $all_meta_for_user[$profile_field_name.'_status'][0];
                            
                    
                            
                        }
                    } 
              //  echo '<pre>';
              //  print_r($myNewArray);exit;
            }

                array_push($columns_rows_data, $column_row);
            }
           
          
           
        }

        $orderreport_all_col_rows_data['columns'] = $columns_headers;
        $orderreport_all_col_rows_data['data'] = $columns_rows_data;
        
        
       
       // print_r($columns_headers); exit;
        contentmanagerlogging_file_upload($lastInsertId, serialize($orderreport_all_col_rows_data));
        
        
       // echo '<pre>';
       // print_r($columns_rows_data);exit;
        echo json_encode($columns_rows_data) . '//' . json_encode($columns_headers);
    } catch (Exception $e) {

        contentmanagerlogging_file_upload($lastInsertId, serialize($e));

        return $e;
    }

    die();
}

function decline_selfsignuser_metas($user_id){
    
    try{
    
    $all_meta_for_user = get_user_meta( $user_id );
    $all_meta_for_user['user_info'] = get_userdata( $user_id );
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    
    
    $lastInsertId = contentmanagerlogging('Declined User',"Admin Action",serialize($all_meta_for_user),$user_ID,$user_info->user_email,"Declined");
    update_user_option( $user_id, 'selfsignupstatus', 'Declined' );
    $send_email_type = 'declined';
    selfsign_registration_emails($user_id,$send_email_type);
    //send decline email user
   
    //print_r($responce);
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
       die();
      
 }
  die();   
}

function approve_selfsign_user($user_id,$user_assignrole){
    
    try{
        
    global $wpdb;
    $site_prefix = $wpdb->get_blog_prefix();
    
    $all_meta_for_user = get_user_meta( $user_id );
    
    $all_meta_for_user['user_info'] = get_userdata( $user_id );
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    
    $floorplan_keys = get_option( 'ContenteManager_Settings' );
    $mapapikey = $floorplan_keys['ContentManager']['mapapikey'];
    $mapsecretkey = $floorplan_keys['ContentManager']['mapsecretkey'];
    
    
    $lastInsertId = contentmanagerlogging('Approved Self Signed User',"Admin Action",serialize($all_meta_for_user),$user_ID,$user_info->user_email,"Declined");
    update_user_option(  $user_id ,'selfsignupstatus','Approved');
    
   
    $t=time();
   
  
    
    update_user_option(  $user_id ,'convo_welcomeemail_datetime', $t*1000 );
    
    
    $user_info_approved = get_userdata($user_id);
    
    $u = new WP_User($user_id);
    $u->set_role( $user_assignrole );
    
    if(!empty($mapapikey) && !empty($mapsecretkey)){
          
          $data_array=array(
            'company'=>$all_meta_for_user[$site_prefix.'company_name'][0],
            'email'=>$all_meta_for_user['user_info']->user_email,
            'first_name'=>$all_meta_for_user[$site_prefix.'first_name'][0],
            'last_name'=>$all_meta_for_user[$site_prefix.'last_name'][0],
            'image'=>$all_meta_for_user[$site_prefix.'user_profile_url'][0]
              
          ) ;
          
        $request_for_sync_map_dynamics = contentmanagerlogging('Sync to map dynamics Selfsign User',"Admin Action",serialize($data_array),$user_ID,$user_info->user_email,"pre_action_data");
        $result = insert_exhibitor_map_dynamics($data_array) ;
        
        contentmanagerlogging_file_upload ($request_for_sync_map_dynamics,serialize($result));
       
        
        
        if($result->status == 'success'){
            
             update_user_option($user_id, 'exhibitor_map_dynamics_ID', $result->results->Exhibitor_ID);
          
             $mapdynamicsstatus = '';
            
        }else{
            
            $sync_map_dynamics_message = $result->status_details;
          
            $mapdynamicsstatus = '';
        }
        
        
       
       }else{
           
           $mapdynamicsstatus = '';
           
       }
    custome_email_send($user_id,$user_info_approved->user_email);
    //send Approved email user;
    //send sync call
    echo $mapdynamicsstatus;
    //print_r($responce);
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
       die();
      
 }
  die();   
}

function selfsign_registration_emails($user_id,$send_email_type){
        
        require_once 'Mandrill.php';
    
        $user = get_userdata($user_id);
        $email = $user->user_email;
        global $wpdb;
        $site_prefix = $wpdb->get_blog_prefix();
    
        $all_meta_for_user = get_user_meta( $user_id );
        $site_url = get_option('siteurl' );
        $site_title=get_option( 'blogname' );
       
        //$settitng_key='AR_Contentmanager_Email_Template_welcome';
        //$sponsor_info = get_option($settitng_key);
        
        $sponsor_info['selfsign_registration_request_email']['selfsignfromname'] = $site_title;
        $sponsor_info['selfsign_registration_request_email']['selfsignsubject'] = 'Registration Application Received for '.$site_title;
        $sponsor_info['selfsign_registration_request_email']['selfsignboday'] = '<p>Hi '.$all_meta_for_user[$site_prefix.'first_name'][0].'  '.$all_meta_for_user[$site_prefix.'last_name'][0].',</p><p>Thank you for submitting your reservation form for the <strong>'.$site_title.'</strong>. We are currently reviewing your form. You will receive an email with login credentials to the <strong>'.$site_title.'</strong> portal once the review is complete.</p><p>Thank You!</p>';

        $sponsor_info['selfsign_registration_declined_email']['declinedfromname'] = $site_title;
        $sponsor_info['selfsign_registration_declined_email']['declinedsubject'] = 'Registration Application Declined for '.$site_title;
        $sponsor_info['selfsign_registration_declined_email']['declinedboday'] = '<p>Dear '.$all_meta_for_user[$site_prefix.'first_name'][0].'  '.$all_meta_for_user[$site_prefix.'last_name'][0].',</p><p>Your registration application on <strong>'.$site_title.'</strong>  has been declined. If you have any further queries, please contact us: <strong>'.$site_url.'</strong> </p><p>Thanks</p>';

        $oldvalues = get_option( 'ContenteManager_Settings' );
        $formemail = $oldvalues['ContentManager']['formemail'];
        
        if(empty($formemail)){
    
            $formemail = 'noreply@convospark.com';
        
        }
        if($send_email_type == 'declined'){
            
            $subject_body = $sponsor_info['selfsign_registration_declined_email']['declinedsubject'];
            $body_message=$sponsor_info['selfsign_registration_declined_email']['declinedboday'];
            $formemailandtitle = $sponsor_info['selfsign_registration_declined_email']['declinedfromname'];
            
        }else{
            
            $subject_body = $sponsor_info['selfsign_registration_request_email']['selfsignsubject'];
            $body_message=$sponsor_info['selfsign_registration_request_email']['selfsignboday'];
            $formemailandtitle = $sponsor_info['selfsign_registration_request_email']['selfsignfromname']; 
            
        }
       
     
          
        $welcomememailreplayto = get_option('AR_Contentmanager_Email_Template_welcome');
        $replaytoemailadd = $welcomememailreplayto['welcome_email_template']['replaytoemailadd'];
        $oldvalues = get_option( 'ContenteManager_Settings' );
        $mandrillKeys = $oldvalues['ContentManager']['mandrill'];
        $to_message_array[]=array('email'=>$email,'name'=>$all_meta_for_user[$site_prefix.'first_name'][0],'type'=>'to');
      
     
        $mandrill = new Mandrill($mandrillKeys);
        
      
        $message = array(

             'html' => $body_message,
             'text' => '',
             'subject' => $subject_body,
             'from_email' => $formemail,
             'from_name' => $formemailandtitle,
             'to' => $to_message_array,
             'headers' => array('Reply-To' => $formemail),
             'bcc_address'=>$replaytoemailadd,
             'track_opens' => true,
             'track_clicks' => true


         );
     
    $async = false;
    $ip_pool = 'Main Pool';
  
    $result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
  
    
}



 
 function  selecteduser_getuploadfiles_download($selected_task_data){
    
    try{
        
       global  $wpdb;
       $selected_task_key = $selected_task_data['selectedtaskkey'];
       $user_ids_array = json_decode(stripslashes($selected_task_data['selecteduserids']), true);
       $user_ID = get_current_user_id();
       $user_info = get_userdata($user_ID);
       $lastInsertId = contentmanagerlogging('Selected Bulk Download',"Admin Action",serialize($selected_task_data),$user_ID,$user_info->user_email,"pre_action_data");
       $site_prefix = $wpdb->get_blog_prefix();
       
        foreach ($user_ids_array as $kesy=>$ids){
            
            $file_url = get_user_meta($ids, $selected_task_key);
            $user_company_name = get_user_meta($ids, $site_prefix.'company_name', true);

            if (!empty($file_url[0]['file'])) {

                $user_file_list[] = $user_company_name . '*' . $file_url[0]['file'];
            }
        }
        echo   json_encode($user_file_list);
       
       
       
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
       die();
      
 }
  die();   
}
function  check_useremail_exist($useremail){
    
    try{
        
        
       $useremail = $useremail['currentemail'];
       $user_id = username_exists($useremail);
       $user_ID = get_current_user_id();
       $user_info = get_userdata($user_ID);
       $lastInsertId = contentmanagerlogging('Check Email Status',"Admin Action",serialize($useremail),$user_ID,$user_info->user_email,"pre_action_data");
       $current_blog_id = get_current_blog_id();
       $user_blogs = get_blogs_of_user( $user_id );
       
       
     
       
       if (!$user_id and email_exists($email) == false) {
          echo 'This email address doesnt exist';
       }else{
           
           $user_status_for_this_site = 'not_exist';
           foreach ($user_blogs as $blog_id) { 
               
               $fetchuserdatauserblogID = $blog_id->userblog_id;
               if($blog_id->userblog_id == $current_blog_id ){
                   
                   $user_status_for_this_site = 'alreadyexist';
                   break;
               }
               
           }
           
        
          if($user_status_for_this_site == 'alreadyexist'){
              
              echo 'User already exists for this site.'; 
          }else{
              
              $data_array['first_name'] = get_user_meta($user_id, 'wp_'.$fetchuserdatauserblogID.'_first_name', true);
              $data_array['last_name'] =  get_user_meta($user_id, 'wp_'.$fetchuserdatauserblogID.'_last_name', true);
              $data_array['company_name'] = get_user_meta($user_id, 'wp_'.$fetchuserdatauserblogID.'_company_name', true);
              $Srole = get_user_meta($user_id, 'wp_'.$fetchuserdatauserblogID.'_capabilities', true);
              $rolename = array_keys($Srole);
              $data_array['role_name'] = $rolename[0]; //;get_user_meta($user_id, 'wp_'.$fetchuserdatauserblogID.'_capabilities', true);
                      
              echo json_encode($data_array);        
              
              
              
          }
        }
       
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
       die();
      
 }
  die();   
}

function updateuserforthissite($userinfo){
    
     try{
      
        
         
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('User added to this event',"Admin Action",serialize($userinfo),''.$user_ID,$user_info->user_email,"pre_action_data");
        $newemail = $userinfo['newemailaddress'];
        $userrole = $userinfo['userrole'];
        
        global $wpdb;
        $site_prefix = $wpdb->get_blog_prefix();
    
        
        
        $welcome_email_status = $userinfo['welcomememailstatus'];
        $welcome_selected_email_template = $userinfo['selectedtemplateemailname'];
        
        $user_id = username_exists($newemail);
        $user_data = get_userdata($user_id);
        $current_blog_id = get_current_blog_id();
       // send mapdynmis calls 
        $all_meta_for_user = get_user_meta( $user_id );
        $oldvalues = get_option( 'ContenteManager_Settings' );
        $mapapikey = $oldvalues['ContentManager']['mapapikey'];
        $mapsecretkey = $oldvalues['ContentManager']['mapsecretkey'];
        $company_name = get_user_meta($user_id, 'company_name', true);
        
        
        if(!empty($mapapikey) && !empty($mapsecretkey)){
          
          $data_array=array(
            'company'=>$company_name,
            'email'=>$newemail,
            'first_name'=>$all_meta_for_user[$site_prefix.'first_name'][0],
            'last_name'=>$all_meta_for_user[$site_prefix.'last_name'][0],
            'image'=>''
              
          ) ;
          
        $request_for_sync_map_dynamics = contentmanagerlogging('Sync to map dynamics',"Admin Action",serialize($data_array),$user_ID,$user_info->user_email,"pre_action_data");
        $result = insert_exhibitor_map_dynamics($data_array) ;
        contentmanagerlogging_file_upload ($request_for_sync_map_dynamics,serialize($result));
       
        if($result->status == 'success'){
            
             update_user_option($user_id, 'exhibitor_map_dynamics_ID', $result->results->Exhibitor_ID);
             $mapdynamicsstatus['synctofloorplan'] = '';
            
        }else{
            
            $sync_map_dynamics_message = $result->status_details;
            $mapdynamicsstatus['synctofloorplan'] = '';
        }
        
       }else{
           
           $mapdynamicsstatus['synctofloorplan'] = '';
           
       }
        
        $mapdynamicsstatus['useradded'] ="updated successfully";
        add_user_to_blog($current_blog_id, $user_data->ID, $userrole);
        if($welcome_email_status == 'checked'){
                    custome_email_send($user_data->ID,$user_data->user_email,$welcome_selected_email_template);
        }
        contentmanagerlogging_file_upload ($lastInsertId,serialize('updated successfully'));
        
        
        echo json_encode($mapdynamicsstatus);
        die();
     }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
       die();
     }
    
    
    
}
