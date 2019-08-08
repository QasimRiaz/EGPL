

jQuery( document ).ready(function() {
            
            
            jQuery(".fusion-position-text").each(function(){
                
                
                var getquantity = jQuery(this).text().trim();
                console.log(getquantity);
                jQuery(this).empty();
                jQuery(this).append("No Longer Available");
                
            });
           
            
            
});

