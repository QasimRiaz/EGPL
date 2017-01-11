
jQuery.noConflict();

/*function update_time_stamp(elem){
 
 
 var url      = window.location.protocol + "//" + window.location.host + "/";
 var id = jQuery(elem).attr("id");
 var value =id.replace('status', 'datetime');
 var statusvlaue = jQuery("#"+id+" option:selected").text();
 if(statusvlaue == 'Complete'){
 jQuery.ajax({ url: url+'wp-content/plugins/user-forms-stats/user-forms-stats.php?f=update_task_datetime',
 data: {action:value},
 type: 'post',
 success: function(output) {
 //alert(statusvlaue);
 }
 });
 }else{
 // alert(statusvlaue);
 }
 }*/
jQuery(document).ready(function() {
  
    jQuery( ".sf-sub-indicator" ).addClass( "icon-play" ); 
    
    
});

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
                        console.log('test');
                       
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

  
  console.log('11111111111111111111111111111111111111');
 
    
 
   
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
    var sponsorid=getUrlParameter('sponsorid');
    
    var url = window.location.protocol + "//" + window.location.host + "/";
    var statusid = id.replace('update_', '');
    var statusvalue = jQuery('#' + statusid + " option:selected").text()
    var value = statusid.replace('_status', '');
    var elementType = jQuery("#my" + value).is("input[type='file']"); //jQuery(this).prev().prop('tagName');
    if (elementType == false) {
        var metaupdate = jQuery('#' + value).val();




    jQuery.ajax({url: url + 'wp-content/plugins/EGPL/usertask_update.php?usertask_update=update_user_meta_custome',
            data: {action: value, updatevalue: metaupdate, status: statusvalue,sponsorid:sponsorid},
            type: 'post',
            success: function(output) {
             
               filestatus=true;
               jQuery("body").css({'cursor':'default'});
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

        //alert(metaupdate);
        //l.stop();
    }
}
jQuery(document).ready(function() {
   [].slice.call( document.querySelectorAll( 'button.progress-button' ) ).forEach( function( bttn ) {
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
// Bind normal buttons


// You can control loading explicitly using the JavaScript API
// as outlined below:

// var l = Ladda.create( document.querySelector( 'button' ) );
// l.start();
// l.stop();
// l.toggle();
// l.isLoading();
// l.setProgress( 0-1 );
