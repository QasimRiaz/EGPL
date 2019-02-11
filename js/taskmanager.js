

jQuery(document).ready(function() {



});

jQuery(function() {
    jQuery('#newtaskkey').keyup(function(e) {

        //return false;

        var valueme = jQuery(this).val().replace(/\s+/g, '_');
        jQuery("#loading").text("task_" + valueme.toLowerCase());
        // alert('qasimriaz');

    });
});
function create_sponsor_task() {


    jQuery("body").css("cursor", "progress");
    var url = currentsiteurl+'/';
    var key = jQuery('#newtaskkey').val();
    
    var preappend = key.replace(/\s+/g, '_');
    
    var uniquekey = "task_" + preappend.toLowerCase();
    var urlnew = url + 'wp-content/plugins/EGPL/taskmanager.php?createnewtask=check_sponsor_task_key_value';
    var data = new FormData();



    data.append('key', uniquekey);
    jQuery.ajax({
        url: urlnew,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(data) {

            var message = jQuery.parseJSON(data);
            jQuery('body').css('cursor', 'default');

            if (message.msg == 'already Exist') {

                // jQuery('#sponsor-form').hide();

                jQuery("form")[0].reset();
                jQuery("#loading").empty();
                swal({
                        title: "Error",
                        text: 'That key already exists. Choose a different name.',
                        type: "error",
                        confirmButtonClass: "btn-danger"
                    })



            } else {


                jQuery("#newtaskkey").attr("readonly", true);
          //      jQuery(".cleditor").cleditor();
          //      jQuery(".cleditor").cleditor()[0].clear();
                jQuery("#unique-buttons").hide();
                jQuery("#task_settings").show();



            }


        }, error: function(xhr, ajaxOptions, thrownError) {
            
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



function create_sponsor_task_data() {

   jQuery("body").css("cursor", "progress");
    var linkUrl = "";
    var linkName = "";
    var taskLabel = jQuery("#tasklabel").val();
    var uniqueKey = taskLabel.toLowerCase().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '_');
   
    var uniquecode =randomString(5, 'a#');
    uniqueKey ='task_'+uniqueKey+'_'+uniquecode;
    console.log(uniqueKey);
    
    var taskInputType = jQuery("#inputtype option:selected").val();
   // console.log(taskInputType);
    var taskDate = jQuery("#datepicker").val();
    //console.log(taskDate);
    var taskDescrpition = tinymce.activeEditor.getContent();
  
    var taskDropdownvalue = jQuery("#dropdownval").val();
    var taskAddationalAttr = jQuery("#attribure").val();
    var taskDropdownvalues = taskDropdownvalue.split(",");
    var taskRoles = jQuery("#Srole").val() || [];
    
   
     var selectedRoles=taskRoles.toString().split(',');
    if (taskInputType == 'link') {
        linkUrl = jQuery("#linkurlval").val();
        linkName = jQuery("#linknameval").val();
    }
   
    var data = new FormData();
    data.append('key', uniqueKey);
    data.append('labell', taskLabel);
    data.append('date', taskDate);
    data.append('descrpition', taskDescrpition);
    data.append('roles', selectedRoles);
    data.append('dropdown', taskDropdownvalues);
    data.append('type', taskInputType);
    data.append('linkurl', linkUrl);
    data.append('linkname', linkName);
    data.append('addational_attr', taskAddationalAttr);
    var url = currentsiteurl+'/';
    var urlnew = url + 'wp-content/plugins/EGPL/taskmanager.php?createnewtask=create_new_task';


    jQuery.ajax({
        url: urlnew,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(data) {


          





           
          //  jQuery("form")[0].reset();
          //  jQuery("#newtaskkey").attr("readonly", false);
          //  jQuery("#remove-task-buttons").hide();
          //  jQuery("#slectedKeyValue").hide();
          //  jQuery("#task_settings").hide();
          //  jQuery("#remove-task-buttons").hide();
          //  jQuery("#unique-buttons").show();
          //  jQuery('#updateTaskKey').show();
           
           
            
         //   jQuery("#loading").empty();
              jQuery('body').css('cursor', 'default');
             swal({
                        title: "Task added successfully",
                        text: 'Title : ' + taskLabel ,
                        type: "success",
                        confirmButtonClass: "btn-success"
                    },function() {
                                                                    location.reload();
                                                                 });
                     // <-- time in milliseconds
            
            
            





        }, error: function(xhr, ajaxOptions, thrownError) {
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
function sponsor_task_update() {

   jQuery("body").css("cursor", "progress");
    var linkUrl = "";
    var linkName = "";
    var taskLabel = jQuery("#tasklabel").val();
    var uniqueKey = jQuery("#slectedKeyValue").val();
    
   // console.log(uniqueKey);
    
    var taskInputType = jQuery("#inputtype option:selected").val();
   // console.log(taskInputType);
    var taskDate = jQuery("#datepicker").val();
    console.log(taskDate);
    var taskDescrpition = tinymce.activeEditor.getContent();
   
    var taskDropdownvalue = jQuery("#dropdownval").val();
    var taskAddationalAttr = jQuery("#attribure").val();
    var taskDropdownvalues = taskDropdownvalue.split(",");
    var taskRoles = jQuery("#Srole").val() || [];
    
   
     var selectedRoles=taskRoles.toString().split(',');
    if (taskInputType == 'link') {
        linkUrl = jQuery("#linkurlval").val();
        linkName = jQuery("#linknameval").val();
    }
   
    var data = new FormData();
    data.append('key', uniqueKey);
    data.append('labell', taskLabel);
    data.append('date', taskDate);
    data.append('descrpition', taskDescrpition);
    data.append('roles', selectedRoles);
    data.append('dropdown', taskDropdownvalues);
    data.append('type', taskInputType);
    data.append('linkurl', linkUrl);
    data.append('linkname', linkName);
    data.append('addational_attr', taskAddationalAttr);
    var url = currentsiteurl+'/';
    var urlnew = url + 'wp-content/plugins/EGPL/taskmanager.php?createnewtask=create_new_task';


    jQuery.ajax({
        url: urlnew,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(data) {


          





           
          //  jQuery("form")[0].reset();
          //  jQuery("#newtaskkey").attr("readonly", false);
          //  jQuery("#remove-task-buttons").hide();
          //  jQuery("#slectedKeyValue").hide();
          //  jQuery("#task_settings").hide();
          //  jQuery("#remove-task-buttons").hide();
          //  jQuery("#unique-buttons").show();
          //  jQuery('#updateTaskKey').show();
           
           
            
         //   jQuery("#loading").empty();
              jQuery('body').css('cursor', 'default');
             swal({
                        title: "Task Update successfully",
                        text: 'Title : ' + taskLabel ,
                        type: "success",
                        confirmButtonClass: "btn-success"
                    });
                     // <-- time in milliseconds
            
            
            





        }, error: function(xhr, ajaxOptions, thrownError) {
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

function checkinputtype() {

 
    var selectedValue = jQuery("#inputtype option:selected").text();

    if (selectedValue == 'Drop Down') {
           console.log(selectedValue);
        jQuery("#dropdownonly").show();
    } else if (selectedValue == 'Link') {

        jQuery("#linkname").show();
        jQuery("#linkurl").show();


    } else {
        jQuery("#dropdownonly").hide();
        jQuery("#linkname").hide();
        jQuery("#linkurl").hide();
    }

}

function get_edit_sponsor_task_date() {


    jQuery("body").css("cursor", "progress");
    var url = currentsiteurl+'/';
    var key = jQuery('#updateTaskKey option:selected').val();
   
    jQuery("#slectedKeyValue").val(key);
    
    
   
    var urlnew = url + 'wp-content/plugins/EGPL/taskmanager.php?createnewtask=get_edit_task_key_data';
    var data = new FormData();



    data.append('key', key);
    jQuery.ajax({
        url: urlnew,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(data) {
            jQuery('body').css('cursor', 'default');
            jQuery("#unique-buttons").hide();
            jQuery('#updateTaskKey').hide();
            jQuery("#slectedkeylabel").show();
            jQuery("#remove-task-buttons").show();
            jQuery("#task_settings").show();


            var person = jQuery.parseJSON(data);
            var attrs = person.taskattrs;
           
            jQuery('#inputtype option').each(function() {
                if (jQuery(this).attr('value') == person.type) {

                    jQuery("#inputtype").append('<option value="' + person.type + '" selected="selected" >' + jQuery(this).text() + '</option>');
                    jQuery(this).remove();

                }
            });
            var length = jQuery('#Srole').children('option').length;
           
            jQuery('#Srole option').each(function() {
                if (jQuery.inArray(jQuery(this).attr('value'), person.roles) !== -1) {
                      
                    jQuery("#Srole").append('<option value="' + jQuery(this).attr('value') + '" selected="selected" >' + jQuery(this).text() + '</option>');
                    jQuery(this).remove();
                }
            });

            if (person.type == 'select-2') {
                jQuery("#dropdownonly").show();
                var dropdownvalues = [];
                jQuery.each(person.options, function(key, value) {
                    dropdownvalues.push(value.label);
                });
                var editDropDownValues = dropdownvalues.toString();
                 console.log(editDropDownValues);
                jQuery("#dropdownval").val(editDropDownValues);
               
            }
            var dateTime = person.attrs;
            var taskDescrpitions = person.descrpition;
           // var formattedDate = new Date(dateTime);
            var role = [];
           // var month = formattedDate.getMonth();
           // var dueDate = (month + 1) + '/' + formattedDate.getDate() + '/' + formattedDate.getFullYear();


            if (person.type == 'link') {
                var getUrl = person.lin_url;
                var getName = person.linkname;
                jQuery("#linkurlval").val(getUrl);
                jQuery("#linknameval").val(getName);
                jQuery("#linkurl").show();
                jQuery("#linkname").show();
            }

         //  var d = jQuery.datepicker.parseDate("dd-M-yy", dateTime);
         //  var datestrInNewFormat = jQuery.datepicker.formatDate( "mm/dd/yy", d);
         //   console.log(datestrInNewFormat);
          //   var datestr=(dateTime.split(" ")[0]).split("-");
         //  alert(dateTime);
         
         var dateSplit = dateTime.split("-");            
         var fullDate = new Date(dateSplit[1] + " " + dateSplit[0] + ", " + dateSplit[2]);
         var currentDate = fullDate.getMonth()+1 + "/" +fullDate.getDate()+ "/" + fullDate.getFullYear();
           //   alert(currentDate);
           console.log(currentDate);
            jQuery("#tasklabel").val(person.label);
            jQuery("#slectedkeylabel").val(person.label);
           
            jQuery("#datepicker").val(currentDate);
           

        

           tinymce.activeEditor.setContent(taskDescrpitions);
            //alert(varTitle);
            jQuery("#attribure").val(attrs);
            jQuery("#editing").show();

            //alert(dat);
            
        }});
}

function removeTask(){

 var uniqueLable = jQuery("#updateTaskKey option:selected").text();

 swal({
							title: "Are you sure?",
							text: 'you want to permanently delete this Task : '+uniqueLable,
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
                                                             var Sname = conformRemoveSponsorTask();
								swal({
									title: "Deleted!",
									text: "Task deleted Successfully",
									type: "success",
									confirmButtonClass: "btn-success"
								},function() {
                                                                    location.reload();
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

function conformRemoveSponsorTask(){
   jQuery("body").css("cursor", "progress");
   var uniqueKey = jQuery("#updateTaskKey option:selected").val();
   var url = currentsiteurl+'/';
   var urlnew = url + 'wp-content/plugins/EGPL/taskmanager.php?createnewtask=removeTaskData';
   var data = new FormData();
   data.append('uniqueKey', uniqueKey);
   jQuery.ajax({
        url: urlnew,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(data) {
            jQuery('body').css('cursor', 'default');
           // location.reload();
            var sName = settingArray.ContentManager['sponsor_name'];
           
                
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
function randomString(length, chars) {
    var mask = '';
    if (chars.indexOf('a') > -1) mask += 'abcdefghijklmnopqrstuvwxyz';
    if (chars.indexOf('A') > -1) mask += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if (chars.indexOf('#') > -1) mask += '0123456789';
    if (chars.indexOf('!') > -1) mask += '~`!@#$%^&*()_+-={}[]:";\'<>?,./|\\';
    var result = '';
    for (var i = length; i > 0; --i) result += mask[Math.round(Math.random() * (mask.length - 1))];
    return result;
}