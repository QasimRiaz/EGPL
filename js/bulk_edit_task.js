
 var t;
 var roleassignmenttable;
 var listview;
 var newfieldtask =0;
 var loadinglightbox;
 var taskuseremaillist = [];
 var deletedtaskslist = [];
jQuery(document).ready(function() {
  
   t = jQuery('.bulkedittask').DataTable( {
        "order": [[ 0, "desc" ]],
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
   if ( window.location.href.indexOf("bulk-edit-task") > -1)
    {
   jQuery('.block-msg-default').remove();
   jQuery('.blockOverlay').remove();
    }
});

    jQuery('.addnewbulktask').on( 'click', function () {
        
        jQuery("#customers_select_search").select2({ allowClear: true });
        t.columns().every( function () {
        var that = this;
 
        
           
                that.search(' ').draw();
            
        });
         var uniquecode  = randomString(8, 'a#')+"_addnewtasks";
         var tasktypedata = jQuery('.addnewtaskdata-type').html();
         var taskroledata = jQuery('.addnewtaskdata-role').html();
         var taskuseriddata = jQuery('.addnewtaskdata-userid').html();
        
        var col1 = '<div class="hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><i class="hi-icon fa fa-clone saveeverything" id="'+uniquecode+'" title="Create a clone" onclick="clonebulk_task(this)" style="color:#262626;cursor: pointer;" data-toggle="tooltip" aria-hidden="true"></i> <i data-toggle="tooltip" title="Advanced" name="'+uniquecode+'" onclick="bulktasksettings(this)" class="hi-icon fusion-li-icon fa fa-gears" ></i><i name="'+uniquecode+'" data-toggle="tooltip" style=" cursor: pointer;margin-left: 10px;" onclick="removebulk_task(this)" title="Remove this task" class="hi-icon fusion-li-icon fa fa-times-circle " style="color:#262626;"></i></div>';
        var col2 = '<input data-toggle="tooltip" placeholder="Title" title="Title" id="row-'+uniquecode+'-title" style="margin-top: 10px;margin-bottom: 10px;" type="text" class="form-control" name="tasklabel" >  <input type="hidden" id="row-'+uniquecode+'-key" value=""><input type="hidden" id="row-'+uniquecode+'-attribute"  value="" ><input type="hidden" id="row-'+uniquecode+'-emailnotification"  value="" ><input type="hidden" id="row-'+uniquecode+'-multiselectstatus"  value="" ><input type="hidden" id="row-'+uniquecode+'-emailnotificationaddress"  value="" ><input type="hidden" id="row-'+uniquecode+'-multivaluetasklimit"  value="" > <input type="hidden" id="row-'+uniquecode+'-taskMWC"  value="" ><input type="hidden" id="row-'+uniquecode+'-taskMWDDP"  value="" ><input type="hidden" id="row-'+uniquecode+'-taskCode"  value="" ><input type="hidden" id="row-'+uniquecode+'-SystemTask"  value="" > ';
        var col3 = '<div class="topmarrginebulkedit"><select  data-toggle="tooltip" title="Select Type" class="select2 bulktasktypedrop" id="bulktasktype_'+uniquecode+'" data-placeholder="Select Type" data-allow-clear="true">'+tasktypedata+'</select></div><div class="bulktasktype_'+uniquecode+'" style="display: none;margin-top:10px;margin-bottom: 10px;" ><input type="text"  class="form-control" name="linkurl" placeholder="Link URL" title="Link URL"id="row-'+uniquecode+'-linkurl" ><br><input type="text"  class="form-control" name="linkname" placeholder="Link Name" title="Link Name" id="row-'+uniquecode+'-linkname"></div><div class="dbulktasktype_'+uniquecode+'" style="display: none;margin-top:10px;margin-bottom: 10px;" > <input type="text"  class="form-control" name="dropdownvalues" placeholder="Comma separated list of values" title="Comma separated list of values"  id="row-'+uniquecode+'-dropdownvlaues" ></div>';
        var col4 = '<input  data-toggle="tooltip" title="Due Date" placeholder="Due Date" id="row-'+uniquecode+'-duedate" style="padding-left: 13px;margin-top: 10px;margin-bottom: 10px;" type="text" class="form-control datepicker" name="datepicker" >';
        var col5 = '<div class="addscrol topmarrginebulkedit"><select data-toggle="tooltip" class="select2" id="row-'+uniquecode+'-levels" data-placeholder="Select Levels" title="Select Levels" data-allow-clear="true"  multiple="multiple">'+taskroledata+'</select><br><select data-placeholder="Select Users" title="Select Users" id="row-'+uniquecode+'-userid" data-allow-clear="true"  class="select2" multiple="multiple">'+taskuseriddata+'</select> <br></div>';
        var col6 = '<br><div class="addscrol"><div id="row-'+uniquecode+'-descrpition" name="taskdiscrpition_'+uniquecode+'" class="edittaskdiscrpition_'+uniquecode+'" oncontextmenu="return false;" onclick="bulktask_descripiton(this)"></div><p ><i class="font-icon fa fa-edit" name="taskdiscrpition_'+uniquecode+'" title="Edit your task specifications"style="cursor: pointer;color: #0082ff;"onclick="bulktask_descripiton(this)"></i><span id="desplaceholder-'+uniquecode+'"style="margin-left: 10px;color:gray;">Specifications</span></p></div></div>';
                  
       t.row.add( [
            col1,
            col2,
            col3,
            col4,
            col5,
            col6
          
        ]).draw().nodes().to$().addClass("bulkaddnewtask");
        
        //t.column(0).order('desc').draw();
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
            if (selectedtype == 'select-2' || selectedtype == 'multiselect') {

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
            '<div class=""><br><p>Upload Task Decrpition</p><p ><i title="Edit your task specifications" class="font-icon fa fa-edit" style="cursor: pointer;color: #0082ff;"onclick="bulktask_descripiton()"></i></p></div>'
        ] ).draw().nodes().to$().addClass("bulkaddnewtask");
        
        
        
        jQuery('.special'+newfieldtask).select2();
        
        
 
       
   
    } );
    
   
   
   });
            
 jQuery(document).ready(function() {
    
    
    var url = currentsiteurl+'/';
     var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=getuseremailids';
    var data = new FormData();
    jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
               taskuseremaillist = jQuery.parseJSON(data);
               
               
           }
       });
       var $myneweventSelect = jQuery(".js-example-events");



   });           
    
            

var $eventSelect = jQuery(".bulktasktypedrop");
//$eventSelect.on("select2:open", function (e) {  console.log('open'); });
//$eventSelect.on("select2:close", function (e) { console.log('close'); });
$eventSelect.on("select2:select", function (e) {
    console.log('1');
     var selectedtype = jQuery(this).val();
     var className = jQuery(this).attr('id');
     
    
     if (selectedtype == 'select-2' || selectedtype == 'multiselect') {

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
jQuery('.bulktasktypedrop').on("select2:selecting", function(e) {
    console.log(e.currentTarget['id']);
   
   
    
   
    
    var oldselectingvalue ='';
    oldselectingvalue = jQuery('#'+e.currentTarget['id']).val();
    
  
    
   
    swal({
            title: "Warning !",
            text: 'Changing task input type can result in losing user submissions that were made for this task in the past. Are you sure you want to continue?',
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {



            if (isConfirm) {
              
                swal({
                    title: "Success",
                    text: "Task input type change successfully.",
                    type: "success",
                    confirmButtonClass: "btn-success"
                });
            
            } else {
                jQuery('#'+e.currentTarget['id']).val(oldselectingvalue).trigger("change");
                swal({
                    title: "Cancelled",
                    text: "Task input type safe ",
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
            }
        });
});

function bulktask_descripiton(e){
    
       
        
         var classname = jQuery(e).attr("name");
         var desplaceholder = jQuery(e).attr("name").split('_');
         var descrpition = jQuery(".edit"+classname).html();
         console.log(classname);
         console.log(descrpition)
      
        var updatedescripiton = jQuery.confirm({
            
        title: 'Task Specifications',
        content: '<textarea name="taskdescrpition" class="taskdescrpition"  >'+descrpition+'</textarea>',
        confirmButton: 'Update',
        cancelButton: 'Close',
        confirmButtonClass: 'btn mycustomwidth btn-lg btn-primary mysubmitemailbutton',
        cancelButtonClass: 'btn mycustomwidth btn-lg btn-danger',
        columnClass: 'jconfirm-box-container-special',
         closeIcon: true,
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
        
        
      
        jQuery("#customers_select_search").select2({ allowClear: true });
   
        t.columns().every( function () {
            var that = this;
            that.search(' ').draw();
            
        } );
        
        var uniquecode  = randomString(8, 'a#')+"_addnewtasks";
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
             
             if(countervalue == 3 || countervalue == 5 ){
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
        jQuery('#row-'+uniquecode+'-title').attr('readonly',false);
        jQuery('#row-'+uniquecode+'-SystemTask').val('');
        
        jQuery('#row-'+uniquecode+'-key').val('');
        jQuery('#bulktasktype_'+uniquecode).select2();
        jQuery('#row-'+uniquecode+'-levels').select2();
        jQuery('#row-'+uniquecode+'-userid').select2();
        
        jQuery("#bulktasktype_"+uniquecode).attr('disabled',false);
        var $eventSelect = jQuery(".bulktasktypedrop");
        //$eventSelect.on("select2:open", function (e) {  console.log('open'); });
        //$eventSelect.on("select2:close", function (e) { console.log('close'); });
        $eventSelect.on("select2:select", function (e) {
            console.log('1');
            var selectedtype = jQuery(this).val();
            var className = jQuery(this).attr('id');

            console.log(selectedtype);
            if (selectedtype == 'select-2' || selectedtype == 'multiselect') {

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
     var productID = jQuery(e).attr("name");
      swal({
            title: "Are you sure?",
            text: 'Click confirm to delete this Task. Deleting a task will delete the data already submitted by users.',
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
                 
                 deletedtaskslist.push(productID);
                 
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
    });
    jQuery("body").css({'cursor':'wait'});
    var taskdataupdate = {};
    var requeststatus = 'stop';
    var errormsg= "";
    var titlemsg = "";
    var specialcharacterstatus = false;
    if(t.rows().data()['length'] == 0 ){
        var requeststatus = 'update';
    }else{
    
    jQuery( ".saveeverything" ).each(function( index ) {
        
        
        
         var taskid = jQuery( this ).attr('id');
         var taskLabelcheck = jQuery( '#row-'+taskid+'-title' ).val();
         var status = 'noduplicate';
       jQuery( ".saveeverything" ).each(function( index2 ) {
            
            var taskid2 = jQuery( this ).attr('id');
            var taskLabelcompare = jQuery( '#row-'+taskid2+'-title' ).val();
             
            if(taskid != taskid2){
                
               
                if(taskLabelcheck == taskLabelcompare){
                    
                    console.log(taskLabelcompare +'=='+ taskLabelcheck);
                    status = 'duplicate';
                    return false;
                }
            }
           
       });
       if(status == 'duplicate'){
            console.log(status);
            console.log(taskid);
            jQuery('#'+taskid).parent('div').parent('td').parent('tr').addClass('emptyfielderror');
       
            requeststatus = 'stop';
            errormsg = "Multiple tasks have the same title. Please give each task a unique title.";
            titlemsg = 'Duplicate Task Title Detected';
            return false;
           
           
       }
      
     
        
    var taskid = jQuery( this ).attr('id');
    
    
    var str = jQuery( '#row-'+taskid+'-title' ).val();
    if(jQuery.trim( str ).length !=0  && jQuery( '#bulktasktype_'+taskid ).val() !="" && jQuery( '#row-'+taskid+'-duedate' ).val() !="" && jQuery( '#row-'+taskid+'-levels' ).val()!=null){
        
        if(jQuery( '#row-'+taskid+'-title' ).val() !='Company Name'){
        
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
              var uniquecode =randomString(8, 'a#');
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
        singletaskarray['taskMWC'] = jQuery( '#row-'+taskid+'-taskMWC' ).val();
        singletaskarray['taskMWDDP'] = jQuery( '#row-'+taskid+'-taskMWDDP' ).val();
        singletaskarray['roles'] = jQuery( '#row-'+taskid+'-levels' ).val();
        singletaskarray['usersids'] = jQuery( '#row-'+taskid+'-userid' ).val();
        singletaskarray['SystemTask'] = jQuery( '#row-'+taskid+'-SystemTask' ).val();
        singletaskarray['taskCode'] = jQuery( '#row-'+taskid+'-taskCode' ).val();
        singletaskarray['descrpition'] = jQuery( '#row-'+taskid+'-descrpition' ).html();
        singletaskarray['emailnotification'] = jQuery( '#row-'+taskid+'-emailnotification' ).val();
        singletaskarray['emailnotificationaddress'] = jQuery( '#row-'+taskid+'-emailnotificationaddress' ).val();
        
        
        singletaskarray['multivaluetasklimit'] = jQuery( '#row-'+taskid+'-multivaluetasklimit' ).val();
        singletaskarray['multiselectstatus'] = jQuery( '#row-'+taskid+'-multiselectstatus' ).val();
        
        singletaskarray['key'] = uniqueKey;
      
        
        
        
          //task action array || selectedtype == 'multiselect'
      if(jQuery('#bulktasktype_'+taskid).val() == 'select-2' || jQuery('#bulktasktype_'+taskid).val() == 'multiselect'){
          
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
     
      taskdataupdate[taskid]=singletaskarray;
  }else{
         
         
         jQuery('#'+taskid).parent('div').parent('td').parent('tr').addClass('emptyfielderror');
       
         requeststatus = 'stop';
         errormsg = "Uh-oh, looks like you're using special characters (i.e. '&', ',', etc) that Task titles don't support. Please remove any special characters from the title and try again.";
         titlemsg = 'Unsupported Characters';
         return false;
     }
   }else{
     
        jQuery('#'+taskid).parent('div').parent('td').parent('tr').addClass('emptyfielderror');
       
         requeststatus = 'stop';
         errormsg = "Multiple tasks have the same title. Please give each task a unique title.";
         titlemsg = 'Duplicate Task Title';
         return false;
     
     
     
     
 }  
     }else{
         
         
         jQuery('#'+taskid).parent('div').parent('td').parent('tr').addClass('emptyfielderror');
       
         requeststatus = 'stop';
         titlemsg = 'Error';
         errormsg = 'Some required fields are empty.';
         return false;
     }
   
        
    });
}
    
   
    
 if(requeststatus == 'update'){ 
    var url = currentsiteurl+'/';
    var urlnew = url + 'wp-content/plugins/EGPL/taskmanager.php?createnewtask=savebulktask';
    var data = new FormData();
     console.log(taskdataupdate);
    data.append('bulktaskdata',   JSON.stringify(taskdataupdate));
    data.append('deletedtaskslist',   JSON.stringify(deletedtaskslist));
    
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
            jQuery("body").css({'cursor':'wait'});
            //location.reload();
             document.location.href = currentsiteurl+'/dashboard'
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
            title:titlemsg,
	    text:errormsg,
            type:"warning",
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

function bulktasksettings(e){
    
  var task_code = jQuery(e).attr('name');
  var task_attribute_value = jQuery('#row-'+task_code+'-attribute').val();
  var taskcode = jQuery('#row-'+task_code+'-taskCode').val();
  var selectedtasktype = jQuery( '#bulktasktype_'+task_code ).val();
  
  var multivaluetasklimit = jQuery('#row-'+task_code+'-multivaluetasklimit').val();
  var multiselectdropdownstats = jQuery('#row-'+task_code+'-multiselectstatus').val();
  
  
  var trvalue='';
  if(selectedtasktype == 'color'){
     var attributes_file = task_attribute_value.replace('accept=','');
     var substr = attributes_file.split(',');
                if(taskcode == "COL"){
                   trvalue = '<td ><strong>Accept File Types</strong><br>(List of acceptable file extensions. Leave blank for all)</td><td ><select disabled="true" style="width:150px !important;" id="confrim_attributes" title="Select File Types" class="select2 form-control newmultiselect" multiple="multiple" name="attribure" value="'+attributes_file+'" ><option value=".eps" ' + ((jQuery.inArray("eps", substr) != -1)? "selected" : "") + '>.eps</option><option value=".ai" ' + ((jQuery.inArray("ai", substr) != -1)? "selected" : "") + '>.ai</option><option value=".jpg" ' + ((jQuery.inArray("jpg", substr) != -1)? "selected" : "") + '>.jpg</option><option value=".png" ' + ((jQuery.inArray("png", substr) != -1)? "selected" : "") + '>.png</option><option value=".jpeg" ' + ((jQuery.inArray("jpeg", substr) != -1)? "selected" : "") + '>.jpeg</option><option value=".pdf" ' + ((jQuery.inArray("pdf", substr) != -1)? "selected" : "") + '>.pdf</option><option value=".ppt" ' + ((jQuery.inArray("ppt", substr) != -1)? "selected" : "") + '>.ppt</option><option value=".pptx" ' + ((jQuery.inArray("pptx", substr) != -1)? "selected" : "") + '>.pptx</option><option value=".doc" ' + ((jQuery.inArray("doc", substr) != -1)? "selected" : "") + '>.doc</option><option value=".docx" ' + ((jQuery.inArray("docx", substr) != -1)? "selected" : "") + '>.docx</option><option value=".xls" ' + ((jQuery.inArray("xls", substr) != -1)? "selected" : "") + '>.xls</option><option value=".xlsx" ' + ((jQuery.inArray("xlsx", substr) != -1)? "selected" : "") + '>.xlsx</option><option value=".svg" ' + ((jQuery.inArray("xlsx", substr) != -1)? "selected" : "") + '>.svg</option></select></td>';
      
                }else{
                  trvalue = '<td ><strong>Accept File Types</strong><br>(List of acceptable file extensions. Leave blank for all)</td><td ><select style="width:150px !important;" id="confrim_attributes" title="Select File Types" class="select2 form-control newmultiselect" multiple="multiple" name="attribure" value="'+attributes_file+'" ><option value=".eps" ' + ((jQuery.inArray("eps", substr) != -1)? "selected" : "") + '>.eps</option><option value=".ai" ' + ((jQuery.inArray("ai", substr) != -1)? "selected" : "") + '>.ai</option><option value=".jpg" ' + ((jQuery.inArray("jpg", substr) != -1)? "selected" : "") + '>.jpg</option><option value=".png" ' + ((jQuery.inArray("png", substr) != -1)? "selected" : "") + '>.png</option><option value=".jpeg" ' + ((jQuery.inArray("jpeg", substr) != -1)? "selected" : "") + '>.jpeg</option><option value=".pdf" ' + ((jQuery.inArray("pdf", substr) != -1)? "selected" : "") + '>.pdf</option><option value=".ppt" ' + ((jQuery.inArray("ppt", substr) != -1)? "selected" : "") + '>.ppt</option><option value=".pptx" ' + ((jQuery.inArray("pptx", substr) != -1)? "selected" : "") + '>.pptx</option><option value=".doc" ' + ((jQuery.inArray("doc", substr) != -1)? "selected" : "") + '>.doc</option><option value=".docx" ' + ((jQuery.inArray("docx", substr) != -1)? "selected" : "") + '>.docx</option><option value=".xls" ' + ((jQuery.inArray("xls", substr) != -1)? "selected" : "") + '>.xls</option><option value=".xlsx" ' + ((jQuery.inArray("xlsx", substr) != -1)? "selected" : "") + '>.xlsx</option><option value=".svg" ' + ((jQuery.inArray("xlsx", substr) != -1)? "selected" : "") + '>.svg</option></select></td>';
       
                }
		
    // trvalue='<td ><strong>Accept File Types</strong><br>(List of acceptable file extensions)</td><td ><input name="attribure"  placeholder=".png,.eps" id="confrim_attributes"  class="form-control"  value="'+attributes_file+'" ></td>';
  }else if(selectedtasktype == 'textarea'){
       var attributes_file = task_attribute_value.replace('maxlength=','');
       trvalue = '<td ><strong>Max Length</strong><br>(Number of characters allowed)</td><td ><input name="attribure"  placeholder="200" id="confrim_attributes"  class="form-control"  value="'+attributes_file+'" ></td>';
      
  }else if(selectedtasktype == 'multivaluedtask'){
      
      trvalue = '<td ><strong>Maximum Inputs</td><td ><input name="attribure"  id="multivaluetasklimit"  class="form-control"  value="'+multivaluetasklimit+'" ></td>';
      
      
  }else if(selectedtasktype == 'select-2'){
      
      trvalue = '<td ><strong>Multi Select</strong></td><td ><input '+multiselectdropdownstats+' type="checkbox" class="toggle-one" id="multiselectdropdownstats" data-toggle="toggle"></td>';
      
      
  }else{
      
  }
  
  var currentadmnirole = jQuery('#currentadmnirole').val();
  var task_additional_MWComplete = jQuery('#row-'+task_code+'-taskMWC').val();
  var task_additional_MWDueDatePass = jQuery('#row-'+task_code+'-taskMWDDP').val();
  var task_additional_taskCode = jQuery('#row-'+task_code+'-taskCode').val();
  var task_additional_systmeTaskStatus = jQuery('#row-'+task_code+'-SystemTask').val();
  var task_emailnotification = jQuery('#row-'+task_code+'-emailnotification').val();
  var task_notificationemailsaddress = jQuery('#row-'+task_code+'-emailnotificationaddress').val();
  
  if(task_notificationemailsaddress == undefined){
      
      task_notificationemailsaddress = "";
  }
  
  var task_title = jQuery('#row-'+task_code+'-title').val();
  var specialclasssnmae="display:none;";
  if(currentadmnirole == 'Administrator'){
      
    specialclasssnmae = "";
  }
  
   var htmlforsystemtask = '<tr style="'+specialclasssnmae+'"><td>System Task Status</td><td><input '+task_additional_systmeTaskStatus+' type="checkbox" class="toggle-one" id="confrim_systaskstatus" data-toggle="toggle"></td></tr>';
   var htmlfortaskCode = '<tr style="'+specialclasssnmae+'"><td>Task Code</td><td><input value="'+task_additional_taskCode+'" type="text"  id="confrim_taskCode" ></td></tr>';
  
  
  var content='';
 
            
   content='<table><tr><h5 style="margin-top: 2px;">'+task_title+'</h5><hr/></tr></table><table><tr><td><strong>Lock task when submitted</strong><br>(User cannot remove their submission)</td><td><input '+task_additional_MWComplete+' type="checkbox" class="toggle-one" id="confrim_taskMWC" data-toggle="toggle"></td></tr><tr><td><strong>Submission Notification <i title="helpText" class="fa fa-question-circle" style="font-size: 15px;cursor: pointer;"></i></td><td><input '+task_emailnotification+' type="checkbox" class="toggle-one" id="setnotification_taskMWC" data-toggle="toggle"></td></tr><tr><td><strong>Notification Emails <i title="helpText" class="fa fa-question-circle" style="font-size: 15px;cursor: pointer;"></i><br><span>(Comma separated list of email addresses that should receive submission notifications)</span></td><td><textarea id="notificationemails_taskMWC" >'+task_notificationemailsaddress+'</textarea></td></tr><tr><td><strong>Lock task when due date is passed</strong><br>(User cannot submit after due date)</td><td><input '+task_additional_MWDueDatePass+' type="checkbox" class="toggle-one"  id="confrim_taskMWDDP" data-toggle="toggle"></td></tr><tr>'+trvalue+'</tr>'+htmlforsystemtask+htmlfortaskCode+'</table>';
   
    
    
  jQuery.confirm({
            
        title: 'Advanced',
        content: content,
        confirmButton: 'Update',
        cancelButton: false,
        confirmButtonClass: 'btn mycustomwidth btn-lg btn-primary mysubmitemailbutton',
        closeIcon: true,
        onOpen: function() {
         jQuery('.toggle-one').bootstrapToggle();   
		 
		 jQuery.each(attributes_file.split(","), function(i,e){
		 jQuery(".select2 option[value='" + e + "']").prop("selected", true);
		  
		});
		jQuery('.newmultiselect').select2({
			placeholder: "Select file types"
		});
        },
        confirm: function () {
            
            var attributes='';
            if(selectedtasktype == 'color'){
                if(jQuery('#confrim_attributes').val() !=""){
                    attributes+='accept='+jQuery('#confrim_attributes').val();
                }
           }else if(selectedtasktype == 'textarea'){
           
                if(jQuery('#confrim_attributes').val() !=""){
                attributes+='maxlength='+jQuery('#confrim_attributes').val();
                }
            }else if(selectedtasktype == 'multivaluedtask'){
                
                 jQuery('#row-'+task_code+'-multivaluetasklimit').val(jQuery('#multivaluetasklimit').val());
            }
            jQuery('#row-'+task_code+'-attribute').val(attributes);
            
           if(jQuery('#notificationemails_taskMWC').val() !=""){
                    jQuery('#row-'+task_code+'-emailnotificationaddress').val(jQuery('#notificationemails_taskMWC').val());
                }
          
           if(jQuery('#confrim_taskMWC').is(':checked')){
                jQuery('#row-'+task_code+'-taskMWC').val('checked');
           }else{
              jQuery('#row-'+task_code+'-taskMWC').val(''); 
           }
           
           if(jQuery('#setnotification_taskMWC').is(':checked')){
                jQuery('#row-'+task_code+'-emailnotification').val('checked');
           }else{
              jQuery('#row-'+task_code+'-emailnotification').val(''); 
           }
           
           if(jQuery('#multiselectdropdownstats').is(':checked')){
                jQuery('#row-'+task_code+'-multiselectstatus').val('checked');
           }else{
              jQuery('#row-'+task_code+'-multiselectstatus').val(''); 
           }
           
           
           if(jQuery('#confrim_systaskstatus').is(':checked')){
                jQuery('#row-'+task_code+'-SystemTask').val('checked');
           }else{
              jQuery('#row-'+task_code+'-SystemTask').val(''); 
           }
           
           jQuery('#row-'+task_code+'-taskCode').val(jQuery('#confrim_taskCode').val());
          
           
           if(jQuery('#confrim_taskMWDDP').is(':checked')){
                jQuery('#row-'+task_code+'-taskMWDDP').val('checked');
           }else{
             jQuery('#row-'+task_code+'-taskMWDDP').val('');
           }
           
           
            
            
            
        }

        });
    
}

