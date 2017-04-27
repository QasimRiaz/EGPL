<?php
// Silence is golden.
 get_header();
 $additional_fields_settings_key = 'EGPL_Settings_Additionalfield';
 $additional_fields = get_option($additional_fields_settings_key);
 
 
?>
 <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/selfsigncss.css?v=2.19">
<div id="content" class="full-width">
    <div class="form">

        <a href="../new way/new_user_report_template.php"></a>

        <div class="tab-content">
            <div id="signup">   
                <h1>Registration</h1>

                <form action="/" method="post">

                    <div class="top-row">
                        <div class="field-wrap">
                            <label>
                                First Name<span class="req">*</span>
                            </label>
                            <input type="text" required autocomplete="off" />
                        </div>

                        <div class="field-wrap">
                            <label>
                                Last Name<span class="req">*</span>
                            </label>
                            <input type="text"required autocomplete="off"/>
                        </div>
                    </div>

                    <div class="field-wrap">
                        <label>
                            Email Address<span class="req">*</span>
                        </label>
                        <input type="email"required autocomplete="off"/>
                    </div>

                    <div class="field-wrap">
                        <label>
                            Set A Password<span class="req">*</span>
                        </label>
                        <input type="password"required autocomplete="off"/>
                    </div>

                    <button type="submit" class="button button-block"/>Get Started</button>

                </form>

            </div>

            <div id="login">   
                <h1>Welcome Back!</h1>

                <form action="/" method="post">

                    <div class="field-wrap">
                        <label>
                            Email Address<span class="req">*</span>
                        </label>
                        <input type="email"required autocomplete="off"/>
                    </div>

                    <div class="field-wrap">
                        <label>
                            Password<span class="req">*</span>
                        </label>
                        <input type="password"required autocomplete="off"/>
                    </div>

                    <p class="forgot"><a href="#">Forgot Password?</a></p>

                    <button class="button button-block"/>Log In</button>

                </form>

            </div>

        </div><!-- tab-content -->

    </div> <!-- /form -->
<!--        <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Registration</h3>
                           
                        </div>
                    </div>
                </div>
            </header>

            <div class="box-typical box-typical-padding">
                

                

              <form method="post" action="javascript:void(0);" onSubmit="selfisignupadd_new_sponsor()">
                  <br>
                  <br>
               
				

				
                                  
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
                                        
                                 
                
                           
                       <?php   foreach ($additional_fields as $key=>$value){  if($additional_fields[$key]['name'] !='Notes'){?>
                               
                                <div class="form-group row" >
                                    <label class="col-sm-2 form-control-label"><?php echo $additional_fields[$key]['name'];?></label>
                                    <div class="col-sm-10">
                                        
					<input type="text"  class="form-control mymetakey" id="<?php echo $additional_fields[$key]['key'];?>" name="<?php echo $additional_fields[$key]['key'];?>" placeholder="<?php echo $additional_fields[$key]['name'];?>" >
								
                                        
                                    </div>
                                </div>
                           
                       <?php }} ?>
                             
                                <div class="form-group row" >
                                    <label class="col-sm-2 form-control-label">Notes</label>
                                    <div class="col-sm-10">
                                        
                                        <textarea   class="form-control mymetakey" id="usernotes" name="usernotes"  ></textarea>
								
                                        
                                    </div>
                                </div>
	                  
                      <div class="form-group row">
                                    <label class="col-sm-2 form-control-label"></label>
                                    <div class="col-sm-6">
                                             <button type="submit" id="selfisignup" name="selfisignup"  class="btn btn-lg mycustomwidth btn-success" value="Register">Register</button>
                                            
                                        
                                    </div>
                                </div>
                  
                

                </form>
            </div>
        </div>
    </div>-->
</div>
<?php   get_footer(); ?>