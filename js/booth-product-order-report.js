
jQuery(document).ready(function () {

    
    jQuery('#boothreview').DataTable({});
    
    
    
 
});

function approvethisbooth(elem){
 
  
 
 
   
    var postID = jQuery(elem).attr("id");
    var orderID = jQuery(elem).attr("name");
    jQuery.confirm({
        title: '<p style="text-align:center;" >Are you sure?</p>',
        content: '<p><h3 style="text-align:center;">Do you want to assign this Booth to the selected exhibitor? </h3></p>',
        confirmButton: 'Yes, Assign it!',
        cancelButton: 'No, cancel please!',
       
        confirmButtonClass: 'btn mycustomwidth btn-lg btn-primary',
        cancelButtonClass: 'btn  btn-lg btn-danger',
       
        
        confirm: function () {
            jQuery("body").css("cursor", "wait");
           
            jQuery(".fa-check-circle-o").css("cursor", "not-allowed");
           
            conform_approvethis_booth(postID,orderID);
           
        },
        cancel: function () {
            //  location.reload();
        }

    });

}

function conform_approvethis_booth(postID,orderID){
    
    //  console.log(idsponsor);
     jQuery(".confirm").attr('disabled','disabled');
     var url = currentsiteurl+'/';
     
     var urlnew = url + 'wp-content/plugins/EGPL/reviewbooth.php?contentManagerRequest=approve_booth';
     var data = new FormData();
    
    
     
     data.append('postid', postID);
     data.append('orderID', orderID);
   
     
     jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
               jQuery("body").css({'cursor':'default'}); 
                swal({
                    title: "Approved!",
                    text: "Booth has been assigned to the selected exhibitor.</br>",
                    type: "success",
                    html: true,
                    confirmButtonClass: "btn-success"
                }, function () {
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



function declinethisbooth(elem){

 var idsponsor = jQuery(elem).attr("id");
 

                                                swal({
							title: "Are you sure?",
							text: 'Do you want to decline booth assignment request? ',
							type: "warning",
							showCancelButton: true,
							confirmButtonClass: "btn-danger",
							confirmButtonText: "Yes, decline it!",
							cancelButtonText: "No, cancel please!",
							closeOnConfirm: false,
							closeOnCancel: false
						},
						function(isConfirm) {
                                                    
                                                    
                                                     
							if (isConfirm) {
                                                             var Sname = conform_declinethis_booth(idsponsor);
								swal({
									title: "Declined!",
									text: "Booth assignment request declined successfully. Any associated payments will have to be refunded manually.",
									type: "success",
									confirmButtonClass: "btn-success"
								},function() {
                                                                    location.reload();
                                                                 }
                                                            );
							} else {
								swal({
									title: "Cancelled",
									text: "Booth is safe :)",
									type: "error",
									confirmButtonClass: "btn-danger"
								});
							}
						});
    
}
function conform_declinethis_booth(idsponsor){
    
    //  console.log(idsponsor);
     
     var url = currentsiteurl+'/';
    
     var urlnew = url + 'wp-content/plugins/EGPL/reviewbooth.php?contentManagerRequest=decline_booth';
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
