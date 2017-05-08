<?php
// Silence is golden.
if (current_user_can('administrator') || current_user_can('contentmanager')) {

    //get_header();

    $test = 'custome_task_manager_data';
    $result = get_option($test);
    
   // unset($result['profile_fields']['task_company_logo_png_file_datetime']);
   //  update_option($test, $result);
   //  exit;
  //  echo '<pre>';
 //    print_r($result['profile_fields']);exit;
    
  //  uasort($result['profile_fields'], "cmp2");
    
   //   echo '<pre>';
  //   print_r($result['profile_fields']);exit;
    $idx = 5;
    $labelArray = null;
    $file_upload_list.='<select id="file_upload" ><option value="">Select a Download Field</option>';

    foreach ($result['profile_fields'] as $profile_field_name => $profile_field_settings) {

        if ($profile_field_settings['type'] == 'color') {

            $file_upload_list.='<option value="' . $profile_field_name . '">' . $profile_field_settings['label'] . '</option>';
        }

        if (strpos($profile_field_name, "status") !== false) {


            if ($profile_field_settings['type'] == "select") {
                $task_drop_down.='<option value="' . $profile_field_settings['label'] . '">' . $profile_field_settings['label'] . '</option>';
            }
        }

        $showhidefields.='<option   title="' . $profile_field_name . '"  class="my-toggle" value="' . $profile_field_name . '"  >' . $profile_field_settings['label'] . '</option>';
        $idx++;
    }
    $file_upload_list.='</select>';
    $settitng_key = 'ContenteManager_Settings';
    $sponsor_info = get_option($settitng_key);
    $sponsor_name = $sponsor_info['ContentManager']['sponsor-name'];
    $report_seetingkey = 'AR_Contentmanager_Reports_Filter';
    $report_data = get_option($report_seetingkey);
   // update_option( $report_seetingkey, '' );
   // echo '<pre>';
  //  print_r($report_data);exit;
    
    
    $get_email_template='AR_Contentmanager_Email_Template';
    $email_template_data = get_option($get_email_template);
    $content = "";
    $editor_id_bulk = 'bodytext';
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $formemail = $oldvalues['ContentManager']['formemail'];
   
       include 'cm_header.php';
       include 'cm_left_menu_bar.php';
    
    
    ?>

<div class="page-content">
		
       <div class="container-fluid" id="reportstab">         
              
	
   	<section class="box-typical faq-page">
				<div class="faq-page-header-search">
					<div class="search">
						<div class="row">
						<div class="col-md-6">
							
								<fieldset class="form-group">
									
							<select style="width:100%;height:38px;"class="form-control" onchange="reportUpdateFilter()" id="reportdropdownlist">
								<option disabled selected hidden>Load a report</option>
                                                                <option value="defult"></option>
                                                                <option value="saveCurrentReport">Save Current Report As</option>
                                                                <optgroup label="---------------------------" id="reportlist">

                                                                        <?php
                                                                       if(!empty($report_data)){
                                                                        foreach ($report_data as $key => $value) {


                                                                            echo '<option value="' . $key . '">' . $key . '</option>';
                                                                        }
                                                                       }
                                                                        ?>
                                                                 </optgroup>
							</select>
								</fieldset>
								
								
							
						</div>
						<div class="col-md-6">
						  	
						  <form method="post" action="javascript:void(0);" onSubmit="update_admin_report()">     
						<div class="form-group">
                                                    	
							<div class="input-group">
                                                          
                                                           
                                                                
								<input style="height: 38px;" id="reportname" placeholder="Report Name"type="text" class="form-control" required>
								<div class="input-group-btn">
									<button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Action
									</button>
									<div class="dropdown-menu dropdown-menu-right">
                                                                            <button type="submit"  class="dropdown-item"> <i class="font-icon fa fa-save" aria-hidden="true"></i> Save</button>
										<a class="dropdown-item" onclick="removeSaveReport()"><i class="font-icon fa fa-remove" aria-hidden="true"></i>Delete</a>
										
									</div>
                                                                    
								</div>
                                                             
                                                              
							</div>
						</div>
                                                </form>		
						
						</div>
					</div>
					</div>
				</div><!--.faq-page-header-search-->

				<section class="faq-page-cats">
					<div class="row">
						<div class="col-md-4">
							<div class="faq-page-cat" title="Number of users matching current filter">
								<div class="faq-page-cat-icon"><i class="reporticon font-icon fa fa fa-filter fa-2x"></i></div>
								<div class="faq-page-cat-title">
									Filtered
								</div>
								<div class="faq-page-cat-txt" id="filteredstatscount" >0</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="faq-page-cat" title="Number of users currently selected">
								<div class="faq-page-cat-icon"><i class="reporticon font-icon fa fa-check-square fa-2x"></i></div>
								<div class="faq-page-cat-title">
									Selected
								</div>
							<div class="faq-page-cat-txt" id="selectedstatscount"> 0</div>
							</div>
						</div>
                                               <div class="col-md-4">
							<div class="faq-page-cat" title="Compose and send a bulk email message to the currently selected users">
								<div class="faq-page-cat-icon"><i class="reporticon font-icon fa fa-users fa-2x"></i></div>
								
								<div class="faq-page-cat-txt">
                                                                    
                                                                    <div class="btn-group">
                                                                            <button disabled type="button" id="sendbulkemailstatus" class="btn btn-inline dropdown-toggle btn-square-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                Bulk Action
                                                                                <span class="label label-pill label-danger" id="bulkemailcounter">0</span>
                                                                            </button>
                                                                            <div class="dropdown-menu">
                                                                                <a class="dropdown-item" onclick="old_get_bulk_email_address()"><i class="fa fa-mail-forward"></i> Bulk Email</a>
                                                                                <a class="dropdown-item" onclick="old_sendwelcomemsg()"><i class="fa fa-paper-plane"></i> Welcome Email</a>
                                                                                <a class="dropdown-item" onclick="old_sync_bulk_users()"><i class="fa fa-refresh"></i> Sync to Floorplan</a>
                                                                                
                                                                                
                                                                            </div>
                                                                        </div>
                                                                   
                                                                </div>
							</div>
						 </div>
                                            
                                            
						
                                              
					</div><!--.row-->
				</section><!--.faq-page-cats-->

			
			</section><!--.faq-page-->
       
       <div id="example2" style="width:auto;margin-top: 30px;"></div> 
       <input type="hidden" id='welcomecustomeemail' > 
       </div><!--.container-fluid-->
       <div class="container-fluid" id="bulkemailtab" style="display:none;">         
              
	
        <section class="box-typical faq-page">
				<div class="faq-page-header-search">
					<div class="search">
						<div class="row">
						<div class="col-md-6">
							
								<fieldset class="form-group">
									
                                                                    <select style="width:100%;height:38px;"class="form-control" onchange="templateupdatefilter()" id="templateupdatefilterlist">
                                                                            <option disabled selected hidden>Load a template</option>
                                                                            <option value="defult"></option>
                                                                            <option value="saveCurrentEmailtemplate">Save Current Template As</option>
                                                                            <optgroup label="Saved Templates" id="emailtemplatelist">

                                                                                <?php
                                                                                foreach ($email_template_data as $key => $value) {


                                                                                    echo '<option value="' . $key . '">' . $key . '</option>';
                                                                                }
                                                                                ?>
                                                                            </optgroup>
                                                                        </select>
						                 </fieldset>
						 </div>
                                                    
						<div class="col-md-6">
							
						 <form method="post" action="javascript:void(0);" onSubmit="update_admin_email_template()">    	
						<div class="form-group">
							<div class="input-group">
								<input style="height: 38px;" placeholder="Email Template Name" id="emailtemplate" type="text" class="form-control" required>
								<div class="input-group-btn">
									<button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Action
									</button>
									<div class="dropdown-menu dropdown-menu-right">
										<button type="submit"  name="saveemailtemplate"  class="dropdown-item"  ><i class="font-icon fa fa-save" aria-hidden="true"></i> Save</button>
										<a class="dropdown-item" onclick="removeemailtemplate()"><i class="font-icon fa fa-remove" aria-hidden="true"></i>Delete</a>
										
									</div>
								</div>
							</div>
						</div>
                                                 </form>		
								
						
						</div>
					</div>
					</div>
				</div><!--.faq-page-header-search-->

				<section class="faq-page-cats myfaq-bulk-email">
					<div class="row">
						<div class="col-md-8">
						<article class="faq-item">
							<div class="faq-item-circle">?</div>
							<p>Here you can send an email message to the currently selected users. You can also save or load bulk mail templates from the dropdown above.</p>
						</article>
					       </div>
<!--						<div class="col-md-4">
							<div class="faq-page-cat" title="Number of users currently selected">
								<div class="faq-page-cat-icon"><i class="reporticon font-icon fa fa-check-square fa-2x"></i></div>
								<div class="faq-page-cat-title">
									Selected
								</div>
							<div class="faq-page-cat-txt" id="selectedstatscountforbulk"> 0</div>
							</div>
						</div>-->
						<div class="col-md-4">
							<div class="faq-page-cat" title="Compose and send a bulk email message to the currently selected users">
								
								
								<div class="faq-page-cat-txt">
                                                                    <a type="button" onclick="old_back_report()" class="btn btn-inline btn-primary"><i class="font-icon fa fa-chevron-left"></i> Back to report</a>
                                                                   
                                                                </div>
							</div>
						</div>
					</div><!--.row-->
				</section><!--.faq-page-cats-->

			
			</section><!--.faq-page-->
                      
       
                        <div class="box-typical box-typical-padding">
                            <form method="post" action="javascript:void(0);" onSubmit="old_bulkemail_preview()">
                                 <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">From <strong>*</strong></label>
                                    <div class="col-sm-3">
                                        
                                       <div class="form-control-wrapper form-control-icon-left">
								<input type="text" id="fromname" class="form-control" placeholder="Name" required>
								<i class="font-icon fa fa-arrow-right"></i>
							</div>
                                    </div>
                                     <div class="col-sm-3">
                                         <label class="form-control-label"><?php if(!empty($formemail)){echo $formemail; }else{echo 'noreply@convospark.com';}?> </label>
                                       
                                    </div>
                                     <div class="col-sm-3">
                                       
                                         <label style="margin-top: 8px;font-weight: 500;"><i class="font-icon fa fa-check-square"></i>&nbsp;&nbsp;Selected Recipients:&nbsp;&nbsp;&nbsp;<span id="selectedstatscountforbulk">0</span> </label >
                                    </div>
                                </div>
                               
                                
                                
                                
                                <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">BCC <i style="cursor: pointer;" title='Please input an (only one) email address. All outgoing Welcome emails will be blind carbon copied to this address.'class="reporticon font-icon fa fa-question-circle"></i></label>
                                    <div class="col-sm-10">
                                            <div class="form-control-wrapper form-control-icon-left">
								<input type="text"  class="form-control" id="BCC" placeholder="BCC" >
								<i class="font-icon fa fa-copy"></i>
							</div>
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Subject <strong>*</strong></label>
                                    <div class="col-sm-10">
                                         <div class="form-control-wrapper form-control-icon-left">
								<input type="text"  class="form-control" id="emailsubject" placeholder="Subject" required>
								<i class="font-icon fa fa-edit"></i>
							</div>
                                    
                                    </div>
                                </div>
                                 
                                
                                <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Message <strong>*</strong> <p style="margin-top: 53px" id="sponsor_meta_keys"></p></label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static"><textarea id="bodytext"></textarea></p>
                                        
                                       
                                    </div>
                                </div>
                                  <div class="form-group row">
                                    <label class="col-sm-2 form-control-label"> </label>
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-lg mycustomwidth btn-success">Preview & Send</button> 
                                        
                                       
                                    </div>
                                   
                                </div>
                               
                            </form>  
                            
                            
                        </div>
       
       </div><!--.container-fluid-->
                
                
       
	</div><!--.page-content-->
  

       
    <?php
    include 'cm_footer.php';
		
      
      
      
       
   }else{
       $redirect = get_site_url();
    wp_redirect( $redirect );exit;
   
   }
?>