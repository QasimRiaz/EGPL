



var manageproducts;
var months = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", 
               "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];
jQuery(document).ready(function () {
    
    
    
   jQuery("#depositsstatus").click(function(){
        
        if(jQuery("#depositsstatus").prop( "checked" )){
            
            
            jQuery(".depositsdetail").show();
            
            
        }else{
            
             jQuery(".depositsdetail").hide();
        }
    });
    
    
    jQuery('#selectedtasks').select2();
    
    
    if ( window.location.href.indexOf("manage-products") > -1)
    {
    jQuery("body").css({'cursor': 'wait'});
    var url = currentsiteurl+'/';
    var urlnew = url + 'wp-content/plugins/EGPL/orderreport.php?contentManagerRequest=manageproducts';
    jQuery.ajax({
        url: urlnew,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function (data) {
            data = data.split('//');
            var productrowsdata = JSON.parse(data[0]);
            var productcoldata = JSON.parse(data[1]);
            console.log(productrowsdata);
            console.log(productcoldata);
            var productcolusheaderdataarray = [];
            jQuery.each(productcoldata, function (key, value) {

                if (productcoldata[key].type == 'num' || productcoldata[key].type == 'num-fmt') {

                    productcolusheaderdataarray.push({type:'num',title: productcoldata[key].title, data: productcoldata[key].title, render: jQuery.fn.dataTable.render.number(',', '.', 2, '$')});
                }else if(productcoldata[key].type == 'date'){
                    
                    productcolusheaderdataarray.push({title: productcoldata[key].title, data: productcoldata[key].title, type: productcoldata[key].type, render: function (data) {if (data !== null && data !== "") {var javascriptDate = new Date(data);javascriptDate = javascriptDate.getDate() + "/" + months[javascriptDate.getMonth()] + "/" + javascriptDate.getFullYear() +" "+javascriptDate.getHours()+":"+javascriptDate.getMinutes()+":"+javascriptDate.getSeconds();return javascriptDate;} else {return "";} }});
                
                }else {
                    productcolusheaderdataarray.push({title: productcoldata[key].title, data: productcoldata[key].title, type: productcoldata[key].type});
                }

            });
         

           
           
            if (data != '') {
                jQuery('body').css('cursor', 'default');
                manageproducts = jQuery('#manageproduct');
                manageproducts.DataTable({
                    data: productrowsdata,
                    columns: productcolusheaderdataarray,
                   
                    "columnDefs": [
                        
                        { "width": "140", "targets": 0 }
                    ],
                });
               
                
                
            jQuery('[data-toggle="tooltip"]').tooltip();
                

            }

        }
    });
    }
tinymce.init({
  selector: '.pdescriptionbox',
  height: 300,
  plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table contextmenu paste code'
  ],table_default_attributes: {
    
    
           border:1, class:'table'
  },
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  convert_urls: false,
        content_css: [
    '/wp-content/plugins/EGPL/css/editorstyle.css'
  ]
});





});

function customefilterapplyontable(){
    
    
    var table = jQuery('#manageproduct').DataTable();
    var getfilterValue =  jQuery("#filterdropdown option:selected").val();
    
    console.log(getfilterValue);
    
    table.column( 3 ).search(getfilterValue).draw();
                    
                    
    
    
}
function checkptoducttype(){
    
    
   
    var selectedproductType = jQuery('#pcategories option:selected').text();
    
    if(selectedproductType ==  'Packages'){
        
        jQuery("#assignmentlevelfield").show();
    }else{
        
        jQuery("#assignmentlevelfield").hide();
        
    }
    
    
    
    
    
    
}
function check_whocat_selet(){
    
    
     
     var pcategoriesname = jQuery("#pcategories option:selected").text();
     var roleassign = jQuery("#roleassign option:selected").val();
     add_new_product();
     
//    if(pcategoriesname == 'Add-ons' && roleassign !=""){
//        swal({
//            title: "Warning",
//            text: 'You have selected a level for an Add-on product. This is usually not recommended. Are you sure you want to do this?',
//            type: "warning",
//            showCancelButton: true,
//            confirmButtonClass: "btn-danger",
//            confirmButtonText: "Yes, continue!",
//            cancelButtonText: "No, cancel please!",
//            closeOnConfirm: false,
//            closeOnCancel: false
//            },
//                function (isConfirm) {
//
//                    if (isConfirm) {
//                        
//                    add_new_product();
//
//                    }else{
//                       
//                       
//                       swal.close(); 
//                        
//                    }
//                });
//    
//    }else{
//        
//        
//    } 
    
    
}



function add_new_product(){
    jQuery("body").css({'cursor': 'wait'});
    
    
    var productid = jQuery("#productid").val();
    var productimageurl = jQuery('#productoldimage').val();
    var ptitle = jQuery("#ptitle").val();
    var pprice = jQuery("#pprice").val();
    var pquanitity = jQuery("#pquanitity").val();
    var stockstatus = jQuery("#pstrockstatus option:selected").val(); 
    var pstatus = jQuery("#pstatus option:selected").val(); 
    var pcategories = jQuery("#pcategories option:selected").val();
    var pcategoriesname = jQuery("#pcategories option:selected").text();
    var menu_order = jQuery("#menu_order").val();
    var selectedtaskvalues = jQuery('#selectedtasks').select2("val");
    
    
    var visiblelevels = jQuery('#visiblelevels').select2("val");
   
    var listofuservisible = jQuery('#listofuservisible').select2("val");
    
    
    
    var getcatname = jQuery('#getcatname').val();
    var depositstype = "";
    var depositsamount = "";
    
    if(jQuery("#depositsstatus").prop( "checked" )){
         
          depositstype = jQuery("#depositstype option:selected").val(); 
          depositsamount = jQuery('#depositamount').val();
         
         
     }else{
         
         depositstype = "";
         depositsamount = "";
     }
    
  
    
    
    
    
    if(pcategoriesname == 'Product'){
        
        var roleassign = "";
        
    }else{
        
       var roleassign = jQuery("#roleassign option:selected").val();  
    }
   
    
    if(getcatname == 'Booth'){
        var pshortdescrpition = "";
    }else{
        var pshortdescrpition  = tinyMCE.get('pshortdescription').getContent();
    }
    var pdescrpition = tinyMCE.get('pdescription').getContent();
    
   
    
    var url = currentsiteurl+'/';
    var urlnewproduct = url + 'wp-content/plugins/EGPL/orderreport.php?contentManagerRequest=addnewproducts';
    var urlupdateproduct = url + 'wp-content/plugins/EGPL/orderreport.php?contentManagerRequest=updateproducts';
    
    var data = new FormData();
    if(selectedtaskvalues != 'null'){
        data.append('selectedtaskvalues', JSON.stringify(selectedtaskvalues));
    }else{
         data.append('selectedtaskvalues', '');
    }
    
    
    if(visiblelevels != 'null'){
        data.append('visiblelevels', JSON.stringify(visiblelevels));
    }else{
         data.append('visiblelevels', '');
    }
    
    
    
    if(listofuservisible != 'null'){
        data.append('listofuservisible', JSON.stringify(listofuservisible));
    }else{
         data.append('listofuservisible', '');
    }
    
    
    data.append('ptitle', ptitle);
    
    data.append('depositstype', depositstype);
    data.append('depositsamount', depositsamount);
    
    data.append('pprice', pprice);
    data.append('pquanitity', pquanitity);
    data.append('stockstatus', stockstatus);
    data.append('pstatus', pstatus);
    data.append('pcategories', pcategories);
    data.append('pdescrpition', pdescrpition);
    data.append('pshortdescrpition', pshortdescrpition);
    data.append('roleassign', roleassign);
    data.append('menu_order', menu_order);
    
    if(productid !=""){
        var updateproductimage = jQuery('#updateproductimage')[0].files[0];
        if(!updateproductimage){
            if(jQuery('#productimage')[0]){
            var updateproductimage = jQuery('#productimage')[0].files[0];
        }
        }
        
        
        data.append('productid', productid);
        data.append('productimageurl', productimageurl);
        data.append('updateproductimage', updateproductimage);
        
        jQuery.ajax({
            url: urlupdateproduct,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                jQuery('body').css('cursor', 'default');
                
                var data = data.replace(/\s/g, '');
                console.log(data)
                if(data == 'updatesuccessfully'){
                    
                    swal({
                    title: "Success",
                    text: "Product saved successfully.",
                    type: "success",
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "Ok"
                    },function() {
                         window.location.href = currentsiteurl+'/manage-products/';
                    }
                            );
                    
                    
                }else{
                    
                swal({
                    title: "Error",
                    text: "Product could not be saved. Please try again.",
                    type: "error",
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Ok"
                });
                }
            }
        });
        
    }else{
     var productimage = jQuery('#productimage')[0].files[0];
     data.append('productimage', productimage);
       jQuery.ajax({
            url: urlnewproduct,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                jQuery('body').css('cursor', 'default');
                var data = data.replace(/\s/g, '');
                if(data == 'createdsuccessfully'){
                    
                    swal({
                    title: "Success",
                    text: "Product saved successfully.",
                    type: "success",
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "Ok"
                    },function(){window.location.href = currentsiteurl+'/manage-products/';}
                            );
                    
                    
                }else{
                    
                swal({
                    title: "Error",
                    text: "Product could not be saved successfully. Please try again.",
                    type: "error",
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Ok"
                });
                }
            }
        });
    }
}

function deleteproduct(elem){
    
    jQuery("body").css({'cursor': 'wait'});
    var postid = jQuery(elem).attr('id');
    
    swal({
            title: "Are you sure?",
            text: 'Click confirm to delete this product.',
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {



            if (isConfirm) {
                confrim_deleteproduct(postid);
                swal({
                    title: "Deleted!",
                    text: "Product deleted Successfully",
                    type: "success",
                    confirmButtonClass: "btn-success"
                }, function() {
                    location.reload();
                }
                );
            } else {
                jQuery('body').css('cursor', 'default');
                swal({
                    title: "Cancelled",
                    text: "Product is safe :)",
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
            }
        });
    
}
function confrim_deleteproduct(postid){
    
  
   
    
    var url = currentsiteurl+'/';
    var urlnew = url + 'wp-content/plugins/EGPL/orderreport.php?contentManagerRequest=deleteproduct';
    var data = new FormData();
    
    data.append('postid', postid);
    
    jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                jQuery('body').css('cursor', 'default');
               
            }
        });
    
}

function createproductclone(elem){
    
   jQuery("body").css({'cursor': 'wait'});
    var postid = jQuery(elem).attr('id');
    
    
    swal({
            title: "Are you sure?",
            text: 'Click confirm to clone this product.',
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {



            if (isConfirm) {
                confrim_productclone(postid);
                swal({
                    title: "Success!",
                    text: "Product cloned successfully",
                    type: "success",
                    confirmButtonClass: "btn-success"
                }, function() {
                    location.reload();
                }
                );
            } else {
                jQuery('body').css('cursor', 'default');
                swal({
                    title: "Cancelled",
                    text: "Product is safe :)",
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
            }
        }); 
    
    
    
    
}

function confrim_productclone(postid){
    
  
   
   
    var url = currentsiteurl+'/';
    var urlnew = url + 'wp-content/plugins/EGPL/orderreport.php?contentManagerRequest=productclone';
    var data = new FormData();
    
    data.append('postid', postid);
  
    
    jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                jQuery('body').css('cursor', 'default');
               
            }
        });
    
}

function changeimage(){
    
    jQuery('#changeimageupload').show();
    jQuery('.productremoveimageblock').hide();
    jQuery('#productoldimage').val("");
    
    
    
    
}

function checkstockstatus(){
    
     var stockstatus = jQuery("#pstrockstatus option:selected").val(); 
if(stockstatus == 'instock'){
    
    //jQuery('.stockstatusbox').show();
     jQuery('.quanititybox').empty("");
     jQuery('.quanititybox').append('<div class="form-group row stockstatusbox"><label class="col-sm-2 form-control-label">Stock Quantity<strong>*</strong></label><div class="col-sm-10"> <input type="number"  class="form-control" id="pquanitity" name="pquanitity" placeholder="Stock Quantity" ></div></div>');
    
}else{
    
    jQuery('.quanititybox').empty("");
    
    
}    
    
    
    
}