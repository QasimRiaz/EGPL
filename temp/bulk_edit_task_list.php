<?php
// Template Name: Bulk Edit Task 
if (current_user_can('administrator') || current_user_can('contentmanager')) {

    include 'cm_header.php';
    include 'cm_left_menu_bar.php';

    $sponsor_id = get_current_user_id();
    $test = 'custome_task_manager_data';
    $result = get_option($test);
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
                     Help Text
                </p>

                <h5 class="m-t-lg with-border"></h5>
   <div class="form-group row">
                            <label class="col-sm-2 form-control-label"></label>
                            <div class="col-sm-5">
  
                              
 
 
                            </div>
                            <div class="col-sm-5">
                                <button  name="addsponsor" style="float: right;"  class="addnewbulktasklistview btn btn-inline mycustomwidth btn-info" value="Register">Add New Task</button>
                              
                          
                                <button  style="float: right;"name="savealltask"  onclick="saveallbulktask()" id="savealltask" class="btn btn-inline  btn-success" value="Register">Save All Changes</button>
                              
                            </div>
                        </div>
<div class="form-group row">
                <table  class="bulkedittasklistview  table-bordered compact dataTable no-footer"  >
                    <thead>
                        <tr class="text_th" >
                            <th >Action</th>
                            <th >Title</th>
                            <th >Type</th>
                            <th >Due Date</th>
                            <th >Attributes <i class="fa fa-info-circle" title="Use this to define constraints such as character limit, allowed file types, etc.Example: maxlength=5   accept=.png,.jpg" style="cursor: pointer;"aria-hidden="true"></i></th>
                            <th >User/Level</th>
                            <th >Description</th>
                           
                        </tr></thead>
                    <tbody>

                        <tr >
                            <td><p style="margin-top: 10px;margin-bottom: 10px;"><i class="fa fa-clone" style="color:#262626;cursor: pointer;" title="Create a clone" aria-hidden="true"></i><i style=" cursor: pointer;margin-left: 10px;" title="Remove this task" onclick="removebulk_tasklistview(this)"class="fusion-li-icon fa fa-times-circle " style="color:#262626;"></i></p></td>
                            
                              <td>
                              
                               
                                <input type="text" style="margin-top: 10px;margin-bottom: 10px;" class="form-control" name="tasklabel" placeholder="Task Title"  id="tasklabel" value="Contact Information"> 
                                
                               
                               
                            </td>
                            <td>
                                 <div class="topmarrginebulkedit">
                                <select  class="select2 " data-placeholder="Select Type" data-allow-clear="true">
                                    <option >None</option>
                                    <option selected="selected">Textarea</option>
                                    <option>File Upload</option>
                                    <option>Date</option>
                                    <option>Email</option>
                                    <option>Number</option>
                                </select>
                                 </div>
                            </td>
                          
                            <td>
                                
                              
                               
                                <input type="text" style="margin-top: 10px;margin-bottom: 10px;" class="form-control" name="datepicker" placeholder="Task Due Date"  value="12/12/2016">
                               
                                
                            </td>
                              <td>
                               
                               
                               <input name="attribure" style="margin-top: 10px;margin-bottom: 10px;" class="form-control" placeholder="Task Attributes" id="attribure" value="maxlength=5">
                                
                                
                            </td>
                            <td > 
                                <div class=" topmarrginebulkedit">
                                
                                <select class="select2"  data-placeholder="Select Levels" data-allow-clear="true" multiple="multiple">
                                    <option>All</option>
                                    <option selected="selected">Admin</option>
                                    <option>Content Manager</option>
                                    <option selected="selected">Gold</option>
                                    <option>Sliver</option>
                                </select>
                                <br>
                               
                                
                             
                                <select class="select2" data-placeholder="Select Users" data-allow-clear="true" multiple="multiple" >
                                    <option>testuser1@gmail.com</option>
                                   <option>testuser2@gmail.com</option>
                                   <option>testuser4@gmail.com</option>
                                   <option>testuser5@gmail.com</option>
                                   <option>testuser3@gmail.com</option>
                                </select>
                               </div> 
                            </td>
                          
                            <td ><br>
                                <div class="">
                                <p>Upload your headshot file here.  (.PNG or .JPG files only)</p>
                                <p ><i class="font-icon fa fa-edit" title="Edit your task description"style="cursor: pointer;color: #0082ff;"onclick="bulktask_descripiton()"></i></p>
                             </div> 
                            </td>
                        </tr>  
<tr>
                            <td><p style="margin-top: 10px;margin-bottom: 10px;"><i class="fa fa-clone" style="color:#262626;cursor: pointer;" title="Create a clone" aria-hidden="true"></i><i style=" cursor: pointer;margin-left: 10px;" title="Remove this task" onclick="removebulk_tasklistview(this)"class="fusion-li-icon fa fa-times-circle " style="color:#262626;"></i></p></td>
                            <td>
                              
                               
                                <input type="text" style="margin-top: 10px;margin-bottom: 10px;" class="form-control" name="tasklabel" placeholder="Task Title"  id="tasklabel" value="Payment Last Date"> 
                                
                               
                               
                            </td>
                            <td>
                                 <div class="topmarrginebulkedit">
                                <select  class="select2 " data-placeholder="Select Type" data-allow-clear="true">
                                    <option >None</option>
                                    <option >Textarea</option>
                                    <option>File Upload</option>
                                    <option selected="selected">Date</option>
                                    <option>Email</option>
                                    <option>Number</option>
                                </select>
                                 </div>
                            </td>
                            
                            <td>
                                
                              
                               
                                <input type="text" style="margin-top: 10px;margin-bottom: 10px;" class="form-control" placeholder="Task Due Date" name="datepicker"  value="11/06/2016">
                               
                                
                            </td>
                              <td>
                               
                               
                               <input name="attribure" style="margin-top: 10px;margin-bottom: 10px;" class="form-control" placeholder="Task Attributes" id="attribure" value="maxlength=5">
                                
                                
                            </td>
                            <td >
                                <div class=" topmarrginebulkedit">
                                
                                <select class="select2"  data-placeholder="Select Levels" data-allow-clear="true" multiple="multiple">
                                    <option>All</option>
                                    <option >Admin</option>
                                    <option>Content Manager</option>
                                    <option >Gold</option>
                                    <option>Sliver</option>
                                </select>
                                <br>
                               
                                
                             
                                <select class="select2" data-placeholder="Select Users" data-allow-clear="true" multiple="multiple" >
                                    <option selected="selected">testuser1@gmail.com</option>
                                   <option>testuser2@gmail.com</option>
                                   <option>testuser4@gmail.com</option>
                                   <option selected="selected">testuser5@gmail.com</option>
                                   <option>testuser3@gmail.com</option>
                                </select>
                              </div>  
                            </td>
                          
                            <td ><br>
                                <div class="">
                                <p>Upload your headshot file here.  (.PNG or .JPG files only)</p>
                                <p ><i class="font-icon fa fa-edit" title="Edit your task description"style="cursor: pointer;color: #0082ff;"onclick="bulktask_descripiton()"></i></p>
                              </div>  
                                </td>
                        </tr>  
                        <tr>
                            <td><p style="margin-top: 10px;margin-bottom: 10px;"><i class="fa fa-clone" style="color:#262626;cursor: pointer;" title="Create a clone" aria-hidden="true"></i><i style=" cursor: pointer;margin-left: 10px;" title="Remove this task" onclick="removebulk_tasklistview(this)"class="fusion-li-icon fa fa-times-circle " style="color:#262626;"></i></p></td>
                             <td>
                              
                               
                                <input type="text" style="margin-top: 10px;margin-bottom: 10px;" class="form-control" placeholder="Task Title" name="tasklabel"  value="Upload Your Company Logo"> 
                                
                               
                               
                            </td>
                            <td>
                                <div class="topmarrginebulkedit">
                                <select  class="select2" data-placeholder="Select Type" data-allow-clear="true">
                                    <option >None</option>
                                    <option >Textarea</option>
                                    <option selected="selected">File Upload</option>
                                    <option >Date</option>
                                    <option>Email</option>
                                    <option>Number</option>
                                </select>
                                </div>
                            </td>
                           
                            <td>
                                
                              
                               
                                <input type="text" style="margin-top: 10px;margin-bottom: 10px;" class="form-control" placeholder="Task Due Date" name="datepicker" id="datepicker" value="11/06/2016">
                               
                                
                            </td>
                              <td>
                               
                               
                               <input name="attribure" style="margin-top: 10px;margin-bottom: 10px;" class="form-control" placeholder="Task Attributes" id="attribure" value="maxlength=5">
                                
                                
                            </td>
                            <td >
                                <div class=" topmarrginebulkedit">
                                
                                <select class="select2"  data-placeholder="Select Levels" data-allow-clear="true" multiple="multiple">
                                    <option>All</option>
                                    <option >Admin</option>
                                    <option>Content Manager</option>
                                    <option >Gold</option>
                                    <option>Sliver</option>
                                </select>
                                <br>
                               
                                
                             
                                <select class="select2" data-placeholder="Select Users" data-allow-clear="true" multiple="multiple" >
                                    <option selected="selected">testuser1@gmail.com</option>
                                   <option>testuser2@gmail.com</option>
                                   <option>testuser4@gmail.com</option>
                                   <option selected="selected">testuser5@gmail.com</option>
                                   <option>testuser3@gmail.com</option>
                                </select>
                               </div> 
                            </td>
                          
                            <td ><br>
                                <div class="">
                                <p>Upload your presentation for:
                                   Session Title: Pre-Recorded Case: Complex SFA/Pop Recanalization Using Antegrade and Retrograde Proximal Tibial Artery
                                   Thursday  8/10/2017  
                                   3:40pm - 3:50pmUpload your headshot file here.  (.PNG or .JPG files only)</p>
                                <p ><i class="font-icon fa fa-edit" title="Edit your task description"style="cursor: pointer;color: #0082ff;"onclick="bulktask_descripiton()"></i></p>
                             </div> 
                            </td>
                        </tr> 
                    </tbody>

                </table>
                </div>
<div class="form-group row">
                            <label class="col-sm-2 form-control-label"></label>
                            <div class="col-sm-5">
  
                              
 
 
                            </div>
                            <div class="col-sm-5">
                                <button  name="addsponsor2" style="float: right;"  class="addnewbulktasklistview btn btn-inline mycustomwidth btn-info" value="Register">Add New Task</button>
                              
                          
                                <button  style="float: right;" name="savealltask"  onclick="saveallbulktask()" id="savealltask" class="btn btn-inline  btn-success" value="Register">Save All Changes</button>
                              
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