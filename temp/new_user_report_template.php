<?php
// Template Name: Bulk Edit Task 
if (current_user_can('administrator') || current_user_can('contentmanager')) {
    
    $user_reportsaved_list = get_option('ContenteManager_usersreport_settings');
    $get_email_template='AR_Contentmanager_Email_Template';
    $email_template_data = get_option($get_email_template);
    $content = "";
    $editor_id_bulk = 'bodytext';
    $oldvalues = get_option('ContenteManager_Settings');
    $formemail = $oldvalues['ContentManager']['formemail'];
    $base_url = get_site_url();
    
    ?>
    <?php include 'cm_header.php'; ?>
    <!--    user-reporting jQuery Querybuilder css-->
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/bootstrap-select.min.css?v=2.19">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/awesome-bootstrap-checkbox.css?v=2.19">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/bootstrap-slider.min.css?v=2.19">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/selectize.bootstrap3.css?v=2.19">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/query-builder.default.css?v=2.19">


    <?php

    include 'cm_left_menu_bar.php';

    ?>
   
    <div class="blockUI" style="display:none;"></div>
<div class="blockUI blockOverlay" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgba(142, 159, 167, 0.8); opacity: 1; cursor: wait; position: absolute;"></div>
<div class="blockUI block-msg-default blockElement" style="z-index: 1011; position: absolute; padding: 0px; margin: 0px;  top: 300px;  text-align: center; color: rgb(0, 0, 0); border: 3px solid rgb(170, 170, 170); background-color: rgb(255, 255, 255); cursor: wait; height: 200px;left: 50%;">
        <div class="blockui-default-message">
            <i class="fa fa-circle-o-notch fa-spin"></i><h6>Please Wait.</h6></div></div>
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
           <?php if(isset($_REQUEST)){ 
                
               //  echo '<pre>';
                // print_r($_REQUEST);exit;
                
                ?>
                <input type="hidden" id='querybuilderfilter' value='{"condition":"AND","rules":<?php echo stripslashes($_POST['filterdata-hiddenfield']);?>,"valid":true}' > 
                <input type="hidden" id='showcolonreport' value="<?php echo htmlentities(stripslashes($_POST['selectedcolumnskeys-hiddenfield'])); ?>" > 
                <input type="hidden" id='orderby' value="<?php echo $_POST['userbytype-hiddenfield'];?>" > 
                <input type="hidden" id='orderbycolname' value="<?php echo $_POST['userbycolname-hiddenfield'];?>" > 
                <input type="hidden" id='loadreportname' value="<?php echo $_POST['loadreportname-hiddenfield'];?>" > 
                
                
            <?php } ?>
                <form action="<?php echo $base_url;?>/user-report-result/?report=run" method="post"  id="runreportresult"  >
                    
                    <input type="hidden" id='usertimezone-hiddenfield' name='usertimezone-hiddenfield' value='' > 
                    <input type="hidden" id='filterdata-hiddenfield' name='filterdata-hiddenfield'value="" > 
                    <input type="hidden" id='selectedcolumnslebel-hiddenfield' name='selectedcolumnslebel-hiddenfield' value="" > 
                    <input type="hidden" id='selectedcolumnskeys-hiddenfield' name='selectedcolumnskeys-hiddenfield' value="" > 
                    <input type="hidden" id='userbytype-hiddenfield' name='userbytype-hiddenfield' value="" > 
                    <input type="hidden" id='userbycolname-hiddenfield'  name='userbycolname-hiddenfield' value="" > 
                    <input type="hidden" id='loadreportname-hiddenfield' name='loadreportname-hiddenfield' value="" > 
                   
                
                    
                </form>
                <div class="box-typical box-typical-padding"  >
              
                    <div role="tabpanel" class="tab-pane fade in active" id="tabs-1-tab-1">
                        <br>
                        <section class="box-typical faq-page">
                            <div class="faq-page-header-search">
                                <div class="search">
                                    <div class="row">
                                        <div class="col-md-6">

                                            <fieldset class="form-group">

                                                <select style="width:100%;height:38px;"class="form-control" onchange="loaduserreport()" id="loaduserreport">
                                                    <option disabled selected hidden>Load a Report</option>
                                                   
                                                    <option value="defult">Save Current Template As</option>
                                                    <optgroup label="Saved Templates" id="loaduserreportlist">

    <?php
    foreach ($user_reportsaved_list as $key => $value) {


        echo '<option value="' . $key . '">' . $key . '</option>';
    }
    ?>
                                                    </optgroup>
                                                </select>
                                            </fieldset>
                                        </div>

                                        <div class="col-md-6">

                                            <form method="post" action="javascript:void(0);" onSubmit="user_report_savefilters()">    	
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input style="height: 38px;" placeholder="Report Name" id="userreportname" type="text" class="form-control" required>
                                                        <div class="input-group-btn">
                                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Action
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <button type="submit"  name="saveuserreport"  class="dropdown-item"  ><i class="font-icon fa fa-save" aria-hidden="true"></i> Save</button>
                                                                <a class="dropdown-item" onclick="removeeuserreport()"><i class="font-icon fa fa-remove" aria-hidden="true"></i>Delete</a>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>		


                                        </div>
                                    </div>
                                </div>
                            </div><!--.faq-page-header-search-->



                        </section><!--.faq-page-->
                        <h5 class="m-t-lg with-border">Filters</h5>
                        <div id="builder"></div>
                        <h5 class="m-t-lg with-border">Show Columns</h5>
                        <div class="form-group row">

                            <div class="col-sm-12" >
                                <select class="select2"  data-placeholder="Select Columns" title="Select Columns" id="userreportcolumns" data-allow-clear="true" data-toggle="tooltip" multiple="multiple">
                                    <optgroup label="User Details" id="usercontactfields"></optgroup>
                                    <optgroup label="Tasks" id="usertaskfields"></optgroup>
                                </select>

                            </div>
                        </div>
                        <h5 class="m-t-lg with-border">Sort by</h5>
                        <div class="form-group row">

                            <div class="col-sm-6" >
                                <select class="select2"  data-placeholder="Select Columns"  id="userbycolumnsname" data-allow-clear="true" >
                                    <optgroup label="User Details" id="usercontactfieldssortby"></optgroup>
                                    <optgroup label="Tasks" id="usertaskfieldssortby"></optgroup>
                                </select>

                            </div>
                            <div class="col-sm-6" >
                                <select class="select2"  id="sortingtype" data-allow-clear="true">

                                    <option value='asc'>Ascending</option>
                                    <option value='desc' selected="selected">Descending</option>

                                </select>

                            </div>
                        </div>
                        <h5 class="m-t-lg with-border"></h5>
                        <div class="form-group row">

                            <div class="col-sm-6" style="text-align: left;">
                                <button class="btn btn-danger btn-lg  resetuserfilters">Reset</button>&nbsp;&nbsp;
                                <button class="btn btn-lg mycustomwidth btn-success drawdatatable">Run Report</button>

                            </div>
                            <div class="col-sm-6"></div>
                        </div>
                        
                          
                        

                    </div>
                   
                </div>
               






        </div>
    </div>





    <?php
    include 'cm_footer.php';
    ?>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/bootstrap-select.js?v=2.19"></script>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/bootbox.js?v=2.19"></script>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/bootstrap-slider.min.js?v=2.19"></script>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/selectize.min.js?v=2.19"></script>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/jQuery.extendext.min.js?v=2.19"></script>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/sql-parser.js?v=2.19"></script>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/doT.js?v=2.19"></script>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/interact.js?v=2.19"></script>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/query-builder.js?v=2.19"></script>


    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/newuser_report.js?v=2.30"></script>

    <?php
} else {
    $redirect = get_site_url();
    wp_redirect($redirect);
    exit;
}
?>