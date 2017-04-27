<?php
// Template Name: Bulk Edit Task 
if (current_user_can('administrator') || current_user_can('contentmanager')) {
    
    
   
    
    $user_reportsaved_list = get_option('ContenteManager_usersreport_settings');
    $get_email_template='AR_Contentmanager_Email_Template';
    $email_template_data = get_option($get_email_template);
    $content = "";
    $editor_id_bulk = 'bodytext';
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $formemail = $oldvalues['ContentManager']['formemail'];
    $base_url = get_site_url();
    ?>

    <?php include 'cm_header.php'; ?>
    <!--    user-reporting jQuery Querybuilder css-->
   


    <?php

include 'cm_left_menu_bar.php';

    ?>
   
     <input type="hidden" id='querybuilderfilter' value='{"condition":"AND","rules":<?php echo stripslashes($_POST['filterdata-hiddenfield']);?>,"valid":true}' > 
     <input type="hidden" id='showcolonreport' value="<?php echo htmlentities(stripslashes($_POST['selectedcolumnskeys-hiddenfield'])); ?>" >
     <input type="hidden" id='orderby' value="<?php echo $_POST['userbytype-hiddenfield'];?>" > 
     <input type="hidden" id='orderbycolname' value="<?php echo $_POST['userbycolname-hiddenfield'];?>" >
     
     <?php if(isset($_REQUEST)){ 
         
         ?>
            <form action="<?php echo $base_url;?>/user-report/?report=edit" method="post"  id="runreportresult"  >
                    <input type="hidden" name='usertimezone-hiddenfield' id='usertimezone-hiddenfield' value='<?php echo $_POST['usertimezone-hiddenfield'];?>' > 
                    <input type="hidden" name='filterdata-hiddenfield' id='filterdata-hiddenfield' value="<?php echo htmlentities(stripslashes($_POST['filterdata-hiddenfield']));?>" > 
                    <input type="hidden" name='selectedcolumnslebel-hiddenfield' id='selectedcolumnslebel-hiddenfield' value="<?php echo htmlentities(stripslashes($_POST['selectedcolumnslebel-hiddenfield']));?>" > 
                    <input type="hidden" name='selectedcolumnskeys-hiddenfield' id='selectedcolumnskeys-hiddenfield' value="<?php echo htmlentities(stripslashes($_POST['selectedcolumnskeys-hiddenfield']));?>" > 
                    <input type="hidden" name='userbytype-hiddenfield' id='userbytype-hiddenfield' value="<?php echo $_POST['userbytype-hiddenfield'];?>" > 
                    <input type="hidden" name='userbycolname-hiddenfield' id='userbycolname-hiddenfield' value="<?php echo $_POST['userbycolname-hiddenfield'];?>" > 
                    <input type="hidden" name='loadreportname-hiddenfield' id='loadreportname-hiddenfield' value="<?php echo $_POST['loadreportname-hiddenfield'];?>" > 
            </form>             
     <?php } ?>
     
     
     
     <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Users Report</h3>

                        </div>
                    </div>
                </div>
            </header>




            
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
                                <a class="nav-link" href="#tabs-1-tab-2" role="tab" onclick="get_bulk_email_address()" data-toggle="tab">
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
                                    <div class="faq-page-cat" title="Compose and send a bulk email message to the currently selected users">
                                        <div class="faq-page-cat-icon"><i class="bulkbtuton reporticon font-icon fa fa-users fa-2x"></i></div>

                                        <div class="faq-page-cat-txt">

                                            <div class="btn-group">
                                                <button disabled type="button" id="newsendbulkemailstatus" class="btn btn-inline dropdown-toggle btn-square-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Bulk Action
                                                    <span class="label label-pill label-danger" id="newbulkemailcounter">0</span>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" onclick="get_bulk_email_address()"><i class="fa fa-mail-forward"></i> Bulk Email</a>
                                                    <a class="dropdown-item" onclick="sendwelcomemsg()"><i class="fa fa-paper-plane"></i> Welcome Email</a>
                                                    <a class="dropdown-item" onclick="sync_bulk_users()"><i class="fa fa-refresh"></i> Sync to Floorplan</a>


                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>




                            </div><!--.row-->
                        </section><!--.faq-page-cats-->
                        <h5 class="m-t-lg with-border"></h5>


                        <table id="example" class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%">
                            
                            
                        </table>
                        <h5 class="m-t-lg with-border"></h5>
                        <div class="form-group row">

                            <div class="col-sm-3" style="text-align: left;">

                                <button   class="btn btn-lg mycustomwidth btn-success backtofilter">Edit Report</button>

                            </div>
                            <div class="col-sm-9" ></div>
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
                                                                    <a type="button" onclick="back_report()" class="btn btn-inline btn-primary"><i class="font-icon fa fa-chevron-left"></i> Back to report</a>
                                                                   
                                                                </div>
							</div>
						</div>
					</div><!--.row-->
				</section><!--.faq-page-cats-->

			
			</section><!--.faq-page-->
                        <div class="bulkemail_status"></div>
                        <div class="box-typical box-typical-padding sendbulkemailbox">
                            <form method="post" action="javascript:void(0);" onSubmit="bulkemail_preview()">
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
                                    <label class="col-sm-2 form-control-label">Message <strong>*</strong> <p style="margin-top: 53px" id="sponsor_meta_keys"><a class="btn btn-sm btn-primary mergefieldbutton" style="cursor: pointer;" onclick="keys_preview()">Insert Merge Fields</a></p></label>
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
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/newuser_report_result.js?v=2.19"></script>

    <?php
} else {
    $redirect = get_site_url();
    wp_redirect($redirect);
    exit;
}
?>