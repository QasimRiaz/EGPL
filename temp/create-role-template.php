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
     
    
    
    $list="";
      $test = 'custome_task_manager_data';
      $get_task_keys = get_option($test);
      
      
      
    
      include 'cm_header.php';
       include 'cm_left_menu_bar.php';
                ?>



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
                        Here you can create various levels your users will belong to. Each level represents a different class of your users. e.g. Gold, Silver, Platinum etc. Each user will be able to see a different set of tasks based on their assigned level. 
                       
                    
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
						<tr>
							<th><b>Level Name</b><p style="font-size: 12px;color: gray;">(Hover over a level name to see the associated tasks)</p></th>
							<th>Action</th>
							
							
						</tr>
						</thead>
						
						<tbody>
   <?php  foreach ($get_all_roles as $key => $name) {
                                                $taskcount=0;
                                                $list='';
                                              if ($key != 'administrator' && $key != 'contentmanager' && $key != 'subscriber') {              
                                                    
                                                   

        foreach ($get_task_keys['profile_fields'] as $profile_field_name => $profile_field_settings) {
              
            if (strpos($profile_field_name, "task") !== false) {
                
                
               if (strpos($profile_field_name, "status") !== false  || strpos($profile_field_name, "datetime") !== false) {  
                   
                   
               }else{
               // echo $key;
                   $os = $profile_field_settings['roles'];
                   
                if (in_array($key, $os) || in_array('all', $os)) {
                 // print_r(  $os );
                    $list.=$profile_field_settings['label'].'&#013;';
                    $taskcount++;
                    
                }
               }
                    
                }
            }
           
        
                          echo '<tr>
                                                           <td><a href="'.$site_url.'/role-assignment/?rolename='.$name['name'].'"><label style="cursor: pointer;"  id="mrolename" title="'.$list.'">' . $name['name'] . '  ('.$taskcount.' tasks)</label></a></td>
                                                           <td><div class="hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a">
   
    
    <i class="hi-icon fusion-li-icon fa fa-pencil-square fa-2x"  title="Edit Level Name" name="'.$name['name'].'" onclick="editrolename(this)" id="'.$key.'" ></i>
    <i class="hi-icon fusion-li-icon fa fa-clone fa-2x" name="'.$name['name'].'" title="Create a Clone" onclick="createroleclone(this)" id="'.$key.'" ></i>
    <i class="hi-icon fusion-li-icon fa fa-times-circle fa-2x" onclick="delete_role_name(this)" id="'.$key.'" name="delete-sponsor" title="Remove Level" ></i>
</div></td>
                                                           </tr>';                          
                                                  
                                                  
                                                  
                                                   
                                              }
                                    
                                              }
                                           
                                    ?> 
                                                      
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
       </div>
    
                
    
    
			<?php   include 'cm_footer.php';
		
      
      
      
       
   }else{
       $redirect = get_site_url();
    wp_redirect( $redirect );exit;
   
   }
   ?>