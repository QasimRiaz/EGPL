<?php
// Template Name: Expo Genie Log
if (current_user_can('administrator') || current_user_can('contentmanager') ) { 
   
      //get_header();
		
     
     $sponsor_id = get_current_user_id(); 
     $roles = wp_get_current_user()->roles;
     $check= array_key_exists("contentmanager",$roles);
     
     
     
     
     $args = array(
	'posts_per_page'   => -1,
	'orderby'          => 'date',
	'order'            => 'ASC',
	'post_type'        => 'expo_genie_log',
	
	
    );
    $result = get_posts( $args );
    
     $base_url = "http://" . $_SERVER['SERVER_NAME'];
     // echo '<pre>';
    // print_r($result );exit;
                       
      global $wp_roles;
      $site_url  = get_site_url();
      $all_roles = $wp_roles->get_names();
      
     include 'cm_header.php';
       include 'cm_left_menu_bar.php';
      
     ?>
    

          <script>
        
            
        
        currentsiteurl = '<?php echo $site_url;?>';
        
        
    </script> 
    <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Admin Settings</h3>
                           
                        </div>
                    </div>
                </div>
            </header>

            <div class="box-typical box-typical-padding">
                
                
                <table id="example" class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%" style="width:100%">
                    <thead>
                        <tr>
                            <th>Log ID</th>
                            <th>Admin ID</th>
                            <th>Email</th>
                            <th>Action Name</th>
                            <th>Date&time</th>
                            <th>Type</th>
                            <th>Pre Action Data</th>
                            <th>Post Action Data</th>
                            <th>Browser Agent</th>
                            <th>IP</th>
                            <th>Current User Info</th>
                           
                               
                        </tr>
                    </thead>
                    <tbody>
                        
                       <?php foreach ($result as $taskskey => $tasksObject) {
                           
                            $logID=$tasksObject->ID;
                            $actiontype = get_post_meta( $logID, 'actiontype', true );
                            $preactiondata = get_post_meta( $logID, 'preactiondata', true );
                            $currentinfo = get_post_meta( $logID, 'currentuserinfo', true );
                            $useremail = get_post_meta( $logID, 'email', true );
                            $ipaddress = get_post_meta( $logID, 'ip', true );
                            if(empty($ipaddress)){
                                
                                 $ipaddress = get_post_meta( $logID, 'ip-address', true );
                                
                            }
                             if(empty($actiontype)){
                                
                                 $actiontype= get_post_meta( $logID, 'action-type-name', true );
                                
                            }
                            
                            $browseragent = get_post_meta( $logID, 'browseragent', true );
                            $finalresut = get_post_meta( $logID, 'result', true );
                            $LogID = '"'.$tasksObject->ID.'"';
                           
                           ?> 
                        <tr>
                            
                            
                            
                            
                            <td><?php echo $tasksObject->ID;?></td>
                            <td><?php echo $tasksObject->post_author;?></td>
                            <td><?php echo $useremail;?></td>
                            <td><?php echo $tasksObject->post_title;?></td>
                            <td><?php echo $tasksObject->post_date;?></td>
                            <td><?php echo $actiontype;?></td>
                            <td><?php echo "<a onclick='getpreactiondata(\"preactiondata\",$LogID)' >Pre Action Data...</a>"; ?></td>
                            <td><?php echo "<a onclick='getpreactiondata(\"postactiondata\",$LogID)' >Post Action Data...</a>"; ?></td>
                            <td><?php echo "<a onclick='getpreactiondata(\"browseragent\",$LogID)' >Browser Agent Information...</a>"; ?></td>
                            <td><?php echo $ipaddress;?></td>
                            <td><?php echo "<a onclick='getpreactiondata(\"userinfo\",$LogID)' >User Info ...</a>"; ?></td>
                        
                            
                            
                            
                        </tr>
                       <?php } ?>
                    </tbody>
                </table>
                
                
                
            </div>
     
            
            
     </div>
    </div>

<?php   include 'cm_footer.php'; ?>

  <script type="text/javascript" src="/wp-content/plugins/EGPL/js/expogenielog.js?v=2.59"></script>
 <script>
    
    jQuery(document).ready(function() {
     
    jQuery('#example').DataTable({
                                        
                                        aLengthMenu : [[100, 150, 200, -1], [100, 150, 200, "All"]],
                                        dom: 'fBrlptrfBrlp',
                                                 
                                                    buttons: [
                                                        {
                                                            extend: 'excelHtml5',
                                                            title: 'userreport_' + jQuery.now(),
                                                            exportOptions: {
                                                                columns: "thead th:not(.noExport)",
                                                                format: {
                                                                body: function ( data, row, column, node ) {
                                                                    
                                                                    
                                                                    
                                                                    var href = jQuery('<div>').append(data).find('a:first').attr('href');
                                                                    if(href !== undefined){
                                                                         data = href;
                                                                    }
                                                                    return  data;
                                                                    
                                                                }
        }
                                                            },
                                                        },
                                                        {
                                                            extend: 'csvHtml5',
                                                            title: 'userreport_' + jQuery.now(),
                                                            exportOptions: {
                                                                columns: "thead th:not(.noExport)",
                                                                format: {
                                                                body: function ( data, row, column, node ) {
                                                                    
                                                                    
                                                                    
                                                                    var href = jQuery('<div>').append(data).find('a:first').attr('href');
                                                                    if(href !== undefined){
                                                                         data = href;
                                                                    }
                                                                    return  data;
                                                                    
                                                                }
                                                            }
                                                            },
                                                        },

                                                        {
                                                            extend: 'print',
                                                            exportOptions: {
                                                                columns: "thead th:not(.noExport)",
                                                                 format: {
                                                                body: function ( data, row, column, node ) {
                                                                    
                                                                    
                                                                    
                                                                    var href = jQuery('<div>').append(data).find('a:first').attr('href');
                                                                    if(href !== undefined){
                                                                         data = href;
                                                                    }
                                                                    return  data;
                                                                    
                                                                }
                                                            }
                                                            }
                                                        }
                                                    ]
           
           
           
       });
    });  
    
    </script>
 
 
 <?php  }else{
        $redirect = get_site_url();
        wp_redirect( $redirect );exit;
   
   }?>