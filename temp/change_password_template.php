<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
 include 'cm_header.php';
       include 'cm_left_menu_bar.php';
       
   //     $test = 'custome_task_manager_data';
 //   $result = get_option($test);
   //     uasort($result['profile_fields'], "cmp2");
     //  echo '<pre>';
       //print_r($result['profile_fields']);exit;
?>


<div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Change Password</h3>
                           
                        </div>
                    </div>
                </div>
            </header>

            <div class="box-typical box-typical-padding">
                <p>
                
              Here you can change your password. Be sure to keep a strong password and do not share it with others.  </p>

           
				

				<h5 class="m-t-lg with-border">Create New Password</h5>

				 <form method="post" action="javascript:void(0);" onSubmit="change_password_custome()">
					<div class="form-group row">
						<label class="col-sm-2 form-control-label">New Password <strong>*</strong></label>
						<div class="col-sm-10">
                                                    <div class="form-control-wrapper form-control-icon-left">  
							<input type="password"  name="newpassword" id="newpassword"   class="form-control"  required><div id="messages"></div>
						
                                                 <i class="font-icon fa fa-unlock-alt"></i>
 </div>
                                                </div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 form-control-label">Confirm Password<strong>*</strong></label>
						<div class="col-sm-10">
                                                    <div class="form-control-wrapper form-control-icon-left">  
							<input type="password"   name="confirmpassword" id="confirmpassword"   class="form-control" required>
						 <i class="font-icon fa fa-unlock-alt"></i>
 </div>
                                                    </div>
					</div>
                                     <div class="form-group row">
						<label class="col-sm-2 form-control-label"></label>
						<div class="col-sm-10">
							<p class="form-control-static"><div id="pass-info"></div></p>
						</div>
					</div>
                                     
                                    <h5 class="m-t-lg with-border"></h5>
                  <div class="form-group row">
                                    <label class="col-sm-2 form-control-label"></label>
                                    <div class="col-sm-6">
                                             <button type="submit"  name="addsponsor"  class="btn btn-lg mycustomwidth btn-success" value="Register">Update</button>
                                         
                                        
                                    </div>
                                </div>
					
				</form>

				

				
			</div><!--.box-typical-->
		</div><!--.container-fluid-->
	</div><!--.page-content-->
            
        
		 <?php   
  
    include 'cm_footer.php';
		
   }else{
       
       $redirect = get_site_url();
       wp_redirect( $redirect );exit;
   
   }
   ?>