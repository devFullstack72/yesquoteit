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
            <h5>Where are you going?</h5>
            <form  action="search-result.html" name="search-hotel" id="search-hotel" class="htlfndr-search-form">
                <div id="htlfndr-input-1" class="htlfndr-input-wrapper">
                    <input type="text" name="htlfndr-city" id="htlfndr-city" class="search-hotel-input" placeholder="Enter city, region or district" />
                    <p class="htlfndr-search-checkbox">
                        <input type="checkbox" id="htlfndr-checkbox" name="htlfndr-checkbox" value="no-dates" />
                        <label for="htlfndr-checkbox">I don't have specific dates yet</label>
                    </p>
                </div><!-- #htlfndr-input-1.htlfndr-input-wrapper -->

                <div id="htlfndr-input-date-in" class="htlfndr-input-wrapper">
                    <label for="htlfndr-date-in" class="sr-only">Date in</label>
                    <input type="text" name="htlfndr-date-in" id="htlfndr-date-in" class="search-hotel-input" />
                    <button type="button" class="htlfndr-clear-datepicker"></button>
                </div><!-- #htlfndr-input-date-in.htlfndr-input-wrapper -->

                <div id="htlfndr-input-date-out" class="htlfndr-input-wrapper">
                    <label for="htlfndr-date-out" class="sr-only">Date out</label>
                    <input type="text" name="htlfndr-date-out" id="htlfndr-date-out" class="search-hotel-input" />
                    <button type="button" class="htlfndr-clear-datepicker"></button>
                </div><!-- #htlfndr-input-date-out.htlfndr-input-wrapper -->

                <div id="htlfndr-input-4" class="htlfndr-input-wrapper">
                    <label for="htlfndr-dropdown" class="sr-only">Number person in room</label>
                    <select name="htlfndr-dropdown" id="htlfndr-dropdown" class="htlfndr-dropdown">
                        <option value="1 adult">1 adult</option>
                        <option value="2 adults in 1 room">2 adults in 1 room</option>
                        <option value="3 adults in 1 room">3 adults in 1 room</option>
                        <option value="4 adults in 1 room">4 adults in 1 room</option>
                        <option value="2 adults in 2 room">2 adults in 2 room</option>
                        <option value="need more">Need more?</option>
                    </select>
                </div><!-- #htlfndr-input-4.htlfndr-input-wrapper -->

                <div id="htlfndr-input-5">
                    <input type="submit" value="search"/>
                </div><!-- #htlfndr-input-5.htlfndr-input-wrapper -->
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

            <?php get_footer(); ?>
