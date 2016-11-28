<?php
// Silence is golden.
if (current_user_can('administrator') || current_user_can('contentmanager')) {

  



    $test = 'custome_task_manager_data';
    $result = get_option($test);
    
    
   // echo '<pre>';
   // print_r($result);exit;
    
    
    $test_setting = 'ContenteManager_Settings';
    $plug_in_settings = get_option($test_setting);
    
    
  $fields = array( 'ID','user_email' );
    $args = array(
        'role__not_in' => array('administrator'),
        'fields' => $fields,
    );
    $get_all_ids = get_users($args);
    
   



    global $wp_roles;

    $all_roles = $wp_roles->get_names();
       include 'cm_header.php';
      include 'cm_left_menu_bar.php';
    
    ?>





        <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Edit User Task</h3>

                        </div>
                    </div>
                </div>
            </header>

            <div class="box-typical box-typical-padding">
                <p>
                You can edit your existing tasks here. Be sure to carefully select the user levels this task should be visible to. 
                </p>

                <h5 class="m-t-lg with-border"></h5>

                <form method="post" action="javascript:void(0);" onSubmit="get_edit_sponsor_task_date()">

                    <div class="form-group row">
                        <label class="col-sm-3 form-control-label">Select a Task <strong>*</strong></label>
                        <div class="col-sm-6">
                            <select id="updateTaskKey"  class="form-control" required>
                                            <option></option>
                                        <?php
                                            foreach ($result['profile_fields'] as $profile_field_name => $profile_field_settings) {
                                                if (strpos($profile_field_name, "task") !== false) {
                                                    if (strpos($profile_field_name, "status") == false && $profile_field_settings['type'] != "datetime") {
                                                        ?>
                                                        <option value="<?php echo $profile_field_name ?>"> <?php echo $profile_field_settings['label'] ?> </option>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                            <input style="display:none;" class="form-control" name="slectedKeyValue" id="slectedKeyValue" value="" type="hidden">
                            <input style="display:none;" class="form-control" name="slectedkeylabel" id="slectedkeylabel" value="" type="text" readonly>
                            

                        </div>
                        <div class="col-sm-3">
                            
                            
                            <button type="submit"  id="unique-buttons" class="btn btn-inline mycustomwidth btn-success"  value="">Proceed</button>
                            <a  onclick="removeTask()" id="remove-task-buttons" style="display:none;"class="btn  mycustomwidth btn-danger btn-inline">Remove</a>
                         
                           
                          
                        </div>
                        
                    </div>
                </form>
            </div>
            
            <div class="box-typical box-typical-padding" id="task_settings" style="display:none;">
                
                <form method="post" action="javascript:void(0);" onSubmit="sponsor_task_update()">
                    
                    <div class="form-group row">
                            <label class="col-sm-3 form-control-label">Task Input Field Type <strong>*</strong></label>
                            <div class="col-sm-9">
                             

                                    <select  class="form-control"  name="inputtype" id="inputtype"   onchange="checkinputtype(this)" required>
                                        <?php foreach ($plug_in_settings['ContentManager']['taskmanager']['input_type'] as $val) { ?>
                                            <option value="<?php echo $val['type']; ?>"><?php echo $val['lable']; ?></option>
                                        <?php } ?>
                                    </select>

             


                            </div>


                        </div>
                     <div class="form-group row" id="dropdownonly" style="display: none;">
                        <label class="col-sm-3 form-control-label">Comma separated list of values</label>
                        <div class="col-sm-9">
                            <div class="form-control-wrapper form-control-icon-left">
                               
                                 <input  class="form-control"  name="dropdownval" id="dropdownval" value="" />
                               <i class="font-icon fa fa-th-list"></i>
                            </div>
                            

                        </div>
                        
                        
                    </div>
                      <div class="form-group row" id="linkurl" style="display: none;">
                        <label class="col-sm-3 form-control-label">Link URL</label>
                        <div class="col-sm-9">
                            <div class="form-control-wrapper form-control-icon-left">
                               
                                 <input placeholder="URL" class="form-control"  type="text"  name="linkurlval" id="linkurlval" value="" >
                                <i class="font-icon fa fa-external-link"></i>
                               
                            </div>
                            

                        </div>
                        
                        
                    </div>
                    <div class="form-group row" id="linkname" style="display: none;">
                        <label class="col-sm-3 form-control-label">Link Name</label>
                        <div class="col-sm-9">
                            <div class="form-control-wrapper form-control-icon-left">
                               
                                <input type="text" class="form-control"  placeholder="Name" name="linknameval" id="linknameval" value="" >
                                 <i class="font-icon fa fa-edit"></i>
                               
                            </div>
                            

                        </div>
                        
                        
                    </div>
                    <div class="form-group row" >
                        <label class="col-sm-3 form-control-label">Task Due Date <strong>*</strong></label>
                        <div class="col-sm-9">
                            <div class="form-control-wrapper form-control-icon-left">
                               
                                <input type="text" class="form-control"  name="datepicker" id="datepicker"  required > 
                                   <i class="font-icon fa fa-calendar"></i>
                               
                            </div>
                            

                        </div>
                        
                        
                    </div>
                      <div class="form-group row" >
                        <label class="col-sm-3 form-control-label">Task Title <strong>*</strong></label>
                        <div class="col-sm-9">
                            <div class="form-control-wrapper form-control-icon-left">
                               
                                <input type="text" class="form-control"  name="tasklabel" id="tasklabel"  required > 
                                 <i class="font-icon fa fa-edit"></i>
                               
                            </div>
                            

                        </div>
                        
                        
                    </div>
                   
                     <div class="form-group row" >
                        <label class="col-sm-3 form-control-label">Additional Attributes </label>
                        <div class="col-sm-9">
                           
                                 <div class="form-control-wrapper form-control-icon-left">
                               <input name="attribure" class="form-control" id="attribure" ><p>Use this to define constraints such as character limit, allowed file types, etc.</p><p>Example: maxlength=5 &nbsp;&nbsp;accept=.png,.jpg</p>
                               <i class="font-icon fa fa-gears"></i> 
                               </div> 
                           
                            

                        </div>
                        
                        
                    </div>
                     <div class="form-group row" >
                        <label class="col-sm-3 form-control-label">User Level <strong>*</strong></label>
                        <div class="col-sm-9">
                            <div class="form-control-wrapper form-control-icon-left">
                               
                              <select name="role" id="Srole" required class="select2" multiple="multiple">
                                            <option value="all">All</option>
                                            <?php
                                            foreach ($all_roles as $key=>$name) {
                                                echo '<option value="' . $key . '">' . $name . '</option>';
                                            }
                                            ?>

                                        </select>
                               
                               
                            </div>
                            

                        </div>
                        
                        
                    </div>
<!--                      <div class="form-group row" >
                        <label class="col-sm-3 form-control-label">Assign Users <strong>*</strong></label>
                        <div class="col-sm-9">
                            <div class="form-control-wrapper form-control-icon-left">
                               
                              <select name="userids" id="userids"  class="select2" multiple="multiple">
                                            <option value="all">All</option>
                                            <?php
                                            foreach ($get_all_ids as $user) {
                                                echo '<option value="' . $user->ID . '">' . $user->user_email . '</option>';
                                            }
                                            ?>

                                        </select>
                               
                               
                            </div>
                            

                        </div>
                        
                        
                    </div>-->
                      <div class="form-group row" >
                        <label class="col-sm-3 form-control-label">Task Description <strong>*</strong></label>
                        <div class="col-sm-9">
                            <div class="form-control-wrapper form-control-icon-left">
                               
                                <textarea   name="taskdescrpition" id="taskdescrpition"  required > </textarea>
                               
                               
                            </div>
                            

                        </div>
                        
                        
                    </div>
                     <div class="form-group row">
                                    <label class="col-sm-3 form-control-label"></label>
                                    <div class="col-sm-6">
                                             <button type="submit"  name="updatesponsor"  class="btn btn-lg mycustomwidth btn-success" value="Update">Update</button>
                                             <a class="btn mycustomwidth btn-lg btn-primary" href="/dashboard">Cancel</a>
                                        
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