<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="wrap">
    <h1>Lead Sent Provides</h1>
    <table class="widefat fixed" cellspacing="0">
        <thead>
            <tr>
                <th>Lead</th>
                <th>Category</th>
                <th>Location</th>
                <th>Partner</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($lead_sent_providers_data)) : ?>
                <?php foreach ($lead_sent_providers_data as $row) :
                    $latitude = '';
                    $longitude = '';
                    $full_address = '';
                    if (!empty($row->quote_data)) {
                        $quote_data = json_decode($row->quote_data, true);
                        $full_address = $quote_data['google_places_form_address']['value'] ?? '';
                        $latitude = $quote_data['google_places_form_latitude']['value'] ?? '';
                        $longitude = $quote_data['google_places_form_longitude']['value'] ?? '';
                    }
                    $lead_link = get_permalink($row->lead_id);
                    ?>
                    <tr>
                        <td><a href="<?php echo esc_url($lead_link); ?>" target="_blank"><?php echo esc_html($row->lead_name); ?></a></td>
                        <td><?php echo esc_html($row->category_names); ?></td>
                        <td>
                            <div>Address: <?php echo esc_html($full_address); ?></div>
                            <div>Latitude: <?php echo esc_html($latitude); ?></div>
                            <div>Longitude: <?php echo esc_html($longitude); ?></div>
                        </td>
                        <td><?php echo esc_html($row->provider_names); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr><td colspan="4">No records found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
