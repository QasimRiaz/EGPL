
jQuery.noConflict();

var filesizestatus = 0;
jQuery(document).ready(function() {
  
    jQuery( ".sf-sub-indicator" ).addClass( "icon-play" ); 
    
    
});

jQuery(document).ready(function () {
  //called when key is pressed in textbox
  jQuery(".quantitynumber").keypress(function (e) {
      
     //if the letter is not digit then display error and don't type anything
     
     if (e.which != 8 && e.which != 0 && (e.which < 46 || e.which > 57 || e.which == 47)) {
        //display error message
                                swal({
					title: "Warning",
					text: "Please enter a valid number.",
					type: "warning",
					confirmButtonClass: "btn-warning",
					confirmButtonText: "Ok"
				});
               return false;
    }
   });
});

 function closeIFramemain(){
       var parentsitename = "https://"+window.name+'/home';
       console.log(parentsitename);
       
        window.top.location.href = parentsitename;
       
    }
    
function movetolivesite(){
        
        
        window.top.location.href = "/landing-page";
        
    }

jQuery( document ).ready(function() {
    
    
   
    
    
    jQuery( ".remove_upload" ).click(function() {
        
        
        var id = jQuery(this).attr('id');
        swal({
            title: "Are you sure?",
            text: 'You want to remove this resource.',
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, remove it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
                function (isConfirm) {



                    if (isConfirm) {
                       
                       
                        myString = id.replace('remove_', '');
                        jQuery("input[name='" + myString + "']").val("");
                        var myClass = jQuery("#" + id).attr("class");
                        var myArray = myClass.split(' ');
                        jQuery("input[name$='" + myArray[0] + "']").val("");
                        jQuery("#hd_" + myArray[0]).val("");
                        jQuery("." + id).hide();
                        jQuery("." + myArray[0]).show();
                        swal({
                            title: "Removed!",
                            text: "Resource remove Successfully",
                            type: "success",
                            confirmButtonClass: "btn-success"
                        }, function () {
                            
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
         
         
         
         
   });
jQuery( ".sf-sub-indicator" ).addClass( "icon-chevron-right" ); 
    jQuery('textarea').each(function(){
      console.log('test');
        var maxLength = jQuery(this).attr('maxlength');
        var textareaid= jQuery(this).attr('id');
        var length = jQuery(this).val().length;
        var remininglength=maxLength-length;
        jQuery('#chars_'+textareaid).text(remininglength);
});
jQuery("input").change(function(event) {
       var id = jQuery(this).attr('id');
       var value = this.value;
      jQuery("#display_"+id).val(value);
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

var erroralert;
var  filestatus;
var erroralert;
function update_user_meta_custome(elem,typeoftask) {
    
    jQuery("body").css({'cursor':'wait'})
    var id = jQuery(elem).attr("id");
    erroralert = "";
    var sponsorid=getUrlParameter('sponsorid');
    var url = currentsiteurl+'/';
    var statusid = id.replace('update_', '');
    var statusvalue ;
    var value = statusid.replace('_status', '');
    var elementType = jQuery("#my" + value).is("input[type='file']"); //jQuery(this).prev().prop('tagName');
    var curdate = new Date();
    var usertimezone = curdate.getTimezoneOffset()/60;
    if (elementType == false) {
        
        var pattern = /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,15}(:[0-9]{1,5})?(\/.*)?$/g;
        var GetFieldurlValue = jQuery("#" + value).is("input[type='url']");
       
        
        
        
        if(GetFieldurlValue == true){
             var metaupdate = jQuery('#' + value).val();
             
             if(pattern.test(metaupdate)){
                 
                 
                 var checkstatus  = metaupdate.includes("http");
                  var checkstatuswww  = metaupdate.includes("www");
                if(checkstatus == false && checkstatuswww == false){
                     
                     
                     metaupdate = "https://www."+metaupdate;
                     
                 }else if(checkstatus == false && checkstatuswww == true){
                     
                     metaupdate = "https://"+metaupdate;
                     
                 }
                if(metaupdate !=""){

                    statusvalue = 'Complete';

                    }else{

                    statusvalue = 'Pending';
                }

        

        jQuery.ajax({url: url + 'wp-content/plugins/EGPL/usertask_update.php?usertask_update=update_user_meta_custome',
            data: {action: value, updatevalue: metaupdate, status: statusvalue,sponsorid:sponsorid,usertimezone:usertimezone},
            type: 'post',
            success: function(output) {
				//console.log("Hello world 1");
             //alert("Hello! I am an alert box!!");
               filestatus=true;
               jQuery("body").css({'cursor':'default'});
               if(metaupdate !=""){
                   
                   jQuery('#update_'+value+'_remove').removeClass('specialremoveicondisable');
                   jQuery("." + value+'_submissionstatus').css( "background-color", "#d5f1d5");
                   jQuery('#update_'+value+'_remove').addClass('specialremoveiconenable');
                   jQuery('#'+id).children('.content').text('Submitted');
                   
                   
               }
               if(sponsorid){
                   swal({
                                 title: "Success",
                                 text: "Value has been updated successfully.",
                                 type: "success",
                                 confirmButtonClass: "btn-success",
                                 confirmButtonText: "Ok"
                             });
                }else{
                    
                    if(metaupdate !=""){
                        jQuery('#'+id).addClass('disableremovebutton');
                        jQuery("#" + value).prop("disabled", true);
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
            }else{
                
                filestatus=true;
                erroralert = "faildvaildurl";
                jQuery("body").css({'cursor':'default'});
                
            }
            
        }else{
            
             var metaupdate = jQuery('#' + value).val();
             var mutiselecterrocontroll = "clear";
             if(metaupdate !=""){

                statusvalue = 'Complete';

                }else{

                statusvalue = 'Pending';
             }

        if(typeoftask == "multivaluedtask"){
             
            var filedname = 'input[name="'+value+'[]"]';
             console.log(filedname);
            
            var alltaskvalues = jQuery(filedname).map(function () {
                
                    console.log(this.value);
                    if(this.value == ""){
                         mutiselecterrocontroll = "errorEmpty";
                         filestatus=true;
                         jQuery("body").css({'cursor':'default'});
                          swal({
                                 title: "Empty Field",
                                 text: "There is one or more empty fields. Please either enter a value or remove them.",
                                 type: "error",
                                 confirmButtonClass: "btn-danger",
                                 confirmButtonText: "Ok"
                             });
                        return false;
                    }else{
                       
                     return this.value; // $(this).val()
                        
                    }    
                       
            }).get();
            if(mutiselecterrocontroll !="errorEmpty"){
                 jQuery(".speicaltaskmulittask_"+value).attr("disabled","disabled");
            }
            metaupdate = JSON.stringify(alltaskvalues);
            
        }
          if(mutiselecterrocontroll !="errorEmpty"){
        jQuery.ajax({url: url + 'wp-content/plugins/EGPL/usertask_update.php?usertask_update=update_user_meta_custome',
            data: {action: value, updatevalue: metaupdate, status: statusvalue,sponsorid:sponsorid,usertimezone:usertimezone,typeoftask:typeoftask},
            type: 'post',
            success: function(output) {
            
               filestatus=true;
               jQuery("body").css({'cursor':'default'});
               if(metaupdate !=""){
                   
                   jQuery('#update_'+value+'_remove').removeClass('specialremoveicondisable');
                   jQuery("." + value+'_submissionstatus').css( "background-color", "#d5f1d5");
                   jQuery('#update_'+value+'_remove').addClass('specialremoveiconenable');
                   jQuery('#'+id).children('.content').text('Submitted');
                   
                   
               }
               if(sponsorid){
                   
                    swal({
                                 title: "Success",
                                 text: "Value has been updated successfully.",
                                 type: "success",
                                 confirmButtonClass: "btn-success",
                                 confirmButtonText: "Ok"
                             });
                }else{
                    
                    if(metaupdate !=""){
                        jQuery('#'+id).addClass('disableremovebutton');
                        jQuery("#" + value).prop("disabled", true);
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
            
            
        }
       
        
        
        
    } else {

        //var metaupdate =jQuery('#my'+value).val();

        var file = jQuery('#my' + value)[0].files[0];
        console.log(file);
        if(file != undefined && file != ""  ){
            
           var filezier = parseInt(jQuery('#my' + value)[0].files[0].size);
           var convertintombs = filezier/(1024*1024);
            
        }else{
            var convertintombs = 1
        }
        if(file){
            
            statusvalue = 'Complete';
            
        }else{
           
            statusvalue = 'Pending';
        }
        console.log(statusvalue);
       
        if(convertintombs < 50){
        
        
        
        // if (typeof(file) != 'undefined') {
        var lastvalue = jQuery('#hd_' + value).val();
        var data = new FormData();
        data.append('file', file);
        data.append('action', value);
        data.append('status', statusvalue);
        data.append('lastvalue', lastvalue);
        data.append('sponsorid',sponsorid);
        data.append('usertimezone',usertimezone);
        
        var urlnew = url + 'wp-content/plugins/EGPL/usertask_update.php?usertask_update=user_file_upload';


        //console.log(file);
        jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                var speratdata = data.split('////');
                var alertmessage = jQuery.parseJSON(speratdata[1]);
                    
                
                
                if (typeof(alertmessage.error) != 'undefined') {
                  
                    
                    
                   if(alertmessage.error == "Sorry, this file type is not permitted for security reasons."){
                        
                        
                         var sponsorid=getUrlParameter('sponsorid');
                         if(sponsorid){
                             
                              swal({
                                title: "Error",
                                text: "This file type is not permitted for security reasons or the file extension is invalid.",
                                type: "error",
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "Ok"
                            });
                             
                         }
                         
                         filestatus=true;
                         erroralert = 'faildfileextenshion';
                         jQuery("body").css({'cursor':'default'});
                          
                         
                    }else if (alertmessage.error != "Empty File") {

                        erroralert = true;
                        filestatus=true;
                        jQuery("body").css({'cursor':'default'});
                        var sponsorid=getUrlParameter('sponsorid');
                         if(sponsorid){
                             
                              swal({
                                title: "Error",
                                text: alertmessage.error,
                                type: "error",
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "Ok"
                            });
                             
                         }
                        
                    }else{
                        
                        filestatus=true;
                        jQuery("body").css({'cursor':'default'});
                        
                        var sponsorid=getUrlParameter('sponsorid');
                         if(sponsorid){
                             
                              swal({
                                title: "Error",
                                text: alertmessage.error,
                                type: "error",
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "Ok"
                            });
                             
                         }
                    }

                } else {
                    
                    if(file !=""){
                   
                        jQuery('#update_'+value+'_remove').removeClass('specialremoveicondisable');
                        jQuery("." + value+'_submissionstatus').css( "background-color", "#d5f1d5");
                        jQuery('#update_'+value+'_remove').addClass('specialremoveiconenable');
                        jQuery('#'+id).children('.content').text('Submitted');
                        jQuery('#'+id).addClass('disableremovebutton');
                   
                    }
                    filestatus=true;
                    jQuery("body").css({'cursor':'default'})
                    location.reload();

                }
                //alert(alertmessage);
            },error: function (alertmessage, ajaxOptions, thrownError) {
                
                    
                    swal({
					title: "Error",
					text: alertmessage.error,
					type: "error",
					confirmButtonClass: "btn-danger",
					confirmButtonText: "Ok"
				});
      }
        });
        // }
    }else{
            jQuery("body").css({'cursor':'default'});
            swal({
                title: "File too large",
                text: "Could not upload. File size must be less than 50MB.",
                type: "error",
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Ok"
            },function(){
                
                location.reload();
                
            });
        
        
    }
        //alert(metaupdate);
        //l.stop();
    }
    
    
    
}
jQuery(document).ready(function() {
    
   [].slice.call( document.querySelectorAll( 'button.taskcustomesubmit' ) ).forEach( function( bttn ) {
       
                               
				new ProgressButton( bttn, {
					callback : function( instance ) {
						var progress = 0,
							interval = setInterval( function() {
								progress = Math.min( progress + Math.random() * 0.5, 1 );
								instance._setProgress( progress );

								if( filestatus === true ) {
                                                                    
                                                                    if(erroralert == true){
									instance._stop(-1);
                                                                        erroralert=false;
                                                                        swal({
                                                                                title: "Error",
                                                                                text: "There was an error during the requested operation. Please try again.",
                                                                                type: "error",
                                                                                confirmButtonClass: "btn-danger",
                                                                                confirmButtonText: "Ok"
                                                                        });
                                                                        
                                                                    }else if(erroralert == "faildfileextenshion"){
                                                                        
                                                                               swal({
                                                                                           title: "Error",
                                                                                           text: "This file type is not permitted for security reasons or the file extension is invalid.",
                                                                                           type: "error",
                                                                                           confirmButtonClass: "btn-danger",
                                                                                           confirmButtonText: "Ok"
                                                                                   });
                                                                    }else if(erroralert == "faildvaildurl"){
                                                                                instance._stop(1);
                                                                               swal({
                                                                                           title: "Error",
                                                                                           text: "Url is not valid. Provide a valid url (e.g. https://www.domain.com).",
                                                                                           type: "error",
                                                                                           confirmButtonClass: "btn-danger",
                                                                                           confirmButtonText: "Ok"
                                                                                   });
                                                                    }else{
                                                                        instance._stop(1);
                                                                    }
									clearInterval( interval );
                                                                        filestatus=false;
								}
							}, 200 );
					}
				} );
                            
                            
			} );
});


function remove_task_value_readyfornew(e,typeoftask){
    
    
     var removebuttonid = jQuery(e).attr('id');
     var task_name_key = jQuery(e).attr('name');
     var url = currentsiteurl+'/';
     var elementType = jQuery("#my" + task_name_key).is("input[type='file']");
     var curdate = new Date();
     var usertimezone = curdate.getTimezoneOffset()/60;
     console.log(typeoftask) ;
     var tasktype='';
     if (elementType == false) {
         
         
         
          swal({
            title: "Are you sure?",
            text: 'You want to remove your submission?',
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, remove it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
                function (isConfirm) {



                    if (isConfirm) {
                       
                        update_task_submission_status(task_name_key,tasktype);
                        console.log(typeoftask) ;
                        if(typeoftask == "multivaluedtask"){
                            
                            
                            jQuery(".speicaltaskmulittask_" + task_name_key).prop("disabled", false);
                            
                        }else{
                            
                            
                            jQuery("#" + task_name_key).prop("disabled", false);
                            
                            
                            
                        }
                            jQuery("." + task_name_key+'_submissionstatus').removeAttr('style');
                            jQuery('#' + removebuttonid).removeClass('specialremoveiconenable');
                            jQuery('#' + removebuttonid).addClass('specialremoveicondisable');
                            jQuery('#update_' + task_name_key + '_status').children('.content').text('Submit');
                            jQuery('#update_' + task_name_key + '_status').removeClass('disableremovebutton');
                        
                        swal({
                            title: "Removed!",
                            text: "Submission successfully removed.",
                            type: "success",
                            confirmButtonClass: "btn-success"
                        }, function () {
                            
                        }
                        );
                    } else {
                        swal({
                            title: "Cancelled",
                            text: "Submission is safe :)",
                            type: "error",
                            confirmButtonClass: "btn-danger"
                        });
                    }
                });
         
         
         
     }else{
         
         swal({
            title: "Are you sure?",
            text: 'You want to remove your submission?',
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, remove it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
                function (isConfirm) {



                    if (isConfirm) {
                        tasktype='fileupload';
                        update_task_submission_status(task_name_key,tasktype);
                        jQuery("." + task_name_key+'_submissionstatus').removeAttr('style');
                        
                        myString = task_name_key;
                        jQuery("input[name='" + myString + "']").val("");
                        jQuery("input[name$='" + myString + "']").val("");
                        jQuery("#hd_" + myString).val("");
                        jQuery(".remove_" + myString).hide();
                        jQuery("." + myString).show();
                        jQuery('#' + removebuttonid).removeClass('specialremoveiconenable');
                        jQuery('#' + removebuttonid).addClass('specialremoveicondisable');
                        jQuery('#update_' + task_name_key + '_status').children('.content').text('Submit');
                        jQuery('#update_' + task_name_key + '_status').removeClass('disableremovebutton');
                        swal({
                            title: "Removed!",
                            text: "Submission remove Successfully",
                            type: "success",
                            confirmButtonClass: "btn-success"
                        }, function () {
                            
                        }
                        );
                    } else {
                        swal({
                            title: "Cancelled",
                            text: "Submission is safe :)",
                            type: "error",
                            confirmButtonClass: "btn-danger"
                        });
                    }
                });
         
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

function update_task_submission_status(submissiontaskstatuskey,tasktype){
    
    
    
    var sponsorid=getUrlParameter('sponsorid');
    var url = currentsiteurl+'/';
    var urlnew = url + 'wp-content/plugins/EGPL/usertask_update.php?usertask_update=update_submission_status';
    var data = new FormData();
    
    var curdate = new Date();
        var usertimezone = curdate.getTimezoneOffset()/60;
    data.append('sponsorid',   sponsorid);
    data.append('tasktype',   tasktype);
    data.append('usertimezone',usertimezone);
    data.append('submissiontaskstatuskey',   submissiontaskstatuskey);
    jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
            
                
                
                
            }
        });
   
    
    
    
    
}


function addnewmultivalueinput(taskid,limit){
    
    
    var countlimit =  jQuery(".specialcountclass_"+taskid).length;
    console.log(countlimit);
    if(limit == ""){
        
        
        var r = "'"+Math.random().toString(36).substring(7)+"'";
        var arrayvalue  = taskid+"[]";
        var appendhtml = '<p id='+r+'><input style="width: 80% !important;margin-top: 1px;"  class="myclass specialcountclass_'+taskid+'  speicaltaskmulittask_'+taskid+'" type="text" name="' + arrayvalue+'" /> <button style="width: 17%;" class="speicaltaskmulittask_'+taskid+' btn btn-danger btn-small" onclick="removethisvaluetask('+r+')" title="Delete"><i class="fas fa-trash"></i></button></p>';
        jQuery(".multivaluetask_"+taskid).append(appendhtml);
        console.log(countlimit);
        
    }else{
    if(countlimit == limit || countlimit > limit ){
        
       //jQuery(".disableclassbutton_"+taskid).attr("disabled",true);
        
    }else{
        
        var r = "'"+Math.random().toString(36).substring(7)+"'";
        var arrayvalue  = taskid+"[]";
        var appendhtml = '<p id='+r+'><input style="width: 80% !important;margin-top: 1px;"  class="myclass specialcountclass_'+taskid+' speicaltaskmulittask_'+taskid+'" type="text" name="' + arrayvalue+'" /> <button style="width: 17%;" class="speicaltaskmulittask_'+taskid+' btn btn-danger btn-small" onclick="removethisvaluetask('+r+')" title="Delete"><i class="fas fa-trash"></i></button></p>';
        jQuery(".multivaluetask_"+taskid).append(appendhtml);
    }}
    
}

function removethisvaluetask(r){
    
    jQuery("#"+r).remove();
    //jQuery(".disableclassbutton_"+taskid).attr("disabled",false);
    
}

function downloadfontendfile(taskname,userid) {

    

    

        
    var data = new FormData();
    var url = currentsiteurl+'/';
    var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=get_current_file_url';
    data.append('taskname', taskname);
    data.append('userid', userid);
     jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
               jQuery('#hiddenform').empty();
               if(jQuery.parseJSON(data) !=null){
                   var imageurl = jQuery.parseJSON(data)
                  
               var hiddenformhtml ="";
                 hiddenformhtml += '<form id="myform" action="'+url+'wp-content/plugins/EGPL/singlefile_download.php" method="post"><input type="hidden" name="zipfoldername" value="'+taskname+'">';
                
                 
                   
                     hiddenformhtml += '<input type="hidden" name="result[]" value="'+ imageurl+ '">';
                
                hiddenformhtml += '</form>' ;
                
                
                jQuery( "#hiddenform" ).append(hiddenformhtml);
                
                 document.getElementById('myform').submit();
             }else{
                 swal({
									title: "Error",
									text: "There are no files uploaded for this selected task.",
									type: "error",
									confirmButtonClass: "btn-danger"
								});
             }
            }
        });
        
}