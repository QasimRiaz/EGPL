<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
       
        include 'cm_header.php';
    include 'cm_left_menu_bar.php';
       $site_url  = get_site_url();
      
                ?>



   <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Create Resources</h3>
                           
                        </div>
                    </div>
                </div>
            </header>

            <div class="box-typical box-typical-padding">
                <p>
                
           Create Resources for your users to download here. These are visible to all user levels. Examples are Insurance Certificates, Exhibitor Kits, or templates/guidelines for submitting tasks. 
                </p>
                
                   <h5 class="m-t-lg with-border"></h5>
                <form method="post" action="javascript:void(0);" onSubmit="create_new_resource()">
             
                 <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Resource Title <strong>*</strong></label>
                                    <div class="col-sm-10">
                                         <div class="form-control-wrapper form-control-icon-left">    
							<input type="text"  class="form-control" id="Stitle" placeholder="Resource Title"  required>
							 <i class="font-icon fa fa-edit"></i>
                                       </div>
                                    </div>
                   </div>
                     <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Resource File <strong>*</strong></label>
                                    <div class="col-sm-10">
                                          <input  onclick="show_button(this)" type="file" class="form-control"  name="Sfile" id="Sfile"  required>
                                    </div>
                   </div>
                      <h5 class="m-t-lg with-border"></h5>
                 
                      <div class="form-group row">

                            <div class="col-sm-6" style="text-align: left;">
                                <a href="<?php echo $site_url;?>/all-resources/" class="btn btn-danger btn-lg">Back</a>&nbsp;&nbsp;
                                <button type="submit" class="btn btn-lg mycustomwidth btn-success">Create</button>

                            </div>
                            <div class="col-sm-6"></div>
                        </div>
              </form>
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