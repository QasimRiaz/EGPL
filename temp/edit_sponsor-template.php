<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
       
    
		
      if(!empty($_GET['sponsorid'])){
          $sponsor_id=$_GET['sponsorid'];
          $meta_for_user = get_userdata( $sponsor_id );
          $all_meta_for_user = get_user_meta($sponsor_id );
          //echo '<pre>';
          //print_r( $all_meta_for_user );exit;
          
      }
       $settitng_key='ContenteManager_Settings';
       $sponsor_info = get_option($settitng_key);
       $additional_fields_settings_key = 'EGPL_Settings_Additionalfield';
       $additional_fields = get_option($additional_fields_settings_key);
       $sponsor_name = $sponsor_info['ContentManager']['sponsor-name'];
       $welcomeemail_template_info_key='AR_Contentmanager_Email_Template_welcome';
       $welcomeemail_template_info = get_option($welcomeemail_template_info_key);
       
       require_once plugin_dir_path( __DIR__ ) . 'includes/egpl-custome-functions.php';
       $GetAllcustomefields = new EGPLCustomeFunctions();
       
       $additional_fields = $GetAllcustomefields->getAllcustomefields();
      
        function sortByOrder($a, $b) {
            return $a['fieldIndex'] - $b['fieldIndex'];
        }

        usort($additional_fields, 'sortByOrder');
       
       
        global $wp_roles;

  //  $all_roles = $wp_roles->roles;
        
        
       // echo '<pre>';
      //  print_r($all_roles);exit;
        
    if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
                $site_prefix = 'wp_'.$blog_id.'_';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }
     $all_roles = get_option($get_all_roles_array);
       $blog_id =get_current_blog_id();
       if (is_multisite()) {
           $getroledata = unserialize($all_meta_for_user['wp_'.$blog_id.'_capabilities'][0]);
           reset($getroledata);
           $rolename = key($getroledata);
       }else{
         $rolename = $meta_for_user->roles[0];  
       }
      
     //  echo $rolename;exit;
       include 'cm_header.php';
       include 'cm_left_menu_bar.php';
                ?>
                
       <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Edit User</h3>
                           
                        </div>
                    </div>
                </div>
            </header>
            <select id="hiddenlistemaillist" style="display: none;">
                
                <?php  foreach ($welcomeemail_template_info as $key=>$value) { 
                                            
                                            $template_name = ucwords(str_replace('_', ' ', $key));
                                            if($key == "welcome_email_template"){
                                                 echo  '<option value="' . $key . '" selected="selected">Defult Welcome Email</option>';
                                            }else{
                                                 echo  '<option value="' . $key . '" >'.$template_name.'</option>';
                                            }
                                          
                                         }
                ?>
                                     
                
            </select>
            <div class="box-typical box-typical-padding">
                <p>
                You can edit the selected user here and change their password. </p>

                <br>
                <br>

              <form method="post" action="javascript:void(0);" onSubmit="update_sponsor()">
                    
                  <section class="tabs-section">
				<div class="tabs-section-nav tabs-section-nav-icons">
					<div class="tbl">
						<ul class="nav" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" href="#tabs-1-tab-1" role="tab" data-toggle="tab">
									<span class="nav-link-in">
										<i class="fa fa-info-circle" ></i>
										Basic Information
									</span>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#tabs-1-tab-2" role="tab" data-toggle="tab">
									<span class="nav-link-in">
										<span class="fa fa-list-alt"></span>
										Additional Information
									</span>
								</a>
							</li>
							
						</ul>
					</div>
				</div><!--.tabs-section-nav-->

				<div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="tabs-1-tab-1">  
                                        <input type="hidden" name="sponsorid" id="sponsorid" value="<?php echo $sponsor_id;?>" >
                                        
                                        
                                     <?php   foreach ($additional_fields as $key=>$value){ 
                           
                                if($additional_fields[$key]['fieldsystemtask'] == "checked" && $additional_fields[$key]['SystemfieldInternal'] != "checked"){
                                $requiredStatus = $additional_fields[$key]['fieldrequriedstatus'];
                                $requriedStatus = "";
                                $requiredStatueUpdate = "";
                                if($requiredStatus == true){
                                    
                                    
                                    $requiredStatueUpdate = "required='ture'";
                                    $requriedStatus = "*";
                                    
                                }
                                ?>
                                
                                <?php if($additional_fields[$key]['fieldType'] != 'checkbox' && $additional_fields[$key]['fieldType'] != 'display'){ ?>
                               
                                    <div class="form-group row" >
                                    <div class="col-sm-2">
                                    <label><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatus;?>
                                    <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>
                                    
                                      <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                    <?php }?>
                                    </label>
                                    <?php if(!empty($additional_fields[$key]['fielddescription'])){?>
                                    
                                    <?php echo $additional_fields[$key]['fielddescription'];?>
                                    <?php }?>
                                    </div>
                                    
                                    <?php if($additional_fields[$key]['fieldType'] == 'text' || $additional_fields[$key]['fieldType'] == 'email' || $additional_fields[$key]['fieldType'] == 'url' ||$additional_fields[$key]['fieldType'] == 'date' ||$additional_fields[$key]['fieldType'] == 'number'){ ?> 
                                        
                                        <?php if($additional_fields[$key]['fieldType'] == 'email'){?>
                                    
                                            <div class="col-sm-5">
                                                <input type="<?php echo $additional_fields[$key]['fieldType'];?>"  class="form-control mymetakey" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" value="<?php echo $meta_for_user->user_email;?>" readonly placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>>
                                            </div>
                                            <div class="col-sm-5">
                                        
                                                 <a    class="btn btn-inline mycustomwidth btn-success" onclick="changeuseremailaddressalert()">Change Email</a>
                                        
                                             </div>
                                    
                                        <?php }else{?>
                                        <div class="col-sm-10">
					<input type="<?php echo $additional_fields[$key]['fieldType'];?>"  class="form-control mymetakey" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" value="<?php echo $all_meta_for_user[$site_prefix.$additional_fields[$key]['fielduniquekey']][0];?>" <?php echo $requiredStatueUpdate;?>>
                                        </div>
                                        <?php }}else if($additional_fields[$key]['fieldType'] == 'textarea'){?>
                                             <div class="col-sm-10">
                                             <textarea   <?php echo $additional_fields[$key]['attribute'];?> class="form-control mymetakey" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>><?php echo $all_meta_for_user[$site_prefix.$additional_fields[$key]['fielduniquekey']][0];?></textarea>
                                             </div>
                                        
                                       <?php }else if($additional_fields[$key]['fieldType'] == 'dropdown'){?>
                                             
                                             <div class="col-sm-10">
                                             <?php if($additional_fields[$key]['multiselect'] == 'chekced') {?>
                                              <select class="select2 mycustomedropdown "  title="<?php echo $additional_fields[$key]['fielduniquekey'];?>" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" data-allow-clear="true" data-toggle="tooltip" multiple="multiple" <?php echo $requiredStatueUpdate;?>>
                                                    <?php  foreach ($all_roles as $key => $name) { 
                                                        
                                                        
                                                        if ($key != 'administrator' && $key != 'subscriber') {
                                                        ?>
                                                  
                                                         <option value='<?php echo$key; ?>'><?php echo $name['name'];?></option>
                                                    
                                                    <? }} ?>
                                                   
                                              </select>
                                             <?php }else {?>
                                                    
                                                    <select class="select2 mycustomedropdown "  title="<?php echo $additional_fields[$key]['fielduniquekey'];?>" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" data-allow-clear="true" <?php echo $requiredStatueUpdate;?>>

                                                       <?php  foreach ($all_roles as $key => $name) { 
                                                        
                                                        
                                                        if ($key != 'administrator'  && $key != 'subscriber') {
                                                            
                                                           // echo $rolename;exit;
                                                            if($rolename == $key){
                                                        ?>
                                                        <option value='<?php echo $key;?>' selected="true"><?php echo $name['name'];?></option>
                                                        <? }else{if ($key != 'administrator' && $key != 'contentmanager' && $key != 'subscriber') {?>
                                                        <option value='<?php echo $key;?>' ><?php echo $name['name'];?></option>
                                                        <?php }}}} ?>

                                                   </select>
                                             
                                             <?php } ?>
                                             </div>
                                       <?php }?> </div> <?php }?> 
                                       <?php if($additional_fields[$key]['fieldType'] == 'checkbox'){ ?>
                                             <div class="form-group row" >
                                                 
                                                 <div class="col-sm-12">
                                                     
                                                     <input  <?php echo $all_meta_for_user[$site_prefix.$additional_fields[$key]['fielduniquekey']][0];?>  class="mycustomcheckbox"<?php echo $requiredStatueUpdate;?> type="checkbox" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>"><?php echo '   '.$additional_fields[$key]['fieldName'];?><br/>
                                             
                                                </div>
                                                </div>      
                                               
                                            
                                       <?}?>
                                       <?php if($additional_fields[$key]['fieldType'] == 'display'){ ?>
                                             <div class="form-group row" >
                                                 
                                                 <div class="col-sm-12">
                                                     
                                                     <?php echo $additional_fields[$key]['fielddescription'];?>
                                                </div>
                                               </div>  
                                       <?}?>
                                    
                                  
                           
                                <?php }} ?>
              
                                   
                                        
                             
                                <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Change Password <p>(if u want to change or reset password)</p></label>
                                    <div class="col-sm-10">
                                          
					<input type="password"  class="form-control mymetakey" id="password" name="password" placeholder="Password" value="" >
					
                                    </div>
                                </div>
                                  
                              
                       
                              
                                   
                  </div>                  
                      <div role="tabpanel" class="tab-pane fade" id="tabs-1-tab-2">
                          
                           
                             <?php   foreach ($additional_fields as $key=>$value){ 
                           
                                if($additional_fields[$key]['fieldsystemtask'] != "checked" && $additional_fields[$key]['SystemfieldInternal'] != "checked"){
                                
                                
                                
                                if($additional_fields[$key]['fieldType'] != 'checkbox' && $additional_fields[$key]['fieldType'] != 'display'){ 
                                    
                                    $requiredStatus = $additional_fields[$key]['fieldrequriedstatus'];
                                    $requriedStatussysomb = "";
                                    $requiredStatueUpdate = "";
                                    $showstatus = "";
                                    if($requiredStatus == true){
                                    
                                    
                                    $requiredStatueUpdate = "required='ture'";
                                    $requriedStatussysomb = "*";
                                    $showstatus = true;
                                    
                                    }
                                    
                                    
                                    
                                    ?>
                               
                                   
                                      <?php if($additional_fields[$key]['fieldType'] == 'text' || $additional_fields[$key]['fieldType'] == 'email' || $additional_fields[$key]['fieldType'] == 'url' ||$additional_fields[$key]['fieldType'] == 'date' ||$additional_fields[$key]['fieldType'] == 'number'){ ?> 
                                             <div class="form-group row" >
                                    <div class="col-sm-4">
                                    <label><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatussysomb;?>
                                    <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>
                                    
                                      <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                    <?php }?>
                                    </label>
                                    <?php if(!empty($additional_fields[$key]['fielddescription'])){?>
                                    
                                    <?php echo $additional_fields[$key]['fielddescription'];?>
                                    <?php }?>
                                    </div>
                                    <div class="col-sm-8">
                                         <input type="hidden" name="requiredstatus" id="<?php echo $additional_fields[$key]['fieldID'];?>_requiredstatus" value="<?php echo $showstatus;?>" >
                                   
                                            <input type="<?php echo $additional_fields[$key]['fieldType'];?>"  class="form-control mymetakey" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" value="<?php echo $all_meta_for_user[$site_prefix.$additional_fields[$key]['fielduniquekey']][0];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>>
                                        </div></div>
                                        <?php }else if($additional_fields[$key]['fieldType'] == 'textarea'){?>
                                              <div class="form-group row" >
                                    <div class="col-sm-4">
                                    <label><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatussysomb;?>
                                    <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>
                                    
                                      <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                    <?php }?>
                                    </label>
                                    <?php if(!empty($additional_fields[$key]['fielddescription'])){?>
                                    
                                    <?php echo $additional_fields[$key]['fielddescription'];?>
                                    <?php }?>
                                    </div>
                                    <div class="col-sm-8">
                                         <input type="hidden" name="requiredstatus" id="<?php echo $additional_fields[$key]['fieldID'];?>_requiredstatus" value="<?php echo $showstatus;?>" >
                                   
                                             <textarea   <?php echo $additional_fields[$key]['attribute'];?>  class="form-control mymetakey" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>><?php echo $all_meta_for_user[$site_prefix.$additional_fields[$key]['fielduniquekey']][0];?></textarea>
                                        
                                         </div></div>
                                       <?php }else if($additional_fields[$key]['fieldType'] == 'file'){?>
                                           
                                            <div class="form-group row" >
                                    <div class="col-sm-4">
                                    <label><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatussysomb;?>
                                    <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>
                                    
                                      <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                    <?php }?>
                                    </label>
                                    <?php if(!empty($additional_fields[$key]['fielddescription'])){?>
                                    
                                    <?php echo $additional_fields[$key]['fielddescription'];?>
                                    <?php }?>
                                    </div>
                                    <div class="col-sm-8">
                                         <input type="hidden" name="requiredstatus" id="<?php echo $additional_fields[$key]['fieldID'];?>_requiredstatus" value="<?php echo $showstatus;?>" >
                                   
                                         <?php   if(!empty($all_meta_for_user[$site_prefix.$additional_fields[$key]['fielduniquekey']][0])){
                                               
                                               echo '<div id="'.$additional_fields[$key]['fieldID'].'_fileuploadholder"></div><div id="'.$additional_fields[$key]['fieldID'].'_fileuploadpic"><div class="col-sm-5"><img width="200" id="'.$additional_fields[$key]['fieldID'].'_fileuploadpicviewer"  name="userprofilepic" src="'.$all_meta_for_user[$site_prefix.$additional_fields[$key]['fielduniquekey']][0].'" ></div><div class="col-sm-4"><a width="200" id="'.$additional_fields[$key]['fieldID'].'_fileuploadbutton" class="btn btn-inline mycustomwidth btn-success" name="'.$additional_fields[$key]['fielduniquekey'].'" onclick="showprofilefieldupload(this)" >Edit</a></div></div>';
                                               
                                           }else{
                                               
                                               echo '<input  type="'.$additional_fields[$key]['fieldType'].'"  class="form-control '.$additional_fields[$key]['fieldID'].'_fileupload"" id="'.$additional_fields[$key]['fielduniquekey'].'" name="customefiels[]" placeholder="'.$additional_fields[$key]['fieldplaceholder'].'"'.$requiredStatueUpdate.'>';
                                               
                                               
                                           }
                                           
                                           ?>
                                            </div></div>
                                           
                                       <?php }else if($additional_fields[$key]['fieldType'] == 'dropdown'){?>
                                             
                                              <div class="form-group row" >
                                    <div class="col-sm-4">
                                    <label><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatussysomb;?>
                                    <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>
                                    
                                      <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                    <?php }?>
                                    </label>
                                    <?php if(!empty($additional_fields[$key]['fielddescription'])){?>
                                    
                                    <?php echo $additional_fields[$key]['fielddescription'];?>
                                    <?php }?>
                                    </div>
                                    <div class="col-sm-8">
                                         <input type="hidden" name="requiredstatus" id="<?php echo $additional_fields[$key]['fieldID'];?>_requiredstatus" value="<?php echo $showstatus;?>" >
                                   
                                             <?php if($additional_fields[$key]['multiselect'] == "checked") {?>
                                              <select class="select2 mycustomedropdown mymetakey"  title="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" data-allow-clear="true" data-toggle="tooltip" multiple="multiple" <?php echo $requiredStatueUpdate;?>>
                                                    <?php foreach ($additional_fields[$key]['options'] as $key=>$value){ ?>
                                                  
                                                         <option value='<?php echo $value->label;?>'><?php echo $value->label;?></option>
                                                    
                                                    <? } ?>
                                                   
                                              </select>
                                             <?php }else {
                                                    ?>
                                                    <select class="select2 mycustomedropdown mymetakey"  title="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" data-allow-clear="true" <?php echo $requiredStatueUpdate;?>>

                                                       <?php foreach ($additional_fields[$key]['options'] as $keysoptions=>$additionalvalue){ 
                                                           
                                                        if($additionalvalue->label == $all_meta_for_user[$site_prefix.$additional_fields[$key]['fielduniquekey']][0]){?>
                                                               
                                                        <option value='<?php echo $additionalvalue->label;?>' selected="true"><?php echo $additionalvalue->label;?></option>    
                                                      <? }else{?>
                                                        
                                                           <option value='<?php echo $additionalvalue->label;?>'><?php echo $additionalvalue->label;?></option>  
                                                     <?php }} ?>

                                                   </select>
                                             
                                             <?php } ?>
                                         </div></div>
                                       <?php } }?> 
                                       <?php if($additional_fields[$key]['fieldType'] == 'checkbox'){ ?>
                                             <div class="form-group row" >
                                                 
                                                 <div class="col-sm-12">
                                                     
                                                     <input  class="mycustomcheckbox" <?php echo $all_meta_for_user[$site_prefix.$additional_fields[$key]['fielduniquekey']][0];?> <?php echo $requiredStatueUpdate;?> type="checkbox" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>"><?php echo '   '.$additional_fields[$key]['fieldName'];?><br/>
                                             
                                                     
                                                </div></div>
                                            
                                       <?}?>
                                       <?php if($additional_fields[$key]['fieldType'] == 'display'){ ?>
                                             <div class="form-group row" >
                                                 
                                                 <div class="col-sm-12 ">
                                                     
                                                     <?php echo $additional_fields[$key]['fielddescription'];?>
                                                </div></div>
                                       <?}?>
                                    
                                    
                           
                       <?php }} ?>
                             
                          
                          
                          
	                    </div><!--.box-typical-body-->
	                </section><!--.box-typical-dashboard-->           
                               
                      <h5 class="m-t-lg with-border"></h5>
                                  <div class="form-group row">
                                    <label class="col-sm-2 form-control-label"></label>
                                    <div class="col-sm-6">
                                             <button type="submit"  id="addnewsponsor_q" name="updatesponsor"  class="btn btn-lg mycustomwidth btn-success" value="Update">Update</button>
                                          
                                        
                                    </div>
                                </div>
                               
                
                </form>
            </div>
        </div>
    </div>
     
                        
				<?php   include 'cm_footer.php';
		
      
      
      
       
   }else{
       $redirect = get_site_url();
    wp_redirect( $redirect );exit;
   
   }
   ?>