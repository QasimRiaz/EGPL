jQuery.noConflict();

function updatecontentsettings() {


   jQuery("body").css("cursor", "progress");
   
   var data = new FormData();
   var url = window.location.protocol + "//" + window.location.host + "/";


   
   var exclude_array_create = jQuery("#listofmeta").val();
   var exclude_array_edit = jQuery("#listofmetaedit").val();
   var sponsor_name = jQuery("#spnsorname").val();
   var totalAmountKey = jQuery("#totalamount").val();
   var attendyTypeKey = jQuery("#attendytype").val();
   var eventdate = jQuery("#eventdate").val();
   var formemail = jQuery("#formemail").val();
   var mandrill = jQuery("#mandrill").val();
   var mapapikey = jQuery("#mapapikey").val();
   var mapsecretkey = jQuery("#mapsecretkey").val();
   
   var addresspoints = jQuery("#addresspoints").val();
   var uploadlogourl = jQuery("#uploadlogourl").attr("src");
   if(uploadlogourl == ''){
       
      var adminsitelogo = jQuery('#adminsitelogo')[0].files[0];
      data.append('adminsitelogo', adminsitelogo);
      
   }else{
       
      data.append('adminsitelogourl', uploadlogourl);
   }
 

   var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=updatecmanagersettings';
  
       data.append('excludemetakeyscreate', exclude_array_create);
       data.append('excludemetakeysedit', exclude_array_edit);
       data.append('sponsorname', sponsor_name);
       data.append('eventdate', eventdate);
       data.append('attendyTypeKey', attendyTypeKey);
       data.append('formemail', formemail);
       data.append('mandrill', mandrill);
       data.append('mapapikey', mapapikey);
       data.append('mapsecretkey', mapsecretkey);
       data.append('addresspoints', addresspoints);
     

       jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {

 jQuery('body').css('cursor', 'default');
            jQuery( "#successmsg" ).empty();
                    jQuery( "#successmsg" ).show();
                    jQuery( "#successmsg" ).append( 'Settings Updated.</p></div></div>' );
                    setTimeout(function() {
                        jQuery( "#successmsg" ).empty();
                        jQuery( "#successmsg" ).hide();
                        location.reload();
                        }, 2000);
                        

            }
          });
}

   
function clearfilepath(){
    
    jQuery('#uploadlogourl').attr('src', ''); // Clear the src
    
    
}