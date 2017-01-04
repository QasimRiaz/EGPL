
 var t;
jQuery(document).ready(function() {
    t = jQuery('.bulkedittask').DataTable({
        
        "paging": false,
        "info": false,
        "dom": '<"top"i><"clear">',
        "columnDefs": [
           
            {"width": "50px", "targets": 0},
            {"width": "100px", "targets": 3}
        ]
    });
   
  
 
    jQuery('.addnewbulktask').on( 'click', function () {
      var  rowNode = t.row.add( [
            '<p style="margin-top: 10px;margin-bottom: 10px;" ><i class="fa fa-clone" title="Create a clone" style="color:#262626;cursor: pointer;" aria-hidden="true"></i><i style=" cursor: pointer;margin-left: 10px;" onclick="removebulk_task(this)" title="Remove this task" class="fusion-li-icon fa fa-times-circle " style="color:#262626;"></i></p>',
            '<input placeholder="Task Title" style="margin-top: 10px;margin-bottom: 10px;" type="text" class="form-control" name="tasklabel" id="tasklabel" > ',
            '<div class="topmarrginebulkedit">\n\
            <select  class="select2" data-placeholder="Select Type" data-allow-clear="true">\n\
            <option>None</option>\n\
            <option>File Upload</option>\n\
            <option>Date</option><option>Email</option><option>Number</option></select></div>',
            '<input placeholder="Task Due Date" style="margin-top: 10px;margin-bottom: 10px;" type="text" class="form-control" name="datepicker">',
            '<input placeholder="Task Attributes" style="margin-top: 10px;margin-bottom: 10px;" name="attribure" class="form-control" id="attribure">',
            '<div class="addscrol topmarrginebulkedit"><select class="select2" data-placeholder="Select Levels" data-allow-clear="true"  multiple="multiple"><option>All</option><option>Admin</option><option>Content Manager</option><option>Gold</option><option>Sliver</option> </select><br><select data-placeholder="Select Users" data-allow-clear="true"  class="select2" multiple="multiple"><option>testuser1@gmail.com</option><option>testuser2@gmail.com</option><option>testuser4@gmail.com</option><option>testuser5@gmail.com</option> <option>testuser3@gmail.com</option></select> <br></div>',
            '<div class="addscrol"><br><p>Upload Task Decrpition</p><p ><i title="Edit your task description" class="font-icon fa fa-edit" style="cursor: pointer;color: #0082ff;"onclick="bulktask_descripiton()"></i></p></div>'
        ] ).draw().nodes().to$().addClass("bulkaddnewtask");
        
        
        
        jQuery('.select2').select2();
    
    
   
    } );
    
  
 
    // Automatically add a first row of data
   
} );
                                  
function bulktask_descripiton(){
    
    
        jQuery.confirm({
            
        title: 'Task Descripiton!',
        content: '<textarea name="taskdescrpition" class="taskdescrpition"  >Upload Task Decrpition</textarea>',
        confirmButton: 'Update',
        cancelButton: 'Close',
        confirmButtonClass: 'btn mycustomwidth btn-lg btn-primary mysubmitemailbutton',
        cancelButtonClass: 'btn mycustomwidth btn-lg btn-danger'
       

        });
  tinymce.init({
  selector: '.taskdescrpition',
  height: 400,
  plugins: [
    'table code link hr paste'
  ],table_default_attributes: {
    
    
           border:1
  },
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  content_css: [
    '/wp-content/plugins/EGPL/css/editorstyle.css'
  ]
});
    
}                        
                                    
                                    
                                    
 function removebulk_task(e){
     
     
             
   

     
     
     swal({
            title: "Are you sure?",
            text: 'Click confirm to delete this Task.',
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
                 t.row( jQuery(e).parents('tr') ).remove().draw();
                swal({
                    title: "Deleted!",
                    text: "Task deleted Successfully",
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
                               
                                
                               
                                
 function saveallbulktask(){
     
      swal({
					title: "Success",
					text: 'All Task Updated Successfully',
					type: "success",
					confirmButtonClass: "btn-success",
					confirmButtonText: "Ok"
				});
     
     
     
 }        
                                    
                                  
                                   
                                  
 