<?php 

global $current_user;
get_currentuserinfo();
?>

<div class="mobile-menu-left-overlay"></div>
	<nav class="side-menu">
	<div class="side-menu-avatar" style="background: #fff;">
	        <div class="avatar-preview avatar-preview-100">
                    Welcome,
                    <p><strong><?php echo $current_user->user_firstname.' '.$current_user->user_lastname;?></strong></p>
                    
	        </div>
            
	    </div>
 <hr style="margin: 0px;">           
<ul class="side-menu-list mynav" style="margin-top:7px;">
                
                
            <li class="mythemestyle">
	            <a href="/dashboard/">
	               
                        <i class="font-icon fa fa-dashboard"></i>
	                <span class="lbl">Dashboard</span>
	            </a>
	    </li>
 </ul>
	  <section>
	        <header class="side-menu-title">Users</header>
	        <ul class="side-menu-list mynav">
	            <li class="mythemestyle">
                             <a href="/user-report/">
                               <span class="glyphicon glyphicon-th"></span>
	                       <span class="lbl">User Report</span>
                            </a>
                            
                        </li>
	            <li class="mythemestyle">
                           <a href="/create-user/">
                               <i class="font-icon fa fa-user-plus"></i>
	                    <span class="lbl">New User</span>
                            </a>
                            
                        </li>
                     
                        <li class="mythemestyle">
                            <a href="/bulk-download-files/">
	                   <i class="font-icon fa fa-download"></i>
	                    <span class="lbl">Bulk Download</span>
	                </a>
                            
                        </li>
                         <li class="mythemestyle">
                            <a href="/bulk-import-user/">
	                   <i class="font-icon fa fa-upload"></i>
	                    <span class="lbl">Bulk Import Users</span>
	                </a>
                            
                        </li>
                         <li class="mythemestyle">
	                <a href="/add-new-level/">
	                  <i class="font-icon fa fa-bars"></i>
	                    <span class="lbl">Manage Levels</span>
	                </a>
	            </li>
	            
	        </ul>
	    </section>
	    <section>
	        <header class="side-menu-title">Content</header>
	        <ul class="side-menu-list mynav">
	            <li class="mythemestyle"> 
	                <a href="/welcome-email/">
	                   <i class="font-icon fa fa-envelope"></i>
	                    <span class="lbl">Welcome Email</span>
	                </a>
	            </li>
	            <li class="mythemestyle">
	                <a href="/content-editor/">
	                   <i class="font-icon fa fa-pencil"></i>
	                    <span class="lbl">Content Editor</span>
	                </a>
	            </li>
	            
	        </ul>
	    </section>
              <section>
	        <header class="side-menu-title">Tasks</header>
	        <ul class="side-menu-list mynav">
	            <li class="mythemestyle"> 
	                <a href="/create-task/">
	                   <i class="font-icon fa fa-tasks"></i>
	                    <span class="lbl">Create New Task</span>
	                </a>
	            </li>
	            <li class="mythemestyle">
	                <a href="/edit-task/">
	                   <i class="font-icon fa fa-pencil"></i>
	                    <span class="lbl">Edit Tasks</span>
	                </a>
	            </li>
	            
	        </ul>
	    </section>
             <section>
	        <header class="side-menu-title">Resources</header>
	        <ul class="side-menu-list mynav">
                     <li class="mythemestyle">
	                <a href="/create-resource/">
	                   <i class="font-icon fa fa-upload"></i>
	                    <span class="lbl">Create Resource</span>
	                </a>
	            </li>
	            <li class="mythemestyle"> 
	                <a href="/all-resources/">
	                   <i class="font-icon fa fa-files-o"></i>
	                    <span class="lbl">All Resources</span>
	                </a>
	            </li>
	           
	            
	        </ul>
	    </section>
	   
	  
	</nav><!--.side-menu-->
