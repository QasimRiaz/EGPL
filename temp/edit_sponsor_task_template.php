<?php /* 

Template Name: Admin Edit Tasks */ 

if ( is_user_logged_in() ) { 
     if (current_user_can('administrator') || current_user_can('contentmanager') ){
//wp_head();
get_header();

    global $wpdb, $wp_hasher;
    $site_prefix = $wpdb->get_blog_prefix();

     if(!empty($_GET['sponsorid'])){
         
         
         
          $sponsor_id=$_GET['sponsorid'];
          $meta_for_user = get_userdata( $sponsor_id );
          $all_meta_for_user = get_user_meta($sponsor_id );
		  
		  
         // echo '<pre>';
        //  print_r( $meta_for_user );
          
      }

     //$sponsor_id = get_current_user_id(); 
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
     $base_url = "https://" . $_SERVER['SERVER_NAME'];
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
//      
     ?>
    <link href="/wp-content/plugins/EGPL/cmtemplate/css/lib/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />	
    <script>
        
            
        
        currentsiteurl = '<?php echo $site_url;?>';
        
        
    </script>
			
<div id="content" class="full-width">

        <div id="sponsor-status"></div>
              <?php
   
    
    
    if ( is_user_logged_in() ) {    
    
    ?>


			</div>
            <div id="hiddenform"></div>
          
			
			
						<div class="container mb-8 mt-8">
                                                    <div class="p-6"><div class="card card-custom gutter-b">
                                                            
                                                                        <div class="card-header">
										<div class="card-title">
											<span class="card-icon">
												<i class="fa fa-tasks text-primary fa-lg"></i>
											</span>
                                                                                    <h4>Exhibitor Task List </h4>
										</div>
                                                                            <h4 style="margin-top: 20px;">For : <?php echo $all_meta_for_user[$site_prefix.'first_name'][0] .' '.$all_meta_for_user[$site_prefix.'last_name'][0]; ?></h4>
									</div>
									<div class="card-body">
										<!--begin::Top-->
										
                                                                                
										
                                                                                
                                                                                <div class="d-flex">
                                                                                <?php // TO SHOW THE PAGE CONTENTS
                                                                                while ( have_posts() ) : the_post(); ?> <!--Because the_content() works only inside a WP Loop -->
                                                                                    <div class="entry-content-page">
                                                                                        <?php the_content(); ?> <!-- Page Content -->
                                                                                    </div><!-- .entry-content-page -->

                                                                                <?php
                                                                                endwhile; //resetting the page loop?>	
										</div>
										<!--end::Top-->
										
									</div>
								</div></div>
                                                    
                                                    
                                                    
							<div class="">
								<div class="">
									<div class="p-6">
										
										
										
									
	
	
	
	
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
                      
                          
                     if($keyvalue == 'user_login'){
                          
                       
                           
                          $taskdescription = str_replace("{user_login}",$userdata->user_login,$taskdescription);
                         
                          
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
                         // $data_field_array[] = array('name'=>$index,'content'=>$currentuserRole); 
                          
                          $taskdescription = str_replace("{level}",$currentuserRole,$taskdescription);
                      }elseif($keyvalue == 'Semail'){
                          
                           $taskdescription = str_replace("{email}",$userdata->user_email ,$taskdescription);
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
                            
                            $linktext = "<a href='".$site_url."' target='_blank' >".$site_url."</a>";
                            $taskdescription = str_replace("{site_url}",$linktext,$taskdescription);
                            $tagvalue = "{site_url}";
                            $arrayurlsvalue[$tagvalue] = $site_url;
                            
                           //$taskdescription = str_replace("{site_url}",$site_url,$taskdescription);
                          //$data_field_array[] = array('name'=>$index,'content'=>$userdata->ID); 
                      }
                      
                      
                      
                   }else{
                       
                       
                      
                       
                       $keyvalueforadd = "{".$index."}";
                     
                      
                       if (!empty($all_meta_for_user[$keyvalue][0])) {
                           
                          
                          $getfieldType = getcustomefieldKeyValue($keyvalue,"fieldType");
                          
                          
                          
                        if($getfieldType == 'date') {
                            
                          $date_value =   date('d-m-Y', intval($all_meta_for_user[$keyvalue][0]/1000));
                          //$data_field_array[] = array('name'=>$index,'content'=>$date_value);
                          $taskdescription = str_replace($keyvalueforadd,$date_value,$taskdescription);
                          
                        }else if($getfieldType == 'url'){
                            
                            $linktext = "<a href='".$all_meta_for_user[$keyvalue][0]."' target='_blank' >".$all_meta_for_user[$keyvalue][0]."</a>";
                            $taskdescription = str_replace($keyvalueforadd,$linktext,$taskdescription);
                            $tagvalue = $keyvalueforadd;
                            $arrayurlsvalue[$tagvalue] = $all_meta_for_user[$keyvalue][0];
                            
                            
                            
                        }else{
                             
                                 
                               // $data_field_array[] = array('name'=>$index,'content'=> $all_meta_for_user[$keyvalue][0]);  
                                
                            
                                $taskdescription = str_replace($keyvalueforadd,$all_meta_for_user[$keyvalue][0],$taskdescription);
                        }
                       }else{
                           
                                $taskdescription = str_replace($keyvalueforadd,"",$taskdescription);
                          
                       }
                  }}
                       
                   
                    
                   $timestamp_task_data = date("M d, Y" ,$timestamp_task_data);
                    
                   if ($result_date <= 0) {

                       $timestamp_task_data_status_o = '<i class="flaticon-calendar-3 mr-2 font-size-lg" style="color:#000;"></i>'.$timestamp_task_data;
                       $duedatebgcolor = "";
                       
                   } else {
                     
                       $timestamp_task_data_status_o = '<p style="color:#000;font-weight:600!important;"><i class="flaticon-calendar-3 mr-2 font-size-lg" style="color:#000;"></i>'.$timestamp_task_data.'</p>';
                       $duedatebgcolor='style="background-color: #f1c8c8;"';
                   }
				   
				   ?>
				   
				   
			
				   
				   
				   <?php
				   
			
                    switch ($profile_field_settings['type']) {
                        
                        
                       
                       case 'date':
					    //echo $value.'-----';
                           //echo htmlspecialchars($value);
                           //exit;
                           $iconclass = "far fa-calendar-alt";
                           
                           $action_col .= '<input '.$fields_staus_type.' placeholder="Select date" class="myclass form-control kt_datepicker_1" type="text" readonly="readonly" id="' . $profile_field_name;
                           $action_col .= '" value="'.htmlspecialchars($value).'" >';  
                           break;
						   
                       case 'text':
                           $iconclass = "fas fa-pencil-alt";
                          
                           $action_col .= '<input '.$fields_staus_type.' class="myclass form-control" type="' . $profile_field_settings['type'] . '" id="' . $profile_field_name;
                           $action_col .= '" value="'.htmlspecialchars($value).'" >';  
                           break;
                       case 'datetime':
                           $iconclass = "fas fa-stopwatch";
                           
                           $action_col .= '<input '.$fields_staus_type.' class="myclass form-control" type="' . $profile_field_settings['type'] . '" id="' . $profile_field_name;
                           $action_col .= '" value="'.htmlspecialchars($value).'" >';  
                           break;
                       case 'email':
                      
                           $iconclass = "far fa-envelope";
                           $action_col .= '<input '.$fields_staus_type.' class="myclass form-control" type="' . $profile_field_settings['type'] . '" id="' . $profile_field_name;
                           $action_col .= '" value="'.htmlspecialchars($value).'" >';  
                           break;
                       case 'number':
                      
                           $iconclass = "fas fa-hashtag";
                           $action_col .= '<input '.$fields_staus_type.' class="quantitynumber myclass form-control" type="' . $profile_field_settings['type'] . '" id="' . $profile_field_name;
                           $action_col .= '" value="'.htmlspecialchars($value).'" >';  
                           break;
                       
                       case 'url':
                           
                           
                           $iconclass = "fas fa-link";
                           $action_col .= '<input '.$fields_staus_type.' class="myclass form-control" type="url" id="' . $profile_field_name;
                           $action_col .= '" value="'.htmlspecialchars($value).'" >';  
                           break;
                       case 'color':
                           
                           $iconclass = "fas fa-file-upload";
                           if (!empty($value)) {
                               $action_col .='<div class="' . $profile_field_name . '" style="display:none;">';
                           }

                           $action_col .= '	<div class="dropzone dropzone-multi" style="background: none;"><input '.$file_fields_staus_type.'  ' . $profile_field_settings['taskattrs'] . ' type="file" class ="upload myfileuploader dropzone-select btn btn-light-primary font-weight-bold btn-sm dz-clickable" id="my' . $profile_field_name . '" name="my' . $profile_field_name . '"></a><span class="form-text text-muted">File size must be less than 50MB.</span></div>';
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
                               $profile_field_name_new = "'".$profile_field_name."'";
                               $action_col .= '<div style="text-align: center;margin-top: 14px;" class="remove_' . $profile_field_name . '"><a  class="btn btn-primary btn-shadow-hover font-weight-bold mr-2" onclick="downloadfontendfile('.$profile_field_name_new.','.$user_IDD.')" style="cursor: pointer;margin-right: 24px;">Download File</a></div>';
                                   
                              
                               
                               }
                           break;
                   
                       //Modification by Qasim Riaz
                       case 'none':
                           $iconclass = "";
                           $action_col .= '';
                           break;
                       case 'comingsoon':
                           $iconclass = "fas fa-sign";
                           $action_col .= '<strong >Coming soon</strong>';
                           break;
                       //Modification by Qasim Riaz
                      
                      case 'textarea':
                          
                           $iconclass = "fas fa-pencil-alt";
                           $action_col .= '<textarea '.$fields_staus_type.' rows="5"  class="myclasstextarea form-control" id="' . $profile_field_name . '" name="' . $profile_field_name;
                           if ($mode == 'adduser')
                               $field_html .= '[]';
                           $action_col .= '" class="' . stripslashes(htmlspecialchars_decode($profile_field_settings['class'])) . $unique . '"';
                           
                           if ($profile_field_settings['required'] == 'yes')
                               $action_col .= ' required="required"';
                           if (!empty($profile_field_settings['taskattrs']))
                               $action_col .= $profile_field_settings['taskattrs'];
                           $action_col .= $form_tag . '>' . htmlspecialchars($value) . '</textarea>';
                           if (!empty($profile_field_settings['taskattrs']))
                               $action_col .='<span style="font-size:10px;padding-right: 7px;" id="chars_' . $profile_field_name . '">' . str_replace("maxlength=", "", $profile_field_settings['taskattrs']) . '</span><span style="font-size:10px;">characters remaining</span>';
                           break;
                     case 'select-2':
                         
                         
                           $iconclass = "fas fa-list";           
                           $multi = ((isset($profile_field_settings['allow_multi']) && $profile_field_settings['allow_multi'] == 'yes') || ($mode == 'adduser')) ? '[]' : '';
                           $multiple = (isset($profile_field_settings['allow_multi']) && $profile_field_settings['allow_multi'] == 'yes') ? ' multiple="multiple"' : '';
                           $size = (!isset($profile_field_settings['size']) || $profile_field_settings['size'] < 1) ? ' size="1"' : ' size="' . $profile_field_settings['size'] . '"';
                           
                           if($profile_field_settings['multiselectstatus'] == "checked"){
                                
                               $action_col .= '<select '.$fields_staus_type.' name="' . $profile_field_name . $multi . '" id="' . $profile_field_name . $multi . '" class="selectclass egpl_single_select2 form-control kt_select2_3"';
                           
                                
                           }else{
                               
                               $action_col .= '<select '.$fields_staus_type.' name="' . $profile_field_name . $multi . '" id="' . $profile_field_name . $multi . '" class="selectclass egpl_single_select2 form-control"';
 
                           }
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
                           $iconclass = "fas fa-list";           
                           $action_col .= '<select style="width: 100% !important;" class="egpl_single_select2 kt_select2_3" '.$fields_staus_type.' name="' . $profile_field_name . $multi . '" id="' . $profile_field_name . '" multiple="multiple"';
                          
                           
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
                            
                           $iconclass = "fas fa-th-list";
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
                               $action_col .= '<p id='.$randomnumber.' class="row"><input '.$fields_staus_type.' value="'.htmlspecialchars($multivaluetaskarray[$multivalueIndex]).'"   class="myclass form-control col-sm-10 specialcountclass_'.$profile_field_name.'  speicaltaskmulittask_'.$profile_field_name.'" type="text" name="' . $profile_field_nameArray.'" /> <button '.$fields_staus_type.' style="margin-left: 10px;" class="speicaltaskmulittask_'.$profile_field_name.' btn btn-icon btn-danger btn-circle btn-lg mr-4" onclick="removethisvaluetask('.$randomnumber.')" title="Delete"><i class="fas fa-trash"></i></button></p>';
    
                               
                           }
                           
                           
                           }else{
                               
                               $baseurl ="'bassfieldtype'";
                               $action_col .= '<p id='.$baseurl.' class="row"><input '.$fields_staus_type.'  class="myclass form-control col-sm-10 specialcountclass_'.$profile_field_name.' speicaltaskmulittask_'.$profile_field_name.' " type="text" name="' . $profile_field_nameArray;
                               $action_col .= '" value="'.htmlspecialchars($multivaluetaskarray[0]).'" ><button '.$fields_staus_type.'  style="margin-left: 10px;" class="speicaltaskmulittask_'.$profile_field_name.' btn btn-icon btn-danger btn-circle btn-lg mr-4" onclick="removethisvaluetask('.$baseurl.')" title="Delete"><i class="fas fa-trash"></i></button></p>';
                               
                               
                           }
                            $action_col .="</div>";
                           $action_col .= '<p class="row"><span class="col-sm-10"></span><button '.$fields_staus_type.'  style="margin-left: 10px;margin-top: 6px;" class="speicaltaskmulittask_'.$profile_field_name.' disableclassbutton_'.$profile_field_name.' btn btn-icon btn-danger btn-circle btn-lg mr-4" onclick="addnewmultivalueinput('.$profile_field_namespecial.','.$multivaluetasklimit.')" title="Add"><i class="fas fa-plus" ></i></button></p>'; 
                           
                          
                           
                          
                           break;
                       
                     case 'link':
                        // echo $profile_field_settings['lin_url'] ;exit;
                                
                         
                               $iconclass = "fas fa-external-link-square-alt";
                               $linkname = $profile_field_settings['lin_url'];
                               
                               if(!empty($arrayurlsvalue[$linkname])){
                                   
                                   $currenturlvalueUpdate = $arrayurlsvalue[$linkname];
                                   
                               }else{
                                   
                                   $currenturlvalueUpdate = $profile_field_settings['lin_url'];
                                   
                               }
                               
                          
                         
                         
                           $action_col .= '<a href="' . $currenturlvalueUpdate . '"target="_blank" ';
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
                                $special_check_buttons_status_submit = 'class="btnclick btn btn-success eg-buttons mr-2 disableremovebutton"';
                                $submit_button_text = 'Submitted';
                                $background_color = 'style="background-color:#d5f1d5;"';
                                $duedatebgcolor='style="background-color: #d5f1d5;"';
                            }else{
                                $special_check_buttons_status_remove = 'class="fusion-li-icon fa fa-times-circle fa-2x specialremoveicondisable" ';
                                $special_check_buttons_status_submit = 'class="btnclick btn btn-light-success eg-buttons font-weight-bold mr-2 fixedwithclass" ';
                                $submit_button_text = 'Submit';
                    }
                   if($lockdownstatus == 'checked' ){ 
                        
                            
                            $status_col .= '<table><tr style="background-color: transparent;" ><td><button    class="btnclick btn btn-light-success eg-buttons font-weight-bold mr-2 fixedwithclass disableremovebutton taskcustomesubmit disableremovebutton" >'.$submit_button_text.'</button></td>';
                            $status_col .= '<td><i  name="'.$profile_field_name.'" data-toggle="tooltip" title="Remove this task"  name="'.$profile_field_name.'" class="fusion-li-icon fa fa-times-circle fa-2x specialremoveicondisable"   ></i><td></tr></table>';
                            
                    
                    }else{
                            
                            
                            $status_col .= '<table><tr style="background-color: transparent;" ><td><button onclick="update_user_meta_custome(this,'.$type.')"  id="update_' . $profile_field_name . '_status" '.$special_check_buttons_status_submit.'  data-style="shrink" data-horizontal>'.$submit_button_text.'</button></td>';
                            $status_col .= '<td><i  name="'.$profile_field_name.'" data-toggle="tooltip" title="Remove this task" onclick="remove_task_value_readyfornew(this,'.$type.')" name="'.$profile_field_name.'" '.$special_check_buttons_status_remove.' id="update_' . $profile_field_name . '_remove"   ></i><td></tr></table>';
                    
                            
                    }
                   
				   
				 
				   
                   
                //   echo   $duedate_html = '<div class="card card-custom gutter-b"><div class="card-body border "><div class="d-flex"><div class="flex-grow-1"><div class="d-flex align-items-center justify-content-between flex-wrap mt-2"><div class="mr-3"><p class="d-flex align-items-center text-dark font-size-h5 font-weight-bold mr-3">' . $profile_field_settings["label"] . '</p></div><div class="my-lg-0 my-1"><div class="d-flex flex-wrap my-2"><p class="text-dark  font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2"><i class="flaticon-calendar-3 mr-2 font-size-lg"></i>' . $timestamp_task_data . '</p></div><a href="#" class="btn btn-sm btn-success font-weight-bolder text-uppercase mr-3">Submit</a><a href="#" class="btn btn-sm btn-danger font-weight-bolder text-uppercase">Remove</a></div></div><div class="d-flex align-items-center flex-wrap">' . $taskdescription . '</div><div class="d-flex align-items-center flex-wrap justify-content-between mt-3"><div class="flex-grow-1 font-weight-bold text-dark-50 py-2 py-lg-2 mr-5 border-0">'.$action_col.'</div><div class="d-flex mt-4 mt-sm-0"><span class="font-weight-bold mr-4">Progress</span><div class="progress progress-xs mt-2 mb-2 flex-shrink-0 w-150px w-xl-250px"><div class="progress-bar bg-success" role="progressbar" style="width: 63%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div></div><span class="font-weight-bolder text-dark ml-4">78%</span></div></div></div></div><div class="separator separator-solid my-7"></div><div class="d-flex align-items-center flex-wrap">' . $taskdescription . '</div></div></div>';
                    //    exit;
                    
                       // exit;
                   
                  
				echo $duedate_html= '<div class="card card-custom gutter-b" id="'.$profile_field_name.'_taskboday" '.$duedatebgcolor.' >
									<div class="card-body">
										<!--begin::Top-->
										<div class="d-flex">
											<!--begin::Pic-->
											<div class="flex-shrink-0 mr-7">
												<div class="symbol symbol-50 symbol-lg-120">
													<span class="mr-4">
													<i class="'.$iconclass.' icon-2x eg-icon-color"></i>
												</span>
												</div>
											</div>
											<!--end::Pic-->
											<!--begin: Info-->
											<div class="flex-grow-1">
												<!--begin::Title-->
												<div class="d-flex align-items-center justify-content-between flex-wrap mt-2">
													<!--begin::User-->
													<div class="mr-3">
														<!--begin::Name-->
														<p  class="d-flex align-items-center text-dark  font-size-h5 font-weight-bold mr-3">'.$profile_field_settings["label"].'
														</p>
														<!--end::Name-->
														<!--begin::Contacts-->
														<div class="d-flex flex-wrap my-2">
															
														</div>
														<!--end::Contacts-->
													</div>
													<!--begin::User-->
													<!--begin::Actions-->
													<div class="my-lg-0 my-1">
													
													<p  class="text-dark  font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2" style="font-weight:600!important;">
															' . $timestamp_task_data_status_o . '</p>
													
													</div>
													<!--end::Actions-->
												</div>
												<!--end::Title-->
												<!--begin::Content-->
												<div class="d-flex align-items-center flex-wrap justify-content-between">
													<!--begin::Description-->
													<div class="flex-grow-1 font-weight-bold text-dark-50 py-2 py-lg-2 mr-5 taskdescription" >' . $taskdescription . '</div>
													<!--end::Description-->
													<!--begin::Progress-->
													
													<!--end::Progress-->
												</div>
												
												
												
												<div class="">
												<div class="row">
												  <div class="col-sm-8"><div class="form-group">
													'.$action_col.'
													</div></div>
												  <div class="col-sm-4"><div class="float-right">
												  '.$status_col.'
                                                                                                  

												  </div>
												  </div>
												</div>
												</div>
												
												
												
														
														
												<!--end::Content-->
											</div>
											<!--end::Info-->
										</div>
										<!--end::Top-->
										
									</div>
								</div>';
                   
                   
               //   echo $duedate_html .= '<td class="content-vertical-middle">'.$action_col.'</td><td class="'.$profile_field_name.'_submissionstatus content-vertical-middle" '.$background_color.'>'.$status_col.'</td></tr>';
                
               }  
                
                
            }
           
           
           
           ?>
           
	     

	
	 <?php }
  //  get_footer(); 

?>


              
								
								
								
								
								
								
								</div>
								</div>
							</div>
						</div>
						
					</div>		
						


  
			
			
	
			
			
<?php



//wp_footer();
get_footer();
?>
  <script src="/wp-content/themes/twentytwentyone-child/js/pages/crud/forms/widgets/bootstrap-datepicker.js"></script>
  <script src="/wp-content/themes/twentytwentyone-child/js/pages/crud/forms/widgets/select2.js"></script>
  <script src="/wp-content/themes/twentytwentyone-child/plugins/custom/uppy/uppy.bundle.js"></script>
  <script src="/wp-content/themes/twentytwentyone-child/js/pages/crud/file-upload/uppy.js"></script>
  <script src="/wp-content/themes/twentytwentyone-child/js/pages/crud/file-upload/dropzonejs.js"></script>
  <script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/bootstrap-sweetalert/sweetalert.min.js"></script>

  
  
  
  
  
		 <script>
    
    
      // Initialization
      jQuery(document).ready(function() {
			
			
				 // multi select
        jQuery('.kt_select2_3').select2({
         placeholder: "Select a Value",
        });
			
		 jQuery('.kt_datepicker_1').datepicker({
               
              });
              
        jQuery('.btnclick').click(function(){
            
            
            var buttonID = jQuery(this).attr('id');
            console.log(buttonID);
            var btn = KTUtil.getById(buttonID);
            
            KTUtil.btnWait(btn, "spinner spinner-right spinner-white pr-15", "Please wait");

            setTimeout(function() {
                KTUtil.btnRelease(btn);
            }, 3000);
            
        });    
        

        
			
      });
	
	
	
    </script>
    
     <?php }else{
         
           $redirect = get_site_url();
        wp_redirect($redirect);
        exit;
         
         
     }}else{
    
      $redirect = get_site_url();
        wp_redirect($redirect);
        exit;
    
    
    
}?>