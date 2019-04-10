<?php
class FloorPlanManager {
    
    
    
    
    
    public function __construct() {
    
        
        
    }
    
    
    public function getAllbooths(){
        
        $contentmanager_settings = get_option( 'ContenteManager_Settings' );
	$CurrentFloorPlanID = $contentmanager_settings['ContentManager']['floorplanactiveid'];
        $FloorplanXml   = get_post_meta( $CurrentFloorPlanID, 'floorplan_xml', true );
        $FloorplanXml = str_replace('"n<','<',$FloorplanXml);
        $FloorplanXml= str_replace('>n"','>',$FloorplanXml);
        $xml=simplexml_load_string($FloorplanXml) or die("Error: Cannot create object");
        $counter= 0;
        $xml = json_decode(json_encode($xml), TRUE);
        
        foreach ($xml['root']['MyNode'] as $cellIndex=>$CellValue){
            
           
            
            $cellboothlabelvalue = $CellValue['@attributes'];
            $GetAllBoothsWithUsers[$counter]['boothNumber']   = $cellboothlabelvalue['mylabel'];
            $GetAllBoothsWithUsers[$counter]['bootheOwnerID'] = $cellboothlabelvalue['boothOwner'];
            $GetAllBoothsWithUsers[$counter]['bootheID'] = $cellboothlabelvalue['id'];
            $counter++;
           
            
        }
        
        return $GetAllBoothsWithUsers;
    }
    
    
    function createAllBoothPorducts($floorplanID,$requestBoothsproductArray, $UpdatedFloorplanXml,$productpicID){
    
    
    try{
	
       
        
      
        
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $FloorplanXml = stripslashes($UpdatedFloorplanXml);
        $requestBoothsproductArray = $requestBoothsproductArray;
        $lastInsertId = floorplan_contentmanagerlogging('AutoGenrate Booth Products Request',"Admin Action",unserialize($FloorplanXml),$user_ID,$user_info->user_email,"");
      
        
        $FloorplanXml = str_replace('"n<','<',$FloorplanXml);
        $FloorplanXml= str_replace('>n"','>',$FloorplanXml);
        
        $requestBoothsproductArray = json_decode(stripslashes($requestBoothsproductArray));
        $xml=simplexml_load_string($FloorplanXml) or die("Error: Cannot create object");
        
      
       
        $currentIndex = 0;
        $att = "boothproductid";
        $NewProductArrayIndex = 0;
        $NewProductArray = [];
        
        
          
       
        
        foreach ($xml->root->MyNode as $cellIndex=>$CellValue){
            
            
          
       
                    $new_product_id = "";
                    $cellboothlabelvalue = $CellValue->attributes();
                    $getCellStylevalue = $xml->root->MyNode[$currentIndex]->mxCell->attributes();
                    $boothid = $cellboothlabelvalue['id'];
                    
                    
                    
                    foreach ($requestBoothsproductArray as $boothIndex=>$boothObject){
                    
                        
                    
                    $newRequestBoothArray = new stdClass;
                        
                    if($boothObject->cellID == $boothid){
                        
                       
                        
                        $newRequestBoothArray->cellID=$boothObject->cellID;;
                        $newRequestBoothArray->boothdescripition=$boothObject->boothdescripition;
                        $newRequestBoothArray->boothprice=$boothObject->boothprice;
                        $newRequestBoothArray->boothlevel=$boothObject->boothlevel;
                        $newRequestBoothArray->boothtitle=$boothObject->boothtitle;
                      
                         
                         
                        
                     if($boothObject->boothstatus == "newBooth"){
                
                            $objProduct = new WC_Product();
                            $objProduct->set_slug($boothObject->cellID);
                            $objProduct->set_name($boothObject->boothtitle); 

                            $objProduct->set_status('publish'); //Set product status.
                            $objProduct->set_featured(TRUE); //Set if the product is featured.                          | bool
                            $objProduct->set_catalog_visibility('visible'); //Set catalog visibility.                   | string $visibility Options: 'hidden', 'visible', 'search' and 'catalog'.
                            $objProduct->set_description($boothObject->boothdescripition); //Set product description.
                            $objProduct->set_short_description($boothObject->boothdescripition); //Set product short description.

                            $objProduct->set_price($boothObject->boothprice); //Set the product's active price.
                            $objProduct->set_regular_price($boothObject->boothprice); //Set the product's regular price.

                            $objProduct->set_manage_stock(TRUE); //Set if product manage stock.                         | bool
                            $objProduct->set_stock_quantity(1); //Set number of items available for sale.
                            $objProduct->set_stock_status('instock'); //Set stock status.                               | string $status 'instock', 'outofstock' and 'onbackorder'
                            $objProduct->set_backorders('no'); //Set backorders.                                        | string $backorders Options: 'yes', 'no' or 'notify'.
                            $objProduct->set_sold_individually(FALSE);
                           // $objProduct->set_tax_class(); 
                            $objProduct->set_image_id($productpicID); //Set main image ID.
                            //  $objProduct->set_menu_order($menu_order); 
                            $objProduct->update_meta_data('productlevel', $boothObject->boothlevel);
                            $objProduct->set_reviews_allowed(TRUE); //Set if reviews is allowed.                        | bool
                            $new_product_id = $objProduct->save(); //Saving the data to create new product, it will return product ID.
                           
                           

                            $newRequestBoothArray->boothstatus = 'updated';
                            $newRequestBoothArray->boothID = $new_product_id;
                            $NewProductArray[$NewProductArrayIndex] = $newRequestBoothArray;
                            $NewProductArrayIndex++;

                        }else if($boothObject->boothstatus == "updated"){
                            
                            
                            $objProduct = wc_get_product( $boothObject->boothID );
                            $objProduct->set_name($boothObject->boothtitle); 
                            $objProduct->set_stock_quantity(1); //Set number of items available for sale.
                            $objProduct->set_description($boothObject->boothdescripition); //Set product description.
                            $objProduct->set_short_description($boothObject->boothdescripition); //Set product short description.
                            $objProduct->set_price($boothObject->boothprice); //Set the product's active price.
                            $objProduct->set_regular_price($boothObject->boothprice); //Set the product's regular price.
                            $objProduct->update_meta_data('productlevel', $boothObject->boothlevel);
                           // $objProduct->set_tax_class($boothObject->boothlevel); 
                            $objProduct->set_image_id($productpicID); //Set main image ID.
                            $new_product_id = $objProduct->save();
                            
                           
                            $newRequestBoothArray->boothstatus = 'updated';
                            $newRequestBoothArray->boothID = $new_product_id;
                            $NewProductArray[$NewProductArrayIndex] = $newRequestBoothArray;
                            $NewProductArrayIndex++;

                        }else if($boothObject->boothstatus == "deleterequest"){

                            wp_delete_post( $boothObject->boothID, true );
                            $new_product_id = "";

                        }
                
                        if($boothid == $boothObject->cellID){
                             $xml->root->MyNode[$currentIndex]->attributes()->$att = $new_product_id;  
                        }
                     }
                    }
                     
                    $currentIndex++;
                   
                }
        
      
      
        
        if(!empty($NewProductArray)){
            
            
            $NewProductArray = json_encode($NewProductArray);
            
        }else{
            
            $NewProductArray = "";
        }
        update_post_meta( $floorplanID, 'sellboothsjson', $NewProductArray );
        
        
        $FloorplanXml = str_replace('<?xml version="1.0"?>',"",$xml->asXML());
        
        $FloorplanXml = str_replace('"n<','<',$FloorplanXml);
        $FloorplanXml = str_replace('>n"','>',$FloorplanXml);
        
        update_post_meta( $floorplanID, 'floorplan_xml', json_encode($FloorplanXml));
        
       
        
        contentmanagerlogging_file_upload ($lastInsertId,serialize($FloorplanXml));
        
       
       echo 'createdAllboothsProducts';
       exit;
        
        
        
        
        
    }catch (Exception $e) {
       
     
        return $e;
        
    }
  }

}