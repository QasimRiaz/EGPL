<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
       
   
    
    global $wp_roles;
    $site_url  = get_site_url();

   // $all_roles = $wp_roles->roles;
    
  
    
          
    
     //$get_all_roles_array = 'wp_user_roles';
     if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }
     $get_all_roles = get_option($get_all_roles_array);
     $sortedrolelist = array();
     foreach ($get_all_roles as $key => $name) {
            if ($name['name'] != 'Unassigned' && $name['name'] != 'Content Manager' && $name['name'] != 'Administrator') {   
                $sortedrolelist[$key]['key'] =  $key;
                $sortedrolelist[$key]['name'] =  $name['name'];
                $sortedrolelist[$key]['priorityNum'] =  $name['priorityNum'];
            }
         
     }
    
    // echo '<pre>';
     //print_r($get_all_roles);exit;
     
     usort($sortedrolelist, function($a, $b) {
        return $a['priorityNum'] <=> $b['priorityNum'];
     });




      $list="";
      $args = array(
	'posts_per_page'   => -1,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'egpl_custome_tasks',
	'post_status'      => 'draft',
	
    );
    $get_task_keys = get_posts( $args );
      
      //$test = 'custome_task_manager_data';
     // $get_task_keys = get_option($test);
      
      
      
    
      include 'cm_header.php';
       include 'cm_left_menu_bar.php';
                ?>

<style>
            td:hover{
		cursor:move;
            }
</style>

   <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Manage Levels</h3>
                           
                        </div>
                    </div>
                </div>
            </header>

            <div class="box-typical box-typical-padding">
                    <p>
                        Levels are a group of permissions which define what a user can access in the portal. Each user can only be assigned one Level at a time. For example, a user assigned a "Gold" Level could see one set of Tasks and/or Marketing Opportunities, whereas users assigned a "Silver" Level could see a different set. This is just one example, but you have the flexibility to create the Level names and assignment options as you choose. Level assignment can be assigned at the time of creating a user, or can be auto-assigned based on an item they purchase. Level assignment is also an internal assignment, meaning your users will not have any visibility of their Level.
                        </p><p>Level Priorities define a hierarchy of Levels. You can drag and drop Levels in the table below to change the Priority. So if a user added multiple items in their shop which has various Levels, the highest priority will be the Level assigned to that user.
                    </p>
                    
                    <h5 class="m-t-lg with-border"></h5>

                    <form method="post" action="javascript:void(0);" onSubmit="add_new_role_contentmanager()">

                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Add New Level <strong>*</strong></label>
                            <div class="col-sm-7">
  <div class="form-control-wrapper form-control-icon-left">    
                                <input type="text"  class="form-control" id="rolename" placeholder="Level Name" required>
 <i class="font-icon fa fa-edit"></i>
 </div>
                            </div>
                            <div class="col-sm-3">
                                <button type="submit"  name="addsponsor"  class="btn btn-inline mycustomwidth btn-success" value="Register">Create</button>
                              
                            </div>
                        </div>
                    </form>
                </div>
                <div class="box-typical box-typical-padding">
                           <div class="card-block">
					<table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
						<tr>     <th>Priority</th>
							<th><b>Level Name</b><p style="font-size: 12px;color: gray;">(Hover over a level name to see the associated tasks)</p></th>
                                                        <th>Action <span style="float: right;"><button onclick="setlevelspriorities()" class="btn btn-inline mycustomwidth btn-success" title="Save Level Priorities">Save</button></span></th>
                                                        
							
						</tr>
						</thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>Priority</th>
                                                        <th><b>Level Name</b><p style="font-size: 12px;color: gray;">(Hover over a level name to see the associated tasks)</p></th>
                                                        <th>Action<span style="float: right;"><button onclick="setlevelspriorities()" class="btn btn-inline mycustomwidth btn-success" title="Save Level Priorities">Save</button></span></th>
                                                        
							
						</tr>
                                                    
                                                </tfoot>
						<tbody>
   <?php  $proiortunumber=1; foreach ($sortedrolelist as $key => $name) {
                                                $taskcount=0;
                                                $list='';
                                                      
                                                    
                                                   
                                                  
        foreach ($get_task_keys as $tasksIDKey => $tasksObject) {
             
                                                
                                    
                                                $tasksID = $tasksObject->ID;
                                                $value_label = get_post_meta( $tasksID, 'label' , false);
                                                $value_key = get_post_meta( $tasksID, 'key', false);
                                                $profile_field_name = $value_key[0];
                                                $value_roles = get_post_meta( $tasksID, 'roles' , false);
                                                $os = $value_roles[0];
                                                
                                                
            if (strpos($profile_field_name, "task") !== false) {
                
                
               if (strpos($profile_field_name, "status") !== false  || strpos($profile_field_name, "datetime") !== false) {  
                   
                   
               }else{
               // echo $key;
                   //$os = $profile_field_settings['roles'];
                   
                if (in_array($name['key'], $os) || in_array('all', $os)) {
                 // print_r(  $os );
                    $list.=$value_label[0].'&#013;';
                    $taskcount++;
                    
                }
               }
                    
                }
            }
           
        
                          echo '<tr id="'.$name['key'].'"><td>'.$proiortunumber.'</td>
                                                           <td><a href="'.$site_url.'/role-assignment/?rolename='.$name['name'].'"><label style="cursor: pointer;"  id="mrolename" title="'.$list.'">' . $name['name'] . '  ('.$taskcount.' tasks)</label></a></td>
                                                           <td><div class="hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a">
   
    
    <i class="hi-icon fusion-li-icon fa fa-pencil-square fa-2x"  title="Edit Level Name" name="'.$name['name'].'" onclick="editrolename(this)" id="'.$name['key'].'" ></i>
    <i class="hi-icon fusion-li-icon fa fa-clone fa-2x" name="'.$name['name'].'" title="Create a Clone" onclick="createroleclone(this)" id="'.$name['key'].'" ></i>
    <i class="hi-icon fusion-li-icon fa fa-times-circle fa-2x" onclick="delete_role_name(this)" id="'.$name['key'].'" name="delete-sponsor" title="Remove Level" ></i>
</div></td>
                                                           </tr>';                          
                                                  
                                                  
                                                $proiortunumber++;  
                                                   
                                              
                                    
                                              }
                                           
                                    ?> 
                                                      
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
       </div>
    
                
    
    
			<?php   include 'cm_footer.php';?>
		
      
<script>	
                jQuery(function () {
    jQuery("#example tbody").sortable({
        items: 'tr',
        cursor: 'pointer',
        axis: 'y',
        dropOnEmpty: false,
        start: function (e, ui) {
            ui.item.addClass("selected");
        },
        stop: function (e, ui) {
            ui.item.removeClass("selected");
            jQuery("#example tbody").find("tr").each(function (index) {
                if (index >= 0) {
                        
                        index=index+1;
                    
                    jQuery(this).find("td").eq(0).html(index);
                }
            });
        }
    });
});
 
      </script>
       
   <?}else{
       $redirect = get_site_url();
    wp_redirect( $redirect );exit;
   
   }
   ?>