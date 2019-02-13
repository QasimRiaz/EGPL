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
           if(empty($taskkeyContent)){
           if(!empty($result_old)){
               
               
               foreach($result_old['profile_fields'] as $taskKey=>$taskObject){
                   
                   
                    
                        
                        $taskaObjectData = array(
                         'post_title'    => wp_strip_all_tags( $taskObject['label'] ),
                         'post_content'  => "",
                         'post_status'   => 'draft',
                         'post_author'   => $user_ID,
                         'post_type'=>'egpl_custome_tasks'
                     );
                        
                    $tasksID = wp_insert_post( $taskaObjectData );
                    
                    echo 'Task Name : '.$taskObject['label'].'<br>';
                    echo 'New Created Task ID   '.$tasksID.'<br>'; 
                    echo 'Task Type : '.$taskObject['type'].'<br>';
                    echo 'Task Key   '.$taskKey.'<br>';
                    echo '----------------------------------------------------';
                    $result_old['NewIDsCreateTAsks'][$tasksID] = $taskObject['label'];
                    
                    update_post_meta( $tasksID, 'value', $taskObject['value'] );
                    update_post_meta( $tasksID, 'unique', $taskObject['unique'] );
                    update_post_meta( $tasksID, 'class', $taskObject['class'] );
                    update_post_meta( $tasksID, 'after', $taskObject['after'] );
                    update_post_meta( $tasksID, 'required', $taskObject['required'] );
                    update_post_meta( $tasksID, 'allow_tags', $taskObject['allow_tags'] );
                    update_post_meta( $tasksID, 'add_to_profile', $taskObject['add_to_profile'] );
                    update_post_meta( $tasksID, 'allow_multi', $taskObject['allow_multi'] );
                    update_post_meta( $tasksID, 'label', $taskObject['label'] );
                    update_post_meta( $tasksID, 'type', $taskObject['type'] );
                    update_post_meta( $tasksID, 'link_url', $taskObject['lin_url'] );
                    update_post_meta( $tasksID, 'linkname', $taskObject['linkname'] );
                    update_post_meta( $tasksID, 'duedate', $taskObject['attrs'] );
                    update_post_meta( $tasksID, 'taskattrs', $taskObject['taskattrs'] );
                    update_post_meta( $tasksID, 'taskMWC', $taskObject['taskMWC'] );
                    update_post_meta( $tasksID, 'taskMWDDP', $taskObject['taskMWDDP'] );
                    update_post_meta( $tasksID, 'roles', $taskObject['roles'] );
                    update_post_meta( $tasksID, 'SystemTask', 0 );
                    
                    if(!empty($taskObject['usersids'])){
                        
                        
                        foreach ($taskObject['usersids'] as $userkey=>$userIndex){
                            
                             $newuserarray[]=$userIndex;
                            
                            
                        }
                        
                        
                        update_post_meta( $tasksID, 'usersids', $newuserarray );
                    }else{
                        update_post_meta( $tasksID, 'usersids', "" );
                    }
                    
                    if(!empty($taskObject['options'])){
                         foreach ($taskObject['options'] as $userkeyoptions=>$userIndexoptions){
                            
                             $options[]['label']=$userIndexoptions;
                             $options[]['value']=$userIndexoptions;
                             $options[]['state']=$userIndexoptions;
                            
                            
                        }
                        update_post_meta( $tasksID, 'options', $options );
                    }else{
                        update_post_meta( $tasksID, 'options', "" );
                    }
                    
                    update_post_meta( $tasksID, 'descrpition', $taskObject['descrpition'] );
                    update_post_meta( $tasksID, 'key', $taskKey );
                    
                    
                    
                   $counter++;
                   
                   
                   
               }
               
               echo 'Total Created Tasks   '.$counter.'<br>';
               $my_file = $currentsiteName.'.txt';
               $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
               fwrite($handle,  print_r($result_old, true));
               fclose($handle);
               
               
           }
           
                    }else{?></p>
                    <p>  All Tasks already moved successfully.... </p>
                    <?php }} ?>
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