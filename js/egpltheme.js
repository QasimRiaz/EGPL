// Class definition
var KTLeaflet = function () {

 // Private functions
 var demo5 = function () {
  // Define Map Location
  var leaflet = L.map('kt_leaflet_5', {
   center: [40.725, -73.985],
   zoom: 13
  });

  // Init Leaflet Map
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
   attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(leaflet);

  // Set Geocoding
  var geocodeService;
  if (typeof L.esri.Geocoding === 'undefined') {
   geocodeService = L.esri.geocodeService();
  } else {
   geocodeService = L.esri.Geocoding.geocodeService();
  }

  // Define Marker Layer
  var markerLayer = L.layerGroup().addTo(leaflet);

  // Set Custom SVG icon marker
  var leafletIcon = L.divIcon({
   html: `<span class="svg-icon svg-icon-danger svg-icon-3x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="24" width="24" height="0"/><path d="M5,10.5 C5,6 8,3 12.5,3 C17,3 20,6.75 20,10.5 C20,12.8325623 17.8236613,16.03566 13.470984,20.1092932 C12.9154018,20.6292577 12.0585054,20.6508331 11.4774555,20.1594925 C7.15915182,16.5078313 5,13.2880005 5,10.5 Z M12.5,12 C13.8807119,12 15,10.8807119 15,9.5 C15,8.11928813 13.8807119,7 12.5,7 C11.1192881,7 10,8.11928813 10,9.5 C10,10.8807119 11.1192881,12 12.5,12 Z" fill="#000000" fill-rule="nonzero"/></g></svg></span>`,
   bgPos: [10, 10],
   iconAnchor: [20, 37],
   popupAnchor: [0, -37],
   className: 'leaflet-marker'
  });

  // Map onClick Action
  leaflet.on('click', function (e) {
   geocodeService.reverse().latlng(e.latlng).run(function (error, result) {
    if (error) {
     return;
    }
    markerLayer.clearLayers(); // remove this line to allow multi-markers on click
    L.marker(result.latlng, { icon: leafletIcon }).addTo(markerLayer).bindPopup(result.address.Match_addr, { closeButton: false }).openPopup();
    alert(`You've clicked on the following address: ${result.address.Match_addr}`);
   });
  });
 }

 return {
  // public functions
  init: function () {
   // default charts
   demo5();
  }
 };
}();

jQuery(document).ready(function () {
    if ( window.location.href.indexOf("home") > -1){
        
        KTLeaflet.init();
        
    }
    
    
    
        
        
        
        
        
    
    
 
});


//function addToCart(p_id) {
//     
//     
//          jQuery("body").css("cursor", "progress");
//          jQuery.get(baseCurrentSiteURl+'/?add-to-cart=' + p_id+'&quantity=1', function() {
//             
//             jQuery("#"+p_id).text('Added');
//             jQuery("body").css("cursor", "default");
//             location.reload();
//            
//          });
//       }
       
jQuery.urlParam = function(name,url){
       
        
	var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(url);
	if(results == null){
            
           return 0; 
        }
        
        return results[1];
    } 
    
function movestep(url,event){
    
    console.log(jqueryarray);
    
    jQuery("body").css("cursor", "progress");
    var $iframe = jQuery('#exhibitorentryflowiframe');
    if ( $iframe.length ) {
        $iframe.attr('src',url);
        jQuery("body").css("cursor", "default");
        var currentcounter = parseInt(jQuery.urlParam("step",url));
       
        if(event == 'back'){
            
            history.back();
            if(jqueryarray[currentcounter-1]){
                
                
                jQuery(".backbutton").attr("onclick='"+jqueryarray[currentcounter-1]['url']+"','back'");
                
            }
            
        }else if(event == 'next'){
            
//            if(jqueryarray[currentcounter+1]){
//                
//                jQuery("#nextbutton").show();
//                jQuery("#nextbutton").attr("onclick='"+jqueryarray[currentcounter+1]['url']+"','next'");
//                
//            }
//            if(jqueryarray[currentcounter-1]){
//                
//                
//                jQuery("#backbutton").attr("onclick='"+jqueryarray[currentcounter-1]['url']+"','back'");
//                
//                
//            }
            
        }
        
        
        return false;
    }
    
    return true;
    
}     


jQuery(document).ready(function() {
    
               
   jQuery("#samebillingfields").click(function(){
        
        
                if (jQuery(this).prop("checked") == true) {
                    
                    
                    if(jQuery("input[name='first_name']").val() !=""){
                        
                        jQuery("input[name='billing_first_name']").val(jQuery("input[name='first_name']").val());
                        
                    }
                    if(jQuery("input[name='last_name']").val() !=""){
                        jQuery("input[name='billing_last_name']").val(jQuery("input[name='last_name']").val());
                        
                    }if(jQuery("input[name='Semail']").val() !=""){
                        
                        jQuery("input[name='billing_email']").val(jQuery("input[name='Semail']").val());
                    }if(jQuery("input[name='company_name']").val() !=""){
                        
                        jQuery("input[name='billing_company']").val(jQuery("input[name='company_name']").val());
                    }if(jQuery("input[name='address_line_1']").val() !=""){
                        
                        jQuery("input[name='billing_address_1']").val(jQuery("input[name='address_line_1']").val());
                    }if(jQuery("input[name='address_line_2']").val() !=""){
                        
                        jQuery("input[name='billing_address_2']").val(jQuery("input[name='address_line_2']").val());
                    }if(jQuery("input[name='usercity']").val() !=""){
                        
                        jQuery("input[name='billing_city']").val(jQuery("input[name='usercity']").val());
                    }if(jQuery("input[name='userstate']").val() !=""){
                        
                        jQuery("input[name='billing_state']").val(jQuery("input[name='userstate']").val());
                    }if(jQuery("input[name='user_phone_1']").val() !=""){
                        
                        jQuery("input[name='billing_phone']").val(jQuery("input[name='user_phone_1']").val());
                    }if(jQuery("input[name='userzipcode']").val() !=""){
                        
                        jQuery("input[name='billing_postcode']").val(jQuery("input[name='userzipcode']").val());
                    }
                    
                    
                    
                    
                    
                    
                    
                    if(jQuery("#usercountry").val() !=""){
                        
                        jQuery("#billing_country option:selected").removeAttr("selected");
                        var countryname = jQuery("#usercountry").val();
                        jQuery("#billing_country option").each(function () {
                            if (jQuery(this).text() == countryname) {
                                jQuery(this).attr('selected', 'selected');
                            }
                        });
                        
                        
                    }
                    
                    
                    
                    
                    
                    
                    
                    
                } else if (jQuery(this).prop("checked") == false) {
                   
                    
                
                
                
                }
});


function createAlert(title, summary, details, severity, dismissible, autoDismiss, appendToId) {
  var iconMap = {
    info: "fa fa-info-circle",
    success: "fa fa-thumbs-up",
    warning: "fa fa-exclamation-triangle",
    danger: "fa ffa fa-exclamation-circle"
  };

  var iconAdded = false;

  var alertClasses = ["alert", "animated", "flipInX"];
  alertClasses.push("alert-" + severity.toLowerCase());

  if (dismissible) {
    alertClasses.push("alert-dismissible");
  }

  var msgIcon = jQuery("<i />", {
    "class": iconMap[severity] // you need to quote "class" since it's a reserved keyword
  });

  var msg = jQuery("<div />", {
    "class": alertClasses.join(" ") // you need to quote "class" since it's a reserved keyword
  });

  if (title) {
    var msgTitle = jQuery("<h4 />", {
      html: title
    }).appendTo(msg);
    
    if(!iconAdded){
      msgTitle.prepend(msgIcon);
      iconAdded = true;
    }
  }

  if (summary) {
    var msgSummary = jQuery("<strong />", {
      html: summary
    }).appendTo(msg);
    
    if(!iconAdded){
      msgSummary.prepend(msgIcon);
      iconAdded = true;
    }
  }

  if (details) {
    var msgDetails = jQuery("<p />", {
      html: details
    }).appendTo(msg);
    
    if(!iconAdded){
      msgDetails.prepend(msgIcon);
      iconAdded = true;
    }
  }
  

  if (dismissible) {
    var msgClose = jQuery("<span />", {
      "class": "close", // you need to quote "class" since it's a reserved keyword
      "data-dismiss": "alert",
      html: "<i class='fa fa-times-circle'></i>"
    }).appendTo(msg);
  }
  
  jQuery('#' + appendToId).prepend(msg);
  
  if(autoDismiss){
    setTimeout(function(){
      msg.addClass("flipOutX");
      setTimeout(function(){
        msg.remove();
      },1000);
    }, 5000);
  }
}


                
                
function addToCart(p_id,price,slug) {
     
            
          jQuery("body").css("cursor", "progress");
          var data = new FormData();
          if(price == "full"){
              
              data.append('wc_deposit_option',  'no');
          }else{
              
             data.append('wc_deposit_option',  'yes'); 
          }
          
          data.append('quantity',  1);
          data.append('add-to-cart',  p_id);
          
     
          jQuery.ajax({
                url: baseCurrentSiteURl+'/?add-to-cart=' + p_id+'&quantity=1',
                data:data,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function(data) {
                    
                        
                    //var checkouturl = baseCurrentSiteURl+'/checkout/';
                    //var addONs = baseCurrentSiteURl+'/product-category/add-ons/';
                    //jQuery("#"+p_id).empty();
                    //jQuery("#"+p_id+'_checkout').attr("disabled", false);

                    //if(request == 'log'){ 
                      // var enbutton = "<a class='btn btn-success btn-small' >Added</a>"
                       //jQuery("#"+p_id).append(enbutton);
                    //}else{

                        //top.window.location.href = baseCurrentSiteURl+"/exhibitor-entry/";
                    //}
                    jQuery("body").css("cursor", "default");
                    location.reload();
                    
                }
            });
     
     
     
//           jQuery.post(baseCurrentSiteURl+'/?add-to-cart=' + p_id+'&quantity=1', function(data) {
//             
//            
//          });
       } 
               