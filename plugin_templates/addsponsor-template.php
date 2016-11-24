<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
       
  
		
      
      
      $test = 'user_meta_manager_data';
      $result = get_option($test);
      $settitng_key='ContenteManager_Settings';
      $sponsor_info = get_option($settitng_key);
    
      $sponsor_name = $sponsor_info['ContentManager']['sponsor-name'];
      //echo '<pre>';
     //print_r( $result); exit;
      global $wp_roles;
     
      $all_roles = $wp_roles->get_names();
     
       include 'cm_header.php';
       include 'cm_left_menu_bar.php';
                ?>
        <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Create User</h3>
                           
                        </div>
                    </div>
                </div>
            </header>

            <div class="box-typical box-typical-padding">
                <p>
                Create a new user with a unique and valid email address.
                </p>

                <h5 class="m-t-lg with-border">Personal Information</h5>

              <form method="post" action="javascript:void(0);" onSubmit="add_new_sponsor()">
                    
                
                  <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Email <strong>*</strong></label>
                                    <div class="col-sm-10">
                                           
								<input type="text"  class="form-control" id="Semail" placeholder="Email" required>
                                                               
                                        
                                    </div>
                                </div>
                   <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">First Name <strong>*</strong></label>
                                    <div class="col-sm-10">
                                         
								<input type="text"  class="form-control mymetakey" id="Sfname" name="first_name" placeholder="First Name" required>
								
                                        
                                    </div>
                                </div>
                   <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Last Name <strong>*</strong></label>
                                    <div class="col-sm-10">
                                        
								<input type="text"  class="form-control mymetakey" id="Slname" name="last_name" placeholder="Last Name" required>
								
                                        
                                    </div>
                                </div>
                  <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">User Level <strong>*</strong></label>
                                    <div class="col-sm-10">
                                           
								 <select  class="form-control" id="Srole" required>
								
                                                                     <option></option>
                                                                         <?php
                                                                         foreach ($all_roles as $key => $name) {


                                                                             if ($key != 'administrator' && $key != 'contentmanager') {
                                                                                 echo '<option value="' . $key . '">' . $name . '</option>';
                                                                             }
                                                                         }
                                                                         ?>
								 </select>
					    
                                        
                                    </div>
                 </div>
                    <h5 class="m-t-lg with-border">Additional Information</h5>
                  <?php
                                    $oldvalues = get_option( 'ContenteManager_Settings' );
                                    if(!empty($oldvalues['ContentManager']['exclude_sponsor_meta_create'])){
                                      
                                             foreach ($result['profile_fields'] as $profile_field_name => $profile_field_settings) {
                                                
                                                 if (!in_array($profile_field_name, $oldvalues['ContentManager']['exclude_sponsor_meta_create'])) {
                                                        if (strpos($profile_field_name, "task") !== false || strpos($profile_field_name, "convo") !== false) {
                                                            
                                                        } else {
                                                            
                                                            echo ' <div class="form-group row">
                                                      <label class="col-sm-2 form-control-label">' . $profile_field_settings['label'] . '</label><div class="col-sm-10">
                                           
                                                     <input  class="form-control mymetakey"  type="' . $profile_field_settings['type'] . '" id="' . $profile_field_name . '" name="' . $profile_field_name . '" value="" />
                                                   </div></div>';
                                                        }
                                                    }
                                                 
                                            
                                             
                                             
                                         }
                                    }else{
                                         foreach ($result['profile_fields'] as $profile_field_name => $profile_field_settings) {
                                                if (strpos($profile_field_name, "task") !== false || strpos($profile_field_name, "convo") !== false) {
                                            
                                                } else {
                                                 echo ' <div class="form-group row">
                                                      <label class="col-sm-2 form-control-label">' . $profile_field_settings['label'] . '</label><div class="col-sm-10">
                                          
                                                     <input  class="form-control mymetakey"  type="' . $profile_field_settings['type'] . '" id="' . $profile_field_name . '" name="' . $profile_field_name . '" value="" />
                                                   </div></div>';
                                            }
                                         }
                                   }
                                    ?>
                     <h5 class="m-t-lg with-border"></h5>
                     <div class="row" style="margin-bottom: 15px;">
                        <div class="col-sm-2"></div>
                            <div class="col-sm-6">
                                <div class="checkbox">
                                    <input  type="checkbox" id="checknewuser">Send welcome email.<br/>
                                    
                                   
                                </div>
                               

                            </div>
                    </div>
                    
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