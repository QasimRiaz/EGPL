<?php // Template Name: Landing 

if ( is_user_logged_in() ) {
        
   
    
	 wp_redirect( get_site_url().'/home/'); exit;
         
} else {

 ?>

<?php get_header(); ?>

<div class="fusion-fullwidth fullwidth-box fusion-fullwidth-3  fusion-parallax-none nonhundred-percent-fullwidth">
	<div class="fusion-row">
		
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
</div>

<?php get_footer(); } ?>


