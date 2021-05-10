<?php
// Silence is golden.
 get_header();
 $blog_list = get_blog_list( 0, 'all' );
 $current_user = wp_get_current_user();
 $roles = $current_user->roles;
 $site_url  = get_site_url();
 $user_id = get_current_user_id();
 $user_blogs = get_blogs_of_user( $user_id );
 $virtualpluginstatus = get_option('Activated_VirtualEGPL');

?>
<div class="fusion-row">
                    <div class="fusion-fullwidth fullwidth-box fusion-fullwidth-3  fusion-parallax-none nonhundred-percent-fullwidth">
			<div class="fusion-column-wrapper">
				<p>
					<?php 
					if (!(have_posts())) { ?>
					<?php __("There are no posts", "Avada"); ?><?php } ?>   
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
               		<?php the_content(); ?>
           	 	    <?php endwhile; ?> 
        	        <?php endif; ?>
			   </p>

			<div class="fusion-clearfix">
			</div>
		</div>
                </div></div>
 <?php  if ( is_user_logged_in() ) {     ?> 
<div id="content" class="full-width">
        <div class="page-content" style="max-width: 800px;margin-left: auto;margin-right: auto;">
        
           
            
            
            
          
            <div class="box-typical box-typical-padding">
                
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td><strong>Event</strong></td>
                            <td><strong></strong></td>
                            
                        </tr>
                        
                        <?php foreach ($user_blogs as $blog_id) { 
                            
                            $sitename = $blog_id->blogname;
                            if($blog_id->userblog_id != 1){
                                
                               
                            if( $virtualpluginstatus == 'VirtualEGPL/virtualegpl.php'  && is_page( 'live' )) {
                                
                               if($roles[0] == 'contentmanager' || $roles[0] == 'administrator' ){

                                    echo '<tr><td>'.$sitename.'</td><td style="width:40%;"><a target="_blank" href="'.$blog_id->siteurl.'/" class="btn btn-info eg-buttons" >Exhibitor  Portal</a><a target="_blank" href="'.$site_url.'/live" class="btn btn-info eg-buttons" >Virtual Event</a><a href="'.$blog_id->siteurl.'/dashboard" style="margin-left: 9%;"  target="_blank" class="btn btn-info eg-buttons" >Admin Dashboard</a></td></tr>';

                               }else{
                                    if(current_user_can('attendee')){
                                          echo '<tr><td>'.$sitename.'</td><td style="width:40%;"><a target="_blank" href="'.$site_url.'/live" class="btn btn-info eg-buttons" >Virtual Event</a></td></tr>';

                                    }else{
                                         echo '<tr><td>'.$sitename.'</td><td style="width:40%;"><a  target="_blank" href="'.$blog_id->siteurl.'/" class="btn btn-info eg-buttons" >Exhibitor  Portal</a><a target="_blank" href="'.$site_url.'/live" class="btn btn-info eg-buttons" >Virtual Event</a></td></tr>';
 
                                    }
                                  
                               }
                                
                                
                                
                            }else{
                                
                               if($roles[0] == 'contentmanager' || $roles[0] == 'administrator' ){

                                    echo '<tr><td>'.$sitename.'</td><td style="width:40%;"><a target="_blank" href="'.$blog_id->siteurl.'/" class="btn btn-info eg-buttons" >Exhibitor  Portal</a><a href="'.$blog_id->siteurl.'/dashboard" style="margin-left: 9%;"  target="_blank" class="btn btn-info eg-buttons" >Admin Dashboard</a></td></tr>';

                               }else{

                                    echo '<tr><td>'.$sitename.'</td><td style="width:40%;"><a  target="_blank" href="'.$blog_id->siteurl.'/" class="btn btn-info eg-buttons" >Exhibitor  Portal</a></td></tr>';

                               } 
                                
                            }
                            
                            }
                           
                        }
                        ?>
                    </tbody>
                </table>

                

              
            </div>
        </div>
    </div>
</div>
 <?php   } get_footer(); ?>