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
      
        global $wp_roles;

  //  $all_roles = $wp_roles->roles;
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
                    
              
                                    <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Email <strong>*</strong></label>
                                    <div class="col-sm-5">
                                         <input type="hidden" name="sponsorid" id="sponsorid" value="<?php echo $sponsor_id;?>" >
					 <input type="text"  class="form-control" id="Semail" placeholder="Email"  value="<?php echo $meta_for_user->user_email;?>" readonly>
							
                                        
                                    </div>
                                    <div class="col-sm-5">
                                        <a    class="btn btn-inline mycustomwidth btn-success" onclick="changeuseremailaddressalert()">Change Email</a>
                                          
                                    </div>
                                    
                                </div>
                   <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">First Name <strong>*</strong></label>
                                    <div class="col-sm-10">
                                          
								<input type="text"  class="form-control mymetakey" id="Sfname" name="first_name" placeholder="First Name" value="<?php echo $all_meta_for_user[$site_prefix.'first_name'][0];?>" required>
								
                                        
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Last Name <strong>*</strong></label>
                                    <div class="col-sm-10">
                                           
								<input type="text"  class="form-control mymetakey" id="Slname" name="last_name" placeholder="Last Name" value="<?php echo $all_meta_for_user[$site_prefix.'last_name'][0];?>"  required>
								
                                        
                                    </div>
                                </div>
                    <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Change Password <p>(if u want to change or reset password)</p></label>
                                    <div class="col-sm-10">
                                          
								<input type="password"  class="form-control mymetakey" id="password" name="password" placeholder="Password" value="" >
							
                                        
                                    </div>
                                </div>
                                  
                              <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">User Level <strong>*</strong></label>
                                    <div class="col-sm-10">
                                           
								 <select  class="form-control" id="Srole" required>
								
                                                                     <option></option>
                                                                         <?php
                                             foreach ($all_roles as $key => $name) {
                                                if ($rolename == $key) {
                                                echo '<option value="' . $key . '" selected>' . $name['name'] . '</option>';
        } else {

            if ($key != 'administrator' && $key != 'contentmanager' && $key != 'subscriber') {
                echo '<option value="' . $key . '">' . $name['name'] . '</option>';
            }
        }
    }
                                                                         ?>
								 </select>
					   
                                        
                                    </div>
                 </div>   
                 <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Company Name <strong>*</strong></label>
                                    <div class="col-sm-10">
                                        
								<input type="text"  class="form-control mymetakey" id="company_name" name="company_name" placeholder="Company Name" value="<?php echo $all_meta_for_user[$site_prefix.'company_name'][0];?>"  required>
								
                                        
                                    </div>
                                </div>             
                   <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Company Logo </label>
                                    <?php if(empty($all_meta_for_user[$site_prefix.'user_profile_url'][0])){?>  
                                    <div class="col-sm-10">
                                                     
                                        
					<input  type="file" class="form-control" name="profilepic" id="profilepic" >				
                                      
                                       
		                  </div>
                                   <?php }else{?>
                                    <div id="showprofilepic">
                                    <div class="col-sm-5">
                                       <img width="200" id="userprofilepic"  name="userprofilepic" src="<?php echo $all_meta_for_user[$site_prefix.'user_profile_url'][0];?>" >
                                    </div>
                                    <div class="col-sm-4">
                                        <a width="200" class="btn btn-inline mycustomwidth btn-success" onclick="showprofilefieldupload()" >Edit Logo</a>
                                    </div>
                                     </div>
                                      <?php } ?>
                                    
                                    <div class="col-sm-10" style="display:none;" id="updateprofilepic">
                                                     
                                        
					<input  type="file" class="form-control" name="profilepic" id="profilepic" >				
                                      
                                       
		                  </div>
		</div>                   
                              
                                   
                  </div>                  
                      <div role="tabpanel" class="tab-pane fade" id="tabs-1-tab-2">
                          
                           
                             <?php   foreach ($additional_fields as $key=>$value){ if($additional_fields[$key]['type'] != 'checkbox' && $additional_fields[$key]['type'] != 'html'){ ?>
                               
                                <div class="form-group row" >
                                    <label class="col-sm-4 form-control-label"><?php echo $additional_fields[$key]['formlabel'];?></label>
                                    <div class="col-sm-8">
                                        
                                       <?php if($additional_fields[$key]['type'] == 'text'){ ?> 
                                        
					<input type="text"  class="form-control mymetakey" id="<?php echo $additional_fields[$key]['key'];?>" name="<?php echo $additional_fields[$key]['key'];?>" value="<?php echo $all_meta_for_user[$site_prefix.$additional_fields[$key]['key']][0];?>" placeholder="<?php echo $additional_fields[$key]['formlabel'];?>" >
                                       
                                        <?php }else if($additional_fields[$key]['type'] == 'textarea'){?>
                                        
                                             <textarea   class="form-control mymetakey" id="<?php echo $additional_fields[$key]['key'];?>" name="<?php echo $additional_fields[$key]['key'];?>" placeholder="<?php echo $additional_fields[$key]['formlabel'];?>"><?php echo $all_meta_for_user[$site_prefix.$additional_fields[$key]['key']][0];?></textarea>
                                        
                                        
                                       <?php }else if($additional_fields[$key]['type'] == 'dropdown'){?>
                                             
                                             
                                             <?php if($additional_fields[$key]['multiselect'] == true) {
                                                 
                                                    $multiselectvalues = explode(";",$all_meta_for_user[$site_prefix.$additional_fields[$key]['key']][0]);
                                                   
                                                 
                                                 ?>
                                              <select class="select2 mycustomedropdown"   id="<?php echo $additional_fields[$key]['key'];?>" data-allow-clear="true" data-toggle="tooltip" multiple="multiple">
                                                    <?php foreach ($additional_fields[$key]['options'] as $key=>$value){
                                                            $skipthis = false;
                                                        foreach ($multiselectvalues as $nkey=>$nvalue){
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            if(!empty($nvalue) && $nvalue == $value['value'] ){
                                                                
                                                                $skipthis = true;
                                                                
                                                                
                                                                ?>
                                                  
                                                  
                                                                 <option value='<?php echo $value['value'];?>' selected="selected"><?php echo $value['value'];?></option>
                                                                
                                                                
                                                                
                                                            <?php } ?>
                                                            
                                                        
                                                        
                                                                
                                                         
                                                    
                                                            <?php }?>
                                                            <?php if($skipthis == false){?>
                                                                
                                                                <option value='<?php echo $value['value'];?>'><?php echo $value['value'];?></option>
                                                                
                                                                
                                                            <?php }} ?>
                                                         
                                                         
                                                         
                                                         
                                                       
                                                   
                                              </select>
                                             <?php }else {
                                                 
                                                  $multiselectvalues = explode(";",$all_meta_for_user[$site_prefix.$additional_fields[$key]['key']][0]);
                                                 
                                                 
                                                 ?>
                                                
                                                    <select class="select2 mycustomedropdown"   id="<?php echo $additional_fields[$key]['key'];?>" data-allow-clear="true">

                                                       <?php foreach ($additional_fields[$key]['options'] as $key=>$value){ 
                                                           
                                                           
                                                           if($multiselectvalues[0] == $value['value']){
                                                           
                                                           
                                                           ?>
                                                                
                                                           
                                                            
                                                        <option value='<?php echo $value['value'];?>' selected="selected"><?php echo $value['value'];?></option>
                                                                 
                                                              
                                                           <?php }else{?>
                                                        
                                                        
                                                   
                                                            <option value='<?php echo $value['value'];?>'><?php echo $value['value'];?></option>
                                                       
                                                        
                                                         
                                                       <?php }} ?>

                                                   </select>
                                             
                                             <?php } ?>
                                             
                                       <?php } }?> 
                                       <?php if($additional_fields[$key]['type'] == 'checkbox'){ 
                                           
                                           
                                           $checkstatusValue = $all_meta_for_user[$site_prefix.$additional_fields[$key]['key']][0];
                                           
                                           ?>
                                             <div class="form-group row" >
                                                 
                                                 <div class="col-sm-12">
                                                     <?php if($checkstatusValue == true){ ?>
                                                         
                                                     <input  class="mycustomcheckbox" type="checkbox" id="<?php echo $additional_fields[$key]['key'];?>" checked="true"><?php echo '   '.$additional_fields[$key]['formlabel'];?><br/>
                                                         
                                                     <?php }else{ ?>
                                                         
                                                         
                                                         <input  class="mycustomcheckbox" type="checkbox" id="<?php echo $additional_fields[$key]['key'];?>"><?php echo '   '.$additional_fields[$key]['formlabel'];?><br/>
                                             
                                                         
                                                         
                                                     
                                                    <?php }?>  
                                                     
                                               
                                            
                                       <?}?>
                                       
                                       <?php if($additional_fields[$key]['type'] == 'html'){ ?>
                                             <div class="form-group row" >
                                                 
                                                 <div class="col-sm-12">
                                                    
                                                    <?php echo $additional_fields[$key]['name'];?>
                                                     
                                                     
                                               
                                            
                                       <?}?>
                                        
                                    </div>
                                </div>
                           
                       <?php } ?>  
                             
                          
                          
                          
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