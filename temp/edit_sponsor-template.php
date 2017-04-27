<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
       
    
		
      if(!empty($_GET['sponsorid'])){
          $sponsor_id=$_GET['sponsorid'];
          $meta_for_user = get_userdata( $sponsor_id );
          $all_meta_for_user = get_user_meta($sponsor_id );
         // echo '<pre>';
        //  print_r( $meta_for_user );
          
      }
       $settitng_key='ContenteManager_Settings';
       $sponsor_info = get_option($settitng_key);
       $additional_fields_settings_key = 'EGPL_Settings_Additionalfield';
       $additional_fields = get_option($additional_fields_settings_key);
       $sponsor_name = $sponsor_info['ContentManager']['sponsor-name'];
     
      
      global $wp_roles;
     
      $all_roles = $wp_roles->get_names();
    // echo '<pre>';
   //  print_r($meta_for_user->roles[0] );
    //  exit;
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
                                        <a    class="btn btn-inline mycustomwidth btn-success" onclick="changeuseremailaddress()">Change Email</a>
                                          
                                    </div>
                                    
                                </div>
                   <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">First Name <strong>*</strong></label>
                                    <div class="col-sm-10">
                                          
								<input type="text"  class="form-control mymetakey" id="Sfname" name="first_name" placeholder="First Name" value="<?php echo $all_meta_for_user['first_name'][0];?>" required>
								
                                        
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Last Name <strong>*</strong></label>
                                    <div class="col-sm-10">
                                           
								<input type="text"  class="form-control mymetakey" id="Slname" name="last_name" placeholder="Last Name" value="<?php echo $all_meta_for_user['last_name'][0];?>"  required>
								
                                        
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
                                                if ($meta_for_user->roles[0] == $key) {
                                                echo '<option value="' . $key . '" selected>' . $name . '</option>';
        } else {

            if ($key != 'administrator' && $key != 'contentmanager' && $key != 'subscriber') {
                echo '<option value="' . $key . '">' . $name . '</option>';
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
                                        
								<input type="text"  class="form-control mymetakey" id="company_name" name="company_name" placeholder="Company Name" value="<?php echo $all_meta_for_user['company_name'][0];?>"  required>
								
                                        
                                    </div>
                                </div>             
                   <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Company Logo </label>
                                    <?php if(empty($all_meta_for_user['user_profile_url'][0])){?>  
                                    <div class="col-sm-10">
                                                     
                                        
					<input  type="file" class="form-control" name="profilepic" id="profilepic" >				
                                      
                                       
		                  </div>
                                   <?php }else{?>
                                    <div id="showprofilepic">
                                    <div class="col-sm-5">
                                       <img width="200" id="userprofilepic"  name="userprofilepic" src="<?php echo $all_meta_for_user['user_profile_url'][0];?>" >
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
                          
                           <?php   foreach ($additional_fields as $key=>$value){  if($additional_fields[$key]['name'] !='Notes'){?>
                          
                                 <div class="form-group row" >
                                    <label class="col-sm-2 form-control-label"><?php echo $additional_fields[$key]['name'];?></label>
                                    <div class="col-sm-10">
                                        
					<input type="text"  class="form-control mymetakey" id="<?php echo $additional_fields[$key]['key'];?>" name="<?php echo $additional_fields[$key]['key'];?>" value="<?php echo $all_meta_for_user[$additional_fields[$key]['key']][0];?>" placeholder="<?php echo $additional_fields[$key]['name'];?>" >
								
                                        
                                    </div>
                                </div>
                           <?php }} ?>
                               
                                <div class="form-group row" >
                                    <label class="col-sm-2 form-control-label">Notes</label>
                                    <div class="col-sm-10">
                                        
                                        <textarea   class="form-control mymetakey" id="usernotes" name="usernotes"  ><?php echo $all_meta_for_user['usernotes'][0];?></textarea>
								
                                        
                                    </div>
                                </div>
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