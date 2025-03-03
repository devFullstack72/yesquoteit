<?php
/**
 * Template for displaying single posts of the 'lead_generation' custom post type
 *
 * @package Neve
 */

$container_class = apply_filters( 'neve_container_class_filter', 'container', 'single-lead_generation' );

get_header();

?>
    <div class="<?php echo esc_attr( $container_class ); ?> single-lead-generation-container">
        <div class="row">
            <?php do_action( 'neve_do_sidebar', 'single-lead_generation', 'left' ); ?>
            <article id="post-<?php echo esc_attr( get_the_ID() ); ?>"
                    class="<?php echo esc_attr( join( ' ', get_post_class( 'nv-single-post-wrap col' ) ) ); ?>">
                <?php
                /**
                 * Executes actions before the post content.
                 *
                 * @since 2.3.8
                 */
                do_action( 'neve_before_post_content' );

                if ( have_posts() ) {
                    while ( have_posts() ) {
                        the_post();

                        // Display the featured image
                        if ( has_post_thumbnail() ) :
                            echo '<div class="lead-generation-featured-image">';
                            the_post_thumbnail( 'large' );  // Adjust image size as needed
                            echo '</div>';
                        endif;

                        // Display the title of the post
                        echo '<h1 class="lead-generation-title">' . get_the_title() . '</h1>';

                        // Display the content of the post
                        echo '<div class="lead-generation-content">';
                        the_content();  // This will display the content you added in the editor
                        echo '</div>';

                        // Display custom fields (if any)
                        $custom_field_value = get_post_meta( get_the_ID(), 'your_custom_field', true ); // Replace 'your_custom_field' with your actual custom field name
                        if ( ! empty( $custom_field_value ) ) :
                            echo '<div class="lead-generation-custom-field">';
                            echo '<strong>Custom Field:</strong> ' . esc_html( $custom_field_value );
                            echo '</div>';
                        endif;
                    }
                } else {
                    get_template_part( 'template-parts/content', 'none' );
                }

                /**
                 * Executes actions after the post content.
                 *
                 * @since 2.3.8
                 */
                do_action( 'neve_after_post_content' );
                ?>
            </article>
            <?php do_action( 'neve_do_sidebar', 'single-lead_generation', 'right' ); ?>
        </div>
    </div>
<?php
get_footer();
