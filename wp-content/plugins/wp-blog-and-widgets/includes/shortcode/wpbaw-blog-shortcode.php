<?php
/**
 * 'blog' Shortcode
 * 
 * @package WP Blog and Widgets
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function wpbaw_get_blog_post( $atts, $content = null ){

	// Taking some globals
	global $post, $multipage;

	// Shortcode Parameter
	extract( shortcode_atts( array(
		'limit'					=> 10,
		'category'				=> '',
		'grid'					=> 0,
		'show_date'				=> 'true',
		'show_category_name'	=> 'true',
		'show_author'			=> 'true',
		'show_content'			=> 'true',
		'show_full_content'		=> 'false',
		'content_words_limit'	=> 20,
		'pagination_type'		=> 'numeric',
		'order'					=> 'DESC',
		'orderby'				=> 'date',
		'extra_class'			=> '',
		'className'				=> '',
		'align'					=> '',
	), $atts, 'blog' ) );

	// Define variables
	$unique 				= wpbaw_get_unique();
	$limit 					= ! empty( $limit ) 					? $limit 						: 10;
	$category 				= ! empty( $category ) 					? explode( ',', $category ) 	: '';
	$grid					= wpbaw_clean_number( $grid, 0 );
	$show_date 				= ( $show_date == 'true' ) 				? 1 							: 0;
	$show_category_name 	= ( $show_category_name == 'true' )		? 1 							: 0;
	$show_content 			= ( $show_content == 'true' ) 			? 1 							: 0;
	$show_author   			= ( $show_author == 'true' ) 			? 1 							: 0;
	$show_full_content   	= ( $show_full_content == 'true' ) 		? 1 							: 0;
	$content_words_limit 	= ! empty( $content_words_limit ) 		? $content_words_limit 			: 20;
	$pagination_type 		= ( $pagination_type == 'next-prev' )	? 'next-prev' 					: 'numeric';
	$order 					= ( strtolower( $order ) == 'asc' ) 	? 'ASC' 						: 'DESC';
	$orderby 				= ! empty( $orderby )					? $orderby 						: 'date';
	$align					= ! empty( $align )						? 'align'.$align				: '';
	$extra_class			= $extra_class .' '. $align .' '. $className;
	$extra_class			= wpbaw_get_sanitize_html_classes( $extra_class );
	$multi_page				= ( $multipage || is_single() || is_front_page() || is_archive() ) ? 1 : 0;

	ob_start();

	// Taking some variables
	$count = 0;
	$paged = 1;

	if( $multi_page ) {
		$paged = isset( $_GET['blog_page'] ) ? wpbaw_clean( $_GET['blog_page'] ) : 1;
	} else if ( get_query_var('paged') ) {
		$paged = get_query_var('paged');
	} else if ( get_query_var('page') ) {
		$paged = get_query_var('page');
	}

	// Query Parameters
	$args = array (
		'post_type'			=> WPBAW_POST_TYPE,
		'orderby'			=> $orderby, 
		'order'				=> $order,
		'posts_per_page'	=> $limit,
		'paged'				=> $paged,
	);

	// Category Parameter
	if( $category != "" ) {
		$args['tax_query'] = array(
								array( 
									'taxonomy'	=> WPBAW_CAT,
									'field'		=> 'term_id',
									'terms'		=> $category
								));
	}

	// WP Query
	$query			= new WP_Query( $args );
	$max_num_pages 	= $query->max_num_pages;

	// If post is there  
	if ( $query->have_posts() ) : ?>

	<div id="wpbaw-blog-<?php echo esc_attr( $unique ); ?>" class="blogfree-plugin blog-clearfix <?php echo esc_attr( $extra_class ); ?>">
	
		<?php while ( $query->have_posts() ) : $query->the_post();

		$count++;
		$news_links = array();
		$post_title = get_the_title();
		$feat_image = wpbaw_get_post_featured_image( $post->ID, 'large' );
		$terms		= get_the_terms( $post->ID, WPBAW_CAT );

		if( $terms ) {
			foreach ( $terms as $term ) {
				$term_link		= get_term_link( $term );
				$news_links[]	= '<a href="' . esc_url( $term_link ) . '">'.wp_kses_post( $term->name ).'</a>';
			}
		}
		$cate_name = join( ", ", $news_links );

		// CSS Class
		$main_wrap_css = "wpbaw-blog-class blog-col-{$grid}";
		$main_wrap_css .= ( $grid && $count % $grid == 1 )	? ' wpbaw-first'	: '';
		$main_wrap_css .= ( $show_date )					? ' has-date'		: ' has-no-date';
		$main_wrap_css .= ( $feat_image )					? ' has-thumb'		: ' no-thumb';
	?>
		<div id="post-<?php the_ID(); ?>" class="blog type-blog blog-clearfix <?php echo esc_attr( $main_wrap_css ); ?>">
			<div class="blog-inner-wrap-view blog-clearfix">
				<?php if ( $feat_image ) { ?>
					<div class="blog-thumb">
						<div class="grid-blog-thumb">
							<a href="<?php the_permalink(); ?>"><img class="wpbaw-blog-img" src="<?php echo esc_url( $feat_image ); ?>" alt="<?php the_title_attribute(); ?>"/></a>
						</div>
					</div>
				<?php } ?>

				<div class="blog-content">
					<?php if( $grid == 1 ) {
						if( $show_date ) { ?>
							<div class="date-post">
								<h2><span><?php echo get_the_date('j'); ?></span></h2>
								<p><?php echo get_the_date('M y'); ?></p>
							</div>
						 <?php }
					} else {

						if( $show_category_name && $cate_name != '' ) { ?>
						<div class="grid-category-post">
							<?php echo wp_kses_post( $cate_name ); ?>
						</div>
						<?php }

						if( $show_author || $show_date ) { ?>
							<div class="blog-author">
								<?php if( $show_author ) { ?>
									<span>
										<?php esc_html_e( 'By', 'wp-blog-and-widgets' ); ?> <a href="<?php echo esc_url( get_author_posts_url( $post->post_author ) ); ?>" target="_self"><?php echo get_the_author(); ?></a>
									</span>
								<?php }
								
								echo ( $show_author && $show_date ) ? ' / ' : '';

								echo ( $show_date ) ? get_the_date() : ""; ?>
							</div>
						<?php }
					} ?>

					<div class="post-content-text">
						<?php if( $grid == 1 && $show_date ) { ?>
						<div class="grid-1-date">
							<?php echo get_the_date(); ?>
						</div>
						<?php }

						if( $post_title ) { ?>
						<h3 class="blog-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php echo wp_kses_post( $post_title ); ?></a></h3>
						<?php }

						if( $grid == 1 ) { ?>
						<div class="blog-cat">
							<?php if( $show_author ) { ?>
						  	<span class="grid-1-author"><?php esc_html_e( 'By', 'wp-blog-and-widgets' ); ?>
						  		<a href="<?php echo esc_url( get_author_posts_url( $post->post_author ) ); ?>" target="_self"><?php echo get_the_author(); ?></a>
						  	</span>
							<?php }

							echo ( $show_author && ( $show_category_name && $cate_name ) ) ? ' / ' : '';

							if( $show_category_name ) { echo wp_kses_post( $cate_name ); } ?>
						</div>
						<?php }

						if( $show_content ) { ?>
						<div class="blog-content-excerpt">
							<?php if( ! $show_full_content ) { ?>
								<p class="blog-short-content"><?php echo wpbaw_get_post_excerpt( $post->ID, get_the_content(), $content_words_limit ); ?></p>
								<a href="<?php the_permalink(); ?>" class="blog-more-link"> <?php esc_html_e( 'Read More', 'wp-blog-and-widgets' ); ?></a>
							<?php } else {
								echo get_the_content();
							} ?>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<?php endwhile; ?>
	</div>
	
	<?php if( $max_num_pages > 1 ) { ?>
	<div class="blog_pagination blog-clearfix wpbaw-<?php echo esc_attr( $pagination_type ); ?>">
		<?php echo wpbaw_blog_pagination( array( 'paged' => $paged, 'total' => $max_num_pages, 'multi_page' => $multi_page, 'pagination_type' => $pagination_type, 'unique' => $unique ) ); ?>
	</div>
	<?php }

	endif;

	wp_reset_postdata(); // Reset WP Query

	$content .= ob_get_clean();
	return $content;
}

// Blog Shortcode
add_shortcode( 'blog', 'wpbaw_get_blog_post' );