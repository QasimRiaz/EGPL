<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
    
    include 'cm_header.php';
    include 'cm_left_menu_bar.php';
    
    $test = 'custome_task_manager_data';
    $result = get_option($test);
    //  echo '<pre>';
    // print_r($result);
    $idx = 5;
    $labelArray = null;
    $file_upload_list.='<select class="form-control" id="file_upload" ><option value="">Select a Download Field</option>';

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
    
    
?>


<div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Bulk Download</h3>
                           
                        </div>
                    </div>
                </div>
            </header>

            <div class="box-typical box-typical-padding">
                <p>
                
            Here you can download all the user uploads for a selected task in a single ZIP file.   </p>

           
				

				<h5 class="m-t-lg with-border"></h5>

				
                                <div class="form-group row">
                                        <label class="col-sm-2 form-control-label">Download Files <strong>*</strong></label>
                                        <div class="col-sm-7">
                                           <?php echo $file_upload_list; ?>
                                        </div>
                                        <div class="col-sm-3">
                                            <a   class="btn btn-inline mycustomwidth btn-success" onclick="get_all_files()">Download</a>
                                         

                                        </div>
                                        <div id="hiddenform"></div>
                                    </div>

                 

				

				
			</div><!--.box-typical-->
		</div><!--.container-fluid-->
	</div><!--.page-content-->
            
        
		 <?php   
  
    include 'cm_footer.php';
		
   }else{
       
       $redirect = get_site_url();
       wp_redirect( $redirect );exit;
   
   }
   ?>