<?php
get_header(); ?>

<div class="container archive-container">
	<div class="row">
		<div class="nv-index-posts blog col">
			<div class="posts-wrapper">
				<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<article id="post-<?php the_ID(); ?>" class="post-<?php the_ID(); ?> lead_generation type-lead_generation status-publish has-post-thumbnail hentry layout-grid">
							<div class="article-content-col">
								<div class="content">
									<div class="nv-post-thumbnail-wrap img-wrap">
										<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
											<img width="600" height="600" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>" class="wp-post-image" alt="<?php the_title_attribute(); ?>" decoding="async">
										</a>
									</div>
									<h2 class="blog-entry-title entry-title">
										<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
									</h2>
									<ul class="nv-meta-list">
										<li class="meta author vcard">
											<span class="author-name fn">by 
												<a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" title="Posts by <?php the_author(); ?>" rel="author">
													<?php the_author(); ?>
												</a>
											</span>
										</li>
									</ul>
									<div class="excerpt-wrap entry-summary">
										<p><?php echo wp_trim_words(get_the_excerpt(), 25, ''); ?></p>
									</div>
								</div>
							</div>
						</article>
					<?php endwhile; ?>

					<!-- Pagination -->
					<div class="pagination">
						<?php
							echo paginate_links(array(
								'prev_text' => __('« Prev'),
								'next_text' => __('Next »'),
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
