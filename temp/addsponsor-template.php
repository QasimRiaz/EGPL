<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
       
  
   
      
      $test = 'custome_task_manager_data';
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

                

              <form method="post" action="javascript:void(0);" onSubmit="add_new_sponsor()">
                  <br>
                  <br>
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
                
                    <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Company Name <strong>*</strong></label>
                                    <div class="col-sm-10">
                                        
				<input type="text"  class="form-control mymetakey" id="company_name" name="company_name" placeholder="Company Name" required>
								
                                        
                                    </div>
                                </div>
                    <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Company Logo </label>
                                    <div class="col-sm-10">
                                                     
                                          
					<input  type="file" class="form-control" name="profilepic" id="profilepic" >				
								
				    </div>
                                    
		</div>
                                        
                                    </div><!--.tab-pane-->
                                    <div role="tabpanel" class="tab-pane fade" id="tabs-1-tab-2">
                           
                       
                               <div class="form-group row" >
                                    <label class="col-sm-2 form-control-label">Address 1</label>
                                    <div class="col-sm-10">
                                        
					<input type="text"  class="form-control mymetakey" id="address_line_1" name="address_line_1" placeholder="Address 1" >
								
                                        
                                    </div>
                                </div>
                                <div class="form-group row" >
                                    <label class="col-sm-2 form-control-label">Address 2</label>
                                    <div class="col-sm-10">
                                        
				<input type="text"  class="form-control mymetakey" id="address_line_1" name="address_line_2" placeholder="Address 2" >
								
                                        
                                    </div>
                                </div>
                                <div class="form-group row" >
                                    <label class="col-sm-2 form-control-label">City</label>
                                    <div class="col-sm-10">
                                        
								<input type="text"  class="form-control mymetakey" id="usercity" name="usercity" placeholder="City" >
								
                                        
                                    </div>
                                </div>
                                 <div class="form-group row" >
                                    <label class="col-sm-2 form-control-label">State</label>
                                    <div class="col-sm-10">
                                        
								<input type="text"  class="form-control mymetakey" id="userstate" name="userstate" placeholder="State" >
								
                                        
                                    </div>
                                </div>
                                 <div class="form-group row" >
                                    <label class="col-sm-2 form-control-label">Zipcode</label>
                                    <div class="col-sm-10">
                                        
								<input type="text"  class="form-control mymetakey" id="userzipcode" name="userzipcode" placeholder="Zipcode" >
								
                                        
                                    </div>
                                </div>
                                <div class="form-group row" >
                                    <label class="col-sm-2 form-control-label">Country</label>
                                    <div class="col-sm-10">
                                        
								<input type="text"  class="form-control mymetakey" id="usercountry" name="usercountry" placeholder="Country" >
								
                                        
                                    </div>
                                </div>
                                <div class="form-group row" >
                                    <label class="col-sm-2 form-control-label">Phone 1</label>
                                    <div class="col-sm-10">
                                        
								<input type="text"  class="form-control mymetakey" id="user_phone_1" name="user_phone_1" placeholder="Phone 1" >
								
                                        
                                    </div>
                                </div>
                                <div class="form-group row" >
                                    <label class="col-sm-2 form-control-label">Phone 2</label>
                                    <div class="col-sm-10">
                                        
								<input type="text"  class="form-control mymetakey" id="user_phone_2" name="user_phone_2" placeholder="Phone 2" >
								
                                        
                                    </div>
                                </div>
                                <div class="form-group row" >
                                    <label class="col-sm-2 form-control-label">Notes</label>
                                    <div class="col-sm-10">
                                        
                                        <textarea   class="form-control mymetakey" id="usernotes" name="usernotes"  ></textarea>
								
                                        
                                    </div>
                                </div>
	                  
                                    </div><!--.tab-pane-->
					
				</div><!--.tab-content-->
			</section><!--.tabs-section-->
                  
                   
             
           
                       
                    
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