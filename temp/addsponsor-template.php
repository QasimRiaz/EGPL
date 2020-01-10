<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
       
      require_once plugin_dir_path( __DIR__ ) . 'includes/egpl-custome-functions.php';
      $GetAllcustomefields = new EGPLCustomeFunctions();
      
      //$test = 'wp_7_user_roles';
      //$result = get_option($test);
      
     // echo '<pre>';
     // print_r($result);exit;
      
      $test = 'custome_task_manager_data';
      $result = get_option($test);
      $settitng_key='ContenteManager_Settings';
      $additional_fields_settings_key = 'EGPL_Settings_Additionalfield';
      $additional_fields = get_option($additional_fields_settings_key);
      $welcomeemail_template_info_key='AR_Contentmanager_Email_Template_welcome';
      $welcomeemail_template_info = get_option($welcomeemail_template_info_key);
      $sponsor_info = get_option($settitng_key);
      $sponsor_name = $sponsor_info['ContentManager']['sponsor-name'];

      global $wp_roles;

      $all_roles = $wp_roles->roles;
      $welcomeemail_template_info_key='AR_Contentmanager_Email_Template_welcome';
      $welcomeemail_template_info = get_option($welcomeemail_template_info_key);
      $additional_fields = $GetAllcustomefields->getAllcustomefields();
      
      function sortByOrder($a, $b) {
            return $a['fieldIndex'] - $b['fieldIndex'];
      }

      usort($additional_fields, 'sortByOrder');
      
      
      //sort($additional_fields);
     
    // echo '<pre>';
    // print_r($additional_fields);exit;
      
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
								
                                                                     
                                                                         <?php
                                                                         foreach ($all_roles as $key => $name) {


                                                                             if ($key != 'administrator' && $key != 'contentmanager' && $key != 'subscriber') {
                                                                                 echo '<option value="' . $key . '">' . $name['name'] . '</option>';
                                                                             }
                                                                         }
                                                                         ?>
								 </select>


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

                

              <form method="post" class="needs-validation" action="javascript:void(0);" onSubmit="add_new_sponsor()" >
                  <br>
                  <br>
                <section class="tabs-section">
				<div class="tabs-section-nav tabs-section-nav-icons">
					<div class="tbl">
						<ul class="nav" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" href="#tabs-1-tab-1" role="tab" data-toggle="tab">
									<span class="nav-link-in">
										<span class="fa fa-info-circle" ></span>
										User Details
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
                                        
                                       <div class="form-group row" >
                                            <div class="col-sm-2">
                                                <label>Email *</label>
                                            </div>
                                            <div class="col-sm-5">
                                                <input type="email"  class="form-control mymetakey"  id="Semail" name="Semail" placeholder="Email" required='ture'>
                                            </div>
                                            <div class="col-sm-5">
                                        
                                                <a class="btn btn-inline" onclick="checkemailaddressalreadyexist()">Lookup User</a>
                                            </div> 
                                        </div>  
                                        
                                        
                                        <div class="form-group row" >
                                            <div class="col-sm-2">
                                                <label>First Name *</label>
                                            </div>
                                            <div class="col-sm-10">
                                                <input  type="text"  class="form-control mymetakey" id="first_name" name="first_name" placeholder="First Name" required='ture'>
                                            </div>
                                           
                                        </div>  
                                        
                                        <div class="form-group row" >
                                            <div class="col-sm-2">
                                                <label>Last Name *</label>
                                            </div>
                                            <div class="col-sm-10">
                                                <input type="text"  class="form-control mymetakey" id="last_name" name="last_name" placeholder="Last Name" required='ture'>
                                            </div>
                                           
                                        </div> 
                                          <div class="form-group row" >
                                            <div class="col-sm-2">
                                                <label>Company Name *</label>
                                            </div>
                                            <div class="col-sm-10">
                                                <input type="text"  class="form-control mymetakey" id="company_name" name="company_name" placeholder="Company Name" required='ture'>
                                            </div>
                                           
                                        </div> 
                                        
                                        <div class="form-group row" >
                                            <div class="col-sm-2">
                                                <label>Level *</label>
                                            </div>
                                            <div class="col-sm-10">
                                                <select class="select2 mycustomedropdown"  title="Role" id="Role" data-allow-clear="true" data-toggle="tooltip" required='ture' >
                                                     <option value=""></option>
                                                    <?php  foreach ($all_roles as $key => $name) { 
                                                        
                                                        
                                                       
                                                        if ($key != 'administrator' && $key != 'contentmanager') {
                                                        ?>
                                                  
                                                         <option value='<?php echo$key; ?>'><?php echo $name['name'];?></option>
                                                    
                                                    <? }} ?>
                                                </select>
                                            </div>
                                           
                                        </div> 
                                        
                                      
                                        
                                              
                                       
                                        
                                        
                              <?php   foreach ($additional_fields as $key=>$value){ 
                           
                                if($additional_fields[$key]['fieldsystemtask'] == "checked" && $additional_fields[$key]['SystemfieldInternal'] != "checked"){
                                $requiredStatus = $additional_fields[$key]['fieldrequriedstatus'];
                                $requriedStatus = "";
                                $requiredStatueUpdate = "";
                                if($requiredStatus == true){
                                    
                                    
                                    $requiredStatueUpdate = "required='ture'";
                                    $requriedStatus = "*";
                                    
                                }
                                ?>
                                
                                <?php if($additional_fields[$key]['fielduniquekey'] !="Semail" && $additional_fields[$key]['fielduniquekey'] !="first_name" && $additional_fields[$key]['fielduniquekey'] !="last_name" && $additional_fields[$key]['fielduniquekey'] !="Role" && $additional_fields[$key]['fielduniquekey'] !="company_name" && $additional_fields[$key]['fieldType'] != 'checkbox' && $additional_fields[$key]['fieldType'] != 'display'){ ?>
                               
                                    <div class="form-group row" >
                                    <div class="col-sm-2">
                                    <label><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatus;?>
                                    <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>
                                    
                                      <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                    <?php }?>
                                    </label>
                                    <?php if(!empty($additional_fields[$key]['fielddescription'])){?>
                                    
                                    <?php echo $additional_fields[$key]['fielddescription'];?>
                                    <?php }?>
                                    </div>
                                    
                                    <?php if($additional_fields[$key]['fieldType'] == 'text' || $additional_fields[$key]['fieldType'] == 'email'  ||$additional_fields[$key]['fieldType'] == 'date' ||$additional_fields[$key]['fieldType'] == 'number'){ ?> 
                                        
                                        <?php if($additional_fields[$key]['fieldType'] == 'email'){?>
                                    
                                            <div class="col-sm-5">
                                                <input type="<?php echo $additional_fields[$key]['fieldType'];?>"  class="form-control mymetakey" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>>
                                            </div>
                                            <div class="col-sm-5">
                                        
                                                <a class="btn btn-inline" onclick="checkemailaddressalreadyexist()">Lookup User</a>
                                             </div>
                                    
                                        <?php }else{?>
                                        <div class="col-sm-10">
					<input type="<?php echo $additional_fields[$key]['fieldType'];?>"  class="form-control mymetakey" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>>
                                        </div>
                                        <?php }}else if($additional_fields[$key]['fieldType'] == 'textarea'){?>
                                             <div class="col-sm-10">
                                             <textarea   <?php echo $additional_fields[$key]['attribute'];?>  class="form-control mymetakey" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>></textarea>
                                             </div>
                                        
                                        
                                       <?php }else if($additional_fields[$key]['fieldType'] == 'url'){?>
                                        
                                              <div class="col-sm-10">
                                                <input type="text"  class="form-control speiclurlfield" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>>
                                              </div>
                                       
                                        
                                        
                                       <?php }else if($additional_fields[$key]['fieldType'] == 'dropdown'){?>
                                             
                                             <div class="col-sm-10">
                                             <?php if($additional_fields[$key]['multiselect'] == 'chekced') {?>
                                              <select class="select2 mycustomedropdown"  title="<?php echo $additional_fields[$key]['fielduniquekey'];?>" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" data-allow-clear="true" data-toggle="tooltip" multiple="multiple" <?php echo $requiredStatueUpdate;?>>
                                                    <?php  foreach ($all_roles as $key => $name) { 
                                                        
                                                        
                                                        if ($key != 'administrator' && $key != 'contentmanager' && $key != 'subscriber') {
                                                        ?>
                                                  
                                                         <option value='<?php echo$key; ?>'><?php echo $name['name'];?></option>
                                                    
                                                    <? }} ?>
                                                   
                                              </select>
                                             <?php }else {?>
                                                    
                                                    <select class="select2 mycustomedropdown"  title="<?php echo $additional_fields[$key]['fielduniquekey'];?>" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" data-allow-clear="true" <?php echo $requiredStatueUpdate;?>>

                                                       <?php  foreach ($all_roles as $key => $name) { 
                                                        
                                                        
                                                        if ($key != 'administrator' && $key != 'contentmanager') {
                                                        ?>
                                                  
                                                         <option value='<?php echo $key;?>'><?php echo $name['name'];?></option>
                                                    
                                                       <? }} ?>

                                                   </select>
                                             
                                             <?php } ?>
                                             </div>
                                       <?php } ?></div><?php }?> 
                                       <?php if($additional_fields[$key]['fieldType'] == 'checkbox'){ 
                                           
                                            $requiredStatus = $additional_fields[$key]['fieldrequriedstatus'];
                                            $requriedStatus = "";
                                            $requiredStatueUpdate = "";
                                            if($requiredStatus == true){


                                                $requiredStatueUpdate = "required='ture'";
                                                $requriedStatus = "*";

                                            }
                                           
                                           
                                           ?>
                                             <div class="form-group row" >
                                                 
                                                 <div class="col-sm-12">
                                                     
                                                     <input  class="mycustomcheckbox"  <?php echo $requiredStatueUpdate;?> type="checkbox" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>"><?php echo '   '.$additional_fields[$key]['fieldName'];?><br/>
                                             
                                                </div>
                                                 </div>
                                                    
                                               
                                            
                                       <?}?>
                                       <?php if($additional_fields[$key]['fieldType'] == 'display'){ ?>
                                             <div class="form-group row" >
                                                 
                                                 <div class="col-sm-12">
                                                     
                                                     <?php echo $additional_fields[$key]['fielddescription'];?>
                                                </div>
                                               </div>
                                               
                                       <?}?>
                                    
                                 
                           
                                <?php }} ?>
                                                     
                  
                                        
                                    </div><!--.tab-pane-->
                <div role="tabpanel" class="tab-pane fade" id="tabs-1-tab-2">
                           
                       <?php   foreach ($additional_fields as $key=>$value){ 
                           
                                if($additional_fields[$key]['fieldsystemtask'] != "checked" && $additional_fields[$key]['SystemfieldInternal'] != "checked"){
                                
                                
                                
                                if($additional_fields[$key]['fieldType'] != 'checkbox' && $additional_fields[$key]['fieldType'] != 'display'){ 
                                    
                                    $requiredStatus = $additional_fields[$key]['fieldrequriedstatus'];
                                    $requriedStatussysomb = "";
                                    $requiredStatueUpdate = "";
                                    
                                    if($requiredStatus == true){
                                    
                                    
                                    $requiredStatueUpdate = "required='ture'";
                                    $requriedStatussysomb = "*";
                                    
                                    }
                                    
                                    
                                    
                                    ?>
                               
                                       
                                        
                                      <?php if($additional_fields[$key]['fieldType'] == 'text' || $additional_fields[$key]['fieldType'] == 'email' || $additional_fields[$key]['fieldType'] == 'date' ||$additional_fields[$key]['fieldType'] == 'number'){ ?> 
                                            <div class="form-group row" >
                                            <div class="col-sm-4">
                                            <label><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatussysomb;?>
                                            <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>

                                              <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                            <?php }?>
                                            </label>
                                            <?php if(!empty($additional_fields[$key]['fielddescription'])){?>

                                            <?php echo $additional_fields[$key]['fielddescription'];?>
                                            <?php }?>
                                            </div>
                                            <div class="col-sm-8">
                                            <input type="<?php echo $additional_fields[$key]['fieldType'];?>"  class="form-control mymetakey" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>>
                                            </div>
                                            </div>
                                        <?php }else if($additional_fields[$key]['fieldType'] == 'textarea'){?>
                                             <div class="form-group row" >
                                            <div class="col-sm-4">
                                            <label><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatussysomb;?>
                                            <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>

                                              <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                            <?php }?>
                                            </label>
                                            <?php if(!empty($additional_fields[$key]['fielddescription'])){?>

                                            <?php echo $additional_fields[$key]['fielddescription'];?>
                                            <?php }?>
                                            </div>
                                            <div class="col-sm-8">
                                             <textarea  <?php echo $additional_fields[$key]['attribute'];?>   class="form-control mymetakey" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>></textarea>
                                              </div>
                                            </div>
                                        
                                       <?php }else if($additional_fields[$key]['fieldType'] == 'file'){?>
                                           
                                           <div class="form-group row" >
                                            <div class="col-sm-4">
                                            <label><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatussysomb;?>
                                            <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>
                                              
                                              <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                            <?php }?>
                                            </label>
                                            <?php if(!empty($additional_fields[$key]['fielddescription'])){?>

                                            <?php echo $additional_fields[$key]['fielddescription'];?>
                                            <?php }?>
                                            </div>
                                            <div class="col-sm-8">
                                           <input <?php echo $additional_fields[$key]['attribute'];?> type="<?php echo $additional_fields[$key]['fieldType'];?>"  class="form-control" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="customefiels[]" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>>
                                        </div>
                                            </div>
                                           
                                       <?php }else if($additional_fields[$key]['fieldType'] == 'dropdown'){?>
                                             <div class="form-group row" >
                                            <div class="col-sm-4">
                                            <label><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatussysomb;?>
                                            <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>

                                              <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                            <?php }?>
                                            </label>
                                            <?php if(!empty($additional_fields[$key]['fielddescription'])){?>

                                            <?php echo $additional_fields[$key]['fielddescription'];?>
                                            <?php }?>
                                            </div>
                                            <div class="col-sm-8">
                                             
                                             <?php if($additional_fields[$key]['multiselect'] == "checked") {?>
                                              <select class="select2 mycustomedropdown mymetakey"  title="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" data-allow-clear="true" data-toggle="tooltip" multiple="multiple" <?php echo $requiredStatueUpdate;?>>
                                                    <?php foreach ($additional_fields[$key]['options'] as $key=>$value){ ?>
                                                  
                                                         <option value='<?php echo $value->label;?>'><?php echo $value->label;?></option>
                                                    
                                                    <? } ?>
                                                   
                                              </select>
                                             <?php }else {?>
                                                
                                                    <select class="select2 mycustomedropdown mymetakey"  title="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" data-allow-clear="true" <?php echo $requiredStatueUpdate;?>>

                                                       <?php foreach ($additional_fields[$key]['options'] as $key=>$value){ ?>
                                                  
                                                         <option value='<?php echo $value->label;?>'><?php echo $value->label;?></option>
                                                    
                                                       <? } ?>

                                                   </select>
                                             
                                             <?php } ?>
                                            </div>
                                            </div>
                                       <?php }else if($additional_fields[$key]['fieldType'] == 'url'){ ?>
                                           
                                           
                                           <div class="form-group row" >
                                            <div class="col-sm-4">
                                            <label><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatussysomb;?>
                                            <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>

                                              <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                            <?php }?>
                                            </label>
                                            <?php if(!empty($additional_fields[$key]['fielddescription'])){?>

                                            <?php echo $additional_fields[$key]['fielddescription'];?>
                                            <?php }?>
                                            </div>
                                            <div class="col-sm-8">
                                            <input type="text"  class="form-control speiclurlfield" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>>
                                            </div>
                                            </div>
                                           
                                           
                                           
                                       <?php }} ?> 
                                           
                                       <?php if($additional_fields[$key]['fieldType'] == 'checkbox'){ 
                                           
                                                    $requiredStatus = $additional_fields[$key]['fieldrequriedstatus'];
                                             $requriedStatussysomb = "";
                                             $requiredStatueUpdate = "";

                                             if($requiredStatus == true){


                                             $requiredStatueUpdate = "required='ture'";
                                             $requriedStatussysomb = "*";

                                             }
                                           
                                           
                                           
                                           ?>
                                             <div class="form-group row" >
                                                
                                                 <div class="col-sm-12" >
                                                     
                                                     <input  class="mycustomcheckbox"  <?php echo $requiredStatueUpdate;?> type="checkbox" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>"><?php echo '   '.$additional_fields[$key]['fieldName'];?><br/>
                                             
                                                 </div>
                                               </div>     
                                               
                                            
                                       <?}?>
                                       <?php if($additional_fields[$key]['fieldType'] == 'display'){ ?>
                                             <div class="form-group row" >
                                                 
                                                 <div class="col-sm-12">
                                                     
                                                     <?php echo $additional_fields[$key]['fielddescription'];?>
                                               </div>
                                               </div>  
                                       <?}?>
                                  
                                   
                                    
                           
                       <?php }} ?>
                             
                               
	                  
                                    </div><!--.tab-pane-->
					
				</div><!--.tab-content-->
			</section><!--.tabs-section-->
                  
                   
             
           
                       
                    
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
                  <div class="form-group row">
                                    <label class="col-sm-2 form-control-label"></label>
                                    <div class="col-sm-6">
                                             <button type="submit" id="addnewsponsor_q" name="addsponsor"  class="btn btn-lg mycustomwidth btn-success" value="Register">Create</button>
                                            
                                        
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