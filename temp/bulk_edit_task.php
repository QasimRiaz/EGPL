<?php
// Template Name: Bulk Edit Task 
if (current_user_can('administrator') || current_user_can('contentmanager')) {
   

    $sponsor_id = get_current_user_id();
    $test = 'custome_task_manager_data';
    $result = get_option($test);
    //$result = json_decode(json_encode($result), true);
    //echo '<pre>';
    //print_r($result);exit;
    
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
   // $options_values='';
    // foreach ($result['profile_fields'] as $key=>$value){  
        $tasktitle_list = array();
        foreach ($result['profile_fields'] as $key=>$value){ 
    
            $tasktitle_list[] = htmlspecialchars($value['label']);
    
        }
     sort($tasktitle_list);
    ?> 
    

    <?php
    include 'cm_header.php';
    include 'cm_left_menu_bar.php';
    ?>
<!--   <div class="spoverlay overlay-hugeinc " id="loadingalert">
   <div class="sweet-alert showSweetAlert visible" data-custom-class="" data-has-cancel-button="false" data-has-confirm-button="true" data-allow-outside-click="false" data-has-done-function="false" data-animation="pop" data-timer="null" style="display:block;border: #b7b7b8 solid 1px;height: 329px;">
                
                <div class="sa-icon sa-info" style="display: block;"></div>
                <h2>Wait</h2>
            <p style="display: block;">Please wait ......</p>
           
   </div>			
</div>-->
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
                            <h3>Bulk Edit Tasks</h3>

                        </div>
                    </div>
                </div>
            </header>
           
            <div class="box-typical box-typical-padding">
                <p>
                    You can create new or edit all existing tasks here. Be sure to carefully select the user levels each task should be visible to.
                </p>
               
                <h5 class="m-t-lg with-border"></h5>
                <div class="form-group row">
                  
                    <div class="col-sm-6">
                   
                              
                       <select class="specialsearchfilter select2" id="customers_select_search" data-placeholder="Quick Search"  data-allow-clear="true" style="width:95%;border: #d6e2e8 solid 1px; height: 36px; border-radius: 3px;  padding-left: 10px;">
   
                           <option value=""></option>
                     <?php  foreach ($tasktitle_list as $key=>$value){ ?>
                        <option value="<?php echo $value;?>"><?php echo $value;?></option>
                        
                       
                     <?php  }?>
                        
                        
                       </select>
                  </div>
               
                    
                    <div class="col-sm-6">
                        <form method="post" action="javascript:void(0);" onSubmit="saveallbulktask()">
                        


                        <a  name="addsponsor"   style="margin-left: 2%;float: right;"class="addnewbulktask btn btn-lg mycustomwidth btn-success" value="Register">Add New Task</a>
                        <button  style="float: right;" type="submit" name="savealltask" class="btn btn-lg mycustomwidth btn-success" value="Register">Save All Changes</button>
                        
                    </div>
                </div>
                <div class="form-group row">
                    
                    
                            <select  class="addnewtaskdata-type" style="display: none;">
                                
                                
                                <?php foreach ($plug_in_settings['ContentManager']['taskmanager']['input_type'] as $val) { ?>
                                        <option value="<?php echo $val['type']; ?>" ><?php echo $val['lable']; ?></option>
                                <?php } ?>
                            </select>
                            <select class="addnewtaskdata-role" style="display: none;" >

                            <option value="all">All</option>
                            <?php 
                            
                            foreach ($all_roles as $key=>$name) {
                                if($key !='administrator'){
                                    echo '<option value="' . $key . '">' . $name . '</option>';
                                }
                            }
                            ?>
                            </select>
                            <select class="addnewtaskdata-userid" style="display: none;">
                                            <?php
                                            foreach ($get_all_ids as $user) {
                                               
                                                  echo '<option value="' . $user->ID . '">' . $user->user_email . '</option>';  
                                                }
                                                
                                                
                                            ?>
                            </select>
                       
                    
                     
                      
             
                    <table  class="bulkedittask  table-bordered compact dataTable no-footer cards"  width="100%">
                            <thead>
                                <tr class="text_th" >
                                    <th >Action</th>
                                    <th >Title</th>
                                    <th >Type</th>
                                    <th >Due Date</th>
                                    <th >User/Level</th>
                                    <th >Description</th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                foreach ($result['profile_fields'] as $key => $value) {

                                    $task_code = end(split('_', $key));
                                    ?>

                                    <tr>

                                        <td><div class="hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a">
                                                
                                                <i data-toggle="tooltip" class="hi-icon fa fa-clone saveeverything" id="<?php echo $task_code; ?>" onclick="clonebulk_task(this)" title="Create a clone" ></i>
                                                <i  data-toggle="tooltip" title="Advanced" name="<?php echo $task_code; ?>" onclick="bulktasksettings(this)" class="hi-icon fusion-li-icon fa fa-gears" ></i>
                                                <i  data-toggle="tooltip" title="Remove this task" onclick="removebulk_task(this)" class="hi-icon fusion-li-icon fa fa-times-circle" ></i>
                                                 
                                            </div> </td>
                                        <td><input type="text" style="margin-top: 10px;margin-bottom: 10px;" id="row-<?php echo $task_code; ?>-title" class="form-control" name="tasklabel" placeholder="Title" data-toggle="tooltip" title="Title" value="<?php echo htmlspecialchars($value['label']); ?>" required> 
                                            <span><input type="hidden" id="row-<?php echo $task_code; ?>-key"  value="<?php echo $key; ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $task_code; ?>-attribute"  value="<?php echo $value['taskattrs']; ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $task_code; ?>-taskMWC"  value="<?php  if(isset($value['taskMWC'])){ echo $value['taskMWC']; }?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $task_code; ?>-taskMWDDP"  value="<?php if(isset($value['taskMWDDP'])){ echo $value['taskMWDDP'];} ?>" ></span>
                                        
                                        </td>
                                        <td>
                                           <div class="topmarrginebulkedit">
                                                <select  style="width:100px !important;"class="select2 bulktasktypedrop tasktypesdata" id="bulktasktype_<?php echo $task_code; ?>" data-placeholder="Select Type" data-toggle="tooltip" title="Select Type" data-allow-clear="true">
                                                    <?php foreach ($plug_in_settings['ContentManager']['taskmanager']['input_type'] as $val) { ?>
                                                        <?php if ($val['type'] == $value['type']) { ?>
                                                            <option value="<?php echo $val['type']; ?>" selected="selected"><?php echo $val['lable']; ?></option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $val['type']; ?>" ><?php echo $val['lable']; ?></option>

                                                        <?php }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <?php if ($value['type'] == 'link') { ?>
                                                <div class="bulktasktype_<?php echo $task_code; ?>" style="display: block;margin-top:10px;margin-bottom: 10px;" >
                                                    <input type="text"  class="form-control" name="linkurl" id="row-<?php echo $task_code; ?>-linkurl" placeholder="Link URL" title="Link URL" data-toggle="tooltip" value="<?php echo $value['lin_url']; ?>" > 
                                                    <br>
                                                    <input type="text"  class="form-control" name="linkname" id="row-<?php echo $task_code; ?>-linkname" placeholder="Link Name"  title="Link Name" data-toggle="tooltip" value="<?php echo $value['linkname']; ?>" > 
                                                </div>
                                            <?php } else { ?>
                                                <div class="bulktasktype_<?php echo $task_code; ?>" style="display: none;margin-top:10px;margin-bottom: 10px;" >
                                                    <input type="text"  class="form-control" name="linkurl" id="row-<?php echo $task_code; ?>-linkurl" placeholder="Link URL" title="Link URL" data-toggle="tooltip" value="<?php echo $value['lin_url']; ?>" > 
                                                    <br>
                                                    <input type="text"  class="form-control" name="linkname" id="row-<?php echo $task_code; ?>-linkname" placeholder="Link Name"  title="Link Name" data-toggle="tooltip" value="<?php echo $value['linkname']; ?>" > 
                                                </div>
                                            <?php } ?>


                                            <?php
                                            if ($value['type'] == 'select-2') {
                                                $options_values = "";
                                                foreach ($value['options'] as $Okey => $Ovalue) {
                                                    $options_values .= $Ovalue['label'] . ',';
                                                }
                                                ?>
                                                <div class="dbulktasktype_<?php echo $task_code; ?>" style="display: block;margin-top:10px;margin-bottom: 10px;" >
                                                    <?php } else { ?>
                                                    <div class="dbulktasktype_<?php echo $task_code; ?>" style="display: none;margin-top:10px;margin-bottom: 10px;" > 
                                                <?php } ?>
                                                    <input type="text"  class="form-control" name="dropdownvalues" id="row-<?php echo $task_code; ?>-dropdownvlaues" data-toggle="tooltip" placeholder="Comma separated list of values" title="Comma separated list of values" value="<?php echo rtrim($options_values, ','); ?>" /> 
                                                </div> 
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" style="padding-left: 13px;margin-top: 10px;margin-bottom: 10px;" id="row-<?php echo $task_code; ?>-duedate" data-toggle="tooltip" class="form-control datepicker" name="datepicker"   placeholder="Due Date" title="Due Date"  value="<?php echo $value['attrs']; ?>">
                                        </td>
                                        <td> 
                                            <div class="addscrol topmarrginebulkedit">
                                                <select class="select2"  data-placeholder="Select Levels" title="Select Levels" id="row-<?php echo $task_code; ?>-levels" data-allow-clear="true" data-toggle="tooltip" multiple="multiple">
                                                    <?php
                                                    if (in_array('all', $value['roles'])) {

                                                        echo '<option value="all" selected="selected">All</option>';
                                                    } else {

                                                        echo '<option value="all">All</option>';
                                                    }

                                                    foreach ($all_roles as $key => $name) {
                                                        if($key !='administrator' && $key !='subscriber'){
                                                        if (in_array($key, $value['roles'])) {

                                                            echo '<option value="' . $key . '" selected="selected">' . $name . '</option>';
                                                        } else {

                                                            echo '<option value="' . $key . '">' . $name . '</option>';
                                                        }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <br>

                                                <select class="select2" data-placeholder="Select Users" title="Select Users" data-allow-clear="true" id="row-<?php echo $task_code; ?>-userid" data-toggle="tooltip" multiple="multiple" >
                                                    <?php
                                                    foreach ($get_all_ids as $user) {
                                                        if (in_array($user->ID, $value['usersids'])) {
                                                            echo '<option value="' . $user->ID . '" selected="selected">' . $user->user_email . '</option>';
                                                        } else {
                                                            echo '<option value="' . $user->ID . '">' . $user->user_email . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div> 
                                        </td>
                                        
                                        <td><br>
                                            <div class="addscrol">
                                                <div id="row-<?php echo $task_code; ?>-descrpition" class='edittaskdiscrpition_<?php echo $task_code; ?>'><?php echo $value['descrpition']; ?></div>

                                                <p ><i class="font-icon fa fa-edit" id='taskdiscrpition_<?php echo $task_code; ?>'title="Edit your task description" data-toggle="tooltip" style="cursor: pointer;color: #0082ff;"onclick="bulktask_descripiton(this)"></i>
        <?php if (!empty($value['descrpition'])) { ?>

                                                        <span id="desplaceholder-<?php echo $task_code; ?>" style="display:none;margin-left: 10px;color:gray;">Description</span>
        <?php } else { ?>

                                                        <span id="desplaceholder-<?php echo $task_code; ?>" style="margin-left: 10px;color:gray;">Description</span>
        <?php }; ?>
                                                </p>
                                            </div> 
                                        </td>
                                    </tr>  


    <?php } ?>        

                            </tbody>

                        </table>
                </div>
                <div class="form-group row">
                    
                    <div class="col-sm-10">
                        


                        <button  type="submit"  name="savealltask"   class="btn btn-lg mycustomwidth btn-success" value="Register">Save All Changes</button>
                        <a  name="addsponsor2"   class="addnewbulktask btn btn-lg mycustomwidth btn-success" value="Register">Add New Task</a>
                    </div>
                </div>
            </form>
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