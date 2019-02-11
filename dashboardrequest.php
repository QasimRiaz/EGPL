<?php


if ($_GET['dashboardRequest'] == 'getdashboarddailygraph') {
    
    require_once('../../../wp-load.php');
    getdashboarddailygraph($_POST);
     
    
}else if($_GET['dashboardRequest'] == 'getdashboardactiveusergraph'){
    
    require_once('../../../wp-load.php');
    getdashboardactiveusergraph();
    
}else if($_GET['dashboardRequest'] == 'gettaskstatusbardata'){
    
    require_once('../../../wp-load.php');
    gettaskstatusbardata();
    
}

 

 function gettaskstatusbardata() {

    require_once('../../../wp-load.php');

    try {
        
      // $get_all_roles_array = 'wp_user_roles';
      // $get_all_roles = get_option($get_all_roles_array);
       
      // echo '<pre>';
     //  print_r($get_all_roles);exit;
       
       
        $test = 'custome_task_manager_data';
        $result_task_array_list = get_option($test);
        $statscolcount = 0;
        $divheight = 645;
        $scroll = 'disable';
        
        foreach ($result_task_array_list['profile_fields'] as $key => $value) {
            
            if (strpos($key, "task") !== false) {
                if (strpos($value['label'], 'Status') !== false || strpos($value['label'], 'Date-Time') !== false) {
                    
                } else {
                    $arrDates[] = array($key => $value['attrs']);
                }
            }
        }

        $html_task_due_soon = "";
        $flat = array_reduce($arrDates, 'array_merge', array());
        uasort($flat, "cmp");
        $duetaskcount = 0;
        $user_pie_chart_stats = count_users();
        
      
       
        foreach ($flat as $index => $taskdate) {
            
             
            
             $get_thistask_roles = $result_task_array_list['profile_fields'][$index]['roles'];
             $totaltaskcount = count($result_task_array_list['profile_fields'][$index]['usersids']);
             
             foreach ($get_thistask_roles as $index_key=>$rolename){
                 
                 if($rolename == 'all'){
                     $taskassignmentcount_singlerole = intval($user_pie_chart_stats['total_users']);
                     $totaltaskcount = $taskassignmentcount_singlerole; 
                 }else{
                   $taskassignmentcount_singlerole = intval($user_pie_chart_stats['avail_roles'][$rolename]);
                   $totaltaskcount = $totaltaskcount + $taskassignmentcount_singlerole;  
                 }
                 
                
             }
             
            
             if($statscolcount > 35 ){
                   $divheight = $divheight + 15;
                   $scroll = 'enable';
                }
            
                $get_taskstatus_complete = array(
                    'role__not_in' => 'Administrator',
                    'meta_query' => array(
                        'relation' => 'AND',
                        array(
                            'key' => $index.'_status',
                            'value' => 'Complete', // date to compare to, after this one
                            'compare' => '=',
                        )
                    )
                );
                
                
                
                
                
                $taskstatusdata_complete = new WP_User_Query( $get_taskstatus_complete );
                $thistask_complete_total = $taskstatusdata_complete->get_results();
                
                
                
                
                $task_columnsname_array[]= $result_task_array_list['profile_fields'][$index]['label'].' Status';
                
                $task_pending_total_task_array['color'] = '#F5F5F5';
                $task_pending_total_task_array['data'][] = $totaltaskcount - count($thistask_complete_total);
                $task_pending_total_task_array['name'] = 'Pending';
                
                $task_complete_total_task_array['color'] = '#7cb5ec';
                $task_complete_total_task_array['data'][] = count($thistask_complete_total);
                $task_complete_total_task_array['name'] = 'Complete';
                
               

            $time = strtotime($taskdate);
            $currenttime = strtotime(date('Y-m-d'));                                      //echo $index;
            //  echo $taskdate;
            if ($time >= $currenttime) {
                $html_task_due_soon .= '<tr><td>' . $result_task_array_list['profile_fields'][$index]['label'] . '</td><td nowrap align="center"><span class="semibold">' . $taskdate . '</span></td></tr>';
                $duetaskcount++;
            }
        }

        if ($duetaskcount == 0) {
            $html_task_due_soon .= 'No Task Due Soon.';
        }

        $totalcount_complet_pending[0]=$task_pending_total_task_array;
        $totalcount_complet_pending[1]=$task_complete_total_task_array;
        
        $taskstatus_graph_data['columnnames'] = $task_columnsname_array;
        $taskstatus_graph_data['graphstats'] = $totalcount_complet_pending;
        $taskstatus_graph_data['divheight'] = $divheight;
        $taskstatus_graph_data['scrollstatus'] = $scroll;

        
       
        
        
        echo json_encode($taskstatus_graph_data) .'//'. json_encode($html_task_due_soon);
    
    
    } catch (Exception $e) {
        return $e;
    }

    die();
}
function getdashboardactiveusergraph() {

    require_once('../../../wp-load.php');

    try {
        
        global $wpdb;
        $site_prefix = $wpdb->get_blog_prefix();
        $custome_login_date_time_key = $site_prefix.'custom_login_time_as_site';
        
        $args_activeuser = array(
            'role__not_in'=>'Administrator',
            'meta_query' => array(
                'relation'=>'AND',
                array(
                    'key' => $custome_login_date_time_key,//'wp_user_login_date_time',
                    'value' => '', // date to compare to, after this one
                    'compare' => '!=',
                    
                )
            )
        );
        $args_ttoaluser = array(
            'role__not_in'=>'Administrator',
            
        );
        
        $user_pie_chart_stats = count_users();
        
        
        if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }

      //  echo     $get_all_roles_array;exit;
             //   echo '<pre>';
       // print_r($user_pie_chart_stats);
  
        $get_all_roles = get_option($get_all_roles_array);
        $count_array = 0;
        
        foreach ($user_pie_chart_stats['avail_roles'] as $rolename=>$rolecount){
            if($rolename!='none' &&  $rolename != 'administrator' && $rolename != 'contentmanager' && !empty($rolename) && $rolename != 'subscriber'){
               if($get_all_roles[$rolename]['name'] != null){
                   
               //    echo     $get_all_roles[$rolename]['name'];
        
                   $piechartdata[$count_array]['name'] = $get_all_roles[$rolename]['name'];
                $piechartdata[$count_array]['y'] = $rolecount;
                $count_array++;
               }
            }
        }
       // echo '<pre>';
       // print_r($piechartdata);
        $picchartgetarraylist['totalroles'] = count($user_pie_chart_stats['avail_roles'])-2;
        $picchartgetarraylist['rolesdata'] = $piechartdata;
        
        
        
        $active_user_query = new WP_User_Query( $args_activeuser );
        $totaluserdata_query = new WP_User_Query( $args_ttoaluser );
        $totaluserdata = $totaluserdata_query->get_results();
        $authors_active = $active_user_query->get_results();
       
        
       
        $result_array['totaluser']=count($totaluserdata);
        $result_array['activeuser']=count($authors_active);
        
        echo json_encode($result_array).'//'.json_encode($picchartgetarraylist);
    
    
    } catch (Exception $e) {
        return $e;
    }

    die();
}
function getdashboarddailygraph($data) {

    require_once('../../../wp-load.php');

    try {
        
        $usertimezone = -5;//$data['usertimezone'];
        global $wpdb;
        $site_prefix = $wpdb->get_blog_prefix();
        $custome_login_date_time_key = $site_prefix.'custom_login_time_as_site';
        
        
        $start_date = date('d-M-Y');
        $end_date   = date('d-M-Y', strtotime("-6 days"));
        
       
        for ($x = 0; $x <= 6; $x++) {
           $totaldatesarray[] =  date('d-M-Y', strtotime("-".$x." days"));
        }
        usort($totaldatesarray, "date_sort");
        $args = array(
            'role__not_in'=>'Administrator',
            'meta_query' => array(
                'relation'=>'AND',
                array(
                    'key' => $custome_login_date_time_key,//'wp_user_login_date_time',
                    'value' => array( strtotime($end_date.' 00:00'), strtotime($start_date.' 23:59') ), // date to compare to, after this one
                    'compare' => 'BETWEEN',
                    
                )
            )
        );
        $user_query = new WP_User_Query( $args );
        $authors = $user_query->get_results();
        
        foreach ($authors as $aid) {
             
             $user_data = get_userdata($aid->ID);
             $all_meta_for_user = get_user_meta($aid->ID);
             $user_last_login[] = date('d-M-Y', $all_meta_for_user[$custome_login_date_time_key][0]);
        }
        $occurences = array_count_values($user_last_login);
         foreach ($totaldatesarray as $datekeys) {
             
             if(array_key_exists($datekeys,$occurences)){
                
                 $result_array [] = $occurences[$datekeys];
             }else{
                 $result_array [] = 0;
             }
             
         }
        echo json_encode($result_array);
    
    
    } catch (Exception $e) {
        return $e;
    }

    die();
}

function date_sort($a, $b) {
    return strtotime($a) - strtotime($b);
}