<?php
/**
 * Pro Designs and Plugins Feed
 *
 * @package WP Blog and Widgets
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="wrap wpbawh-wrap">
	<style type="text/css">
		.wpos-box{box-shadow: 0 5px 30px 0 rgba(214,215,216,.57);background: #fff; padding-bottom:10px; position:relative;}
		.wpos-box ul{padding: 15px;}
		.wpos-box h5{background:#555; color:#fff; padding:15px; text-align:center;}
		.wpos-box h4{ padding:0 15px; margin:5px 0; font-size:18px;}
		.wpos-box .button{margin:0px 15px 15px 15px; text-align:center; padding:7px 15px; font-size:15px;display:inline-block;}
		.wpos-box .wpos-list{list-style:square; margin:10px 0 0 20px;}
		.wpos-clearfix:before, .wpos-clearfix:after{content: "";display: table;}
		.wpos-clearfix::after{clear: both;}
		.wpos-clearfix{clear: both;}
		.wpos-col{width: 47%; float: left; margin-right:10px; margin-bottom:10px;}
		.wpos-pro-box .hndle{background-color:#0073AA; color:#fff;}
		.wpos-pro-box.postbox{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
		.postbox-container .wpos-list li:before{font-family: dashicons; content: "\f139"; font-size:20px; color: #0073aa; vertical-align: middle;}
		.wpbawh-wrap .wpos-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.wpbawh-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
		.upgrade-to-pro{font-size:18px; text-align:center; margin-bottom:15px;}
		.wpos-copy-clipboard{-webkit-touch-callout: all; -webkit-user-select: all; -khtml-user-select: all; -moz-user-select: all; -ms-user-select: all; user-select: all;}
		.wpos-new-feature{ font-size: 10px; color: #fff; font-weight: bold; background-color: #03aa29; padding:1px 4px; font-style: normal; }
		.button-orange{background: #ff5d52 !important;border-color: #ff5d52 !important; font-weight: 600;}
		.button-blue{background: #0055fb !important;border-color: #0055fb !important; font-weight: 600;}
	</style>

	<h2><?php esc_html_e( 'How It Works', 'wp-blog-and-widgets' ); ?></h2>
	
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">

			<!--How it workd HTML -->
			<div id="post-body-content">

				<div class="meta-box-sortables">
					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle">
								<span><?php esc_html_e( 'How It Works - Display and Shortcode', 'wp-blog-and-widgets' ); ?></span>
							</h2>
						</div>
						<div class="inside">
							<table class="form-table">
								<tbody>
									<tr>
										<th>
											<label><?php esc_html_e( 'Getting Started', 'wp-blog-and-widgets' ); ?>:</label>
										</th>
										<td>
											<ul>
												<li><?php esc_html_e('Step-1: This plugin create a BLOG menu tab in WordPress menu with custom post type.".', 'wp-blog-and-widgets'); ?></li>
												<li><?php esc_html_e('Step-2: Go to "Blog --> Add blog tab".', 'wp-blog-and-widgets'); ?></li>
												<li><?php esc_html_e('Step-3: Add blog title, description, category, and images as featured image.', 'wp-blog-and-widgets'); ?></li>
												<li><?php esc_html_e('Step-4:', 'wp-blog-and-widgets'); ?> <b>NOTE:- </b><?php esc_html_e('If you want to create a blog page with WordPress existing POST section then try our other plugin --', 'wp-blog-and-widgets'); ?> <a href="https://www.essentialplugin.com/wordpress-plugin/blog-designer-post-and-widget/" target="_blank">Blog Designer – Post and Widget</a></li>
											</ul>
										</td>
									</tr>

									<tr>
										<th>
											<label><?php esc_html_e( 'How Shortcode Works', 'wp-blog-and-widgets' ); ?>:</label>
										</th>
										<td>
											<ul>
												<li><?php esc_html_e( 'Step-1. Create a page like Blog OR Our Blog.', 'wp-blog-and-widgets' ); ?></li>
												<li><?php esc_html_e( 'Step-2. Put below shortcode as per your need.', 'wp-blog-and-widgets' ); ?></li>
											</ul>
										</td>
									</tr>

									<tr>
										<th>
											<label><?php esc_html_e( 'All Shortcodes', 'wp-blog-and-widgets' ); ?>:</label>
										</th>
										<td>
											<span class="wpbawh-shortcode-preview wpos-copy-clipboard">[blog]</span> – <?php esc_html_e( 'Blog in List View', 'wp-blog-and-widgets' ); ?> <br />
											<span class="wpbawh-shortcode-preview wpos-copy-clipboard">[blog grid="1"]</span> – <?php esc_html_e('Display blog in grid 1', 'wp-blog-and-widgets'); ?> <br />
											<span class="wpbawh-shortcode-preview wpos-copy-clipboard">[blog grid="2"]</span> – <?php esc_html_e('Display blog in grid 2', 'wp-blog-and-widgets'); ?> <br />
											<span class="wpbawh-shortcode-preview wpos-copy-clipboard">[blog grid="3"]</span> – <?php esc_html_e('Display blog in grid 3', 'wp-blog-and-widgets'); ?><br />
											<span class="wpbawh-shortcode-preview wpos-copy-clipboard">[recent_blog_post]</span> – <?php esc_html_e('Display recent blog posts', 'wp-blog-and-widgets'); ?>
										</td>
									</tr>
									<tr>
										<th>
											<label><?php esc_html_e('Documentation', 'wp-blog-and-widgets'); ?>:</label>
										</th>
										<td>
											<a class="button button-primary" href="https://docs.essentialplugin.com/wp-blog-and-widgets/" target="_blank"><?php esc_html_e('Check Documentation', 'wp-blog-and-widgets'); ?></a>
										</td>
									</tr>
									<tr>
										<th>
											<label><?php esc_html_e('Demo', 'wp-blog-and-widgets'); ?></label>
										</th>
										<td>
											<a class="button button-primary" href="https://demo.essentialplugin.com/blog-demo/" target="_blank"><?php esc_html_e('Check Free Demo', 'wp-blog-and-widgets'); ?></a>
										</td>
									</tr>
								</tbody>
							</table>
						</div><!-- .inside -->
					</div><!-- #general -->
				</div><!-- .meta-box-sortables -->

				<div class="meta-box-sortables">
					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle">
								<span><?php esc_html_e( 'Gutenberg Support', 'wp-blog-and-widgets' ); ?></span>
							</h2>
						</div>
						<div class="inside">
							<table class="form-table">
								<tbody>
									<tr>
										<th>
											<label><?php esc_html_e('How it Work', 'wp-blog-and-widgets'); ?>:</label>
										</th>
										<td>
											<ul>
												<li><?php esc_html_e('Step-1. Go to the Gutenberg editor of your page.', 'wp-blog-and-widgets'); ?></li>
												<li><?php esc_html_e('Step-2. Search "blog post" keyword in the Gutenberg block list.', 'wp-blog-and-widgets'); ?></li>
												<li><?php esc_html_e('Step-3. Add any block of blog post and you will find its relative options on the right end side.', 'wp-blog-and-widgets'); ?></li>
											</ul>
										</td>
									</tr>
								</tbody>
							</table>
						</div><!-- .inside -->
					</div><!-- #general -->
				</div><!-- .meta-box-sortables -->

				<!-- Help to improve this plugin! -->
				<div class="meta-box-sortables">
					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle">
								<span><?php esc_html_e( 'Help to improve this plugin!', 'wp-blog-and-widgets' ); ?></span>
							</h2>
						</div>
						<div class="inside">
							<p><?php echo sprintf( __( 'Enjoyed this plugin? You can help by rate this plugin <a href="%s" target="_blank">5 stars!', 'wp-blog-and-widgets'), 'https://wordpress.org/support/plugin/wp-blog-and-widgets/reviews/' ); ?></a></p>
						</div><!-- .inside -->
					</div><!-- #general -->
				</div><!-- .meta-box-sortables -->

			</div><!-- #post-body-content -->

			<!--Upgrad to Pro HTML -->
			<div id="postbox-container-1" class="postbox-container">
				<div class="meta-box-sortables">
					<div class="postbox wpos-pro-box">

						<h3 class="hndle">
							<span><?php esc_html_e( 'Blog and Widegt Premium Features', 'wp-blog-and-widgets' ); ?></span>
						</h3>
						<div class="inside">
							<ul class="wpos-list">
								<li><?php esc_html_e('50 Designs for Grid Layout', 'wp-blog-and-widgets'); ?></li>
								<li><?php esc_html_e('45 Designs for Slider/Carousel', 'wp-blog-and-widgets'); ?></li>
								<li><?php esc_html_e('8 Designs for List View', 'wp-blog-and-widgets'); ?></li>
								<li><?php esc_html_e('13 Designs for Grid Box', 'wp-blog-and-widgets'); ?></li>
								<li><?php esc_html_e('8 Designs for News Grid Box Slider', 'wp-blog-and-widgets'); ?></li>
								<li><?php esc_html_e('6 Shortcodes', 'wp-blog-and-widgets'); ?></li>
								<li><?php esc_html_e('6 Widgets (slider,list and category etc)', 'wp-blog-and-widgets'); ?></li>
								<li><?php esc_html_e('Gutenberg Block Supports', 'wp-blog-and-widgets'); ?></li>
								<li><?php esc_html_e('WPBakery Page Builder Supports', 'wp-blog-and-widgets'); ?></li>
								<li><?php esc_html_e('Gutenberg, Elementor, Beaver and SiteOrigin Page Builder Support', 'wp-blog-and-widgets'); ?> <span class="wpos-new-feature">New</span></li>
								<li><?php esc_html_e('Divi Page Builder Native Support', 'wp-blog-and-widgets'); ?> <span class="wpos-new-feature">New</span></li>
								<li><?php esc_html_e('Fusion Page Builder (Avada) native support', 'wp-blog-and-widgets'); ?> <span class="wpos-new-feature">New</span></li>
								<li><?php esc_html_e('WP Templating Features', 'wp-blog-and-widgets'); ?></li>
								<li><?php esc_html_e('Fully Responsive and Touch Based Slider', 'wp-blog-and-widgets'); ?></li>
								<li><?php esc_html_e('Custom Read More link for Blog Post', 'wp-blog-and-widgets'); ?></li>
								<li><?php esc_html_e('Blog display with categories', 'wp-blog-and-widgets'); ?></li>
								<li><?php esc_html_e('Drag & Drop feature to display Blog post in your desired order and other 6 types of order parameter', 'wp-blog-and-widgets'); ?></li>
								<li><?php esc_html_e("'Publicize' support with Jetpack to publish your Blog post on your social network", 'wp-blog-and-widgets'); ?></li>
								<li><?php esc_html_e('Custom CSS', 'wp-blog-and-widgets'); ?></li>
								<li><?php esc_html_e('100% Multi language', 'wp-blog-and-widgets'); ?></li>
							</ul>
							<div class="upgrade-to-pro"><?php esc_html_e('Gain access to', 'wp-blog-and-widgets'); ?> <strong>WP Blog and Widget</strong></div>
							<a class="button button-primary wpos-button-full button-orange" href="<?php echo esc_url(WPBAW_PLUGIN_LINK_UNLOCK); ?>" target="_blank"><?php esc_html_e('Upgrade To PRO', 'wp-blog-and-widgets'); ?></a>
						</div><!-- .inside -->
					</div><!-- #general -->
				</div><!-- .meta-box-sortables -->
			</div><!-- #post-container-1 -->

		</div><!-- #post-body -->
	</div><!-- #poststuff -->
</div><!-- end .wpbawh-wrap -->