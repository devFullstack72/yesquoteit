<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package WP News and Scrolling Widgets
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>
<div id="wpbaw_basic_tabs" class="wpbaw-vtab-cnt wpbaw_basic_tabs wpbaw-clearfix">

	<!-- <div class="wpbaw-deal-offer-wrap">
		<div class="wpbaw-deal-offer"> 
			<div class="wpbaw-inn-deal-offer">
				<h3 class="wpbaw-inn-deal-hedding"><span>Buy WP Blog and Widgets Pro</span> today and unlock all the powerful features.</h3>
				<h4 class="wpbaw-inn-deal-sub-hedding"><span style="color:red;">Extra Bonus: </span>Users will get <span>extra best discount</span> on the regular price using this coupon code.</h4>
			</div>
			<div class="wpbaw-inn-deal-offer-btn">
				<div class="wpbaw-inn-deal-code"><span>EPSEXTRA</span></div>
				<a href="<?php // echo esc_url(WPBAW_PLUGIN_BUNDLE_LINK); ?>"  target="_blank" class="wpbaw-sf-btn wpbaw-sf-btn-orange"><span class="dashicons dashicons-cart"></span> Get Essential Bundle Now</a>
				<em class="risk-free-guarantee"><span class="heading">Risk-Free Guarantee </span> - We offer a <span>30-day money back guarantee on all purchases</span>. If you are not happy with your purchases, we will refund your purchase. No questions asked!</em>
			</div>
		</div>
	</div> -->

	<!-- <div class="wpbaw-deal-offer-wrap">
		<div class="wpbaw-deal-offer"> 
			<div class="wpbaw-inn-deal-offer">
				<h3 class="wpbaw-inn-deal-hedding"><span>Try WP Blog and Widgets Pro</span> in Essential Bundle Free For 5 Days.</h3>
			</div>
			<div class="wpbaw-deal-free-offer">
				<a href="<?php // echo esc_url( WPBAW_PLUGIN_BUNDLE_LINK ); ?>" target="_blank" class="wpbaw-sf-free-btn"><span class="dashicons dashicons-cart"></span>Try Pro For 5 Days Free</a>
			</div>
		</div>
	</div> -->

	<!-- <div class="wpbaw-black-friday-banner-wrp">
		<a href="<?php // echo esc_url( WPBAW_PLUGIN_BUNDLE_LINK ); ?>" target="_blank"><img style="width: 100%;" src="<?php // echo esc_url( WPBAW_URL ); ?>assets/images/black-friday-banner.png" alt="black-friday-banner" /></a>
	</div> -->

	<div class="wpbaw-black-friday-banner-wrp" style="background:#e1ecc8;padding: 20px 20px 40px; border-radius:5px; text-align:center;margin-bottom: 40px;">
		<h2 style="font-size:30px; margin-bottom:10px;"><span style="color:#0055fb;">WP Blog and Widgets</span> is included in <span style="color:#0055fb;">Essential Plugin Bundle</span> </h2> 
		<h4 style="font-size: 18px;margin-top: 0px;color: #ff5d52;margin-bottom: 24px;">Now get Designs, Optimization, Security, Backup, Migration Solutions @ one stop. </h4>

		<div class="wpbaw-black-friday-feature">

			<div class="wpbaw-inner-deal-class" style="width:40%;">
				<div class="wpbaw-inner-Bonus-class">Bonus</div>
				<div class="wpbaw-image-logo" style="font-weight: bold;font-size: 26px;color: #222;"><img style="width: 34px; height:34px;vertical-align: middle;margin-right: 5px;" class="wpbaw-img-logo" src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/essential-logo-small.png" alt="essential-logo" /><span class="wpbaw-esstial-name" style="color:#0055fb;">Essential </span>Plugin</div>
				<div class="wpbaw-sub-heading" style="font-size: 16px;text-align: left;font-weight: bold;color: #222;margin-bottom: 10px;">Includes All premium plugins at no extra cost.</div>
				<a class="wpbaw-sf-btn" href="<?php echo esc_url( WPBAW_PLUGIN_BUNDLE_LINK ); ?>" target="_blank">Grab The Deal</a>
			</div>

			<div class="wpbaw-main-list-class" style="width:60%;">
				<div class="wpbaw-inner-list-class">
					<div class="wpbaw-list-img-class"><img src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/logo-image/img-slider.png" alt="essential-logo" /> Image Slider</li></div>

					<div class="wpbaw-list-img-class"><img src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/logo-image/advertising.png" alt="essential-logo" /> Publication</li></div>

					<div class="wpbaw-list-img-class"><img src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/logo-image/marketing.png" alt="essential-logo" /> Marketing</li></div>

					<div class="wpbaw-list-img-class"><img src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/logo-image/photo-album.png" alt="essential-logo" /> Photo album</li></div>

					<div class="wpbaw-list-img-class"><img src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/logo-image/showcase.png" alt="essential-logo" /> Showcase</li></div>

					<div class="wpbaw-list-img-class"><img src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/logo-image/shopping-bag.png" alt="essential-logo" /> WooCommerce</li></div>

					<div class="wpbaw-list-img-class"><img src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/logo-image/performance.png" alt="essential-logo" /> Performance</li></div>

					<div class="wpbaw-list-img-class"><img src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/logo-image/security.png" alt="essential-logo" /> Security</li></div>

					<div class="wpbaw-list-img-class"><img src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/logo-image/forms.png" alt="essential-logo" /> Pro Forms</li></div>

					<div class="wpbaw-list-img-class"><img src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/logo-image/seo.png" alt="essential-logo" /> SEO</li></div>

					<div class="wpbaw-list-img-class"><img src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/logo-image/backup.png" alt="essential-logo" /> Backups</li></div>

					<div class="wpbaw-list-img-class"><img src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/logo-image/White-labeling.png" alt="essential-logo" /> Migration</li></div>
				</div>
			</div>
		</div>
		<div class="wpbaw-main-feature-item">
			<div class="wpbaw-inner-feature-item">
				<div class="wpbaw-list-feature-item">
					<img src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/logo-image/layers.png" alt="layer" />
					<h5>Site management</h5>
					<p>Manage, update, secure & optimize unlimited sites.</p>
				</div>
				<div class="wpbaw-list-feature-item">
					<img src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/logo-image/risk.png" alt="backup" />
					<h5>Backup storage</h5>
					<p>Secure sites with auto backups and easy restore.</p>
				</div>
				<div class="wpbaw-list-feature-item">
					<img src="<?php echo esc_url( WPBAW_URL ); ?>assets/images/logo-image/support.png" alt="support" />
					<h5>Support</h5>
					<p>Get answers on everything WordPress at anytime.</p>
				</div>
			</div>
		</div>
		<a class="wpbaw-sf-btn" href="<?php echo esc_url( WPBAW_PLUGIN_BUNDLE_LINK ); ?>" target="_blank">Grab The Deal</a>
	</div>

	<h3 style="text-align:center">Compare <span class="wpbaw-blue">"WP Blog and Widget"</span> Free VS Pro</h3>

	<table class="wpos-plugin-pricing-table">
		<colgroup></colgroup>
		<colgroup></colgroup>
		<colgroup></colgroup>
		<thead>
			<tr>
				<th></th>
				<th>
					<h2>Free</h2>
				</th>
				<th>
					<h2 class="wpos-epb">Premium</h2>
				</th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<th>Designs <span>Designs that make your website better</span></th>
				<td>1</td>
				<td>120+</td>
			</tr>
			<tr>
				<th>Shortcodes <span>Shortcode provide output to the front-end side</span></th>
				<td>1 (Blog, Recent Blog)</td>
				<td>6 (Blog, Recent Blog, Slider, Gridbox , Gridbox Slider , List)</td>
			</tr>
			<tr>
				<th>Shortcode Parameters <span>Add extra power to the shortcode</span></th>
				<td>10</td>
				<td>30+</td>
			</tr>
			<tr>
				<th>Shortcode Generator <span>Play with all shortcode parameters with preview panel. No documentation required!!</span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th>WP Templating Features <span class="subtext">You can modify plugin html/designs in your current theme.</span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th>Widgets<span> WordPress Widgets to your sidebars.</span></th>
				<td>1</td>
				<td>6</td>
			</tr>
			<tr>
				<th>Drag & Drop Post Order Change <span>Arrange your desired post with your desired order and display</span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th>Gutenberg Block Supports <span>Use this plugin with Gutenberg easily</span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th>Elementor Page Builder Support <em class="wpos-new-feature">New</em> <span>Use this plugin with Elementor easily</span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th>Bevear Builder Support <em class="wpos-new-feature">New</em> <span>Use this plugin with Bevear Builder easily</span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th>SiteOrigin Page Builder Support <em class="wpos-new-feature">New</em> <span>Use this plugin with SiteOrigin easily</span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th>Divi Page Builder Native Support <em class="wpos-new-feature">New</em> <span>Use this plugin with Divi Builder easily</span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th>Fusion Page Builder (Avada) native support <em class="wpos-new-feature">New</em> <span>Use this plugin with Fusion(Avada) Builder easily</span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th>WPBakery Page Builder Supports <span>Use this plugin with Visual Composer/WPBakery Page Builder easily</span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th>Custom Read More link for Post <span>Redirect post to third party destination if any</span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th>Publicize <span> Support with Jetpack to publish your News post on</span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr><tr>
				<th>Display Desired Post <span>Display only the post you want</span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th>Display Post for Particular Categories <span>Display only the posts with particular category</span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th>Exclude Some Posts <span>Do not display the posts you want</span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th>Exclude Some Categories <span>Do not display the posts for particular categories</span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th>Post Order / Order By Parameters <span>Display post according to date, title and etc</span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th>Multiple Slider Parameters <span>Slider parameters like autoplay, number of slide, sider dots and etc.</span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th>Slider RTL Support <span>Slider supports for RTL website</span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th>Automatic Update <span>Get automatic  plugin updates </span></th>
				<td>Lifetime</td>
				<td>Lifetime</td>
			</tr>
			<tr>
				<th>Support <span>Get support for plugin</span></th>
				<td>Limited</td>
				<td>1 Year</td>
			</tr>
		</tbody>
	</table>
	<!-- End - Blog - Features -->
	<!-- <div class="wpbaw-deal-offer-wrap">
		<div class="wpbaw-deal-offer"> 
			<div class="wpbaw-inn-deal-offer">
				<h3 class="wpbaw-inn-deal-hedding"><span>Buy WP Blog and Widgets Pro</span> today and unlock all the powerful features.</h3>
				<h4 class="wpbaw-inn-deal-sub-hedding"><span style="color:red;">Extra Bonus: </span>Users will get <span>extra best discount</span> on the regular price using this coupon code.</h4>
			</div>
			<div class="wpbaw-inn-deal-offer-btn">
				<div class="wpbaw-inn-deal-code"><span>EPSEXTRA</span></div>
				<a href="<?php //echo esc_url(WPBAW_PLUGIN_BUNDLE_LINK); ?>"  target="_blank" class="wpbaw-sf-btn wpbaw-sf-btn-orange"><span class="dashicons dashicons-cart"></span> Get Essential Bundle Now</a>
				<em class="risk-free-guarantee"><span class="heading">Risk-Free Guarantee </span> - We offer a <span>30-day money back guarantee on all purchases</span>. If you are not happy with your purchases, we will refund your purchase. No questions asked!</em>
			</div>
		</div>
	</div> -->

	<!-- <div class="wpbaw-deal-offer-wrap">
		<div class="wpbaw-deal-offer"> 
			<div class="wpbaw-inn-deal-offer">
				<h3 class="wpbaw-inn-deal-hedding"><span>Try WP Blog and Widgets Pro</span> in Essential Bundle Free For 5 Days.</h3>
			</div>
			<div class="wpbaw-deal-free-offer">
				<a href="<?php //echo esc_url( WPBAW_PLUGIN_BUNDLE_LINK ); ?>" target="_blank" class="wpbaw-sf-free-btn"><span class="dashicons dashicons-cart"></span>Try Pro For 5 Days Free</a>
			</div>
		</div>
	</div> -->

	<!-- <div class="wpbaw-black-friday-banner-wrp">
		<a href="<?php // echo esc_url( WPBAW_PLUGIN_BUNDLE_LINK ); ?>" target="_blank"><img style="width: 100%;" src="<?php // echo esc_url( WPBAW_URL ); ?>assets/images/black-friday-banner.png" alt="black-friday-banner" /></a>
	</div> -->

</div>