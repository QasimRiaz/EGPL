<?php
// Template Name: Bulk Edit Task 
if (current_user_can('administrator') || current_user_can('contentmanager')) {
    
    $user_reportsaved_list = get_option('ContenteManager_usersreport_settings');
    $get_email_template='AR_Contentmanager_Email_Template';
    $email_template_data = get_option($get_email_template);
    $content = "";
    $editor_id_bulk = 'bodytext';
    $oldvalues = get_option('ContenteManager_Settings');
    $formemail = $oldvalues['ContentManager']['formemail'];
    $base_url = get_site_url();
    
    $args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	'role'         => '',
	'role__in'     => array(),
	'role__not_in' => array(),
	'meta_key'     => '',
	'meta_value'   => '',
	'meta_compare' => '',
	'meta_query'   => array(),
	'date_query'   => array(),        
	'include'      => array(),
	'exclude'      => array(),
	'orderby'      => 'login',
	'order'        => 'ASC',
	'offset'       => '',
	'search'       => '',
	'number'       => '',
	'count_total'  => false,
	'fields'       => 'all',
	'who'          => '',
 ); 
    $numberofusers = get_users( $args );
   

    foreach ($numberofusers as $user=>$key){
    
        
        
        $getcurrentuserdata = get_user_meta($key->ID);
        
        echo '<pre>';
        print_r($getcurrentuserdata);
        
      
        
        
            
                
                $companyName = $getcurrentuserdata['company_name'][0];
                $first_name = $getcurrentuserdata['first_name'][0];
                $last_name = $getcurrentuserdata['last_name'][0];
                $convo_welcomeemail_datetime = $getcurrentuserdata['convo_welcomeemail_datetime'][0];
                $selfsignupstatus = $getcurrentuserdata['selfsignupstatus'][0];
                $user_profile_url = $getcurrentuserdata['user_profile_url'][0];
                $exhibitor_map_dynamics_ID = $getcurrentuserdata['exhibitor_map_dynamics_ID'][0];
                $prefix = $getcurrentuserdata['prefix'][0];
                $address_line_1 = $getcurrentuserdata['address_line_1'][0];
                $address_line_2 = $getcurrentuserdata['address_line_2'][0];
                $usercity = $getcurrentuserdata['usercity'][0];
                $userstate = $getcurrentuserdata['userstate'][0];
                $userzipcode = $getcurrentuserdata['userzipcode'][0];
                $usercountry = $getcurrentuserdata['usercountry'][0];
                $user_phone_1 = $getcurrentuserdata['user_phone_1'][0];
                $user_phone_2 = $getcurrentuserdata['user_phone_2'][0];
                $reg_codes = $getcurrentuserdata['reg_codes'][0];
                $usernotes = $getcurrentuserdata['usernotes'][0];


                update_user_option($key->ID,'company_name',$companyName);
                update_user_option($key->ID,'first_name',$first_name);
                update_user_option($key->ID,'last_name',$last_name);
                update_user_option($key->ID,'convo_welcomeemail_datetime',$convo_welcomeemail_datetime);
                update_user_option($key->ID,'selfsignupstatus',$selfsignupstatus);
                update_user_option($key->ID,'user_profile_url',$user_profile_url);
                update_user_option($key->ID,'exhibitor_map_dynamics_ID',$exhibitor_map_dynamics_ID);
                update_user_option($key->ID,'prefix',$prefix);
                update_user_option($key->ID,'address_line_1',$address_line_1);
                update_user_option($key->ID,'address_line_2',$address_line_2);
                update_user_option($key->ID,'usercity',$usercity);
                update_user_option($key->ID,'userzipcode',$userzipcode);
                update_user_option($key->ID,'usercountry',$usercountry);
                update_user_option($key->ID,'user_phone_1',$user_phone_1);
                update_user_option($key->ID,'user_phone_2',$user_phone_2);
                update_user_option($key->ID,'reg_codes',$reg_codes);
                update_user_option($key->ID,'usernotes',$usernotes);
        
                echo $key->email.'<br>';
      
        
         
        
        
        
    }
    
    
    
    
    

    
   
   
} else {
    $redirect = get_site_url();
    wp_redirect($redirect);
    exit;
}
?>