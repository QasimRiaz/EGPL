<?php
// Silence is golden.
 get_header();
 $additional_fields_settings_key = 'EGPL_Settings_Additionalfield';
 $additional_fields = get_option($additional_fields_settings_key);
?>

<div id="content" class="full-width">
        <div class="page-content" style="max-width: 800px;margin-left: auto;margin-right: auto;">
        
            <h2 style="text-align: center;">Registration</h2>
            
            <div class="fusion-column-wrapper">
				<p>
					<?php 
					if (!(have_posts())) { ?>
					<?php __("There are no posts", "Avada"); ?><?php } ?>   
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
               		<?php the_content(); ?>
           	 	    <?php endwhile; ?> 
        	        <?php endif; ?>
			   </p>

			<div class="fusion-clearfix">
			</div>
		</div>
            
            <h4 >Basic Information</h4>
            <hr>
            <div class="box-typical box-typical-padding">
                

                

              <form method="post" action="javascript:void(0);" onSubmit="selfisignupadd_new_sponsor()">
                 
		 <div class="form-group row">
                                    <label class="col-sm-4 fontclass">Email <strong>*</strong></label>
                                    <div class="col-sm-8">
                                           
								<input type="text"  class="form-control fontclass" id="Semail" placeholder="Email" required>
                                                               
                                        
                                    </div>
                                </div>
                   <div class="form-group row">
                                     <label class="col-sm-4 fontclass">First Name <strong>*</strong></label>
                                    <div class="col-sm-8">
                                         
								<input type="text"  class="form-control mymetakey fontclass" id="Sfname" name="first_name" placeholder="First Name" required>
								
                                        
                                    </div>
                                </div>
                   <div class="form-group row">
                                     <label class="col-sm-4 fontclass">Last Name <strong>*</strong></label>
                                    <div class="col-sm-8">
                                        
								<input type="text"  class="form-control mymetakey fontclass" id="Slname" name="last_name" placeholder="Last Name" required>
								
                                        
                                    </div>
                                </div>

                
                    <div class="form-group row">
                                     <label class="col-sm-4 fontclass">Company Name <strong>*</strong></label>
                                    <div class="col-sm-8">
                                        
				<input type="text"  class="form-control mymetakey fontclass" id="company_name" name="company_name" placeholder="Company Name" required>
								
                                        
                                    </div>
                                </div>
                    <div class="form-group row">
                                     <label class="col-sm-4 fontclass">Company Logo </label>
                                    <div class="col-sm-8">
                                                     
                                          
					<input  type="file" class="form-control" name="profilepic" id="profilepic" >				
								
				    </div>
                                    
		</div>
                  
                                                     
                                          
                            <input  type="hidden" class="form-control mymetakey" name="selfsignupstatus" id="selfsignupstatus" value="Pending" >				
								
				   
                  
                  
                  <h4 >Additional Information</h4>
                  <hr>
                                 
                
                           
                       <?php   foreach ($additional_fields as $key=>$value){  if($additional_fields[$key]['name'] !='Notes' && $additional_fields[$key]['name'] !='Registration Codes'){?>
                              
                                <div class="form-group row" >
                                     <label class="col-sm-4 fontclass"><?php echo $additional_fields[$key]['name'];?></label>
                                    <div class="col-sm-8">
                                        
					<input type="text"  class="form-control mymetakey fontclass" id="<?php echo $additional_fields[$key]['key'];?>" name="<?php echo $additional_fields[$key]['key'];?>" placeholder="<?php echo $additional_fields[$key]['name'];?>" >
								
                                        
                                    </div>
                                </div>
                           
                       <?php }} ?>
                             
                               
	                  
                      <div class="form-group row">
                                     <label class="col-sm-4 fontclass"></label>
                                    <div class="col-sm-8">
                                             <button type="submit" id="selfisignup" name="selfisignup"  class="button fusion-button fusion-button-default button-square fusion-button-xlarge button-xlarge button-flat  fusion-mobile-button continue-center" value="Register">Register</button>
                                            
                                        
                                    </div>
                                </div>
                  
                

                </form>
            </div>
        </div>
    </div>
</div>
<?php   get_footer(); ?>