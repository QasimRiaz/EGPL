<?php
// Silence is golden.
if (current_user_can('administrator') || current_user_can('contentmanager')) {
//          global $wp_roles;
//    echo '<pre>';
//    print_r($all_roles);exit;

    $welcomeemail_template_info_key = 'AR_Contentmanager_Email_Template_welcome';
    $welcomeemail_template_info = get_option($welcomeemail_template_info_key);
    $additional_fields_settings_key = 'EGPL_Settings_Additionalfield';
    $additional_fields = get_option($additional_fields_settings_key);




    include 'cm_header.php';
    include 'cm_left_menu_bar.php';
    ?>


    <div class="page-content">
        <div class="container-fluid">
            <header class="section-header" id="bulkimport">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Bulk Import Users</h3>

                        </div>
                    </div>
                </div>
            </header>
            <header class="section-header" id="bulkimportstatus" style="display:none;">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Bulk Import Status</h3>

                        </div>
                    </div>
                </div>
            </header>

            <div class="box-typical box-typical-padding" id="uploadimportfile">
                <p>

                    Please upload an Excel file (xlsx format) containing data of users you want to bulk import. Once you have uploaded the file, you will be required to map your user fields before executing the import. An example import file can be downloaded here: <a href="/wp-content/plugins/EGPL/import/sampledatafile.xlsx" target="_blank" >Sampledata.xlsx</a>.
                
                </p>

                <h5 class="m-t-lg with-border"></h5>



                <form method="post" action="javascript:void(0);" onSubmit="bulk_import_user()">




                   
                    
                    <div class="form-group row">
                        <label class="col-sm-3 form-control-label">Select Import File </label>
                        <div class="col-sm-9">
                            <input   type="file" class="form-control"  name="Sfile" id="Sfile"  required>
                        </div>

                    </div>

  
                    <h5 class="m-t-lg with-border"></h5>        
                    <div class="form-group row">
                        <label class="col-sm-3 form-control-label"></label>

                        <div class="col-sm-9">

                            <button type="submit"    id="uploadstatus" class="btn btn-inline mycustomwidth btn-success" value="Upload">Next</button>


                        </div>
                    </div>


                </form>
            </div>
            <div class="box-typical box-typical-padding" id="mapuserdatacol" style="display:none;" >

                <p>

                    Your selected file has <strong id="numberofrows">0</strong> rows. You can define your field mapping here. For each field in ExpoGenie, please select the appropriate column from your uploaded file.
                </p>
                
                 <input type="hidden" id="excelsheeturl" value=""/>
                <h5 class="m-t-lg with-border"></h5>
                <form method="post" action="javascript:void(0);" onSubmit="getimportmapping_data()">
                     <div class="form-group row">
                           
                             <h5 class="col-sm-3 m-t-lg with-border">ExpoGenie Fields</h5>
                             <h5 class="col-sm-9 m-t-lg with-border">Uploaded file columns</h5>
                            
                          

                            
                           
                    </div>
                    <div class="form-group row">
                            <label class="col-sm-3 form-control-label">Email <strong>*</strong></label>
                            <div class="col-sm-9">

                                <select class="mappingdropdown select2" name="email" id="getusersheetcollist" required>
                                    <option ></option>
                                    



                                </select>


                            </div>
                           
                        </div>

                    <div class="form-group row">
                            <label class="col-sm-3 form-control-label">First Name <strong>*</strong></label>
                            <div class="col-sm-9">

                                <select class="mappingdropdown select2" name="fname" id="getusersheetcollist" required>
                                    <option ></option>
                                   



                                </select>


                            </div>
                           
                        </div>
                    <div class="form-group row">
                            <label class="col-sm-3 form-control-label">Last Name <strong>*</strong></label>
                            <div class="col-sm-9">

                                <select class="mappingdropdown select2" name="lanme" id="getusersheetcollist" required>
                                    <option ></option>
                                   


                                </select>


                            </div>
                           
                        </div>
                    <div class="form-group row">
                            <label class="col-sm-3 form-control-label">User Level <strong>*</strong></label>
                            <div class="col-sm-9">

                                <select class="mappingdropdown select2" name="userlevel" id="getusersheetcollist" required>
                                    <option ></option>
                                   



                                </select>


                            </div>
                           
                        </div>
                    <div class="form-group row">
                            <label class="col-sm-3 form-control-label">Company Name <strong>*</strong></label>
                            <div class="col-sm-9">

                                <select class="mappingdropdown select2" name="companyname" id="getusersheetcollist" required>
                                    <option ></option>
                                    


                                </select>


                            </div>
                           
                        </div>
                   
                    <div class="row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9" id="bulkchecknewuserdiv">
                                <div class="checkbox">
                                    <input  type="checkbox" id="check-1" >
                                    Send welcome emails.<br/>

                                </div>


                            </div>
                        </div>
                        <div class="row" id="bulkshowlistofselectwelcomeemail" style="display:none;margin-bottom: 15px;">
                            <label class="col-sm-3 form-control-label">Select Welcome Email Template</label>
                            <div class="col-sm-9">

                                <select style="width:100%;height:38px;"class="form-control" id="selectedwelcomeemailtemp">
                                    <?php
                                    foreach ($welcomeemail_template_info as $key => $value) {

                                        $template_name = ucwords(str_replace('_', ' ', $key));
                                        if ($key == "welcome_email_template") {
                                            echo '<option value="' . $key . '" selected="selected">Default Welcome Email</option>';
                                        } else {
                                            echo '<option value="' . $key . '" >' . $template_name . '</option>';
                                        }
                                    }
                                    ?>

                                </select>



                            </div>
                        </div>
                    
                           
                        
                         <h5 class="m-t-lg with-border">Additional Information</h5>        
                         
                        
                        
                        <?php foreach ($additional_fields as $key => $value) { ?>
                         
                          <div class="form-group row">
                            <label class="col-sm-3 form-control-label"><?php echo $additional_fields[$key]['name']; ?> </label>
                            <div class="col-sm-9">

                                <select class="mappingdropdown select2" name="<?php echo $additional_fields[$key]['key'];?>" id="getusersheetcollist" >
                                    <option ></option>
                                   



                                </select>


                            </div>
                           
                        </div>
                          <?php } ?>
                        
 
                <h5 class="m-t-lg with-border"></h5>        
                
                <div class="form-group row">

                            <div class="col-sm-6" style="text-align: left;">
                                <a href="<?php echo  get_site_url();?>/bulk-import-user/" class="btn btn-danger btn-lg  resetuserfilters">Cancel</a>&nbsp;&nbsp;
                                <button type="submit" id="addnewsponsor_q" name="addsponsor"  class="btn btn-lg mycustomwidth btn-success" value="Register">Import</button>

                            </div>
                            <div class="col-sm-6"></div>
                        </div>
</form>       
            </div>
          
            
            
       
            
            
            <div class="box-typical box-typical-padding" id="importuserstatusdiv" style="display:none;">


                <p>

                    The results of the attempted import are below. If you see errors in the status column, please correct them and re-upload the import file.
                </p>

                <h5 class="m-t-lg with-border"></h5>
                <footer class="documentation-meta" style="margin-bottom: 30px;">
                    <p class="inline" id="createdusers">
                        <span style='font-weight: bold;'>Users created successfully:</span>

                    </p>
                    <p class="inline" id="userserrors">
                        <span style='font-weight: bold;'>Users could not be created:</span>

                    </p>

                </footer>

            </div>
        </div>
    </div>
    <?php
    include 'cm_footer.php';
} else {

    $redirect = get_site_url();
    wp_redirect($redirect);
    exit;
}
?>