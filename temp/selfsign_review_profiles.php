<?php
// Template Name: Bulk Edit Task 
if (current_user_can('administrator') || current_user_can('contentmanager')) {
    
    
    
    include 'cm_left_menu_bar.php';
    include 'cm_header.php'; 
     
   $additional_fields_settings_key = 'EGPL_Settings_Additionalfield';
   $additional_fields = get_option($additional_fields_settings_key);
   $args['meta_query']['relation']= 'OR';
   $args['role__not_in']= 'Administrator';
   $sub_query['key']='selfsignupstatus';
   $sub_query['compare']='=';
   $sub_query['value']='Pending';
   array_push($args['meta_query'],$sub_query);
   $sub_query['key']='selfsignupstatus';
   $sub_query['compare']='=';
   $sub_query['value']='Declined';
   array_push($args['meta_query'],$sub_query);
   
   
   $user_query = new WP_User_Query( $args );
   $authors = $user_query->get_results();
   
   $get_all_roles_array = 'wp_user_roles';
   $get_all_roles = get_option($get_all_roles_array);
   
   
   ?>


   <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Self Signed Users Report</h3>

                        </div>
                    </div>
                </div>
            </header>
           <select id="assignuserroles" style="display: none;" >
               <option value="subscriber" selected="selected"></option>   
    <?php  
    foreach ($get_all_roles as $key => $item) {
        if($item['name'] !='Administrator' && $item['name'] !='Content Manager' && $item['name'] !='Subscriber'){
        ?>
            <option value="<?php echo $key;?>"><?php echo $item['name'];?></option>   
            
    <?php }} ?>
</select>
           <div class="box-typical box-typical-padding"  > 
            
            
            <table id="selfsignusers" class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%">
                  <thead>
                        <tr>
                            <th>Action</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Company Name</th>
                             <th>Status</th>
                            <th>Company Logo</th>
                           
                            <?php   
                                foreach ($additional_fields as $key=>$value){  if($additional_fields[$key]['name'] !='Notes' && $additional_fields[$key]['name'] !='Registration Codes'){?>
                                  <th><?php echo $additional_fields[$key]['name'];?></th>  
                                <?php } }?>
                        </tr>
                </thead>
                <tbody>
                       <?php  foreach ($authors as $aid) {
                           
                            $user_data = get_userdata($aid->ID);
                            $all_meta_for_user = get_user_meta($aid->ID);
                            
                            if($all_meta_for_user['selfsignupstatus'][0] == 'Declined'){
                                $column_row_action="";
                            }else{
                                $column_row_action= '<div style="width: 140px !important;"class = "hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><a onclick="approvethisuser(this)" id="' . $aid->ID . '" name="approve"   title="Approved" data-toggle="tooltip" ><i class="hi-icon fusion-li-icon fa fa-check-circle-o" ></i></a><a onclick="declinethisuser(this)" id="' . $aid->ID . '" name="delete-sponsor" data-toggle="tooltip"  title="Declined" ><i class="hi-icon fusion-li-icon fa fa-times-circle" ></i></a></div>';
                            }
                            
                           ?> 
                        <tr>
                            
                            
                            <td><?php echo $column_row_action;?></td>
                            <td><?php echo $user_data->first_name;?></td>
                            <td><?php echo $user_data->last_name;?></td>
                            <td><?php echo $user_data->user_email;?></td>
                            <td><?php echo $all_meta_for_user['company_name'][0];?></td>
                            <td><?php echo $all_meta_for_user['selfsignupstatus'][0];?></td>
                            <?php if(!empty($all_meta_for_user['user_profile_url'][0])){
                                
                                $imge_src = '<img src="'.$all_meta_for_user['user_profile_url'][0].'" width="100" />';
                             }else{
                                $imge_src = "";
                                
                            } ?>
                            <td><?php echo $imge_src;?></td>
                            <?php   
                                foreach ($additional_fields as $key=>$value){  if($additional_fields[$key]['name'] !='Notes' && $additional_fields[$key]['name'] !='Registration Codes'){?>
                                  <td><?php echo $all_meta_for_user[$additional_fields[$key]['key']][0];?></td>  
                                <?php } }?>
                            
                        </tr>
                       <?php }?>
                        
                </tbody>
            </table>
           </div>           
     </div>
    </div>

      
<?php
 
 include 'cm_footer.php';
 ?>
<script>
jQuery(document).ready(function() {
   jQuery('#selfsignusers').DataTable({
       
       "order": [[ 5, "desc" ]]
   });
} );


</script>  
<?php 
}else{
    
    $redirect = get_site_url();
    wp_redirect($redirect);
    exit;
}
?>