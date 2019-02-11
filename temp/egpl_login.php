<?php // Template Name: Login

if ( is_user_logged_in() ) {
        $site_url = get_site_url();
	 wp_redirect( $site_url.'/home/'); exit;
         
} else {

 ?>

<?php get_header(); ?>

<div class="fusion-fullwidth fullwidth-box fusion-fullwidth-3  fusion-parallax-none nonhundred-percent-fullwidth">
	<div class="fusion-row">
		<div class="fusion-two-third fusion-layout-column fusion-spacing-yes">
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
	</div>

			<div class="fusion-one-third fusion-layout-column fusion-column-last fusion-spacing-yes">
					<div class="fusion-column-wrapper">
					<p>  
						<div class="login_page" id="login_temp">
                   			 <!--  <p class="message">    Please log in to continue.<br> </p> --> 
                       	 <?php 
                          if(isset($_GET['login']) && $_GET['login'] == 'failed'){
						?>
						<div id="login-error" style="margin-bottom: 10px; background-color: #FFEBE8;border:1px solid #C00;padding:5px;">
						Incorrect Username and/or Password.
						</div>
						<?php
                        }
                        
                        echo do_shortcode( ' [login_widget title=""]' );?>
						</div> 
                        </p>
						<div class="fusion-clearfix">
                   </div>
               </div>
            </div>
      </div>
</div>

<?php get_footer(); } ?>


