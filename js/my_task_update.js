
jQuery.noConflict();

var filesizestatus = 0;
jQuery(document).ready(function() {
  
    jQuery( ".sf-sub-indicator" ).addClass( "icon-play" ); 
    
    
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
function update_user_meta_custome(elem) {
    
    jQuery("body").css({'cursor':'wait'})
    var id = jQuery(elem).attr("id");
    console.log(id)
    var sponsorid=getUrlParameter('sponsorid');
    
    var url = currentsiteurl+'/';
    var statusid = id.replace('update_', '');
    var statusvalue ;
    var value = statusid.replace('_status', '');
    var elementType = jQuery("#my" + value).is("input[type='file']"); //jQuery(this).prev().prop('tagName');
    if (elementType == false) {
        var metaupdate = jQuery('#' + value).val();
        if(metaupdate !=""){
            
            statusvalue = 'Complete';
            
        }else{
           
            statusvalue = 'Pending';
        }



    jQuery.ajax({url: url + 'wp-content/plugins/EGPL/usertask_update.php?usertask_update=update_user_meta_custome',
            data: {action: value, updatevalue: metaupdate, status: statusvalue,sponsorid:sponsorid},
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
                    //console.log(alertmessage.error);
                    if (alertmessage.error != "Empty File") {

                        erroralert = true;
                        filestatus=true;
                        jQuery("body").css({'cursor':'default'});
                        
                    }else{
                        filestatus=true;
                         jQuery("body").css({'cursor':'default'});
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


function remove_task_value_readyfornew(e){
    
    
     var removebuttonid = jQuery(e).attr('id');
     var task_name_key = jQuery(e).attr('name');
     var url = currentsiteurl+'/';
     var elementType = jQuery("#my" + task_name_key).is("input[type='file']");
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
                        jQuery("." + task_name_key+'_submissionstatus').removeAttr('style');
                   
                   
 
                        jQuery("#" + task_name_key).prop("disabled", false);
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
    data.append('sponsorid',   sponsorid);
    data.append('tasktype',   tasktype);
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


// Bind normal buttons


// You can control loading explicitly using the JavaScript API
// as outlined below:

// var l = Ladda.create( document.querySelector( 'button' ) );
// l.start();
// l.stop();
// l.toggle();
// l.isLoading();
// l.setProgress( 0-1 );
