
 var t;
 var roleassignmenttable;
 var listview;
 var newfieldtask =0;
 var loadinglightbox;
 var taskuseremaillist = [];
 var deletedtaskslist = [];
 var editor;
jQuery(document).ready(function() {
   
   t = jQuery('.bulkeditfield').DataTable( {
        "order": [[ 0, "desc" ]],
        initComplete: function () {
            
           this.api().columns([0]).every( function () {
                var column = this;
                jQuery(".specialsearchfilter")
                    .on( 'change', function () {
                        var val = jQuery.fn.dataTable.util.escapeRegex(
                            jQuery(this).val()
                            
                        );
                       // var  searchvalue = val.replace(/([~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, ' ');
                        var regex = '\\b' + val + '\\b';
                        console.log(regex);
                        column.search(regex, true, false).draw();
                            
                            
                    } );
                 
               column.data().unique().sort().each( function ( d, j ) {
                    var val = jQuery(d).val();
                  
                  // jQuery(".specialsearchfilter").append( '<option value="'+val+'">'+val+'</option>' );
                   
                } 
                        
             );
                
               
               
                
            } );
            
        },
       
        
        'createdRow': function(row, data, dataIndex){
          //  console.log(row)
           // console.log(data)
          //  console.log(dataIndex)
            jQuery(row).attr('id', 'row-' + dataIndex);
        },
        paging: false,
        info: false,
        dom: '<"top"i><"clear">',
        columnDefs: [
            { "type": "html-input", "targets": [1] }
        ] 
    } );

   
		
     t.rowReordering();   
    
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
   if ( window.location.href.indexOf("user-fields") > -1)
    {
   jQuery('.block-msg-default').remove();
   jQuery('.blockOverlay').remove();
    }
});

    jQuery('.addnewbulktask').on( 'click', function () {
        
       //jQuery("#customers_select_search").select2({ allowClear: true });
        
        jQuery('#customers_select_search').val(null).trigger('change');
        
        
        
        var uniquecode  = randomString(5, 'a#')+"_addnewfield";
        var tasktypedata = jQuery('.addnewtaskdata-type').html();
        var getIndexValue = jQuery(".ui-sortable tr:first-child td:first-child").text();
      
        var col01 =  getIndexValue-1;
          console.log(col01);
        var col1 = '<div class="hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><i class="hi-icon fa fa-clone saveeverything" id="'+uniquecode+'" title="Create a clone" onclick="clonebulk_fields(this)" style="color:#262626;cursor: pointer;" data-toggle="tooltip" aria-hidden="true"></i> <i data-toggle="tooltip" title="Field Settings" name="'+uniquecode+'" onclick="bulkfieldsettings(this)" class="hi-icon fusion-li-icon fa fa-gears" ></i><i name="'+uniquecode+'" data-toggle="tooltip" style=" cursor: pointer;margin-left: 10px;" onclick="removebulk_fields(this)" title="Remove this field" class="hi-icon fusion-li-icon fa fa-times-circle " style="color:#262626;"></i></div>';
        
        var col2 = '<input data-toggle="tooltip" placeholder="Title" title="Title" id="row-'+uniquecode+'-title" style="margin-top: 10px;margin-bottom: 10px;" type="text" class="form-control" name="tasklabel" >  <input type="hidden" id="row-'+uniquecode+'-fieldCode" value=""><input type="hidden" id="row-'+uniquecode+'-Systemfield"  value="" ><input type="hidden" id="row-'+uniquecode+'-fieldtooltip"  value="" > <input type="hidden" id="row-'+uniquecode+'-SystemfieldInternal"  value="" > <input type="hidden" id="row-'+uniquecode+'-fieldstatusrequried"  value="" ><input type="hidden" id="row-'+uniquecode+'-fieldplaceholder"  value="" > <input type="hidden" id="row-'+uniquecode+'-attribute"  value="" ><input type="hidden" id="row-'+uniquecode+'-fielduniquekey"  value="" ><input type="hidden" id="row-'+uniquecode+'-linkurl"  value="" ><input type="hidden" id="row-'+uniquecode+'-linkname"  value="" ><input type="hidden" id="row-'+uniquecode+'-multiselect"  value="" > <input type="hidden" id="row-'+uniquecode+'-dropdownvlaues"  value="" >';
        
        var col3 = '<div class="topmarrginebulkedit"><select  data-toggle="tooltip" title="Field Type" class="select2 bulktasktypedrop" id="bulktasktype_'+uniquecode+'" data-placeholder="Field Type" data-allow-clear="true">'+tasktypedata+'</select></div>'; //var col4 = '<br><div class="addscrol"><div id="row-'+uniquecode+'-descrpition" class="editfielddiscrpition_'+uniquecode+'"></div><p ><i class="font-icon fa fa-edit" id="fielddiscrpition_'+uniquecode+'" title="Edit your task description"style="cursor: pointer;color: #0082ff;"onclick="bulkfield_descripiton(this)"></i><span id="desplaceholder-'+uniquecode+'"style="margin-left: 10px;color:gray;">Description</span></p></div></div>';
        var col4 = '<p style="margin-top: 10px;">Display on Application Form <input style="margin-left: 116px;margin-top: -17px;" id="row-'+uniquecode+'-fieldstatusshowonregform" type="checkbox" class="form-control" ></p>'; //var col4 = '<br><div class="addscrol"><div id="row-'+uniquecode+'-descrpition" class="editfielddiscrpition_'+uniquecode+'"></div><p ><i class="font-icon fa fa-edit" id="fielddiscrpition_'+uniquecode+'" title="Edit your task description"style="cursor: pointer;color: #0082ff;"onclick="bulkfield_descripiton(this)"></i><span id="desplaceholder-'+uniquecode+'"style="margin-left: 10px;color:gray;">Description</span></p></div></div>';
        
        
        var col5 = '<p style="margin-top: 5px;"><i class="font-icon fa fa-edit" id="fielddiscrpition_'+uniquecode+'" title="Edit your field description"style="cursor: pointer;color: #0082ff;"onclick="bulkfield_descripiton(this)"></i><span id="desplaceholder-'+uniquecode+'"style="margin-left: 10px;color:gray;">Description</span></p><div class="addscrolfield"><div id="row-'+uniquecode+'-descrpition" class="editfielddiscrpition_'+uniquecode+'"></div></div></div>';
        t.row.add( [
            col01,
             col1,
             col2,
             col3,
             col4,
             col5,
            
        ]).draw().nodes().to$().addClass("bulkaddnewtask");
        
        t.column(0).order('desc').draw();
        jQuery('#bulktasktype_'+uniquecode).select2();
       
    
        var $eventSelect = jQuery(".bulktasktypedrop");
        //$eventSelect.on("select2:open", function (e) {  console.log('open'); });
        //$eventSelect.on("select2:close", function (e) { console.log('close'); });
        $eventSelect.on("select2:select", function (e) {
            console.log('1');
            var selectedtype = jQuery(this).val();
            var className = jQuery(this).attr('id');

            console.log(selectedtype);
            if (selectedtype == 'dropdown') {

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
    });
    
   
    
   
   
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
     
    
     if (selectedtype == 'dropdown') {

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
            text: 'Changing filed input type can result in losing user submissions that were made for this field in the past. Are you sure you want to continue?',
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
                    text: "Field input type change successfully.",
                    type: "success",
                    confirmButtonClass: "btn-success"
                });
            
            } else {
                jQuery('#'+e.currentTarget['id']).val(oldselectingvalue).trigger("change");
                swal({
                    title: "Cancelled",
                    text: "Field input type safe ",
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
            }
        });
});

function bulkfield_descripiton(e){
    
       
        
         var classname = jQuery(e).attr("id");
         console.log(classname);
         var desplaceholder = jQuery(e).attr("id").split('_');
         var descrpition = jQuery(".edit"+classname).html();
      
      
        var updatedescripiton = jQuery.confirm({
            
        title: 'Field Descrpition',
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
function bulkfield_tooltip(e){
    
       
        
         var classname = jQuery(e).attr("id");
         var desplaceholder = jQuery(e).attr("id").split('_');
         var tooltip = jQuery(".edit"+classname).html();
      
      
        var updatedescripiton = jQuery.confirm({
            
        title: 'Help Text',
        content: '<textarea name="fieldtooltip" class="fieldtooltip"  >'+tooltip+'</textarea>',
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
            
                jQuery("#tooltipholder-"+desplaceholder[1]).show();
            
            }else{
                
                jQuery("#tooltipholder-"+desplaceholder[1]).hide();
            }
        }

        });
        
        
  tinymce.init({
  selector: '.fieldtooltip',
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

function clonebulk_fields(e){
        
        
      
       // jQuery("#customers_select_search").select2({ allowClear: true });
         jQuery('#customers_select_search').val(null).trigger('change');
         
        
        
        var uniquecode  = randomString(5, 'a#')+"_addnewfield";
        var currentclickid = jQuery(e).attr('id');
        var clonetask = jQuery('#'+currentclickid).parent('p').parent('td').parent('tr').addClass('clontrposition');
      
        var countervalue = 1;
        var anSelected = jQuery(e).parents('tr');
        var data=[];
        jQuery(anSelected).find('td').each(function(){
            
            var regex = new RegExp(currentclickid, 'g');
           
            var res = jQuery(this).html().replace(regex, uniquecode);
            var resnew = res;
           
            if(countervalue ==1){
                 
                var getIndexValue = jQuery(".ui-sortable tr:first-child td:first-child").text();
                resnew = getIndexValue-1;
                
             }else if(countervalue == 4 || countervalue == 6 ){
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
        
        
        
        jQuery('#row-'+uniquecode+'-fielduniquekey').val('');
        jQuery('#row-'+uniquecode+'-Systemfield').val('');
        jQuery('#row-'+uniquecode+'-SystemfieldInternal').val('');
        
        
        
        jQuery('#bulktasktype_'+uniquecode).select2();
        jQuery("#bulktasktype_"+uniquecode).attr('disabled',false);
        
        var $eventSelect = jQuery(".bulktasktypedrop");
        //$eventSelect.on("select2:open", function (e) {  console.log('open'); });
        //$eventSelect.on("select2:close", function (e) { console.log('close'); });
        $eventSelect.on("select2:select", function (e) {
            console.log('1');
            var selectedtype = jQuery(this).val();
            var className = jQuery(this).attr('id');

            console.log(selectedtype);
            if (selectedtype == 'dropdown') {

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
       
        jQuery('#loadingalert').removeClass('showwaitingboox');
       // jQuery('.loadingalert').css("display", "none !important");
         //console.log(resnew);
   // } );
     
   }                                  
                                    
 function removebulk_fields(e){
     var productID = jQuery(e).attr("name");
      swal({
            title: "Are you sure?",
            text: 'Deleting a field will also delete ALL data collected on this field from users, and you will no longer be able to reference or report on this data. It is recommended that before deleting a field you first export all data currently collected on this field.',
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {

            
            jQuery('#customers_select_search').val(null).trigger('change');
            if (isConfirm) {
                 t.row( jQuery(e).parents('tr') ).remove().draw();
                 
                 deletedtaskslist.push(productID);
                 
                swal({
                    title: "Deleted!",
                    text: "Filed removed. It will be deleted when you save changes.",
                    type: "success",
                    confirmButtonClass: "btn-success"
                }
                );
            } else {
                swal({
                    title: "Cancelled",
                    text: "Field is safe",
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
                    text: "Field deleted Successfully",
                    type: "success",
                    confirmButtonClass: "btn-success"
                }
                );
            } else {
                swal({
                    title: "Cancelled",
                    text: "Field is safe ",
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
            }
        });
         
     
     
     
 }                             
                               
                                
function saveallbulkcustomefields(){
   
   
    //jQuery("#customers_select_search").select2("val", "");
   //jQuery("#customers_select_search").select2({ allowClear: true });
   
   jQuery('#customers_select_search').val(null).trigger('change');
   
   
    jQuery("body").css({'cursor':'wait'});
    var taskdataupdate = {};
    var requeststatus = 'stop';
    var errormsg= "";
    var titlemsg = "";
    var Indexfield = 0;
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
            errormsg = "Multiple fields have the same title. Please give each field a unique title.";
            titlemsg = 'Duplicate Field Title Detected';
            return false;
           
           
       }
      
     
        
    var taskid = jQuery( this ).attr('id');
    
    
    var str = jQuery( '#row-'+taskid+'-title' ).val();
    if(jQuery.trim( str ).length !=0  && jQuery( '#bulktasktype_'+taskid ).val() !=""){
        
        //if(jQuery( '#row-'+taskid+'-title' ).val() !='Company Name'){
        
        if(/^[ A-Za-z0-9_?()\-]*$/.test(str) == false) {
           specialcharacterstatus = true;
        }else{
            specialcharacterstatus = false;
        }
        
        if(specialcharacterstatus == false){
        jQuery('#'+taskid).parent('div').parent('td').parent('tr').removeClass('emptyfielderror');
        requeststatus = 'update';
        var singletaskarray={};
      
        var uniqueKey = jQuery( '#row-'+taskid+'-fielduniquekey' ).val();
        if(uniqueKey == ""){
            
              var taskLabel = jQuery( '#row-'+taskid+'-title' ).val();
              var uniqueKey = taskLabel.toLowerCase().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '_');
              var uniquecode =randomString(5, 'a#');
              uniqueKey ='customefield_'+uniqueKey+'_'+uniquecode;
            
            
        }
        
        singletaskarray['label'] = jQuery( '#row-'+taskid+'-title' ).val();
        singletaskarray['type'] = jQuery( '#bulktasktype_'+taskid ).val();
        singletaskarray['lin_url'] = jQuery( '#row-'+taskid+'-linkurl' ).val();
        singletaskarray['linkname'] = jQuery( '#row-'+taskid+'-linkname' ).val();
        singletaskarray['fieldtooltip'] = jQuery( '#row-'+taskid+'-fieldtooltip' ).val();
        singletaskarray['fieldstatusrequried'] = jQuery( '#row-'+taskid+'-fieldstatusrequried' ).val();
        singletaskarray['Systemfield'] = jQuery( '#row-'+taskid+'-Systemfield' ).val();
        var displayonformstatus = "";
        if(jQuery( '#row-'+taskid+'-fieldstatusshowonregform' ).is(':checked')){
                
                displayonformstatus = "checked";
                
           }
        
        singletaskarray['fieldstatusshowonregform'] = displayonformstatus;//jQuery( '#row-'+taskid+'-fieldstatusshowonregform' ).val();
        singletaskarray['fieldplaceholder'] = jQuery( '#row-'+taskid+'-fieldplaceholder' ).val();
        singletaskarray['attribute'] = jQuery( '#row-'+taskid+'-attribute' ).val();
        singletaskarray['SystemfieldInternal'] = jQuery( '#row-'+taskid+'-SystemfieldInternal' ).val();
        singletaskarray['multiselect'] = jQuery( '#row-'+taskid+'-multiselect' ).val();
        
        
        
        
        
         singletaskarray['fielduniquekey'] = uniqueKey;
        
               
        
            
        singletaskarray['Indexfield'] = Indexfield;
           
             
        
        
        
        Indexfield++;
        singletaskarray['descrpition'] = jQuery( '#row-'+taskid+'-descrpition' ).html();
        singletaskarray['fieldCode'] = uniqueKey;
        
        
        
          //task action array 
      if(jQuery('#bulktasktype_'+taskid).val() == 'dropdown' && jQuery( '#row-'+taskid+'-title' ).val() !="Level"){
          
          if(jQuery('#row-'+taskid+'-dropdownvlaues').val() !=""  ){
          
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
        }else{
            
         jQuery('#'+taskid).parent('div').parent('td').parent('tr').addClass('emptyfielderror');
         requeststatus = 'stop';
         errormsg = "Dropdown fields must contain a comma separated list of values. Please go to Field settings to add this.";
         titlemsg = 'Dropdown';
         return false;
            
            
        }
           
       }
     
      taskdataupdate[taskid]=singletaskarray;
  }else{
         
         
         jQuery('#'+taskid).parent('div').parent('td').parent('tr').addClass('emptyfielderror');
       
         requeststatus = 'stop';
         errormsg = "Uh-oh, looks like you're using special characters (i.e. '&', ',', etc) that Field titles don't support. Please remove any special characters from the title and try again.";
         titlemsg = 'Unsupported Characters';
         return false;
     }
   //}else{
     
       // jQuery('#'+taskid).parent('div').parent('td').parent('tr').addClass('emptyfielderror');
       
       //  requeststatus = 'stop';
       //  errormsg = "Multiple tasks have the same title. Please give each task a unique title.";
       //  titlemsg = 'Duplicate Task Title';
       //  return false;
     
     
     
     
 //}  
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
    var urlnew = url + 'wp-content/plugins/EGPL/taskmanager.php?createnewtask=savebulkfields';
    var data = new FormData();
     console.log(taskdataupdate);
    data.append('bulkfielddata',   JSON.stringify(taskdataupdate));
    data.append('deletedfieldlist',   JSON.stringify(deletedtaskslist));
    
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
            jQuery("body").css({'cursor':'default'});
            location.reload();
             //document.location.href = currentsiteurl+'/dashboard'
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

function bulkfieldsettings(e){
    
  var task_code = jQuery(e).attr('name');
  var fieldCode = jQuery('#row-'+task_code+'-fieldCode').val();
  var selectedtasktype = jQuery( '#bulktasktype_'+task_code ).val();
  var Systemfield = jQuery('#row-'+task_code+'-Systemfield').val();
  var fieldtooltip = jQuery('#row-'+task_code+'-fieldtooltip').val();
  var fieldstatusrequried = jQuery('#row-'+task_code+'-fieldstatusrequried').val();
  var fieldstatusshowonregform = jQuery('#row-'+task_code+'-fieldstatusshowonregform').val();
  var fieldplaceholder = jQuery('#row-'+task_code+'-fieldplaceholder').val();
  var field_title = jQuery('#row-'+task_code+'-title').val();
  var task_attribute_value = jQuery('#row-'+task_code+'-attribute').val();
  var linkurl = jQuery('#row-'+task_code+'-linkurl').val();
  var linkname = jQuery('#row-'+task_code+'-linkname').val();
  var dropdownvlaues = jQuery('#row-'+task_code+'-dropdownvlaues').val();
  var fielduniquekey = jQuery('#row-'+task_code+'-fielduniquekey').val();
  var SystemfieldInternal = jQuery('#row-'+task_code+'-SystemfieldInternal').val();
  var multiselect = jQuery('#row-'+task_code+'-multiselect').val();
  
  
  var trvalue='';
  if(selectedtasktype == 'file'){
      
     var attributes_file = task_attribute_value.replace('accept=','');
     var substr = attributes_file.split(',');
	 
		trvalue = '<tr><td ><strong>Accept File Types</strong><br>(List of acceptable file extensions. Leave blank for all)</td><td ><select style="width:150px !important;" id="confrim_attributes" title="Select File Types" class="select2 form-control newmultiselect" multiple="multiple" name="attribure" value="'+attributes_file+'" ><option value=".eps" ' + ((jQuery.inArray("eps", substr) != -1)? "selected" : "") + '>.eps</option><option value=".ai" ' + ((jQuery.inArray("ai", substr) != -1)? "selected" : "") + '>.ai</option><option value=".jpg" ' + ((jQuery.inArray("jpg", substr) != -1)? "selected" : "") + '>.jpg</option><option value=".png" ' + ((jQuery.inArray("png", substr) != -1)? "selected" : "") + '>.png</option><option value=".jpeg" ' + ((jQuery.inArray("jpeg", substr) != -1)? "selected" : "") + '>.jpeg</option><option value=".pdf" ' + ((jQuery.inArray("pdf", substr) != -1)? "selected" : "") + '>.pdf</option><option value=".ppt" ' + ((jQuery.inArray("ppt", substr) != -1)? "selected" : "") + '>.ppt</option><option value=".pptx" ' + ((jQuery.inArray("pptx", substr) != -1)? "selected" : "") + '>.pptx</option><option value=".doc" ' + ((jQuery.inArray("doc", substr) != -1)? "selected" : "") + '>.doc</option><option value=".docx" ' + ((jQuery.inArray("docx", substr) != -1)? "selected" : "") + '>.docx</option><option value=".xls" ' + ((jQuery.inArray("xls", substr) != -1)? "selected" : "") + '>.xls</option><option value=".xlsx" ' + ((jQuery.inArray("xlsx", substr) != -1)? "selected" : "") + '>.xlsx</option></select></td></tr>';
     
    // trvalue='<td ><strong>Accept File Types</strong><br>(List of acceptable file extensions)</td><td ><input name="attribure"  placeholder=".png,.eps" id="confrim_attributes"  class="form-control"  value="'+attributes_file+'" ></td>';
  }else if(selectedtasktype == 'textarea'){
       var attributes_file = task_attribute_value.replace('maxlength=','');
       trvalue = '<tr><td ><strong>Max Length</strong><br>(Number of characters allowed)</td><td ><input name="attribure"  placeholder="200" id="confrim_attributes"  class="form-control"  value="'+attributes_file+'" ></td></tr>';
      
  }else if(selectedtasktype == 'link'){
      
      
       trvalue = '<tr><td ><strong>Link Name</strong></td><td ><input id="field_link_name" value="'+linkname+'"></td></tr><tr><td ><strong>Link URL</strong></td><td ><input id="field_link_url" value="'+linkurl+'"></td></tr>';
     
      
  }else if(selectedtasktype == 'dropdown'){
      
      
     trvalue = '<tr><td ><strong>Comma separated list of values</strong></td><td ><input id="field_drop_down_values" value="'+dropdownvlaues+'"></td></tr>';
     trvalue += '<tr style="'+specialclasssnmae+'"><td><strong>Multi Select</strong></td><td><input '+multiselect+' type="checkbox" class="toggle-one" id="confrim_multiselect" data-toggle="toggle"></td></tr>';
    
  }
  
  var currentadmnirole = jQuery('#currentadmnirole').val();
  
  
  var specialclasssnmae="display:none;";
  if(currentadmnirole == 'Administrator'){
      
    specialclasssnmae = "";
  }
  
   var htmlforsystemtask = '<tr style="'+specialclasssnmae+'"><td><strong>System Field Status</strong></td><td><input '+Systemfield+' type="checkbox" class="toggle-one" id="confrim_systaskstatus" data-toggle="toggle"></td></tr>';
   var htmlcheckforinternaltasks = '<tr style="'+specialclasssnmae+'"><td><strong>System Field Internal</strong></td><td><input '+SystemfieldInternal+' type="checkbox" class="toggle-one" id="confrim_systaskstatusinternal" data-toggle="toggle"></td></tr>';
   var fielduniquekeyhtml = '<tr style="'+specialclasssnmae+'"><td><strong>Field Unique Key</strong></td><td><input value="'+fielduniquekey+'" type="text"  id="confrim_fielduniquekey" ></td></tr>';
   
  
  var content='';
 
            
  content='<table><tr><h5 style="margin-top: 2px;">'+field_title+'</h5><hr/></tr></table><table><tr><td><strong>Required?</strong></td><td><input '+fieldstatusrequried+' type="checkbox" class="toggle-one" id="confrim_fieldRequriedstatus" data-toggle="toggle"></td></tr><tr><td><strong>Placeholder Text</strong></td><td><input type="text" value="'+fieldplaceholder+'" id="confrim_placeholdertext" ></td></tr></tr><tr><td><strong>Help Text</strong></td><td><textarea  id="confrim_helptext">'+fieldtooltip+'</textarea></td></tr><tr>'+trvalue+'</tr>'+fielduniquekeyhtml+htmlcheckforinternaltasks+htmlforsystemtask+'</table>'; 
 
  jQuery.confirm({
            
        title: 'Field Settings',
        content: content,
        confirmButton: 'Update',
        cancelButton: false,
        confirmButtonClass: 'btn mycustomwidth btn-lg btn-primary mysubmitemailbutton',
        closeIcon: true,
        onOpen: function() {
         jQuery('.toggle-one').bootstrapToggle();
         jQuery('#confrim_fieldRequriedstatus').bootstrapToggle({
            on: 'Yes',
            off: 'No'
          });
           
                 console.log(attributes_file);
		 jQuery.each(attributes_file.split(","), function(i,e){
		 jQuery(".select2 option[value='" + e + "']").prop("selected", true);
		  
		});
		jQuery('.newmultiselect').select2({
			placeholder: "Select file types"
		});
        },
        confirm: function () {
            
            var attributes='';
            if(selectedtasktype == 'file'){
                if(jQuery('#confrim_attributes').val() !=""){
                    attributes+='accept='+jQuery('#confrim_attributes').val();
                }
           }else if(selectedtasktype == 'textarea'){
           
                if(jQuery('#confrim_attributes').val() !=""){
                attributes+='maxlength='+jQuery('#confrim_attributes').val();
                }
            }
            
           jQuery('#row-'+task_code+'-attribute').val(attributes);
           
           if(jQuery('#confrim_fieldRequriedstatus').is(':checked')){
                jQuery('#row-'+task_code+'-fieldstatusrequried').val('checked');
           }else{
              jQuery('#row-'+task_code+'-fieldstatusrequried').val(''); 
           }
           
           
           
         
           if(jQuery('#confrim_multiselect').is(':checked')){
                jQuery('#row-'+task_code+'-multiselect').val('checked');
           }else{
             jQuery('#row-'+task_code+'-multiselect').val('');
           }
           
           
           if(jQuery('#confrim_systaskstatus').is(':checked')){
                jQuery('#row-'+task_code+'-Systemfield').val('checked');
           }else{
             jQuery('#row-'+task_code+'-Systemfield').val('');
           }
           if(jQuery('#confrim_systaskstatusinternal').is(':checked')){
                jQuery('#row-'+task_code+'-SystemfieldInternal').val('checked');
           }else{
             jQuery('#row-'+task_code+'-SystemfieldInternal').val('');
           }
           jQuery('#row-'+task_code+'-fieldplaceholder').val(jQuery('#confrim_placeholdertext').val());
           jQuery('#row-'+task_code+'-fieldtooltip').val(jQuery('#confrim_helptext').val()); 
           jQuery('#row-'+task_code+'-linkurl').val(jQuery('#field_link_url').val()); 
           jQuery('#row-'+task_code+'-linkname').val(jQuery('#field_link_name').val()); 
           jQuery('#row-'+task_code+'-dropdownvlaues').val(jQuery('#field_drop_down_values').val()); 
           jQuery('#row-'+task_code+'-fielduniquekey').val(jQuery('#confrim_fielduniquekey').val()); 
          
            
            
            
        }

        });
    
}


  