<?php
// Template Name: Bulk Edit Task 
if (current_user_can('administrator') || current_user_can('contentmanager')) {
    global $wp_roles;
    $all_roles = $wp_roles->get_names();
    
    
    include 'cm_header.php';
    include 'cm_left_menu_bar.php';

    $sponsor_id = get_current_user_id();

    
    $args = array(
	'posts_per_page'   => -1,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'egpl_custome_tasks',
	'post_status'      => 'draft',
	
    );
    $result = get_posts( $args );
   
    foreach ($all_roles as $key=>$name){
        
        if($name == $_GET['rolename']){
            $rolekey = $key;
        }
    }
    
     $Rolename=$rolekey;
     $Rolename_display=$_GET['rolename'];
    ?>
    <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            
                            <h3 >Task Assignment for <strong><?php echo $Rolename_display;?> </strong></h3>
                            <input type="hidden" value="<?php echo $Rolename;?>" id="editrolename"/>
                        </div>
                    </div>
                </div>
            </header>

            <div class="box-typical box-typical-padding">
                <p>
                     Here you can manage all task assignments of the selected level. You can assign and unassign any tasks you like.
                </p>
                <h5 class="m-t-lg with-border"></h5>
                
                <div class="form-group row">
                        <label class="col-sm-2 form-control-label">Assign New Tasks</label>

                        <div class="col-sm-9">
                            <select  class="select2"  id="addnewroleassignment" data-placeholder="Select Tasks" data-allow-clear="true" multiple="multiple">
                               
                                <?php
                                
                                foreach ($result as $tasksIDKey => $tasksObject) {
                                                
                                    
                                                $tasksID = $tasksObject->ID;
                                                $value_label = get_post_meta( $tasksID, 'label' , false);
                                                $value_key = get_post_meta( $tasksID, 'key', false);
                                                $profile_field_name_key = $value_key[0];
                                                $value_roles = get_post_meta( $tasksID, 'roles' , false);
                                                $os = $value_roles[0];
                                                
                                                
                                                
                                                
                                                if (in_array($Rolename, $os) || in_array('all', $os)) {
                                                    // print_r(  $os );
                                                   
                                                }else{
                                                    
                                                     echo '<option value="'.$tasksID.'">'.$value_label[0] . '</option>';
                                                }
                                           
                                        
                                    }
                                    ?>
                                
                                
                                
                            </select>
                        </div>
                        <div class="col-sm-1">
                          
                              <button  style="float: right;" name="assignnewtask"   id="assignnewtask" class="btn btn-inline  btn-success" value="Register">Add</button>
                            
                        </div>
                  </div>
                 <div class="form-group row">
                        

                        <div class="col-sm-12">
                            
                            <table  class="assigntaskrole display table table-striped table-bordered"  >
                                    <thead><tr class="text_th " > <th >Assigned Tasks</th> <th >Action</th> </tr></thead> <tbody>
                                       
                                            
                                       
                                    <?php
                                
                                foreach ($result as $tasksIDKey => $tasksObject) {

                                       


                                                $tasksID = $tasksObject->ID;
                                                $value_label = get_post_meta( $tasksID, 'label' , false);
                                                $value_key = get_post_meta( $tasksID, 'key', false);
                                                $profile_field_name_key = $value_key[0];
                                                $value_roles = get_post_meta( $tasksID, 'roles' , false);
                                                
                                                
                                                $os = $value_roles[0];

                                                if (in_array($Rolename, $os)) {
                                                    // print_r(  $os );
                                                    echo ' <tr><td ><p class="assignedtasks" id="'.$tasksID.'">'.$value_label[0] . '</p></td>';
                                                    echo '<td ><i style=" cursor: pointer;margin-left: 10px;" onclick="removetask_forthisrole(this)" title="Remove this task" class="fusion-li-icon fa fa-times-circle fa-2x" style="color:#262626;"></i></td></tr>';
                                                
                                                    
                                                }else if(in_array('all', $os)){
                                                    
                                                    echo ' <tr ><td ><p id="'.$tasksID.'">'.$value_label[0] . '</p></td>';
                                                    echo '<td ><i style=" color:gray;margin-left: 10px;"  title="You can\'t remove this task." class="fusion-li-icon fa fa-times-circle fa-2x" style="color:#262626;"></i></td></tr>';
                                                 
                                                }
                                            
                                        
                                    }
                                    ?>
                                     
                                    </tbody>
                            </table>
                            
                            
                        </div>
                    </div>
                
                   <div class="form-group row">
                            
                            <div class="col-sm-6">
  
                                <button    name="savealltask"  onclick="roleassignednewtask()" id="roleassignnewtask" class="btn btn-lg mycustomwidth btn-success" value="Register">Save All Changes</button>
                                <a  onclick="returnback()" class="btn btn-lg mycustomwidth btn-success" style="background: gray !important;border: 1px solid #808080 !important;">Cancel</a>
                                
                              
 
 
                            </div>
                            
                        </div>
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