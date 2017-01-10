
 var t;
 var roleassignmenttable;
 var listview;
 var newfieldtask =0;
  var loadinglightbox;
jQuery(document).ready(function() {
     
   t = jQuery('.bulkedittask').DataTable( {
        initComplete: function () {
           this.api().columns([1]).every( function () {
                var column = this;
                jQuery(".specialsearchfilter")
                    .on( 'change', function () {
                        var val = jQuery.fn.dataTable.util.escapeRegex(
                            jQuery(this).val()
                            
                        );
                        var  searchvalue = val.replace(/([~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, ' ');
                       
                        column
                            .search( searchvalue )
                            .draw();
                    } );
                 
               column.data().unique().sort().each( function ( d, j ) {
                    var val = jQuery(d).val();
                  
                  // jQuery(".specialsearchfilter").append( '<option value="'+val+'">'+val+'</option>' );
                   
                } 
                        
             );
                
               
               
                
            } );
            
        },
        "paging": false,
        "info": false,
        "dom": '<"top"i><"clear">',
        columnDefs: [
            { "type": "html-input", "targets": [1] }
        ] 
    } );

  
    
   
    listview = jQuery('.bulkedittasklistview').DataTable({
        
        "paging": false,
        "info": false,
        "dom": '<"top"i><"clear">',
        "columnDefs": [
           
            {"width": "50px", "targets": 0},
            {"width": "400px", "targets": 1},
            {"width": "100px", "targets": 3}
        ]
    });
   roleassignmenttable = jQuery('.assigntaskrole').DataTable({
        
        "paging": false,
        "info": false,
        "dom": '<"top"i><"clear">',
        
    });
    jQuery('datepicker').daterangepicker({
				singleDatePicker: true,
				showDropdowns: true,
                               
                                locale: {
                                    format: 'DD-MMM-YYYY'
                                }
                                
			});
  
jQuery(window).load(function() {
   console.log('finshedloading'); 
   //jQuery('#loadingalert').hide();
   jQuery('.block-msg-default').remove();
   jQuery('.blockOverlay').remove();
});

    jQuery('.addnewbulktask').on( 'click', function () {
         var uniquecode  = randomString(5, 'a#');
         var tasktypedata = jQuery('.addnewtaskdata-type').html();
         var taskroledata = jQuery('.addnewtaskdata-role').html();
         var taskuseriddata = jQuery('.addnewtaskdata-userid').html();
        
        var col1 = '<div class="hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><i class="hi-icon fa fa-clone saveeverything" id="'+uniquecode+'" title="Create a clone" onclick="clonebulk_task(this)" style="color:#262626;cursor: pointer;" aria-hidden="true"></i><i style=" cursor: pointer;margin-left: 10px;" onclick="removebulk_task(this)" title="Remove this task" class="hi-icon fusion-li-icon fa fa-times-circle " style="color:#262626;"></i></div>';
        var col2 = '<input placeholder="Title" title="Title" id="row-'+uniquecode+'-title" style="margin-top: 10px;margin-bottom: 10px;" type="text" class="form-control" name="tasklabel" >  <input type="hidden" id="row-'+uniquecode+'-key" value=""> ';
        var col3 = '<div class="topmarrginebulkedit"><select  title="Select Type" class="select2 bulktasktypedrop" id="bulktasktype_'+uniquecode+'" data-placeholder="Select Type" data-allow-clear="true">'+tasktypedata+'</select></div><div class="bulktasktype_'+uniquecode+'" style="display: none;margin-top:10px;margin-bottom: 10px;" ><input type="text"  class="form-control" name="linkurl" placeholder="Link URL" title="Link URL"id="row-'+uniquecode+'-linkurl" ><br><input type="text"  class="form-control" name="linkname" placeholder="Link Name" title="Link Name" id="row-'+uniquecode+'-linkname"></div><div class="dbulktasktype_'+uniquecode+'" style="display: none;margin-top:10px;margin-bottom: 10px;" > <input type="text"  class="form-control" name="dropdownvalues" placeholder="Comma separated list of values" title="Comma separated list of values"  id="row-'+uniquecode+'-dropdownvlaues" ></div>';
        var col4 = '<input title="Due Date" placeholder="Due Date" id="row-'+uniquecode+'-duedate" style="padding-left: 13px;margin-top: 10px;margin-bottom: 10px;" type="text" class="form-control datepicker" name="datepicker" >';
        var col5 = '<input placeholder="Attributes" title="Attributes" id="row-'+uniquecode+'-attributes" style="margin-top: 10px;margin-bottom: 10px;" name="attribure" class="form-control" id="attribure">';
        var col6 = '<div class="addscrol topmarrginebulkedit"><select class="select2" id="row-'+uniquecode+'-levels" data-placeholder="Select Levels" title="Select Levels" data-allow-clear="true"  multiple="multiple">'+taskroledata+'</select><br><select data-placeholder="Select Users" title="Select Users" id="row-'+uniquecode+'-userid" data-allow-clear="true"  class="select2" multiple="multiple">'+taskuseriddata+'</select> <br></div>';
        var col7 = '<br><div class="addscrol"><div id="row-'+uniquecode+'-descrpition" class="edittaskdiscrpition_'+uniquecode+'"></div><p ><i class="font-icon fa fa-edit" id="taskdiscrpition_'+uniquecode+'" title="Edit your task description"style="cursor: pointer;color: #0082ff;"onclick="bulktask_descripiton(this)"></i><span id="desplaceholder-'+uniquecode+'"style="margin-left: 10px;color:gray;">Description</span></p></div></div>';
                  
      var  rowNode = t.row.add( [
            col1,
            col2,
            col3,
            col4,
            col5,
            col6,
            col7
        ] ).draw().nodes().to$().addClass("bulkaddnewtask");
        
        
        jQuery('#bulktasktype_'+uniquecode).select2();
        jQuery('#row-'+uniquecode+'-levels').select2();
        jQuery('#row-'+uniquecode+'-userid').select2();
    
        var $eventSelect = jQuery(".bulktasktypedrop");
        //$eventSelect.on("select2:open", function (e) {  console.log('open'); });
        //$eventSelect.on("select2:close", function (e) { console.log('close'); });
        $eventSelect.on("select2:select", function (e) {
            console.log('1');
            var selectedtype = jQuery(this).val();
            var className = jQuery(this).attr('id');

            console.log(selectedtype);
            if (selectedtype == 'select-2') {

                jQuery('.d' + className).show();
                jQuery('.' + className).hide();
            } else if (selectedtype == 'link') {
                jQuery('.' + className).show();
                jQuery('.d' + className).hide();
            } else {
                jQuery('.' + className).hide();
                jQuery('.d' + className).hide();
            }

        });
          jQuery('#row-'+uniquecode+'-duedate').daterangepicker({
				singleDatePicker: true,
				showDropdowns: true,
                               
                                locale: {
                                    format: 'DD-MMM-YYYY'
                                   
                                }
                                
			});
        jQuery('#row-'+uniquecode+'-duedate').val('');
    } );
    
     jQuery('.addnewbulktasklistview').on( 'click', function () {
         newfieldtask++;
      var  rowNode = listview.row.add( [
            '<div class="hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><i class="hi-icon fa fa-clone" title="Create a clone" style="color:#262626;cursor: pointer;" aria-hidden="true"></i><i style=" cursor: pointer;margin-left: 10px;" onclick="removebulk_tasklistview(this)" title="Remove this task" class="hi-icon fusion-li-icon fa fa-times-circle " style="color:#262626;"></i></div>',
            '<input placeholder="Task Title" style="margin-top: 10px;margin-bottom: 10px;" type="text" class="form-control" name="tasklabel" id="tasklabel" > ',
            '<div class="topmarrginebulkedit">\n\
            <select  class="select2 special'+newfieldtask+'" data-placeholder="Select Type" data-allow-clear="true">\n\
            <option>None</option>\n\
            <option>File Upload</option>\n\
            <option>Date</option><option>Email</option><option>Number</option></select></div>',
            '<input placeholder="Task Due Date" style="margin-top: 10px;margin-bottom: 10px;" type="text" class="form-control datepicker" name="datepicker" value="">',
            '<input placeholder="Task Attributes" style="margin-top: 10px;margin-bottom: 10px;" name="attribure" class="form-control" id="attribure">',
            '<div class="topmarrginebulkedit"><select class="select2 special'+newfieldtask+'" data-placeholder="Select Levels" data-allow-clear="true"  multiple="multiple"><option>All</option><option>Admin</option><option>Content Manager</option><option>Gold</option><option>Sliver</option> </select><br><select data-placeholder="Select Users" data-allow-clear="true"  class="select2 special'+newfieldtask+'" multiple="multiple"><option>testuser1@gmail.com</option><option>testuser2@gmail.com</option><option>testuser4@gmail.com</option><option>testuser5@gmail.com</option> <option>testuser3@gmail.com</option></select> <br></div>',
            '<div class=""><br><p>Upload Task Decrpition</p><p ><i title="Edit your task description" class="font-icon fa fa-edit" style="cursor: pointer;color: #0082ff;"onclick="bulktask_descripiton()"></i></p></div>'
        ] ).draw().nodes().to$().addClass("bulkaddnewtask");
        
        
        
        jQuery('.special'+newfieldtask).select2();
        
        
 
       
   
    } );
    
   
   
   });
            
            
    
            

var $eventSelect = jQuery(".bulktasktypedrop");
//$eventSelect.on("select2:open", function (e) {  console.log('open'); });
//$eventSelect.on("select2:close", function (e) { console.log('close'); });
$eventSelect.on("select2:select", function (e) {
    console.log('1');
     var selectedtype = jQuery(this).val();
     var className = jQuery(this).attr('id');
     
    
     if (selectedtype == 'select-2') {

                jQuery('.d' + className).show();
                jQuery('.' + className).hide();
            } else if (selectedtype == 'link') {
                jQuery('.' + className).show();
                jQuery('.d' + className).hide();
            } else {
                jQuery('.' + className).hide();
                jQuery('.d' + className).hide();
            }

});
//$eventSelect.on("select2:unselect", function (e) { console.log('unselect');});


function bulktask_descripiton(e){
    
       
        
         var classname = jQuery(e).attr("id");
         var desplaceholder = jQuery(e).attr("id").split('_');
         var descrpition = jQuery(".edit"+classname).html();
      
      
        var updatedescripiton = jQuery.confirm({
            
        title: 'Task Descripiton',
        content: '<textarea name="taskdescrpition" class="taskdescrpition"  >'+descrpition+'</textarea>',
        confirmButton: 'Update',
        cancelButton: 'Close',
        confirmButtonClass: 'btn mycustomwidth btn-lg btn-primary mysubmitemailbutton',
        cancelButtonClass: 'btn mycustomwidth btn-lg btn-danger',
        confirm: function () {
            
            
            jQuery(".edit"+classname).empty();
            jQuery(".edit"+classname).append(tinymce.activeEditor.getContent());
            var n = jQuery(".edit"+classname).text().length;
            if(n == 0){
            
                jQuery("#desplaceholder-"+desplaceholder[1]).show();
            
            }else{
                
                jQuery("#desplaceholder-"+desplaceholder[1]).hide();
            }
        }

        });
        
        
  tinymce.init({
  selector: '.taskdescrpition',
  height: 300,
  plugins: [
    'table code link hr paste'
  ],table_default_attributes: {
    
    
           border:1, class:'table'
  },
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  convert_urls: false,
        content_css: [
    '/wp-content/plugins/EGPL/css/editorstyle.css'
  ]
});

}                     

function strRemove(theTarget, theString) {
        return jQuery("<div/>").append(
            jQuery(theTarget, theString).remove().end()
        ).html();
       }

function clonebulk_task(e){
        
        
      
        
        
        var uniquecode  = randomString(5, 'a#');
        var currentclickid = jQuery(e).attr('id');
        var clonetask = jQuery('#'+currentclickid).parent('p').parent('td').parent('tr').addClass('clontrposition');
      
        var countervalue = 1;
        var anSelected = jQuery(e).parents('tr');
        var data=[];
        jQuery(anSelected).find('td').each(function(){
            
            var regex = new RegExp(currentclickid, 'g');
           // var regex1 = new RegExp('select2-hidden-accessible', 'g');
            
            var res = jQuery(this).html().replace(regex, uniquecode);
            var resnew = res;
                    //.replace(regex1, '');
             
             if(countervalue == 3 || countervalue == 6 ){
                resnew = strRemove("span", resnew);
                //console.log(theResult);
            }
            
             countervalue++;
            data.push(resnew);
            
            
        });
        t.row.add(data).draw().nodes().to$().addClass("bulkaddnewtask");
       
      // t.row.add(data).draw().node();
       
        var oldvalue = jQuery('#row-'+uniquecode+'-title').val();
        jQuery('#row-'+uniquecode+'-title').val('Copy of '+oldvalue);
        jQuery('#row-'+uniquecode+'-key').val('');
        jQuery('#bulktasktype_'+uniquecode).select2();
        jQuery('#row-'+uniquecode+'-levels').select2();
        jQuery('#row-'+uniquecode+'-userid').select2();
    
        var $eventSelect = jQuery(".bulktasktypedrop");
        //$eventSelect.on("select2:open", function (e) {  console.log('open'); });
        //$eventSelect.on("select2:close", function (e) { console.log('close'); });
        $eventSelect.on("select2:select", function (e) {
            console.log('1');
            var selectedtype = jQuery(this).val();
            var className = jQuery(this).attr('id');

            console.log(selectedtype);
            if (selectedtype == 'select-2') {

                jQuery('.d' + className).show();
                jQuery('.' + className).hide();
            } else if (selectedtype == 'link') {
                jQuery('.' + className).show();
                jQuery('.d' + className).hide();
            } else {
                jQuery('.' + className).hide();
                jQuery('.d' + className).hide();
            }

        });
        jQuery('.datepicker').daterangepicker({
				singleDatePicker: true,
				showDropdowns: true,
                                locale: {
                                    format: 'DD-MMM-YYYY'
                                }
                                
			});
                       jQuery('#loadingalert').removeClass('showwaitingboox');
       // jQuery('.loadingalert').css("display", "none !important");
         //console.log(resnew);
   // } );
     
   }                                  
                                    
 function removebulk_task(e){
     
      swal({
            title: "Are you sure?",
            text: 'Click confirm to delete this Task. Deleting a task will not delete the data already submitted by users.',
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
                    text: "Task removed. It will be deleted when you save changes.",
                    type: "success",
                    confirmButtonClass: "btn-success"
                }
                );
            } else {
                swal({
                    title: "Cancelled",
                    text: "Task is safe",
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
            }
        });
         
     
     
     
 }                           
                               
  function removebulk_tasklistview(e){
     
     
             
   

     
     
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
                 listview.row( jQuery(e).parents('tr') ).remove().draw();
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
                    text: "Task is safe ",
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
            }
        });
         
     
     
     
 }                             
                               
                                
function saveallbulktask(){
   
   
    //jQuery("#customers_select_search").select2("val", "");
   jQuery("#customers_select_search").select2({ allowClear: true });
   // t.search(' ').draw();
    t.columns().every( function () {
        var that = this;
 
        
           
                that.search(' ').draw();
            
        } );
    jQuery("body").css({'cursor':'wait'});
    var taskdataupdate = {};
    var requeststatus = 'stop';
    var errormsg= "";
    var specialcharacterstatus = false;
    if(t.rows().data()['length'] == 0 ){
        var requeststatus = 'update';
    }else{
    
    jQuery( ".saveeverything" ).each(function( index ) {
      
     
        
    var taskid = jQuery( this ).attr('id');
    
    
    var str = jQuery( '#row-'+taskid+'-title' ).val();
    if(jQuery.trim( str ).length !=0  && jQuery( '#bulktasktype_'+taskid ).val() !="" && jQuery( '#row-'+taskid+'-duedate' ).val() !="" && jQuery( '#row-'+taskid+'-levels' ).val()!=null){
        
        
        if(/^[ A-Za-z0-9_?()\-]*$/.test(str) == false) {
           specialcharacterstatus = true;
        }else{
            specialcharacterstatus = false;
        }
        
        if(specialcharacterstatus == false){
        jQuery('#'+taskid).parent('div').parent('td').parent('tr').removeClass('emptyfielderror');
        requeststatus = 'update';
        var singletaskarray={};
      
        var uniqueKey = jQuery( '#row-'+taskid+'-key' ).val();
        if(uniqueKey == ""){
            
              var taskLabel = jQuery( '#row-'+taskid+'-title' ).val();
              var uniqueKey = taskLabel.toLowerCase().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '_');
              var uniquecode =randomString(5, 'a#');
              uniqueKey ='task_'+uniqueKey+'_'+uniquecode;
            
            
        }
        
        
      
        
        
        singletaskarray['value'] = '';
        singletaskarray['unique'] = 'no';
        singletaskarray['class'] = '';
        singletaskarray['after'] = '';
        singletaskarray['required'] = 'no';
        singletaskarray['allow_tags'] = 'yes';
        singletaskarray['add_to_profile'] = 'yes';
        singletaskarray['allow_multi'] = 'no';
        singletaskarray['size'] = '';
        singletaskarray['label'] = jQuery( '#row-'+taskid+'-title' ).val();
        singletaskarray['type'] = jQuery( '#bulktasktype_'+taskid ).val();
        singletaskarray['lin_url'] = jQuery( '#row-'+taskid+'-linkurl' ).val();
        singletaskarray['linkname'] = jQuery( '#row-'+taskid+'-linkname' ).val();
        singletaskarray['attrs'] = jQuery( '#row-'+taskid+'-duedate' ).val();
        singletaskarray['taskattrs'] = jQuery( '#row-'+taskid+'-attribute' ).val();
        singletaskarray['roles'] = jQuery( '#row-'+taskid+'-levels' ).val();
        singletaskarray['usersids'] = jQuery( '#row-'+taskid+'-userid' ).val();
        singletaskarray['descrpition'] = jQuery( '#row-'+taskid+'-descrpition' ).html();
        
        
          //task action array 
      if(jQuery('#bulktasktype_'+taskid).val() == 'select-2'){
          
          var dropdownvalues =jQuery('#row-'+taskid+'-dropdownvlaues').val().split(',');
          var specialindexforoptions = 1;
              var optionarray = {};
              jQuery.each(dropdownvalues, function( index, value ) {
                var optionvalue = {};
                
                optionvalue['label'] = value;
                optionvalue['value'] = value;
                optionvalue['state'] = '';
                optionarray[specialindexforoptions] = optionvalue;
                specialindexforoptions++;
            
          });
          singletaskarray['options'] = optionarray;
           
       }
     
      taskdataupdate[uniqueKey]=singletaskarray
  }else{
         
         
         jQuery('#'+taskid).parent('div').parent('td').parent('tr').addClass('emptyfielderror');
       
         requeststatus = 'stop';
         errormsg = 'Invalid characters used in task title(s). Please remove and try again.';
         return false;
     }
     
     }else{
         
         
         jQuery('#'+taskid).parent('div').parent('td').parent('tr').addClass('emptyfielderror');
       
         requeststatus = 'stop';
         errormsg = 'Some required fields are empty.';
         return false;
     }
   
        
    });
}
    
   
    
 if(requeststatus == 'update'){ 
    var url = window.location.protocol + "//" + window.location.host + "/";
    var urlnew = url + 'wp-content/plugins/EGPL/taskmanager.php?createnewtask=savebulktask';
    var data = new FormData();
     console.log(taskdataupdate);
    data.append('bulktaskdata',   JSON.stringify(taskdataupdate));
    
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
                    text: "All changes saved successfully",
                    type: "success",
                    confirmButtonClass: "btn-success"
                },
        function(isConfirm) {
            
            location.reload();
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
            title: "Error",
	    text: errormsg,
            type: "error",
	    confirmButtonClass: "btn-danger",
	    confirmButtonText: "Ok"
	}
        );
    }
}        
                               

 
 function chunkify(a, n, balanced) {
    
    if (n < 2)
        return [a];

    var len = a.length,
            out = [],
            i = 0,
            size;

    if (len % n === 0) {
        size = Math.floor(len / n);
        while (i < len) {
            out.push(a.slice(i, i += size));
        }
    }

    else if (balanced) {
        while (i < len) {
            size = Math.ceil((len - i) / n--);
            out.push(a.slice(i, i += size));
        }
    }

    else {

        n--;
        size = Math.floor(len / n);
        if (len % size === 0)
            size--;
        while (i < size * n) {
            out.push(a.slice(i, i += size));
        }
        out.push(a.slice(size * n));

    }

    return out;
}


function stripSlashesspecial(str)
	{
		return str.replace(/\\/g, '');
	}
 function log(text) {
          jQuery('#logs').append(text + '<br>');
        }

//manger task js code
