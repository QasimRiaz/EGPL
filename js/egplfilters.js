

jQuery( document ).ready(function() {
            
            
            jQuery(".fusion-position-text").each(function(){
                
                
                var getquantity = jQuery(this).text().trim();
                console.log(getquantity);
                jQuery(this).empty();
                jQuery(this).append("No Longer Available");
                
            });
            
            jQuery("#avada_coupon_code").attr("placeholder","Discount Code");
            jQuery("#coupon_code").attr("placeholder","Discount Code");
            
            
            
});

