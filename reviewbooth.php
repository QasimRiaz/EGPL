<?php


if ($_GET['contentManagerRequest'] == 'approve_booth') {
    
    require_once('../../../wp-load.php');
    approveboothforthisuser($_POST);
    die();
    
}else if ($_GET['contentManagerRequest'] == 'decline_booth') {
    
    require_once('../../../wp-load.php');
    declineboothforthisuser($_POST);
    die();
    
}

function approveboothforthisuser($postID){
    
     try{
      
        
         $woocommerce_rest_api_keys = get_option( 'ContenteManager_Settings' );
         $wooconsumerkey = $woocommerce_rest_api_keys['ContentManager']['wooconsumerkey'];
         $wooseceretkey = $woocommerce_rest_api_keys['ContentManager']['wooseceretkey'];
         require_once( 'temp/lib/woocommerce-api.php' );
         $url = get_site_url();
         $options = array(
            'debug'           => true,
            'return_as_array' => false,
            'validate_url'    => false,
            'timeout'         => 30,
            'ssl_verify'      => false,
        );
       
       
       $client = new WC_API_Client( $url, $wooconsumerkey, $wooseceretkey, $options );
       $product_cat_list = $client->products->get_categories() ;
         
        $order = wc_get_order( $postID['orderID'] );
        $items = $order->get_items();
        
        foreach ( $items as $item ) {
            
            $product_name = $item->get_name();
            $product_id = $item->get_product_id();
            $update_product = wc_get_product( $product_id );
            foreach ($product_cat_list->product_categories as $cat_key=>$cat_value){
           
           
                if($cat_value->id == $update_product->category_ids[0]){
                    
                    $selectedcat_name = $cat_value->name;
                    if($selectedcat_name == "Booths"){
                    
                        $seletectProductID = $product_id;
                        break;
                    }
                    
                    
                }
           
           
                }
        }
        
        
       
        
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('Approved Booth',"Admin Action",serialize($postID),''.$user_ID,$user_info->user_email,"pre_action_data");
       
        
        $OrderUserID = get_post_meta( $postID['postid'], 'OrderUserID', true );
        $contentmanager_settings = get_option( 'ContenteManager_Settings' );
        $id = $contentmanager_settings['ContentManager']['floorplanactiveid'];
        $boothTypesLegend = json_decode(get_post_meta($_REQUEST['post_id'], 'legendlabels', true ));
        
        $FloorplanXml = get_post_meta( $id, 'floorplan_xml', true );
        $FloorplanXml = str_replace('"n<','<',$FloorplanXml);

        $FloorplanXml= str_replace('>n"','>',$FloorplanXml);
        $xml=simplexml_load_string($FloorplanXml) or die("Error: Cannot create object");
        $currentIndex = 0;
        foreach ($xml->root->MyNode as $cellIndex=>$CellValue){
            
           
            $cellboothlabelvalue = $CellValue->attributes();
            $getCellStylevalue = $xml->root->MyNode[$currentIndex]->mxCell->attributes();
            
            if($cellboothlabelvalue['boothproductid'] == $seletectProductID){
                
               
                $att = "boothOwner";
                $styleatt = 'style';
                $xml->root->MyNode[$currentIndex]->attributes()->$att = $OrderUserID;
                
                $getCellStyle = $getCellStylevalue['style'];
                
                
                if(isset($cellboothlabelvalue['legendlabels']) && !empty($cellboothlabelvalue['legendlabels'])){
                    
                    
                    $getlabelID = $cellboothlabelvalue['legendlabels'];
                    
                    foreach ($boothTypesLegend as $boothlabelIndex=>$boothlabelValue){
                        if($boothlabelValue->ID ==  $getlabelID){
                            
                            $createdproductPrice = $boothlabelValue->colorcode;
                            if($createdproductPrice != "none"){
                                
                                $NewfillColor = $createdproductPrice;
                                
                            }else{
                                $getCellStyleArray = explode(';',$getCellStyle);
                                foreach ($getCellStyleArray as $styleIndex=>$styleValue){


                                    if($styleValue != 'DefaultStyle1'){

                                        $styledeepCheck = explode('=',$styleValue);

                                        if($styledeepCheck[0] == 'occ'){

                                            $NewfillColor = $styledeepCheck[1];

                                        }else if($styledeepCheck[0] == 'fillColor'){

                                            $oldfillcolortext = $styleValue;
                                        }


                                    }


                                }
                                
                            }
                            
                            
                        }
                    }
                }else{
                    
                        $getCellStyleArray = explode(';',$getCellStyle);
                        foreach ($getCellStyleArray as $styleIndex=>$styleValue){


                            if($styleValue != 'DefaultStyle1'){

                                $styledeepCheck = explode('=',$styleValue);

                                if($styledeepCheck[0] == 'occ'){

                                    $NewfillColor = $styledeepCheck[1];

                                }else if($styledeepCheck[0] == 'fillColor'){

                                    $oldfillcolortext = $styleValue;
                                }


                            }


                        }
                }
                
                
                $getCellStyle = str_replace($oldfillcolortext,'fillColor='.$NewfillColor,$getCellStyle);
                $xml->root->MyNode[$currentIndex]->mxCell->attributes()->$styleatt = $getCellStyle;
                
                 
            }
            $currentIndex++;
    
        }
        
        $getresultforupdat = str_replace('<?xml version="1.0"?>',"",$xml->asXML());
        
        update_post_meta( $id, 'floorplan_xml', json_encode($getresultforupdat));
        $result =  update_post_meta( $postID['postid'], 'boothStatus', 'Completed' ); 
        
        
        
       
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize('updated successfully'));
        
        
       
        die();
     }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
       die();
     }
    
    
    
}

function declineboothforthisuser($postID){
    
    try{
      
        
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('Declined Booth',"Admin Action",serialize($postID),''.$user_ID,$user_info->user_email,"pre_action_data");
        $OrderUserID = get_post_meta($postID['id'], 'OrderUserID', true);
        $result      = update_post_meta($postID['id'], 'boothStatus', 'Declined');
        contentmanagerlogging_file_upload($lastInsertId,serialize('updated successfully'));
        
        echo 'statusupdated';
        die();
       
     }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
        return $e;
        die();
      
     }
    
    
    
}