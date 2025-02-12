<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
		<meta content="width=device-width, initial-scale=1.0" name="viewport">
		<title><?php echo get_bloginfo( 'name', 'display' ); ?></title>
        <link href="<?php echo esc_url(Yeemail_Settings_Builder_Backend::google_font("link")) ?>" rel="stylesheet"/>   
	</head>
    <body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" data-yeemail-check="true" class="body" style="padding:0 !important; margin:0 !important; display:block !important; min-width:100% !important; width:100% !important; -webkit-text-size-adjust:none;">
    <style>
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            background: #f1f1f1;
            font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;
        }
        p {
            font-size: 13px;
            line-height: 1.5;
            margin: 0;
        }
        td {
            vertical-align: top;
        }
        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }
        /* What it does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }
        table.row table, .builder-content-email table{
            width: 100% !important;
        }
        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
            padding: 0;
        }
        /* What it does: Fixes webkit padding issue. */
        table {
            border-spacing: 0 !important;;
            margin: 0 auto !important;
        }
        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
            -ms-interpolation-mode:bicubic;
            vertical-align: middle;
        }
        /* What it does: Prevents Windows 10 Mail from underlining links despite inline CSS. Styles for underlined links should be inline. */
        a {
            text-decoration: none;
        }
        /* What it does: A work-around for email clients meddling in triggered links. */
        *[x-apple-data-detectors],  /* iOS */
        .unstyle-auto-detected-links *,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }
        /* What it does: Prevents Gmail from displaying a download button on large, non-linked images. */
        .a6S {
            display: none !important;
            opacity: 0.01 !important;
        }
        /* What it does: Prevents Gmail from changing the text color in conversation threads. */
        .im {
            color: inherit !important;
        }
        /* If the above doesn't work, add a .g-img class to any image in question. */
        img.g-img + div {
            display: none !important;
        }
        /* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
        /* Create one of these media queries for each additional viewport size you'd like to fix */
        /* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
        @media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
            u ~ div .email-container {
                min-width: 320px !important;
            }
        }
        /* iPhone 6, 6S, 7, 8, and X */
        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
            u ~ div .email-container {
                min-width: 375px !important;
            }
        }
        /* iPhone 6+, 7+, and 8+ */
        @media only screen and (min-device-width: 414px) {
            u ~ div .email-container {
                min-width: 414px !important;
            }
        }
        @media only screen and (max-width: 600px) {
            table {
                width: 100% !important;
                max-width: 600px !important;
            }
            img {
                height: auto !important;
            }
            .yeemail-responsive > table.row > tbody > tr > td{
                width: 100% !important;
                float: left;
                margin-bottom: 5px;
            }
            .builder-elements-content-img{
                text-align: center !important;
            }
            #addresses td {
                width: 100%;
                display: block;
                padding-bottom: 15px !important;
            }
        }
        img {
            max-width: 100%;
        }
        .links a {
            display: inline-block;
            width: 100%;
        }
        .yeemail_button {
            display: inline-block;
        }
        .builder-elements-content-hold .builder-content{
            text-align: center;
            font-size: 18px;
            background: #666;
            padding: 50px 15px;
            color: #fff;
        }
        p, div {
            font-size:14px;
            line-height: 1.5;
            color: #666666;
        }
        .table-data {
            border: 1px solid rgb(266, 266, 266);
        }
        .table-data tr{
            padding-left: 15px;
        }
        .woocommerce-style h2 {
            font-size: 18px;
            font-weight: bold;
            line-height: 130%;
        }
        address {
            -webkit-text-size-adjust: 100%;
            padding: 12px;
            border: 1px solid #e5e5e5;
        }
        .yeemail-table {
            border: 1px solid #e5e5e5;
        }
        .yeemail-table th,
        .yeemail-table td {
            border: 1px solid #e5e5e5;
            vertical-align: middle;
            padding: 12px;
        }
        .yeemail_hook {
            text-align: center;
            border: 1px dotted red;
            font-weight: bold;
            padding: 5px;
            margin: 10px 0;
            overflow: hidden;
        }
        .yeemail_button, .yeemail-menu {
            text-decoration: none;
        }
        @media only screen and (max-width: 801px) {
            .yeemail_show_desktop {
                display:none !important;
                mso-hide: all;
            }
            .yeemail_hook {
                font-size: 12px;
            }
        }
        @media only screen and (min-width: 801px) {
            .yeemail_show_mobile {
                display:none !important;
                mso-hide: all;
            }
        }
    </style>