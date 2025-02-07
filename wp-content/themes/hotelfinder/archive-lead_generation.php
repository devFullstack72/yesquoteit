<?php
get_header(); ?>

<div class="container archive-container" style="margin-bottom:100px;">
	<div class="row">
		
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
						$image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
						$post_link = get_permalink();
						$title = get_the_title();
						
						$output = ''; // Initialize the output variable

						// Start outputting the custom HTML structure
						$output .= '<div class="col-sm-4 col-xs-6">';
						$output .= '<div class="htlfndr-category-box" onclick="void(0)">';
						$output .= '<img src="' . esc_url( $image ) . '" height="311" width="370" alt="' . esc_attr( $title ) . '" />';
						$output .= '<div class="category-description">';
						$output .= '<h2 class="subcategory-name">' . esc_html( $title ) . '</h2>';
						$output .= '<a href="' . esc_url( $post_link ) . '" class="htlfndr-category-permalink"></a>';
						$output .= '<h5 class="category-name">' . esc_html( $title ) . '</h5>';
						$output .= '</div>'; // .category-description
			            $output .= '</div>'; // .htlfndr-category-box
			            $output .= '</div>'; // .col-sm-4 .col-xs-6

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

<?php get_footer(); ?>
