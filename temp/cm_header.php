<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Admin Dashboard</title>

	<link href="/wp-content/plugins/EGPL/cmtemplate/img/favicon.144x144.png" rel="apple-touch-icon" type="image/png" sizes="144x144">
	<link href="/wp-content/plugins/EGPL/cmtemplate/img/favicon.114x114.png" rel="apple-touch-icon" type="image/png" sizes="114x114">
	<link href="/wp-content/plugins/EGPL/cmtemplate/img/favicon.72x72.png" rel="apple-touch-icon" type="image/png" sizes="72x72">
	<link href="/wp-content/plugins/EGPL/cmtemplate/img/favicon.57x57.png" rel="apple-touch-icon" type="image/png">
	<link href="/wp-content/plugins/EGPL/cmtemplate/img/favicon.png" rel="icon" type="image/png">
	<link href="/wp-content/plugins/EGPL/cmtemplate/img/favicon.ico" rel="shortcut icon">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
          <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/contentmanager.css?v=2.1">
	<link rel="stylesheet" href="/wp-content/plugins/EGPL/cmtemplate/css/lib/lobipanel/lobipanel.min.css?v=2.1">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/cmtemplate/css/lib/jqueryui/jquery-ui.min.css?v=2.1">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/cmtemplate/css/lib/font-awesome/font-awesome.min.css?v=2.1">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/cmtemplate/css/main.css?v=2.1">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/cmtemplate/css/customstyle.css?v=2.1">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/cmtemplate/css/lib/bootstrap-sweetalert/sweetalert.css?v=2.1"/>
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/cmtemplate/css/lib/clockpicker/bootstrap-clockpicker.min.css?v=2.1">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/cmtemplate/css/lib/lobipanel/lobipanel.min.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<!--    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/bootstrap.min.css">-->
     
<!--    contetnmanager css-->
    
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/watable.css?v=2.1">
     <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/datepicker.css?v=2.1">
     <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/bootstrap-multiselect.css?v=2.1">
     <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/jquery-confirm.css?v=2.1">
       <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/dataTables.tableTools.css?v=2.1">
       <link rel="stylesheet" type="text/css" href="/wp-content/plugins/EGPL/css/jquery.dataTables.css?v=2.1">
       <link rel="stylesheet" type="text/css" href="/wp-content/plugins/EGPL/css/buttons.dataTables.min.css?v=2.1">
        <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/admin-component.css?v=2.1">
     
   
       
       
 
    
</head>

<?php $oldvalues = get_option( 'ContenteManager_Settings' );

$logo_imag = $oldvalues['ContentManager']['adminsitelogo'];


?>

<body class="with-side-menu theme-picton-blue">
     

	<header class="site-header">
	    <div class="container-fluid">
           <?php if(!empty($logo_imag)){?>
	        <a   class="site-logo" style="cursor: default;">
	            <img class="hidden-md-down" src="<?php echo $logo_imag;?>" alt="">
	            <img class="hidden-lg-up"   src="<?php echo $logo_imag;?>" alt="">
	        </a>
           <?php }?>
	        <button class="hamburger hamburger--htla">
	            <span>toggle menu</span>
	        </button>
	        <div class="site-header-content">
	            <div class="site-header-content-in" style="margin-top: -10px;">
	               <!-- <div class="site-header-shown">
	                  
                            <section class="widget widget-simple-sm statistic-box yellow __web-inspector-hide-shortcut__" style="margin-top: -13px !important;">
                                <div class="widget-simple-sm-statistic" style="height: 72px;">
                                    <div class="number" id="eventdays" style="padding: 0px !important;font-size: 36px;"></div>
                                    <div class="caption color-blue" style="color: #ffffff !important;font-size: 9px;"> Days to event</div>
                                </div>

                            </section>
                        </div>
                        <div class="site-header-shown" style="    margin-right: 13px !important;">
	                  
                               <section class="widget widget-simple-sm statistic-box purple __web-inspector-hide-shortcut__" style="margin-top: -13px !important;">
                                <div class="widget-simple-sm-statistic" style="height: 72px;">
                                    <div class="number" id="activeuser" style="padding: 0px !important;font-size: 36px;"></div>
                                    <div class="caption color-blue" style="color: #ffffff  !important;font-size: 9px;"> Active Users</div>
                                </div>

                            </section>
	                </div>
	            -->
	
	                <div class="mobile-menu-right-overlay"></div>
	                <div class="site-header-collapsed">
	                    <div class="site-header-collapsed-in">
                               
                                <div class="dropdown" style="margin-top: 10px;">
                                    <button class="btn btn-rounded dropdown-toggle" id="dd-header-add" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Admin
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dd-header-add">
                                        <a class="dropdown-item" href="/add-content-manager-user/">
                                            <i class="font-icon fa fa-user-md"></i>
                                            <span class="lbl">Add New Admin</span>
                                        </a>
                                        <a class="dropdown-item" href="/admin-settings/">
                                            <i class="font-icon fa fa-gears"></i>
                                            <span class="lbl">Settings</span>
                                        </a>
                                        <a class="dropdown-item" href="/change-password/">
                                            <i class="font-icon fa fa-lock"></i>
                                            <span class="lbl">Change Password</span>
                                        </a>
                                        <a class="dropdown-item" href="/logout/">
                                            <i class="font-icon fa fa-sign-out"></i>
                                            <span class="lbl">LogOut</span>
                                        </a>
                                        
                                    </div>
                                </div>
                                
	                        <div class="help-dropdown">
	                           <h2 style="color:#000;margin-top: 5px;" id="sitename"></h2>
	                           
	                        </div><!--.help-dropdown-->
	                     
	                    </div><!--.site-header-collapsed-in-->
	                </div><!--.site-header-collapsed-->
	            </div><!--site-header-content-in-->
	        </div><!--.site-header-content-->
	    </div><!--.container-fluid-->
	</header><!--.site-header-->
