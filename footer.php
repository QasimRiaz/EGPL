	<?php $siteurl = get_site_url();
        
        $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $page1 = strpos($actual_link, "product-category/add-ons");
        $page2 = strpos($actual_link, "/checkout");
        $page3 = strpos($actual_link, "/cart");
        $page4 = strpos($actual_link, "/product/");
        ?>		
			
			<!--begin::Footer-->
                              <?php if(($page4 == true || $page1 == true || $page2 == true || $page3 == true) && !is_user_logged_in()){}else{?>
					<div class="footer bg-white py-4 d-flex flex-lg-column" id="kt_footer">
						<!--begin::Container-->
						<div class="container d-flex flex-column flex-md-row align-items-center justify-content-between">
							<!--begin::Copyright-->
							<div class="text-dark order-2 order-md-1">
							
							<div class="row">
							<div class="pt-3">
								<span class="text-secondary font-weight-bold mr-2">Powered by:</span></div>
								<div class="pb-5">
								<a href="http://expo-genie.com/" target="_blank" class="text-dark-75 text-hover-primary"><img src="<?php echo $siteurl;?>/wp-content/themes/twentytwentyone-child/ExpoGenieLogo.png" /></a>
								</div>
								
								</div>
							</div>
							<!--end::Copyright-->
							<!--begin::Nav-->
							<div class="nav text-secondary font-weight-bold nav-dark order-1 order-md-2"><p>
			Like this ERC? Click<a href="http://expo-genie.com/contact/" target="_blank"> here </a> to use it for your own event!</p>
							</div>
							<!--end::Nav-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Footer-->
                        <?php }?>
					
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::Main-->
		
		
		
		<!--begin::Scrolltop-->
		<?php wp_footer(); ?>
		<div id="kt_scrolltop" class="scrolltop">
			<span class="svg-icon">
				<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Up-2.svg-->
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<polygon points="0 0 24 0 24 24 0 24" />
						<rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
						<path d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z" fill="#000000" fill-rule="nonzero" />
					</g>
				</svg>
				<!--end::Svg Icon-->
			</span>
		</div>
		
                <script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
		<!--begin::Global Config(global config for global JS scripts)-->
		<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#6993FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "primary": "#E1E9FF", "secondary": "#ECF0F3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#212121", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Lato" };</script>
		
                
                <script src="/wp-content/themes/twentytwentyone-child/plugins/global/plugins.bundle.js"></script>
                <script src="/wp-content/themes/twentytwentyone-child/plugins/custom/prismjs/prismjs.bundle.js"></script>
		<script src="/wp-content/themes/twentytwentyone-child/js/scripts.bundle.js"></script>
                <script src="/wp-content/themes/twentytwentyone-child/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
                <script src="/wp-content/themes/twentytwentyone-child/js/pages/widgets.js"></script>
                
                
                <script src="/wp-content/themes/twentytwentyone-child/plugins/custom/leaflet/leaflet.bundle.js"></script>
                <script src="/wp-content/themes/twentytwentyone-child/js/egpltheme.js?v=5.14"></script>
                <script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/bootstrap-sweetalert/sweetalert.min.js"></script>
                
                
             
	</body>
	<!--end::Body-->
</html>