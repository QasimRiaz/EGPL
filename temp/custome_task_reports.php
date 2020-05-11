<?php
// Template Name: Bulk Edit Task 
if (current_user_can('administrator') || current_user_can('contentmanager')) {
    
   // $get_all_roles_array = 'wp_user_roles';
  //  $get_all_roles = get_option($get_all_roles_array);
    
    
  //  echo '<pre>';
  //  print_r($get_all_roles);exit;
    
    
    $user_reportsaved_list = get_option('ContenteManager_userstasksreport_settings');
    
   //    echo '<pre>';
  // print_r($user_reportsaved_list);exit;
    
    
    $get_email_template='AR_Contentmanager_Email_Template';
    $email_template_data = get_option($get_email_template);
    $content = "";
    $editor_id_bulk = 'bodytext';
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $formemail = $oldvalues['ContentManager']['formemail'];
    $base_url = get_site_url();
    
    $welcomeemail_template_info_key='AR_Contentmanager_Email_Template_welcome';
    $welcomeemail_template_info = get_option($welcomeemail_template_info_key);
    
   // $test = 'custome_task_manager_data';
   // $result_task_array_list = get_option($test);
    $args = array(
	'posts_per_page'   => -1,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'egpl_custome_tasks',
	'post_status'      => 'draft',
	
        );
    
    $result_task_array_list = get_posts( $args );
    $userreportcontent =   stripslashes($oldvalues['ContentManager']['userreportcontent']);
    
    include 'cm_header.php'; 
    include 'cm_left_menu_bar.php';
    
    if(isset($_REQUEST)){ 
        
        
        $querybuilderfilter = htmlentities(stripslashes($_POST['filterdata-hiddenfield']));
        $showcolonreport = htmlentities(stripslashes($_POST['selectedcolumnskeys-hiddenfield']));
        $orderby = $_POST['userbytype-hiddenfield'];
        $orderbycolname = $_POST['userbycolname-hiddenfield'];
        $selectedcolumnslebel_hiddenfield = htmlentities(stripslashes($_POST['selectedcolumnslebel-hiddenfield']));
        $selectedcolumnskeys_hiddenfield = htmlentities(stripslashes($_POST['selectedcolumnskeys-hiddenfield']));
        $userbytype_hiddenfield = $_POST['userbytype-hiddenfield'];
        $loadreportname_hiddenfield = $_POST['loadreportname-hiddenfield'];
   
        
    }
   if($_GET['report'] != 'run' ){ 
        
        $loadreportname = $_GET['report'];
       // echo $loadreportname;
        $get_report_detial = $user_reportsaved_list[$loadreportname];
        
       // echo '<pre>';
       // print_r($get_report_detial);exit;
        
        $queryfilter = json_decode($get_report_detial[0]);
        $querybuilderfilter = htmlentities(stripslashes(json_encode($queryfilter->rules)));
        $showcolonreport = htmlentities(stripslashes($get_report_detial[1]));
       
        $orderby = $get_report_detial[2];
        $orderbycolname = $get_report_detial[3];
        $selectedcolumnslebel_hiddenfield = htmlentities(stripslashes($get_report_detial[1]));
        $selectedcolumnskeys_hiddenfield = htmlentities(stripslashes($get_report_detial[1]));
        $userbytype_hiddenfield = $get_report_detial[3];
        $loadreportname_hiddenfield = $loadreportname;
   
        
    }

    ?>
     <input type="hidden" id='querybuilderfilter' value='{"condition":"AND","rules":<?php echo $querybuilderfilter;?>,"valid":true}' > 
     <input type="hidden" id='showcolonreport' value="<?php echo $showcolonreport; ?>" >
     <input type="hidden" id='orderby' value="<?php echo $orderby;?>" > 
     <input type="hidden" id='orderbycolname' value="<?php echo $orderbycolname ?>" >
     
     <?php if(isset($_REQUEST)){ 
         
         ?>
            <form action="<?php echo $base_url;?>/task-report-filters/?report=edit" method="post"  id="runreportresult"  >
                    <input type="hidden" name='usertimezone-hiddenfield' id='usertimezone-hiddenfield' value='<?php echo $_POST['usertimezone-hiddenfield'];?>' > 
                    <input type="hidden" name='filterdata-hiddenfield' id='filterdata-hiddenfield' value="<?php echo $querybuilderfilter ;?>" > 
                    <input type="hidden" name='selectedcolumnslebel-hiddenfield' id='selectedcolumnslebel-hiddenfield' value="<?php echo $selectedcolumnslebel_hiddenfield;?>" > 
                    <input type="hidden" name='selectedcolumnskeys-hiddenfield' id='selectedcolumnskeys-hiddenfield' value="<?php echo $selectedcolumnslebel_hiddenfield;//$selectedcolumnskeys_hiddenfield;?>" > 
                    <input type="hidden" name='userbytype-hiddenfield' id='userbytype-hiddenfield' value="<?php echo $userbytype_hiddenfield;?>" > 
                    <input type="hidden" name='userbycolname-hiddenfield' id='userbycolname-hiddenfield' value="<?php echo $orderbycolname;?>" > 
                    <input type="hidden" name='loadreportname-hiddenfield' id='loadreportname-hiddenfield' value="<?php echo $loadreportname_hiddenfield;?>" > 
            </form>             
     <?php } ?>
     <div id="hiddenform" style="display:none;"></div>
     <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Task Report</h3>

                        </div>
                    </div>
                </div>
            </header>
            
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
            <select id="hiddenfileuploadtasklist" style="display: none;">
                
                <?php   if(!empty($result_task_array_list)){foreach ($result_task_array_list as $taskindex => $taskValue) {
                                            
                                            $tasksID = $taskValue->ID;
                                            $value_key = get_post_meta( $tasksID, 'key', true);
                                            $value_type = get_post_meta( $tasksID, 'type', true);
                                            $value_label = get_post_meta( $tasksID, 'label', true);
                                            if($value_type == 'color'){
                                                 echo  '<option value="' . $value_key . '" selected="selected">'.$value_label.'</option>';
                                            }
                                          
                                         }
                }
                ?>
                                     
                
            </select>
            <input type="hidden" id='welcomecustomeemail' > 
            <section class="tabs-section">
                <div class="tabs-section-nav tabs-section-nav-icons">
                    <div class="tbl">
                        <ul class="nav" role="tablist">
                            <li class="nav-item" style="width:50%;">
                                <a class="nav-link active" href="#tabs-1-tab-1" role="tab" data-toggle="tab">
                                    <span class="nav-link-in">
                                        <i class="fa fa-list-alt"></i>
                                            Report
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#tabs-1-tab-2" role="tab" onclick="get_bulk_email_address_tasks()" data-toggle="tab">
                                    <span class="nav-link-in">
                                         <i class="fa fa-mail-forward"></i>
                                        
                                         Bulk Email
                                    </span>
                                </a>
                            </li>
                           

                        </ul>
                    </div>
                </div><!--.tabs-section-nav-->


                <div class="tab-content">
                  
                    <div role="tabpanel" class="tab-pane fade in active"  id="tabs-1-tab-1">
                        
                        
                        <div class="form-group row">

                            
                            <div class="col-sm-9" >
                                   <section class="box-typical faq-page">
                                <div class="faq-page-header-search">
                                    <div class="search">
                                        <div class="row">
                                            <div class="col-md-12">

                                                <fieldset class="form-group">

                                                    <select style="width:100%;height:38px;" class="form-control" onchange="customloaduserreport()" id="customloaduserreportss">
                                                        <option disabled selected hidden>Load a Report</option>
                                                      
                                                            <?php
                                                            foreach ($user_reportsaved_list as $key => $value) {

                                                                if(!empty($loadreportname_hiddenfield)){
                                                                    if($loadreportname_hiddenfield == $key){
                                                                         echo '<option value="' . $key . '" selected="selected">' . $key . '</option>';  
                                                                    }else{
                                                                         echo '<option value="' . $key . '">' . $key . '</option>';  
                                                                        
                                                                    }
                                                                    
                                                                }else{
                                                                   echo '<option value="' . $key . '">' . $key . '</option>';  
                                                                }
                                                               
                                                            }
                                                            ?>
                                                    </select>
                                                </fieldset>
                                            </div>


                                        </div>
                                    </div>
                                </div><!--.faq-page-header-search-->



                            </section><!--.faq-page-->
                             
                            </div>
                             <div class="col-sm-3" >

                                <button   style="margin-top: 9px !important;" class="btn btn-lg mycustomwidth btn-success backtofilter">Customize Report</button>

                            </div> 
                            
                        </div>
                        <section class="faq-page-cats" style="border-bottom:none;">
                            <div class="row">


                                <div class="col-md-4 filtersarraytooltip">
                                    <div class="faq-page-cat" title="No Filters Applied" data-toggle="tooltip" placement='bottom' style="cursor: pointer;">
                                        <div class="faq-page-cat-icon"><i style="color:#00a8ff !important" class="reporticon font-icon fa fa fa-filter fa-2x"></i></div>
                                        <div class="faq-page-cat-title" style="color:#00a8ff">
                                            Filters applied
                                        </div>
                                        <div class="faq-page-cat-txt" id="filteredusercount" >0</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="faq-page-cat" title="Number of users currently selected">
                                        <div class="faq-page-cat-icon"><i  class="selectedusericon reporticon font-icon fa fa-check-square fa-2x"></i></div>
                                        <div class="faq-page-cat-title selecteduserbox" >
                                            Selected
                                        </div>
                                        <div class="faq-page-cat-txt" id="ntableselectedstatscount"> 0</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="faq-page-cat" >
                                        <div class="faq-page-cat-icon"><i class="bulkbtuton reporticon font-icon fa fa-users fa-2x"></i></div>

                                        <div class="faq-page-cat-txt">

                                            <div class="btn-group">
                                                <button disabled type="button" id="newsendbulkemailstatus" class="btn btn-inline dropdown-toggle btn-square-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Bulk Action
                                                    <span class="label label-pill label-danger" id="newbulkemailcounter">0</span>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" onclick="get_bulk_email_address_tasks()"><i class="fa fa-mail-forward"></i> Bulk Email</a>
<!--                                                    <a class="dropdown-item" onclick="sendwelcomemsg()"><i class="fa fa-paper-plane"></i> Welcome Email</a>-->
                                                    <a class="dropdown-item" onclick="reportbulkdownload()"><i class="fa fa-download"></i> Bulk Download</a>
                                                    
                                                    

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div><!--.row-->
                        </section><!--.faq-page-cats-->
                        <h5 class="m-t-lg with-border"></h5>
                        <table id="customereports" class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%">
                        </table>
                        <h5 class="m-t-lg with-border"></h5>
                        <div class="form-group row">

                            <div class="col-sm-3" style="text-align: left;">

                                <button   class="btn btn-lg mycustomwidth btn-success backtofilter">Customize Report</button>

                            </div>
                            
                          
                            <div class="col-sm-9" ></div>
                        </div>
                        <div class="form-group row">
                               <div class="col-sm-12" >
                                  <?php echo $userreportcontent;?>
                               </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="tabs-1-tab-2">
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
									<button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
										
									
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
							<div class="faq-page-cat" >
								
								
								<div class="faq-page-cat-txt">
                                                                    <a type="button" onclick="back_report()" class="btn btn-inline btn-primary"><i class="font-icon fa fa-chevron-left"></i> Back to report</a>
                                                                   
                                                                </div>
							</div>
						</div>
					</div><!--.row-->
				</section><!--.faq-page-cats-->
			</section><!--.faq-page-->
                        <div class="bulkemail_status"></div>
                        <div class="box-typical box-typical-padding sendbulkemailbox">
                            <form method="post" action="javascript:void(0);" onSubmit="bulkemail_preview_tasks_report()">
                                 <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">From <strong>*</strong></label>
                                    <div class="col-sm-3">
                                        
                                       <div class="form-control-wrapper form-control-icon-left">
								<input type="text" id="fromname" class="form-control" placeholder="Name" required>
								<i class="font-icon fa fa-arrow-right"></i>
							</div>
                                    </div>
                                     <div class="col-sm-4">
                                         <label class="form-control-label"><?php if(!empty($formemail)){echo $formemail; }else{echo 'noreply@convospark.com';}?> </label>
                                       
                                    </div>
                                     <div class="col-sm-3">
                                       
                                         <label style="margin-top: 8px;font-weight: 500;"><i class="font-icon fa fa-check-square"></i>&nbsp;&nbsp;Selected Recipients:&nbsp;&nbsp;&nbsp;<span id="selectedstatscountforbulk">0</span> </label >
                                    </div>
                                </div>
                               
                                <div class="form-group row">
                                        <label class="col-sm-2 form-control-label">Reply-To <i style="cursor: pointer;" title = 'Please input an email address. When the recipient hits Reply on the message, this email address will be selected to receive their reply.' class="reporticon font-icon fa fa-question-circle"></i></label>
                                        <div class="col-sm-10">
                                            <div class="form-control-wrapper form-control-icon-left">    
                                                <input type="text"  class="form-control" id="replaytoemailadd" placeholder="Reply To Email address" value="<?php echo $sponsor_info[$loadreportname]['replaytoemailadd']; ?>" >
                                                <i class="fa fa-mail-reply"></i>	
                                            </div>
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
<!--                                <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">CC <i style="cursor: pointer;" title='Please input one or multiple email address (comma separated). All outgoing Welcome emails will be carbon copied to these address(es).'class="reporticon font-icon fa fa-question-circle"></i></label>
                                    <div class="col-sm-10">
                                            <div class="form-control-wrapper form-control-icon-left">
								<input type="text"  class="form-control" id="CC" placeholder="CC" >
								<i class="font-icon fa fa-copy"></i>
							</div>
                                        
                                    </div>
                                </div>-->
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
                                    <label class="col-sm-2 form-control-label">Message <strong>*</strong> <p style="margin-top: 53px" id="sponsor_meta_keys"><a class="btn btn-sm btn-primary mergefieldbutton" style="cursor: pointer;" onclick="welcome_available_merge_fields()">Insert Merge Fields</a></p></label>
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
                    </div>
                    
                </div><!--.tab-content-->
            </section><!--.tabs-section-->
        </div>
    </div>
    <?php
    include 'cm_footer.php';
    ?>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/custome_tasks_report.js?v=2.60"></script>

    <?php
}else{
    
    $redirect = get_site_url();
    wp_redirect($redirect);
    exit;
    
}
?>