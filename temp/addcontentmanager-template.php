<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
       
    
		
      
      
      $test = 'custome_task_manager_data';
      $result = get_option($test);
      $settitng_key='ContenteManager_Settings';
      $sponsor_info = get_option($settitng_key);
    
      $sponsor_name = $sponsor_info['ContentManager']['sponsor-name'];
      $welcomeemail_template_info_key='AR_Contentmanager_Email_Template_welcome';
      $welcomeemail_template_info = get_option($welcomeemail_template_info_key);
      
     
      
      global $wp_roles;
     
      $all_roles = $wp_roles->get_names();
      include 'cm_header.php';
       include 'cm_left_menu_bar.php';
      
                ?>
              <select id="hiddenlistemaillist" style="display: none;">
                
                <?php  foreach ($welcomeemail_template_info as $key=>$value) { 
                                            
                                            $template_name = ucwords(str_replace('_', ' ', $key));
                                            if($key == "welcome_email_template"){
                                                 echo  '<option value="' . $key . '" selected="selected">Default Welcome Email</option>';
                                            }else{
                                                 echo  '<option value="' . $key . '" >'.$template_name.'</option>';
                                            }
                                          
                                         }
                ?>
                                     
                
        </select>
    <select  id="hiddenlistusersrole" style="display: none;">
								
        <option value="contentmanager" selected="selected">Content Manager</option>
                                                                       
								 </select>
   <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Add New Admin</h3>
                           
                        </div>
                    </div>
                </div>
            </header>

            <div class="box-typical box-typical-padding">
                <p>
            Create additional Admin users here. Admin users will have all of the same administrative capabilities as you.  </p>

                <h5 class="m-t-lg with-border">Personal Information</h5>

              <form method="post" action="javascript:void(0);" onSubmit="add_new_admin_user()">
                    
<!--                   <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Username <strong>*</strong></label>
                                    <div class="col-sm-10">
                                            <div class="form-control-wrapper form-control-icon-left">    
					     <input type="text"  class="form-control" id="Susername" placeholder="Username" required>
                                             <i class="font-icon fa fa-user"></i>
                                            </div>		
                                        
                                    </div>
                                </div>-->
<!--                  <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Email <strong>*</strong></label>
                                    <div class="col-sm-10">
                                            <div class="form-control-wrapper form-control-icon-left">    
								<input type="text"  class="form-control" id="Semail" placeholder="Email" required>
						<i class="font-icon fa fa-envelope-o"></i>	
                                        </div>
                                    </div>
                                </div>-->
                    
                     <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Email <strong>*</strong></label>
                                    <div class="col-sm-5">
                                        <div class="form-control-wrapper form-control-icon-left">     
					<input type="text"  class="form-control" id="Semail" placeholder="Email" required>
                                          <i class="font-icon fa fa-envelope-o"></i>                     
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        
                                        <a class="btn btn-inline" onclick="checkemailaddressalreadyexist()" >Find email address</a>
                                        
                                    </div>
                                </div>
                   <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">First Name <strong>*</strong></label>
                                    <div class="col-sm-10">
                                          <div class="form-control-wrapper form-control-icon-left">    
								<input type="text"  class="form-control mymetakey" id="Sfname" name="first_name" placeholder="First Name" required>
						<i class="font-icon  fa fa-pencil"></i>
                                                               	
                                        </div>
                                    </div>
                                </div>
                   <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Last Name <strong>*</strong></label>
                                    <div class="col-sm-10">
                                         <div class="form-control-wrapper form-control-icon-left">    
								<input type="text"  class="form-control mymetakey" id="Slname" name="last_name" placeholder="Last Name" required>
					<i class="font-icon  fa fa-pencil"></i>
                                         </div>			
                                        
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Company Name <strong>*</strong></label>
                                    <div class="col-sm-10">
                                         <div class="form-control-wrapper form-control-icon-left">    
								<input type="text"  class="form-control mymetakey" id="company_name" name="company_name" placeholder="Company Name" required>
					<i class="font-icon  fa fa-pencil"></i>
                                         </div>			
                                        
                                    </div>
                                </div>
                  <div class="form-group row" style="display:none;">
                                    <label class="col-sm-2 form-control-label">User Level <strong>*</strong></label>
                                    <div class="col-sm-10">
                                           
								 <select  class="form-control" id="Srole" required>
								
                                                                     <option></option>
                                                                         <?php
                                                                         foreach ($all_roles as $key => $name) {


                                                                             if ($key == 'contentmanager') {
                                                                                 echo '<option value="' . $key . '" selected>' . $name . '</option>';
                                                                             }
                                                                         }
                                                                         ?>
								 </select>
					    
                                        
                                    </div>
                 </div>
                 <div class="row" style="margin-bottom: 5px;">
                        <div class="col-sm-2"></div>
                            <div class="col-sm-6">
                                <div class="checkbox" id="checknewuserdiv">
                                    <input  type="checkbox" id="checknewuser">Send welcome email.<br/>
                                    
                                   
                                </div>
                               

                            </div>
                    </div>
                        <div class="row" id="showlistofselectwelcomeemail" style="display:none;margin-bottom: 15px;">
                        <label class="col-sm-2 form-control-label">Select Welcome Email Template</label>
                            <div class="col-sm-10">
                                
                                    <select style="width:100%;height:38px;"class="form-control" id="selectedwelcomeemailtemp">
                                    <?php  foreach ($welcomeemail_template_info as $key=>$value) { 
                                            
                                            $template_name = ucwords(str_replace('_', ' ', $key));
                                            if($key == "welcome_email_template"){
                                                 echo  '<option value="' . $key . '" selected="selected">Default Welcome Email</option>';
                                            }else{
                                                 echo  '<option value="' . $key . '" >'.$template_name.'</option>';
                                            }
                                          
                                         }
                                        ?>
                                     
                                   </select>
                                
                               

                            </div>
                    </div>
                    <h5 class="m-t-lg with-border"></h5>
                  <div class="form-group row">
                                    <label class="col-sm-2 form-control-label"></label>
                                    <div class="col-sm-6">
                                             <button type="submit"  name="addsponsor"  class="btn btn-lg mycustomwidth btn-success" value="Register">Create</button>
                                            
                                        
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