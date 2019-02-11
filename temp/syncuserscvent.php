<?php

$timestamp = time();



if($_SERVER['REQUEST_METHOD'] == 'GET'){
    
    
    $lastInsertId = contentmanagerlogging('Cvent - '.$timestamp,"Admin Action",serialize($_SERVER),'GET',serialize($_GET),serialize($_POST));
    header("HTTP/1.0 200 OK");
    
}else{
    
    $json = file_get_contents('php://input');
    
    // $json = '{"message": [{"email": "testuser104@gmail.com", "title": "104", "gender": "", "prefix": "", "ccEmail": "", "company": "104", "homeFax": "", "workFax": "", "fullName": "Test user 104", "homeCity": "", "lastName": "104", "nickName": "", "optedOut": "no", "workCity": "City", "firstName": "Test user", "homePhone": "", "homeState": "", "workPhone": "1", "workState": "", "middleName": "", "accountStub": "2C04671F-DB08-40E5-BF26-B99501DF3A46", "contactStub": "8C850890-D2B2-4B50-9352-21890F02E411", "contactType": "Vendor", "dateOfBirth": "", "designation": "", "homeCountry": "", "homeZipcode": "", "mobilePhone": "+9232145698745", "updatedDate": "2018-08-30", "workCountry": "PAKISTAN", "workZipcode": "54000", "customFields": [{"id": "B812EE46-28BA-4DAA-A6ED-659E6B9E7060", "name": "Company name", "value": "Expogenie"}], "homeAddress1": "", "homeAddress2": "", "homeAddress3": "", "workAddress1": "Address", "workAddress2": "11", "workAddress3": "11", "homeStateCode": "", "workStateCode": "", "contactSourceId": "", "workCountryCode": "PK"}], "eventType": "ContactCreated", "messageStub": "9e331b67-ed4e-42d6-b2da-35a1f6d41684", "messageTime": "2018-08-30T02:14:06.090Z"}';
    $data = json_decode($json, true);
    $lastInsertId = contentmanagerlogging('Cvent - '.$timestamp,"Admin Action",serialize($_SERVER),'POST',serialize($_GET),serialize($json));

   

    try{
    
        if ($json !== false){
        
            $data = json_decode($json, true);
                
            if ($data !== null){
        
                   
                $lastInsertId = contentmanagerlogging('Cvent User',"Admin Action",serialize($data),'','',"pre_action_data");
                $username = $data['message'][0]['email'];
                $email = $data['message'][0]['email'];
                $meta_array['first_name']=$data['message'][0]['firstName'];
                $meta_array['last_name']=$data['message'][0]['lastName'];
                $meta_array['contactStub']=$data['message'][0]['contactStub'];
                $meta_array['company_name']=$data['message'][0]['company'];
                $meta_array['confirmationNumber']=$data['message'][0]['confirmationNumber'];
                $meta_array['inviteeStub']=$data['message'][0]['inviteeStub'];
                $meta_array['cventinviteeurl']='https://sandbox-www.cvent.com/events/Register/RegNumConfirmation.aspx?i='.$meta_array['inviteeStub'];
                
                
                
                
                
                $role = strtolower ($data['message'][0]['registrationType']) ;//$_POST['sponsorlevel'];
                $welcomeemailtemplatename = 'welcome_email_template';//$_POST['welcomeemailtempname'];
                $loggin_data=$data;
                $welcomeemail_status = 'send';//$_POST['welcomeemailstatus'];
                $user_id = username_exists($username);
                $message['username'] = $username;
                        
                        
                       // $profilepic=$_FILES['profilepic'];
                       // $picprofileurl = resource_file_upload($profilepic);


                        $oldvalues = get_option( 'ContenteManager_Settings' );

                    if (!$user_id and email_exists($email) == false) {

                       $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
                       $user_id = myregisterrequest_new_user($username, $email) ;//register_new_user( $username, $email );//wp_create_user($username, $random_password, $email);
                       if ( ! is_wp_error( $user_id ) ) {

                            $result=$user_id;
                            $loggin_data['created_id']=$result;
                            $message['user_id'] = $user_id;
                            $message['msg'] = 'User created';
                            $message['userrole'] = $role;
                           
                            //update_user_option($user_id, 'user_profile_url', $picprofileurl);
                            add_new_sponsor_metafields($user_id,$meta_array,$role);


                            add_user_to_blog(1, $user_id, $role);
                            if($welcomeemail_status == 'send'){
                                      $useremail='';
                                      custome_email_send($user_id,$useremail,$welcomeemailtemplatename);
                                      $t=time();
                                      update_user_option($user_id, 'convo_welcomeemail_datetime', $t*1000);
                                 }      
                            
                        }else{

                                   $userregister_responce = (array)$user_id;

                                   if(empty($userregister_responce['errors']['invalid_username'][0])){

                                           $message['msg'] = $userregister_responce['errors']['invalid_email'][0];
                                   }else{

                                           $message['msg'] = $userregister_responce['errors']['invalid_username'][0];
                                   }

                    } 
                    } else {


                        $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
                        $currentblogid = get_current_blog_id() ;
                        $user_blogs = get_blogs_of_user( $user_id );
                        $user_status_for_this_site = 'not_exist';
                        foreach ($user_blogs as $blog_id) { 

                               if($blog_id->userblog_id == $currentblogid ){

                                   $user_status_for_this_site = 'alreadyexist';
                                   break;
                               }

                        }
                    if($user_status_for_this_site == 'alreadyexist'){

                        $message['msg'] =  'User already exists for this site.';

                    }else{    

                        if (add_user_to_blog($blogid, $user_id, $role)) {
                                 add_user_to_blog(1, $user_id, $role);
                                $message['user_id'] = $user_id;
                                $message['msg'] = 'User created';
                                $message['userrole'] = $role;
                                
                                update_user_option($user_id, 'user_profile_url', $picprofileurl);
                                add_new_sponsor_metafields($user_id,$meta_array,$role);
                                if($welcomeemail_status == 'send'){
                                    $useremail='';
                                    custome_email_send($user_id,$email,$welcomeemailtemplatename);
                                    $t=time();
                                    update_user_option($user_id, 'convo_welcomeemail_datetime', $t*1000);
                                }      

                                $message['msg'] =  'User added to this blog.';

                            } else {

                                $message['msg'] = 'Failed to add user ' . $user_id . ' as ' . $role . ' to blog ' . $blogid . '.';
                            }
                        }
                    }

                    $loggin_data['msg']=$message['msg'];
                  
                    contentmanagerlogging_file_upload ($lastInsertId,serialize($loggin_data));
                    
                  // echo '<pre>';
                  // print_r($loggin_data);exit;
   
                
            }
    }
   }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    
    
    
    
    
}










