<?php
// Template Name: Bulk Edit Task 
if (current_user_can('administrator') || current_user_can('contentmanager')) {
   

    $sponsor_id = get_current_user_id();
    $test = 'custome_task_manager_data';
    $result = get_option($test);
        global $wp_roles;
        global $current_user, $wpdb;
        $all_roles = $wp_roles->roles;
        $editable_roles = apply_filters('editable_roles', $all_roles);
        $role = $wpdb->prefix . 'capabilities';
        $current_user->role = array_keys($current_user->$role);
        
        $currentrolename = $editable_roles[$current_user->role[0]]['name'];
       
    
    $args = array(
	'posts_per_page'   => -1,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'egpl_custome_tasks',
	'post_status'      => 'draft',
	
    );
    $listOFtaskArray = get_posts( $args );
    
   
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
    if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }
     $all_roles = get_option($get_all_roles_array);
    //$all_roles = $wp_roles->roles;
    
   // $options_values='';
    // foreach ($result['profile_fields'] as $key=>$value){  
        $tasktitle_list = array();
        foreach($listOFtaskArray as $taskKey=>$tasksObject){
            $tasksID = $tasksObject->ID;
            $value_label = get_post_meta( $tasksID, 'label' , true);
            
            $tasktitle_list[] = $value_label;//htmlspecialchars($tasksObject->post_title);
    
        }
     sort($tasktitle_list);
    ?> 
    

    <?php
    include 'cm_header.php';
    include 'cm_left_menu_bar.php';
    ?>

<style>
	.select2-container--default .select2-search--dropdown {
    padding-left: 0px;
    padding-right: 0px;
	border-radius: 0px;
}
	.select2-search__field .newmultiselect{
	
	   
		margin-bottom: -20px;
}.select2-search--dropdown {
	
	    padding: 0px;
}
	.select2-container--open .select2-dropdown--below {
z-index:100000000000;
height: 50% !important;

}.select2-results__options {
	
	background: #FFFFFF !important;
	border: 1px solid #d7dee2 !important;
}

.jconfirm{z-index: 99 !important;}
	</style>
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
                            <h3>Manage Tasks</h3>

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
                     <?php  foreach ($tasktitle_list as $Qkey=>$Qvalue){ ?>
                        <option value="<?php echo $Qvalue;?>"><?php echo $Qvalue;?></option>
                        
                       
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
                                <option value="multiselect" >Multi-Select</option>
                                <option value="multivaluedtask" >Multi Value Task</option>
                            </select>
                            <select class="addnewtaskdata-role" style="display: none;" >

                            <option value="all">All</option>
                            <?php 
                            
                            foreach ($all_roles as $key=>$name) {
                                if($key !='administrator' ){
                                    echo '<option value="' . $key . '">' . $name['name'] . '</option>';
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
                       
                    
                     
                      
                     <span><input type="hidden" id="currentadmnirole"  value="<?php echo $currentrolename;?>" ></span>
                                            
                    <table  class="bulkedittask  table-bordered compact dataTable no-footer cards"  width="100%">
                            <thead>
                                <tr class="text_th" >
                                    <th >Action</th>
                                    <th >Title</th>
                                    <th >Type</th>
                                    <th >Due Date</th>
                                    <th >User/Level</th>
                                    <th >Specifications</th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                foreach ($listOFtaskArray as $taskskey => $tasksObject) {

                                    $task_code = $tasksObject->ID;
                                    $tasksID = $tasksObject->ID;
                                    
                                    $value = [];
                                    $value_value = get_post_meta( $tasksID, 'value' , false);
                                    $value_unique = get_post_meta( $tasksID, 'unique' , false);
                                    $value_class = get_post_meta( $tasksID, 'class' , false);
                                    $value_after = get_post_meta( $tasksID, 'after', false);
                                    $value_required = get_post_meta( $tasksID, 'required' , false);
                                    $value_allow_tags = get_post_meta( $tasksID, 'allow_tags' , false);
                                    $value_add_to_profile = get_post_meta( $tasksID, 'add_to_profile' , false);
                                    $value_allow_multi = get_post_meta( $tasksID, 'allow_multi', false);
                                    $value_label = get_post_meta( $tasksID, 'label' , false);
                                    $value_type = get_post_meta( $tasksID, 'type' , false);
                                    $value_lin_url = get_post_meta( $tasksID, 'link_url' , false);
                                    $value_linkname = get_post_meta( $tasksID, 'linkname', false);
                                    $value_attr = get_post_meta( $tasksID, 'duedate', false);
                                    
                                    
                                    $value_taskattrs = get_post_meta( $tasksID, 'taskattrs', false);
                                    $value_taskMWC = get_post_meta( $tasksID, 'taskMWC' , false);
                                    $value_taskMWDDP = get_post_meta( $tasksID, 'taskMWDDP' , false);
                                    $value_roles = get_post_meta( $tasksID, 'roles' , false);
                                    $value_usersids = get_post_meta( $tasksID, 'usersids' , false);
                                    $value_descrpition = get_post_meta( $tasksID, 'descrpition', false);
                                    $value_key = get_post_meta( $tasksID, 'key', false);
                                    
                                    $value_taskCode = get_post_meta( $tasksID, 'taskCode', true);
                                    $value_systaskstatus = get_post_meta( $tasksID, 'SystemTask', true);
                                    $value_emailnotification = get_post_meta( $tasksID, 'emailnotification', true);
                                    $value_emailnotificationaddress = get_post_meta( $tasksID, 'emailnotificationaddress', true);

                                    //my code Shehroze

                                    $value_position = get_post_meta($tasksID, 'TaskPosition', true);
                                   


                                    
                                    $value_multiselectstatus = get_post_meta( $tasksID, 'multiselectstatus', true);
                                    $value_multivaluetasklimit = get_post_meta( $tasksID, 'multivaluetasklimit', true);
                                    
                                    $value['SystemTask'] =$value_systaskstatus;
                                    $value['taskCode'] =$value_taskCode;
                                    
                                    $key  = $value_key[0];
                                    $value['value'] = $value_value[0];
                                    $value['unique'] = $value_unique[0];
                                    $value['class'] =$value_class[0];
                                    $value['after'] =$value_after[0];
                                    $value['required'] =$value_required[0];
                                    $value['allow_tags'] =$value_allow_tags[0];
                                    $value['add_to_profile'] =$value_add_to_profile[0];
                                    $value['allow_multi'] =$value_allow_multi[0];
                                    $value['label'] =$value_label[0];
                                    $value['type'] =$value_type[0];
                                    $value['lin_url'] =$value_lin_url[0];
                                    $value['linkname'] =$value_linkname[0];
                                    $value['attrs'] =$value_attr[0];
                                    $value['taskattrs'] =$value_taskattrs[0];
                                    $value['taskMWC'] =$value_taskMWC[0];
                                    $value['taskMWDDP'] =$value_taskMWDDP[0];
                                    $value['taskMWDDP'] =$value_taskMWDDP[0];
                                    $value['roles'] =$value_roles[0];
                                    $value['usersids'] =$value_usersids[0];
                                    $value['descrpition'] =$value_descrpition[0];
                                    $value['emailnotification'] =$value_emailnotification;
                                    $value['emailnotificationaddress'] =$value_emailnotificationaddress;
                                    
                                    $value['multivaluetasklimit'] =$value_multivaluetasklimit;
                                    $value['multiselectstatus'] =$value_multiselectstatus;
                                    

                                    //my code Shehroze
                                    
                                    $value['taskposition'] = (int)$value_position;


                                    
                                    if($value['type'] == "select-2" || $value['type'] == "multiselect"){
                                        
                                            $getarraysValue = get_post_meta( $tasksID, 'options', false);
                                            
                                            if(!empty($getarraysValue[0])){

                                                
                                                 $value['options'] =$getarraysValue[0];
                                                 
                                             }
                                   }
                                   
                                    if($value['SystemTask'] == 'checked'){
                                    
                                    ?>
                                   
                                <tr style="background-color:  #2196f31c !important;">
                                    
                                    <?php }else{?>
                                <tr>
                                    <?php }?>
                                        <td><div class="hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a">
                                                
                                                <i data-toggle="tooltip" class="hi-icon fa fa-clone saveeverything" id="<?php echo $task_code; ?>" onclick="clonebulk_task(this)" title="Create a clone" ></i>
                                                <i  data-toggle="tooltip" title="Advanced" name="<?php echo $task_code; ?>" onclick="bulktasksettings(this)" class="hi-icon fusion-li-icon fa fa-gears" ></i>
                                                <?php if($value['SystemTask'] != "checked") {?>
                                                <i  data-toggle="tooltip" title="Remove this task" name="<?php echo $task_code; ?>" onclick="removebulk_task(this)" class="hi-icon fusion-li-icon fa fa-times-circle" ></i>
                                                <?php }?>
												
												<?php if($value['SystemTask'] == "checked") {?>
												 <i  data-toggle="tooltip" title='This highlighted task is a "System Task". If you use this task on your Task Page, that means whatever value users submit to this task will automatically show up in the pop-up of their assigned booth(s) once assigned. By default they are not assigned to any Level, and you can configure most of the settings such as the due date and description, however they cannot be deleted. For more details on how these are used, please contact support@expo-genie.com'  name="" onclick="" class="hi-icon fusion-li-icon fa fa-question-circle" ></i>
                                                <?php }?>
                                            </div> </td>
                                        <td><input <?php if($value['SystemTask'] == "checked") {echo 'readonly="true" title="This is a system task. Changing its title is not allowed"';}else{echo 'title="Title"';} ?> type="text" style="margin-top: 10px;margin-bottom: 10px;" id="row-<?php echo $task_code; ?>-title" class="form-control" name="tasklabel" placeholder="Title" data-toggle="tooltip" title="Title" value="<?php echo $value['label']; ?>" required> 
                                            <span><input type="hidden" id="row-<?php echo $task_code; ?>-key"  value="<?php echo $key; ?>" ></span>
                                            <span><input <?php if($value['taskCode'] == "COL") { echo "value='accept=.png'";}else{echo "value='".$value['taskattrs']."'";}?>type="hidden" id="row-<?php echo $task_code; ?>-attribute"   ></span>
                                            <span><input type="hidden" id="row-<?php echo $task_code; ?>-taskMWC"  value="<?php  if(isset($value['taskMWC'])){ echo $value['taskMWC']; }?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $task_code; ?>-taskMWDDP"  value="<?php if(isset($value['taskMWDDP'])){ echo $value['taskMWDDP'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $task_code; ?>-taskCode"  value="<?php  if(isset($value['taskCode'])){ echo $value['taskCode']; }?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $task_code; ?>-SystemTask"  value="<?php if(isset($value['SystemTask'])){ echo $value['SystemTask'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $task_code; ?>-emailnotification"  value="<?php if(isset($value['emailnotification'])){ echo $value['emailnotification'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $task_code; ?>-emailnotificationaddress"  value="<?php if(isset($value['emailnotificationaddress'])){ echo $value['emailnotificationaddress'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $task_code; ?>-multivaluetasklimit"  value="<?php if(isset($value['multivaluetasklimit'])){ echo $value['multivaluetasklimit'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $task_code; ?>-multiselectstatus"  value="<?php if(isset($value['multiselectstatus'])){ echo $value['multiselectstatus'];} ?>" ></span>

                                            <!-- my -->

                                             <span><input type="hidden" id="row-<?php echo $task_code; ?>-taskposition"  value="<?php if(isset($value['taskposition'])){ echo $value['taskposition'];} ?>" ></span>
                                            
                                            
                                            
                                        </td>
                                        <td>
                                           <div class="topmarrginebulkedit">
                                               <select  <?php if($value['SystemTask'] == "checked") {echo 'disabled="true" title="This is a system task. Changing its type is not allowed"';}else{echo 'title="Task Type"';} ?>  style="width:100% !important;"class="form-control bulktasktypedrop tasktypesdata" id="bulktasktype_<?php echo $task_code; ?>" data-placeholder="Select Type" data-toggle="tooltip" data-allow-clear="true">
                                                    <?php foreach ($plug_in_settings['ContentManager']['taskmanager']['input_type'] as $val) { ?>
                                                        <?php if ($val['type'] == $value['type']) { ?>
                                                            <option value="<?php echo $val['type']; ?>" selected="selected"><?php echo $val['lable']; ?></option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $val['type']; ?>" ><?php echo $val['lable']; ?></option>

                                                        <?php }
                                                    }
                                                    ?>
                                                   
                                                            
                                                    <?php if ("multivaluedtask" == $value['type']) { ?>        
                                                            <option value="multivaluedtask" selected="selected">Multi Value Task</option>
                                                    <?php }else{?>
                                                            <option value="multivaluedtask" >Multi Value Task</option>
                                                    <?php } ?>        
                                                            
                                                            
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
                                                    <input type="text"  class="form-control" name="linkname" id="row-<?php echo $task_code; ?>-linkname" placeholder="Link Name"  title="Link Name" data-toggle="tooltip" value="<?php echo htmlspecialchars($value['linkname']); ?>" > 
                                                </div>
                                            <?php } ?>


                                            <?php
                                            if ($value['type'] == 'select-2' || $value['type'] == 'multiselect') {
                                                $options_values = "";
                                                foreach ($value['options'] as $Okey => $Ovalue) {
                                                    $options_values .= $Ovalue->label . ',';
                                                }
                                                ?>
                                                <div class="dbulktasktype_<?php echo $task_code; ?>" style="display: block;margin-top:10px;margin-bottom: 10px;" >
                                                    <?php } else { ?>
                                                    <div class="dbulktasktype_<?php echo $task_code; ?>" style="display: none;margin-top:10px;margin-bottom: 10px;" > 
                                                <?php } ?>
                                                    <input type="text"  class="form-control" name="dropdownvalues" id="row-<?php echo $task_code; ?>-dropdownvlaues" data-toggle="tooltip" placeholder="Comma separated list of values" title="Comma separated list of values" value="<?php echo htmlspecialchars(rtrim($options_values, ',')); ?>" /> 
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
                                                        if($key !='administrator' ){
                                                        if (in_array($key, $value['roles'])) {

                                                            echo '<option value="' . $key . '" selected="selected">' . $name['name'] . '</option>';
                                                        } else {

                                                            echo '<option value="' . $key . '">' . $name['name'] . '</option>';
                                                        }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <br>

                                                <select class="select2 js-example-events" data-placeholder="Select Users" title="Select Users" data-allow-clear="true" id="row-<?php echo $task_code; ?>-userid" data-toggle="tooltip" multiple="multiple" >
                                                    <?php
                                                    foreach ($get_all_ids as $user) {
                                                       // if(!empty($value['usersids'])){
                                                            if (in_array($user->ID, $value['usersids'])) {
                                                                echo '<option value="' . $user->ID . '" selected="selected">' . $user->user_email . '</option>';
                                                            }else{
                                                                 echo '<option value="' . $user->ID . '" >' . $user->user_email . '</option>';
                                                            }
                                                       // }
                                                    }
                                                    ?>
                                                </select>
                                            </div> 
                                        </td>
                                        
                                        <td><br>
                                            <div class="addscrol">
                                                <div id="row-<?php echo $task_code; ?>-descrpition" name="taskdiscrpition_<?php echo $task_code; ?>" oncontextmenu="return false;" class='edittaskdiscrpition_<?php echo $task_code; ?>' onclick="bulktask_descripiton(this)"><?php echo $value['descrpition']; ?></div>

                                                <p ><i class="font-icon fa fa-edit" name='taskdiscrpition_<?php echo $task_code; ?>'title="Edit your task specifications" data-toggle="tooltip" style="cursor: pointer;color: #0082ff;"onclick="bulktask_descripiton(this)"></i>
        <?php if (!empty($value['descrpition'])) { ?>

                                                        <span id="desplaceholder-<?php echo $task_code; ?>" style="display:none;margin-left: 10px;color:gray;">Specifications</span>
        <?php } else { ?>

                                                        <span id="desplaceholder-<?php echo $task_code; ?>" style="margin-left: 10px;color:gray;">Specifications</span>
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
        ?>
            
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/bulk_edit_task.js?v=3.68"></script>    
            
        <?php
        
    } else {
        $redirect = get_site_url();
        wp_redirect($redirect);
        exit;
    }
    ?>