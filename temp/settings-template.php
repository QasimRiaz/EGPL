<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
       
    //role.js 
		
      
     $oldvalues = get_option( 'ContenteManager_Settings' );
     $eventdate = $oldvalues['ContentManager']['eventdate'];
     $mainheaderbackground = $oldvalues['ContentManager']['mainheader'];
     
     $headelogo = $oldvalues['ContentManager']['headerlogo'];
     $sitefavicon = $oldvalues['ContentManager']['sitefavicon'];
     
     $mainheaderlogo = $oldvalues['ContentManager']['mainheaderlogo'];
     $applicationmoderationstatus = $oldvalues['ContentManager']['applicationmoderationstatus'];
     $eventstartdate = $oldvalues['ContentManager']['eventstartdate'];
     $eventenddate = $oldvalues['ContentManager']['eventenddate'];
     $prinarythemecolor = $oldvalues['ContentManager']['prinarythemecolor'];
     $secondarythemecolor = $oldvalues['ContentManager']['secondarythemecolor'];
     
     $eventaddress = $oldvalues['ContentManager']['eventaddress'];
     
     
     $buttonfontcolor = $oldvalues['ContentManager']['buttonfontcolor'];
     
     
     
     
     $exhibitorterminology = $oldvalues['ContentManager']['exhibitorterminology'];
     $boothterminology = $oldvalues['ContentManager']['boothterminology'];
     $packageterminology = $oldvalues['ContentManager']['packageterminology'];
     $addonsterminology = $oldvalues['ContentManager']['addonsterminology'];
     
     
     $ordernotficationemails = $oldvalues['ContentManager']['ordernotficationemails'];
     
     $welcomememailreplayto = get_option('AR_Contentmanager_Email_Template_welcome');
     $replaytoemailadd = $welcomememailreplayto['welcome_email_template']['replaytoemailadd'];
     $registration_notificationemails = $oldvalues['ContentManager']['registration_notificationemails'];
     
     if(empty($registration_notificationemails)){
         
         $registration_notificationemails  = $replaytoemailadd;
     }
     
     //echo $mainheader;exit;
     
     $formemail = $oldvalues['ContentManager']['formemail'];
     $mandrill = $oldvalues['ContentManager']['mandrill'];
     $infocontent = $oldvalues['ContentManager']['infocontent'];
     $addresspoints = $oldvalues['ContentManager']['addresspoints'];
     
       include 'cm_header.php';
       include 'cm_left_menu_bar.php';
               
       ?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.2.0/css/bootstrap-colorpicker.min.css"
    rel="stylesheet">
<link  href="/wp-content/plugins/EGPL/css/jquery.datetimepicker.min.css" rel="stylesheet">
<link  href="/wp-content/plugins/EGPL/css/cropper.css" rel="stylesheet">
<link rel="stylesheet" href="/wp-content/plugins/EGPL/css/main.css">

       <style>
    
    .egmb-10{margin-bottom: 10px;}
    .egmb-20{margin-bottom: 20px;}
    .radio input{visibility: unset;}
    .egradio-inline{display: inline;}
    .egcolor{color:red;}
    .row{margin-left: 0px;}
    
</style>     
<div class="blockUI" style="display:none;"></div>
<div class="blockUI blockOverlay" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgba(142, 159, 167, 0.8); opacity: 1; cursor: wait; position: absolute;"></div>
<div class="blockUI block-msg-default blockElement" style="z-index: 1011; position: absolute; padding: 0px; margin: 0px;  top: 300px;  text-align: center; color: rgb(0, 0, 0); border: 3px solid rgb(170, 170, 170); background-color: rgb(255, 255, 255); cursor: wait; height: 200px;left: 50%;">
        <div class="blockui-default-message">
            <i class="fa fa-circle-o-notch fa-spin"></i><h6>Please Wait.</h6></div></div> 
        <div class="page-content">
        <div class="container-fluid">
            <header class="section-header" style="text-align: center;">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Exhibitor Portal Settings</h3>
                           
                        </div>
                    </div>
                </div>
            </header>

            <div class="box-typical box-typical-padding">
                
                    <div class="card-header egmb-20">
                        <h4 class="card-title">Branding</h4>
                       
                    </div>
                    
                    
                    <form method="post" action="javascript:void(0);" onSubmit="portalsettings_update()">
                            
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="form-control-label" for="inputEventStart">Event Start Date</label>
                                    <input type="text" class="form-control datetimepicker portalsettings" name="eventstartdate" value="<?php echo $eventstartdate;?>" id="inputEventStart" placeholder="Event Start Date" required="true">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form-control-label" for="inputEventend">Event End Date</label>
                                    <input type="text" class="form-control datetimepicker portalsettings" name="eventenddate" value="<?php echo $eventenddate;?>" id="inputEventend" placeholder="Event End Date" required="true">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label class="form-control-label" for="inputEventStart">Event Address</label>
                                    <textarea  class="form-control portalsettings" name="eventaddress"  id="inputEventaddress" placeholder="Event Address" ><?php echo $eventaddress;?></textarea>
                                </div>
                            </div>
                        <p><h6 class="card-title">Site Theme Colors</h6></p>
                            
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label class="form-control-label" >Primary Color <i class="fa fa-question-circle fa-sm" title="The primary color of buttons and icons throughout the portal"></i></label>
                                    <input type="text" class="form-control cp-component portalsettings" name="prinarythemecolor" id="inputprimarytheme" value="<?php echo $prinarythemecolor;?>" placeholder="Primary Theme Color" required="true">
                                </div>
<!--                                <div class="form-group col-sm-6">
                                    <label class="form-control-label">Secondary Color <i class="fa fa-question-circle fa-sm"></i></label>
                                    <input type="text" class="form-control cp-component portalsettings" name="secondarythemecolor" id="inputsecondarytheme" value="<?php echo $secondarythemecolor;?>" placeholder="Secondary Theme Color" required="true">
                                </div>-->
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="form-control-label" >Button Font Color <i class="fa fa-question-circle fa-sm" title="The font color within buttons"></i></label>
                                    <input type="text" class="form-control cp-component portalsettings" name="buttonfontcolor" id="inputbuttonfontcolor" value="<?php echo $buttonfontcolor;?>" placeholder="Buttons Font Color" required="true">
                                </div>
                                
                            </div>
                    
                    
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label class="form-control-label" >Header Logo </label>
                                    <input type="file" class="form-control" id="inputImageLogo"  accept=".jpeg, .jpg, .jpe,.png">
                                    <input type="hidden"  id="headerimageLogo"  <?php if(!empty($headelogo)){ echo 'value="'.$headelogo.'"';}?> >
                                </div>
                                
                                <div class="headerimagecropperLogo" >
                                        <div class="row">
                                            <div class="col-md-9">
                                                <!-- <h3>Demo:</h3> -->
                                                <div class="img-container">
                                                    <?php if(!empty($headelogo)){
                                                        
                                                         echo '<img id="headerbgimgLogo" src="'.$headelogo.'" alt="Header Logo">';
                                                        
                                                    }else{
                                                        
                                                        echo '<img id="headerbgimgLogo" src="/wp-content/plugins/EGPL/temp/default-benner.png" alt="Header Logo">';
                                                    }?>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                    <!-- <h3>Preview:</h3> -->
                                                   
                                                    <div class="row">
                                                    <div class="form-group col-md-10">
                                                            <label class="form-control-label" for="dataHeightLogo">Width</label>
                                                            <input type="text" class="form-control"   id="dataWidthLogo" placeholder="Width in px" title="Width in px"readonly="true">
                                                            
                                                    </div></div><div class="row">
                                                    <div class="form-group col-md-10">
                                                            <label class="form-control-label" for="dataHeightLogo">Height</label>
                                                            <input type="text" class="form-control"   id="dataHeightLogo" placeholder="Height in px" title="Height in px"readonly="true">
                                                            
                                                    </div></div>
                                                    <div class="row">
                                                    <div class="form-group col-md-11">
                                                            
                                                        <div class="previewDivselectedImageLogo"></div>
                                                            
                                                    </div></div>
                                                </div>
                                                

                                                <!-- <h3>Data:</h3> -->

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-9 logo-docs-buttons docs-buttons">
                                                <!-- <h3>Toolbar:</h3> -->


                                                <div class="logo-btn-group btn-group">
                                                    <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Zoom In">
                                                            <span class="fa fa-search-plus"></span>
                                                        </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Zoom Out">
                                                            <span class="fa fa-search-minus"></span>
                                                        </span>
                                                    </button>
                                                </div>

                                                <div class="logo-btn-group btn-group">
                                                    <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Move Left">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Left">
                                                            <span class="fa fa-arrow-left"></span>
                                                        </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Move Right">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Right">
                                                            <span class="fa fa-arrow-right"></span>
                                                        </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Move Up">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Up">
                                                            <span class="fa fa-arrow-up"></span>
                                                        </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Move Down">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Down">
                                                            <span class="fa fa-arrow-down"></span>
                                                        </span>
                                                    </button>
                                                </div>

                                                <div class="logo-btn-group btn-group">
                                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Rotate Left">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Rotate Left">
                                                            <span class="fa fa-undo"></span>
                                                        </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Rotate Right">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Rotate Right">
                                                            <span class="fa fa-repeat"></span>
                                                        </span>
                                                    </button>
                                                </div>

                                              

                                                <div class="logo-btn-group btn-group">
                                                    <button type="button" class="btn btn-success" data-method="getCroppedCanvas" title="Crop">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Apply">
                                                            <span class="fa fa-check"></span>
                                                        </span>
                                                    </button>
                                                   
                                                </div>


            
                                            </div><!-- /.docs-buttons -->


                                        </div>
                                    </div>
                                <div class="row">
                                <div class="form-group col-sm-12">
                                    <label class="form-control-label" >Site Favicon </label>
                                    <input type="file" class="form-control" id="inputImageFavicon"  accept=".jpeg, .jpg, .jpe,.png">
                                    <input type="hidden"  id="headerimageFavicon" <?php if(!empty($sitefavicon)){ echo 'value="'.$sitefavicon.'"';}?> >
                                </div>
                                
                                <div class="headerimagecropperFavicon" >
                                        <div class="row">
                                            <div class="col-md-9">
                                                <!-- <h3>Demo:</h3> -->
                                                <div class="img-container">
                                                    <?php if(!empty($sitefavicon)){
                                                        
                                                         echo '<img id="headerbgimgFavicon" src="'.$sitefavicon.'" alt="Site Favicon">';
                                                        
                                                    }else{
                                                        
                                                        echo '<img id="headerbgimgFavicon" src="/wp-content/plugins/EGPL/temp/default-benner.png" alt="Site Favicon">';
                                                    }?>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                    <!-- <h3>Preview:</h3> -->
                                                    
                                                    <div class="row">
                                                    <div class="form-group col-md-10">
                                                            <label class="form-control-label" for="dataHeightFavicon">Width</label>
                                                            <input type="text" class="form-control"   id="dataWidthFavicon" placeholder="Width in px" title="Width in px"readonly="true">
                                                            
                                                    </div></div><div class="row">
                                                    <div class="form-group col-md-10">
                                                            <label class="form-control-label" for="dataHeightFavicon">Height</label>
                                                            <input type="text" class="form-control"   id="dataHeightFavicon" placeholder="Height in px" title="Height in px"readonly="true">
                                                            
                                                    </div></div>
                                                    <div class="row">
                                                    <div class="form-group col-md-11">
                                                            
                                                        <div class="previewDivselectedImageFavicon"></div>
                                                            
                                                    </div></div>
                                                </div>
                                                

                                                <!-- <h3>Data:</h3> -->

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-9 favicon-docs-buttons docs-buttons">
                                                <!-- <h3>Toolbar:</h3> -->


                                                <div class="favicon-btn-group btn-group">
                                                    <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Zoom In">
                                                            <span class="fa fa-search-plus"></span>
                                                        </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Zoom Out">
                                                            <span class="fa fa-search-minus"></span>
                                                        </span>
                                                    </button>
                                                </div>

                                                <div class="favicon-btn-group btn-group">
                                                    <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Move Left">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Left">
                                                            <span class="fa fa-arrow-left"></span>
                                                        </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Move Right">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Right">
                                                            <span class="fa fa-arrow-right"></span>
                                                        </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Move Up">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Up">
                                                            <span class="fa fa-arrow-up"></span>
                                                        </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Move Down">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Down">
                                                            <span class="fa fa-arrow-down"></span>
                                                        </span>
                                                    </button>
                                                </div>

                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Rotate Left">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Rotate Left">
                                                            <span class="fa fa-undo"></span>
                                                        </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Rotate Right">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Rotate Right">
                                                            <span class="fa fa-repeat"></span>
                                                        </span>
                                                    </button>
                                                </div>

                                              

                                                <div class="favicon-btn-group btn-group">
                                                    <button type="button" class="btn btn-success" data-method="getCroppedCanvas" title="Crop">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Apply">
                                                            <span class="fa fa-check"></span>
                                                        </span>
                                                    </button>
                                                   
                                                </div>


            
                                            </div><!-- /.docs-buttons -->


                                        </div>
                                    </div>
                    
                                <div class="row">
                                <div class="form-group col-sm-12">
                                    <label class="form-control-label" >Header Image </label>
                                    <input type="file" class="form-control" id="inputImage"  accept=".jpeg, .jpg, .jpe,.png">
                                    <input type="hidden"  id="headerimage" <?php if(!empty($mainheaderbackground)){ echo 'value="'.$mainheaderbackground.'"';}?>>
                                </div>
                                
                                <div class="headerimagecropper" >
                                        <div class="row">
                                            <div class="col-md-9">
                                                <!-- <h3>Demo:</h3> -->
                                                <div class="img-container">
                                                    <?php if(!empty($mainheaderbackground)){
                                                        
                                                         echo '<img id="headerbgimg" src="'.$mainheaderbackground.'" alt="Header Image">';
                                                        
                                                    }else{
                                                        
                                                        echo '<img id="headerbgimg" src="/wp-content/plugins/EGPL/temp/default-benner.png" alt="Header Image">';
                                                    }?>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                    <!-- <h3>Preview:</h3> -->
                                                    
                                                    <div class="row">
                                                    <div class="form-group col-md-10">
                                                            <label class="form-control-label" for="dataHeight">Width</label>
                                                            <input type="text" class="form-control"   id="dataWidth" placeholder="Width in px" title="Width in px"readonly="true">
                                                            
                                                    </div></div><div class="row">
                                                    <div class="form-group col-md-10">
                                                            <label class="form-control-label" for="dataHeight">Height</label>
                                                            <input type="text" class="form-control"   id="dataHeight" placeholder="Height in px" title="Height in px"readonly="true">
                                                            
                                                    </div></div>
                                                    <div class="row">
                                                    <div class="form-group col-md-11">
                                                            
                                                        <div class="previewDivselectedImageheader"></div>
                                                            
                                                    </div></div>
                                                </div>
                                                

                                                <!-- <h3>Data:</h3> -->

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-9 header-docs-buttons docs-buttons">
                                                <!-- <h3>Toolbar:</h3> -->


                                                <div class="header-btn-group btn-group">
                                                    <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Zoom In">
                                                            <span class="fa fa-search-plus"></span>
                                                        </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Zoom Out">
                                                            <span class="fa fa-search-minus"></span>
                                                        </span>
                                                    </button>
                                                </div>

                                                <div class="header-btn-group btn-group">
                                                    <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Move Left">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Left">
                                                            <span class="fa fa-arrow-left"></span>
                                                        </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Move Right">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Right">
                                                            <span class="fa fa-arrow-right"></span>
                                                        </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Move Up">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Up">
                                                            <span class="fa fa-arrow-up"></span>
                                                        </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Move Down">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Down">
                                                            <span class="fa fa-arrow-down"></span>
                                                        </span>
                                                    </button>
                                                </div>

                                                <div class="header-btn-group btn-group">
                                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Rotate Left">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Rotate Left">
                                                            <span class="fa fa-undo"></span>
                                                        </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Rotate Right">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Rotate Right">
                                                            <span class="fa fa-repeat"></span>
                                                        </span>
                                                    </button>
                                                </div>

                                              

                                                <div class="header-btn-group btn-group">
                                                    <button type="button" class="btn btn-success" data-method="getCroppedCanvas" title="Crop">
                                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Apply">
                                                            <span class="fa fa-check"></span>
                                                        </span>
                                                    </button>
                                                   
                                                </div>


            
                                            </div><!-- /.docs-buttons -->


                                        </div>
                                    </div>
                                
                    
                                
                           
                            
<!--                            <p><h5 class="card-title">Terminology</h5></p>
                            <p><h6 class="card-title">Set your site's terminology below</h6></p>
                             <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="form-control-label" >Exhibitor </label>
                                    <input type="text" class="form-control portalsettings" value="<?php echo $exhibitorterminology;?>" name="exhibitorterminology" id="inputexhibitorterminology" >
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form-control-label">Booth </label>
                                    <input type="text" class="form-control portalsettings" value="<?php echo $boothterminology;?>" name="boothterminology" id="inputboothterminology" >
                                </div>
                            </div>
                             <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="form-control-label" >Package </label>
                                    <input type="text" class="form-control portalsettings" value="<?php echo $packageterminology;?>" name="packageterminology" id="inputpackageterminology" >
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form-control-label">Add-Ons </label>
                                    <input type="text" class="form-control portalsettings" value="<?php echo $addonsterminology;?>" name="addonsterminology" id="inputaddonsterminology" >
                                </div>
                            </div>
                    
                        <div class="card-header egmb-20">
                            <h4 class="card-title">Entry Wizard</h4>
                        </div>
                        
                    <p><h6 class="card-title">The Entry Wizard is where you configure the pages  and flow that you end-users will go through before becoming users in the system.Click "Entry Wizard" to configure your user entry wizard flow. </h6></p>
                        
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <button class="btn btn-large btn-success">Entry Wizard</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12">
                                <label class="form-control-label" >Enable Application Moderation </label>
                                <input type="checkbox" class="toggle-one form-control" id="applicationmoderationstatus" data-toggle="toggle" <?php echo $applicationmoderationstatus;?>>
                        </div>
                    </div>
                    
                        <div class="card-header egmb-20">
                            <h4 class="card-title">Booth Assignment Settings</h4>
                        </div>
                    <br>
                        <p><h5 class="card-title">Visibility Settings</h5></p>
                        
                         Default unchecked 
                        
                        <div class="row">
                                <div class="form-group col-sm-12">
                                    
                                    <div class="form-check">
                                            <input class="form-check-input" type="radio" name="hideexhibitordetails" id="hideexhibitordetails" value="option1" >
                                            <label class="form-check-label egradio-inline" for="hideexhibitordetails">
                                                Hide Exhibitor Details
                                            </label>
                                            <p>Hides all exhibitor details from the public floor plan view. Booths will be just be labeled "Available, Reserved, or Occupied"</p>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="hidereservedboothexhibitordetails" id="hidereservedboothexhibitordetails" value="option2">
                                            <label class="form-check-label egradio-inline" for="hidereservedboothexhibitordetails">
                                                Hide Reserved Booth Exhibitor Details
                                            </label>
                                            <p>Hides all exhibitor details for "Reserved" booths only</p>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="hideboothprice" id="hideboothprice" value="option3" >
                                            <label class="form-check-label egradio-inline" for="hideboothprice">
                                                Hide Booth Price on Public View
                                            </label>
                                            <p>Hides booth prices from non-exhibitor view.</p>
                                        </div>
                                    
                                        <div class="form-check disabled">
                                            <input class="form-check-input" type="radio" name="displaycompnaynameonbooth" id="displaycompnaynameonbooth" value="option3" >
                                            <label class="form-check-label egradio-inline" for="displaycompnaynameonbooth">
                                                Display Company Name on Booth
                                            </label>
                                            <p>This shows both the booth number and company name on assigned booths. If this is unchecked, then the company name can be viewed by hovering over the booth, clicking into the booth, or selecting it on the exhibitor list.</p>
                                        </div>
                                    <br>
                                    <p><h4 class="card-title">Booth Selection Order</h4></p>
                                
                                </div>
                            </div>
                        
                            <div class="card-header egmb-20">
                                <h4 class="card-title">Payment Settings</h4>
                            </div>
                        
                            <p><h5 class="card-title">Payment Options</h5></p>
                            
                            <div class="row">
                            <div class="form-group col-sm-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="stripeoption" id="stripeoption" value="option1" >
                                            <label class="form-check-label egradio-inline" for="stripeoption">
                                                Stripe
                                            </label>
                                           
                                        </div>
                            </div>
                            <div class="form-group col-sm-5">
                                <input type="text" class="form-control" id="stipesecruitkey" placeholder="Secret Key">
                            </div>
                            <div class="form-group col-sm-5">
                                <input type="text" class="form-control" id="stripepublishkey" placeholder="Publisher Key">
                            </div>
                            </div>
                    
                            <div class="row">
                            <div class="form-group col-sm-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="authorizenet" id="authorizenet" value="option1" >
                                            <label class="form-check-label egradio-inline" for="authorizenet">
                                                Authorize.net
                                            </label>
                                           
                                        </div>
                            </div>
                            <div class="form-group col-sm-5">
                                <input type="text" class="form-control" id="authorizesecruitkey" placeholder="Secret Key">
                            </div>
                            <div class="form-group col-sm-5">
                                <input type="text" class="form-control" id="authorizepublishkey" placeholder="Publisher Key">
                            </div>
                            </div>
                    
                    
                            <div class="row">
                            <div class="form-group col-sm-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="paypaleoption" id="paypaleoption" value="option1" >
                                            <label class="form-check-label egradio-inline" for="paypaleoption">
                                                PayPal
                                            </label>
                                           
                                        </div>
                            </div>
                            <div class="form-group col-sm-5">
                                <input type="text" class="form-control" id="paypalsecruitkey" placeholder="Secret Key">
                            </div>
                            <div class="form-group col-sm-5">
                                <input type="text" class="form-control" id="paypalpublishkey" placeholder="Publisher Key">
                            </div>
                            </div>
                    
                            <div class="row">
                            <div class="form-group col-sm-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="paypaleoption" id="paypaleoption" value="option1" >
                                            <label class="form-check-label egradio-inline" for="paypaleoption">
                                                Check
                                            </label>
                                           
                                        </div>
                            </div>
                            <div class="form-group col-sm-5">
                                
                            </div>
                            <div class="form-group col-sm-5">
                                
                            </div>
                            </div>-->
                            
                            <div class="card-header egmb-20">
                                <h4 class="card-title">Notification Settings</h4>
                            </div>
                    
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="form-control-label" >Registration Notification Emails </label>
                                    <input type="text" class="form-control portalsettings" value="<?php echo $registration_notificationemails;?>" name="registration_notificationemails" id="registrationnoticationemails" >
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form-control-label">Order Notification Emails:  </label>
                                    <input type="text" class="form-control portalsettings" value="<?php echo $ordernotficationemails;?>" name="ordernotficationemails" id="ordernotifcationemails" >
                                </div>
                            </div>
                    <br>
                     <br>
                  <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"></label>
                                    <div class="col-sm-8">
                                             <button type="submit"  name="addsettings"  class="btn btn-lg mycustomwidth btn-success" value="Update">Update</button>
                                            
                                        
                                    </div>
                                </div>
                    
                    </form>
               
            </div>
        </div>
    </div>
     
                           
                                   




        
       
<?php   include 'cm_footer.php';?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.2.0/js/bootstrap-colorpicker.min.js"></script>
<script src="/wp-content/plugins/EGPL/js/cropper.js?v=1.2"></script>
<script src="/wp-content/plugins/EGPL/js/jquery.datetimepicker.full.js?v=1.2"></script>



<script>          
jQuery( document ).ready(function() {
        
        jQuery('.cp-component').colorpicker();
        jQuery(".datetimepicker").datetimepicker({
          format: 'd M Y',
          timepicker:false
        });
        jQuery(window).load(function() {
            if ( window.location.href.indexOf("admin-settings") > -1)
             {
                jQuery('.block-msg-default').remove();
                jQuery('.blockOverlay').remove();
             }
        });  
        
  });
  
  jQuery(function () {
  'use strict';

  var console = window.console || { log: function () {} };
  var URL = window.URL || window.webkitURL;
  var $image = jQuery('#headerbgimg');
  var $download = jQuery('#download');
  var $dataX = jQuery('#dataX');
  var $dataY = jQuery('#dataY');
  var $dataHeight = jQuery('#dataHeight');
  var $dataWidth = jQuery('#dataWidth');
  var $dataRotate = jQuery('#dataRotate');
  var $dataScaleX = jQuery('#dataScaleX');
  var $dataScaleY = jQuery('#dataScaleY');
  var options = {
    aspectRatio: 50 / 9,
    preview: '.img-preview',
    crop: function (e) {
      var data = e.detail;
      dataHeight.value = Math.round(data.height);
      dataWidth.value = Math.round(data.width);
    }
  };
  var originalImageURL = $image.attr('src');
  var uploadedImageName = 'cropped.jpg';
  var uploadedImageType = 'image/jpeg';
  var uploadedImageURL;

  // Tooltip
  jQuery('[data-toggle="tooltip"]').tooltip();

  // Cropper
  $image.on({
    ready: function (e) {
      console.log(e.type);
    },
    cropstart: function (e) {
      console.log(e.type, e.detail.action);
    },
    cropmove: function (e) {
      console.log(e.type, e.detail.action);
    },
    cropend: function (e) {
      console.log(e.type, e.detail.action);
    },
    crop: function (e) {
      console.log(e.type);
    },
    zoom: function (e) {
      console.log(e.type, e.detail.ratio);
    }
  }).cropper(options);
  

 
  jQuery('.header-docs-buttons').on('click', '[data-method]', function () {
    var $this = jQuery(this);
    var data = $this.data();
    var cropper = $image.data('cropper');
    var cropped;
    var $target;
    var result1;
    console.log(data)
    console.log($image)
    result1 = $image.cropper(data.method, data.option, data.secondOption);
    console.log(result1);
    
    if ($this.prop('disabled') || $this.hasClass('disabled')) {
      return;
    }

    if (cropper && data.method) {
      data = jQuery.extend({}, data); // Clone a new one

      if (typeof data.target !== 'undefined') {
        $target = jQuery(data.target);

        if (typeof data.option === 'undefined') {
          try {
            data.option = JSON.parse($target.val());
          } catch (e) {
            console.log(e.message);
          }
        }
      }

      cropped = cropper.cropped;

      switch (data.method) {
        case 'rotate':
          if (cropped && options.viewMode > 0) {
            $image.cropper('clear');
          }

          break;

        case 'getCroppedCanvas':
          if (uploadedImageType === 'image/jpeg') {
            if (!data.option) {
              data.option = {};
            }

            data.option.fillColor = '#fff';
          }

          break;
      }
      
      
      
      

      switch (data.method) {
        case 'rotate':
          if (cropped && options.viewMode > 0) {
            $image.cropper('crop');
          }

          break;

        case 'scaleX':
        case 'scaleY':
          jQuery(this).data('option', -data.option);
          break;

        case 'getCroppedCanvas':
          if (result1) {
            // Bootstrap's Modal
            
            var inputImageType = jQuery("#inputImage")[0].files[0];
            var Formiamgedata = new FormData();
            var extension;
            jQuery("body").css({'cursor':'wait'});
            if(inputImageType !="" && inputImageType != undefined){
            
                    var filetypeAc = jQuery("#inputImage")[0].files[0].type;
                    var filetype = filetypeAc.split('/');
                    extension = filetype[1];
                    console.log(filetypeAc);
                    console.log(filetype[1]);
                    Formiamgedata.append('imagetype', filetype[1]);


            }else{
                    var fileURL = jQuery("#headerbgimg").attr('src');
                    extension = fileURL.substr( (fileURL.lastIndexOf('.') +1) );
                    console.log(extension);
                    console.log(filetype);
                    Formiamgedata.append('imagetype', extension);
            }
            
            console.log(getheaderimage);
            
            var getheaderimage = result1.toDataURL(extension);
            Formiamgedata.append('imagedata', getheaderimage);
           
            var url = currentsiteurl+'/';
            var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=uploadbase64image';
            jQuery.ajax({
                    url: urlnew,
                    data: Formiamgedata,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data) {
                        
                        
                        jQuery("body").css({'cursor':'default'});
                        var newURL = jQuery.parseJSON(data);
                        jQuery("#headerimage").val(newURL);
                        jQuery('.previewDivselectedImageheader').empty();
                        var imagehtml = '<img style="width: 100%;" src="'+newURL+'" >';
                        jQuery('.previewDivselectedImageheader').append(imagehtml);
                        
                        
                    }
                        
                        
                        
                    });
              
           
          }

          break;

        case 'destroy':
          if (uploadedImageURL) {
            URL.revokeObjectURL(uploadedImageURL);
            uploadedImageURL = '';
            $image.attr('src', originalImageURL);
          }

          break;
      }

      if (jQuery.isPlainObject(result1) && $target) {
        try {
          $target.val(JSON.stringify(result1));
        } catch (e) {
          console.log(e.message);
        }
      }
    }
  });

  
  // Import image
  var $inputImage = jQuery('#inputImage');

  if (URL) {
    $inputImage.change(function () {
      var files = this.files;
      var file;

      if (!$image.data('cropper')) {
        return;
      }
      jQuery(".headerimagecropper").show();
      if (files && files.length) {
        file = files[0];

        if (/^image\/\w+$/.test(file.type)) {
          uploadedImageName = file.name;
          uploadedImageType = file.type;

          if (uploadedImageURL) {
            URL.revokeObjectURL(uploadedImageURL);
          }

          uploadedImageURL = URL.createObjectURL(file);
          $image.cropper('destroy').attr('src', uploadedImageURL).cropper(options);
          //$inputImage.val('');
        } else {
          window.alert('Please choose an image file.');
        }
      }
    });
  } else {
    $inputImage.prop('disabled', true).parent().addClass('disabled');
  }
});
jQuery(function () {
  'use strict';

  var console = window.console || { log: function () {} };
  var URL = window.URL || window.webkitURL;
  var $imageLogo = jQuery('#headerbgimgLogo');
  var $downloadLogo = jQuery('#downloadLogo');
  var $dataXLogo = jQuery('#dataXLogo');
  var $dataYLogo = jQuery('#dataYLogo');
  var $dataHeightLogo = jQuery('#dataHeightLogo');
  var $dataWidthLogo = jQuery('#dataWidthLogo');
  var $dataRotateLogo = jQuery('#dataRotateLogo');
  var $dataScaleXLogo = jQuery('#dataScaleXLogo');
  var $dataScaleYLogo = jQuery('#dataScaleYLogo');
  var optionsLogo = {
    aspectRatio: 2 / 1,
    preview: '.img-previewLogo',
    crop: function (e) {
      var dataLogo = e.detail;
      console.log(dataLogo)
      dataHeightLogo.value = Math.round(dataLogo.height);
      dataWidthLogo.value = Math.round(dataLogo.width);
    }
  };
  var originalImageURLLogo = $imageLogo.attr('src');
  var uploadedImageNameLogo = 'cropped.jpg';
  var uploadedImageTypeLogo = 'image/jpeg';
  var uploadedImageURLLogo;

  // Tooltip
  jQuery('[data-toggle="tooltip"]').tooltip();

  // Cropper
  $imageLogo.on({
    ready: function (e) {
      console.log(e.type);
    },
    cropstart: function (e) {
      console.log(e.type, e.detail.action);
    },
    cropmove: function (e) {
      console.log(e.type, e.detail.action);
    },
    cropend: function (e) {
      console.log(e.type, e.detail.action);
    },
    crop: function (e) {
      console.log(e.type);
    },
    zoom: function (e) {
      console.log(e.type, e.detail.ratio);
    }
  }).cropper(optionsLogo);
  
  jQuery('.logo-docs-buttons').on('click', '[data-method]', function () {
    var $this = jQuery(this);
    var data = $this.data();
    var cropper = $imageLogo.data('cropper');
    var cropped;
    var $target;
    var result2;
    
    
    console.log(data)
    console.log($imageLogo)
    result2 = $imageLogo.cropper(data.method, data.option, data.secondOption);
    console.log(result2.toDataURL(uploadedImageTypeLogo));
    
    
    if ($this.prop('disabled') || $this.hasClass('disabled')) {
      return;
    }

    if (cropper && data.method) {
      data = jQuery.extend({}, data); // Clone a new one

      if (typeof data.target !== 'undefined') {
        $target = jQuery(data.target);

        if (typeof data.option === 'undefined') {
          try {
            data.option = JSON.parse($target.val());
          } catch (e) {
            console.log(e.message);
          }
        }
      }

      cropped = cropper.cropped;

      switch (data.method) {
        case 'rotate':
          if (cropped && options.viewMode > 0) {
            $imageLogo.cropper('clear');
          }

          break;

        case 'getCroppedCanvas':
          if (uploadedImageTypeLogo === 'image/jpeg') {
            if (!data.option) {
              data.option = {};
            }

            data.option.fillColor = '#fff';
          }

          break;
      }

      result2 = $imageLogo.cropper(data.method, data.option, data.secondOption);

      switch (data.method) {
        case 'rotate':
          if (cropped && options.viewMode > 0) {
            $image.cropper('crop');
          }

          break;

        case 'scaleXLogo':
        case 'scaleYLogo':
          jQuery(this).data('option', -data.option);
          break;

        case 'getCroppedCanvas':
          if (result2) {
            // Bootstrap's Modal
            result2 = $imageLogo.cropper(data.method, data.option, data.secondOption);
            
            
            
            
            var inputImageType = jQuery("#inputImageLogo")[0].files[0];
            var FormImage2data = new FormData();
            var extension;
            jQuery("body").css({'cursor':'wait'});
            if(inputImageType !="" && inputImageType != undefined){
            
                    var filetypeAc = jQuery("#inputImageLogo")[0].files[0].type;
                    var filetype = filetypeAc.split('/');
                    extension = filetype[1];
                    console.log(filetypeAc);
                    console.log(filetype[1]);
                    FormImage2data.append('imagetype', extension);


            }else{
                    var fileURL = jQuery("#headerbgimgLogo").attr('src');
                    var extension = fileURL.substr( (fileURL.lastIndexOf('.') +1) );
                    console.log(extension);
                    console.log(filetype);
                    FormImage2data.append('imagetype', extension);
            }
            
            
            var Image2ULR = result2.toDataURL(extension);
            FormImage2data.append('imagedata', Image2ULR);
           
            var url = currentsiteurl+'/';
            var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=uploadbase64image';
            jQuery.ajax({
                    url: urlnew,
                    data: FormImage2data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data) {
                        
                        
                        jQuery("body").css({'cursor':'default'});
                        var newURL = jQuery.parseJSON(data);
                        jQuery("#headerimageLogo").val(newURL);
                        jQuery('.previewDivselectedImageLogo').empty();
                        var imagehtml = '<img style="width: 100%;" src="'+newURL+'" >';
                         
                        jQuery('.previewDivselectedImageLogo').append(imagehtml);
                      
                        
                    }
                        
                        
                        
                    });
             
           
          }

          break;

        case 'destroyLogo':
          if (uploadedImageURLLogo) {
            URL.revokeObjectURL(uploadedImageURLLogo);
            uploadedImageURLLogo = '';
            $image.attr('src', originalImageURLLogo);
          }

          break;
      }

      if (jQuery.isPlainObject(result2) && $target) {
        try {
          $target.val(JSON.stringify(result2));
        } catch (e) {
          console.log(e.message);
        }
      }
    }
  });

 
  // Import image
  var $inputImageLogo = jQuery('#inputImageLogo');

  if (URL) {
    $inputImageLogo.change(function () {
      var files = this.files;
      var file;

      if (!$imageLogo.data('cropper')) {
        return;
      }
      jQuery(".headerimagecropperLogo").show();
      if (files && files.length) {
        file = files[0];

        if (/^image\/\w+$/.test(file.type)) {
          uploadedImageNameLogo = file.name;
          uploadedImageTypeLogo = file.type;

          if (uploadedImageURLLogo) {
            URL.revokeObjectURL(uploadedImageURLLogo);
          }

          uploadedImageURLLogo = URL.createObjectURL(file);
          $imageLogo.cropper('destroy').attr('src', uploadedImageURLLogo).cropper(optionsLogo);
          //$inputImage.val('');
        } else {
          window.alert('Please choose an image file.');
        }
      }
    });
  } else {
    $inputImageLogo.prop('disabled', true).parent().addClass('disabled');
  }
});

jQuery(function () {
  'use strict';

  var console = window.console || { log: function () {} };
  var URL = window.URL || window.webkitURL;
  var $imageFavicon = jQuery('#headerbgimgFavicon');
  var $downloadFavicon = jQuery('#downloadFavicon');
  var $dataXFavicon = jQuery('#dataXFavicon');
  var $dataYFavicon = jQuery('#dataYFavicon');
  var $dataHeightFavicon = jQuery('#dataHeightFavicon');
  var $dataWidthFavicon = jQuery('#dataWidthFavicon');
  var $dataRotateFavicon = jQuery('#dataRotateFavicon');
  var $dataScaleXFavicon = jQuery('#dataScaleXFavicon');
  var $dataScaleYFavicon = jQuery('#dataScaleYFavicon');
  var optionsFavicon = {
    aspectRatio: 1 / 1,
    preview: '.img-previewFavicon',
    crop: function (e) {
      var dataFavicon = e.detail;
      dataHeightFavicon.value = Math.round(dataFavicon.height);
      dataWidthFavicon.value = Math.round(dataFavicon.width);
    }
  };
  var originalImageURLFavicon = $imageFavicon.attr('src');
  var uploadedImageNameFavicon = 'cropped.jpg';
  var uploadedImageTypeFavicon = 'image/jpeg';
  var uploadedImageURLFavicon;

  // Tooltip
  jQuery('[data-toggle="tooltip"]').tooltip();

  // Cropper
  $imageFavicon.on({
    ready: function (e) {
      console.log(e.type);
    },
    cropstart: function (e) {
      console.log(e.type, e.detail.action);
    },
    cropmove: function (e) {
      console.log(e.type, e.detail.action);
    },
    cropend: function (e) {
      console.log(e.type, e.detail.action);
    },
    crop: function (e) {
      console.log(e.type);
    },
    zoom: function (e) {
      console.log(e.type, e.detail.ratio);
    }
  }).cropper(optionsFavicon);
  
  

  

 
  jQuery('.favicon-docs-buttons').on('click', '[data-method]', function () {
    var $this = jQuery(this);
    var data = $this.data();
    var cropper = $imageFavicon.data('cropper');
    var cropped;
    var $target;
    var result3;

    if ($this.prop('disabled') || $this.hasClass('disabled')) {
      return;
    }

    if (cropper && data.method) {
      data = jQuery.extend({}, data); // Clone a new one

      if (typeof data.target !== 'undefined') {
        $target = jQuery(data.target);

        if (typeof data.option === 'undefined') {
          try {
            data.option = JSON.parse($target.val());
          } catch (e) {
            console.log(e.message);
          }
        }
      }

      cropped = cropper.cropped;

      switch (data.method) {
        case 'rotate':
          if (cropped && optionsFavicon.viewMode > 0) {
            $imageFavicon.cropper('clear');
          }

          break;

        case 'getCroppedCanvas':
          if (uploadedImageTypeFavicon === 'image/jpeg') {
            if (!data.option) {
              data.option = {};
            }

            data.option.fillColor = '#fff';
          }

          break;
      }

      result3 = $imageFavicon.cropper(data.method, data.option, data.secondOption);

      switch (data.method) {
        case 'rotate':
          if (cropped && optionsFavicon.viewMode > 0) {
            $image.cropper('crop');
          }

          break;

        case 'scaleXFavicon':
        case 'scaleYFavicon':
          jQuery(this).data('option', -data.option);
          break;

        case 'getCroppedCanvas':
          if (result3) {
            // Bootstrap's Modal
            var inputImageType = jQuery("#inputImageFavicon")[0].files[0];
            var image1Date = new FormData();
            var extension;
            
            jQuery("body").css({'cursor':'wait'});
            if(inputImageType !="" && inputImageType != undefined){
            
                    var filetypeAc = jQuery("#inputImageFavicon")[0].files[0].type;
                    var filetype = filetypeAc.split('/');
                    extension = filetype[1];
                    console.log(filetypeAc);
                    console.log(filetype[1]);
                    image1Date.append('imagetype', filetype[1]);


            }else{
                    var fileURL = jQuery("#headerbgimgFavicon").attr('src');
                    extension = fileURL.substr( (fileURL.lastIndexOf('.') +1) );
                    console.log(extension);
                    console.log(filetype);
                    image1Date.append('imagetype', extension);
            }
            
            
             var getheaderimage = result3.toDataURL(extension);
            image1Date.append('imagedata', getheaderimage);
           
            var url = currentsiteurl+'/';
            var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=uploadbase64image';
            jQuery.ajax({
                    url: urlnew,
                    data: image1Date,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data) {
                        
                        
                        jQuery("body").css({'cursor':'default'});
                        var newURL = jQuery.parseJSON(data);
                        jQuery("#headerimageFavicon").val(newURL);
                        jQuery('.previewDivselectedImageFavicon').empty();
                        var imagehtml = '<img style="width: 100%;" src="'+newURL+'" >';
                        jQuery('.previewDivselectedImageFavicon').append(imagehtml);
                    }
                        
                        
                        
                    });
              
           
          }

          break;

        case 'destroyFavicon':
          if (uploadedImageURLFavicon) {
            URL.revokeObjectURL(uploadedImageURLFavicon);
            uploadedImageURLFavicon = '';
            $image.attr('src', originalImageURLFavicon);
          }

          break;
      }

      if (jQuery.isPlainObject(result3) && $target) {
        try {
          $target.val(JSON.stringify(result3));
        } catch (e) {
          console.log(e.message);
        }
      }
    }
  });

  
  var $inputImageFavicon = jQuery('#inputImageFavicon');

  if (URL) {
    $inputImageFavicon.change(function () {
      var files = this.files;
      var file;

      if (!$imageFavicon.data('cropper')) {
        return;
      }
      jQuery(".headerimagecropperFavicon").show();
      if (files && files.length) {
        file = files[0];

        if (/^image\/\w+$/.test(file.type)) {
          uploadedImageNameFavicon = file.name;
          uploadedImageTypeFavicon = file.type;

          if (uploadedImageURLFavicon) {
            URL.revokeObjectURL(uploadedImageURLFavicon);
          }

          uploadedImageURLFavicon = URL.createObjectURL(file);
          $imageFavicon.cropper('destroy').attr('src', uploadedImageURLFavicon).cropper(optionsFavicon);
          //$inputImage.val('');
        } else {
          window.alert('Please choose an image file.');
        }
      }
    });
  } else {
    $inputImageFavicon.prop('disabled', true).parent().addClass('disabled');
  }
});
</script>

      
       
   <?php }else{
       $redirect = get_site_url();
    wp_redirect( $redirect );exit;
   
   }
   ?>