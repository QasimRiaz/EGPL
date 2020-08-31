
function getOrderproductdetail(OrderID,remaningamount){


    jQuery("body").css("cursor", "progress");
    var url = currentsiteurl+'/';
    
    
    
    
   
    var urlnew = url + 'wp-content/plugins/EGPL/orderreport.php?floorplanRequest=getOrderProductsdetails';
    var data = new FormData();



    data.append('ID', OrderID);
    data.append('remaningamount', remaningamount);
    
    
    jQuery.ajax({
        url: urlnew,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(data) {

            var message = jQuery.parseJSON(data);
            jQuery("body").css("cursor", "default");
          
        swal({
            
            title: 'Products in Order ID #'+OrderID,
            text:message,
            confirmButtonText: "Close",
            customClass: "customewidthsweet",
            html:true
         });
         
         
      
           


        }, error: function(xhr, ajaxOptions, thrownError) {
            
             swal({
					title: "Error",
					text: "There was an error during the requested operation. Please try again.",
					type: "error",
					confirmButtonClass: "btn-danger",
					confirmButtonText: "Ok"
				});
            
          
        }
    });
}
jQuery( document ).ready(function() {
   
                
                
                
            var getquantity = jQuery(".product-quantity").text().trim();
            if (getquantity.indexOf(" 0") >= 0){
                 console.log(getquantity)
                jQuery(".product-quantity").empty();
                
            }
            
            jQuery("#completedOrderIcon").click(function() {
            
               var classname = jQuery("#completedOrderIconicon").attr("class");
               var value = "up";
               if(classname.indexOf(value) != -1){
                   
                   var res = classname.replace("up", "down");  
                   
               }else{
                   
                  var res = classname.replace("down", "up");  
                   
               }
               
               
         
                jQuery("#completedOrderIconicon").removeClass(classname);
                jQuery("#completedOrderIconicon").attr("class",res);
                
               
              
            
            
            
            });
            jQuery("#OpenOrderIcon").click(function() {
                
                var classname = jQuery("#OpenOrderIconicon").attr("class");
                var value = "up";
                if(classname.indexOf(value) != -1){
                   
                   var res = classname.replace("up", "down");  
                   
               }else{
                   
                  var  res = classname.replace("down", "up");  
                   
               }
                
              
                
                jQuery("#OpenOrderIconicon").removeClass(classname);
                jQuery("#OpenOrderIconicon").attr("class",res);
                
                
                
            });
   
   
});

