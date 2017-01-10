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
         
         
        
         
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('Save Bulk Task',"Admin Action",$request,$user_ID,$user_info->user_email,"pre_action_data");
       
        $tasksdata=json_decode(stripslashes($request['bulktaskdata']));
        $tasksdata = json_decode(json_encode($tasksdata), true);
        $test = 'custome_task_manager_data';
        $result = get_option($test);
        $result['profile_fields'] = $tasksdata;
        $user_info = get_userdata($user_ID);
        $restults = update_option($test, $result);
        
        contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
        
       
         
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
 
 die();  
    
    
}




