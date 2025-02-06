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
<div style="margin-top: 25px;"> <!-- Adjust value as needed -->
    <div class="container">
        <h2 class="htlfndr-section-title bigger-title">Leads</h2>
        <div class="htlfndr-section-under-title-line"></div>
        <div class="row lead-generation-cards-wrapper">
            <?php echo do_shortcode('[lead_generation_cards]'); ?>
        </div>
    </div>
</div>
<!-- Start of main content -->
<main role="main">
    <!-- Section with popular destinations -->
    <section class="container htlfndr-top-destinations">
        <h2 class="htlfndr-section-title">top destinations</h2>
        <div class="htlfndr-section-under-title-line"></div>
        <div class="row">

            <div class="col-xs-12 col-sm-4 col-md-4 ">
                <article class="htlfndr-top-destination-block">
                    <div class="htlfndr-content-block">
                        <img src="http://placehold.it/360x295" alt="room-1" />
                        <div class="htlfndr-post-content">
                            <p class="htlfndr-the-excerpt">A modern hotel room in Star Hotel Nunc tempor erat in magna pulvinar fermentum. Pellentesque scelerisque at leo nec vestibulum. malesuada metus.
                                <a class="htlfndr-read-more-arrow" href="hotel-room-page.html"><i class="fa fa-angle-right"></i></a>
                            </p>
                            <div class="htlfndr-services">
                                <div class="row">
                                    <div class="col-sm-6 col-xs-6 htlfndr-service">Free WI-FI</div><!-- .col-sm-6 -->
                                    <div class="col-sm-6 col-xs-6 htlfndr-service">Incl. breakfast</div><!-- .col-sm-6 -->
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 col-xs-6 htlfndr-service">Private balcony</div><!-- .col-sm-6 -->
                                    <div class="col-sm-6 col-xs-6 htlfndr-service">Bathroom</div><!-- .col-sm-6 -->
                                </div><!-- .row -->
                            </div><!-- .htlfndr-services -->
                        </div><!-- .htlfndr-post-content -->
                    </div><!-- .htlfndr-content-block -->
                    <footer class="entry-footer">
                        <div class="htlfndr-left-side-footer">
                            <h5 class="entry-title">King Size Bedroom</h5>
                            <div class="htlfndr-entry-rating-stars">
                                <i class="fa fa-star htlfndr-star-color"></i>
                                <i class="fa fa-star htlfndr-star-color"></i>
                                <i class="fa fa-star htlfndr-star-color"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div><!-- .htlfndr-slide-rating-stars -->
                        </div><!-- .htlfndr-left-side-footer -->
                        <div class="htlfndr-right-side-footer">
                            <span class="htlfndr-cost">$ 100</span>
                            <span class="htlfndr-per-night">per night</span>
                        </div><!-- .htlfndr-right-side-footer -->
                    </footer>
                </article><!-- .htlfndr-top-destination-block -->
            </div><!-- .col-sm-4.col-xs-12 -->

            <div class="col-xs-12 col-sm-4 col-md-4 ">
                <article class="htlfndr-top-destination-block">
                    <div class="htlfndr-content-block">
                        <img src="http://placehold.it/360x295" alt="room-2" />
                        <div class="htlfndr-post-content">
                            <p class="htlfndr-the-excerpt">A modern hotel room in Star Hotel Nunc tempor erat in magna pulvinar fermentum. Pellentesque scelerisque at leo nec vestibulum. malesuada metus.
                                <a class="htlfndr-read-more-arrow" href="hotel-room-page.html"><i class="fa fa-angle-right"></i></a>
                            </p>
                            <div class="htlfndr-services">
                                <div class="row">
                                    <div class="col-sm-6 col-xs-6 htlfndr-service">Free WI-FI</div><!-- .col-sm-6 -->
                                    <div class="col-sm-6 col-xs-6 htlfndr-service">Incl. breakfast</div><!-- .col-sm-6 -->
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 col-xs-6 htlfndr-service">Private balcony</div><!-- .col-sm-6 -->
                                    <div class="col-sm-6 col-xs-6 htlfndr-service">Bathroom</div><!-- .col-sm-6 -->
                                </div><!-- .row -->
                            </div><!-- .htlfndr-services -->
                        </div><!-- .htlfndr-post-content -->
                    </div><!-- .htlfndr-content-block -->
                    <footer class="entry-footer">
                        <div class="htlfndr-left-side-footer">
                            <h5 class="entry-title">Awesome Suits</h5>
                            <div class="htlfndr-entry-rating-stars">
                                <i class="fa fa-star htlfndr-star-color"></i>
                                <i class="fa fa-star htlfndr-star-color"></i>
                                <i class="fa fa-star htlfndr-star-color"></i>
                                <i class="fa fa-star htlfndr-star-color"></i>
                                <i class="fa fa-star"></i>
                            </div><!-- .htlfndr-slide-rating-stars -->
                        </div><!-- .htlfndr-left-side-footer -->
                        <div class="htlfndr-right-side-footer">
                            <span class="htlfndr-cost">$ 250</span>
                            <span class="htlfndr-per-night">per night</span>
                        </div><!-- .htlfndr-right-side-footer -->
                    </footer>
                </article><!-- .htlfndr-top-destination-block -->
            </div><!-- .col-sm-4.col-xs-12 -->

            <div class="col-xs-12 col-sm-4 col-md-4 ">
                <article class="htlfndr-top-destination-block">
                    <div class="htlfndr-content-block">
                        <img src="http://placehold.it/360x295" alt="room-3" />
                        <div class="htlfndr-post-content">
                            <p class="htlfndr-the-excerpt">A modern hotel room in Star Hotel Nunc tempor erat in magna pulvinar fermentum. Pellentesque scelerisque at leo nec vestibulum. malesuada metus.
                                <a class="htlfndr-read-more-arrow" href="hotel-room-page.html"><i class="fa fa-angle-right"></i></a>
                            </p>
                            <div class="htlfndr-services">
                                <div class="row">
                                    <div class="col-sm-6 col-xs-6 htlfndr-service">Free WI-FI</div><!-- .col-sm-6 -->
                                    <div class="col-sm-6 col-xs-6 htlfndr-service">Incl. breakfast</div><!-- .col-sm-6 -->
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 col-xs-6 htlfndr-service">Private balcony</div><!-- .col-sm-6 -->
                                    <div class="col-sm-6 col-xs-6 htlfndr-service">Bathroom</div><!-- .col-sm-6 -->
                                </div><!-- .row -->
                            </div><!-- .htlfndr-services -->
                        </div><!-- .htlfndr-post-content -->
                    </div><!-- .htlfndr-content-block -->
                    <footer class="entry-footer">
                        <div class="htlfndr-left-side-footer">
                            <h5 class="entry-title">Single Room</h5>
                            <div class="htlfndr-entry-rating-stars">
                                <i class="fa fa-star htlfndr-star-color"></i>
                                <i class="fa fa-star htlfndr-star-color"></i>
                                <i class="fa fa-star htlfndr-star-color"></i>
                                <i class="fa fa-star htlfndr-star-color"></i>
                                <i class="fa fa-star"></i>
                            </div><!-- .htlfndr-slide-rating-stars -->
                        </div><!-- .htlfndr-left-side-footer -->
                        <div class="htlfndr-right-side-footer">
                            <span class="htlfndr-cost">$ 150</span>
                            <span class="htlfndr-per-night">per night</span>
                        </div><!-- .htlfndr-right-side-footer -->
                    </footer>
                </article><!-- .htlfndr-top-destination-block -->
            </div><!-- .col-sm-4.col-xs-12 -->
        </div><!-- .row -->
    </section><!-- .container.htlfndr-top-destinations -->

    <!-- Section called USP section -->
    <section class="container-fluid htlfndr-usp-section">
        <h2 class="htlfndr-section-title htlfndr-lined-title"><span>USP section</span></h2><!-- You need <span> and 'htlfndr-lined-title' class for both side line -->
            <div class="container">
                <div class="row">
                    <div class="col-sm-4 htlfndr-icon-box">
                        <img class="htlfndr-icon icon-drinks" src="images/icon-ups-drinks.png" height="100" width="100" alt="icon" />
                        <h5 class="htlfndr-section-subtitle">beverages included</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse interdum eleifend augue, quis rhoncus purus fermentum.</p>
                        <a href="#" class="htlfndr-read-more-button">read more</a>
                    </div><!-- .col-sm-4.htlfndr-icon-box -->

                    <div class="col-sm-4 htlfndr-icon-box">
                        <img class="htlfndr-icon icon-drinks" src="images/icon-ups-card.png" height="100" width="100" alt="icon" />
                        <h5 class="htlfndr-section-subtitle">best deals</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse interdum eleifend augue, quis rhoncus purus fermentum.</p>
                        <a href="#" class="htlfndr-read-more-button">read more</a>
                    </div><!-- .col-sm-4.htlfndr-icon-box -->

                    <div class="col-sm-4 htlfndr-icon-box">
                        <img class="htlfndr-icon icon-drinks" src="images/icon-ups-check.png" height="100" width="100" alt="icon" />
                        <h5 class="htlfndr-section-subtitle">guarantee</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse interdum eleifend augue, quis rhoncus purus fermentum.</p>
                        <a href="#" class="htlfndr-read-more-button">read more</a>
                    </div><!-- .col-sm-4.htlfndr-icon-box -->
                </div><!-- .row -->
            </div><!-- .container -->
        </section><!-- .container-fluid .htlfndr-usp-section -->

        <!-- Section with categories -->
        <section class="container-fluid htlfndr-categories-portfolio">
            <h2 class="htlfndr-section-title bigger-title">discover the world</h2>
            <div class="htlfndr-section-under-title-line"></div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4 col-xs-6">
                                <div class="htlfndr-category-box" onclick="void(0)"><!-- The "onclick" is using for Safari (IOS)
                                   that recognizes the 'div' element as a clickable element -->
                                   <img src="http://placehold.it/360x310" height="311" width="370" alt="category-img" />
                                   <div class="category-description">
                                    <div class="htlfndr-icon-flag-border"><i class="htlfndr-icon-flag flag-germany"></i></div><!-- .htlfndr-icon-flag-border -->
                                    <h2 class="subcategory-name">berlin</h2>
                                        <a href="#" class="htlfndr-category-permalink"></a><!-- This link will be displayed to "block" and
                                           will overlay to whole box by hovering on large desktop and will be a circle link on small devices -->
                                           <h5 class="category-name">germany</h5>
                                           <p class="category-properties"><span>374</span> properties</p>
                                       </div><!-- .category-description -->
                                   </div><!-- .htlfndr-category-box -->
                               </div><!-- .col-sm-4.col-xs-6 -->

                               <div class="col-sm-4 col-xs-6">
                                <div class="htlfndr-category-box" onclick="void(0)">
                                    <img src="http://placehold.it/360x310" height="311" width="370" alt="category-img" />
                                    <div class="category-description">
                                        <div class="htlfndr-icon-flag-border"><i class="htlfndr-icon-flag flag-britain"></i></div><!-- .htlfndr-icon-flag-border -->
                                        <h2 class="subcategory-name">london</h2>
                                        <a href="#" class="htlfndr-category-permalink"></a>
                                        <h5 class="category-name">britain</h5>
                                        <p class="category-properties"><span>185</span> properties</p>
                                    </div><!-- .category-description -->
                                </div><!-- .htlfndr-category-box -->
                            </div><!-- .col-sm-4.col-xs-6 -->

                            <div class="col-sm-4 col-xs-6">
                                <div class="htlfndr-category-box" onclick="void(0)">
                                    <img src="http://placehold.it/360x310" height="311" width="370" alt="category-img" />
                                    <div class="category-description">
                                        <div class="htlfndr-icon-flag-border"><i class="htlfndr-icon-flag flag-italy"></i></div><!-- .htlfndr-icon-flag-border -->
                                        <h2 class="subcategory-name">rom</h2>
                                        <a href="#" class="htlfndr-category-permalink"></a>
                                        <h5 class="category-name">italy</h5>
                                        <p class="category-properties"><span>98</span> properties</p>
                                    </div><!-- .category-description -->
                                </div><!-- .htlfndr-category-box -->
                            </div><!-- .col-sm-4.col-xs-6 -->

                            <div class="col-sm-4 col-xs-6">
                                <div class="htlfndr-category-box" onclick="void(0)">
                                    <img src="http://placehold.it/360x310" height="311" width="370" alt="category-img" />
                                    <div class="category-description">
                                        <div class="htlfndr-icon-flag-border"><i class="htlfndr-icon-flag flag-france"></i></div><!-- .htlfndr-icon-flag-border -->
                                        <h2 class="subcategory-name">paris</h2>
                                        <a href="#" class="htlfndr-category-permalink"></a>
                                        <h5 class="category-name">france</h5>
                                        <p class="category-properties"><span>281</span> properties</p>
                                    </div><!-- .category-description -->
                                </div><!-- .htlfndr-category-box -->
                            </div><!-- .col-sm-4.col-xs-6 -->

                            <div class="col-sm-4 col-xs-6">
                                <div class="htlfndr-category-box" onclick="void(0)">
                                    <img src="http://placehold.it/360x310" height="311" width="370" alt="category-img" />
                                    <div class="category-description">
                                        <div class="htlfndr-icon-flag-border"><i class="htlfndr-icon-flag flag-russia"></i></div><!-- .htlfndr-icon-flag-border -->
                                        <h2 class="subcategory-name">moscow</h2>
                                        <a href="#" class="htlfndr-category-permalink"></a>
                                        <h5 class="category-name">russia</h5>
                                        <p class="category-properties"><span>38</span> properties</p>
                                    </div><!-- .category-description -->
                                </div><!-- .htlfndr-category-box -->
                            </div><!-- .col-sm-4.col-xs-6 -->

                            <div class="col-sm-4 col-xs-6">
                                <div class="htlfndr-category-box" onclick="void(0)">
                                    <img src="http://placehold.it/360x310" height="311" width="370" alt="category-img" />
                                    <div class="category-description">
                                        <div class="htlfndr-icon-flag-border"><i class="htlfndr-icon-flag flag-japan"></i></div><!-- .htlfndr-icon-flag-border -->
                                        <h2 class="subcategory-name">tokio</h2>
                                        <a href="#" class="htlfndr-category-permalink"></a>
                                        <h5 class="category-name">japan</h5>
                                        <p class="category-properties"><span>318</span> properties</p>
                                    </div><!-- .category-description -->
                                </div><!-- .htlfndr-category-box -->
                            </div><!-- .col-sm-4.col-xs-6 -->
                        </div><!-- .row -->
                    </div>
                </section><!-- .container-fluid.htlfndr-categories-portfolio -->

                <!-- Section with visitors cards -->
                <section class="container-fluid htlfndr-visitors-cards">
                    <h2 class="htlfndr-section-title bigger-title">visitors experienced</h2>
                    <div class="htlfndr-section-under-title-line"></div>
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-4 col-xs-12 htlfndr-visitor-column">
                                <div class="htlfndr-visitor-card">
                                    <div class="visitor-avatar-side">
                                        <div class="visitor-avatar">
                                            <img src="http://placehold.it/90x90" height="90" width="90" alt="user avatar" />
                                        </div><!-- .visitor-avatar -->
                                    </div><!-- .visitor-avatar-side -->
                                    <div class="visitor-info-side">
                                        <h5 class="visitor-user-name">Sara Connor</h5>
                                        <h6 class="visitor-user-firm">Travel Magazine</h6>
                                        <p class="visitor-user-text">Nunc cursus libero purus ac congue arcu cursus ut sed vitae pulvinar massa idporta nequetiam nar...</p>
                                    </div><!-- .visitor-info-side -->
                                </div><!-- .htlfndr-visitor-card -->
                            </div><!-- .col-sm-4.col-xs-12.htlfndr-visitor-column -->

                            <div class="col-sm-4 col-xs-12 htlfndr-visitor-column">
                                <div class="htlfndr-visitor-card">
                                    <div class="visitor-avatar-side">
                                        <div class="visitor-avatar">
                                            <img src="http://placehold.it/90x90" height="90" width="90" alt="user avatar" />
                                        </div><!-- .visitor-avatar -->
                                    </div><!-- .visitor-avatar-side -->
                                    <div class="visitor-info-side">
                                        <h5 class="visitor-user-name">Mira Young</h5>
                                        <h6 class="visitor-user-firm">Hotel Manager</h6>
                                        <p class="visitor-user-text">Nunc cursus libero purus ac congue arcu cursus ut sed vitae pulvinar massa idporta nequetiam nar...</p>
                                    </div><!-- .visitor-info-side -->
                                </div><!-- .htlfndr-visitor-card -->
                            </div><!-- .col-sm-4.col-xs-12.htlfndr-visitor-column -->

                            <div class="col-sm-4 col-xs-12 htlfndr-visitor-column">
                                <div class="htlfndr-visitor-card">
                                    <div class="visitor-avatar-side">
                                        <div class="visitor-avatar">
                                            <img src="http://placehold.it/90x90" height="90" width="90" alt="user avatar" />
                                        </div><!-- .visitor-avatar -->
                                    </div><!-- .visitor-avatar-side -->
                                    <div class="visitor-info-side">
                                        <h5 class="visitor-user-name">John Smith</h5>
                                        <h6 class="visitor-user-firm">Hotel Manager</h6>
                                        <p class="visitor-user-text">Nunc cursus libero purus ac congue arcu cursus ut sed vitae pulvinar massa idporta nequetiam nar...</p>
                                    </div><!-- .visitor-info-side -->
                                </div><!-- .htlfndr-visitor-card -->
                            </div><!-- .col-sm-4.col-xs-12.htlfndr-visitor-column -->
                        </div><!-- .row -->
                    </div><!-- .container -->
                </section><!-- .container-fluid.htlfndr-visitors-cards -->
            </main>
            <!-- End of main content -->

            <?php get_footer(); ?>
