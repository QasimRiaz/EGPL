 <?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
//          global $wp_roles;
   

//    echo '<pre>';
//    print_r($all_roles);exit;
   




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
                
                    Please Download <a href="/wp-content/plugins/EGPL/import/sampledatafile.xlsx" target="_blank" >Sampledata.xlsx</a>. Replace the dummy values with actual ones and upload your file below. Please note that Email, First Name, Last Name, User Level and Company Name are required for each user. Please do not change column names or their positions. The file must be in Excel (.xlsx) format.  
                </p>
                
                   <h5 class="m-t-lg with-border"></h5>
                   
                   
                  
                <form method="post" action="javascript:void(0);" onSubmit="bulk_import_user()">
            
                 
                   
                   
               
                     <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">Select Import File </label>
                                    <div class="col-sm-9">
                                          <input   type="file" class="form-control"  name="Sfile" id="Sfile"  required>
                                    </div>
                                   
                   </div>
                    
                    
                    <div class="row">
                        <div class="col-sm-3"></div>
                            <div class="col-sm-9">
                                <div class="checkbox">
                                    <input  type="checkbox" id="check-1" >
                                    Send welcome emails.<br/>
                                    
                                </div>
                               

                            </div>
                    </div>
                    
                      <h5 class="m-t-lg with-border"></h5>        
                    <div class="form-group row">
                                    <label class="col-sm-3 form-control-label"></label>
                                  
                                     <div class="col-sm-9">
                                           
                                             <button type="submit"    class="btn btn-inline mycustomwidth btn-success" value="Upload">Upload</button>
                                           
                                        
                                    </div>
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
		
   }else{
       
       $redirect = get_site_url();
       wp_redirect( $redirect );exit;
   
   }
   ?>