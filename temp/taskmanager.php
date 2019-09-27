<?php



//Add new sponsor task 
if($_GET['createnewtask'] == "create_new_task") {        
    require_once('../../../wp-load.php');
    
    create_new_task($_POST);
   
  
}
if($_GET['createnewtask'] == "savebulktask") {        
    require_once('../../../wp-load.php');
    
    
      
    savebulktask_update($_POST);
   
  
}
if($_GET['createnewtask'] == "savebulkfields") {        
    require_once('../../../wp-load.php');
    
    
      
    savebulkfields_update($_POST);
   
  
}

 if ($_GET['createnewtask'] == 'check_sponsor_task_key_value') {
    
    require_once('../../../wp-load.php');
    
     $key = $_POST['key'];
     check_sponsor_task_key_value($key);
  
} if($_GET['createnewtask'] == 'get_edit_task_key_data'){
    
       require_once('../../../wp-load.php');
       $key = $_POST['key'];
       get_edit_task_key_data($key);
} if($_GET['createnewtask'] == 'removeTaskData'){
    
       require_once('../../../wp-load.php');
       $key = $_POST['uniqueKey'];
       removeTaskData($key);
}

function removeTaskData($taskupdatevalue){
    $key = $taskupdatevalue;
    $user_ID = get_current_user_id();
    $alert_type = "Remove";
    $subject = "Delete Task";
  
   
    $test = 'custome_task_manager_data';
    $result = get_option($test);
    
    $user_info = get_userdata($user_ID);
   
    contentmanagerlogging("Admin Remove Task","Admin Action",serialize($result['profile_fields'][$key]),$user_ID,$user_info->user_email,$result);

   
    unset($result['profile_fields'][$key]);
   
    
    
    $result = update_option($test, $result);
   
    die();
}
function get_edit_task_key_data($key){
    
     if (isset($key)) {
        $test = 'custome_task_manager_data';
        $result = get_option($test);
        $dataval = $result['profile_fields'][$key];
        $dataval['descrpition'] = stripslashes($dataval['descrpition']);
      //   echo '<pre>';
      //  print_r($dataval);exit;
       
        echo json_encode($dataval) ;
    } die();
}


function create_new_task($data_array){
    
    $key = $data_array['key'];
    
    
    
    $user_ID = get_current_user_id();
 
   
    $attr = $data_array['addational_attr'];
    $linkurl = $data_array['linkurl'];
    $linkname = $data_array['linkname'];
    $type = $data_array['type'];
    $lable = $data_array['labell'];
    $descrpition = $data_array['descrpition'];
    $date = $data_array['date'];
    $newDate = date("d-M-Y", strtotime($date));
  
    $rolesvalue = explode(",", $data_array['roles']);
    $usersids = explode(",", $data_array['selectedusersids']);
    
    $subject = "New Task created at ";
    $alert_type = "Add";
    //admin_alert($subject, $key, $lable, $descrpition, $newDate, $rolesvalue, $type, $alert_type);
    $test = 'custome_task_manager_data';
    $result = get_option($test);
 
   
if (in_array($key, $result['profile_fields']))
  {
     $action_name ="Admin Edit Task";
  }
else
  {
  $action_name ="Admin Create Task";
  }
    
      
    $b[] = '';



    //task action array 
    $a['value'] = '';
    $a['unique'] = 'no';
    $a['type'] = $type;
    $a['label'] = $lable;
    $a['class'] = '';
    $a['attrs'] = $newDate;
    $a['taskattrs']=$attr;
    $a['descrpition'] = $descrpition;
    $a['after'] = '';
    $a['required'] = 'no';
    $a['allow_tags'] = 'yes';
    $a['add_to_profile'] = 'yes';
    $a['allow_multi'] = 'no';
    $a['size'] = '';
    $a['roles'] = $rolesvalue;
    $a['usersids'] = $usersids;
   
     if($type == 'link'){
         $a['lin_url']=$linkurl;
         $a['linkname']=$linkname;
     }
    

    if($type == 'select-2'){
        
      $array_drop_down=$_POST['dropdown'];
      $array_drop_down = explode(",", $_POST['dropdown']);
       
      $index_value = 0;
      foreach ($array_drop_down as $array_value){
         
           $gb['label'] = $array_value;
           $gb['value'] = $array_value;
           $gb['state'] = '';
           $a['options'][$index_value] = $gb;
           $index_value++;
      }
     
     
      
    }
   
  
    
   


    $result['profile_fields'][$key] = $a;
    
    $user_info = get_userdata($user_ID);
    
    

   $restult = update_option($test, $result);
    
    
    contentmanagerlogging($action_name,"Admin Action",serialize($result['profile_fields'][$key]),$user_ID,$user_info->user_email,$key);

    
  die();   
}




function check_sponsor_task_key_value($key) {
    
    
    $test = 'custome_task_manager_data';
    $result = get_option($test);
    $value = 0;
    if (empty($result['profile_fields'][$key])) {
        $message['msg']='Not Exist';
    } else {
        $message['msg']='already Exist';
    }
    echo json_encode($message);
    die();
}

function admin_alert($subject, $key, $lable, $descrpition, $newDate, $type, $alert_type) {


    $site_url = get_option('siteurl');
    $postid = get_current_user_id();
     $to = "azhar.ghias@e2esp.com";
    $subject = 'userid:'.$postid.'--'. $subject . ' <' . $site_url . '>';

    if ($alert_type == "Remove") {
        $message =
                "Task Key  :" . $key . "
Status Key  :" . $key . "_status
This alert implies that Deleted fields have to be removed in Salesforce and field mapping should be adjusted in SRC.";
    } elseif ($alert_type == "Edit") {
        $message =
                "Task Key  :" . $key . "
Task Input Field Type :" . $type . "
Task Due Date : " . $newDate . "
Task Label : " . $lable . "
Task Description :" . $descrpition . "
Status Key  :" . $key . "_status
Status Label :" . $lable . " Status
This alert implies that Edited fields have to be defined in Salesforce and field mapping should be adjusted in SRC.";
    } else {
        $message =
                "Task Key  :" . $key . "
Task Input Field Type :" . $type . "
Task Due Date : " . $newDate . "
Task Label : " . $lable . "
Task Description :" . $descrpition . "
Status Key  :" . $key . "_status
Status Label :" . $lable . " Status
This alert implies that new fields have to be defined in Salesforce and field mapping should be adjusted in SRC.";
    }
    
    $headers[] = 'Cc: Qasim Riaz <qasim.riaz@e2esp.com>';
   // wp_mail($to, $subject, $message,$headers);
}

function savebulktask_update($request){
    
     try{
         
         
        
        $listoftaks = json_decode(stripslashes($request['bulktaskdata']));
        $removetaskslist = json_decode(stripslashes($request['deletedtaskslist']));  
         
        foreach ($removetaskslist as $tasksIndex=>$removetaskID){
            
            
            wp_delete_post($removetaskID);
            
        }
        
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('Save Bulk Task',"Admin Action",$request,$user_ID,$user_info->user_email,"pre_action_data");
       
        
        foreach ($listoftaks as $taksKey=>$taskObject){
            
            
            
        if( strpos( $taksKey, "addnewtasks" ) !== false) {
            
          
                $taskaObjectData = array(
                    'post_title'    => wp_strip_all_tags( $taskObject->label ),
                    'post_content'  => "",
                    'post_status'   => 'draft',
                    'post_author'   => $user_ID,
                    'post_type'=>'egpl_custome_tasks'
                );
                $tasksID = wp_insert_post( $taskaObjectData );
            
            }else{
                
                $tasksID = $taksKey;
                
                
            }
            
            
            update_post_meta( $tasksID, 'value', $taskObject->value );
            update_post_meta( $tasksID, 'unique', $taskObject->unique );
            update_post_meta( $tasksID, 'class', $taskObject->class );
            update_post_meta( $tasksID, 'after', $taskObject->after );
            update_post_meta( $tasksID, 'required', $taskObject->required );
            update_post_meta( $tasksID, 'allow_tags', $taskObject->allow_tags );
            update_post_meta( $tasksID, 'add_to_profile', $taskObject->add_to_profile );
            update_post_meta( $tasksID, 'allow_multi', $taskObject->allow_multi );
            update_post_meta( $tasksID, 'label', $taskObject->label );
            update_post_meta( $tasksID, 'type', $taskObject->type );
            update_post_meta( $tasksID, 'link_url', $taskObject->lin_url );
            update_post_meta( $tasksID, 'linkname', $taskObject->linkname );
            update_post_meta( $tasksID, 'duedate', $taskObject->attrs );
            update_post_meta( $tasksID, 'taskattrs', $taskObject->taskattrs );
            update_post_meta( $tasksID, 'taskMWC', $taskObject->taskMWC );
            update_post_meta( $tasksID, 'taskMWDDP', $taskObject->taskMWDDP );
            update_post_meta( $tasksID, 'roles', $taskObject->roles );
            update_post_meta( $tasksID, 'usersids', $taskObject->usersids );
            update_post_meta( $tasksID, 'descrpition', $taskObject->descrpition );
            update_post_meta( $tasksID, 'key', $taskObject->key );
            update_post_meta( $tasksID, 'SystemTask', $taskObject->SystemTask  );
            update_post_meta( $tasksID, 'taskCode', $taskObject->taskCode );
            
            
            
            if(!empty($taskObject->options)){
                
                update_post_meta( $tasksID, 'options', $taskObject->options );
            }
           
            
            
            
        }
        
        
        contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
        
       
         
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
 
 die();  
    
    
}



function savebulkfields_update($request){
    
     try{
         
         
        
        $listoftaks = json_decode(stripslashes($request['bulkfielddata']));
        
        
        
        
        $removetaskslist = json_decode(stripslashes($request['deletedfieldlist']));  
         
        foreach ($removetaskslist as $tasksIndex=>$removetaskID){
            
            
            wp_delete_post($removetaskID);
            
        }
        
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('Save Bulk Field',"Admin Action",$request,$user_ID,$user_info->user_email,"pre_action_data");
       
        
        foreach ($listoftaks as $taksKey=>$taskObject){
            
            
            
        if( strpos( $taksKey, "addnewfield" ) !== false) {
            
          
                $taskaObjectData = array(
                    'post_title'    => wp_strip_all_tags( $taskObject->label ),
                    'post_content'  => "",
                    'post_status'   => 'draft',
                    'post_author'   => $user_ID,
                    'post_type'=>'egpl_custome_fields'
                );
                $tasksID = wp_insert_post( $taskaObjectData );
            
            }else{
                
                $tasksID = $taksKey;
                
                
            }
            
         
            
            update_post_meta( $tasksID, 'label', $taskObject->label );
            update_post_meta( $tasksID, '_egpl_field_type', $taskObject->type );
            update_post_meta( $tasksID, '_egpl_link_url', $taskObject->lin_url );
            update_post_meta( $tasksID, '_egpl_link_name', $taskObject->linkname );
            update_post_meta( $tasksID, '_egpl_field_tooltip_text', $taskObject->fieldtooltip );
            update_post_meta( $tasksID, '_egpl_field_requried_status', $taskObject->fieldstatusrequried );
            update_post_meta( $tasksID, '_egpl_field_system_task', $taskObject->Systemfield );
            update_post_meta( $tasksID, '_egpl_field_code', $taskObject->fieldCode );
            update_post_meta( $tasksID, '_egpl_field_display_on_application_form', $taskObject->fieldstatusshowonregform );
            update_post_meta( $tasksID, '_egpl_field_placeholder', $taskObject->fieldplaceholder );
            update_post_meta( $tasksID, 'Indexfield', $taskObject->Indexfield );
            update_post_meta( $tasksID, '_egpl_field_description', $taskObject->descrpition );
            update_post_meta( $tasksID, '_egpl_field_unique_key', $taskObject->key );
            update_post_meta( $tasksID, '_egpl_field_allow_multi', $taskObject->key );
            update_post_meta( $tasksID, '_egpl_field_attribute', $taskObject->attribute );
            
            
            
            
            if(!empty($taskObject->options)){
                
                update_post_meta( $tasksID, 'options', $taskObject->options );
            }
           
        }
        
        
        contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
        
       
         
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
 
 die();  
    
    
}




