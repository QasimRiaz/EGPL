<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
       
      get_header();
		
      if(!empty($_GET['sponsorid'])){
          $sponsor_id=$_GET['sponsorid'];
          $meta_for_user = get_userdata( $sponsor_id );
          $all_meta_for_user = get_user_meta($sponsor_id );
         // echo '<pre>';
        //  print_r( $meta_for_user );
          
      }
    
		
     
     
     $roles = wp_get_current_user()->roles;
     $check= array_key_exists("contentmanager",$roles);
     
     
     
     
     $args = array(
	'posts_per_page'   => -1,
	'orderby'          => 'date',
	'order'            => 'ASC',
	'post_type'        => 'egpl_custome_tasks',
	'post_status'      => 'draft',
	
    );
    $result = get_posts( $args );
     $base_url  = get_site_url();
   
   $tasksortedArray = [];
     foreach ($result as $taskIndex => $taskObject){
         
        $tasksortedArray[$taskIndex]['Id'] = $taskObject->ID;
        $tasksortedArray[$taskIndex]['sortingOrder'] = strtotime(get_post_meta( $taskObject->ID, 'duedate', true)); 
        
        
         
     }
    
     
     usort($tasksortedArray, function($a, $b) {
    return $a['sortingOrder'] <=> $b['sortingOrder'];
});
   
    
   
     
     $settitng_key = 'ContenteManager_Settings';
     $sponsor_info = get_option($settitng_key);
     $sponsor_name = $sponsor_info['ContentManager']['sponsor-name'];
     $lockTWMcomplete = $sponsor_info['ContentManager']['lockTWMcomplete'];
     $lockTWMduedate = $sponsor_info['ContentManager']['lockTWMduedate'];
     $current_user = get_userdata( $sponsor_id );
     $user_IDD = $sponsor_id;
     $base_url = "http://" . $_SERVER['SERVER_NAME'];
     // echo '<pre>';
    // print_r($result );exit;
                       
      global $wp_roles;
      $site_url  = get_site_url();
      $all_roles = $wp_roles->get_names();
      
      function getRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = '';

    for ($i = 0; $i < $length; $i++) {
        $string .= $characters[mt_rand(0, strlen($characters) - 1)];
    }

    return $string;
}
      
     ?>
          <script>
        
            
        
        currentsiteurl = '<?php echo $site_url;?>';
        
        
    </script> 
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .content{
            
            
            font-size: 18px !important;
            color:#fff !important;
            padding:0px !important;
        }
        .select2-container--default .select2-results__option {
    
            color: #333 !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: solid #d2d2d2 1px !important;
          
        }
    </style>    
<div id="content" class="full-width">

        <div id="sponsor-status"></div>
              <?php
    // TO SHOW THE PAGE CONTENTS
    while ( have_posts() ) : the_post(); ?> <!--Because the_content() works only inside a WP Loop -->
        <div class="entry-content-page">
            <?php the_content(); ?> <!-- Page Content -->
        </div><!-- .entry-content-page -->

    <?php
    endwhile; //resetting the page loop
    
    
    if ( is_user_logged_in() ) {    
    
    ?>

   
            <table class="mytable table table-striped table-bordered table-condensed" >
                <thead>
                    <tr class="text_th" >
                        <th class="duedate-bg">Due Date</th>
                        <th id="task-bg">Task</th>
                        <th id="spec-bg">Specifications</th>
                        <th id="action-bg">Action</th>
                        <th id="status-bg"></th>
                    </tr></thead>
                <tbody>
           <?php
           
         
           foreach ($tasksortedArray as $taskIndex => $taskObject){
                    
               
                                    $tasksID=$taskObject['Id'];
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
                                    $multivaluetasklimit = get_post_meta( $tasksID, 'multivaluetasklimit', true);
                                   
                                    
                                    
                                    $value_taskattrs = get_post_meta( $tasksID, 'taskattrs', false);
                                    $value_taskMWC = get_post_meta( $tasksID, 'taskMWC' , false);
                                    $value_taskMWDDP = get_post_meta( $tasksID, 'taskMWDDP' , false);
                                    $value_roles = get_post_meta( $tasksID, 'roles' , false);
                                    $value_usersids = get_post_meta( $tasksID, 'usersids' , false);
                                    $value_descrpition = get_post_meta( $tasksID, 'descrpition', false);
                                    $value_multiselectstatus = get_post_meta( $tasksID, 'multiselectstatus', false);
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
                                    $profile_field_settings['multiselectstatus'] =$value_multiselectstatus[0];
                                  
                                    
                                    
                                    if($profile_field_settings['type'] == "select-2" || $profile_field_settings['type'] == "multiselect"){
                                        
                                            $getarraysValue = get_post_meta( $tasksID, 'options', false);
                                            
                                            if(!empty($getarraysValue[0])){

                                                
                                                 $profile_field_settings['options'] =$getarraysValue[0];
                                                 
                                             }
                                   }
               
               
               
               
                $lockdownstatus = 'unchecked';
                $user_can_view = false;
                $file_fields_staus_type="";
                $action_col = "";
                $status_col = "";
               if (isset($profile_field_settings['roles']) && is_array($profile_field_settings['roles'])){
                   foreach ($profile_field_settings['roles'] as $role){
                       if ((is_array($current_user->caps) && array_key_exists($role, $current_user->caps)) || (empty($current_user->caps) && $role == 'visitor') || $role == 'all'){
                           $user_can_view = true;
                       }
                   }
                  
                   
                   
               }
               if(!empty($profile_field_settings['usersids'])){
                if (in_array($user_IDD, $profile_field_settings['usersids'])) {
                    
                     $user_can_view = true;
                }
               }
               //else{
                 //  $user_can_view = true;
              // }
             if(isset($profile_field_settings['usersids'])){
               if(in_array($sponsor_id,$profile_field_settings['usersids'])){
                   
                $user_can_view = true;
               }
             }
               if($user_can_view){
                 
                   
                   $task_due_date = date_create($profile_field_settings['attrs']);
                   $current_date = date_create(date("d-M-y"));
                   $diff_both_dates = date_diff($task_due_date, $current_date);
                   $result_date = $diff_both_dates->format("%R%a");
                   $timestamp_task_data = strtotime($profile_field_settings['attrs']);
                   $value = get_user_meta($sponsor_id, $profile_field_name, true);
                   $status_value = get_user_meta($sponsor_id, $profile_field_name.'_status', true);
                   $fields_staus_type='';
                   if($status_value == 'Complete'){
                       
                       $fields_staus_type='disabled';
                       
                   }
                   if($profile_field_settings['taskMWC'] == 'checked'){
                       if($status_value == 'Complete'){
                            $lockdownstatus = 'checked';
                            $fields_staus_type='disabled';
                            $file_fields_staus_type='disabled';
                       }
                   }
                   if($profile_field_settings['taskMWDDP']== 'checked'){
                       
                       if ($result_date <= 0) {
                           
                       }else{
                           $lockdownstatus = 'checked';
                           $fields_staus_type='disabled';
                           $file_fields_staus_type='disabled';
                       }
                       
                   }
                   
                  
                   
                   $taskdescription = stripslashes($profile_field_settings['descrpition']);
                   $field_key_string =  getInbetweenStrings('{', '}', $taskdescription);
                   $all_meta_for_user = get_user_meta($sponsor_id);
                   
                   $site_url = get_option('siteurl' );
                    $login_url = get_option('siteurl' );
                    $admin_email= get_option('admin_email');
                    $datetest=  date("Y-m-d");
                    $timetest=  date('H:i:s');
                    $sitetitle = get_bloginfo( 'name' );
                   $userdata = get_userdata($sponsor_id);
                   foreach($field_key_string as $index=>$keyvalue){
                
                        
                     
                      
                      if($keyvalue == 'wp_user_id' || $keyvalue == 'Semail' || $keyvalue == 'Role' || $keyvalue == 'site_title' || $keyvalue == 'date' || $keyvalue == 'time' || $keyvalue == 'site_url' || $keyvalue == 'user_pass'|| $keyvalue == 'user_login'){
                      
                          
                      if($keyvalue == 'user_pass'){
                          
                           
                            $user_id = $userdata->ID;
                            $plaintext_pass=wp_generate_password( 8, false, false );
                            wp_set_password( $plaintext_pass, $user_id );
                            $taskdescription = str_replace("{user_pass}",$plaintext_pass,$taskdescription);
                            
                          
                      }elseif($keyvalue == 'user_login'){
                          
                       
                           
                          $taskdescription = str_replace("{user_login}",$userdata->user_login,$taskdescription);
                         
                          
                      }elseif($keyvalue == 'level'){
                          
                        
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
                         // $data_field_array[] = array('name'=>$index,'content'=>$currentuserRole); 
                          
                          $taskdescription = str_replace("{Role}",$currentuserRole,$taskdescription);
                      }elseif($keyvalue == 'Semail'){
                          
                           $taskdescription = str_replace("{Semail}",$userdata->user_email ,$taskdescription);
                         // $data_field_array[] = array('name'=>$index,'content'=>$email_address); 
                      }elseif($keyvalue == 'wp_user_id'){
                          
                           $taskdescription = str_replace("{user_id}",$userdata->ID,$taskdescription);
                          //$data_field_array[] = array('name'=>$index,'content'=>$userdata->ID); 
                      }elseif($keyvalue == 'date'){
                          
                           $taskdescription = str_replace("{date}",$datetest,$taskdescription);
                          //$data_field_array[] = array('name'=>$index,'content'=>$userdata->ID); 
                      }elseif($keyvalue == 'time'){
                          
                           $taskdescription = str_replace("{time}",$timetest,$taskdescription);
                          //$data_field_array[] = array('name'=>$index,'content'=>$userdata->ID); 
                      }elseif($keyvalue == 'site_title'){
                          
                           $taskdescription = str_replace("{site_title}",$sitetitle,$taskdescription);
                          //$data_field_array[] = array('name'=>$index,'content'=>$userdata->ID); 
                      }elseif($keyvalue == 'site_url'){
                          
                           $taskdescription = str_replace("{site_url}",$site_url,$taskdescription);
                          //$data_field_array[] = array('name'=>$index,'content'=>$userdata->ID); 
                      }
                      
                      
                      
                   }else{
                       
                       
                      
                       
                       $keyvalueforadd = "{".$index."}";
                     
                       
                       if (!empty($all_meta_for_user[$keyvalue][0])) {
                           
                          
                          $getfieldType = getcustomefieldKeyValue($keyvalue,"fieldType");
                        
                          
                          
                        if($getfieldType == 'date') {
                            
                          $date_value =   date('d-m-Y', intval($all_meta_for_user[$keyvalue][0]/1000));
                          //$data_field_array[] = array('name'=>$index,'content'=>$date_value);
                          $taskdescription = str_replace("{date}",$date_value,$taskdescription);
                        } else{
                             
                                 
                               // $data_field_array[] = array('name'=>$index,'content'=> $all_meta_for_user[$keyvalue][0]);  
                                
                            
                                $taskdescription = str_replace($keyvalueforadd,$all_meta_for_user[$keyvalue][0],$taskdescription);
                        }
                       }else{
                           
                                $taskdescription = str_replace($keyvalueforadd,"",$taskdescription);
                          
                       }
                  
                      
                     // echo $taskdescription;exit;
                      
                      
                 
                 
                 
                 
                   }}
                  
                   if ($result_date <= 0) {

                       $duedate_html = '<td class="duedate"  data-order="' . $timestamp_task_data . '" >' . $profile_field_settings['attrs'] . '</td><td class="checklist">' . $profile_field_settings['label'] . '</td><td class="descrpition">' . $taskdescription . '</td>';
                   
                       
                   } else {
                     
                       $duedate_html = '<tr class="overdue"><td  data-order="' . $timestamp_task_data . '" class="duedate ' . $profile_field_name . '_status">' . $profile_field_settings['attrs'] . ' <span class="icon-wrapper circle-no"><i class="fusion-li-icon fa fa-flag" style="color:#5D5858;"></i></span></td><td class="checklist">' . $profile_field_settings['label'] . '</td><td class="descrpition">' . $taskdescription . '</td>';
                       
                       
                   }
                  
                    switch ($profile_field_settings['type']) {
                        
                        
                       case 'text':
                       case 'date':
                       case 'datetime':
                       case 'number':
                       case 'email':
                      
                           //echo $value.'-----';
                           //echo htmlspecialchars($value);
                           //exit;
                           $action_col .= '<input '.$fields_staus_type.' class="myclass" type="' . $profile_field_settings['type'] . '" id="' . $profile_field_name;
                           $action_col .= '" value="'.htmlspecialchars($value).'" >';  
                           break;
                       
                       case 'url':
                           
                           $action_col .= '<input '.$fields_staus_type.' class="myclass" type="url" id="' . $profile_field_name;
                           $action_col .= '" value="'.htmlspecialchars($value).'" >';  
                           break;
                       case 'color':
                           

                           if (!empty($value)) {
                               $action_col .='<div class="' . $profile_field_name . '" style="display:none;">';
                           }

                           $action_col .= '<input '.$file_fields_staus_type.' class="uploadFileid"  id="display_my' . $profile_field_name . '" placeholder="Choose File" disabled="disabled" /><div class="fusion-button fusion-button-default fusion-button-medium fusion-button-round fusion-button-flat" '.$file_fields_staus_type.' id="fileUpload"><span>Browse</span><input '.$file_fields_staus_type.'  ' . $profile_field_settings['taskattrs'] . ' type="file" class ="upload myfileuploader" id="my' . $profile_field_name . '" name="my' . $profile_field_name . '" /></div>';
                           if (!empty($value)) {
                               $action_col .='</div>';
                           }
                           $action_col .= '<input type="hidden" id="hd_' . $profile_field_name . '"';
                           if (!empty($value)) {
                               $action_col .= ' value="' . base64_encode(serialize($value)) . '"';
                           }

                           $action_col .= 'class="' . stripslashes(htmlspecialchars_decode($profile_field_settings['class'])) . $unique . '"';
                           if ($profile_field_settings['required'] == 'yes')
                               $action_col .= ' required="required"';
                           if (!empty($profile_field_settings['taskattrs']))
                               $action_col .= ' ';
                           $action_col .= $form_tag . " />";
                           if (!empty($value)) {
                               
                               $action_col .= "<div style='text-align: center;margin-top: 14px;' class='remove_" . $profile_field_name . "'><a href='" . $base_url . "/wp-content/plugins/EGPL/download-lib.php?userid=" . $user_IDD . "&fieldname=" . $profile_field_name . "' target='_blank' style='margin-right: 24px;'>Download File</a></div>";
                                   
                              
                               
                               }
                           break;
                   
                       //Modification by Qasim Riaz
                       case 'none':
                           $action_col .= '';
                           break;
                       case 'comingsoon':
                           $action_col .= '<strong >Coming soon</strong>';
                           break;
                       //Modification by Qasim Riaz
                      
                      case 'textarea':
                           
                           $action_col .= '<textarea '.$fields_staus_type.' rows="5"  class="myclasstextarea" id="' . $profile_field_name . '" name="' . $profile_field_name;
                           if ($mode == 'adduser')
                               $field_html .= '[]';
                           $action_col .= '" class="' . stripslashes(htmlspecialchars_decode($profile_field_settings['class'])) . $unique . '"';
                           
                           if ($profile_field_settings['required'] == 'yes')
                               $action_col .= ' required="required"';
                           if (!empty($profile_field_settings['taskattrs']))
                               $action_col .= $profile_field_settings['taskattrs'];
                           $action_col .= $form_tag . '>' . htmlspecialchars($value) . '</textarea>';
                           if (!empty($profile_field_settings['taskattrs']))
                               $action_col .='<span style="font-size:10px;padding-top: 20px;padding-left: 4px;padding-right: 7px;" id="chars_' . $profile_field_name . '">' . str_replace("maxlength=", "", $profile_field_settings['taskattrs']) . '</span><span style="font-size:10px;">characters remaining</span>';
                           break;
                     case 'select-2':
                                      
                           $multi = ((isset($profile_field_settings['allow_multi']) && $profile_field_settings['allow_multi'] == 'yes') || ($mode == 'adduser')) ? '[]' : '';
                           $multiple = (isset($profile_field_settings['allow_multi']) && $profile_field_settings['allow_multi'] == 'yes') ? ' multiple="multiple"' : '';
                           $size = (!isset($profile_field_settings['size']) || $profile_field_settings['size'] < 1) ? ' size="1"' : ' size="' . $profile_field_settings['size'] . '"';
                           $action_col .= '<select style="width: 100% !important;height:36px !important;" '.$fields_staus_type.' name="' . $profile_field_name . $multi . '" id="' . $profile_field_name . $multi . '" class="selectclass egpl_single_select2"';
                          
                           if ($profile_field_settings['required'] == 'yes')
                               $field_html .= ' required="required"';
                           if (!empty($profile_field_settings['attrs']))
                           //$field_html .= ' ' . stripslashes(htmlspecialchars_decode($profile_field_settings['attrs']));
                               
                            if($profile_field_settings['multiselectstatus'] == "checked"){
                                
                                $action_col .= 'multiple="multiple"';
                                
                            }   
                               
                               $action_col .= $multiple . $size . $form_tag . '>' . "\n";
                           foreach ($profile_field_settings['options'] as $option => $option_settings):
                               if (!empty($option_settings->label)):
                                   $action_col .= '<option value="' . htmlspecialchars(stripslashes($option_settings->value)) . '"';
                                   if ((!is_array($value) && $option_settings->value == $value) || (is_array($value) && in_array($option_settings->value, $value)) || (($mode == 'register' || $mode == 'adduser') && ($option_settings->state == 'checked')))
                                       $action_col .= ' selected="selected"';
                                   $action_col .= '>' . stripslashes($option_settings->label) . '</option>';
        
                               endif;
                           endforeach;

                           $action_col .= "</select>\n";
                           break;
                     case 'multiselect':
                                      
                           $action_col .= '<select style="width: 100% !important;" class="egpl_single_select2" '.$fields_staus_type.' name="' . $profile_field_name . $multi . '" id="' . $profile_field_name . '" multiple="multiple"';
                          
                           
                               $action_col .=  '>' . "\n";
                           foreach ($profile_field_settings['options'] as $option => $option_settings):
                               if (!empty($option_settings->label)):
                                   $action_col .= '<option value="' . htmlspecialchars(stripslashes($option_settings->value)) . '"';
                                   if ((!is_array($value) && $option_settings->value == $value) || (is_array($value) && in_array($option_settings->value, $value)) || (($mode == 'register' || $mode == 'adduser') && ($option_settings->state == 'checked')))
                                       $action_col .= ' selected="selected"';
                                   $action_col .= '>' . stripslashes($option_settings->label) . '</option>';
        
                               endif;
                           endforeach;

                           $action_col .= "</select>\n";
                           break;
                     case 'multivaluedtask':
                           $profile_field_nameArray = $profile_field_name."[]";
                           $profile_field_namespecial = "'".$profile_field_name."'";
                           $multivaluetasklimit =  "'".$multivaluetasklimit."'";
                           $randomnumber =getRandomString(8);
                           $multivaluetaskarray = json_decode($value);
                           
                           $action_col .= '<div class="multivaluetask_'.$profile_field_name.'">';
                            //unset($multivaluetaskarray[0]);
                           if(sizeof($multivaluetaskarray) >0){
                           foreach ($multivaluetaskarray as $multivalueIndex=>$multivalue){
                               $randomnumber ="'".getRandomString(8)."'";
                               $action_col .= '<p id='.$randomnumber.'><input '.$fields_staus_type.' value="'.htmlspecialchars($multivaluetaskarray[$multivalueIndex]).'" style="width: 80% !important;margin-top: 1px;"  class="myclass specialcountclass_'.$profile_field_name.'  speicaltaskmulittask_'.$profile_field_name.'" type="text" name="' . $profile_field_nameArray.'" /> <button '.$fields_staus_type.' style="width: 17%;" class="speicaltaskmulittask_'.$profile_field_name.' btn btn-danger btn-small" onclick="removethisvaluetask('.$randomnumber.')" title="Delete"><i class="fas fa-trash"></i></button></p>';
    
                               
                           }
                           
                           
                           }else{
                               
                               $baseurl ="'bassfieldtype'";
                               $action_col .= '<p id='.$baseurl.'><input '.$fields_staus_type.' style="width: 80% !important;margin-top: 1px;"  class="myclass specialcountclass_'.$profile_field_name.' speicaltaskmulittask_'.$profile_field_name.' " type="text" name="' . $profile_field_nameArray;
                               $action_col .= '" value="'.htmlspecialchars($multivaluetaskarray[0]).'" ><button '.$fields_staus_type.' style="width: 17%;" class="speicaltaskmulittask_'.$profile_field_name.' btn btn-danger btn-small" onclick="removethisvaluetask('.$baseurl.')" title="Delete"><i class="fas fa-trash"></i></button></p>';
                               
                               
                           }
                           
                           $action_col .= '<p><button '.$fields_staus_type.' style="width: 27%;float: right;" class="speicaltaskmulittask_'.$profile_field_name.' disableclassbutton_'.$profile_field_name.' btn btn-info btn-small" onclick="addnewmultivalueinput('.$profile_field_namespecial.','.$multivaluetasklimit.')" title="Add">Add <i class="fas fa-plus" ></i></button></p>'; 
                           
                          
                           
                           $action_col .="</div>";
                           break;
                       
                     case 'link':
                        // echo $profile_field_settings['lin_url'] ;exit;
                           $action_col .= '<a href="' . $profile_field_settings['lin_url'] . '"target="_blank" ';
                           if (!empty($profile_field_settings['taskattrs'])){
                               $action_col .= $profile_field_settings['taskattrs'];
                           }
                               $action_col.= '>' . $profile_field_settings['linkname'] . '</a>';
                       
                           break;
                   }
                   
                    
                    
                   $type = "'".$profile_field_settings['type']."'";
                   $background_color='';
                   if($status_value == 'Complete'){
                                $special_check_buttons_status_remove = 'class="fusion-li-icon fa fa-times-circle fa-2x specialremoveiconenable" ';
                                $special_check_buttons_status_submit = 'class="progress-button taskcustomesubmit disableremovebutton"';
                                $submit_button_text = 'Submitted';
                                $background_color = 'style="background-color:#d5f1d5;"';
                            }else{
                                $special_check_buttons_status_remove = 'class="fusion-li-icon fa fa-times-circle fa-2x specialremoveicondisable" ';
                                $special_check_buttons_status_submit = 'class="progress-button taskcustomesubmit" ';
                                $submit_button_text = 'Submit';
                    }
                   if($lockdownstatus == 'checked' ){ 
                        
                            
                            $status_col .= '<table><tr style="background-color: transparent;" ><td><button    class="progress-button taskcustomesubmit disableremovebutton" >'.$submit_button_text.'</button></td>';
                            $status_col .= '<td><i  name="'.$profile_field_name.'" data-toggle="tooltip" title="Remove this task"  name="'.$profile_field_name.'" class="fusion-li-icon fa fa-times-circle fa-2x specialremoveicondisable"   ></i><td></tr></table>';
                    
                    
                    }else{
                            
                            
                            $status_col .= '<table><tr style="background-color: transparent;" ><td><button onclick="update_user_meta_custome(this,'.$type.')"  id="update_' . $profile_field_name . '_status" '.$special_check_buttons_status_submit.'  data-style="shrink" data-horizontal>'.$submit_button_text.'</button></td>';
                            $status_col .= '<td><i  name="'.$profile_field_name.'" data-toggle="tooltip" title="Remove this task" onclick="remove_task_value_readyfornew(this,'.$type.')" name="'.$profile_field_name.'" '.$special_check_buttons_status_remove.' id="update_' . $profile_field_name . '_remove"   ></i><td></tr></table>';
                    
                            
                    }
                   
                   
                   
                   
                   
                  

                   
                   
                  echo $duedate_html .= '<td class="content-vertical-middle">'.$action_col.'</td><td class="'.$profile_field_name.'_submissionstatus content-vertical-middle" '.$background_color.'>'.$status_col.'</td></tr>';
                
               }  
                
                
            }
           
           
           
           ?>
           
                   
                    
                </tbody>
                    
                </table>
    
    
    
 
</div>              

    <?php }
    get_footer(); 

?>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    
    <script>
    
    jQuery(document).ready(function() {
    jQuery('.egpl_single_select2').select2();
    });
    
    
    </script>