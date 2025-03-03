<?php

if (!defined('ABSPATH')) {
    exit;
}

class Partner_CF7_Fields_Labels {
    public function __construct() {
        add_action('admin_menu', [$this, 'custom_cf7_fields_labels_menu']);
    }

    public function custom_cf7_fields_labels_menu() {
        add_menu_page(
            'CF7 Fields Labels',      // Page title
            'CF7 Fields Labels',      // Menu title
            'manage_options',         // Capability
            'cf7-fields-labels',      // Menu slug
            [$this, 'cf7_fields_labels_page'], // Callback function
            'dashicons-edit',         // Icon
            20                        // Position
        );
    }

    public function cf7_fields_labels_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cf7_fields_labels';

        // Handle new field insertion
        if (isset($_POST['add_new_field'])) {
            $field_name = sanitize_text_field($_POST['field_id']);
            $field_label = sanitize_text_field($_POST['field_label']);

            $wpdb->insert(
                $table_name,
                ['field_name' => $field_name, 'field_label' => $field_label],
                ['%s', '%s']
            );

            echo '<div class="updated"><p>New field added successfully!</p></div>';
        }

        // Handle form submission
        if (isset($_POST['update_field_label'])) {
            $id = intval($_POST['field_id']);
            $field_label = sanitize_text_field($_POST['field_label']);

            $wpdb->update(
                $table_name,
                ['field_label' => $field_label],
                ['id' => $id],
                ['%s'],
                ['%d']
            );
            echo '<div class="updated"><p>Field label updated successfully!</p></div>';
        }

        // Fetch records
        $results = $wpdb->get_results("SELECT * FROM $table_name");
        ?>
        <div class="wrap">
            <h2>CF7 Fields Labels</h2>

            <!-- Add New Field Form -->
            <h3>Add New Field</h3>
            <form method="POST">
                <table class="form-table">
                    <tr>
                        <th>Field Name</th>
                        <td><input type="text" name="field_id" required></td>
                    </tr>
                    <tr>
                        <th>Field Label</th>
                        <td><input type="text" name="field_label" required></td>
                    </tr>
                </table>
                <button type="submit" name="add_new_field" class="button button-primary">Add Field</button>
            </form>

            <!-- Display Existing Fields -->
            <h3>Existing Fields</h3>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Field Name</th>
                        <th>Field Label</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row) { ?>
                        <tr>
                            <td><?php echo esc_html($row->field_name); ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="field_id" value="<?php echo esc_attr($row->id); ?>">
                                    <input style="width:100%;" type="text" name="field_label" value="<?php echo esc_attr($row->field_label); ?>">
                            </td>
                            <td>
                                    <button type="submit" name="update_field_label" class="button button-primary">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}
