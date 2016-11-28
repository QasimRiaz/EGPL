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
      
      $test = 'custome_task_manager_data';
      $result = get_option($test);
      $settitng_key = 'ContenteManager_Settings';
      $sponsor_info = get_option($settitng_key);
      $sponsor_name = $sponsor_info['ContentManager']['sponsor-name'];
      $current_user = get_userdata( $sponsor_id );
      $user_IDD = $sponsor_id;
      $base_url = "http://" . $_SERVER['SERVER_NAME'];
    //echo '<pre>';
    // print_r( $result); exit;
      global $wp_roles;
     
      $all_roles = $wp_roles->get_names();
     
      
                ?>
                
       <div id="content" class="full-width">

                    <div id="sponsor-status"></div>
                     <?php
                        // TO SHOW THE PAGE CONTENTS
                        while (have_posts()) : the_post();
                            ?> <!--Because the_content() works only inside a WP Loop -->
                            <div class="entry-content-page">
                            <?php the_content(); ?> <!-- Page Content -->
                            </div><!-- .entry-content-page -->

                            <?php endwhile; //resetting the page loop
                        ?>
                    <div style="padding-bottom: 10px;">
                    <h3 style="float:left;width:50%;"><?php echo $sponsor_name;?> Task</h3>
                    <h3 style="float:right;margin-right: 20px;">For : <?php echo $meta_for_user->display_name; ?></h3>
                    </div>
                   
                     <table class="mytable table table-striped table-bordered table-condensed" >
                <thead>
                    <tr class="text_th" >
                        <th class="duedate-bg">Due Date</th>
                        <th id="task-bg">Task</th>
                        <th id="spec-bg">Specifications</th>
                        <th id="action-bg">Action</th>
                        <th id="status-bg">Status</th>
                    </tr></thead>
                <tbody>
           <?php
           
         
           foreach ($result['profile_fields'] as $profile_field_name => $profile_field_settings){
                    
               
               
               
               
                $user_can_view = false;
                $action_col = "";
                $status_col = "";
               if (isset($profile_field_settings['roles']) && is_array($profile_field_settings['roles'])){
                   foreach ($profile_field_settings['roles'] as $role){
                       if ((is_array($current_user->caps) && array_key_exists($role, $current_user->caps)) || (empty($current_user->caps) && $role == 'visitor') || $role == 'all'){
                           $user_can_view = true;
                       }
                   }
               }else{
                   $user_can_view = true;
               }
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
                   
                   if ($result_date < 0) {

                       $duedate_html = '<td class="duedate"  data-order="' . $timestamp_task_data . '" >' . $profile_field_settings['attrs'] . '</td><td class="checklist">' . $profile_field_settings['label'] . '</td><td class="descrpition">' . $profile_field_settings['descrpition'] . '</td>';
                   
                       
                   } else {
                     
                       $duedate_html = '<tr class="overdue"><td  data-order="' . $timestamp_task_data . '" class="duedate ' . $profile_field_name . '_status">' . $profile_field_settings['attrs'] . ' <span class="icon-wrapper circle-no"><i class="fusion-li-icon fa fa-flag" style="color:#5D5858;"></i></span></td><td class="checklist">' . $profile_field_settings['label'] . '</td><td class="descrpition">' . $profile_field_settings['descrpition'] . '</td>';
                       
                       
                   }
                   
                    switch ($profile_field_settings['type']) {
                        
                        
                       case 'text':
                       case 'date':
                       case 'datetime':
                       case 'number':
                       case 'email':
                       case 'url':
                           
                           $action_col .= '<input class="myclass" type="' . $profile_field_settings['type'] . '" id="' . $profile_field_name;
                           $action_col .= '" value="'.$value.'" >';
                           break;
                       case 'color':
                           

                           if (!empty($value)) {
                               $action_col .='<div class="' . $profile_field_name . '" style="display:none;">';
                           }

                           $action_col .= '<input class="uploadFileid"  id="display_my' . $profile_field_name . '" placeholder="Choose File" disabled="disabled" /><div class="fusion-button fusion-button-default fusion-button-medium fusion-button-round fusion-button-flat" id="fileUpload"><span>Browse</span><input ' . $profile_field_settings['taskattrs'] . ' type="file" class ="upload myfileuploader" id="my' . $profile_field_name . '" name="my' . $profile_field_name . '" /></div>';
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
                               $action_col .= "<div class='remove_" . $profile_field_name . "'><a href='" . $base_url . "/wp-content/plugins/contentmanager/download-lib.php?userid=" . $user_IDD . "&fieldname=" . $profile_field_name . "' target='_blank' style='margin-right: 24px;'>Download File</a><a  style='width:75px;' id='remove_" . $profile_field_name . "' class='" . $profile_field_name . " btn-danger btn remove_upload' >Remove</a></div>";
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
                       case 'link':
                           $action_col .= '<a href="' . $profile_field_settings['lin_url'] . '"target="_blank"';
                           if (!empty($profile_field_settings['taskattrs']))
                               $action_col .= $profile_field_settings['taskattrs'];
                           $action_col.= '>' . $profile_field_settings['linkname'] . '</a>';

                           break;
                      case 'textarea':
                           
                           $action_col .= '<textarea rows="5"  class="myclasstextarea" id="' . $profile_field_name . '" name="' . $profile_field_name;
                           if ($mode == 'adduser')
                               $field_html .= '[]';
                           $action_col .= '" class="' . stripslashes(htmlspecialchars_decode($profile_field_settings['class'])) . $unique . '"';
                           if ($profile_field_settings['required'] == 'yes')
                               $action_col .= ' required="required"';
                           if (!empty($profile_field_settings['taskattrs']))
                               $action_col .= $profile_field_settings['taskattrs'];
                           $action_col .= $form_tag . '>' . $value . '</textarea>';
                           if (!empty($profile_field_settings['taskattrs']))
                               $action_col .='<span style="font-size:10px;padding-top: 20px;padding-left: 4px;padding-right: 7px;" id="chars_' . $profile_field_name . '">' . str_replace("maxlength=", "", $profile_field_settings['taskattrs']) . '</span><span style="font-size:10px;">characters remaining</span>';
                           break;
                     case 'select-2':
                                      
                           $multi = ((isset($profile_field_settings['allow_multi']) && $profile_field_settings['allow_multi'] == 'yes') || ($mode == 'adduser')) ? '[]' : '';
                           $multiple = (isset($profile_field_settings['allow_multi']) && $profile_field_settings['allow_multi'] == 'yes') ? ' multiple="multiple"' : '';
                           $size = (!isset($profile_field_settings['size']) || $profile_field_settings['size'] < 1) ? ' size="1"' : ' size="' . $profile_field_settings['size'] . '"';
                           $action_col .= '<select name="' . $profile_field_name . $multi . '" id="' . $profile_field_name . $multi . '" class="selectclass"';
                           if ($profile_field_settings['required'] == 'yes')
                               $field_html .= ' required="required"';
                           if (!empty($profile_field_settings['attrs']))
                           //$field_html .= ' ' . stripslashes(htmlspecialchars_decode($profile_field_settings['attrs']));
                               $action_col .= $multiple . $size . $form_tag . '>' . "\n";
                           foreach ($profile_field_settings['options'] as $option => $option_settings):
                               if (!empty($option_settings['label'])):
                                   $action_col .= '<option value="' . stripslashes($option_settings['value']) . '"';
                                   if ((!is_array($value) && $option_settings['value'] == $value) || (is_array($value) && in_array($option_settings['value'], $value)) || (($mode == 'register' || $mode == 'adduser') && ($option_settings['state'] == 'checked')))
                                       $action_col .= ' selected="selected"';
                                   $action_col .= '>' . stripslashes($option_settings['label']) . '</option>';
        
                               endif;
                           endforeach;

                           $action_col .= "</select>\n";
                           break;
                   }
                   
                   $status_col .= '<select style="font-size: 16px; width: 112px; " name="' . $profile_field_name .'_status"  id="' . $profile_field_name . '_status" class="selectclass" >';
                   
                  
                   $status_col .= '<option value="Pending">Pending</option>';
                    if($status_value == 'Complete'){
                       $status_col .= '<option value="Complete" selected="selected">Complete</option>';
                   }else{
                        $status_col .= '<option value="Complete" >Complete</option>';
                   }
                   
                   
                   $status_col .= '</select><button onclick="update_user_meta_custome(this)" id="update_' . $profile_field_name . '_status" class="progress-button" data-style="shrink" data-horizontal>Save</button>';

                   
                   
                  echo $duedate_html .= '<td>'.$action_col.'</td><td>'.$status_col.'</td></tr>';
                
               }  
                
                
            }
           
           
           
           ?>
           
                   
                    
                </tbody>
                    
                </table>


                </div>
       
				<?php get_footer(); 
       
   }else{
       echo 'Not Found';
   }
   ?>