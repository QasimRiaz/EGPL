<?php
// Template Name: Sponsor Task Update 
 
   if ( is_user_logged_in() ) {    
      get_header();
		
     
     $sponsor_id = get_current_user_id(); 
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
     ?>
          <script>
        
            
        
        currentsiteurl = '<?php echo $site_url;?>';
        
        
    </script>       
<div id="content" class="full-width">

        <div id="sponsor-status"></div>
              <?php
    // TO SHOW THE PAGE CONTENTS
    while ( have_posts() ) : the_post(); ?> <!--Because the_content() works only inside a WP Loop -->
        <div class="entry-content-page">
            <?php the_content(); ?> <!-- Page Content -->
        </div><!-- .entry-content-page -->

    <?php
    endwhile; //resetting the page loop?>

   
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
           
         
           foreach ($result as $taskIndex => $taskObject){
                    
               
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
                       
                       if ($result_date < 0) {
                           
                       }else{
                           $lockdownstatus = 'checked';
                           $fields_staus_type='disabled';
                           $file_fields_staus_type='disabled';
                       }
                       
                   }
                   
                   
                   if ($result_date < 0) {

                       $duedate_html = '<td class="duedate"  data-order="' . $timestamp_task_data . '" >' . $profile_field_settings['attrs'] . '</td><td class="checklist">' . $profile_field_settings['label'] . '</td><td class="descrpition">' . stripslashes($profile_field_settings['descrpition']) . '</td>';
                   
                       
                   } else {
                     
                       $duedate_html = '<tr class="overdue"><td  data-order="' . $timestamp_task_data . '" class="duedate ' . $profile_field_name . '_status">' . $profile_field_settings['attrs'] . ' <span class="icon-wrapper circle-no"><i class="fusion-li-icon fa fa-flag" style="color:#5D5858;"></i></span></td><td class="checklist">' . $profile_field_settings['label'] . '</td><td class="descrpition">' . stripslashes($profile_field_settings['descrpition']) . '</td>';
                       
                       
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
                           
                           $action_col .= '<input '.$fields_staus_type.' class="myclass" type="text" id="' . $profile_field_name;
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
                           $action_col .= '<select '.$fields_staus_type.' name="' . $profile_field_name . $multi . '" id="' . $profile_field_name . $multi . '" class="selectclass"';
                          
                           if ($profile_field_settings['required'] == 'yes')
                               $field_html .= ' required="required"';
                           if (!empty($profile_field_settings['attrs']))
                           //$field_html .= ' ' . stripslashes(htmlspecialchars_decode($profile_field_settings['attrs']));
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
                     case 'link':
                        // echo $profile_field_settings['lin_url'] ;exit;
                           $action_col .= '<a href="' . $profile_field_settings['lin_url'] . '"target="_blank" ';
                           if (!empty($profile_field_settings['taskattrs'])){
                               $action_col .= $profile_field_settings['taskattrs'];
                           }
                               $action_col.= '>' . $profile_field_settings['linkname'] . '</a>';
                       
                           break;
                   }
                   
                    
                    
                   
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
                            
                            
                            $status_col .= '<table><tr style="background-color: transparent;" ><td><button onclick="update_user_meta_custome(this)"  id="update_' . $profile_field_name . '_status" '.$special_check_buttons_status_submit.'  data-style="shrink" data-horizontal>'.$submit_button_text.'</button></td>';
                            $status_col .= '<td><i  name="'.$profile_field_name.'" data-toggle="tooltip" title="Remove this task" onclick="remove_task_value_readyfornew(this)" name="'.$profile_field_name.'" '.$special_check_buttons_status_remove.' id="update_' . $profile_field_name . '_remove"   ></i><td></tr></table>';
                    
                            
                    }
                   
                   
                   
                   
                   
                  

                   
                   
                  echo $duedate_html .= '<td class="content-vertical-middle">'.$action_col.'</td><td class="'.$profile_field_name.'_submissionstatus content-vertical-middle" '.$background_color.'>'.$status_col.'</td></tr>';
                
               }  
                
                
            }
           
           
           
           ?>
           
                   
                    
                </tbody>
                    
                </table>
    
    
    
 
</div>              

<?php 
    get_footer(); 
}else{
     $redirect = get_site_url();
    wp_redirect( $redirect );exit;
}
?>