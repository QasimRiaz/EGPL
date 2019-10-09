<?php
class EGPLCustomeFunctions {
    
    
    
    
    
    public function __construct() {
    
        
        
    }
    
    
    function getAllcustomefields(){
    
      try{
          
          
        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_type'        => 'egpl_custome_fields',
            'post_status'      => 'draft',
	);
        $listOFcustomfieldsArray = get_posts( $args );
        
      
        
        
        foreach ($listOFcustomfieldsArray as $fieldskey => $fieldsObject) {
            
            $value = [];
            $value['fieldID'] = $fieldsObject->ID;
            $value['fieldName'] = get_post_meta( $value['fieldID'], 'label' , true);
            $value['fieldType'] = get_post_meta( $value['fieldID'], '_egpl_field_type' , true);
            $value['fieldTypeLinkurl'] = get_post_meta( $value['fieldID'], '_egpl_link_url' , true);
            $value['fieldTypeLinkname'] = get_post_meta( $value['fieldID'], '_egpl_link_name' , true);
            $value['fielddescription'] = get_post_meta( $value['fieldID'], '_egpl_field_description' , true);
            $value['fieldrequriedstatus']= get_post_meta( $value['fieldID'], '_egpl_field_requried_status' , true);
            $value['fieldplaceholder'] = get_post_meta( $value['fieldID'], '_egpl_field_placeholder', true);
            $value['displayonapplicationform'] = get_post_meta( $value['fieldID'], '_egpl_field_display_on_application_form', true);
            $value['fieldtooltiptext'] = get_post_meta( $value['fieldID'], '_egpl_field_tooltip_text', true);
            $value['fieldallwomultipul'] = get_post_meta( $value['fieldID'], '_egpl_field_allow_multi', true);
            $value['dropdownvalues'] = get_post_meta( $value['fieldID'], '_egpl_field_drop_down_values' , true);
            $value['fieldcode'] = get_post_meta( $value['fieldID'], '_egpl_field_code', true);
            $value['fielduniquekey'] = get_post_meta( $value['fieldID'], '_egpl_field_unique_key', true);
            $value['fieldsystemtask'] = get_post_meta( $value['fieldID'], '_egpl_field_system_task', true);
            $value['attribute'] = get_post_meta( $value['fieldID'], '_egpl_field_attribute', true);
            $value['fieldIndex'] = get_post_meta( $value['fieldID'], 'Indexfield', true);
            $value['options'] = get_post_meta( $value['fieldID'], 'options', true);
            $value['multiselect'] = get_post_meta( $value['fieldID'], 'multiselect', true);
            
            
            
            
            $value['SystemfieldInternal'] = get_post_meta( $value['fieldID'], '_egpl_field_internal_status', true);
            
            
            
            $completeFieldsDataArray[] = $value;
        }
        
        return $completeFieldsDataArray;
          
      } catch (Exception $ex) {
          

      }
    
}


}