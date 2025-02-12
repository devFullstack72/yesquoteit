<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_action("yeemail_builder_tab_block_addons","yeemail_builder_block__main_templates",40);
function yeemail_builder_block__main_templates(){
    ?>
    <div class="builder__widget--inner">
        <div class="builder__widget_tab builder__widget_templates">
            <div class="builder__widget_tab_title"><span class="builder__widget_tab_title_t"><?php esc_attr_e( "Templates", "yeemail") ?></span><span class="builder__widget_tab_title_icon dashicons dashicons-arrow-down-alt2"></span><span class="builder__widget_tab_title_icon dashicons dashicons-arrow-up-alt2"></span></div>
            <ul class="builder-row-templates momongaPresets_data">
                <?php do_action( "yeemail_builder_tab_block_template" ) ?>
            </ul>
        </div>
    </div>
<?php
}