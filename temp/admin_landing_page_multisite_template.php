<?php
// Silence is golden.
 get_header();
 $blog_list = get_blog_list( 0, 'all' );
 $current_user = wp_get_current_user();
 $roles = $current_user->roles;
 
 $user_id = get_current_user_id();
 $user_blogs = get_blogs_of_user( $user_id );
 

?>

<div id="content" class="full-width">
        <div class="page-content" style="max-width: 800px;margin-left: auto;margin-right: auto;">
        
            <h2 style="text-align: center;"></h2>
            
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
            
            
          
            <div class="box-typical box-typical-padding">
                
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td><strong>Event Name</strong></td>
                            <td><strong>URL</strong></td>
                            
                        </tr>
                        
                        <?php foreach ($user_blogs as $blog_id) { 
                            
                            $sitename = $blog_id->blogname;
                            if($blog_id->userblog_id != 1){
                            if($roles[0] == 'contentmanager' || $roles[0] == 'administrator' ){
                                
                                 echo '<tr><td>'.$sitename.'</td><td><a target="_blank" href="'.$blog_id->siteurl.'/" class="btn btn-info" >Visit Site</a><a href="'.$blog_id->siteurl.'/dashboard" style="margin-left: 9%;"  target="_blank" class="btn btn-info" >Admin Dashboard</a></td></tr>';
                                
                            }else{
                               
                                 echo '<tr><td>'.$sitename.'</td><td><a  target="_blank" href="'.$blog_id->siteurl.'/" class="btn btn-info" >Visit Site</a></td></tr>';
                                 
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
<?php   get_footer(); ?>