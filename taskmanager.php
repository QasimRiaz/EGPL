<?php



//Add new sponsor task 
if($_GET['createnewtask'] == "create_new_task") {        
    require_once('../../../wp-load.php');
    
    create_new_task($_POST);
   
  
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
    admin_alert($subject, $key, $lable, $descrpition, $newDate, $type, $alert_type);
   
    $test = 'user_meta_manager_data';
    $result = get_option($test);
    
    $user_info = get_userdata($user_ID);
   
       contentmanagerlogging("Admin Remove Task","Admin Action",serialize($result['profile_fields'][$key]),$user_ID,$user_info->user_email,$result);

    unset($result['profile_fields'][$key . '_status']);
    unset($result['profile_fields'][$key . '_datetime']);
    unset($result['profile_fields'][$key]);
    unset($result['custom_meta'][$key . '_status']);
    unset($result['custom_meta'][$key . '_datetime']);
    unset($result['custom_meta'][$key]);
    unset($result['sort_order'][$key]);
    unset($result['sort_order'][$key . '_status']);
    unset($result['sort_order'][$key . '_datetime']);
    
    
    $result = update_option($test, $result);
   
    die();
}
function get_edit_task_key_data($key){
    
     if (isset($key)) {
        $test = 'user_meta_manager_data';
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
    
    $subject = "New Task created at ";
    $alert_type = "Add";
    admin_alert($subject, $key, $lable, $descrpition, $newDate, $rolesvalue, $type, $alert_type);
    $test = 'user_meta_manager_data';
    $result = get_option($test);
    $looparray;
    $loop=0;
    foreach ($result['custom_meta'] as $item=>$keys){
        $looparray[$loop]=$item;
        $loop++;
   }
if (in_array($key, $looparray))
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
   
  
    
    //task status array 
    $c['value'] = '';
    $c['unique'] = 'no';
    $c['type'] = 'select';
    $c['label'] = $_POST['labell'] . ' Status';
    $c['class'] = '';
    $c['attrs'] = $newDate;
    $c['after'] = '';
    $c['required'] = 'no';
    $c['allow_tags'] = 'yes';
    $c['add_to_profile'] = 'yes';
    $c['allow_multi'] = 'no';
    $c['size'] = '1';
    $c['roles'] = $rolesvalue;

    $f['label'] = 'Pending';
    $f['value'] = 'Pending';
    $f['state'] = '';

    $g['label'] = 'Complete';
    $g['value'] = 'Complete';
    $g['state'] = '';


    //$d[]=$f;
    //$e[]=$g;

    $c['options'][0] = $f;
    $c['options'][1] = $g;


    $c['unique'] = 'no';
    $c['value'] = '';


    //task time stamp  
    $d['value'] = '';
    $d['unique'] = 'no';
    $d['type'] = 'datetime';
    $d['label'] = $lable . ' Date-Time';
    $d['class'] = '';
    $d['attrs'] = $newDate;
    $d['after'] = '';
    $d['required'] = 'no';
    $d['allow_tags'] = 'yes';
    $d['add_to_profile'] = 'yes';
    $d['allow_multi'] = 'no';
    $d['size'] = '';
    $d['roles'] = $rolesvalue;
    $b;
    $d['options'] = $b;


    $result['profile_fields'][$key] = $a;
    $result['profile_fields'][$key . '_status'] = $c;
    $result['profile_fields'][$key . '_datetime'] = $d;
    $result['custom_meta'][$key] = '';
    $result['custom_meta'][$key . '_status'] = '';
    $result['custom_meta'][$key . '_datetime'] = '';
    array_push($result['sort_order'], $key, $key . "_status", $key . '_datetime');
    $user_info = get_userdata($user_ID);
    
    

   $restult = update_option($test, $result);
    
    
    contentmanagerlogging($action_name,"Admin Action",serialize($result['profile_fields'][$key]),$user_ID,$user_info->user_email,$restult);

    
  die();   
}




function check_sponsor_task_key_value($key) {
    
    
    $test = 'user_meta_manager_data';
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




