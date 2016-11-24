


jQuery(document).ready(function() {
  
    jQuery( ".sf-sub-indicator" ).addClass( "icon-play" ); 
});

jQuery( document ).ready(function() {
    
    jQuery( ".sf-sub-indicator" ).addClass( "icon-chevron-right" ); 
    jQuery('textarea').each(function(){
      
        var maxLength = jQuery(this).attr('maxlength');
        var textareaid= jQuery(this).attr('id');
        var length = jQuery(this).val().length;
        var remininglength=maxLength-length;
        jQuery('#chars_'+textareaid).text(remininglength);
    
       
})
});
jQuery("input").change(function(event) {
       var id = jQuery(this).attr('id');
       var value = this.value;
      jQuery("#display_"+id).val(value);
    });
   jQuery( ".remove_upload" ).click(function() {
         var id = jQuery(this).attr('id');
         myString = id.replace('remove_','');
         jQuery( "input[name='"+myString+"']" ).val("");
         var myClass = jQuery("#"+id).attr("class");
         var myArray = myClass.split(' ');
         jQuery( "input[name$='"+myArray[0]+"']" ).val("");
         jQuery("#hd_"+myArray[0]).val("");
         jQuery("."+id).hide();
         jQuery("."+myArray[0]).show();
   });
   
  jQuery("#login_temp").contents().filter(function () {
     return this.nodeType === 3; 
}).remove();

jQuery('textarea').keyup(function() {

  
  
 
    
 
   
  var maxLength = jQuery(this).attr('maxlength');
  var textareaid= jQuery(this).attr('id');
  var length = jQuery(this).val().length;
  var length = maxLength-length;
  jQuery('#chars_'+textareaid).text(length);
  if(length == 0){
     // alert('.');
   jAlert('You have exceeded the character limit. The extra text has been removed', 'Character limit exceeded'); 
//jQuery( "#dialog" ).dialog();
  }
});

jQuery( document ).ready(function() {
    
   
    jQuery('select').each(function(){
     
        var id = jQuery(this).attr('id');
        var slectvalue =  jQuery("#"+id+" option:selected" ).text();
       
        if(slectvalue == 'Complete'){
           //jQuery("."+id).css( "background-color:#FFF" );
           jQuery("."+id).removeClass('duedate');
        }
        
});
});
jQuery(function() {
    jQuery( "#datepicker" ).datepicker({showAnim: "fadeIn"});
    //$('.datepicker').datepicker({showAnim: "fadeIn"});
     //jQuery( "#datepickerr" ).datepicker();
    
  });

var resuorcemsg;
var  resuorcestatus;
var settingArray;

function add_new_sponsor(){
   var url = window.location.protocol + "//" + window.location.host + "/";
  var username = jQuery("#Susername").val();
  var email =  jQuery("#Semail").val();
 
  var sponsorlevel = jQuery("#Srole option:selected").val();

  var urlnew = url + 'wp-content/plugins/contentmanager/contentmanager.php?contentManagerRequest=add_new_sponsor_metafields';
  var data = new FormData();
   jQuery("body").css("cursor", "progress");
  if(username != "" && email !=""  ){
      
       data.append('username', username);
       data.append('email', email);
       data.append('sponsorlevel', sponsorlevel);
       
       jQuery('.mymetakey').each(function(){
           
            data.append(jQuery(this).attr( "name" ), jQuery(this).val());
       });
       
       
       jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
               var message = jQuery.parseJSON(data);
                var sName = settingArray.ContentManager['sponsor_name'];
                 jQuery('body').css('cursor', 'default');
                if(message.msg == 'User created'){
                    
                   // jQuery('#sponsor-form').hide();
                  if(message.userrole == 'contentmanager'){
                      sName = "Content Manager";
                  }
                    jQuery("form")[0].reset();
                    jQuery( "#sponsor-status" ).empty();
                    jQuery( "#sponsor-status" ).append( '<div class="fusion-alert alert success alert-dismissable alert-success alert-shadow"><span class="alert-icon"><i class="fa fa-lg fa-check-circle"></i></span>'+sName+' Created Successfully. </div><div class="fusion-clearfix"></div>' );
                    setTimeout(function() {
                        jQuery( "#sponsor-status" ).empty();
                        }, 2000); // <-- time in milliseconds
                    


                }else{
                    
                          
                  
                    jQuery( "#sponsor-status" ).empty();
                    jQuery( "#sponsor-status" ).append( '<div class="fusion-alert alert error alert-dismissable alert-danger alert-shadow"><span class="alert-icon"><i class="fa fa-lg fa-exclamation-triangle"></i></span>User already exists</div><div class="fusion-clearfix"></div>' );
                    setTimeout(function() {
                        jQuery( "#sponsor-status" ).empty();
                        }, 2000); // <-- time in milliseconds
                }
               
                
            },error: function (xhr, ajaxOptions, thrownError) {
                     jAlert('There was an error during the requested operation. Please try again.'); 
            }
        });

        
      
      
      
  }
}

function update_sponsor(){
   var url = window.location.protocol + "//" + window.location.host + "/";
  
 
  var sponsorid =  parseInt(jQuery("#sponsorid").val());
  var sponsorlevel = jQuery("#Srole option:selected").val();
  var password =  jQuery("#password").val();
  var urlnew = url + 'wp-content/plugins/contentmanager/contentmanager.php?contentManagerRequest=update_new_sponsor_metafields';
  var data = new FormData();
   jQuery("body").css("cursor", "progress");

      
      
       data.append('password', password);
       data.append('sponsorid', sponsorid);
       data.append('sponsorlevel', sponsorlevel);
       
       
       jQuery('.mymetakey').each(function(){
           
            data.append(jQuery(this).attr( "name" ), jQuery(this).val());
       });
       
       
       jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
             
                 jQuery('body').css('cursor', 'default');
                
                   
                   // jQuery('#sponsor-form').hide();
                  
                  var sName = settingArray.ContentManager['sponsor_name'];
                  
                    jQuery( "#sponsor-status" ).empty();
                    jQuery( "#sponsor-status" ).append( '<div class="fusion-alert alert success alert-dismissable alert-success alert-shadow"><span class="alert-icon"><i class="fa fa-lg fa-check-circle"></i></span>'+sName+' Data Updated Successfully. </div><div class="fusion-clearfix"></div>' );
                    setTimeout(function() {
                        jQuery( "#sponsor-status" ).empty();
                        }, 2000); // <-- time in milliseconds
                    


                
               
                
            },error: function (xhr, ajaxOptions, thrownError) {
                     jAlert('There was an error during the requested operation. Please try again.'); 
      }
        });

        
      
      
      

}


function delete_sponsor_meta(elem){
 var sName = settingArray.ContentManager['sponsor_name'];
 var idsponsor = jQuery(elem).attr("id");
 jAlert('<p>Are you sure you want to permanently delete this '+sName+'</p><p style="text-align: center;margin-right: 56px;"><a  class="btn btn-danger" onclick="conform_remove_sponsor('+idsponsor+')">Delete</a><a id="popup_ok" class="btn btn-info" style="margin-left: 20px;">Cancel</a></p>'); 
 jQuery('#popup_panel').hide();
   
    
}

function delete_resource(elem){
    var idsponsor = jQuery(elem).attr("id");
    jAlert('<p>Are you sure you want to permanently delete this Resource</p><p style="text-align: center;margin-right: 56px;"><a  class="btn btn-danger" onclick="conform_remove_resource('+idsponsor+')">Delete</a><a id="popup_ok" class="btn btn-info" style="margin-left: 20px;">Cancel</a></p>'); 
    jQuery('#popup_panel').hide();
}
function conform_remove_resource(idsponsor){
    
     jQuery("body").css({'cursor':'wait'});
     var url = window.location.protocol + "//" + window.location.host + "/";
     
     var urlnew = url + 'wp-content/plugins/contentmanager/contentmanager.php?contentManagerRequest=remove_post_resource';
     var data = new FormData();
     data.append('id', idsponsor);
     jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                // jQuery('body').css('cursor', 'default');
                 location.reload();
                 jQuery( "#sponsor-status" ).append( '<div class="fusion-five-sixth fusion-layout-column fusion-spacing-yes" style="margin-top:0px;margin-bottom:20px;"><div class="fusion-column-wrapper"><div class="fusion-alert alert success alert-dismissable alert-success alert-shadow"><span class="alert-icon"><i class="fa fa-lg fa-check-circle"></i></span>Resource Deleted Successfully.</div><div class="fusion-clearfix"></div></div></div>' );
                 setTimeout(function() {
                        jQuery( "#sponsor-status" ).empty();
                 }, 2000); // <-- time in milliseconds
                
                
            },error: function (xhr, ajaxOptions, thrownError) {
                     jAlert('There was an error during the requested operation. Please try again.'); 
      }
        });
    
}
function conform_remove_sponsor(idsponsor){
    
     jQuery("body").css({'cursor':'wait'});
     var url = window.location.protocol + "//" + window.location.host + "/";
     
     var urlnew = url + 'wp-content/plugins/contentmanager/contentmanager.php?contentManagerRequest=remove_sponsor_metas';
     var data = new FormData();
     data.append('id', idsponsor);
     jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                // jQuery('body').css('cursor', 'default');
                 location.reload();
                  var sName = settingArray.ContentManager['sponsor_name'];
                 jQuery( "#sponsor-status" ).append( '<div class="fusion-alert alert success alert-dismissable alert-success alert-shadow"><span class="alert-icon"><i class="fa fa-lg fa-check-circle"></i></span>'+sName+' deleted Successfully. </div><div class="fusion-clearfix"></div>' );
                 setTimeout(function() {
                        jQuery( "#sponsor-status" ).empty();
                 }, 2000); // <-- time in milliseconds
                
                
            },error: function (xhr, ajaxOptions, thrownError) {
                     jAlert('There was an error during the requested operation. Please try again.'); 
      }
        });
    
}
/// resource file upload on server and get a url 
function create_new_resource(){
    
     jQuery("body").css({'cursor':'wait'});
     var url = window.location.protocol + "//" + window.location.host + "/";
     var title = jQuery('#Stitle').val(); 
     
     var file = jQuery('#Sfile')[0].files[0]; 
     var urlnew = url + 'wp-content/plugins/contentmanager/contentmanager.php?contentManagerRequest=resource_new_post';
     var data = new FormData();
     data.append('title', title);
     data.append('file', file);
     jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                 jQuery('body').css('cursor', 'default');
                 jQuery("form")[0].reset();
                 jQuery('#success-button').hide();
                    jQuery( "#sponsor-status" ).empty();
                    jQuery('#resource-file-div').show();
                    jQuery( "#file-upload-url" ).empty();
                    jQuery( "#sponsor-status" ).append( '<div class="fusion-alert alert success alert-dismissable alert-success alert-shadow"><span class="alert-icon"><i class="fa fa-lg fa-check-circle"></i></span>Resource Created Successfully. </div><div class="fusion-clearfix"></div>' );
                    setTimeout(function() {
                        jQuery( "#sponsor-status" ).empty();
                        }, 2000); // <-- time in milliseconds
                
                
            },error: function (xhr, ajaxOptions, thrownError) {
                     jAlert('There was an error during the requested operation. Please try again.'); 
      }
        });
    
}
function show_button(){
     var file = jQuery('#Sfile')[0].files[0]; 
     if(file != ""){
         jQuery('#success-button').show();
     }
    
}
function resource_file_upload(){
    
    
    var url = window.location.protocol + "//" + window.location.host + "/";
  
    var file = jQuery('#Sfile')[0].files[0]; 
    
    if(file != '' ){
    jQuery("body").css({'cursor':'wait'});
    var data = new FormData();
    data.append('file', file);
    var urlnew = url + 'wp-content/plugins/contentmanager/contentmanager.php?contentManagerRequest=resource_file_upload';
      jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                 var alertmessage = jQuery.parseJSON(data);
             
                if (typeof(alertmessage.msg) != 'undefined') {
                    //console.log(alertmessage.error);
                    if (alertmessage.msg != "Empty File") {

                       
                         resuorcestatus=true;
                         jQuery('#resource-file-div').hide();
                         jQuery('#file-upload-url').append(alertmessage.url);
                         jQuery("body").css({'cursor':'default'});
                        
                    }else{
                        resuorcestatus=true;
                         jQuery("body").css({'cursor':'default'});
                    }

                } else {
                    resuorcemsg=true;
                    jQuery("body").css({'cursor':'default'})
                   

                }
                
            },error: function (xhr, ajaxOptions, thrownError) {
                     jAlert('There was an error during the requested operation. Please try again.'); 
      }
        });
    }else{
        
    }
    
}

function getUrlParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
    }
}  
jQuery(document).ready(function(){
     var url = window.location.protocol + "//" + window.location.host + "/";
     var data = new FormData();
     var urlnew = url + 'wp-content/plugins/contentmanager/contentmanager.php?contentManagerRequest=plugin_settings';
     jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
             settingArray = jQuery.parseJSON(data); 
                
            }});
        });
     
     
     function update_admin_report(){
    
     
     
     var reportName = jQuery("#reportname").val();
     if(reportName !=""){
         jQuery("body").css({'cursor':'wait'});
     var url = window.location.protocol + "//" + window.location.host + "/";
     var urlnew = url + 'wp-content/plugins/contentmanager/contentmanager.php?contentManagerRequest=update_admin_report';
     var data = new FormData();
     
     
     
   //  jQuery('#sponsor_name').val('testing');
     
     data.append('reportName', reportName);
     jQuery('.filter').each(function(){
           
          data.append(jQuery(this).attr( "id" ), jQuery(this).val());
          
       });
       
       
      // console.log(data);
     jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                //jQuery("form")[0].reset();
                jQuery('body').css('cursor', 'default');
                var reportData = jQuery.parseJSON(data);
                
                 jQuery("#reportlist").empty();
                 jQuery.each( reportData, function( i, item ) {
                     
                     if(item == reportName){
                          
                          //jQuery("#reportlist").append("<option value="+item+" selected>"+item+"<option/>");
                          jQuery("#reportlist").append("<option value='"+item+"' selected='selected'>"+item+"</option>");
                     }else{
                          
                         jQuery("#reportlist").append(jQuery("<option/>").attr("value", item).text(item));
                     }
                    
                });
                
            jQuery( "#sponsor-status" ).empty();
                   
                    jQuery( "#sponsor-status" ).append( '<div class="fusion-alert alert success alert-dismissable alert-success alert-shadow"><span class="alert-icon"><i class="fa fa-lg fa-check-circle"></i></span>Current Report Saved Successfully. </div><div class="fusion-clearfix"></div>' );
                    setTimeout(function() {
                        jQuery( "#sponsor-status" ).empty();
                        }, 2000); // <-- time in milliseconds
                
               
               
               
                
            }});
       
     }else{
         
      jQuery.confirm({
        title: 'Alert!',
        content: 'Please fill out Report Name.',
        cancelButton: 'Colse',
         confirmButton: false,
        
        
        cancelButtonClass: 'btn-info',
       
       
        cancel: function() {
            
        }

    }); 
     }
     
    
    
    
    
    
}
