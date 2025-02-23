<?php if (isset($flash_message) && !empty($flash_message)) : ?>
<div class="container" style="margin-top: 50px;">
    <div class="alert alert-success">
        <?php if (isset($flash_message['message']) && !empty($flash_message['message'])) : ?>
            <div><?php echo $flash_message['message'] ?></div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>