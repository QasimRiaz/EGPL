


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
        swal({
            title: "Warning",
            text: "You have exceeded the character limit. The extra text has been removed', 'Character limit exceeded",
            type: "warning",
            confirmButtonClass: "btn-warning",
            confirmButtonText: "Ok"
        });
  
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
function calltoinsertorupdateuser_confrim(){
    
    swal({
        title: "Are you sure?",
        text: 'you want to start the sync process? It may take a few minutes depending on the size of data.',
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-success",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        closeOnConfirm: true,
        closeOnCancel: true
    },
            function (isConfirm) {



                if (isConfirm) {
                    var Sname = calltoinsertorupdateuser();
                   
                } else {
                   
                }
            });

}


function calltoinsertorupdateuser(){
                       
    
   
    
     
    var userids =  [];
    var url = window.location.protocol + "//" + window.location.host + "/";
    var syncurl = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=insertmapdynamicsuser';
    var statustable ="";
    statustable +='<table id="syncuserstatustable" class="display" cellspacing="0" width="100%"><thead><tr><th>Email</th><th>Company Name</th><th>Status</th><th>Result</th><th>Floor plan Exhibitor ID</th></tr></thead><tbody id="syncuserdata">';
    var data = new FormData();
    jQuery('#prog').progressbar({ value: 0 });
    jQuery("body").css({'cursor':'wait'});                
    jQuery('.useridarray').map(function() {
            userids.push(jQuery(this).val());
    });
     jQuery("#starttosync").hide();
     jQuery(".result").show();
     console.log(userids.length);
    var progresscountsize = 100 / userids.length;
   
    var countersize = progresscountsize;
    
    var counter = 1;
    var newcounter = 1;
    jQuery('#totaluser').empty();
    jQuery('#totaluser').append('<p><i class="fa fa-users" aria-hidden="true"></i>  0/'+userids.length+'</p>');
    
    jQuery.each(userids, function(index, value) {
        var data = new FormData();
        data.append('userid', value);
        data.append('requestcount', newcounter);
        newcounter++;
        jQuery.ajax({
            url: syncurl,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            
            success: function(data) {
           
                var finalresult = jQuery.parseJSON(data);
                statustable += '<tr><td>' + finalresult.email + '</td><td>'+ finalresult.company + '</td>';
                if(finalresult.status == "success"){
				
                                    statustable +='<td>' + finalresult.status + '</td>';
                                    statustable +='<td >' + finalresult.result + '</td>';
                                    statustable +='<td>' +finalresult.Exhibitor_ID+ '</td></tr>';
                                
                                    }else{
			            statustable +='<td >' + finalresult.status + '</td>';
                                    statustable +='<td class="notcreateduser">' + finalresult.result + '</td>';
                                    statustable +='<td></td></tr>';
                                }
                              
                                 jQuery('#prog')
                                    .progressbar('option', 'value', countersize)
                                    .children('.ui-progressbar-value')
                                    .html('')
                                    .css('display', 'block');
                            jQuery('#totaluser').empty();
                            jQuery('#totaluser').append('<p><i class="fa fa-users" aria-hidden="true"></i> '+counter+'/'+userids.length+'</p>');
                            jQuery('#progressreport').empty();
                            if(countersize.toFixed(0) > 100){
                            jQuery('#progressreport').append('<p>100% done</p>');
                            }else{
                                jQuery('#progressreport').append('<p>'+countersize.toFixed(0) + '% done</p>');
                                
                            }
                 console.log(countersize);                
          countersize = countersize+progresscountsize ;
          counter++;
           if (counter > userids.length) {
               //console.log(finalresult.requestcount);
                jQuery('body').css('cursor', 'default');
                statustable += '</tbody> </table>';
                jQuery("#syncuserstatus").empty();
                jQuery("#syncuserstatus").append(statustable);
                 jQuery("#syncuserstatus").show();
                jQuery('#syncuserstatustable').DataTable({
                pageLength: 25,
                dom: 'Bfrtlip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Download Sync results',
                        text: 'Download Sync results'
                    }
                ]
            });
        }      
             
         }   
   
        });
        
       
   });
   
      
        
   
    
    
    
}
var resuorcemsg;
var  resuorcestatus;
var settingArray;

function add_new_sponsor(){
   var url = window.location.protocol + "//" + window.location.host + "/";
  
  var email =  jQuery("#Semail").val();
  var profilepic = jQuery('#profilepic')[0].files[0]; 
  var data = new FormData();
  var sponsorlevel = jQuery("#Srole option:selected").val();
  if (jQuery('#checknewuser').is(":checked")){
       
        
         data.append('welcomeemailstatus', 'send');
       
    }else{
        
        data.append('welcomeemailstatus', 'notsend');
         
       
   }
  
  
  
  
  var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=add_new_sponsor_metafields';
 
   jQuery("body").css("cursor", "progress");
  if(email !=""  ){
      
       data.append('username', email);
       data.append('email', email);
       data.append('profilepic', profilepic);
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
               console.log(message);
                var sName = settingArray.ContentManager['sponsor_name'];
                 jQuery('body').css('cursor', 'default');
                if(message.msg == 'User created'){
                    
                   // jQuery('#sponsor-form').hide();
                  if(message.userrole == 'contentmanager'){
                      sName = "Content Manager";
                  }
                    jQuery("form")[0].reset();
                   // jQuery( "#sponsor-status" ).empty();
                   // jQuery( "#sponsor-status" ).append( '<div class="fusion-alert alert success alert-dismissable alert-success alert-shadow"><span class="alert-icon"><i class="fa fa-lg fa-check-circle"></i></span>'+sName+' Created Successfully. </div><div class="fusion-clearfix"></div>' );
                    swal({
					title: "Success",
					text: 'User Created Successfully</br>'+message.mapdynamicsstatus,
					type: "success",
                                        html:true,
					confirmButtonClass: "btn-success",
					confirmButtonText: "Ok"
				});
                    


                }else{
                    
                          
                  
                    jQuery( "#sponsor-status" ).empty();
                    jQuery( "#sponsor-status" ).append( '<div class="fusion-alert alert error alert-dismissable alert-danger alert-shadow"><span class="alert-icon"><i class="fa fa-lg fa-exclamation-triangle"></i></span>User already exists</div><div class="fusion-clearfix"></div>' );
                     swal({
					title: "Error",
					text: message.msg,
					type: "error",
                                        html:true,
					confirmButtonClass: "btn-danger",
					confirmButtonText: "Ok"
                                       
				});
                                
                }
               
                
            },error: function (xhr, ajaxOptions, thrownError) {
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
}
function add_new_admin_user(){
   var url = window.location.protocol + "//" + window.location.host + "/";
  
  var email =  jQuery("#Semail").val();
 var username =  jQuery("#Semail").val();
  var sponsorlevel = jQuery("#Srole option:selected").val();

  var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=addnewadminuser';
  var data = new FormData();
   jQuery("body").css("cursor", "progress");
  if(email !=""  ){
      
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
                 
                    jQuery("form")[0].reset();
                   // jQuery( "#sponsor-status" ).empty();
                   // jQuery( "#sponsor-status" ).append( '<div class="fusion-alert alert success alert-dismissable alert-success alert-shadow"><span class="alert-icon"><i class="fa fa-lg fa-check-circle"></i></span>'+sName+' Created Successfully. </div><div class="fusion-clearfix"></div>' );
                    swal({
					title: "Success",
					text: 'Content Manager Created Successfully',
					type: "success",
					confirmButtonClass: "btn-success",
					confirmButtonText: "Ok"
				});
                    


                }else{
                    
                          
                  
                    jQuery( "#sponsor-status" ).empty();
                    jQuery( "#sponsor-status" ).append( '<div class="fusion-alert alert error alert-dismissable alert-danger alert-shadow"><span class="alert-icon"><i class="fa fa-lg fa-exclamation-triangle"></i></span>User already exists</div><div class="fusion-clearfix"></div>' );
                     swal({
					title: "Error",
					text: 'Content Manager already exists',
					type: "error",
					confirmButtonClass: "btn-danger",
					confirmButtonText: "Ok"
				});
                                
                }
               
                
            },error: function (xhr, ajaxOptions, thrownError) {
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
}
function update_sponsor(){
   var url = window.location.protocol + "//" + window.location.host + "/";
  
 var profilepic ="";
  var profilepicurl="";
  var sponsorid =  parseInt(jQuery("#sponsorid").val());
  if(jQuery('#userprofilepic').attr('src') == undefined){
  
        profilepic = jQuery('#profilepic')[0].files[0]; 
  }else{
        profilepicurl = jQuery('#userprofilepic').attr('src');
  }
 console.log(profilepicurl);
  var sponsorlevel = jQuery("#Srole option:selected").val();
  var password =  jQuery("#password").val();
  
  var Semail = jQuery('#Semail').val();
  
  var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=update_new_sponsor_metafields';
  var data = new FormData();
   jQuery("body").css("cursor", "progress");

      
      
       data.append('password', password);
       data.append('sponsorid', sponsorid);
       data.append('sponsorlevel', sponsorlevel);
       data.append('profilepic', profilepic);
       data.append('profilepicurl', profilepicurl);
       data.append('Semail', Semail);
       
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
                
                swal({
                        title: "Updated!",
                        text: 'User Data Updated Successfully.</br>'+message.mapdynamicsstatus,
                        type: "success",
                        html:true,
                        confirmButtonClass: "btn-success"
                    });
                    
                jQuery('body').css('cursor', 'default');

                
               
                
            },error: function (xhr, ajaxOptions, thrownError) {
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


function delete_sponsor_meta(elem){
 var sName = settingArray.ContentManager['sponsor_name'];
 var idsponsor = jQuery(elem).attr("id");
 
// jAlert('<p>Are you sure you want to permanently delete this '+sName+'</p><p style="text-align: center;margin-right: 56px;"><a  class="btn btn-danger" onclick="conform_remove_sponsor('+idsponsor+')">Delete</a><a id="popup_ok" class="btn btn-info" style="margin-left: 20px;">Cancel</a></p>'); 

                                                swal({
							title: "Are you sure?",
							text: 'You want to permanently delete this user.',
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
                                                             var Sname = conform_remove_sponsor(idsponsor);
								swal({
									title: "Deleted!",
									text: "User deleted Successfully",
									type: "success",
									confirmButtonClass: "btn-success"
								},function() {
                                                                    location.reload();
                                                                 }
                                                            );
							} else {
								swal({
									title: "Cancelled",
									text: "User is safe :)",
									type: "error",
									confirmButtonClass: "btn-danger"
								});
							}
						});
    
}

function delete_resource(elem){
    var idsponsor = jQuery(elem).attr("id");
  
      swal({
							title: "Are you sure?",
							text: 'you want to permanently delete this Resource',
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
                                                             var Sname = conform_remove_resource(idsponsor);
								swal({
									title: "Deleted!",
									text: "Resource deleted Successfully",
									type: "success",
									confirmButtonClass: "btn-success"
								},function() {
                                                                    location.reload();
                                                                 }
                                                            );
							} else {
								swal({
									title: "Cancelled",
									text: "Resource is safe :)",
									type: "error",
									confirmButtonClass: "btn-danger"
								});
							}
						});
    
    
    
    
}
function conform_remove_resource(idsponsor){
    
     jQuery("body").css({'cursor':'wait'});
     var url = window.location.protocol + "//" + window.location.host + "/";
     
     var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=remove_post_resource';
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
                
                
                
            },error: function (xhr, ajaxOptions, thrownError) {
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
function conform_remove_sponsor(idsponsor){
    
    //  console.log(idsponsor);
     
     var url = window.location.protocol + "//" + window.location.host + "/";
     
     var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=remove_sponsor_metas';
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
             
                 //location.reload();
                 var sName = settingArray.ContentManager['sponsor_name'];
                 
                 return sName;
               
                
            },error: function (xhr, ajaxOptions, thrownError) {
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
/// resource file upload on server and get a url 
function create_new_resource(){
    
     jQuery("body").css({'cursor':'wait'});
     var url = window.location.protocol + "//" + window.location.host + "/";
     var title = jQuery('#Stitle').val(); 
     
     var file = jQuery('#Sfile')[0].files[0]; 
     var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=resource_new_post';
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
                
                
                 jQuery("form")[0].reset();
                 jQuery('#success-button').hide();
                 jQuery( "#sponsor-status" ).empty();
                 jQuery('#resource-file-div').show();
                 jQuery( "#file-upload-url" ).empty();
                  // <-- time in milliseconds
                   jQuery('body').css('cursor', 'default');
                  var message = jQuery.parseJSON(data);
                  console.log(message);
                  if(message == null){
                      swal({
                        title: "Error!",
                        text: 'Sorry, this file type is not permitted for security reasons.',
                        type: "error",
                        confirmButtonClass: "btn-danger"
                    });
                  }else{
                     swal({
                        title: "Success!",
                        text: 'Resource Created Successfully.',
                        type: "success",
                        confirmButtonClass: "btn-success"
                    }); 
                  }
                
                 
                    
                
            },error: function (xhr, ajaxOptions, thrownError) {
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
    var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=resource_file_upload';
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
                    swal({
					title: "Error",
					text: "There was an error during the requested operation. Please try again.",
					type: "error",
					confirmButtonClass: "btn-danger",
					confirmButtonText: "Ok"
				});
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
     var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=plugin_settings';
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
  console.log(reportName);
         jQuery("body").css({'cursor':'wait'});
     var url = window.location.protocol + "//" + window.location.host + "/";
     var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=update_admin_report';
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
                
            //jQuery( "#sponsor-status" ).empty();
                   
                   swal({
					title: "Success",
					text: "Current Report Saved Successfully.",
					type: "success",
					confirmButtonClass: "btn-success",
					confirmButtonText: "Ok"
				});
                   
                   
                  //  jQuery( "#sponsor-status" ).append( '<div class="fusion-alert alert success alert-dismissable alert-success alert-shadow"><span class="alert-icon"><i class="fa fa-lg fa-check-circle"></i></span>Current Report Saved Successfully. </div><div class="fusion-clearfix"></div>' );
                    setTimeout(function() {
                        jQuery( "#sponsor-status" ).empty();
                        }, 2000); // <-- time in milliseconds
                
               
               
               
                
            }});
       
   
     
    
    
    
    
    
}

function bulk_import_user(){
    
     jQuery("body").css({'cursor':'wait'});
     var url = window.location.protocol + "//" + window.location.host + "/";
     var data = new FormData();
     
     var file = jQuery('#Sfile')[0].files[0]; 
    
    
    if (jQuery('#check-1').is(":checked")){
       
         
         data.append('welcomeemailstatus', 'send');
    }else{
        
       data.append('welcomeemailstatus', 'notsend'); 
         
       
   }
    
     
     var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=bulkimportuser';
    
     var datatable ='';
     
     data.append('file', file);
   
     
     
     jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
               
                jQuery("form")[0].reset();
               
                jQuery( "#bulkimportstatus" ).hide();
                 jQuery( "#bulkimport" ).show();
                 
                jQuery('body').css('cursor', 'default');
                 
                  var message = jQuery.parseJSON(data);
                 
                 
                  if(message == 'faild'){
                      jQuery( "#importuserstatusdiv" ).hide();
                      swal({
                        title: "Error!",
                        text: 'Sorry, this file type is not permitted for security reasons.',
                        type: "error",
                        confirmButtonClass: "btn-danger"
                    });
                  }else{
                      
                    
                    
                   
                    if(message.data == 'your sheet is empty.'){
                      jQuery( "#importuserstatusdiv" ).hide();
                      swal({
                        title: "Error!",
                        text: 'Sorry, your sheet is empty.',
                        type: "error",
                        confirmButtonClass: "btn-danger"
                    });
                    }else{
                        
                        
                    jQuery( "#importuserstatus" ).empty();  
                    jQuery( "#uploadimportfile" ).hide();
                    jQuery( "#bulkimport" ).hide();
                    jQuery( "#bulkimportstatus" ).show();
                    console.log(message);
                    datatable +='<table id="importuserstatus" class="display" cellspacing="0" width="100%"><thead><tr><th>Email</th><th>Company Name</th><th>Status</th><th>Created User ID</th></tr></thead><tbody id="importuserdata">'
                    jQuery.each(message.data, function(index, value) {

                        datatable += '<tr><td>' + value.email + '</td><td>'+ value.companyname + '</td>';
						if(value.created_id != ""){
							datatable +='<td>' + value.status + '</td>';
						}else{
							datatable +='<td class="notcreateduser">' + value.status + '</td>'
						}
						
						datatable +='<td>' + value.created_id + '</td></tr>';

                    });
                      datatable += '</tbody> </table>';
                    jQuery( "#importuserstatusdiv" ).append(datatable );
                    jQuery( "#createdusers" ).append(message.createdcount );
                    jQuery( "#userserrors" ).append(message.errorcount );
                    jQuery( "#importuserstatusdiv" ).show( );
                    jQuery('#importuserstatus').DataTable({
					pageLength: 25,
                    dom: 'Bfrtlip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            title: 'Download import results',
                            text:'Download import results'
                        }

                    ]
                });
                  }
                }
                
                 
                    
                
            },error: function (xhr, ajaxOptions, thrownError) {
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
function edit_resource(elem){
    var idresource = jQuery(elem).attr("id");
    var resourcetitle = jQuery("#example #"+idresource+'U').text();
    console.log(idresource);
      jQuery.confirm({
            title: 'Edit Resource',
            content: '<div id="titlestatus"></div>Resource Title :  <input style="padding: 9px;border: #080808 solid 1px; width: 50%; height: 35px; border-radius: 7px;" type="text" id="resourcetitle" value="'+resourcetitle+'">',
            confirmButtonClass: 'mycustomwidth specialbuttoncolor',
           
            confirmButton:'Update',
            cancelButton:false,
            animation: 'rotateY',
            closeIcon: true,
            confirm: function () {
                
                
                var resourcetitle = jQuery("#resourcetitle").val();
                  jQuery("#titlestatus").empty();
                if(resourcetitle!=""){
                  
                    conform_edit_resource(idresource);
                }else{
                    
                    jQuery("#titlestatus").append('<p style="color:red"><strong>Please fill out the resource title.</strong>');
                    return false;
                }
                                            
            
            
            }
         });
    
}

function conform_edit_resource(idresource){
    console.log(idresource);
    var resourcetitle = jQuery("#resourcetitle").val();
    var url = window.location.protocol + "//" + window.location.host + "/"; 
    var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=updatresource';
    var data = new FormData();
    jQuery("body").css({'cursor':'wait'});
    
     data.append('idresource', idresource);
     data.append('resourcetitle', resourcetitle);
     jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                 jQuery('body').css('cursor', 'default');
                var reportData = jQuery.parseJSON(data);
                if(reportData == 'ok'){
                    swal({
					title: "Success",
					text: "Resource Title Updated Successfully",
					type: "success",
					confirmButtonClass: "btn-success",
					confirmButtonText: "Close"
				},function() {
                                      location.reload();
                                 }
                              );
                                                            
                }else{
                    
                  
                swal({
                    title: "Error",
                    text: reportData,
                    type: "error",
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Close"
                });
                                     
                }
                
            }
        });
    
    
    
    
}

 function getTimezoneName() {
          
    var current_date = new Date();
    var res = current_date.toString().split("GMT");
    return res[1];
    
}

function showprofilefieldupload(){
    
    jQuery("#showprofilepic").hide();
    jQuery("#updateprofilepic").show();
    
    jQuery('#userprofilepic').remove();
    
}
        
function sync_bulk_users(){
    
    var url = window.location.protocol + "//" + window.location.host + "/"; 
    var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=GetMapdynamicsApiKeys';
    var syncurl = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=insertmapdynamicsuser';
    var data = new FormData();
    var useridarray = {};
    jQuery("body").css({'cursor':'wait'});  
    
      
                var checkedRows = waTable.getData(true);
                var arrData = typeof checkedRows != 'object' ? JSON.parse(checkedRows) : checkedRows;

                var useridstml = "";
                useridstml += '<form id="myform" action="/sync-to-floorplan/" method="post">';

                for (var i = 0; i < arrData['rows'].length; i++) {


                    useridstml += '<input type="hidden" name="userid[]" value="' + arrData['rows'][i].wp_user_id + '">';
                   // console.log(arrData['rows'][i].wp_user_id);
                }
     
                useridstml += '</form>';
                
                jQuery("body").append(useridstml);
                document.getElementById('myform').submit();
                
}
function changeuseremailaddress(){
    
    
    var userid = jQuery('#sponsorid').val();
    var oldemailaddress = jQuery('#Semail').val();
   
   
    var url = window.location.protocol + "//" + window.location.host + "/";
    var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=changeuseremailaddress';
    var data = new FormData();
    
    data.append('userid',   userid);
    data.append('oldemailaddress',   oldemailaddress);
     jQuery.confirm({
            title: "Change Email Address",
            content: '<div id="titlestatus" ></div><div ><p></p><input placeholder="New Email Address" style="margin-bottom: 10px;padding: 9px;border: #d6e2e8 solid 1px; width: 100%; height: 35px; border-radius: 3px;" type="text" id="newemailaddress" ><p style="color:red;margin: 5px 0px;">This action will also change the login name for this user so we recommend that you send a welcome email message to the new email address.</p><br><p style="margin: 5px 0px;"><input  type="checkbox" value="checked" id="welcomememailstatus"> Send a welcome email (and new password) to the new email address</p></div>',
            confirmButtonClass: 'mycustomwidth specialbuttoncolor',
           
            confirmButton:'Update',
            cancelButton:false,
            animation: 'rotateY',
            closeIcon: true,
           
            confirm: function () {
                
                var welcomememailstatus ;
                var newemailaddress = jQuery("#newemailaddress").val();
                if (isValidEmailAddress(newemailaddress)) {
                   
               
                if (jQuery('#welcomememailstatus').is(':checked')) {
                     welcomememailstatus = 'checked';
                }else{
                     welcomememailstatus = 'unchecked';
                }
                data.append('newemailaddress',   newemailaddress);
                data.append('welcomememailstatus',   welcomememailstatus);
                jQuery("#titlestatus").empty();
                if(newemailaddress!=""){
                    
                    jQuery.ajax({
                    url: urlnew,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function(data) {
                        var finalresult = jQuery.parseJSON(data);
                        
                       jQuery('body').css('cursor', 'default');
                        
                        if(finalresult.msg == 'update'){
                        swal({
                                title: "Success!",
                                text: 'The email and login name for the user has been changed to' + newemailaddress,
                                type: "success",
                                confirmButtonClass: "btn-success"
                            },
                                    function (isConfirm) {

                                        location.reload();
                                    }

                            );
                   }else{
                       
                      
                       swal({
                                title: "Error!",
                                text: finalresult.msg,
                                type: "error",
                                confirmButtonClass: "btn-error"
                            });
                   } 
                    }
                });
                    
                }else{
                    jQuery("#titlestatus").empty();
                    jQuery("#titlestatus").append('<p style="color:red;text-align: center;"><strong>You need to write something!</strong>');
                    return false;
                }
            }else{
                jQuery("#titlestatus").empty();
                jQuery("#titlestatus").append('<p style="color:red;text-align: center;"><strong>Error:</strong> This username is invalid because it uses illegal characters. Please enter a valid username.');
                return false;
            }                            
            
            
            }
         });
    
}

function returnback(){
    
    
    swal({
        title: "Are you sure?",
        text: 'Are you sure you want to leave this screen?',
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        closeOnConfirm: false,
        closeOnCancel: false
    },
            function (isConfirm) {



                if (isConfirm) {
                   
                        //location.reload();
                        //window.location.replace("/add-new-level/");
                        document.location.href = '/add-new-level'
                   
                } else {
                    swal({
                        title: "Cancelled",
                        text: "",
                        type: "error",
                        confirmButtonClass: "btn-danger"
                    });
                }
            });
    
}

function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
};