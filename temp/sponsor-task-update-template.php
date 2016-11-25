<?php
// Template Name: Sponsor Task Update 
 
       
      get_header();
		
     
          $sponsor_id = get_current_user_id(); 
         
     
      
      $test = 'user_meta_manager_data';
      $result = get_option($test);
      $settitng_key = 'ContenteManager_Settings';
      $sponsor_info = get_option($settitng_key);
      $sponsor_name = $sponsor_info['ContentManager']['sponsor-name'];
    //echo '<pre>';
     //print_r( );
      global $wp_roles;
     
      $all_roles = $wp_roles->get_names();
     
      
                ?>
                
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
                    <?php
                 
                    $user_IDD = $sponsor_id;
                    $base_url = "http://" . $_SERVER['SERVER_NAME'];
                    $d = 1;
                    $umm_options = umm_get_option();
                    $umm_settings = $umm_options['settings'];
                    $umm_data = $umm_options['custom_meta'];
                    $profile_fields = $umm_options['profile_fields'];
                    $sort_order = $umm_options['sort_order'];
                    if (empty($sort_order) || !is_array($sort_order))
                        $sort_order = false;
                    /* If this is a short code, $fields string is to be converted to an array. */
                    $show_fields = ($fields) ? explode(",", str_replace(", ", ",", $fields)) : false;
                    //if($debug) print_r($profile_fields);
                    if (!empty($profile_fields)):
                        $output = "";
                        /* Sort the profile fields */
                        if ($sort_order):
                            $new_array = array();
                            foreach ($sort_order as $profile_field_name):
                                if ($debug)
                                    print_r($profile_field_name);
                                if (isset($profile_fields[$profile_field_name]))
                                    $new_array[$profile_field_name] = $profile_fields[$profile_field_name];
                            endforeach;
                            $profile_fields = $new_array;
                        endif;
                        /* If this is a short code reduce the array to only the fields which should be displayed */
                        if ($show_fields):
                            $new_array = array();
                            foreach ($show_fields as $profile_field_name):
                                if (isset($profile_fields[$profile_field_name]))
                                    $new_array[$profile_field_name] = $profile_fields[$profile_field_name];
                            endforeach;
                            $profile_fields = $new_array;
                        endif;

                        $form_tag = (!$form_id) ? '' : ' form="' . $form_id . '"';

                        $html_before = '
<table class="mytable table table-striped table-bordered table-condensed" ><thead><tr class="text_th" ><th class="duedate-bg">Due Date</th><th id="task-bg">Task</th><th id="spec-bg">Specifications</th><th id="action-bg">Action</th><th id="status-bg">Status</th></tr></thead><tbody>';



                        $output .= $html_before;

                        $current_user = get_userdata( $sponsor_id );

                        //print_r($profile_fields);
                        //   $mode = next($profile_fields); // $mode = 'foot';
                        //   echo '<pre>';
                        // print_r($mode);
                        //$mode = next($transport);  
                        //echo '<pre>';
                        //print_r($profile_fields);
                        //Modification by Qasim Riaz	
                        foreach ($profile_fields as $profile_field_name => $profile_field_settings):


                            if ($profile_field_settings['type'] == 'random_string'):
                                continue;
                            endif;



                            //  print_r($pieces);
                            $user_can_view = false;
                            if (isset($profile_field_settings['roles']) && is_array($profile_field_settings['roles'])):
                                foreach ($profile_field_settings['roles'] as $role):
                                    if ((is_array($current_user->caps) && array_key_exists($role, $current_user->caps)) || (empty($current_user->caps) && $role == 'visitor') || $role == 'all'):
                                        $user_can_view = true;
                                    endif;
                                endforeach;
                            else:
                                $user_can_view = true;
                            endif;
                            
                            if (( strpos($profile_field_name, "task") !== false && $user_can_view ) ){
                                
                            if(strpos($profile_field_name, "_datetime") !== false){
                                //echo $profile_field_name.'<br>';
                            }else{
                                $default_value = stripslashes(htmlspecialchars_decode($profile_field_settings['value']));
                                $the_user = $sponsor_id;//((isset($_REQUEST['user_id']) && !empty($_REQUEST['user_id'])) && current_user_can('add_users')) ? $_REQUEST['user_id'] : $current_user->ID;
                                $existing_value = get_user_meta($the_user, $profile_field_name, true);
                                $value = (!is_array($existing_value)) ? stripslashes(htmlspecialchars_decode($existing_value)) : $existing_value;
                                if ($mode == 'register' || $mode == 'adduser')
                                    $value = $default_value;

                                $label = stripslashes(htmlspecialchars_decode($profile_field_settings['label']));
                                $unique = ($profile_field_settings['unique'] == 'yes') ? ' umm-unique' : '';
                                $field_html = '';
                                switch ($profile_field_settings['type']) {

                                    case 'text':
                                    case 'date':
                                    case 'datetime':
                                    case 'datetime-local':
                                    case 'email':
                                    case 'month':
                                    case 'number':
                                    case 'range':
                                    case 'search':
                                    case 'tel':
                                    case 'time':
                                    case 'url':
                                    case 'week':
                                        $field_html .= '<input class="myclass" type="' . $profile_field_settings['type'] . '" id="' . $profile_field_name;
                                        if ($mode == 'adduser')
                                            $field_html .= '[]';
                                        $field_html .= '" value="' . $value . '" class="' . stripslashes(htmlspecialchars_decode($profile_field_settings['class'])) . $unique . '"';
                                        if ($profile_field_settings['required'] == 'yes')
                                            $field_html .= ' required="required"';
                                        if (!empty($profile_field_settings['taskattrs']))
                                            $field_html .= $profile_field_settings['taskattrs'];
                                        $field_html .= $form_tag . " />";
                                        break;
                                    //Modification by Qasim Riaz
                                    case 'color':

                                        if (!empty($value)) {
                                            $field_html .='<div class="' . $profile_field_name . '" style="display:none;">';
                                        }

                                        $field_html .= '<input class="uploadFileid"  id="display_my' . $profile_field_name . '" placeholder="Choose File" disabled="disabled" /><div class="fusion-button fusion-button-default fusion-button-medium fusion-button-round fusion-button-flat" id="fileUpload"><span>Browse</span><input ' . $profile_field_settings['taskattrs'] . ' type="file" class ="upload myfileuploader" id="my' . $profile_field_name . '" name="my' . $profile_field_name . '" /></div>';
                                        if (!empty($value)) {
                                            $field_html .='</div>';
                                        }
                                        $field_html .= '<input type="hidden" id="hd_' . $profile_field_name . '"';
                                        if (!empty($value)) {
                                            $field_html .= ' value="' . base64_encode(serialize($value)) . '"';
                                        }

                                        $field_html .= 'class="' . stripslashes(htmlspecialchars_decode($profile_field_settings['class'])) . $unique . '"';
                                        if ($profile_field_settings['required'] == 'yes')
                                            $field_html .= ' required="required"';
                                        if (!empty($profile_field_settings['taskattrs']))
                                            $field_html .= ' ';
                                        $field_html .= $form_tag . " />";
                                        if (!empty($value)) {
                                            $field_html .= "<div class='remove_" . $profile_field_name . "'><a href='" . $base_url . "/wp-content/plugins/contentmanager/download-lib.php?userid=" . $user_IDD . "&fieldname=" . $profile_field_name . "' target='_blank' style='margin-right: 24px;'>Download File</a><a  style='width:75px;' id='remove_" . $profile_field_name . "' class='" . $profile_field_name . " btn-danger btn remove_upload' >Remove</a></div>";
                                        }
                                        break;
                                    case 'textarea':
                                        $field_html .= '<textarea rows="5"  class="myclasstextarea" id="' . $profile_field_name . '" name="' . $profile_field_name;
                                        if ($mode == 'adduser')
                                            $field_html .= '[]';
                                        $field_html .= '" class="' . stripslashes(htmlspecialchars_decode($profile_field_settings['class'])) . $unique . '"';
                                        if ($profile_field_settings['required'] == 'yes')
                                            $field_html .= ' required="required"';
                                        if (!empty($profile_field_settings['taskattrs']))
                                            $field_html .= $profile_field_settings['taskattrs'];
                                        $field_html .= $form_tag . '>' . $value . '</textarea>';
                                        if (!empty($profile_field_settings['taskattrs']))
                                            $field_html .='<span style="font-size:10px;padding-top: 20px;padding-left: 4px;padding-right: 7px;" id="chars_' . $profile_field_name . '">' . str_replace("maxlength=", "", $profile_field_settings['taskattrs']) . '</span><span style="font-size:10px;">characters remaining</span>';
                                        break;
                                    //Modification by Qasim Riaz
                                    case 'none':
                                        $field_html .= '';
                                        break;
                                    case 'comingsoon':
                                        $field_html .= '<strong >Coming soon</strong>';
                                        break;
                                    //Modification by Qasim Riaz
                                    case 'link':
                                        $field_html .= '<a href="' . $profile_field_settings['lin_url'] . '"target="_blank"';
                                        if (!empty($profile_field_settings['taskattrs']))
                                            $field_html .= $profile_field_settings['taskattrs'];
                                        $field_html.= '>' . $profile_field_settings['linkname'] . '</a>';

                                        break;

                                    case 'checkbox':
                                        $field_html .= '<input type="checkbox" name="' . $profile_field_name;
                                        if ($mode == 'adduser')
                                            $field_html .= '[]';
                                        $field_html .= '" value="' . $profile_field_settings['value'] . '" class="' . stripslashes(htmlspecialchars_decode($profile_field_settings['class'])) . $unique . '"';
                                        if ($profile_field_settings['required'] == 'yes')
                                            $field_html .= ' required="required"';

                                        if ((($mode == 'register' || $mode == 'adduser') && (isset($profile_field_settings['initial_state']) && $profile_field_settings['initial_state'] == 'checked')) || ($value == $profile_field_settings['value'] && ($mode != 'register' && $mode != 'adduser'))):
                                            $field_html .= ' checked="checked" data-mode="' . $existing_value . '"';
                                        endif;
                                        if (!empty($profile_field_settings['attrs']))
                                            $field_html .= ' ';
                                        $field_html .= $form_tag . ' />' . "\n";
                                        break;

                                    case 'checkbox_group':
                                        $x = 0;
                                        foreach ($profile_field_settings['options'] as $option => $option_settings):
                                            if (!empty($option_settings['label'])):
                                                $field_html .= '<span class="umm-checkbox-group-item"><input type="checkbox" name="' . $profile_field_name;
                                                $field_html .= '[]" value="' . $option_settings['value'] . '" class="' . stripslashes(htmlspecialchars_decode($profile_field_settings['class'])) . $unique . '"';
                                                if ($profile_field_settings['required'] == 'yes')
                                                    $field_html .= ' required="required"';
                                                if ((is_array($value) && in_array($option_settings['value'], $value)) || (($mode == 'register' || $mode == 'adduser') && ($option_settings['state'] == 'checked')))
                                                    $field_html .= ' checked="checked"';
                                                if (!empty($profile_field_settings['attrs']))
                                                    $field_html .= ' ';
                                                $field_html .= $form_tag . ' />' . stripslashes($option_settings['label']) . "</span> \n";
                                            endif;
                                            $x++;
                                        endforeach;
                                        break;

                                    case 'radio':
                                        $i = 1;
                                        foreach ($profile_field_settings['options'] as $option => $option_settings):
                                            if (!empty($option_settings['label'])):
                                                $field_html .= '<input id="umm_radio_' . $i . '" type="' . $profile_field_settings['type'] . '" name="' . $profile_field_name;

                                                $field_html .= '" value="' . $option_settings['value'] . '" class="' . stripslashes(htmlspecialchars_decode($profile_field_settings['class'])) . $unique . '"';
                                                if ($profile_field_settings['required'] == 'yes')
                                                    $field_html .= ' required="required"';
                                                if ((isset($option_settings['value']) && $option_settings['value'] == $value) || (($mode == 'register' || $mode == 'adduser') && (isset($option_settings['state']) && $option_settings['state'] == 'checked')))
                                                    $field_html .= ' checked="checked"';
                                                if (!empty($profile_field_settings['attrs']))
                                                    $field_html .= ' ';
                                                $field_html .= $form_tag . ' /><span class="' . str_replace(" ", "-", strtolower($profile_field_name)) . '">' . $option_settings['label'] . '</span> ';
                                            endif;
                                            $i++;
                                        endforeach;
                                        break;
                                    //Modification by Qasim Riaz start
                                    case 'select-2':
                                        //echo $value.'--------';
                                        $multi = ((isset($profile_field_settings['allow_multi']) && $profile_field_settings['allow_multi'] == 'yes') || ($mode == 'adduser')) ? '[]' : '';
                                        $multiple = (isset($profile_field_settings['allow_multi']) && $profile_field_settings['allow_multi'] == 'yes') ? ' multiple="multiple"' : '';
                                        $size = (!isset($profile_field_settings['size']) || $profile_field_settings['size'] < 1) ? ' size="1"' : ' size="' . $profile_field_settings['size'] . '"';
                                        $field_html .= '<select name="' . $profile_field_name . $multi . '" id="' . $profile_field_name . $multi . '" class="selectclass"';
                                        if ($profile_field_settings['required'] == 'yes')
                                            $field_html .= ' required="required"';
                                        if (!empty($profile_field_settings['attrs']))
                                            //$field_html .= ' ' . stripslashes(htmlspecialchars_decode($profile_field_settings['attrs']));
                                        $field_html .= $multiple . $size . $form_tag . '>' . "\n";
                                        foreach ($profile_field_settings['options'] as $option => $option_settings):
                                            if (!empty($option_settings['label'])):
                                                $field_html .= '<option value="' . stripslashes($option_settings['value']) . '"';
                                                if ((!is_array($value) && $option_settings['value'] == $value) || (is_array($value) && in_array($option_settings['value'], $value)) || (($mode == 'register' || $mode == 'adduser') && ($option_settings['state'] == 'checked')))
                                                    $field_html .= ' selected="selected"';
                                                $field_html .= '>' . stripslashes($option_settings['label']) . '</option>
            ';
                                            endif;
                                        endforeach;

                                        $field_html .= "</select>\n";
                                        break;
                                    //Modification by Qasim Riaz end
                                    case 'select':
                                        $multi = ((isset($profile_field_settings['allow_multi']) && $profile_field_settings['allow_multi'] == 'yes') || ($mode == 'adduser')) ? '[]' : '';
                                        $multiple = (isset($profile_field_settings['allow_multi']) && $profile_field_settings['allow_multi'] == 'yes') ? ' multiple="multiple"' : '';
                                        $size = (!isset($profile_field_settings['size']) || $profile_field_settings['size'] < 1) ? ' size="1"' : ' size="' . $profile_field_settings['size'] . '"';
                                        $field_html .= '<select style="width: 112px; " name="' . $profile_field_name . $multi . '"  id="' . $profile_field_name . $multi . '" class="selectclass"';
                                        if ($profile_field_settings['required'] == 'yes')
                                            $field_html .= ' required="required"';
                                        if (!empty($profile_field_settings['attrs']))
                                            $field_html .= ' ' . stripslashes(htmlspecialchars_decode($profile_field_settings['attrs']));
                                        $field_html .= $multiple . $size . $form_tag . '>' . "\n";
                                        foreach ($profile_field_settings['options'] as $option => $option_settings):
                                            if (!empty($option_settings['label'])):
                                                $field_html .= '<option value="' . stripslashes($option_settings['value']) . '"';
                                                if ((!is_array($value) && $option_settings['value'] == $value) || (is_array($value) && in_array($option_settings['value'], $value)) || (($mode == 'register' || $mode == 'adduser') && ($option_settings['state'] == 'checked')))
                                                    $field_html .= ' selected="selected"';
                                                $field_html .= '>' . stripslashes($option_settings['label']) . '</option>
            ';
                                            endif;
                                        endforeach;
                                        $field_html .= '</select><button onclick="update_user_meta_custome(this)" id="update_' . $profile_field_name . '" class="progress-button" data-style="shrink" data-horizontal>Save</button>';



                                        break;

                                    default:
                                        $field_html .= '<input type="text" name="' . $profile_field_name;
                                        if ($mode == 'adduser')
                                            $field_html .= '[]';
                                        $field_html .= '" value="' . $value . '" class="' . stripslashes(htmlspecialchars_decode($profile_field_settings['class'])) . $unique . '"';
                                        if ($profile_field_settings['required'] == 'yes')
                                            $field_html .= ' required="required"';
                                        if (!empty($profile_field_settings['attrs']))
                                            $field_html .= ' ';
                                        $field_html .= $form_tag . ' />';
                            }
                           

                                if (!empty($profile_field_settings['after'])):
                                    $field_html .= stripslashes(htmlspecialchars_decode($profile_field_settings['after']));
                                endif;
                                $action = '';
                                $status = '';
                                $attribute = '';

                                $taskid = explode("_", $profile_field_name);
                                $task_value = substr($taskid[0], 1);


                                if (strpos($profile_field_name, "status") !== false) {
                                    $status = "<td class='action'>[field]</td></tr>";
                                }
                                if (strpos($profile_field_name, "status") == false) {
                                    $action = '<td class="status">[field]</td>';
                                }
                                if (strpos($profile_field_name, "status") == false) {
                                    $date2 = date_create($profile_field_settings['attrs']);
                                    $date1 = date_create(date("d-M-y"));
                                    $diff = date_diff($date1, $date2);
                                    $value = $diff->format("%R%a");
                                    $timestamp = strtotime($profile_field_settings['attrs']) ;

                                    if ($value < 0) {

                                        $attribute = '<tr class="overdue"><td  data-order="'. $timestamp.'" class="duedate ' . $profile_field_name . '_status">' . $profile_field_settings['attrs'] . ' <span class="icon-wrapper circle-no"><i class="fusion-li-icon fa fa-flag" style="color:#5D5858;"></i></span></td><td class="checklist">' . $profile_field_settings['label'] . '</td><td class="descrpition">' . $profile_field_settings['descrpition'] . '</td>';
                                    } else {
                                        $attribute = '<td class="duedate"  data-order="'. $timestamp.'" >' . $profile_field_settings['attrs'] . '</td><td class="checklist">' . $profile_field_settings['label'] . '</td><td class="descrpition">' . $profile_field_settings['descrpition'] . '</td>';
                                    }
                                }

                                $html_during = $attribute . $action . $status;


                                $html_during = str_replace('[label]', $label, stripslashes(htmlspecialchars_decode($html_during)));
                                $html_during = str_replace('[field]', $field_html, $html_during);
                                $html_during = str_replace('[field-name]', $profile_field_name, $html_during);
                                $html_during = str_replace('[field-slug]', str_replace("_", "-", strtolower($profile_field_name)), $html_during);
                                if (umm_is_pro() && isset($profile_field_settings['display']) && is_array($profile_field_settings['display']) && in_array('profile', $profile_field_settings['display']) && $mode == 'profile'):
                                    $output .= $html_during;
                                elseif (umm_is_pro() && isset($profile_field_settings['display']) && is_array($profile_field_settings['display']) && in_array('register', $profile_field_settings['display']) && $mode == 'register'):
                                    $output .= $html_during;
                                elseif ($mode == 'adduser'):
                                    $output .= $html_during;
                                elseif (!umm_is_pro() || (umm_is_pro() && !isset($profile_field_settings['display']) || $mode == 'shortcode')):
                                    $output .= $html_during;
                                endif;

                            } // $show_fields
                            
                            
                            }
                          
                        endforeach;
                        $html_after = (!isset($umm_settings['html_after_' . $mode]) || empty($umm_settings['html_after_' . $mode])) ? '</tbody>
</table>' : stripslashes(htmlspecialchars_decode($umm_settings['html_after_' . $mode]));
                        $umm_nonce = wp_create_nonce('umm_wp_nonce');
                        $output .= $html_after . "\n" . '<input type="hidden" name="umm_nonce" value="' . $umm_nonce . '"' . $form_tag . ' />' . "\n";
                    endif; // !empty($profile_fields)

                    

                       

                       
                            echo $output;
                        

                  
                    ?>


                </div>
				<?php get_footer(); 

   ?>