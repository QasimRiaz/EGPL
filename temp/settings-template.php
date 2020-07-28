<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
       
    //role.js 
		
      
     $oldvalues = get_option( 'ContenteManager_Settings' );
     $eventdate = $oldvalues['ContentManager']['eventdate'];
     $mainheaderbackground = $oldvalues['ContentManager']['mainheader'];
     $mainheaderlogo = $oldvalues['ContentManager']['mainheaderlogo'];
     $applicationmoderationstatus = $oldvalues['ContentManager']['applicationmoderationstatus'];
     
     $welcomememailreplayto = get_option('AR_Contentmanager_Email_Template_welcome');
     $replaytoemailadd = $welcomememailreplayto['welcome_email_template']['replaytoemailadd'];
     $registration_notificationemails = $oldvalues['ContentManager']['registration_notificationemails'];
     
     if(empty($registration_notificationemails)){
         
         $registration_notificationemails  = $replaytoemailadd;
     }
     
     //echo $mainheader;exit;
     
     $formemail = $oldvalues['ContentManager']['formemail'];
     $mandrill = $oldvalues['ContentManager']['mandrill'];
     $infocontent = $oldvalues['ContentManager']['infocontent'];
     $addresspoints = $oldvalues['ContentManager']['addresspoints'];
     
       include 'cm_header.php';
       include 'cm_left_menu_bar.php';
                
       ?>
        <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Admin Settings</h3>
                           
                        </div>
                    </div>
                </div>
            </header>

            <div class="box-typical box-typical-padding">
               

              <form method="post" action="javascript:void(0);" onSubmit="update_admin_settings()">
               
                  
                 <?php if(empty($mainheaderbackground) && empty($mainheaderlogo)){
                     
                            $style ="style='display:none;'";
                  }else{
                            $style ="";
                  } ?>
                 
                <div class="form-group row">
                    <div class="col-sm-5"><label class=" form-control-label">Header Image </label><p style="font-size: 16px;margin-left: 12px;">(Recommended size (Pixels) - w:1260 x h:230)</p></div>
                        <div class="col-sm-7">
                            <input type="hidden" id="headerbannerurl" value="<?php echo $mainheaderbackground;?>" /> 
                            
                            
                            <input   accept=".jpeg, .jpg, .jpe,.png" onchange="getFilePathheaderbanner()" type="file" class="form-control"  name="headerbanner" id="headerbanner"  >
                        
                         
                        
                        </div>

                 </div>
               
                   <?php if(!empty($mainheaderbackground)){?> 
                  <div class="form-group row">
                      <div class="col-sm-4"><label class=" form-control-label">Preview </label></div>
                        <div class="col-sm-8">
                            
                           
                        
                        </div>

                 </div>
                   <?php }?>
                  <div class="form-group row privewdiv" <?php echo $style;?>  > 
                     <div class="col-sm-1"></div>
                  <div class="col-sm-10 " id="previewheaderdiv" style="background-size: cover;text-align: center;background-image:url(<?php echo $mainheaderbackground;?>);">
                      
                      
                     
<!--                      <img  id="previewlogo" style="max-width: 100%;" src="<?php echo $mainheaderlogo;?>"/>-->
                      
                      
                      
                  </div>
                     <div class="col-sm-1"></div>
                  </div>
                  <?php if(!empty($mainheaderbackground) ){?> 
                    <div class="form-group row removebutton">
                                    <label class="col-sm-4 form-control-label"></label>
                                    <div class="col-sm-8">
                                             <a onclick="removeheaderimage()" class="btn btn-lg btn-danger" >Remove</a>
                                            
                                        
                                    </div>
                    </div>
                  <?php } ?>
                  
                  
                              <input type="hidden"  class="form-control" id="eventdate" value ="" >

                            <hr>
                            <br>
                  
                  
                    <div class="form-group row">
                      <div class="col-sm-12">
                          <h3>User Application Settings</h3> 
                      </div>
                      
                  </div>
                <div class="form-group row">
                    <div class="col-sm-8"><label class=" form-control-label">Enable/Disable Application Moderation </label></div>
                        <div class="col-sm-4">
                            
                            <input type="checkbox" class="toggle-one" id="applicationmoderationstatus" data-toggle="toggle" <?php echo $applicationmoderationstatus;?>>
                            
                        </div>
                    
                </div> 
                  <div class="from-group row">
                      
                      
                      <div class="col-sm-12"><p style="font-size: 20px;margin-left: 12px;">Enable this setting if you want to moderate user applications before they gain access into this event portal. If this is disabled, when a user completes the form the system will immediately create an account for that user and send them the Welcome Email with login credentials.</p></div>
                      
                  </div>
               
                  <br>
                  <br>
                   <div class="form-group row">
                    <div class="col-sm-5"><label class=" form-control-label">Registration Notification Emails </label><p style="font-size: 16px;margin-left: 12px;">(Comma separated list of email addresses that should receive submission notifications)</p></div>
                        <div class="col-sm-7">
                            
                            
                            
                            <textarea id="registration_notificationemails" class="form-control mymetakey" ><?php echo $registration_notificationemails;?></textarea> 
                            
                        </div>

                 </div>
               <br>
                  <br>
                  <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"></label>
                                    <div class="col-sm-8">
                                             <button type="submit"  name="addsettings"  class="btn btn-lg mycustomwidth btn-success" value="Update">Update</button>
                                            
                                        
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