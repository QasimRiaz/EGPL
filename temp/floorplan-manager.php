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
    
    
    function createAllBoothPorducts($requestBoothsproductArray, $UpdatedFloorplanXml){
    
    
    try{
	
        
        
      
        
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $FloorplanXml = stripslashes($UpdatedFloorplanXml);
        $requestBoothsproductArray = $requestBoothsproductArray;
        $lastInsertId = floorplan_contentmanagerlogging('AutoGenrate Booth Products Request',"Admin Action",unserialize($FloorplanXml),$user_ID,$user_info->user_email,"");
      
        
        $FloorplanXml = str_replace('"n<','<',$FloorplanXml);
        $FloorplanXml= str_replace('>n"','>',$FloorplanXml);
        
        $requestBoothsproductArray = json_decode(stripslashes($requestBoothsproductArray));
        
   
        
        foreach ($requestBoothsproductArray as $boothIndex=>$boothObject){
            
            
            
            echo '<pre>';
            print_r($boothObject);exit;
            
            
            
            
        }
       
        
        
        
        
        $xml=simplexml_load_string($FloorplanXml) or die("Error: Cannot create object");
        $currentIndex = 0;
        
        $att = "boothproductid";
        
       
       
        
        
        
        foreach ($xml->root->MyNode as $cellIndex=>$CellValue){
            
          
        
          
            $cellboothlabelvalue = $CellValue->attributes();
            $getCellStylevalue = $xml->root->MyNode[$currentIndex]->mxCell->attributes();
            $createdproductLevel = "";
            
            
            $boothtitle = $cellboothlabelvalue['mylabel'];
            $boothid = $cellboothlabelvalue['id'];
           
            
            $prdoucttitlepostfix = " - ";
            if(!empty($boothtitle) && $boothtitle !=""){
                
                $prdoucttitlepostfix.=$boothtitle;
            }else{
                $prdoucttitlepostfix.=$boothid;
            }
            
           
            
            
        if((!isset($cellboothlabelvalue['boothOwner']) || $cellboothlabelvalue['boothOwner'] == "none") &&  (!isset($cellboothlabelvalue['boothproductid']) || $cellboothlabelvalue['boothproductid'] == "none")){    
            
          
               
                if(isset($cellboothlabelvalue['pricetegid']) && !empty($cellboothlabelvalue['pricetegid'])){
                    
                   
                    $getpricetegID = $cellboothlabelvalue['pricetegid'];
                    
                    
                    foreach ($boothTypesLegend as $boothlabelIndex=>$boothlabelValue){
                        if($boothlabelValue->ID ==  $getpricetegID){
                            
                            $createdproductPrice = $boothlabelValue->price;
                            $createdproductName = $boothlabelValue->name;
                            $createdproductLevel = $boothlabelValue->level;
                            
                            
                        }
                    }
                
                    
                    
                    
                    
                   
                    
                    
                    
                    
                    
                
                $objProduct = new WC_Product();
                
                
                
                
                $objProduct->set_slug($cellboothlabelvalue['id']);
                $objProduct->set_name($createdproductName.$prdoucttitlepostfix); 
                
                $objProduct->set_status('publish'); //Set product status.
                $objProduct->set_featured(TRUE); //Set if the product is featured.                          | bool
                $objProduct->set_catalog_visibility('visible'); //Set catalog visibility.                   | string $visibility Options: 'hidden', 'visible', 'search' and 'catalog'.
                $objProduct->set_description(''); //Set product description.
                $objProduct->set_short_description(''); //Set product short description.

                $objProduct->set_price($createdproductPrice); //Set the product's active price.
                $objProduct->set_regular_price($createdproductPrice); //Set the product's regular price.

                $objProduct->set_manage_stock(TRUE); //Set if product manage stock.                         | bool
                $objProduct->set_stock_quantity(1); //Set number of items available for sale.
                $objProduct->set_stock_status('instock'); //Set stock status.                               | string $status 'instock', 'outofstock' and 'onbackorder'
                $objProduct->set_backorders('no'); //Set backorders.                                        | string $backorders Options: 'yes', 'no' or 'notify'.
                $objProduct->set_sold_individually(FALSE);
                $objProduct->set_tax_class($createdproductLevel); 
             
                //  $objProduct->set_menu_order($menu_order); 

                $objProduct->set_reviews_allowed(TRUE); //Set if reviews is allowed.                        | bool

                $term_ids =[$catID];
                $objProduct->set_category_ids($term_ids); //Set the product categories.                   | array $term_ids List of terms IDs.
                $objProduct->set_tag_ids($term_ids); //Set the product tags.                              | array $term_ids List of terms IDs.
               // $objProduct->set_image_id($productpicrul); //Set main image ID.                                         | int|string $image_id Product image id.
                //Set gallery attachment ids.                       | array $image_ids List of image ids.
                $new_product_id = $objProduct->save(); //Saving the data to create new product, it will return product ID.

                $xml->root->MyNode[$currentIndex]->attributes()->$att = $new_product_id;   
                    
                    
                    
                }
           
                
               
            
        }   
        $currentIndex++;
        
    
    
        }
        
        $FloorplanXml = str_replace('<?xml version="1.0"?>',"",$xml->asXML());
        
        $FloorplanXml = str_replace('"n<','<',$FloorplanXml);
        $FloorplanXml = str_replace('>n"','>',$FloorplanXml);
        
        
        //echo '<pre>';
        //print_r($FloorplanXml);exit;
        
        
        update_post_meta( $_REQUEST['post_id'], 'floorplan_xml', json_encode($FloorplanXml));
        
        contentmanagerlogging_file_upload ($lastInsertId,serialize($FloorplanXml));
        
       
       echo 'updated';
       exit;
        
        
        
        
        
    }catch (Exception $e) {
       
     
        return $e;
        
    }
  }

}