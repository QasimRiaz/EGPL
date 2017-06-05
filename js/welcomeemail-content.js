jQuery.noConflict();


 
    
jQuery(document).ready(function() { 
jQuery('#checknewuserdiv').on( "click", function() {
    
    if(jQuery("#checknewuser").is(':checked')) {
            
        jQuery("#showlistofselectwelcomeemail").show();
            
        }else {
            
        jQuery("#showlistofselectwelcomeemail").hide();
    }

});
jQuery('#bulkchecknewuserdiv').on( "click", function() {
    
    if(jQuery("#check-1").is(':checked')) {
            
        jQuery("#bulkshowlistofselectwelcomeemail").show();
            
        }else {
            
        jQuery("#bulkshowlistofselectwelcomeemail").hide();
    }

});


});




function loadmultiwelcomeemailtemplate(){
    
    
    
    var loadreportname = jQuery( "#loadmultiwelcomeemailtemplate option:selected" ).val();
   
    var url = window.location.protocol + "//" + window.location.host + "/";
    if(loadreportname !=""){
        
        if(loadreportname == 'welcome_email_template'){
            window.location.href = url + "welcome-email/";
        }else{
            window.location.href = url + "welcome-email/?loademailtemplate="+ encodeURI(loadreportname);
        }
   }
}

function multi_welcomeemail_save_template(){
     jQuery("body").css({'cursor':'wait'});
    var url = window.location.protocol + "//" + window.location.host + "/";
    var welcomeemailtemplatename = jQuery('#welcomeemailtemplatename').val();
    
    var emailSubject =jQuery('#welcomeemailsubject').val();
    var emailBody=tinymce.activeEditor.getContent();//jQuery('#welcomebodytext').val();
    var welcomeemailfromname =jQuery('#welcomeemailfromname').val();
    var replaytoemailadd=jQuery('#replaytoemailadd').val();
    var BCC=jQuery('#BCC').val();
    
    if(emailSubject !="" && emailBody !="" && welcomeemailfromname !="" ){
        
        var urlnew = url + 'wp-content/plugins/EGPL/userreport.php?contentManagerRequest=multitemplatewelcomeemail';
        var data = new FormData();
        data.append('welcomeemailtemplatename', welcomeemailtemplatename);
        data.append('emailSubject', emailSubject);
        data.append('emailBody', emailBody);
        data.append('replaytoemailadd', replaytoemailadd);
        data.append('BCC', BCC);
        data.append('welcomeemailfromname', welcomeemailfromname);
        jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                 jQuery('body').css('cursor', 'default');
                 swal({
					title: "Success",
					text: "Your message has been saved",
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
        
    
    }else{
        jQuery('body').css('cursor', 'default');
       
                                 swal({
					title: "Warning",
					text: "Some required fields are empty.",
					type: "warning",
					confirmButtonClass: "btn-warning",
					confirmButtonText: "Ok"
				}); 
      
        
    }
    
    
    
    
}


function multi_welcome_removeeuserreport(){
    
      var loadreportname = jQuery( "#loadmultiwelcomeemailtemplate option:selected" ).val();
      var url = window.location.protocol + "//" + window.location.host + "/";
      
    if(loadreportname != 'welcome_email_template'){
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
                        function (isConfirm) {



                            if (isConfirm) {

                            var sendwelcomeemailstatus = conform_multiemail_template_delete();
                                swal({
                                    title: "Success",
                                    text: "Email template delete successfully.",
                                    type: "success",
                                    confirmButtonClass: "btn-success",
                                    confirmButtonText: "Ok"
                                }, function () {
                                     window.location.href = url + "welcome-email/";
                                }
                                );

                            } else {
                                swal({
                                    title: "Cancelled",
                                    text: "Email template is safe.",
                                    type: "error",
                                    confirmButtonClass: "btn-danger"
                                });
                            }
                        });
    }else{
        
                                swal({
                                    title: "Error",
                                    text: "We cant remove defult welcome email template.",
                                    type: "error",
                                    confirmButtonClass: "btn-danger"
                                });
        
    }
                 
    
}

function conform_multiemail_template_delete(){
    
   var loadreportname = jQuery( "#loadmultiwelcomeemailtemplate option:selected" ).val();
   var url = window.location.protocol + "//" + window.location.host + "/";
   var urlnew = url + 'wp-content/plugins/EGPL/userreport.php?contentManagerRequest=multitemplatewelcomeemailremoved';
   var data = new FormData();
   data.append('welcomeemailtemplatename', loadreportname);
   jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
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