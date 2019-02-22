<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
    
       
       $user_ID = get_current_user_id();
                
              
       
        include 'cm_header.php';
        include 'cm_left_menu_bar.php';
       
        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_type'        => 'egpl_custome_tasks',
            'post_status'      => 'draft',

        );
        $taskkeyContent = get_posts( $args );
       
                ?>
       
   <div class="page-content">
        <div class="container-fluid">
            <header class="section-header" id="bulkimport">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3><?php echo $product_name_for_fields_lebal;?></h3>
                           
                        </div>
                    </div>
                </div>
            </header>
            

            <div class="box-typical box-typical-padding" >
                <div class="form-group row">
                
                    <p class="col-sm-12 "><?php if($_GET['tasksstatus'] == "moved"){
         
           
           $test = 'custome_task_manager_data';
           $result_old = get_option($test);
           $currentsiteURL = get_current_site();
           $currentsiteName = get_option( 'blogname' );
           $counter = 0;
          
           if(!empty($result_old)){
               
               
               foreach($result_old['profile_fields'] as $taskKey=>$taskObject){
                   
                   
                    
                        
                        
                        foreach ($taskkeyContent as $NewtaskKey=>$NEwtaskObject){
                             $newuserarray = [];
                             $tasksID = $NEwtaskObject->ID;
                             $value_key = get_post_meta( $tasksID, 'key', false);
                             if($taskKey == $value_key[0]){
                             if(!empty($taskObject['usersids'])){
                        
                        
                                foreach ($taskObject['usersids'] as $userkey=>$userIndex){

                                     $newuserarray[]=$userIndex;


                                }


                                update_post_meta( $tasksID, 'usersids', $newuserarray );
                            }else{
                                update_post_meta( $tasksID, 'usersids', "" );
                            }
                            }
                        }
                    }
               
               
               
               
           }}
           
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php include 'cm_footer.php'; ?>

   
        
        
   <?php }else{
       $redirect = get_site_url();
    wp_redirect( $redirect );exit;
   
   }
   ?>