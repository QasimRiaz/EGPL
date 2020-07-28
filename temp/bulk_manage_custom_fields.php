<?php
// Template Name: Bulk Edit Task 
if (current_user_can('administrator') || current_user_can('contentmanager')) {
   

    $sponsor_id = get_current_user_id();
    
    
    require_once plugin_dir_path( __DIR__ ) . 'includes/egpl-custome-functions.php';
    $GetAllcustomefields = new EGPLCustomeFunctions();
    $listOFcustomfieldsArray = $GetAllcustomefields->getAllcustomefields();
    $showSystemInternalFields = "enabled";
    //asort($listOFcustomfieldsArray);            
   
    // echo '<pre>';
    // print_r($listOFcustomfieldsArray);exit;
  
    
    
    $test_setting = 'ContenteManager_Settings';
    $plug_in_settings = get_option($test_setting);
        global $wp_roles;
        global $current_user, $wpdb;
        $all_roles = $wp_roles->roles;
        $editable_roles = apply_filters('editable_roles', $all_roles);
        $role = $wpdb->prefix . 'capabilities';
        $current_user->role = array_keys($current_user->$role);
        $currentrolename = $editable_roles[$current_user->role[0]]['name'];
        $currentrolename = $editable_roles[$current_user->role[0]]['name'];
    
    $tasktitle_list = array();
    $indexvalue = 0;
        foreach($listOFcustomfieldsArray as $fieldsKey=>$fieldsObject){
            if($fieldsObject['SystemfieldInternal'] !="checked"){
                $fieldstitle_list[$indexvalue]['value'] = $fieldsObject['fieldIndex'];
                $fieldstitle_list[$indexvalue]['label'] = htmlspecialchars($fieldsObject['fieldName']);
                $indexvalue++;
            }
        }
       
     
     sort($fieldstitle_list);
    ?> 
    

    <?php
    include 'cm_header.php';
    include 'cm_left_menu_bar.php';
    
    include plugin_dir_path( __DIR__ ) .'defult-content.php';
    
    
    
    
    ?>

<style>
    .bulkeditfield tbody tr td:first-child{display: none;}
    .hi-icon-wrap{cursor: move;}
    .dataTables_empty{display: block !important;}
    
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
                            <h3>User Fields</h3>

                        </div>
                    </div>
                </div>
            </header>
           
            <div class="box-typical box-typical-padding">
                
                <p>This section is where you can create additional custom user fields to manage your user data. </p>
                <p>If you are using the exhibitor application form, this is where you can add additional fields to capture information on potential exhibitors and later report on.</p> 
<p>You can also create custom internal fields on your users. For example, if you capture meal preferences for your exhibitors in another system but want to also expose it in ExpoGenie, you can create a custom field to add that data and use in reporting.</p>
<p>Note that the sections in BLUE are standard and required ExpoGenie fields that you cannot remove.
                </p>
               
                <h5 class="m-t-lg with-border"></h5>
                <div class="form-group row">
                  
                    <div class="col-sm-6">
                       <span><input type="hidden" id="currentadmnirole"  value="<?php echo $currentrolename;?>" ></span>
                       <select  class="addnewtaskdata-type" style="display: none;">
                                
                                <option></option>
                                <?php foreach ($field_input_type as $val) { ?>
                                        
                                        <option value="<?php echo $val['type']; ?>" ><?php echo $val['lable']; ?></option>
                                <?php } ?>
                        </select>
                       <select class="specialsearchfilter select2" id="customers_select_search" data-placeholder="Quick Search"  data-allow-clear="true" style="width:95%;border: #d6e2e8 solid 1px; height: 36px; border-radius: 3px;  padding-left: 10px;">
   
                           <option value=""></option>
                     <?php  foreach ($fieldstitle_list as $Qkey=>$Qvalue){ ?>
                        <option value="<?php echo $Qvalue['value'];?>"><?php echo $Qvalue['label'];?></option>
                        
                       
                     <?php  }?>
                        
                        
                       </select>
                  </div>
               
                    
                    <div class="col-sm-6">
                        <form method="post" action="javascript:void(0);" onSubmit="saveallbulkcustomefields()">
                        


                        <a  name="addsponsor"   style="margin-left: 2%;float: right;"class="addnewbulktask btn btn-lg mycustomwidth btn-success" value="Register">Add New Field</a>
                        <button  style="float: right;" type="submit" name="savealltask" class="btn btn-lg mycustomwidth btn-success" value="Register">Save All Changes</button>
                        
                    </div>
                </div>
                <div class="form-group row">
                    
                    
                      
                    
                                            
                    <table  class="bulkeditfield  table-bordered compact dataTable no-footer cardsfields"  width="100%">
                            <thead>
                                <tr class="text_th" >
                                    <th>#</th>
                                    <th >Action</th>
                                    <th >Field Name</th>
                                    <th >Type</th>
                                    <th >Display</th>
                                    <th >Description</th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                
                                
                               foreach ($listOFcustomfieldsArray as $fieldskey => $value) {
                                    
                                    
                                    
                                    
                                if($value['SystemfieldInternal'] == 'checked' && $showSystemInternalFields == "enabled" ) {?>
                                
                                     <tr style="background-color:  #da9292 !important;" class="internalfields">
                                     
                                        <td id="row-<?php echo $value['fieldID']; ?>-fieldIndex" ><?php echo $value['fieldIndex'];?></td>
                                        <td><div class="hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a">
                                               
                                                
                                                <i data-toggle="tooltip" class="hi-icon fa fa-clone saveeverything" id="<?php echo $value['fieldID']; ?>" onclick="clonebulk_fields(this)" title="Create a clone" ></i>
                                                <i  data-toggle="tooltip" title="Field Settings" name="<?php echo $value['fieldID']; ?>" onclick="bulkfieldsettings(this)" class="hi-icon fusion-li-icon fa fa-gears" ></i>
                                                <?php if($value['SystemfieldInternal'] != "checked") {?>
                                                <i  data-toggle="tooltip" title="Remove this field" name="<?php echo $value['fieldID']; ?>" onclick="removebulk_fields(this)" class="hi-icon fusion-li-icon fa fa-times-circle" ></i>
                                                <?php }?>
												
												<?php if($value['SystemfieldInternal'] == "checked") {?>
												 <i  data-toggle="tooltip" title='This highlighted field is a "Internal field".'  name="" onclick="" class="hi-icon fusion-li-icon fa fa-question-circle" ></i>
                                                <?php }?>
                                            </div> </td>
                                        <td><input <?php if($value['fieldsystemtask'] == "checked") {echo 'readonly="true" title="This is a system field. Changing its title is not allowed"';}else{echo 'title="Field Name"';} ?> type="text" style="margin-top: 10px;margin-bottom: 10px;" id="row-<?php echo $value['fieldID']; ?>-title" class="form-control" name="tasklabel" placeholder="Title" data-toggle="tooltip" title="Title" value="<?php echo $value['fieldName']; ?>" required> 
                                            
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-fielduniquekey"  value="<?php  if(isset($value['fielduniquekey'])){ echo $value['fielduniquekey']; }?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-fieldCode"  value="<?php  if(isset($value['fieldID'])){ echo $value['fieldID']; }?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-Systemfield"  value="<?php if(isset($value['fieldsystemtask'])){ echo $value['fieldsystemtask'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-fieldtooltip"  value="<?php if(isset($value['fieldtooltiptext'])){ echo $value['fieldtooltiptext'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-fieldstatusrequried"  value="<?php if(isset($value['fieldrequriedstatus'])){ echo $value['fieldrequriedstatus'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-fieldstatusshowonregform"  value="<?php if(isset($value['displayonapplicationform'])){ echo $value['displayonapplicationform'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-fieldplaceholder"  value="<?php if(isset($value['fieldplaceholder'])){ echo $value['fieldplaceholder'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-attribute"  value="<?php if(isset($value['attribute'])){ echo $value['attribute'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-SystemfieldInternal"  value="<?php if(isset($value['SystemfieldInternal'])){ echo $value['SystemfieldInternal'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-multiselect"  value="<?php if(isset($value['multiselect'])){ echo $value['multiselect'];} ?>" ></span>
                                            
                                               
                                                
                                                <?php if ($value['fieldType'] == 'dropdown') {
                                                $options_values = "";
                                                foreach ($value['options'] as $Okey => $Ovalue) {
                                                    $options_values .= $Ovalue->label . ',';
                                                }
                                                $options_values = substr_replace($options_values ,"", -1);
                                                }else{$options_values = "";}
                                                ?>
                                           
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-linkurl"  value="<?php if(isset($value['fieldTypeLinkurl'])){ echo $value['fieldTypeLinkurl'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-linkname"  value="<?php if(isset($value['fieldTypeLinkname'])){ echo $value['fieldTypeLinkname'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-dropdownvlaues"  value="<?php if(isset($options_values)){ echo $options_values;} ?>" ></span>
                                           
                                            
                                            
                                        </td>
                                        <td>
                                           <div class="topmarrginebulkedit">
                                               <select  <?php if($value['fieldsystemtask'] == "checked") {echo 'disabled="true" title="This is a system field. Changing its type is not allowed"';}else{echo 'title="Field Type"';} ?>  style="width:100px !important;"class="select2 bulktasktypedrop tasktypesdata" id="bulktasktype_<?php echo $value['fieldID']; ?>" data-placeholder="Field Type" data-toggle="tooltip" data-allow-clear="true">
                                                    <?php foreach ($field_input_type as $val) { ?>
                                                        <?php if ($val['type'] == $value['fieldType']) { ?>
                                                            <option value="<?php echo $val['type']; ?>" selected="selected"><?php echo $val['lable']; ?></option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $val['type']; ?>" ><?php echo $val['lable']; ?></option>

                                                        <?php }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            

                                        </td>
                                        
                                        <td><p>Display on Application Form 
                                            
                                            <?php if($value['displayonapplicationform'] == "checked"){
                                            
                                            echo '<input type="checkbox" id="row-'.$value["fieldID"].'-fieldstatusshowonregform" class="form-control" checked="true">';
                                            
                                            }else{
                                                
                                             echo '<input id="row-'.$value["fieldID"].'-fieldstatusshowonregform" type="checkbox" class="form-control" >';
                                            }?>
                                            </p></td>
                                        
                                        <td>
                                            <p style="margin-top: 5px;"><i class="font-icon fa fa-edit" id='taskdiscrpition_<?php echo $value['fieldID']; ?>'title="Edit your fileld description" data-toggle="tooltip" style="cursor: pointer;color: #0082ff;"onclick="bulkfield_descripiton(this)"></i>
                                                            <?php if (!empty($value['fielddescription'])) { ?>
                                                                <span id="desplaceholder-<?php echo $value['fieldID']; ?>" style="display:none;margin-left: 10px;color:gray;">Description</span>
                                                            <?php } else { ?>
                                                                <span id="desplaceholder-<?php echo $value['fieldID']; ?>" style="margin-left: 10px;color:gray;">Description</span>
                                                            <?php }; ?>
                                                        </p>
                                            
                                                <div class="addscrolfield">
                                                    <div id="row-<?php echo $value['fieldID']; ?>-descrpition" class='edittaskdiscrpition_<?php echo $value['fieldID']; ?>'><?php echo $value['fielddescription']; ?></div>

                                                       
                                                </div> 
                                        </td>
                                        
                                    </tr>  
                                    
                                <?php }else if($value['SystemfieldInternal'] != 'checked'){
                                    
                                
                                    if($value['fieldsystemtask'] == 'checked'){
                                    
                                    ?>
                                   
                                <tr style="background-color:  #2196f31c !important;">
                                    
                                    <?php }else{?>
                                <tr>
                                    <?php }?>
                                    <td id="row-<?php echo $value['fieldID']; ?>-fieldIndex" ><?php echo $value['fieldIndex'];?></td>
                                        <td><div class="hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a">
                                               
                                                
                                                <i data-toggle="tooltip" class="hi-icon fa fa-clone saveeverything" id="<?php echo $value['fieldID']; ?>" onclick="clonebulk_fields(this)" title="Create a clone" ></i>
                                                <i  data-toggle="tooltip" title="Field Settings" name="<?php echo $value['fieldID']; ?>" onclick="bulkfieldsettings(this)" class="hi-icon fusion-li-icon fa fa-gears" ></i>
                                                <?php if($value['fieldsystemtask'] != "checked") {?>
                                                <i  data-toggle="tooltip" title="Remove this field" name="<?php echo $value['fieldID']; ?>" onclick="removebulk_fields(this)" class="hi-icon fusion-li-icon fa fa-times-circle" ></i>
                                                <?php }?>
												
												<?php if($value['fieldsystemtask'] == "checked") {?>
												 <i  data-toggle="tooltip" title='This highlighted field is a "System field". Please contact support@expo-genie.com'  name="" onclick="" class="hi-icon fusion-li-icon fa fa-question-circle" ></i>
                                                <?php }?>
                                            </div> </td>
                                        <td><input <?php if($value['fieldsystemtask'] == "checked") {echo 'readonly="true" title="This is a system field. Changing its title is not allowed"';}else{echo 'title="Field Name"';} ?> type="text" style="margin-top: 10px;margin-bottom: 10px;" id="row-<?php echo $value['fieldID']; ?>-title" class="form-control" name="tasklabel" placeholder="Title" data-toggle="tooltip" title="Title" value="<?php echo $value['fieldName']; ?>" required> 
                                            
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-fielduniquekey"  value="<?php  if(isset($value['fielduniquekey'])){ echo $value['fielduniquekey']; }?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-fieldCode"  value="<?php  if(isset($value['fieldID'])){ echo $value['fieldID']; }?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-Systemfield"  value="<?php if(isset($value['fieldsystemtask'])){ echo $value['fieldsystemtask'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-fieldtooltip"  value="<?php if(isset($value['fieldtooltiptext'])){ echo $value['fieldtooltiptext'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-fieldstatusrequried"  value="<?php if(isset($value['fieldrequriedstatus'])){ echo $value['fieldrequriedstatus'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-fieldplaceholder"  value="<?php if(isset($value['fieldplaceholder'])){ echo $value['fieldplaceholder'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-attribute"  value="<?php if(isset($value['attribute'])){ echo $value['attribute'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-SystemfieldInternal"  value="<?php if(isset($value['SystemfieldInternal'])){ echo $value['SystemfieldInternal'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-multiselect"  value="<?php if(isset($value['multiselect'])){ echo $value['multiselect'];} ?>" ></span>
                                            
                                                
                                                
                                                <?php if ($value['fieldType'] == 'dropdown') {
                                                $options_values = "";
                                                foreach ($value['options'] as $Okey => $Ovalue) {
                                                    $options_values .= $Ovalue->label . ',';
                                                }
                                                $options_values = substr_replace($options_values ,"", -1);
                                                }else{$options_values = "";}
                                                ?>
                                           
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-linkurl"  value="<?php if(isset($value['fieldTypeLinkurl'])){ echo $value['fieldTypeLinkurl'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-linkname"  value="<?php if(isset($value['fieldTypeLinkname'])){ echo $value['fieldTypeLinkname'];} ?>" ></span>
                                            <span><input type="hidden" id="row-<?php echo $value['fieldID']; ?>-dropdownvlaues"  value="<?php if(isset($options_values)){ echo $options_values;} ?>" ></span>
                                           
                                            
                                            
                                        </td>
                                        <td>
                                           <div class="topmarrginebulkedit">
                                               <select  <?php if($value['fieldsystemtask'] == "checked") {echo 'disabled="true" title="This is a system field. Changing its type is not allowed"';}else{echo 'title="Field Type"';} ?>  style="width:100px !important;"class="select2 bulktasktypedrop tasktypesdata" id="bulktasktype_<?php echo $value['fieldID']; ?>" data-placeholder="Field Type" data-toggle="tooltip" data-allow-clear="true">
                                                    <?php foreach ($field_input_type as $val) { ?>
                                                        <?php if ($val['type'] == $value['fieldType']) { ?>
                                                            <option value="<?php echo $val['type']; ?>" selected="selected"><?php echo $val['lable']; ?></option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $val['type']; ?>" ><?php echo $val['lable']; ?></option>

                                                        <?php }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            

                                        </td>
                                        
                                        <td><p style="margin-top: 10px;">Display on Application Form 
                                            
                                            <?php if($value['displayonapplicationform'] == "checked"){
                                            
                                            echo '<input style="margin-left: 116px;margin-top: -17px;"type="checkbox" id="row-'.$value["fieldID"].'-fieldstatusshowonregform" class="form-control" checked="true">';
                                            
                                            }else{
                                                
                                             echo '<input style="margin-left: 116px;margin-top: -17px;" id="row-'.$value["fieldID"].'-fieldstatusshowonregform" type="checkbox" class="form-control" >';
                                            }?>
                                            </p></td>
                                        
                                        <td>
                                            <p style="margin-top: 5px;"><i class="font-icon fa fa-edit" id='taskdiscrpition_<?php echo $value['fieldID']; ?>'title="Edit your fileld description" data-toggle="tooltip" style="cursor: pointer;color: #0082ff;"onclick="bulkfield_descripiton(this)"></i>
                                                            <?php if (!empty($value['fielddescription'])) { ?>
                                                                <span id="desplaceholder-<?php echo $value['fieldID']; ?>" style="display:none;margin-left: 10px;color:gray;">Description</span>
                                                            <?php } else { ?>
                                                                <span id="desplaceholder-<?php echo $value['fieldID']; ?>" style="margin-left: 10px;color:gray;">Description</span>
                                                            <?php }; ?>
                                                        </p>
                                            
                                                <div class="addscrolfield">
                                                    <div id="row-<?php echo $value['fieldID']; ?>-descrpition" class='edittaskdiscrpition_<?php echo $value['fieldID']; ?>'><?php echo $value['fielddescription']; ?></div>

                                                       
                                                </div> 
                                        </td>
                                        
                                    </tr>  
<?php }} ?>        
                            </tbody>
                             <tfoot>
                               
        </tfoot>
                        </table>
                </div>
            <div class="form-group row"> 
                    
                    <div class="col-sm-10">
                        


                        <button  type="submit"  name="savealltask"   class="btn btn-lg mycustomwidth btn-success" value="Register">Save All Changes</button>
                        <a  name="addsponsor2"   class="addnewbulktask btn btn-lg mycustomwidth btn-success" value="Register">Add New Field</a>
                    </div>
                </div>
            </form>
            </div>
        </div>
</div>

        <?php
        include 'cm_footer.php';?>

   
 <script type="text/javascript" src="https://mpryvkin.github.io/jquery-datatables-row-reordering/1.2.3/jquery.dataTables.rowReordering.js"></script>    
 <script type="text/javascript" src="/wp-content/plugins/EGPL/js/bulk_edit_fields.js?v=4.21"></script>    

 
<?php 
    } else {
        $redirect = get_site_url();
        wp_redirect($redirect);
        exit;
    }
    ?>