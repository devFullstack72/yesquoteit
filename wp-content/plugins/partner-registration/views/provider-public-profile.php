<?php
/* Template for public provider profile */

global $wpdb;

// Get provider ID from URL
$provider_id = get_query_var('provider_id');

if (!$provider_id) {
    wp_die('Invalid Provider ID');
}

// Fetch provider details from database
$provider = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}service_partners WHERE id = %d",
    $provider_id
));

if (!$provider) {
    wp_die('Provider not found');
}


$wp_posts_table = $wpdb->prefix . 'posts';
$lead_partners_table = $wpdb->prefix . 'lead_partners';

// Fetch all lead posts (post type: 'lead_generation')
$all_leads = $wpdb->get_results(
    "SELECT ID, post_title, post_content FROM {$wp_posts_table} WHERE post_type = 'lead_generation' AND post_status = 'publish' ORDER BY post_title ASC"
);

// Fetch assigned leads for this partner
$assigned_leads = $wpdb->get_col(
    $wpdb->prepare(
        "SELECT lead_id FROM {$lead_partners_table} WHERE partner_id = %d",
        $provider_id
    )
);

// If there are assigned leads, filter them from all leads
$assigned_lead_posts = [];
foreach ($all_leads as $lead) {
    if (in_array($lead->ID, $assigned_leads)) {
        $assigned_lead_posts[] = $lead; // Store the assigned lead
    }
}

$query = $wpdb->prepare(
    "SELECT COUNT(id) AS total_reviews, COALESCE(AVG(rating), 0) AS average_rating 
     FROM {$wpdb->prefix}yqit_partner_reviews 
     WHERE partner_id = %d", 
    $provider_id
);

$result = $wpdb->get_row($query);

$total_reviews = $result->total_reviews;
$average_rating = round($result->average_rating, 1); // Round to 1 decimal place

function renderStars($average_rating, $total_reviews) {
    $full_stars = floor($average_rating); // Full stars
    $half_star = ($average_rating - $full_stars) >= 0.5 ? 1 : 0; // Half star
    $empty_stars = 5 - ($full_stars + $half_star); // Remaining empty stars

    $stars_html = '<div class="provider-rating">';
    
    // Full stars
    for ($i = 0; $i < $full_stars; $i++) {
        $stars_html .= '<i class="fa fa-star star-rating-color"></i>';
    }
    // Half star
    if ($half_star) {
        $stars_html .= '<i class="fa fa-star-half-o star-rating-color"></i>';
    }
    // Empty stars
    for ($i = 0; $i < $empty_stars; $i++) {
        $stars_html .= '<i class="fa fa-star-o star-rating-color"></i>';
    }

    // Display total reviews count
    $stars_html .= '<span class="total-reviews"> (' . $total_reviews . ' Reviews)</span>';
    $stars_html .= '</div>';

    return $stars_html;
}

// Set Content-Type
header('Content-Type: text/html; charset=UTF-8');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yes Quote It</title>
    <link rel="icon" href="http://dev.wisencode.com/yesquoteit/wp-content/uploads/2025/02/cropped-webaddress-32x32.jpg" sizes="32x32" />
    <link rel="icon" href="http://dev.wisencode.com/yesquoteit/wp-content/uploads/2025/02/cropped-webaddress-192x192.jpg" sizes="192x192" />
    <link rel="apple-touch-icon" href="http://dev.wisencode.com/yesquoteit/wp-content/uploads/2025/02/cropped-webaddress-180x180.jpg" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1000px;
            margin: 20px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            padding-bottom: 20px;
        }
        .profile-header img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
        .profile-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .tabs {
            display: flex;
            margin-top: 20px;
            border-bottom: 2px solid #ddd;
        }
        .tab {
            flex: 1;
            text-align: center;
            padding: 15px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }
        .tab.active {
            background: #6e7e8f;
            color: white;
        }
        .tab:hover {
            background:rgb(151, 166, 182);
            color: white;
        }
        .tab-content {
            display: none;
            padding: 20px;
        }
        .tab-content.active {
            display: block;
        }

        .wpcf7 form {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        .wpcf7 input[type="text"], 
        .wpcf7 input[type="email"], 
        .wpcf7 textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .wpcf7 input[type="submit"] {
            background-color: #6e7e8f;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .wpcf7 input[type="submit"]:hover {
            background-color: #5a677b;
        }

        .services ol {
            list-style-type: decimal; /* Ensure numbers appear */
            padding-left: 20px; /* Proper alignment for numbers */
            margin: 0;
        }

        .services ol li {
            padding: 3px 5px; /* Reduce padding for compactness */
            border-bottom: 1px solid #ddd; /* Light separator */
            font-size: 14px; /* Keep font size consistent */
        }

        .services ol li:last-child {
            border-bottom: none; /* Remove border from the last item */
        }

        .services h3 {
            margin: 5px 0; /* Reduce spacing for a compact look */
            font-size: 14px; /* Ensure font size stays 14px */
            font-weight: normal; /* Optional: Keep text lightweight */
        }
        
        .provider-rating {
            display: flex;
            align-items: center;
            font-size: 16px;
            gap: 5px;
        }

        .stars i {
            font-size: 18px;
        }

        .star-rating-color {
            color: #ffa100; /* Gold color for stars */
        }

        .provider-rating-container {
            margin-top: 10px;
        }

        .total-reviews {
            font-size: 13px;
        }

    </style>
    <?php wp_head(); ?>
</head>
<body>
    <div class="container">
        <div class="tabs">
            <div class="tab active" data-tab="tab1">Business Information</div>
            <div class="tab" data-tab="tab2">Services</div>
            <div class="tab" data-tab="tab3">Contact</div>
        </div>
        <div class="tab-content active" id="tab1">
            <?php include plugin_dir_path(__FILE__) . '../views/provider-public-profile/base-info.php'; ?>
            <p><strong>Country:</strong> <?php echo esc_html($provider->country); ?></p>
            <p><strong>Address:</strong> <?php echo esc_html($provider->address); ?></p>
            <p><strong>Phone:</strong> <?php echo esc_html($provider->phone); ?></p>
            <p><strong>Website:</strong> <a href="<?php echo esc_url($provider->website_url); ?>" target="_blank"><?php echo esc_html($provider->website_url); ?></a></p>
        </div>
        <div class="tab-content services" id="tab2">
            <?php include plugin_dir_path(__FILE__) . '../views/provider-public-profile/base-info.php'; ?>
            <?php if ($assigned_lead_posts): ?>
                <ol>
                    <?php foreach ($assigned_lead_posts as $lead): ?>
                        <li>
                            <h3><?php echo esc_html($lead->post_title); ?></h3>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php else: ?>
                <p>No services assigned to this partner.</p>
            <?php endif; ?>
        </div>
        <div class="tab-content" id="tab3">
            <?php include plugin_dir_path(__FILE__) . '../views/provider-public-profile/base-info.php'; ?>
            <p>
                <?php echo do_shortcode(get_option('partner_contact_form_shortcode')); ?>
            </p>
        </div>
    </div>
    <script src="<?php echo esc_url(get_template_directory_uri()); ?>/js/jquery-1.11.3.min.js"></script>
    <?php wp_footer(); ?>
    <script>

        document.addEventListener("DOMContentLoaded", function() {
            let inputElement = document.querySelector('input[name="is_partner_contact_form"]');

            if (inputElement) { // Check if the element exists
                inputElement.value = '<?php echo esc_js($provider->email); ?>';
            }

            document.querySelectorAll('.tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById(this.getAttribute('data-tab')).classList.add('active');
                });
            });
            
        });
    </script>
</body>
</html>
