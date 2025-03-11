<?php
/**
 * Blog Widget Class
 *
 * @package WP Blog and Widgets
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpbaw_Blog_Widget extends WP_Widget {

	var $defaults;

	/**
	 * Sets up a new widget instance.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		$widget_ops = array( 'classname' => 'SP_Blog_Widget', 'description' => __( 'Displayed Latest Blog post  in a sidebar', 'wp-blog-and-widgets' ) );
		$control_ops = array( 'width' => 350, 'height' => 450, 'id_base' => 'sp_blog_widget' );
		parent::__construct( 'sp_blog_widget', __( 'Latest Blog Widget', 'wp-blog-and-widgets' ), $widget_ops, $control_ops );

		// Widgets defaults
		$this->defaults = array(
			'title'			=> __( 'Latest Blog Widget', 'wp-blog-and-widgets' ),
			'num_items'		=> 5,
			'date'			=> "false",
			'show_category'	=> "false",
			'show_thunb'	=> "false",
			'category'		=> 0,
		);
	}

	/**
	 * Handles updating settings for the current widget instance.
	 *
	 * @since 1.0.0
	 */
	function update( $new_instance, $old_instance ) {

		$instance					= $old_instance;

		$instance['title']			= isset( $new_instance['title'] )			? wpbaw_clean( $new_instance['title'] )	: '';
		$instance['category']		= isset( $new_instance['category'] )		? wpbaw_clean( $new_instance['category'] ) 	: '';
		$instance['date']			= ! empty( $new_instance['date'] )			? 1	: 0;
		$instance['show_category']	= ! empty( $new_instance['show_category'] )	? 1	: 0;
		$instance['show_thunb']		= ! empty( $new_instance['show_thunb'] )	? 1	: 0;
		$instance['num_items']		= wpbaw_clean_number( $new_instance['num_items'], 5, 'number' );

		return $instance;
	}

	/**
	 * Outputs the settings form for the widget.
	 *
	 * @since 1.0.0
	 */
	function form( $instance ) {

		$instance	= wp_parse_args( (array)$instance, $this->defaults ); ?>

		<!-- Title -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"> <?php esc_html_e( 'Title ', 'wp-blog-and-widgets' ); ?> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</label>
		</p>

		<!-- Limit -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'num_items' ) ); ?>"><?php esc_html_e( 'Number of Items ', 'wp-blog-and-widgets' ); ?>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'num_items' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'num_items' ) ); ?>" type="number" value="<?php echo esc_attr( $instance['num_items'] ); ?>" />
			</label>
		</p>

		<!-- Category -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Category ', 'wp-blog-and-widgets' ); ?></label>
			<?php
				$dropdown_args = array( 
					'taxonomy'			=> 'blog-category', 
					'class'				=> 'widefat', 
					'show_option_all'	=> esc_html__( 'All', 'wp-blog-and-widgets' ), 
					'id'				=> esc_attr( $this->get_field_id( 'category' ) ), 
					'name'				=> esc_attr( $this->get_field_name( 'category' ) ), 
					'selected'			=> $instance['category'] );

				wp_dropdown_categories( $dropdown_args );
			?>
		</p>

		<!-- Post Date -->
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'date' ) ); ?>" type="checkbox"<?php checked( $instance['date'], 1 ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>"><?php esc_html_e( 'Display Date', 'wp-blog-and-widgets' ); ?></label>
		</p>

		<!-- Show Category -->
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_category' ) ); ?>" type="checkbox"<?php checked( $instance['show_category'], 1 ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_category' ) ); ?>"><?php esc_html_e( 'Display Category', 'wp-blog-and-widgets' ); ?></label>
		</p>

		<!-- Show Thumbnail -->
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_thunb' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_thunb' ) ); ?>" type="checkbox"<?php checked( $instance['show_thunb'], 1 ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_thunb' ) ); ?>"><?php esc_html_e( 'Display Thumbnail', 'wp-blog-and-widgets' ); ?></label>
		</p>
	<?php }

	/**
	 * Outputs the settings form for the widget.
	 *
	 * @since 1.0.0
	 */
	function widget( $widget_args, $instance ) {

		// Taking some globals
		global $post;

		$atts = wp_parse_args( (array)$instance, $this->defaults );
		extract( $widget_args, EXTR_SKIP );

		$title					= apply_filters( 'widget_title', $atts['title'], $atts, $this->id_base );
		$atts['date']			= ! empty( $atts['date'] )			? true	: false;
		$atts['show_category']	= ! empty( $atts['show_category'] )	? true	: false;
		$atts['show_thunb']		= ! empty( $atts['show_thunb'] )	? true	: false;

		// Extract Widegt Var
		extract( $atts );

		// WP Query Parameter
		$news_args = array( 
						'post_type'			=> 'blog_post',
						'order'				=> 'DESC',
						'posts_per_page'	=> $num_items,
						'no_found_rows'		=> true,
					);

		// Category Parameter
		if( ! empty( $category ) ) {
			$news_args['tax_query'] = array( array( 
									'taxonomy'	=> 'blog-category', 
									'field'		=> 'id', 
									'terms'		=> $category
			) );
		}

		// WP Query
		$cust_loop	= new WP_Query( $news_args );

		echo $before_widget; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped

		// Widget Title
		if ( $title ) {
			echo $before_title . wp_kses_post($title) . $after_title; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		}

		// If post is there
		if ($cust_loop->have_posts()) :

		// Visual columns
		$no_p = ( empty( $date ) && empty( $show_category ) ) ? "no_p" : ''; ?>

		<div class="recent-blog-items <?php echo esc_attr( $no_p ); ?>">
			<ul>
				<?php while ($cust_loop->have_posts()) : $cust_loop->the_post(); 

					$blog_links = array();
					$terms 		= get_the_terms( $post->ID, 'blog-category' );

					if( $terms ) {
						foreach ( $terms as $term ) {
							$term_link 		= get_term_link( $term );
							$blog_links[]	= '<a href="' . esc_url( $term_link ) . '">'.wp_kses_post($term->name).'</a>';
						}
					}
					$cate_name = join( ", ", $blog_links ); ?>

					<li class="blog_li">
						<?php if( ! $show_thunb ) { ?>
							<a class="blogpost-title" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
							<?php if( $date ||  $show_category ) { ?>
								<div class="widget-date-cat">
									<?php echo ( $date ) ? get_the_date() : "";
									
									echo ( $date && $show_category && $cate_name != '' ) ? " , " : "";

									echo ( $show_category && $cate_name != '' )	? wp_kses_post( $cate_name ) : ""; ?>
								</div>
							<?php }

						} else { ?>

							<div class="blog_thumb_left">
								<a class="li-link-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail(array(80, 80)); ?></a>
							</div>

							<div class="blog_thumb_right">
								<a class="li-link-custom" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
								<?php if( $date || $show_category ) { ?>
									<div class="widget-date-cat" style="margin-bottom:5px;">
										<?php echo ( $date ) ? get_the_date() : "";

										echo ( $date && $show_category && $cate_name != '' ) ? " , " : "";

										echo ( $show_category && $cate_name != '' ) ? wp_kses_post( $cate_name ) : ""; ?>
									</div>
								<?php } ?>
							</div>
						<?php } ?>
					</li>
				<?php endwhile; ?>
			</ul>
		</div>
	<?php
		endif;
		wp_reset_postdata(); // Reset WP Query

		echo $after_widget; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
	}
}

/* Register the widget */
function wpbaw_blog_widget_load_widgets() {
	register_widget( 'Wpbaw_Blog_Widget' );
}

/* Load the widget */
add_action( 'widgets_init', 'wpbaw_blog_widget_load_widgets' );