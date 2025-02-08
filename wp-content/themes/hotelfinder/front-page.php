<?php get_header(); ?>
<!-- Start of slider section -->
<section class="htlfndr-slider-section">
    <div class="owl-carousel">
        <div class="htlfndr-slide-wrapper">
            <img src="https://www.jqueryscript.net/demo/Responsive-Full-Width-jQuery-Image-Slider-Plugin-skdslider/slides/1.jpg" alt="img-1" />
            <div class="htlfndr-slide-data container">      
                <div class="htlfndr-slide-rating-stars">
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                </div><!-- .htlfndr-slide-rating-stars -->
                <h1 class="htlfndr-slider-title">find your perfect quote</h1>
                <div class="htlfndr-slider-under-title-line"></div>
            </div><!-- .htlfndr-slide-data.container -->
        </div><!-- .htlfndr-slide-wrapper-->
        <div class="htlfndr-slide-wrapper">
            <img src="https://www.jqueryscript.net/demo/Responsive-Full-Width-jQuery-Image-Slider-Plugin-skdslider/slides/1.jpg" alt="img-2" />
            <div class="htlfndr-slide-data container">      
                <div class="htlfndr-slide-rating-stars">
                    <i class="fa fa-star-o htlfndr-star-color"></i>
                    <i class="fa fa-star-o htlfndr-star-color"></i>
                    <i class="fa fa-star-o htlfndr-star-color"></i>
                    <i class="fa fa-star-o htlfndr-star-color"></i>
                    <i class="fa fa-star-o"></i>
                </div><!-- .htlfndr-slide-rating-stars -->
                <h1 class="htlfndr-slider-title">find your perfect quote</h1>
                <div class="htlfndr-slider-under-title-line"></div>
            </div><!-- .htlfndr-slide-data.container -->
        </div><!-- .htlfndr-slide-wrapper-->
        <div class="htlfndr-slide-wrapper">
            <img src="https://www.jqueryscript.net/demo/Responsive-Full-Width-jQuery-Image-Slider-Plugin-skdslider/slides/1.jpg" alt="img-3" />
            <div class="htlfndr-slide-data container">      
                <div class="htlfndr-slide-rating-stars">
                    <i class="fa fa-star-o htlfndr-star-color"></i>
                    <i class="fa fa-star-o htlfndr-star-color"></i>
                    <i class="fa fa-star-o htlfndr-star-color"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                </div><!-- .htlfndr-slide-rating-stars -->
                <h1 class="htlfndr-slider-title">find your perfect quote</h1>
                <div class="htlfndr-slider-under-title-line"></div>
            </div><!-- .htlfndr-slide-data.container -->
        </div><!-- .htlfndr-slide-wrapper-->
    </div>

    <!-- Search form aside start -->
    <aside class="htlfndr-form-in-slider htlfndr-search-form-inline">
        <div class="container">
            <h5>Where are you looking for?</h5>
                <form action="<?php echo esc_url(get_post_type_archive_link('lead_generation')); ?>" method="GET" name="search-lead" id="search-lead" class="htlfndr-search-form">
                    <div id="htlfndr-input-category" class="htlfndr-input-wrapper">
                        <label for="htlfndr-category" class="sr-only">Select Category</label>
                        <select name="htlfndr-category" id="htlfndr-category" class="htlfndr-dropdown">
                            <option value="">Select Category</option>
                            <?php
                            $categories = get_terms(array(
                                'taxonomy'   => 'lead_category',
                                'hide_empty' => false,
                            ));
                            if (!empty($categories) && !is_wp_error($categories)) {
                                foreach ($categories as $category) {
                                    echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <span id="category-error" class="error-message" style="color: red; display: none;">Please select a category or enter a search lead text.</span>
                    </div>

                    <div id="htlfndr-input-search" class="htlfndr-input-wrapper">
                        <input type="text" name="htlfndr-search" id="htlfndr-search" class="search-hotel-input" placeholder="Search by lead name" />
                    </div>

                    <div id="htlfndr-input-5">
                        <input type="submit" value="Search" />
                    </div>
                </form>


        </div><!-- .container -->
    </aside><!-- .htlfndr-form-in-slider.container-fluid -->
    <!-- Search form aside stop -->

</section><!-- .htlfndr-slider-section -->

<!-- Start of main content -->
<main role="main">

    <!-- Section called USP section -->
    <section class="container-fluid htlfndr-usp-section">
        <h2 class="htlfndr-section-title htlfndr-lined-title"><span>How It works</span></h2><!-- You need <span> and 'htlfndr-lined-title' class for both side line -->
            <div class="container">
                <div class="row">
                    <div class="col-sm-4 htlfndr-icon-box">
                        <img class="htlfndr-icon icon-drinks" src="<?php echo get_template_directory_uri(); ?>/images/icons1.png" height="100" width="100" alt="icon" />
                        <h5 class="htlfndr-section-subtitle">Post your Request</h5>
                        <p>Post a quote request in under a minute (no obligations ‐ this is a free service!)</p>
                        <!-- <a href="#" class="htlfndr-read-more-button">read more</a> -->
                    </div><!-- .col-sm-4.htlfndr-icon-box -->

                    <div class="col-sm-4 htlfndr-icon-box">
                        <img class="htlfndr-icon icon-drinks" src="<?php echo get_template_directory_uri(); ?>/images/icons2.png" height="100" width="100" alt="icon" />
                        <h5 class="htlfndr-section-subtitle">Receive Quotes</h5>
                        <p>Matching businesses will be notified, and will provide quotes on your requirements. You can then discuss your requirements in detail with each provider.</p>
                        <!-- <a href="#" class="htlfndr-read-more-button">read more</a> -->
                    </div><!-- .col-sm-4.htlfndr-icon-box -->

                    <div class="col-sm-4 htlfndr-icon-box">
                        <img class="htlfndr-icon icon-drinks" src="<?php echo get_template_directory_uri(); ?>/images/icons3.png" height="100" width="100" alt="icon" />
                        <h5 class="htlfndr-section-subtitle">Choose The Best Deal</h5>
                        <p>After comparing each offer, select a business and finalise your quote with the selected business directly.</p>
                        <!-- <a href="#" class="htlfndr-read-more-button">read more</a> -->
                    </div><!-- .col-sm-4.htlfndr-icon-box -->
                </div><!-- .row -->
            </div><!-- .container -->
        </section><!-- .container-fluid .htlfndr-usp-section -->

        <!-- Section with categories -->
        <section class="container-fluid htlfndr-categories-portfolio">
            <h2 class="htlfndr-section-title bigger-title">Leads</h2>
            <div class="htlfndr-section-under-title-line"></div>
            <div class="container">
                <?php echo do_shortcode('[lead_generation_cards]'); ?>
                     </div>
                 </section><!-- .container-fluid.htlfndr-categories-portfolio -->

                 <!-- Section with visitors cards -->
                 <section class="container-fluid htlfndr-visitors-cards">
                    <h2 class="htlfndr-section-title bigger-title">What People are Saying</h2>
                    <div class="htlfndr-section-under-title-line"></div>
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-4 col-xs-12 htlfndr-visitor-column">
                                <div class="htlfndr-visitor-card">
                                    <div class="visitor-avatar-side">
                                        <div class="visitor-avatar">
                                            <img src="<?php echo get_template_directory_uri(); ?>/images/visitor-avatar-1.jpg" height="90" width="90" alt="user avatar" />
                                        </div><!-- .visitor-avatar -->
                                    </div><!-- .visitor-avatar-side -->
                                    <div class="visitor-info-side">
                                        <h5 class="visitor-user-name">Sara Thatcher</h5>
                                        <h6 class="visitor-user-firm">Travel Guru</h6>
                                        <p class="visitor-user-text">With so many sites offering the "Lowest Price Guarantee" this site was a real breath of fresh air.</p>
                                    </div><!-- .visitor-info-side -->
                                </div><!-- .htlfndr-visitor-card -->
                            </div><!-- .col-sm-4.col-xs-12.htlfndr-visitor-column -->

                            <div class="col-sm-4 col-xs-12 htlfndr-visitor-column">
                                <div class="htlfndr-visitor-card">
                                    <div class="visitor-avatar-side">
                                        <div class="visitor-avatar">
                                            <img src="<?php echo get_template_directory_uri(); ?>/images/visitor-avatar-2.jpg" height="90" width="90" alt="user avatar" />
                                        </div><!-- .visitor-avatar -->
                                    </div><!-- .visitor-avatar-side -->
                                    <div class="visitor-info-side">
                                        <h5 class="visitor-user-name">Amelia Young</h5>
                                        <h6 class="visitor-user-firm">Student</h6>
                                        <p class="visitor-user-text">Organised our whole trip in one spot. So easy to plan your trip without searching the internet for hours.</p>
                                    </div><!-- .visitor-info-side -->
                                </div><!-- .htlfndr-visitor-card -->
                            </div><!-- .col-sm-4.col-xs-12.htlfndr-visitor-column -->

                            <div class="col-sm-4 col-xs-12 htlfndr-visitor-column">
                                <div class="htlfndr-visitor-card">
                                    <div class="visitor-avatar-side">
                                        <div class="visitor-avatar">
                                            <img src="<?php echo get_template_directory_uri(); ?>/images/visitor-avatar-3.jpg" height="90" width="90" alt="user avatar" />
                                        </div><!-- .visitor-avatar -->
                                    </div><!-- .visitor-avatar-side -->
                                    <div class="visitor-info-side">
                                        <h5 class="visitor-user-name">Jason Smith</h5>
                                        <h6 class="visitor-user-firm">Hotel Manager</h6>
                                        <p class="visitor-user-text">Genuine enquiries without a middle man.. Very useful tool for any tourism based business..</p>
                                    </div><!-- .visitor-info-side -->
                                </div><!-- .htlfndr-visitor-card -->
                            </div><!-- .col-sm-4.col-xs-12.htlfndr-visitor-column -->
                        </div><!-- .row -->
                    </div><!-- .container -->
                </section><!-- .container-fluid.htlfndr-visitors-cards -->
            </main>
            <!-- End of main content -->
                <script>
                document.getElementById("search-lead").addEventListener("submit", function(event) {
                    var category = document.getElementById("htlfndr-category").value;
                    var search = document.getElementById("htlfndr-search").value.trim();
                    var errorMessage = document.getElementById("category-error");

                    if (category === "" && search === "") {
                        errorMessage.style.display = "block"; // Show error message
                        event.preventDefault(); // Stop form submission
                    } else {
                        errorMessage.style.display = "none"; // Hide error if valid
                    }
                });
                </script>
            <?php get_footer(); ?>
