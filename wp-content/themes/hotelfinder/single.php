<?php get_header(); ?>

<main class="blog-post">
    <div class="container">
        <div class="row" style="margin-top: 50px; margin-bottom: 100px;">
            
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                <!-- Blog Featured Image -->
                <div class="col-12">
                    <?php if (has_post_thumbnail()) : ?>
                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" 
                             alt="<?php the_title(); ?>" 
                             class="img-fluid" 
                             style="width: 100%; height: auto; border-radius: 10px;">
                    <?php endif; ?>
                </div>

                <!-- Blog Content -->
                <div class="col-md-8 mt-4">
                    <h1 class="blog-title"><?php the_title(); ?></h1>
                    
                    <div class="post-content">
                        <?php the_content(); ?>
                    </div>
                </div>

                <!-- Blog Sidebar (Author & Categories) -->
                <div class="col-md-4 mt-4">
                    <div class="blog-sidebar" style="background: #f8f9fa; padding: 15px; border-radius: 10px;">
                        <h5 style="margin-bottom: 15px;">Blog Details</h5>

                        <!-- Author -->
                        <p><strong>Author:</strong> <?php the_author(); ?></p>

                        <!-- Categories -->
                        <?php 
                            $categories = get_the_category();
                            if (!empty($categories)) {
                                ?>
                                <p><strong>Category:</strong>
                                    <?php
                                echo esc_html($categories[0]->name); 
                                ?>
                                </p>
                                <?php
                            }
                            ?>
                        

                        <!-- Published Date -->
                        <p><strong>Published on:</strong> <?php echo get_the_date(); ?></p>
                    </div>
                </div>

            <?php endwhile; else : ?>
                <div class="col-12">
                    <p>No content found.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php get_footer(); ?>
