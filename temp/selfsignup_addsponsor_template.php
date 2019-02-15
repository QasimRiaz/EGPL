<?php
// Silence is golden.
 get_header();
 $additional_fields_settings_key = 'EGPL_Settings_Additionalfield';
 $additional_fields = get_option($additional_fields_settings_key);
 //echo '<pre>';
 //print_r($additional_fields);exit;
 
 
 
 
 $base_url  = get_site_url();
?>
  <script>
    currentsiteurl = '<?php echo $base_url;?>';
  </script> 
  <link href="<?php echo $base_url;?>/wp-content/plugins/EGPL/js/jquery-confirm.css" rel="stylesheet">
  
<div id="content" class="full-width">
        <div class="page-content" style="max-width: 85%;margin-left: auto;margin-right: auto;">
        
            
            
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
								
			
                  
                           
                       <?php   foreach ($additional_fields as $key=>$value){  
                                
                                $htmlinputfield ="";
                                if($additional_fields[$key]['type'] !='checkbox' && $additional_fields[$key]['type'] !='html' ){
                                    
                                    if($additional_fields[$key]['formlabel'] != 'Registration Codes'){
                                    ?>
                              
                                
                                <div class="form-group row" >
                                    
                                   <?php if($additional_fields[$key]['required'] == true){ ?>
                                     <label class="col-sm-4 fontclass"><?php echo $additional_fields[$key]['formlabel'];?> *</label>
                                   <?php }else{?>
                                      <label class="col-sm-4 fontclass"><?php echo $additional_fields[$key]['formlabel'];?></label>
                                     
                                   <?php }?>
                                     
                                    <div class="col-sm-8">
                                        <?php if($additional_fields[$key]['type'] == 'text'){ 
                                            
                                            
                                            
                                            
                                            $htmlinputfield .= '<input type="text"  class="form-control mymetakey fontclass" id="'.$additional_fields[$key]['key'].'" name="'.$additional_fields[$key]['key'].'"'; 
                                            
                                            if($additional_fields[$key]['required'] == true){
                                                
                                                 $htmlinputfield .= 'required="true"' ;
                                                
                                            }
                                            
                                           
                                            $htmlinputfield .= '>';
                                            echo $htmlinputfield;
                                            ?>
					
                                        <?php }else if($additional_fields[$key]['type'] == 'textarea'){			
                                          
                                            $htmlinputfield .='<textarea   class="form-control mymetakey" id="'.$additional_fields[$key]['key'].'" name="'.$additional_fields[$key]['key'].'"';
                                             if($additional_fields[$key]['required'] == true){
                                                
                                                 $htmlinputfield .= 'required="true"' ;
                                                
                                            }
                                            $htmlinputfield .= '></textarea>';
                                            echo $htmlinputfield;
                                          ?>
                                          
                                         <?php }else if($additional_fields[$key]['type'] == 'dropdown'){?>
                                             
                                             
                                             <?php if($additional_fields[$key]['multiselect'] == true) {
                                                 
                                            $htmlinputfield .='<select class="select2 mycustomedropdown" style="width: 100%;" ';
                                             if($additional_fields[$key]['required'] == true){
                                                
                                                 $htmlinputfield .= 'required="true"' ;
                                                
                                            }
                                            
                                            
                                             $htmlinputfield .='id="'.$additional_fields[$key]['key'].'" data-allow-clear="true" data-toggle="tooltip" multiple="multiple">';
                                        
                                                   foreach ($additional_fields[$key]['options'] as $key=>$value){ 
                                                  
                                                         $htmlinputfield .= "<option value='". $value['value']."'>".$value['value']."</option>";
                                                    
                                                   } 
                                               $htmlinputfield .="</select>";
                                               echo $htmlinputfield;
                                             }else {
                                                
                                                    $htmlinputfield .='<select style="width: 100%;" class="select2 mycustomesingledropdown"';
                                                      if($additional_fields[$key]['required'] == true){
                                                
                                                            $htmlinputfield .= 'required="true"' ;
                                                
                                                      }
                                                    
                                                     $htmlinputfield .= 'id="'.$additional_fields[$key]['key'].'" data-allow-clear="true">';

                                                        foreach ($additional_fields[$key]['options'] as $key=>$value){ 
                                                  
                                                         $htmlinputfield .="<option value='".$value['value']."'>".$value['value']."</option>";
                                                    
                                                       }

                                                   $htmlinputfield .='</select>';
                                                   echo $htmlinputfield;
                                              } 
                                            
                                        } ?>
                                           
                                            </div>
                                </div>
                                           
                       <?php }}?> 
                                            
                                        <?php if($additional_fields[$key]['type'] == 'checkbox'){ ?>
                                             <div class="form-group row" >
                                                 
                                                 <div class="col-sm-12">
                                                     
                                                     <p style="color:black;"><input  required="<?php echo $additional_fields[$key]['required'];?>" class="mycustomcheckbox"  type="checkbox" id="<?php echo $additional_fields[$key]['key'];?>"><?php echo '     '.$additional_fields[$key]['formlabel'];?></p><br/>
                                             
                                                     
                                                </div>
                                            </div>
                                            
                                       <?}?>
                                                     <a href="cm_footer.php"></a>
                                       <?php if($additional_fields[$key]['type'] == 'html'){ ?>
                                             <div class="form-group row" >
                                                 
                                                 <div class="col-sm-12">
                                                   
                                                     <?php echo $additional_fields[$key]['name'];?>
                                                  
                                                     
                                             </div>
                                            </div>   
                                            
                                       <?}?>    
                                            
                                   
                           
                       <?php } ?>
                             
                               
	                  
                      <div class="form-group row">
                                     <label class="col-sm-4 fontclass"></label>
                                    <div class="col-sm-8">
                                             <button type="submit" id="selfisignup" name="selfisignup"  class="button fusion-button fusion-button-default button-square fusion-button-xlarge button-xlarge button-flat  fusion-mobile-button continue-center" value="Register">Submit</button>
                                            
                                        
                                    </div>
                                </div>
                  
                

                </form>
            </div>
        </div>
    </div>
</div>
<?php   get_footer(); ?>