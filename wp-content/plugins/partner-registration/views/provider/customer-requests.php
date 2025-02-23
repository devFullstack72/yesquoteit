<div class="container hotel-finder-page">
    <h2 class="htlfndr-section-title bigger-title">Customer Requests</h2>
    <div class="htlfndr-section-under-title-line"></div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Lead</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($customer_quotes)) : ?>
                <?php foreach ($customer_quotes as $customer_quote) : ?>
                    <tr>
                        <td>
                        <a href="<?php echo esc_url(get_permalink($customer_quote->lead_id)); ?>" target="_blank">
                            <?php echo esc_html($customer_quote->lead_name); ?></td>
                        <td><?php echo esc_html($customer_quote->name); ?></td>
                        <td><?php echo esc_html($customer_quote->email); ?></td>
                        <td><?php echo esc_html($customer_quote->phone); ?></td>
                        <td><?php echo esc_html(date('d.m.Y H:i a', strtotime($customer_quote->created_at))); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5" class="text-center">No customer requests found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>