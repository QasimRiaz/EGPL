<?php
// Template Name: Bulk Edit Task 
if (current_user_can('administrator') || current_user_can('contentmanager')) {
    
     $mycurrentblogid = get_current_blog_id() ;
   
    
    include 'cm_left_menu_bar.php';
    include 'cm_header.php'; 
   global $wp_roles; 
   global $wpdb; 
   
   //$additional_fields_settings_key = 'EGPL_Settings_Additionalfield';
   //$additional_fields = get_option($additional_fields_settings_key);
   
   require_once plugin_dir_path( __DIR__ ) . 'includes/egpl-custome-functions.php';
   $GetAllcustomefields = new EGPLCustomeFunctions();
   
   $additional_fields = $GetAllcustomefields->getAllcustomefields();
      
   function sortByOrder($a, $b) {
            return $a['fieldIndex'] - $b['fieldIndex'];
      }

    usort($additional_fields, 'sortByOrder');
      
      
   $site_prefix = $wpdb->get_blog_prefix();
   $args['meta_query']['relation']= 'OR';
   $args['role__not_in']= 'Administrator';
   $sub_query['key']=$site_prefix.'selfsignupstatus';
   $sub_query['compare']='=';
   $sub_query['value']='Pending';
   array_push($args['meta_query'],$sub_query);
   $sub_query['key']=$site_prefix.'selfsignupstatus';
   $sub_query['compare']='=';
   $sub_query['value']='Declined';
   array_push($args['meta_query'],$sub_query);
   
   
   $user_query = new WP_User_Query( $args );
   $authors = $user_query->get_results();
  
   
   
   $get_all_roles =$wp_roles->roles;
 
   
   ?>


   <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Review Applicants</h3>

                        </div>
                    </div>
                </div>
            </header>
           <select id="assignuserroles" style="display: none;" >
                
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
                           
                                <?php   
                                foreach ($additional_fields as $key=>$value){  
                                    
                                    if($value['fieldsystemtask'] == "checked" && $value['displayonapplicationform'] == "checked" && $value['SystemfieldInternal'] != "checked" && $value['fieldName'] != "Level"){
                                        echo "<th>".$value['fieldName']."</th>";
                                    }
                                ?>
                                <?php }?>
                                <?php   
                                foreach ($additional_fields as $key=>$value){  
                                    
                                    if($value['fieldsystemtask'] != "checked" && $value['displayonapplicationform'] == "checked" && $value['SystemfieldInternal'] != "checked"  ){
                                        echo "<th>".$additional_fields[$key]['fieldName']."</th>";
                                    }
                                ?>
                               <?php }?>
                        </tr>
                </thead>
                <tbody>
                       <?php  foreach ($authors as $aid) {
                           
                            $user_data = get_userdata($aid->ID);
                            $all_meta_for_user = get_user_meta($aid->ID);
                            $user_blogs = get_blogs_of_user( $aid->ID );
                            $usergetaccessforthisblog = 'notactive';
                           
                            foreach ($user_blogs as $blog_id) { 
                                
                                if($blog_id->userblog_id == $mycurrentblogid){
                                   
                                    $usergetaccessforthisblog = 'active';
                                    break;
                                }
                            }
                           
                           if($usergetaccessforthisblog == 'active'){
                               
                            if($all_meta_for_user[$site_prefix.'selfsignupstatus'][0] == 'Declined'){
                                $column_row_action="";
                            }else{
                                $column_row_action= '<div style="width: 140px !important;"class = "hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><a onclick="approvethisuser(this)" id="' . $aid->ID . '" name="approve"   title="Approved" data-toggle="tooltip" ><i class="hi-icon fusion-li-icon fa fa-check-circle-o" ></i></a><a onclick="declinethisuser(this)" id="' . $aid->ID . '" name="delete-sponsor" data-toggle="tooltip"  title="Declined" ><i class="hi-icon fusion-li-icon fa fa-times-circle" ></i></a></div>';
                            }
                            
                           ?> 
                        <tr>
                            
                            
                            <td><?php echo $column_row_action;?></td>
                            
                            <?php   
                                foreach ($additional_fields as $key=>$value){  
                                    $additionalfieldkey = $value['fielduniquekey'];
                                    
                                    if($value['fieldsystemtask'] == "checked" && $value['SystemfieldInternal'] != "checked" && $value['fieldName'] != "Level" && $value['displayonapplicationform'] == "checked"){
                                         if($value['fieldName'] == "Email"){
                                             
                                             echo '<td>'.$user_data->user_email.'</td>';
                                         }elseif($value['fieldName'] == "Level" || $value['fieldName'] == "User ID" || $value['fieldName'] == "Action"  || $value['fieldName'] == "Last login" ){
                                             
                                               echo '<td>'.$all_meta_for_user[$additionalfieldkey][0].'</td>';
                                             
                                         }else{
                                             
                                             echo '<td>'.$all_meta_for_user[$site_prefix.$additionalfieldkey][0].'</td>';
                                             
                                         }
                                    }
                                    ?>
                                
                            <?php }?>
                            <?php   
                                foreach ($additional_fields as $key=>$value){  
                                    $additionalfieldkey = $value['fielduniquekey'];
                                    
                                    if($value['fieldsystemtask'] != "checked" && $value['SystemfieldInternal'] != "checked" && $value['displayonapplicationform'] == "checked" ){
                                        
                                            if($value['fieldType'] == "file"){
                                                $valueImage =  $all_meta_for_user[$site_prefix.$additionalfieldkey][0];
                                                
                                                
                                                
                                                if(!empty($valueImage)){
                                                    
                                                     echo "<td><a href='".$valueImage."' target='_blank' >Download</a></td>";
                                                    
                                                }else{
                                                    
                                                    echo "<td></td>";
                                                }
                                               
                                                
                                            }else{
                                                
                                                 echo '<td>'.$all_meta_for_user[$site_prefix.$additionalfieldkey][0].'</td>';
                                            }
                                             
                                            
                                             
                                        
                                    }
                                    ?>
                                
                            <?php }?>
                            
                        </tr>
                        
                        <?php } }?>
                        
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