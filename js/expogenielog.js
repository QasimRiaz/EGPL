
function getpreactiondata(actionname,postid) {

    var data = new FormData();
    var url = currentsiteurl+'/';
    var urlnew = url + 'wp-content/plugins/EGPL/userreport.php?contentManagerRequest=getexpologsentries';
    data.append('actionname', actionname);
    data.append('postid',  postid);
     jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                jQuery("body").css({'cursor':'default'});
               
               if(jQuery.parseJSON(data) !=null){
               
                   var datareturn  = jQuery.parseJSON(data);
                   var x = datareturn.toString();
                   
                   jQuery.confirm({
                    title: '<p style="text-align:center;">View</p>',
                    content: "<textarea style='width:100%'>"+x+"</textarea>",
                    confirmButtonClass: 'mycustomwidth',
                    cancelButtonClass: 'customeclasshide',
                    animation: 'rotateY',
                    closeIcon: true,
                    columnClass: 'jconfirm-box-container-special'

                 });
                    
                
              }else{
                 swal({
                        title: "Error",
			text: "There are no files uploaded by selected users.",
			type: "error",
			confirmButtonClass: "btn-danger"
		});
             }
            }
        });
 
}