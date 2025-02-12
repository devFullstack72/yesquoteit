<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="robots" content="index, follow">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title><?php esc_attr_e( "Preview", "yeemail" ) ?></title>
  <link rel="stylesheet" href="<?php echo esc_url( YEEMAIL_PLUGIN_URL."backend/css/preview.css" ) ?>">
</head>
<body>
<?php 
  $post_id = sanitize_text_field( $_GET["id"] );
  $url = wp_nonce_url(get_home_url() ."/?email_preview=preview_email_inner&id=". $post_id ,"yeemail");
?>
<div class="container">
  <div class="action">
      <h3><?php esc_attr_e( "Preview your emails", "yeemail") ?></h3>
  </div>
  <div class="col-sm-8 ng-star-inserted">
      <div class="esdev-desktop-device">
        <div class="esdev-email-window-panel">
          <span class="es-icon-user-account esdev-email-user-avatar"></span>
          <div class="esdev-email-subject">
            <div class="text-overflow subject">
              &nbsp;
            </div>
            <div class="text-overflow preheader">
              &nbsp;
            </div>
          </div>
        </div>
        <div class="esdev-desktop-device-screen">
          <div class="content-hidden">
            <div class="loader-z"></div>
          </div>
          <iframe id="iframe1" frameborder="0" width="100%" height="642" src="<?php echo esc_url($url) ?>">> </iframe>
        </div>
      </div>
    </div>
    <div class="col-sm-4 esdev-no-padding-left ng-star-inserted">
      <div class="esdev-mobile-device center-block">
        <div class="esdev-mobile-device-screen">
          <div class="content-hidden">
            <div class="loader-z"></div>
          </div>
          <img src="/cabinet/assets/editor/assets/img/mobile-view-top-bar.png" alt="">
          <iframe id="iframe2" frameborder="0" width="100%" height="459" src="<?php echo esc_url($url) ?>">> </iframe>
          <img src="/cabinet/assets/editor/assets/img/mobile-view-bottom-bar.png" alt="" class="esdev-mail-bottom-bar">
        </div>
      </div>
    </div>
</div>
</body>
</html>
