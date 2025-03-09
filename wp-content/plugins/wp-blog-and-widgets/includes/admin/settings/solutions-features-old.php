<?php
/**
 * Plugin Solutions & Features Page
 *
 * @package WP Blog and Widgets
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking some variables
$popup_add_link = add_query_arg( array( 'post_type' =>WPBAW_POST_TYPE ), admin_url( 'post-new.php' ) );
?>

<div id="wrap">
	<div class="wpbaw-sf-wrap">
		<div class="wpbaw-sf-inr">

			<div style="text-align: center; background: #DCDCDC; margin: 30px 0; padding: 10px 30px 30px 30px;">
				<p style="font-weight: bold !important; font-size:20px !important;"><span style="color: #50c621;">Essential Plugin Bundle</span> + Any Leading Builders (Avada / Elementor / Divi / <br>VC-WPBakery / Site Origin / Beaver) = <span style="background: #50c621;color: #fff;padding: 2px 10px;">WordPress Magic</span></p>
				<h4 style="color: #333; font-size: 14px; font-weight: 700;">Over 15K+ Customers Using <span style="color: #50c621 !important;">Essential Plugin Bundle</span></h4>
				<a href="<?php echo esc_url(WPBAW_PLUGIN_BUNDLE_LINK); ?>" target="_blank" class="wpbaw-sf-btn wpbaw-sf-btn-orange"><span class="dashicons dashicons-cart"></span> View Essential Plugin Bundle</a>
			</div>

			<!-- Start - Welcome Box -->
			<div class="wpbaw-sf-welcome-wrap">
				<div class="wpbaw-sf-welcome-inr">
					<div class="wpbaw-sf-welcome-left">
						<div class="wpbaw-sf-subtitle">Getting Started</div>
						<h2 class="wpbaw-sf-title">Welcome to Blog</h2>
						<p class="wpbaw-sf-content">Display customizable blog layouts, vertical scrolling blog widgets in the most engaging and customized way.</p>
						<a href="<?php echo esc_url( $popup_add_link ); ?>" class="wpbaw-sf-btn">Launch Blog and Widget</a></br> <b>OR</b> </br>
						<p style="font-size: 14px;"><span class="wpbaw-sf-blue">Blog </span>Including in <span class="wpbaw-sf-blue">Essential Plugin Bundle</span></p>
						<a href="<?php echo esc_url(WPBAW_PLUGIN_BUNDLE_LINK); ?>" target="_blank" class="wpbaw-sf-btn wpbaw-sf-btn-orange"><span class="dashicons dashicons-cart"></span> View Bundle Deal</a>
						<div class="wpbaw-rc-wrap">
							<div class="wpbaw-rc-inr wpbaw-rc-bg-box">
								<div class="wpbaw-rc-icon">
									<img src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/popup-icon/14-days-money-back-guarantee.png" alt="14-days-money-back-guarantee" title="14-days-money-back-guarantee" />
								</div>
								<div class="wpbaw-rc-cont">
									<h3>14 Days Refund Policy. 0 risk to you.</h3>
									<p>14-day No Question Asked Refund Guarantee</p>
								</div>
							</div>
							<div class="wpbaw-rc-inr wpbaw-rc-bg-box">
								<div class="wpbaw-rc-icon">
									<img src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/popup-icon/popup-design.png" alt="popup-design" title="popup-design" />
								</div>
								<div class="wpbaw-rc-cont">
									<h3>Include Done-For-You Blog Setup</h3>
									<p>Our  experts team will design 1 free Blog for you as per your need.</p>
								</div>
							</div>
						</div>
					</div>
					<div class="wpbaw-sf-welcome-right">
						<div class="wpbaw-sf-fp-ttl">Free vs Pro</div>
						<div class="wpbaw-sf-fp-box-wrp">
							<div class="wpbaw-sf-fp-box">
								<i class="dashicons dashicons-slides"></i>
								<div class="wpbaw-sf-box-ttl">1 Designs for Blog</div>
								<div class="wpbaw-sf-tag">Free</div>
							</div>
							<div class="wpbaw-sf-fp-box">
								<i class="dashicons dashicons-slides"></i>
								<div class="wpbaw-sf-box-ttl">1 Designs for Recent Blog</div>
								<div class="wpbaw-sf-tag">Free</div>
							</div>
							<div class="wpbaw-sf-fp-box">
								<i class="dashicons dashicons-slides"></i>
								<div class="wpbaw-sf-box-ttl">1 Designs for Blog List</div>
								<div class="wpbaw-sf-tag">Free</div>
							</div>
							<div class="wpbaw-sf-fp-box">
								<i class="dashicons dashicons-category"></i>
								<div class="wpbaw-sf-box-ttl">Display Slides for Particular Categories</div>
								<div class="wpbaw-sf-tag">Free</div>
							</div>
							<div class="wpbaw-sf-fp-box">
								<i class="dashicons dashicons-block-default"></i>
								<div class="wpbaw-sf-box-ttl">Gutenbreg Block Support</div>
								<div class="wpbaw-sf-tag">Free</div>
							</div>
							<div class="wpbaw-sf-fp-box">
								<i class="dashicons dashicons-tagcloud"></i>
								<div class="wpbaw-sf-box-ttl">1 Design for Blog Widget</div>
								<div class="wpbaw-sf-tag">Free</div>
							</div>
							<div class="wpbaw-sf-fp-box wpbaw-sf-pro-box">
								<i class="dashicons dashicons-slides"></i>
								<div class="wpbaw-sf-box-ttl">8 – (Post Grid, Recent Post, List, Slider, Carousel, GridBox, GridBox Slider, Masonry)</div>
								<div class="wpbaw-sf-tag">Pro</div>
							</div>
							<div class="wpbaw-sf-fp-box wpbaw-sf-pro-box">
								<i class="dashicons dashicons-art"></i>
								<div class="wpbaw-sf-box-ttl">120+ Designs</div>
								<div class="wpbaw-sf-tag">Pro</div>
							</div>
							<div class="wpbaw-sf-fp-box wpbaw-sf-pro-box">
								<i class="dashicons dashicons-layout"></i>
								<div class="wpbaw-sf-box-ttl">Elementor, Beaver, SiteOrigin, and VC Page Builder Support</div>
								<div class="wpbaw-sf-tag">Pro</div>
							</div>
							<div class="wpbaw-sf-fp-box wpbaw-sf-pro-box">
								<i class="dashicons dashicons-html"></i>
								<div class="wpbaw-sf-box-ttl">WP Templating Features </div>
								<div class="wpbaw-sf-tag">Pro</div>
							</div>
							<div class="wpbaw-sf-fp-box wpbaw-sf-pro-box">
								<i class="dashicons dashicons-format-image"></i>
								<div class="wpbaw-sf-box-ttl">Image Lazyload for Slider</div>
								<div class="wpbaw-sf-tag">Pro</div>
							</div>
							<div class="wpbaw-sf-fp-box wpbaw-sf-pro-box">
								<i class="dashicons dashicons-editor-rtl"></i>
								<div class="wpbaw-sf-box-ttl">Slider RTL Support</div>
								<div class="wpbaw-sf-tag">Pro</div>
							</div>
							<div class="wpbaw-sf-fp-box wpbaw-sf-pro-box">
								<i class="dashicons dashicons-move"></i>
								<div class="wpbaw-sf-box-ttl">Drag & Drop Slide Order Change</div>
								<div class="wpbaw-sf-tag">Pro</div>
							</div>
							<div class="wpbaw-sf-fp-box wpbaw-sf-pro-box">
								<i class="dashicons dashicons-admin-links"></i>
								<div class="wpbaw-sf-box-ttl">Custom Read More link for Post</div>
								<div class="wpbaw-sf-tag">Pro</div>
							</div>
							<div class="wpbaw-sf-fp-box wpbaw-sf-pro-box">
								<i class="dashicons dashicons-shortcode"></i>
								<div class="wpbaw-sf-box-ttl">Shortcode Generator</div>
								<div class="wpbaw-sf-tag">Pro</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End - Welcome Box -->

			<!-- Start - Blog Designer - Post and Widget/Carousel - Features -->
			<div class="wpbaw-features-section">
				<div class="wpbaw-center wpbaw-features-ttl">
					<h2 class="wpbaw-sf-ttl">Powerful Pro Features, Simplified</h2>
				</div>
				<div class="wpbaw-features-section-inr">
					<div class="wpbaw-features-box-wrap">
						<ul class="wpbaw-features-box-grid">
							<li>
							<div class="wpbaw-popup-icon"><img src="<?php echo esc_url(WPBAW_URL); ?>assets/images/popup-icon/blog-grid.png" /></div>
							Blog Grid View</li>
							<li>
							<div class="wpbaw-popup-icon"><img src="<?php echo esc_url(WPBAW_URL); ?>assets/images/popup-icon/blog-grid.png" /></div>
							Recent Blog Grid View</li>
							<li>
							<div class="wpbaw-popup-icon"><img src="<?php echo esc_url(WPBAW_URL); ?>assets/images/popup-icon/slider.png" /></div>
							Blog Slider View</li>
							<li>
							<div class="wpbaw-popup-icon"><img src="<?php echo esc_url(WPBAW_URL); ?>assets/images/popup-icon/blog-list-view.png" /></div>
							Blog List View</li>
							<li>
							<div class="wpbaw-popup-icon"><img src="<?php echo esc_url(WPBAW_URL); ?>assets/images/popup-icon/centermode.png" /></div>
							Blog Centermode View</li>
							<li>
							<div class="wpbaw-popup-icon"><img src="<?php echo esc_url(WPBAW_URL); ?>assets/images/popup-icon/grid-box.png" /></div>
							Blog Gridbox View</li>
							<li>
							<div class="wpbaw-popup-icon"><img src="<?php echo esc_url(WPBAW_URL); ?>assets/images/popup-icon/grid-box.png" /></div>
							Blog Gridbox Slider View</li>
							<li>
							<div class="wpbaw-popup-icon"><img src="<?php echo esc_url(WPBAW_URL); ?>assets/images/popup-icon/blog-masonry.png" /></div>
							Blog Masonry View</li>
						</ul>
					</div>
					<p style="font-size: 14px;"><span class="wpbaw-sf-blue">Blog </span>Including in <span class="wpbaw-sf-blue">Essential Plugin Bundle</span></p>
					<a href="<?php echo esc_url(WPBAW_PLUGIN_BUNDLE_LINK); ?>" target="_blank" class="wpbaw-sf-btn wpbaw-sf-btn-orange"><span class="dashicons dashicons-cart"></span> View Bundle Deal</a>
					<div class="wpbaw-rc-wrap">
						<div class="wpbaw-rc-inr wpbaw-rc-bg-box">
							<div class="wpbaw-rc-icon">
								<img src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/popup-icon/14-days-money-back-guarantee.png" alt="14-days-money-back-guarantee" title="14-days-money-back-guarantee" />
							</div>
							<div class="wpbaw-rc-cont">
								<h3>14 Days Refund Policy. 0 risk to you.</h3>
								<p>14-day No Question Asked Refund Guarantee</p>
							</div>
						</div>
						<div class="wpbaw-rc-inr wpbaw-rc-bg-box">
							<div class="wpbaw-rc-icon">
								<img src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/popup-icon/popup-design.png" alt="popup-design" title="popup-design" />
							</div>
							<div class="wpbaw-rc-cont">
								<h3>Include Done-For-You Blog Setup</h3>
								<p>Our  experts team will design 1 free Blog for you as per your need.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End - Logo Showcase - Features -->

			<!-- Start - Testimonial Section -->
			<div class="wpbaw-sf-testimonial-wrap">
				<div class="wpbaw-center wpbaw-features-ttl">
					<h2 class="wpbaw-sf-ttl">Looking for a Reason to Use Essential Plugin with Blog? Here are 15+...</h2>
				</div>
				<div class="wpbaw-testimonial-section-inr">
					<div class="wpbaw-testimonial-box-wrap">
						<div class="wpbaw-testimonial-box-grid">
							<h3 class="wpbaw-testimonial-title">great plugin</h3>
							<div class="wpbaw-testimonial-desc">i like the plugin. Layout is not that simple …</div>
							<div class="wpbaw-testimonial-clnt">@marcanit</div>
							<div class="wpbaw-testimonial-rating"><img src="<?php echo esc_url(WPBAW_URL); ?>assets/images/rating.png" /></div>
						</div>
						<div class="wpbaw-testimonial-box-grid">
							<h3 class="wpbaw-testimonial-title">Great plugin and support</h3>
							<div class="wpbaw-testimonial-desc">I love this pluggin, very easy to use and the support is fast and awesome !! </div>
							<div class="wpbaw-testimonial-clnt">@tuturgioc</div>
							<div class="wpbaw-testimonial-rating"><img src="<?php echo esc_url(WPBAW_URL); ?>assets/images/rating.png" /></div>
						</div>
						<div class="wpbaw-testimonial-box-grid">
							<h3 class="wpbaw-testimonial-title">Works as described</h3>
							<div class="wpbaw-testimonial-desc">I just installed and used with couple of test posts and it seems working perfect. No problem at all. Many thanks!</div>
							<div class="wpbaw-testimonial-clnt">@cengoguven</div>
							<div class="wpbaw-testimonial-rating"><img src="<?php echo esc_url(WPBAW_URL); ?>assets/images/rating.png" /></div>
						</div>
						<div class="wpbaw-testimonial-box-grid">
							<h3 class="wpbaw-testimonial-title">Quick Awesome Support</h3>
							<div class="wpbaw-testimonial-desc">Quick support and really friendly, Explained all step by step which i could understand clearly.Really patient team members. Issue wasn’t in the plugin still explained me what had happened and how to sort it out. thank you and all the best Guys!</div>
							<div class="wpbaw-testimonial-clnt">@prasad85</div>
							<div class="wpbaw-testimonial-rating"><img src="<?php echo esc_url(WPBAW_URL); ?>assets/images/rating.png" /></div>
						</div>
						<div class="wpbaw-testimonial-box-grid">
							<h3 class="wpbaw-testimonial-title">Top</h3>
							<div class="wpbaw-testimonial-desc">Extremely patient person, who takes the time to explain everything. Besides, the pluggins are great, go for it.</div>
							<div class="wpbaw-testimonial-clnt">@chotty</div>
							<div class="wpbaw-testimonial-rating"><img src="<?php echo esc_url(WPBAW_URL); ?>assets/images/rating.png" /></div>
						</div>
						<div class="wpbaw-testimonial-box-grid">
							<h3 class="wpbaw-testimonial-title">Relaible support team</h3>
							<div class="wpbaw-testimonial-desc">Amazing support that is quick and helpful. Very patient in resolving the issues of users. Appreciate your help. Thank you.</div>
							<div class="wpbaw-testimonial-clnt">@talesandmiles</div>
							<div class="wpbaw-testimonial-rating"><img src="<?php echo esc_url(WPBAW_URL); ?>assets/images/rating.png" /></div>
						</div>
					</div>
					<a href="https://wordpress.org/support/plugin/wp-blog-and-widgets/reviews/?filter=5" target="_blank" class="wpbaw-sf-btn"><span class="dashicons dashicons-star-filled"></span> View All Reviews</a><br /> OR 
					<p style="font-size: 14px;"><span class="wpbaw-sf-blue">Blog </span>Including in <span class="wpbaw-sf-blue">Essential Plugin Bundle</span></p>
					<a href="<?php echo esc_url(WPBAW_PLUGIN_BUNDLE_LINK); ?>"  target="_blank" class="wpbaw-sf-btn wpbaw-sf-btn-orange"><span class="dashicons dashicons-cart"></span> View Bundle Deal</a>
					<div class="wpbaw-rc-wrap">
						<div class="wpbaw-rc-inr wpbaw-rc-bg-box">
							<div class="wpbaw-rc-icon">
								<img src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/popup-icon/14-days-money-back-guarantee.png" alt="14-days-money-back-guarantee" title="14-days-money-back-guarantee" />
							</div>
							<div class="wpbaw-rc-cont">
								<h3>14 Days Refund Policy. 0 risk to you.</h3>
								<p>14-day No Question Asked Refund Guarantee</p>
							</div>
						</div>
						<div class="wpbaw-rc-inr wpbaw-rc-bg-box">
							<div class="wpbaw-rc-icon">
								<img src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/popup-icon/popup-design.png" alt="popup-design" title="popup-design" />
							</div>
							<div class="wpbaw-rc-cont">
								<h3>Include Done-For-You Blog Setup</h3>
								<p>Our  experts team will design 1 free Blog for you as per your need.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End - Testimonial Section -->
		</div>
	</div><!-- end .wpbaw-sf-wrap -->
</div><!-- end .wrap -->