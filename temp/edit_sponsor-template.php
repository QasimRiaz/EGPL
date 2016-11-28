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

                <h5 class="m-t-lg with-border">Personal Information</h5>

              <form method="post" action="javascript:void(0);" onSubmit="update_sponsor()">
                    
                  
                    
              
                                    <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Email <strong>*</strong></label>
                                    <div class="col-sm-10">
                                         <input type="hidden" name="sponsorid" id="sponsorid" value="<?php echo $sponsor_id;?>" >
								<input type="text"  class="form-control" id="Semail" placeholder="Email"  value="<?php echo $meta_for_user->user_email;?>" readonly>
							
                                        
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

            if ($key != 'administrator' && $key != 'contentmanager') {
                echo '<option value="' . $key . '">' . $name . '</option>';
            }
        }
    }
                                                                         ?>
								 </select>
					   
                                        
                                    </div>
                 </div>   
                               
                                   
                              
                                   
                  <h5 class="m-t-lg with-border">Additional Information</h5>                  
                                
                               <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Company Name <strong>*</strong></label>
                                    <div class="col-sm-10">
                                        
								<input type="text"  class="form-control mymetakey" id="company_name" name="company_name" placeholder="Company Name" value="<?php echo $all_meta_for_user['company_name'][0];?>"  required>
								
                                        
                                    </div>
                                </div>
                      <h5 class="m-t-lg with-border"></h5>
                                  <div class="form-group row">
                                    <label class="col-sm-2 form-control-label"></label>
                                    <div class="col-sm-6">
                                             <button type="submit"  name="updatesponsor"  class="btn btn-lg mycustomwidth btn-success" value="Update">Update</button>
                                          
                                        
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