<?php
// Template Name: Bulk Edit Task 
if (current_user_can('administrator') || current_user_can('contentmanager')) {

    include 'cm_header.php';
    include 'cm_left_menu_bar.php';

    $sponsor_id = get_current_user_id();
    $test = 'custome_task_manager_data';
    $result = get_option($test);
    //$result = json_decode(json_encode($result), true);
    //echo '<pre>';
    //print_r($array);exit;
    
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
        
      
     //}
    
     
    ?>
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
                    <label class="col-sm-6 form-control-label"></label>
                    
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
                                echo '<option value="' . $key . '">' . $name . '</option>';
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
                                <th >Attributes <i class="fa fa-info-circle" title="Use this to define constraints such as character limit, allowed file types, etc.Example: maxlength=5   accept=.png,.jpg" style="cursor: pointer;"aria-hidden="true"></i></th>
                                <th >User/Level</th>
                                <th >Description</th>

                            </tr>
                        </thead>
                        <tbody>

                          <?php                                
                          
                          foreach ($result['profile_fields'] as $key=>$value){  
                              
                              $task_code = end(split('_',$key));
                        
                              
                              ?>
                         
                            <tr>
                                <td>
                                   
                                        <div class="hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><i class="hi-icon fa fa-clone saveeverything" style="color:#262626;cursor: pointer;" id="<?php echo $task_code;?>" onclick="clonebulk_task(this)" title="Create a clone" aria-hidden="true"></i>
                                            <i style=" cursor: pointer;margin-left: 10px;" title="Remove this task" onclick="removebulk_task(this)"class="hi-icon fusion-li-icon fa fa-times-circle " style="color:#262626;"></i></div>
                                    
                                </td>

                                <td>


                                    <input type="text" style="margin-top: 10px;margin-bottom: 10px;" id="row-<?php echo $task_code;?>-title" class="form-control" name="tasklabel" placeholder="Title"  title="Title" value="<?php echo htmlspecialchars($value['label']);?>" required> 
                                    <span><input type="hidden" id="row-<?php echo $task_code;?>-key"  value="<?php echo $key; ?>" ></span> 
                                    
                                    


                                </td>
                                <td>
                                    <div class="topmarrginebulkedit">
                                        <select  class="select2 bulktasktypedrop tasktypesdata" id="bulktasktype_<?php echo $task_code;?>" data-placeholder="Select Type" title="Select Type" data-allow-clear="true">
                                            
                                             <?php foreach ($plug_in_settings['ContentManager']['taskmanager']['input_type'] as $val) { ?>
                                                <?php if($val['type'] == $value['type']){ ?>
                                            <option value="<?php echo $val['type']; ?>" selected="selected"><?php echo $val['lable']; ?></option>
                                             <?php }else{ ?>
                                             <option value="<?php echo $val['type']; ?>" ><?php echo $val['lable']; ?></option>
                                           
                                             <?php } } ?>
                                            
                                            
                                        </select>
                                    </div>
                                       
                                        <?php if($value['type'] == 'link'){?>
                                        <div class="bulktasktype_<?php echo $task_code;?>" style="display: block;margin-top:10px;margin-bottom: 10px;" >
                                        <?php }else{ ?>
                                        <div class="bulktasktype_<?php echo $task_code;?>" style="display: none;margin-top:10px;margin-bottom: 10px;" >
                                        <?php } ?>
                                        <input type="text"  class="form-control" name="linkurl" id="row-<?php echo $task_code;?>-linkurl" placeholder="Link URL" title="Link URL" value="<?php echo $value['lin_url'];?>" /> 
                                        <br>
                                        <input type="text"  class="form-control" name="linkname" id="row-<?php echo $task_code;?>-linkname" placeholder="Link Name"  title="Link Name" value="<?php echo $value['linkname'];?>" /> 
                                        </div>
                                       
                                        <?php if($value['type'] == 'select-2'){
                                            $options_values="";
                                            foreach ($value['options'] as $Okey => $Ovalue) {
                                                    $options_values .= $Ovalue['label'] . ',';
                                            }?>
                                      <div class="dbulktasktype_<?php echo $task_code;?>" style="display: block;margin-top:10px;margin-bottom: 10px;" >
                                       <?php }else{ ?>
                                         <div class="dbulktasktype_<?php echo $task_code;?>" style="display: none;margin-top:10px;margin-bottom: 10px;" > 
                                              <?php } ?>
                                        <input type="text"  class="form-control" name="dropdownvalues" id="row-<?php echo $task_code;?>-dropdownvlaues" placeholder="Comma separated list of values" title="Comma separated list of values" value="<?php echo rtrim($options_values,',');?>" /> 
                                       </div> 
                                       
                                        
                                    </div>
                                </td>

                                <td>



                                    <input type="text" style="padding-left: 13px;margin-top: 10px;margin-bottom: 10px;" id="row-<?php echo $task_code;?>-duedate" class="form-control datepicker" name="datepicker"  placeholder="Due Date" title="Due Date"  value="<?php echo $value['attrs'];?>">


                                </td>
                                <td>


                                    <input name="attribure" style="margin-top: 10px;margin-bottom: 10px;" id="row-<?php echo $task_code;?>-attribute" class="form-control" placeholder="Attributes" title="Attributes" value="<?php echo $value['taskattrs'];?>" >


                                </td>
                                <td > 
                                    <div class="addscrol topmarrginebulkedit">

                                        <select class="select2"  data-placeholder="Select Levels" title="Select Levels" id="row-<?php echo $task_code;?>-levels" data-allow-clear="true" multiple="multiple">
                                            
                                            <?php
                                            
                                            if(in_array('all',$value['roles'])){
                                                  
                                                    echo '<option value="all" selected="selected">All</option>';  
                                                }else{
                                                    
                                                    echo '<option value="all">All</option>';
                                                }
                                            
                                            foreach ($all_roles as $key=>$name) {
                                                if(in_array($key,$value['roles'])){
                                                  
                                                    echo '<option value="' . $key . '" selected="selected">' . $name . '</option>';  
                                                }else{
                                                    
                                                    echo '<option value="' . $key . '">' . $name . '</option>';
                                                }
                                                
                                                
                                                
                                                
                                            }
                                            ?>
                                        </select>
                                        <br>
                                        
                                        <select class="select2" data-placeholder="Select Users" title="Select Users" data-allow-clear="true" id="row-<?php echo $task_code;?>-userid" multiple="multiple" >
                                            <?php
                                            foreach ($get_all_ids as $user) {
                                                if(in_array($user->ID,$value['usersids'])){
                                                echo '<option value="' . $user->ID . '" selected="selected">' . $user->user_email . '</option>';
                                                }else{
                                                  echo '<option value="' . $user->ID . '">' . $user->user_email . '</option>';  
                                                }
                                                
                                                }
                                            ?>
                                        </select>
                                    </div> 
                                </td>

                                <td ><br>
                                    <div class="addscrol">
                                        <div id="row-<?php echo $task_code;?>-descrpition" class='edittaskdiscrpition_<?php echo $task_code;?>'><?php echo $value['descrpition'];?></div>
                                        
                                        <p ><i class="font-icon fa fa-edit" id='taskdiscrpition_<?php echo $task_code;?>'title="Edit your task description"style="cursor: pointer;color: #0082ff;"onclick="bulktask_descripiton(this)"></i>
                                            <?php if(!empty($value['descrpition'])){;?>
                                            <span id="desplaceholder-<?php echo $task_code;?>" style="display:none;margin-left: 10px;color:gray;">Description</span>
                                            <?php }else{;?>
                                            <span id="desplaceholder-<?php echo $task_code;?>" style="margin-left: 10px;color:gray;">Description</span>
                                            <?php };?>
                                        </p>
                                    </div> 
                                </td>
                            </tr>  
                 
                          
                        <?php }?>        
                            
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


        <?php
        include 'cm_footer.php';
    } else {
        $redirect = get_site_url();
        wp_redirect($redirect);
        exit;
    }
    ?>