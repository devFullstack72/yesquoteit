<?php
/**
 * Function Custom meta box for Premium
 * 
 * @package WP Blog and Widgets
 * @since 2.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<!-- <div class="pro-notice"><strong><?php //echo sprintf( __( 'Utilize this <a href="%s" target="_blank">Premium Features (With Risk-Free 30 days money back guarantee)</a> to get best of this plugin with Annual or Lifetime bundle deal.', 'wp-blog-and-widgets'), WPBAW_PLUGIN_LINK_UNLOCK); ?></strong></div> -->


<!-- <div class="pro-notice">
	<strong>
		<?php //echo sprintf( __( 'Try All These <a href="%s" target="_blank">PRO Features in Essential Bundle Free For 5 Days.</a>', 'wp-blog-and-widgets'), WPBAW_PLUGIN_LINK_UNLOCK); ?>
	</strong>
</div> -->

	<!-- <div class="wpbaw-black-friday-banner-wrp">
		<a href="<?php // echo esc_url( WPBAW_PLUGIN_LINK_UNLOCK ); ?>" target="_blank"><img style="width: 100%;" src="<?php // echo esc_url( WPBAW_URL ); ?>assets/images/black-friday-banner.png" alt="black-friday-banner" /></a>
	</div> -->

	<strong style="color:#2ECC71; font-weight: 700;"><?php echo sprintf( __( ' <a href="%s" target="_blank" style="color:#2ECC71;">Upgrade To Pro</a> and Get Designs, Optimization, Security, Backup, Migration Solutions @ one stop.', 'wp-blog-and-widgets'), WPBAW_PLUGIN_LINK_UNLOCK); ?></strong>

<table class="form-table wpbaw-metabox-table">
	<tbody>
		<tr class="wpbaw-pro-feature">
			<th>
				<?php esc_html_e('Layouts', 'wp-blog-and-widgets'); ?> <span class="wpbaw-pro-tag"><?php esc_html_e('PRO','wp-blog-and-widgets');?></span>
			</th>
			<td>
				<span class="description"><?php esc_html_e('6 (Blog, Recent Blog, Slider, Gridbox , Gridbox Slider , List). In lite version only 1 layout.', 'wp-blog-and-widgets'); ?></span>
			</td>
		</tr>
		<tr class="wpbaw-pro-feature">
			<th>
				<?php esc_html_e('Designs', 'wp-blog-and-widgets'); ?> <span class="wpbaw-pro-tag"><?php esc_html_e('PRO','wp-blog-and-widgets');?></span>
			</th>
			<td>
				<span class="description"><?php esc_html_e('120+. In lite version only one design.', 'wp-blog-and-widgets'); ?></span>
			</td>
		</tr>
		<tr class="wpbaw-pro-feature">
			<th>
				<?php esc_html_e('Widgets', 'wp-blog-and-widgets'); ?> <span class="wpbaw-pro-tag"><?php esc_html_e('PRO','wp-blog-and-widgets');?></span>
			</th>
			<td>
				<span class="description"><?php esc_html_e('6. In lite version only one widget.', 'wp-blog-and-widgets'); ?></span>
			</td>
		</tr>
		<tr class="wpbaw-pro-feature">
			<th>
				<?php esc_html_e('WP Templating Features', 'wp-blog-and-widgets'); ?> <span class="wpbaw-pro-tag"><?php esc_html_e('PRO','wp-blog-and-widgets');?></span>
			</th>
			<td>
				<span class="description"><?php esc_html_e('You can modify plugin html/designs in your current theme.', 'wp-blog-and-widgets'); ?></span>
			</td>
		</tr>
		<tr class="wpbaw-pro-feature">
			<th>
				<?php esc_html_e('Shortcode Generator', 'wp-blog-and-widgets'); ?> <span class="wpbaw-pro-tag"><?php esc_html_e('PRO','wp-blog-and-widgets');?></span>
			</th>
			<td>
				<span class="description"><?php esc_html_e('Play with all shortcode parameters with preview panel. No documentation required.' , 'wp-blog-and-widgets'); ?></span>
			</td>
		</tr>
		<tr class="wpbaw-pro-feature">
			<th>
				<?php esc_html_e('Drag & Drop Slide Order Change', 'wp-blog-and-widgets'); ?> <span class="wpbaw-pro-tag"><?php esc_html_e('PRO','wp-blog-and-widgets');?></span>
			</th>
			<td>
				<span class="description"><?php esc_html_e('Arrange your desired slides with your desired order and display.' , 'wp-blog-and-widgets'); ?></span>
			</td>
		</tr>
		<tr class="wpbaw-pro-feature">
			<th>
				<?php esc_html_e('Page Builder Support', 'wp-blog-and-widgets'); ?> <span class="wpbaw-pro-tag"><?php esc_html_e('PRO','wp-blog-and-widgets');?></span>
			</th>
			<td>
				<span class="description"><?php esc_html_e('Gutenberg Block, Elementor, Bevear Builder, SiteOrigin, Divi, Visual Composer and Fusion Page Builder Support', 'wp-blog-and-widgets'); ?></span>
			</td>
		</tr>
		<tr class="wpbaw-pro-feature">
			<th>
				<?php esc_html_e('Exclude Blog and Exclude Some Categories', 'wp-blog-and-widgets'); ?> <span class="wpbaw-pro-tag"><?php esc_html_e('PRO','wp-blog-and-widgets');?></span>
			</th>
			<td>
				<span class="description"><?php esc_html_e('Do not display the blog & Do not display the blogs for particular categories.' , 'wp-blog-and-widgets'); ?></span>
			</td>
		</tr>
	</tbody>
</table><!-- end .wpbaw-metabox-table -->