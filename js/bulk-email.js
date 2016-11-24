




jQuery.noConflict();
//jQuery(document).ready(function() { jQuery("#bodytext").cleditor({width: 898,height: 340}); });


jQuery(document).ready(function() { 
    
        jQuery("a[data-tab-destination]").on('click', function() {
        var tab = jQuery(this).attr('data-tab-destination');
        jQuery("#"+tab).click();
    });







});

function get_bulk_email_address() {

   var bulkemails = new Array();    
   var checkedRows = waTable.getData(true);
   var arrData = typeof checkedRows != 'object' ? JSON.parse(checkedRows) : checkedRows;
   for (var i = 0; i < arrData['rows'].length; i++) {
        var row = "";
   for (var index in arrData['rows'][i]) {
       if (typeof(arrData['cols'][index]) != "undefined") {
           
           if (arrData['cols'][index].friendly == "Email") {
               
               bulkemails.push(arrData['rows'][i][index])
           }
       }  
         
         
     }
    
    }
   
    if (bulkemails.length === 0) {
        
        
        
        
        jQuery('#tab2').empty();
         jQuery(".hookinfo").hide();
        jQuery('#bulkemail_status').empty();
         jQuery('#bulkemail_text_fileds').hide();
         jQuery('#savetemplatediv').hide();
        jQuery('#bulkemail_status').append('<div class="fusion-alert alert error alert-dismissable alert-danger alert-shadow"> <button type="button" class="close toggle-alert" data-dismiss="alert" aria-hidden="true"><span class="icon-wrapper circle-no"><i class="fusion-li-icon fa fa-times-circle" style="color:#262626;"></i></span></button> <span class="alert-icon"><i class="fa fa-lg fa-exclamation-triangle"></i></span>No recipients selected. Please select atleast one from the Report.</div>');
      
    }else{
        
        jQuery('#reportstab').hide();
        jQuery('#bulkemailtab').show();
        jQuery('#bulkemail_status').empty();
        var length =bulkemails.length;
        jQuery(".hookinfo").show();
        jQuery('#bulkemail_status').append('<div class="fusion-alert alert success alert-dismissable alert-success alert-shadow"><p>'+length+' recipients selected.</p></div>');
        jQuery('#bulkemail_text_fileds').show();
        jQuery('#savetemplatediv').show();
        jQuery('#emailAddress').val(bulkemails.join(", ")); 
        var keysnames = '<a class="btn btn-sm btn-primary mergefieldbutton" style="cursor: pointer;" onclick="keys_preview()" >Insert Merge Fields</a>';
        jQuery( "#sponsor_meta_keys" ).html(keysnames);
        jQuery('#selectedstatscountforbulk').empty();
        jQuery('#selectedstatscountforbulk').append(jQuery( "#selectedstatscount" ).text());
        
        console.log();
         
        
       //  console.log(bulkemails)    ;
    }
   
}

function bulkemail_preview(){
    
     var emailSubject =jQuery('#emailsubject').val();
     var emailBody=tinymce.activeEditor.getContent();//jQuery('#bodytext').val();
     
      
                                    bulkemailcontentbox =    jQuery.confirm({
                                             title: 'Preview!',
                                             content: '<p id="success-msg-div"></p> <br> Subject : '+emailSubject+' <hr> '+emailBody+' <hr> <p style="margin-bottom: -60px !important;"><button type="button" title="Test email will be sent to '+currentAdminEmail+'" class="examplebutton btn mycustomwidth  btn-secondary">Send me a Test Email</button></p>',
                                             confirmButton:'Send',
                                             cancelButton:'Close',
                                             testButton:'Send Test Email',
                                             confirmButtonClass: 'btn mycustomwidth btn-lg btn-primary mysubmitemailbutton',
                                             cancelButtonClass: 'btn mycustomwidth btn-lg btn-danger',
                                             onOpen: function() {
                                               
                                                this.$b.find('button.examplebutton').click(function() {
                                                 conform_send_test_email_for_admin();
                                                jQuery( "#success-msg-div" ).append('<div class="alert wpb_content_element alert-success"><div class="messagebox_text"><p>we have send a test email on '+currentAdminEmail+' please check your mail.</p></div></div>');
                                               setTimeout(function() {
                                                jQuery( "#success-msg-div" ).empty();
                                                }, 5000);
                                                     
                                              });
    },
                                          
                                            confirm: function () {
                                              conform_send_bulk_email();
                                              
                                               return false;
                                            },
                                            cancel: function () {
                                              //  location.reload();
                                            },
                                            test: function () {
                                               
                                            }
                                       
                                        });
                                    
                                    
                 
                                    
                                    
    // jAlert( 'Subject : ' +emailSubject+ '<hr>'+
            // emailBody+'<hr><p style="text-align: center;margin-right: 56px;"><a  class="btn btn-danger" id="popup_ok" onclick="conform_send_bulk_email()">Send</a><a id="popup_okk" class="btn btn-info" style="margin-left: 20px;">Cancel</a></p>'); 
    
    
    
}

function conform_send_bulk_email(){
     
     
    var emailSubject =jQuery('#emailsubject').val();
    var emailBody=tinymce.activeEditor.getContent();//jQuery('#bodytext').val();
    var emailAddress=jQuery('#emailAddress').val();
    var checkedRows = waTable.getData(true);
    var arrData = typeof checkedRows != 'object' ? JSON.parse(checkedRows) : checkedRows;
    var BCC=jQuery('#BCC').val();
    var fromname=jQuery('#fromname').val();
     var statusmessage='';
     var alertclass='';
    
    jQuery("body").css({'cursor':'wait'});
    var url = window.location.protocol + "//" + window.location.host + "/";
    var urlnew = url + 'wp-content/plugins/EPGL/egpl.php?contentManagerRequest=sendbulkemail';
    var data = new FormData();
    data.append('emailSubject', emailSubject);
    data.append('emailBody', emailBody);
    data.append('emailAddress', emailAddress);
    data.append('fromname', fromname);
   
    data.append('attendeeallfields',   JSON.stringify(arrData['rows']));
    data.append('datacollist',   JSON.stringify(arrData['cols']));
     data.append('BCC', BCC);
     jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                 jQuery('body').css('cursor', 'default');
                 
                 if(data.indexOf("successfully") >-1){
                      statusmessage ='Your message has been sent.';
                      alertclass= 'alert-success';
                  }else{
                      
                      statusmessage = data;
                      alertclass= 'alert-danger';
                  }
                  
                
                bulkemailcontentbox.setContent('<div class="alert wpb_content_element '+alertclass+'"><div class="messagebox_text"><p>'+statusmessage+'</p></div></div>');
                  
                  jQuery('.mysubmitemailbutton').hide();
                 //location.reload();
                // jQuery( "#sponsor-status" ).append( '<div class="alert wpb_content_element alert-success"><div class="messagebox_text"><p>Resource deleted.</p></div></div>' );
                // setTimeout(function() {
                  //      jQuery( "#sponsor-status" ).empty();
                // }, 2000); // <-- time in milliseconds
                
                
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
function conform_send_test_email_for_admin(){
     
     
    var emailSubject =jQuery('#emailsubject').val();
    var emailBody=tinymce.activeEditor.getContent();//jQuery('#bodytext').val();
   
     
    jQuery("body").css({'cursor':'wait'});
    var url = window.location.protocol + "//" + window.location.host + "/";
    var urlnew = url + 'wp-content/plugins/EPGL/egpl.php?contentManagerRequest=sendadmintestemail';
    var data = new FormData();
    data.append('emailSubject', emailSubject);
    data.append('emailBody', emailBody);
  
    
     jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                 jQuery('body').css('cursor', 'default');
                 //location.reload();
                // jQuery( "#sponsor-status" ).append( '<div class="alert wpb_content_element alert-success"><div class="messagebox_text"><p>Resource deleted.</p></div></div>' );
                // setTimeout(function() {
                  //      jQuery( "#sponsor-status" ).empty();
                // }, 2000); // <-- time in milliseconds
                
                
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
function templateupdatefilter(){
    

    var  dropdownvalue =  jQuery("#templateupdatefilterlist option:selected").val();
    if(dropdownvalue != "defult"){
         jQuery("#emailtemplate").val("");
         jQuery("#showemailtemplate").show();
       
       if(dropdownvalue != "saveCurrentEmailtemplate"){
          
            jQuery("#emailtemplate").val(dropdownvalue);
            var url = window.location.protocol + "//" + window.location.host + "/";
            var urlnew = url + 'wp-content/plugins/EPGL/egpl.php?contentManagerRequest=get_email_template';
            var data = new FormData();
            var emailtemplatename = jQuery("#emailtemplate").val();
            data.append('emailtemplatename', emailtemplatename);
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
              
                var emailtemplatenamelist = jQuery.parseJSON(data);
               // console.log(emailtemplatenamelist);
              //  jQuery("#bodytext").cleditor()[0].clear();
                jQuery("#emailsubject").val(emailtemplatenamelist.emailsubject);
               // jQuery("#bodytext").val();
                 jQuery("#bodytext").val(emailtemplatenamelist.emailboday);
                 tinymce.activeEditor.setContent(emailtemplatenamelist.emailboday);
                 jQuery("#BCC").val(emailtemplatenamelist.BCC);
                 jQuery("#fromname").val(emailtemplatenamelist.fromname);
                 
               // jQuery("#bodytext").cleditor()[0].refresh();
          
                
               
               
               
                
            }});
        
        
        
        
        
       }
    }else{
        
        jQuery("#showemailtemplate").hide();
        jQuery("#bodytext").val("");
        jQuery("#emailsubject").val("");
        jQuery("#BCC").val("");
        jQuery("#fromname").val("");
         jQuery("#emailtemplate").val("");
        tinymce.activeEditor.setContent("");
        
    }
     
    
    
}

function update_admin_email_template(){
    
     var url = window.location.protocol + "//" + window.location.host + "/";
     var urlnew = url + 'wp-content/plugins/EPGL/egpl.php?contentManagerRequest=update_admin_email_template';
     var data = new FormData();
     var emailtemplatename = jQuery("#emailtemplate").val();
     var fromname = jQuery("#fromname").val();
   
     
    
     var emailsubject = jQuery("#emailsubject").val();
     var emailboday =  tinymce.activeEditor.getContent();//jQuery("#bodytext").val();
     var BCC =      jQuery("#BCC").val();
     //console.log(emailboday);
     
     
   //  jQuery('#sponsor_name').val('testing');
     
     data.append('emailtemplatename', emailtemplatename);
     data.append('emailsubject', emailsubject);
     data.append('emailboday', emailboday);
     data.append('BCC', BCC);
     data.append('fromname', fromname);
    
       
       
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
              
                var emailtemplatenamelist = jQuery.parseJSON(data);
                
                 jQuery("#emailtemplatelist").empty();
                 jQuery.each( emailtemplatenamelist, function( i, item ) {
                     
                     if(item == emailtemplatename){
                          
                          //jQuery("#reportlist").append("<option value="+item+" selected>"+item+"<option/>");
                          jQuery("#emailtemplatelist").append("<option value='"+item+"' selected='selected'>"+item+"</option>");
                        //  jQuery('#reportlist > option[value = '+item+'] ').attr('selected',true);
                          
                     }else{
                          
                         jQuery("#emailtemplatelist").append(jQuery("<option/>").attr("value", item).text(item));
                     }
                    
                });
                
           // jQuery( "#sponsor-status" ).empty();
             //  jQuery(function(e){ 
				//e.preventDefault();
				swal({
					title: "Success",
					text: "Email Template Saved Successfully.",
					type: "success",
					confirmButtonClass: "btn-success",
					confirmButtonText: "Ok"
				});
			//});
                 
             //    jQuery( "#sponsor-status" ).append( '<div class="alert wpb_content_element alert-success"><div class="messagebox_text"><p>Email Template Saved Successfully.</p></div></div>' );
                    setTimeout(function() {
                        jQuery( "#sponsor-status" ).empty();
                        }, 2000); // <-- time in milliseconds
                
               
               
               
                
            }});
    
    
    
}
function removeemailtemplate(){
    
   
     var emailtemplatename = jQuery("#emailtemplate").val();
     
     if(emailtemplatename != ""){
         
               
        swal({
            title: "Are you sure?",
            text: 'Click confirm to delete this email template.',
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
                conform_remove_email_template(emailtemplatename);
                swal({
                    title: "Deleted!",
                    text: "Email template deleted Successfully",
                    type: "success",
                    confirmButtonClass: "btn-success"
                }, function() {
                    var dropdownvalue = "defult";
                    jQuery("#example2").empty();
                    reportload(dropdownvalue);
                }
                );
            } else {
                swal({
                    title: "Cancelled",
                    text: "Email template is safe :)",
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
            }
        });
         
     
     }
     
    
    
}

function conform_remove_email_template(emailtemplatename){

     var url = window.location.protocol + "//" + window.location.host + "/";
     var urlnew = url + 'wp-content/plugins/EPGL/egpl.php?contentManagerRequest=remove_email_template';
     var data = new FormData();
     data.append('emailtemplatename', emailtemplatename);
 
     jQuery.ajax({
        url: urlnew,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(data) {
                    
                    
               var emailtemplatenamelist = jQuery.parseJSON(data);
               // console.log(emailtemplatenamelist+'------'+emailtemplatenamelist,length)
                 jQuery("#emailtemplatelist").empty();
                 
                 
              if(emailtemplatenamelist != null){
                 jQuery.each( emailtemplatenamelist, function( i, item ) {
                     
                     
                          
                         jQuery("#emailtemplatelist").append(jQuery("<option/>").attr("value", item).text(item));
                    
                    
                });
              }
               tinymce.activeEditor.setContent("");
                jQuery("#emailsubject").val("");
                jQuery("#emailtemplate").val("");
                jQuery("#fromname").val("");
                
             jQuery("#BCC").val("");
               // jQuery("#bodytext").cleditor()[0].refresh();
                jQuery("#showemailtemplate").hide();
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

function welcomeemail_preview(){
    
     var emailSubject =jQuery('#welcomeemailsubject').val();
     //var emailBody=jQuery('#welcomebodytext').html();
     var content;
     
     content = tinymce.activeEditor.getContent();
      
    
      
                                        jQuery.confirm({
                                             title: 'Preview!',
                                             content: '<p id="success-msg-div"></p> <br> Subject : '+emailSubject+' <hr> '+content+' <hr> <p style="margin-bottom: -69px !important;"><button type="button" title="Test email will be sent to '+currentAdminEmail+'" class="btn mycustomwidth btn-inline btn-primary examplebutton">Send me a Test Email</button></p>',
                                             confirmButton:'Save',
                                             cancelButton:'Close',
                                             testButton:'Send Test Email',
                                             confirmButtonClass: 'btn mycustomwidth btn-lg btn-primary mysubmitemailbutton',
                                             cancelButtonClass: 'btn-danger btn mycustomwidth btn-lg',
                                             onOpen: function() {
                                               
                                                this.$b.find('button.examplebutton').click(function() {
                                                 welcome_email_send_admin();
                                                jQuery( "#success-msg-div" ).append('<div class="alert wpb_content_element alert-success"><div class="messagebox_text"><p>we have send a test email on '+currentAdminEmail+' please check your mail.</p></div></div>');
                                               setTimeout(function() {
                                                jQuery( "#success-msg-div" ).empty();
                                                }, 5000);
                                                     
                                              });
    },
                                          
                                            confirm: function () {
                                               updateWelcomeMsg();
                                               this.setContent('<div class="alert wpb_content_element alert-success"><div class="messagebox_text"><p>Your message has been saved.</p></div></div>');
                                               jQuery('.mysubmitemailbutton').hide();
                                              
                                               return false;
                                            },
                                            cancel: function () {
                                              //  location.reload();
                                            },
                                            test: function () {
                                               
                                            }
                                       
                                        });
                                    
                                    
                 
                                    
                                    
    // jAlert( 'Subject : ' +emailSubject+ '<hr>'+
            // emailBody+'<hr><p style="text-align: center;margin-right: 56px;"><a  class="btn btn-danger" id="popup_ok" onclick="conform_send_bulk_email()">Send</a><a id="popup_okk" class="btn btn-info" style="margin-left: 20px;">Cancel</a></p>'); 
    
    
    
}
function welcome_email_send_admin(){
     
     
    var emailSubject =jQuery('#welcomeemailsubject').val();
    var emailBody=tinymce.activeEditor.getContent();//jQuery('#welcomebodytext').val();
    var welcomeemailfromname =jQuery('#welcomeemailfromname').val();
    var replaytoemailadd=jQuery('#replaytoemailadd').val();
     
    jQuery("body").css({'cursor':'wait'});
    var url = window.location.protocol + "//" + window.location.host + "/";
    var urlnew = url + 'wp-content/plugins/EPGL/egpl.php?contentManagerRequest=sendadmintestemailwelcome';
    var data = new FormData();
    data.append('emailSubject', emailSubject);
    data.append('emailBody', emailBody);
    data.append('welcomeemailfromname', welcomeemailfromname);
    data.append('replaytoemailadd', replaytoemailadd);
    
     jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                 jQuery('body').css('cursor', 'default');
                 //location.reload();
                // jQuery( "#sponsor-status" ).append( '<div class="alert wpb_content_element alert-success"><div class="messagebox_text"><p>Resource deleted.</p></div></div>' );
                // setTimeout(function() {
                  //      jQuery( "#sponsor-status" ).empty();
                // }, 2000); // <-- time in milliseconds
                
                
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
function updateWelcomeMsg(){
    
    var emailSubject =jQuery('#welcomeemailsubject').val();
    var emailBody=tinymce.activeEditor.getContent();//jQuery('#welcomebodytext').val();
    var welcomeemailfromname =jQuery('#welcomeemailfromname').val();
    var replaytoemailadd=jQuery('#replaytoemailadd').val();
    var BCC=jQuery('#BCC').val();
    
    jQuery("body").css({'cursor':'wait'});
    var url = window.location.protocol + "//" + window.location.host + "/";
    var urlnew = url + 'wp-content/plugins/EPGL/egpl.php?contentManagerRequest=updatewelocmecontent';
    var data = new FormData();
    data.append('welcomeemailSubject', emailSubject);
    data.append('welcomeemailBody', emailBody);
    data.append('welcomeemailfromname', welcomeemailfromname);
    data.append('replaytoemailadd', replaytoemailadd);
    data.append('BCC', BCC);
    jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                 jQuery('body').css('cursor', 'default');
                 //location.reload();
                // jQuery( "#sponsor-status" ).append( '<div class="alert wpb_content_element alert-success"><div class="messagebox_text"><p>Resource deleted.</p></div></div>' );
                // setTimeout(function() {
                  //      jQuery( "#sponsor-status" ).empty();
                // }, 2000); // <-- time in milliseconds
                
                
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
function keys_preview(){
    
    var checkedRows = waTable.getData(true);
    var datavaluesfields;
   var  areaId = "bodytext";
    
    var arrData = typeof checkedRows != 'object' ? JSON.parse(checkedRows) : checkedRows;
  
      
   for (var index in arrData['cols']) {
       if (typeof(arrData['cols'][index]) != "undefined") {
           
          
          if(arrData['cols'][index].column.indexOf("task") >= 0 ||arrData['cols'][index].column == 'action_edit_delete' || arrData['cols'][index].column == 'undefined'){
    
           }else{
              var keyvalue ='{'+arrData['cols'][index].column+'}';
              //console.log(arrData['cols'][index].column) ;
              datavaluesfields+='<a style="cursor: pointer;" class = "addmetafields" onclick=\'insertAtCaret("'+areaId+'","'+keyvalue+'")\' > '+keyvalue+'</a><br>';  
               
           }
               
          
       }  
         
         
    
    
    }
     datavaluesfields = datavaluesfields.replace('undefined', ''); 
                              currentmergetegpreivew  =           jQuery.confirm({
                                             title: 'Click a merge field below to insert in the editor',
                                             content: '<hr><p>'+datavaluesfields+'</p>',
                                             confirmButtonClass: 'btn mycustomwidth btn-lg btn-primary',
                                             confirmButton:'Close',
                                             cancelButton:false,
                                              
                                        });
                                    
      
    
    
}


function getpagecontent_foreditor(){

    
    jQuery("#contenteditor").hide();
    var pageID = jQuery( "#getallPagesContent option:selected" ).val();
   
    jQuery( "#pagetitle" ).val("");
   
    jQuery("#mycustomeditor").val("");
    var url = window.location.protocol + "//" + window.location.host + "/";
    var urlnew = url + 'wp-content/plugins/EPGL/egpl.php?contentManagerRequest=getpageContent';
    var data = new FormData();
    data.append('pageID', pageID);
    jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                jQuery("#contenteditor").show();
                var message = jQuery.parseJSON(data);
                jQuery("#pagetitle").val(message.pagetitle);
                jQuery("#mycustomeditor").val(message.pagecontent);
               
                jQuery( "#pagecontentid" ).val(pageID);
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

function conform_update_content_page(){
    
    var contenttitle =jQuery('#pagetitle').val();
    
    var contentbody =tinymce.activeEditor.getContent();
    if(contentbody == ""){
        
     contentbody   = jQuery('#mycustomeditor').val();
     
    }
   // console.log(contentbody);
    var contentbodyID =jQuery('#pagecontentid').val();
  
    var url = window.location.protocol + "//" + window.location.host + "/";
    var urlnew = url + 'wp-content/plugins/EPGL/egpl.php?contentManagerRequest=updatepagecontent';
    var data = new FormData();
    data.append('contenttitle', contenttitle);
    data.append('contentbody', contentbody);
    data.append('contentbodyID', contentbodyID);
    
    jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
              
                    
                 swal({
					title: "Success",
					text: 'Page Content Update Successfully.',
					type: "success",
					confirmButtonClass: "btn-success",
					confirmButtonText: "Ok"
				});
                
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

function welcome_available_merge_fields(){
      jQuery("body").css({'cursor':'wait'});
    var url = window.location.protocol + "//" + window.location.host + "/";
    var urlnew = url + 'wp-content/plugins/EPGL/egpl.php?contentManagerRequest=getavailablemergefields';
    var data = new FormData();
    var welcomedatafieldskeys="";
    
    var areaId = "welcomebodytext";
     jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
               jQuery('body').css('cursor', 'default');
               
               
               
               
               var keyslist = jQuery.parseJSON(data);
              
                jQuery.each( keyslist, function( i, item ) {
                    
                  var keyvalue = '{'+item+'}';
                  welcomedatafieldskeys+='<a style="cursor: pointer;" onclick=\'insertAtCaret("'+areaId+'","'+keyvalue+'")\' > '+keyvalue+'</a><br>';  
                    
                });
                
          currentmergetegpreivew =     jQuery.confirm({
                title: 'Click a merge field below to insert in the editor <hr>',
                content: welcomedatafieldskeys,
                confirmButton: 'Close',
                
                confirmButtonClass: 'btn mycustomwidth btn-lg btn-primary',
                cancelButton: false,
                
                

            });
                    
                
                
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

function insertAtCaret(areaId,text) {
    
    tinymce.activeEditor.execCommand('mceInsertContent', false, text);
    
    
    var txtarea = document.getElementById(areaId);
    var scrollPos = txtarea.scrollTop;
    var strPos = 0;
    var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? 
        "ff" : (document.selection ? "ie" : false ) );
    if (br == "ie") { 
        txtarea.focus();
        var range = document.selection.createRange();
        range.moveStart ('character', -txtarea.value.length);
        strPos = range.text.length;
    }
    else if (br == "ff") strPos = txtarea.selectionStart;

    var front = (txtarea.value).substring(0,strPos);  
    var back = (txtarea.value).substring(strPos,txtarea.value.length); 
    txtarea.value=front+text+back;
    strPos = strPos + text.length;
    if (br == "ie") { 
        txtarea.focus();
        var range = document.selection.createRange();
        range.moveStart ('character', -txtarea.value.length);
        range.moveStart ('character', strPos);
        range.moveEnd ('character', 0);
        range.select();
    }
    else if (br == "ff") {
        txtarea.selectionStart = strPos;
        txtarea.selectionEnd = strPos;
        txtarea.focus();
    }
    txtarea.scrollTop = scrollPos;
    currentmergetegpreivew.close();
}

function back_report(){
    
    
    
    jQuery("#bulkemailtab").hide();
    jQuery("#reportstab").show();
    
    
}

function sendwelcomemsg(){
    
    
    
    swal({
            title: "Are you sure?",
            text: 'You want to send the welcome email to the selected users? Their password will be reset and included in the email.',
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, Send it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {



            if (isConfirm) {
              var status = conform_send_welcomeemail_report();
              
              
                   swal({
					title: "Success",
					text: "Welcome email sent successfully.",
					type: "success",
					confirmButtonClass: "btn-success",
					confirmButtonText: "Ok"
				});  
                
             
            } else {
                swal({
                    title: "Cancelled",
                    text: "Welcome email was not sent",
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
            }
        });
    
}
function conform_send_welcomeemail_report(){
     
     
   //jQuery('#bodytext').val();
   var bulkemails = new Array();   
   
   var checkedRows = waTable.getData(true);
   var arrData = typeof checkedRows != 'object' ? JSON.parse(checkedRows) : checkedRows;
   for (var i = 0; i < arrData['rows'].length; i++) {
       
        bulkemails.push(arrData['rows'][i].Email);
    }
   
    
   
    if (bulkemails.length === 0) {
        
    }else{
        
        var length =bulkemails.length;
        jQuery('#welcomecustomeemail').val(bulkemails.join(", ")); 
     
    }
    
    var emailAddress=jQuery('#welcomecustomeemail').val();
    console.log(emailAddress);
    
    var checkedRows = waTable.getData(true);
    var arrData = typeof checkedRows != 'object' ? JSON.parse(checkedRows) : checkedRows;
   
   
     var statusmessage='';
     var alertclass='';
    
    jQuery("body").css({'cursor':'wait'});
    var url = window.location.protocol + "//" + window.location.host + "/";
    var urlnew = url + 'wp-content/plugins/EPGL/egpl.php?contentManagerRequest=sendcustomewelcomeemail';
    var data = new FormData();
   
   
    data.append('attendeeallfields',   JSON.stringify(arrData['rows']));
    data.append('datacollist',   JSON.stringify(arrData['cols']));
    data.append('emailAddress', emailAddress);

     jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                 jQuery('body').css('cursor', 'default');
                 
                 var message = jQuery.parseJSON(data);
                 
                 if(message == 'successfully send'){
                   
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