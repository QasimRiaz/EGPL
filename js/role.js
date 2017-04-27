   jQuery(document).ready(function() {
   jQuery('#assignnewtask').on( 'click', function () {
          
          
          
        var alreadyexist = new Array();
          jQuery('.assignedtasks').each(function (i, selected1) {
                
                    alreadyexist.push(jQuery(selected1).attr('id'));
                    //console.log(jQuery('.assignedtasks').attr('id'));
             });
             
         // console.log(alreadyexist);
        jQuery('#addnewroleassignment :selected').each(function (i, selected) {
           var  valuereturn = jQuery.inArray(jQuery(selected).val(), alreadyexist);
           // console.log(valuereturn)
            
            
          if(valuereturn < 0){
                   
             var rowNode = roleassignmenttable.row.add([
                        '<p class="assignedtasks" id="' + jQuery(selected).val() + '">' + jQuery(selected).text() + '</p>',
                        '<i style=" cursor: pointer;margin-left: 10px;" onclick="removetask_forthisrole(this)" title="Remove this task" class="fusion-li-icon fa fa-times-circle fa-2x" style="color:#262626;"></i>'

                    ]).draw().nodes().to$().addClass("addnewtaskintorole"); 
                }         
           
           
          
        
          });
   });    

  }); 







function add_new_role_contentmanager(){
    
     var rolename =jQuery('#rolename').val();
     jQuery("body").css({'cursor':'wait'});
     var specialcharacterstatuslevelname=false;
     var url = window.location.protocol + "//" + window.location.host + "/";
     var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=addnewrole';
     var data = new FormData();
     data.append('rolename', rolename);
     
     if(/^[ A-Za-z0-9_()\-]*$/.test(rolename) == false) {
           specialcharacterstatuslevelname = true;
     }else{
         specialcharacterstatuslevelname = false;
     }
    if(specialcharacterstatuslevelname == false){
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
					title: msg.title,
					text: msg.msg,
					type: msg.status,
                                        html:true,
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
    }else{
        jQuery('body').css('cursor', 'default');
          swal({
					title: "Error",
					text: "Invalid characters used in Level name. Please remove and try again.",
					type: "error",
					confirmButtonClass: "btn-danger",
					confirmButtonText: "Ok"
				});
        
    }
      
                                       
                                    
                                    

    
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
									text: "Level deleted successfully",
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
     var lockTWMcomplete;
     var lockTWMduedate;
     if(jQuery('#check-toggle-1').is(':checked')){
         lockTWMcomplete = 'checked';
     }else{
         lockTWMcomplete = 'unchecked';
     }
     if(jQuery('#check-toggle-2').is(':checked')){
         lockTWMduedate = 'checked';
     }else{
         lockTWMduedate = 'unchecked';
     }
     data.append('eventdate', eventdate);
     data.append('lockTWMcomplete', lockTWMcomplete);
     data.append('lockTWMduedate', lockTWMduedate);
    // data.append('formemail', formemail);
   
     
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
 function roleassignednewtask(){
     
      jQuery("body").css({'cursor':'wait'});
      var taskdataupdatelist = new Array();
      var removetasklist = new Array();
      var rolename = jQuery('#editrolename').val();
      jQuery('.assignedtasks').each(function (i, selected1) {
                
                    taskdataupdatelist.push(jQuery(selected1).attr('id'));
                    //console.log(jQuery('.assignedtasks').attr('id'));
      });
      jQuery('.removeitems').each(function (i, selected1) {
                
                    removetasklist.push(jQuery(selected1).attr('id'));
                    //console.log(jQuery('.assignedtasks').attr('id'));
      });
      
      
    var url = window.location.protocol + "//" + window.location.host + "/";
    var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=roleassignnewtasks';
    var data = new FormData();
    
    data.append('roleassigntaskdatalist',   JSON.stringify(taskdataupdatelist));
    data.append('removetasklist',   JSON.stringify(removetasklist));
    data.append('rolename',   rolename);
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
                    title: "Updated!",
                    text: "Tasks assigned successfully",
                    type: "success",
                    confirmButtonClass: "btn-success"
                },
        function(isConfirm) {
            
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
                                   
function removetask_forthisrole(e){
     
     swal({
            title: "Are you sure?",
            text: 'Click confirm to unassign this Task.',
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: false
        },function(isConfirm) {

            
           
            if (isConfirm) {
                roleassignmenttable.row( jQuery(e).parents('tr') ).draw().nodes().to$().addClass("removetaskrole");
                jQuery(e).parents('tr').children('td').children('p').removeClass('assignedtasks');
                jQuery(e).parents('tr').children('td').children('p').addClass('removeitems');
                swal({
                    title: "Unassigned!",
                    text: "Task unassigned successfully",
                    type: "success",
                    confirmButtonClass: "btn-success"
                }
                );
            } else {
                swal({
                    title: "Cancelled",
                    text: "Task is safe :)",
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
            }
        });    
     
     
     
 }
 
 
function editrolename(e){
   
    var rolekey = jQuery(e).attr('id');
    var oldrolename = jQuery(e).attr('name');
    var url = window.location.protocol + "//" + window.location.host + "/";
    var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=editrolekey';
    var data = new FormData();
    
    data.append('rolekey',   rolekey);
    
    
    swal({
		title: "Edit Level Name",
		text: '',
		type: 'input',
                inputValue:oldrolename,
		showCancelButton: true,
		closeOnConfirm: false,
		animation: "slide-from-top",
		inputPlaceholder: "Level Name",
	},
	function(inputValue){
		if (inputValue === false) return false;

		if (inputValue === "") {
			swal.showInputError("You need to write something!");
                        jQuery('body').css('cursor', 'default');
			return false;
		}
                data.append('rolenewname',   inputValue);
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
                                text: 'Edit Level Name: ' + inputValue+' changed successfully',
                                type: "success",
                                confirmButtonClass: "btn-success"
                            },
                                    function (isConfirm) {

                                        location.reload();
                                    }

                            );
                   }else{
                       swal.showInputError("A Level with that name already exists Please try another name.");
                   } 
                    }
                });
		

	});
    
    
}

function createroleclone(e){
   
    var rolekey = jQuery(e).attr('id');
    var oldrolename = jQuery(e).attr('name');
    
    var url = window.location.protocol + "//" + window.location.host + "/";
    var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=createlevelclone';
    var data = new FormData();
    
    
    data.append('clonerolekey',   rolekey);
    
    swal({
		title: "Create Clone Level",
		text: '',
		type: 'input',
                inputValue:'Copy of '+oldrolename,
		showCancelButton: true,
		closeOnConfirm: false,
		animation: "slide-from-top",
		inputPlaceholder: "Level Name",
	},
	function(inputValue){
		if (inputValue === false) return false;

		if (inputValue === "") {
			swal.showInputError("You need to write something!");
			return false;
		}
                data.append('rolename',   jQuery.trim(inputValue));
                jQuery.ajax({
                    url: urlnew,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function(data) {
                        
                        jQuery('body').css('cursor', 'default');
                        var finalresult = jQuery.parseJSON(data);
                        if(finalresult.msg == 'New Level created' ){
                            
                            
                            
                            swal({
                                title: "Success!",
                                text: 'New Level: ' + inputValue+' created successfully',
                                type: "success",
                                confirmButtonClass: "btn-success"
                            },
                                    function (isConfirm) {

                                        location.reload();
                                    }

                            );
                           
                                
                        }else{
                        
                            swal.showInputError(finalresult.msg);
                        }
                        
                        
                        //location.reload();
                    }
                });
		

	});
    
    
}