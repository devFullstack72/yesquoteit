<?php
get_header(); ?>

<div class="container archive-container">
	<div class="row">
		<div class="nv-index-posts blog col">
			<div class="posts-wrapper">
				<?php 
				// Get current page for pagination
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

				// Set up a custom query for 'lead_generation' posts with pagination
				$query = new WP_Query( array(
					'post_type' => 'lead_generation',
					'paged' => $paged, // Use the paged parameter to handle pagination
					'posts_per_page' => 12
				));
				
				if ( $query->have_posts() ) : 
					while ( $query->have_posts() ) : $query->the_post();
						$image = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
						$post_link = get_permalink();
						$title = get_the_title();
						
						$output = ''; // Initialize the output variable

						// Start outputting the custom HTML structure
						$output .= '<div class="col-xs-12 col-sm-4 col-md-4">';
						$output .= '<a href="' . esc_url( $post_link ) . '" style="text-decoration: none; color: inherit;">'; // Wrap the entire card
						$output .= '<article class="htlfndr-top-destination-block">';
						$output .= '<div class="htlfndr-content-block">';
						$output .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $title ) . '" />';
						$output .= '<div class="htlfndr-post-content">';
						$output .= '<p class="htlfndr-the-excerpt">' . get_the_excerpt();
						$output .= '<span class="htlfndr-read-more-arrow"><i class="fa fa-angle-right"></i></span>';
						$output .= '</p>';
						$output .= '<div class="htlfndr-services">';
						$output .= '</div>'; // .htlfndr-services
						$output .= '</div>'; // .htlfndr-post-content
						$output .= '</div>'; // .htlfndr-content-block
						$output .= '<footer class="entry-footer" style="text-align: center;">';
						$output .= '<h5 class="entry-title">' . esc_html( $title ) . '</h5>';
						$output .= '</footer>';
						$output .= '</article>'; // .htlfndr-top-destination-block
						$output .= '</a>'; // Close clickable wrapper
						$output .= '</div>'; // .col-xs-12 col-sm-4 col-md-4

						// Echo the output
						echo $output;

					endwhile;

					// Pagination
					?>
					<div class="pagination">
						<?php
							echo paginate_links(array(
								'prev_text' => __('« Prev'),
								'next_text' => __('Next »'),
								'total' => $query->max_num_pages, // Ensure the pagination knows the total number of pages
								'current' => $paged, // Current page number
							));
						?>
					</div>

				<?php else : ?>
					<p>No Lead Generations found.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
