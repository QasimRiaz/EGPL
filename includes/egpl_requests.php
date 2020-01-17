<?php 

if($_GET['contentManagerRequest'] == "bulkimportmappingcreaterequest") {        
    require_once('../../../wp-load.php');
    
    $importfileurl = $_POST['uploadedsheeturl'];
    $col_mapping_datarray = json_decode(stripslashes($_POST['mappingfielddata']), true);
    $welcome_email_status = $_POST['welcomeemailstatus'];
    $welcome_email_template_name = $_POST['seletwelcomeemailtemplate'];
    
    $responce_createdusers = createuserlist_after_mapping($importfileurl,$col_mapping_datarray,$welcome_email_status,$welcome_email_template_name);
     echo json_encode($responce_createdusers);
    die();
   
  
}else if($_GET['contentManagerRequest'] == "getuseremailids") {        
    require_once('../../../wp-load.php');
    $fields = array( 'ID','user_email' );
    $args = array(
        'role__not_in' => array('administrator'),
        'fields' => $fields,
    );
     
    
     $get_all_ids = get_users($args);
    
    $indexplus = 0;
    
    foreach ($get_all_ids as $user) {
        
            $getuserresult[$indexplus]['id'] = $user->ID;
            $getuserresult[$indexplus]['text'] = $user->user_email;
            $indexplus++;
    }
    echo json_encode($getuserresult);
    die();
   
  
}else if($_GET['contentManagerRequest'] == "checkwelcomealreadysend") {        
    require_once('../../../wp-load.php');
    
    checkwelcomealreadysend($_POST);
   
  
}else if($_GET['contentManagerRequest'] == "changeuseremailaddress") {        
    require_once('../../../wp-load.php');
    
    changeuseremailaddress($_POST);
   
  
}else if($_GET['contentManagerRequest'] == "editrolekey") {        
    require_once('../../../wp-load.php');
    
    editrolename($_POST);
   
  
}else if($_GET['contentManagerRequest'] == "roleassignnewtasks") {        

    require_once('../../../wp-load.php');
    
    roleassignnewtasks($_POST);
   
  
}else if ($_GET['contentManagerRequest'] == 'insertmapdynamicsuser') {
    
     require_once('../../../wp-load.php');
     try{
        
     
     
      
        global $wpdb;
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('Insert Map Dynamics User',"Admin Action","",$user_ID,$user_info->user_email,"pre_action_data");
        $site_prefix = $wpdb->get_blog_prefix();
        $userid = $_POST['userid'];
        $requestcount = $_POST['requestcount'];
        $userdata = get_userdata($userid);
        $all_meta_for_user = get_user_meta($userid);
       
        
        
        
        
        if(!empty($all_meta_for_user[$site_prefix.'exhibitor_map_dynamics_ID'][0])){
            
            $data_array=array(
            'company'=>$all_meta_for_user[$site_prefix.'company_name'][0],
            'email'=>$userdata->user_email,
            'first_name'=>$all_meta_for_user[$site_prefix.'first_name'][0],
            'last_name'=>$all_meta_for_user[$site_prefix.'last_name'][0],
            'image'=>$all_meta_for_user[$site_prefix.'user_profile_url'][0],
            'exhibitor_id'=>$all_meta_for_user[$site_prefix.'exhibitor_map_dynamics_ID'][0]  
            ) ;
            $result = update_exhibitor_map_dynamics($data_array);
            if($result->status == 'success'){
            
             $data_array['status'] = $result->status;
             $data_array['result'] = '';
             $data_array['Exhibitor_ID'] = $result->results->Exhibitor_ID;
             
            
         
            }else{
                $data_array['status'] = $result->status;
                $data_array['result'] = $result->status_details;
                $data_array['Exhibitor_ID'] = '';
            }
            
        
        }else{
            
           $data_array=array(
            'company'=>$all_meta_for_user[$site_prefix.'company_name'][0],
            'email'=>$userdata->user_email,
            'first_name'=>$all_meta_for_user[$site_prefix.'first_name'][0],
            'last_name'=>$all_meta_for_user[$site_prefix.'last_name'][0],
            'image'=>$all_meta_for_user[$site_prefix.'user_profile_url'][0],
            
          ) ;
           $result = insert_exhibitor_map_dynamics($data_array);
           
           if($result->status == 'success'){
            
             $data_array['status'] = $result->status;
             $data_array['result'] = '';
             $data_array['Exhibitor_ID'] = $result->results->Exhibitor_ID;
             
             update_user_option($userdata->ID, 'exhibitor_map_dynamics_ID', $result->results->Exhibitor_ID);
         
            }else{
                
                $data_array['status'] = $result->status;
                $data_array['result'] = $result->status_details;
                $data_array['Exhibitor_ID'] = '';
            }
        }
        
      $data_array['requestcount'] =  $requestcount;
      
      contentmanagerlogging_file_upload ($lastInsertId,serialize($result)); 
      echo json_encode($data_array);
      die();
        
        
    }catch (Exception $e) {
       
      contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
 
 die();   
    
}else if ($_GET['contentManagerRequest'] == 'GetMapdynamicsApiKeys') {
    
    require_once('../../../wp-load.php');
    
    
    try{
        
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('Check Map Dynamics keys',"Admin Action","",$user_ID,$user_info->user_email,"pre_action_data");
        $oldvalues = get_option( 'ContenteManager_Settings' );
        $mapapikey = $oldvalues['ContentManager']['mapapikey'];
        $mapsecretkey = $oldvalues['ContentManager']['mapsecretkey'];
        
        if(!empty($mapapikey)&&!empty($mapsecretkey)){
            echo 'connected';
        }else{
            echo 'notconnected';
        }
      
        
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
 
 die();   
    
}else if ($_GET['contentManagerRequest'] == 'addnewadminuser') {
    require_once('../../../wp-load.php');
    
    
    try{
    $t=time();
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);  
    $lastInsertId = contentmanagerlogging('New Admin User',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
      
    $username = str_replace("+","",$_POST['username']);
    
    $email = $_POST['email'];
    $role =$_POST['sponsorlevel'];
    $welcomeemailtemplatename = $_POST['welcomeemailtempname'];
    $loggin_data=$_POST;
    
    
    unset($_POST['username']);
    unset($_POST['email']);
    unset($_POST['sponsorlevel']);
    unset($_POST['welcomeemailtempname']);
    
  //  print_r($_POST);
  
    $welcomeemail_status = $_POST['welcomeemailstatus'];
    $user_id = username_exists($username);
    
    $message['username'] = $username;
    $meta_array=$_POST;
   
    if (!$user_id and email_exists($email) == false) {
        
       $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
       $user_id = myregisterrequest_new_user($username, $email);//register_new_user( $username, $email );//wp_create_user($username, $random_password, $email);
    
       
       
    if ( ! is_wp_error( $user_id ) ) {
       $result=$user_id;
       $loggin_data['created_id']=$result;
       $message['user_id'] = $user_id;
       $message['msg'] = 'User created';
       $message['userrole'] = $role;
       
       add_user_to_blog(1, $user_id, $role);
       add_new_sponsor_metafields($user_id,$meta_array,$role);
     
            $useremail='';
           
            if($welcomeemail_status == 'send'){
                    $useremail='';
                    custome_email_send($user_id,$useremail,$welcomeemailtemplatename);
                    $t=time();
                    update_user_option($user_id, 'convo_welcomeemail_datetime', $t*1000);
            }  
            
       }else{
		  
           $userregister_responce = (array)$user_id;
		  
		   if(empty($userregister_responce['errors']['invalid_username'][0])){
			   
			   $message['msg'] = $userregister_responce['errors']['invalid_email'][0];
		   }else{
			   
			   $message['msg'] = $userregister_responce['errors']['invalid_username'][0];
		   }
           //$user_id->errors['invalid_username'][0];
       } 
       
    } else {
        
        $blogid = get_current_blog_id() ;
        if (add_user_to_blog($blogid, $user_id, $role)) {
                
                switch_to_blog($blogid);
                add_new_sponsor_metafields($user_id,$meta_array,$role);
                add_user_to_blog(1, $user_id, $role);
                
                
                
               // custome_email_send($user_id,$email);
                 if($welcomeemail_status == 'send'){
                    $useremail='';
                    custome_email_send($user_id,$email,$welcomeemailtemplatename);
                    $t=time();
                    update_user_option($user_id, 'convo_welcomeemail_datetime', $t*1000);
                }  
                
                
               
                $message['msg'] = 'User added to this blog.';
            } else {
                $message['msg'] = 'Failed to add user ' . $user_id . ' as ' . $role . ' to blog ' . $blogid . '.';
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
}else if ($_GET['contentManagerRequest'] == 'getavailablemergefields') {
    
    require_once('../../../wp-load.php');
    
    //$test = 'custome_task_manager_data';
    //$result = get_option($test);
    require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/egpl-custome-functions.php';
    $GetAllcustomefields = new EGPLCustomeFunctions();
    $additional_fields = $GetAllcustomefields->getAllcustomefields();
    
     function sortByOrder($a, $b) {
            return $a['fieldIndex'] - $b['fieldIndex'];
        }

    usort($additional_fields, 'sortByOrder');
    
    $keys_string[]= 'date';
    $keys_string[]= 'time';
    $keys_string[]= 'user_pass';
    $keys_string[]= 'site_url';
    $keys_string[]= 'site_title';
  
    $keys_string[]= 'user_login';
    
    
    
    foreach ($additional_fields as $key=>$value){ 
            
                        if($value['fieldType']!="display" && $value['fieldName'] != "Action" && $value['fieldType'] != "file" && $value['fieldType'] != "checkbox" ){
                        

                            $keys_string[] = str_replace(' ', '_', strtolower($value['fieldName']));
                         
                        
                    }}
    
    $bodytext_id = 'welcomebodytext';
//    if(!empty($result['custom_meta'])){
//    foreach($result['custom_meta'] as $key=>$value){
//      
//      if (preg_match('/task/',$key)){
//          
//      }else{
//     
//        $keys_string[]= $key; 
//      }
//      
//    }
//   }
    
 // echo '<pre>';
 // print_r( $result['sort_order'] );
    
    
    echo  json_encode($keys_string);
    
 
   die();

}else if ($_GET['contentManagerRequest'] == 'get_all_file_urls') {
    
    require_once('../../../wp-load.php');
    global $wpdb;
    $zip_folder_name=$_POST['colvalue'];
    
    $users = $wpdb->get_results( "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = '".$zip_folder_name."'" );
    
    
    foreach ( $users as $user ) {
        $file_url = get_user_meta($user->user_id, $zip_folder_name);
        $user_company_name = get_user_option('company_name',$user->user_id);
        
        if(!empty($file_url[0]['file'])){
            
            $user_file_list[] = $user_company_name.'*'.$file_url[0]['file'];
           
        }
        

        
    }
    
    
    echo   json_encode($user_file_list);
    
 
   die();

}else if ($_GET['contentManagerRequest'] == 'getpageContent') {
    
    require_once('../../../wp-load.php');
    
    $content_ID=$_POST['pageID'];
    $page_data = get_page($content_ID);
    $data_array['pagecontent'] = $page_data->post_content;
    $data_array['pagetitle'] = $page_data->post_title;
    
    
    echo   json_encode($data_array);
    
 
   die();

}else if ($_GET['contentManagerRequest'] == 'updatresource') {
    
    require_once('../../../wp-load.php');
    
    $resource_id=$_POST['idresource'];
   
    $resource_title = $_POST['resourcetitle'];
    $replacefileurl=$_FILES['replacefile'];
    
        
       $current_item = array(
        'ID'           => $resource_id,
        'post_title'   => $resource_title
     
    ); 
        
    $error = "ok";
    $post_id = wp_update_post( $current_item, true );
    if(!empty($replacefileurl)){
        
      
      $newupdatedfileurl = resource_file_upload($replacefileurl);
     
      $result = update_post_meta($post_id, 'excerpt', $newupdatedfileurl);  
        
    }
    
    if (is_wp_error($post_id)) {
	$errors = $post_id->get_error_messages();
	foreach ($errors as $error) {
		$error = $error;
	}
    }
    
    
    echo   json_encode($error);
    
 
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
    $email = $_POST['Semail'];
    $meta_array=$_POST;
    
    $oldvalues = get_option( 'ContenteManager_Settings' );
    
    require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/egpl-custome-functions.php';
        $GetAllcustomefields = new EGPLCustomeFunctions();
        $listOFcustomfieldsArray = $GetAllcustomefields->getAllcustomefields();
    
        foreach($listOFcustomfieldsArray as $fieldsKey=>$fieldsObject){
    
            $fieldTYpe = $fieldsObject['fieldType'];
            
            if($fieldTYpe == "file"){

               
                $fieldKey = $fieldsObject['fielduniquekey'];
                $uploadFilesubmit = $_FILES[$fieldKey];
               
                if(!empty($uploadFilesubmit)){

                    $uploadedFileURL = resource_file_upload($uploadFilesubmit);
                    
                    $meta_array[$fieldKey]=$uploadedFileURL;
                }
            }
        }    
    
    
    
    if(!empty($password)){ wp_set_password( $password, $userid );}
    
       //update_user_option($userid, 'user_profile_url', $picprofileurl);
       
       $mapapikey = $oldvalues['ContentManager']['mapapikey'];
       $mapsecretkey = $oldvalues['ContentManager']['mapsecretkey'];
       $userexhibitor_id = get_user_option('exhibitor_map_dynamics_ID',  $userid); 
       if(!empty($mapapikey) && !empty($mapsecretkey)){
          
        $request_for_sync_map_dynamics = contentmanagerlogging('Sync to map dynamics update',"Admin Action",serialize($data_array),$user_ID,$user_info->user_email,"pre_action_data");
        
        if(!empty($userexhibitor_id)){
            $data_array=array(
            'company'=>$meta_array['company_name'],
            'email'=>$email,
            'first_name'=>$meta_array['first_name'],
            'last_name'=>$meta_array['last_name'],
           
            'exhibitor_id'=>intval($userexhibitor_id)
              
          ) ;
            $result = update_exhibitor_map_dynamics($data_array) ;
           
        }else{
            $data_array=array(
            'company'=>$meta_array['company_name'],
            'email'=>$email,
            'first_name'=>$meta_array['first_name'],
            'last_name'=>$meta_array['last_name'],
            
            
              
          ) ; 
            $result = insert_exhibitor_map_dynamics($data_array) ;
            
        }
        contentmanagerlogging_file_upload ($request_for_sync_map_dynamics,serialize($result));
       
        
        
        if($result->status == 'success'){
            
             update_user_option($userid, 'exhibitor_map_dynamics_ID', $result->results->Exhibitor_ID);
         
             $mapdynamicsstatus = '';
            
        }else{
            
            $sync_map_dynamics_message = $result->status_details;
            $mapdynamicsstatus = '';
        }
        
        
       
       }else{
           
           $mapdynamicsstatus = '';
           
       }
       $result =  add_new_sponsor_metafields($userid,$meta_array,$role);
       $message['mapdynamicsstatus'] = $mapdynamicsstatus;
       contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
       echo json_encode($message);
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
        $message = [];
        $username = str_replace("+","",$_POST['username']);
        $email = $_POST['email'];
        $role =$_POST['sponsorlevel'];
        $welcomeemailtemplatename = $_POST['welcomeemailtempname'];
        $loggin_data=$_POST;
    
        unset($_POST['username']);
        unset($_POST['email']);
        unset($_POST['sponsorlevel']);
        unset($_POST['welcomeemailtempname']);
    
        //  print_r($_POST);
        
        $welcomeemail_status = $_POST['welcomeemailstatus'];
        $user_id = username_exists($username);
        $message['username'] = $username;
        $meta_array=$_POST;
        
       // $profilepic=$_FILES['profilepic'];
        //$picprofileurl = resource_file_upload($profilepic);
        
        
    
        $oldvalues = get_option( 'ContenteManager_Settings' );
    
        require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/egpl-custome-functions.php';
        $GetAllcustomefields = new EGPLCustomeFunctions();
        $listOFcustomfieldsArray = $GetAllcustomefields->getAllcustomefields();
    
        foreach($listOFcustomfieldsArray as $fieldsKey=>$fieldsObject){
    
            $fieldTYpe = $fieldsObject['fieldType'];
            
            if($fieldTYpe == "file"){

               
                $fieldKey = $fieldsObject['fielduniquekey'];
                $uploadFilesubmit = $_FILES[$fieldKey];
               
                if(!empty($uploadFilesubmit)){

                    $uploadedFileURL = resource_file_upload($uploadFilesubmit);
                    
                    $meta_array[$fieldKey]=$uploadedFileURL;
                }
            }
        }    
    
      
    
    if (!$user_id and email_exists($email) == false) {
        
       $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
       $user_id = myregisterrequest_new_user($username, $email) ;//register_new_user( $username, $email );//wp_create_user($username, $random_password, $email);
       if ( ! is_wp_error( $user_id ) ) {
       
       $result=$user_id;
       $loggin_data['created_id']=$result;
       $message['user_id'] = $user_id;
       $message['msg'] = 'User created';
       $message['userrole'] = $role;
      
       $site_prefix = $wpdb->get_blog_prefix();
       update_user_option($user_id, 'user_profile_url', $picprofileurl);
       
       $mapapikey = $oldvalues['ContentManager']['mapapikey'];
       $mapsecretkey = $oldvalues['ContentManager']['mapsecretkey'];
        add_user_to_blog(1, $user_id, $role);
       if(!empty($mapapikey) && !empty($mapsecretkey)){
          
          $data_array=array(
            'company'=>$meta_array['company_name'],
            'email'=>$email,
            'first_name'=>$meta_array['first_name'],
            'last_name'=>$meta_array['last_name'],
           
              
          ) ;
          
        $request_for_sync_map_dynamics = contentmanagerlogging('Sync to map dynamics',"Admin Action",serialize($data_array),$user_ID,$user_info->user_email,"pre_action_data");
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
       
       add_new_sponsor_metafields($user_id,$meta_array,$role);
       if($welcomeemail_status == 'send'){
            $useremail='';
            custome_email_send($user_id,$useremail,$welcomeemailtemplatename);
            $t=time();
            update_user_option($user_id, 'convo_welcomeemail_datetime', $t*1000);
       }      
    }else{
        
        $userregister_responce = (array)$user_id;
		  
		   if(empty($userregister_responce['errors']['invalid_username'][0])){
			   
			   $message['msg'] = $userregister_responce['errors']['invalid_email'][0];
		   }else{
			   
			   $message['msg'] = $userregister_responce['errors']['invalid_username'][0];
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
        
        $message['msg'] =  'User already exists for this site.';
        
    }else{    
           
        if (add_user_to_blog($blogid, $user_id, $role)) {
                 add_user_to_blog(1, $user_id, $role);
                $message['user_id'] = $user_id;
                $message['msg'] = 'User created';
                $message['userrole'] = $role;
                $meta_array=$_POST;
                //update_user_option($user_id, 'user_profile_url', $picprofileurl);
                $mapapikey = $oldvalues['ContentManager']['mapapikey'];
                $mapsecretkey = $oldvalues['ContentManager']['mapsecretkey'];
                if(!empty($mapapikey) && !empty($mapsecretkey)){
          
                        $data_array=array(
                          'company'=>$meta_array['company_name'],
                          'email'=>$email,
                          'first_name'=>$meta_array['first_name'],
                          'last_name'=>$meta_array['last_name'],
                       

                        ) ;
          
                    $request_for_sync_map_dynamics = contentmanagerlogging('Sync to map dynamics',"Admin Action",serialize($data_array),$user_ID,$user_info->user_email,"pre_action_data");
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
                
                add_new_sponsor_metafields($user_id,$meta_array,$role);
                if($welcomeemail_status == 'send'){
                    $useremail='';
                    custome_email_send($user_id,$email,$welcomeemailtemplatename);
                    $t=time();
                    update_user_option($user_id, 'convo_welcomeemail_datetime', $t*1000);
                }      
                
                $message['msg'] =  'User added to this blog.';
            
            } else {
                
                $message['msg'] = 'Failed to add user ' . $user_id . ' as ' . $role . ' to blog ' . $blogid . '.';
            }
        }
    }
   
    $loggin_data['msg']=$message['msg'];
    $message['mapdynamicsstatus'] = $mapdynamicsstatus;
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
    $usertimezone=intval($_POST['usertimezone']);
    getReportsdatanew($report_name,$usertimezone); 
    $result='Report Loaded';
    
    contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
    
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
}else if($_GET['contentManagerRequest'] == 'updatecmanagersettings'){ 
    require_once('../../../wp-load.php');
    
    $adminsitelogo=$_FILES['adminsitelogo'];
    
    if(empty($adminsitelogo)){
        
        
    }else{
      
      $adminstielogourl = resource_file_upload($adminsitelogo);
     
      
      $_POST['adminsitelogourl'] = $adminstielogourl;
      
    }
    
    
    
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

}else if ($_GET['contentManagerRequest'] == 'sendcustomewelcomeemail') {
    
    require_once('../../../wp-load.php');
    require_once 'Mandrill.php';
   
try { 
    
    global $wpdb;
    $site_prefix = $wpdb->get_blog_prefix();
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $mandrill = $oldvalues['ContentManager']['mandrill'];
    $mandrill = new Mandrill($mandrill);
    
   
    
    $sendcustomewelcomeemail = $_POST['selectedtemplateemailname'];
    
    
    $settitng_key='AR_Contentmanager_Email_Template_welcome';
    $sponsor_info = get_option($settitng_key);
    
    
    $subject = $sponsor_info[$sendcustomewelcomeemail]['welcomesubject'];
    $body=stripslashes ($sponsor_info[$sendcustomewelcomeemail]['welcomeboday']);
    $emailAddress=$_POST['emailAddress'];
    $emailaddress_array=explode(",", $emailAddress);
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $attendeefields_data=json_decode(stripslashes($_POST['attendeeallfields']), true);
    $colsdatatype=json_decode(stripslashes($_POST['datacollist']), true);
    $field_key_string = getInbetweenStrings('{', '}', $body);
    $field_key_subject = getInbetweenStrings('{', '}', $subject);
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $formemail = $oldvalues['ContentManager']['formemail'];
    $fromname = stripslashes ($sponsor_info[$sendcustomewelcomeemail]['fromname']);
   
    
    
    if(empty($formemail)){
        
        $formemail = 'noreply@expo-genie.com';
        
    }
   $bcc =  $sponsor_info[$sendcustomewelcomeemail]['BCC'];
   //$cc =  $sponsor_info[$sendcustomewelcomeemail]['CC'];
   //$cc_array = explode(',',$cc);
   $bcc_array = $bcc;
   
   
   
   
   
   
  // $fromname = $_POST['fromname'];
  
//print_r($attendeefields_data);;
    
    
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
    
   // foreach($emailaddress_array as $email=>$to){
       
       $body_message =    $body ;
      // $user = get_user_by( 'email', $to );
      // $firstname=$user->first_name;
      // $lastname=$user->last_name;
      // $user_email=$to;
       
       
      
       
        foreach($attendeefields_data as $key=>$Onerowvalue){
        
            $data_field_array= array();
            $result_email_index = multidimensional_search($Onerowvalue, array('colkey' => 'Semail')); // 1 
            $result_firstName_index = multidimensional_search($Onerowvalue, array('colkey' => $site_prefix.'first_name')); // 1 
            
            
            $userdata = get_user_by_email($Onerowvalue[$result_email_index]['colvalue']);
            $t=time();
            update_user_option($userdata->ID, 'convo_welcomeemail_datetime', $t*1000);
            $email_address = $Onerowvalue[$result_email_index]['colvalue'];
            $first_name = $Onerowvalue[$result_firstName_index]['colvalue'];
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
                            
                            
                          
                          $date_value =   date('d-m-Y' , intval($all_meta_for_user[$keyvalue_subject][0]/1000));
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
                      }elseif($keyvalue == 'wp_user_id'){
                          
                        
                          $data_field_array[] = array('name'=>$index,'content'=>$userdata->ID); 
                      }
                      
                      
                      
                   }else{
                       
                       
                       
                        
                       if (!empty($all_meta_for_user[$keyvalue][0])) {
                           
                           $result = multidimensional_search($colsdatatype, array('colkey' => $keyvalue)); // 1 
                           
                          $getfieldType = getcustomefieldKeyValue($keyvalue,"fieldType");
                          
                          
                         
                        if($getfieldType == 'date') {
                          
                          $date_value =   date('d-m-Y', intval($all_meta_for_user[$keyvalue][0])/1000);
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
  
   
   $get_currentsiteURl = get_site_url();
   $message = array(
        
        'html' => $html_body_message,
        'text' => '',
        'subject' => $subject,
        'from_email' => $formemail,
        'from_name' => $fromname,
        'to' => $to_message_array,
        'headers' => array('Reply-To' => $sponsor_info[$sendcustomewelcomeemail]['replaytoemailadd']),
        'bcc_address'=>$bcc_array,
        'track_opens' => true,
        'track_clicks' => true,
       
        'merge' => true,
        'merge_language' => 'mailchimp',
        'global_merge_vars' => $goble_data_array,
        'merge_vars' => $user_data_array,
        "tags" => [$get_currentsiteURl],
        
    );
   
    // exit;
       
    $lastInsertId = contentmanagerlogging('Welcome Email',"Admin Action",serialize($message),$user_ID,$user_info->user_email,"pre_action_data");
     
    $async = false;
    $ip_pool = 'Main Pool';
   // $send_at = 'example send_at';
    $result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
    
   
    contentmanagerlogging_file_upload($lastInsertId,serialize($result));
    echo json_encode('successfully send');
   
    
}catch(Mandrill_Error $e) {
    // Mandrill errors are thrown as exceptions
    $error_msg = 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
    // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
    
 
    contentmanagerlogging_file_upload($lastInsertId,$error_msg);
     echo   $e->getMessage();
    //throw $e;
}
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
    $field_key_string_subject = getInbetweenStrings('{', '}', $subject);
    
   
    
    
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $formemail = $oldvalues['ContentManager']['formemail'];
    if(empty($formemail)){
        $formemail = 'noreply@expo-genie.com';
        
    }
    
   $bcc = $_POST['BCC'];
   $cc  = $_POST['CC'];
  
   
   
   
   $replytoEmail = $_POST['RTO'];
   $fromname = $_POST['fromname'];
  

    
    
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
    $body_message =    $body ;
     
       foreach($attendeefields_data as $key=>$Onerowvalue){
        
            $data_field_array= array();
            $result_email_index = multidimensional_search($Onerowvalue, array('colkey' => 'Semail')); // 1 
            $result_firstName_index = multidimensional_search($Onerowvalue, array('colkey' => $site_prefix.'first_name')); // 1 
            
            
            $userdata = get_user_by_email($Onerowvalue[$result_email_index]['colvalue']);
            $t=time();
            update_user_option($userdata->ID, 'convo_welcomeemail_datetime', $t*1000);
            $email_address = $Onerowvalue[$result_email_index]['colvalue'];
            $first_name = $Onerowvalue[$result_firstName_index]['colvalue'];
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
                      }elseif($keyvalue == 'wp_user_id'){
                          
                        
                          $data_field_array[] = array('name'=>$index,'content'=>$userdata->ID); 
                      }
                      
                      
                      
                   }else{
                       
                       
                       
                        
                       if (!empty($all_meta_for_user[$keyvalue][0])) {
                           
                           $result = multidimensional_search($colsdatatype, array('colkey' => $keyvalue)); // 1 
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
       
      
   $bcc_array = $bcc;
   //$cc_array = explode(',',$cc);
   
   
   
//   if(sizeof($bcc_array) > 1){
//       
//       foreach($bcc_array as $key=>$value){
//           
//           
//        $to_message_array[]=array('email'=>$value,'name'=>'','type'=>'bcc');
//           
//           
//           
//       }
//       
//       
//       
//   }else{
//       
//       if(!empty($bcc_array)){
//           
//           $to_message_array[]=array('email'=>$bcc_array[0],'name'=>'','type'=>'bcc');
//       }
//   }
//   if(sizeof($cc_array) > 1){
//       
//       foreach($cc_array as $key=>$value){
//           
//           
//           
//         $to_message_array[]=array('email'=>$value,'name'=>'','type'=>'cc');
//           
//           
//       }
//       
//       
//       
//   }else{
//     if(!empty($cc_array)){
//          
//           $to_message_array[]=array('email'=>$cc_array[0],'name'=>'','type'=>'cc');
//       }  
//   }
   
       
       //$result = send_email($to,$subject,$body_message);

    
  
   
  
 //  print_r($bcc);exit;
   $get_currentsiteURl = get_site_url();
   $message = array(
        
        'html' => $body,
        'text' => '',
        'subject' => $subject,
        'from_email' => $formemail,
        'from_name' => $fromname,
        'to' => $to_message_array,
        'headers' => array('Reply-To' => $replytoEmail),
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
        
          global $wpdb;  
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $lastInsertId = contentmanagerlogging('Admin Test Email',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
    $site_prefix = $wpdb->get_blog_prefix();
     
        
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
        $formemail='noreply@expo-genie.com';
    }
       
      $body_message =    $body ;
      $subject_body =$subject;
      $site_url = get_option('siteurl' );
      $data=  date("Y-m-d");
      $time=  date('H:i:s');
      $user = get_user_by( 'email', $user_info->user_email );
      $all_meta_for_user = get_user_meta($user->ID);
      
      $firstname=$all_meta_for_user[$site_prefix.'first_name'][0];
      $lastname=$all_meta_for_user[$site_prefix.'last_name'][0];
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
    $body=stripslashes($_POST['emailBody']);
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
        $formemail = 'noreply@expo-genie.com';
        
    }
   
      
      $body_message =    $body ;
      $subject_body =$subject;
      $site_url = get_option('siteurl' );
      $data=  date("Y-m-d");
      $time=  date('H:i:s');
      $user = get_user_by( 'email', $user_info->user_email );
      $all_meta_for_user = get_user_meta($user_info->ID);
      $firstname=$all_meta_for_user[$site_prefix.'first_name'][0];
      $lastname=$all_meta_for_user[$site_prefix.'last_name'][0];
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
<table id="content" style="padding-right: 30px;padding-left: 30px;"  border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
<tbody>
<tr>
<td style="border-top: solid 1px #d9d9d9;" colspan="2">
<div style="padding: 1em 0;">
'.$body_message.'
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
       
       $result = send_email($user_info->user_email,$subject_body,$html_body_message,$headers);

    
   
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
    //$template_data['CC'] = $get_email_template_date[$report_name]['CC'];
    $template_data['RTO'] = $get_email_template_date[$report_name]['RTO'];
   
     
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
    
    $blog_id =get_current_blog_id();
    //switch_to_blog($blog_id);
    
    try{
    
   
    $newrolename =$_POST['rolename'];
    
     $user_ID = get_current_user_id();
     $user_info = get_userdata($user_ID);
     $lastInsertId = contentmanagerlogging('Add New Role',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
     $role_key=strtolower($newrolename);
     $remove_space_role_kye=str_replace(" ","_",$role_key);
     
     
     if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }
     $get_all_roles = get_option($get_all_roles_array);
     $result_update = 'newvalue';
     foreach ($get_all_roles as $key => $item) {
            
            if($role_key == strtolower($item['name']) || $key == $remove_space_role_kye ){
                $result_update = 'already';
                break;
            }
            
    }
     
     
     
    
     if($result_update == 'newvalue'){
        //$result = add_role( $remove_space_role_kye, ucfirst($newrolename), array( 'read' => true,'unfiltered_upload'=>true,'upload_files'=>true ) );
         $get_all_roles[$remove_space_role_kye]['name'] =  ucfirst($newrolename);
         $get_all_roles[$remove_space_role_kye]['capabilities']['unfiltered_upload'] =  1;//ucfirst($newrolename);
         $get_all_roles[$remove_space_role_kye]['capabilities']['upload_files'] =  1;//ucfirst($newrolename);
         $get_all_roles[$remove_space_role_kye]['capabilities']['level_0'] =  1;
         $get_all_roles[$remove_space_role_kye]['capabilities']['read'] =  1;
          
         update_option ($get_all_roles_array, $get_all_roles); 
        
            $msg['msg'] = '<strong>'.ucfirst($newrolename).'</strong> New Level created';
            $msg['status'] = 'success';
            $msg['title'] = 'Success';
       
     }else {
        
        $msg['msg'] = '<strong>'.ucfirst($newrolename).'</strong> Level already exists.';
        $msg['status'] = 'warning';
        $msg['title'] = 'Warning';
        
       }
    echo   json_encode($msg);
    contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
    
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
         return $e;
 }
 
    die(); 

}else if ($_GET['contentManagerRequest'] == 'createlevelclone') {
    
    require_once('../../../wp-load.php');
    
    try{
    $newrolename =$_POST['rolename'];
    $clonelevelkey =$_POST['clonerolekey'];
    
     $user_ID = get_current_user_id();
     $user_info = get_userdata($user_ID);
     $lastInsertId = contentmanagerlogging('Create new Clone',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
     if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }
     $get_all_roles = get_option($get_all_roles_array);
     
     
     
     $new_role_key=strtolower($newrolename);
     $new_remove_space_role_kye=str_replace(" ","_",$new_role_key);
     $result = add_role($new_remove_space_role_kye, ucfirst($newrolename), array( 'read' => true, ) );
    // $get_all_roles[$new_remove_space_role_kye]['name'] =  ucfirst($newrolename);
    // $result  =    update_option ($get_all_roles_array, $get_all_roles);
     
     
     
     
     if (!empty($result)) {
        $msg['msg'] = 'New Level created';
        
        
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
                   $value_roles = get_post_meta( $tasksID, 'roles' , false);
                   if(in_array($clonelevelkey,$value_roles[0])){
                        array_push($value_roles[0],$new_remove_space_role_kye);
                        update_post_meta( $tasksID, 'roles' , $value_roles[0]);
                   }
             
               
           } 
            //echo $key;
            
       
     // $taskarray_update = update_option($test, $assign_new_role);
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
     if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }
     $get_all_roles = get_option($get_all_roles_array);
     echo $remove_role_name;
     unset($get_all_roles[$remove_role_name]);
     update_option ($get_all_roles_array, $get_all_roles); 
     
     //$result = remove_role($remove_role_name);
     
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
    
    $filedataurl = $_POST['oldheaderbannerurl'];
    $headerlogourl = $_POST['oldheaderlogourl'];
    if(empty($_POST['oldheaderbannerurl'])){
        
        $filedata =  $_FILES['uploadedfile'];
        $filedataurl = resource_file_upload($filedata);
        
    }
    
    
    updateadmin_frontend_settings($_POST,$filedataurl);

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
   
   // echo '<pre>';
  //  print_r($loggin_data);exit;
    
    
    $responce="";
    if(!empty($resourceurl)){
    
      $filename_import = basename($resourceurl);      
      $responce  =  bulkimport_mappingdata($filename_import);
       
    }else{
       
         $responce = 'faild'; 
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
?>