








function add_new_role_EGPL(){
    
     var rolename =jQuery('#rolename').val();
     jQuery("body").css({'cursor':'wait'});
     var url = window.location.protocol + "//" + window.location.host + "/";
     var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=addnewrole';
     var data = new FormData();
     data.append('rolename', rolename);
      jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                 jQuery('body').css('cursor', 'default');
                   var msg = jQuery.parseJSON(data);
                    jQuery("form")[0].reset();
                 //location.reload();
                 jQuery( "#sponsor-status" ).append( '<div class="alert wpb_content_element alert-success"><div class="messagebox_text"><p>'+msg.msg+'</p></div></div>' );
                swal({
					title: "Success",
					text: msg.msg,
					type: "success",
					confirmButtonClass: "btn-success",
					confirmButtonText: "Ok"
				},function() {
                                                                    location.reload();
                                                                 }
                            
            );
               
                
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
function delete_role_name(elem){
     
     
   
   
     var rolename =jQuery(elem).attr("id");
     var viewrolename= rolename.replace("_", " ");
      swal({
							title: "Are you sure?",
							text: 'you want to remove this level: '+viewrolename.toUpperCase(),
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
                                                             var Sname =  delete_role_name_conform(rolename);
								swal({
									title: "Deleted!",
									text: "Level deleted Successfully",
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
function delete_role_name_conform(namerole){
    var rolename =namerole;
     jQuery("body").css({'cursor':'wait'});
     var url = window.location.protocol + "//" + window.location.host + "/";
     var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=removerole';
     var data = new FormData();
     data.append('rolename', rolename);
      jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                 jQuery('body').css('cursor', 'default');
                   var msg = jQuery.parseJSON(data);
                    jQuery("form")[0].reset();
                 //location.reload();
                
                
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

function update_admin_settings(){
    
    
    // var formemail = jQuery('#formemailaddress').val();
     var eventdate = jQuery('#eventdate').val();
   
    
     jQuery("body").css({'cursor':'wait'});
     var url = window.location.protocol + "//" + window.location.host + "/";
     var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=adminsettings';
     var data = new FormData();
     
     data.append('eventdate', eventdate);
    // data.append('formemail', formemail);
   
     
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
					text: "Content Manager Settings Updated",
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