<?php if (isset($flash_message) && !empty($flash_message)) : ?>
<div class="container" style="margin-top: 50px;">
    <?php if(isset($flash_message['alert_type'])): ?>
        <div class="alert alert-<?php echo $flash_message['alert_type'] ?>">
            <?php if (isset($flash_message['message']) && !empty($flash_message['message'])) : ?>
                <div><?php echo $flash_message['message'] ?></div>
            <?php endif; ?>
        </div>
    <?php else : ?>
        <div class="alert alert-success">
            <?php if (isset($flash_message['message']) && !empty($flash_message['message'])) : ?>
                <div><?php echo $flash_message['message'] ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>