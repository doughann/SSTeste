<!DOCTYPE html>
<?php
    if (!defined('JOURNAL_INSTALLED')) {
        echo '
            <h3>Journal Installation Error</h3>
            <p>Make sure you have uploaded all Journal files to your server and successfully replaced <b>system/engine/front.php</b> file.</p>
            <p>You can find more information <a href="http://docs.digital-atelier.com/opencart/journal/#/settings/install" target="_blank">here</a>.</p>
        ';
        exit();
    }
?>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="<?php echo $this->journal2->html_classes->getAll(); ?>" data-j2v="<?php echo JOURNAL_VERSION; ?>">
<head>
<meta charset="UTF-8" />
<?php if ($this->journal2->settings->get('responsive_design')): ?>
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
<?php endif; ?>
<meta name="format-detection" content="telephone=no">
<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/><![endif]-->
<!--[if lt IE 9]><script src="//ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script><![endif]-->
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($meta_title = $this->journal2->settings->get('blog_meta_title')): ?>
<meta name="title" content="<?php echo $meta_title; ?>" />
<?php endif; ?>
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<meta property="og:title" content="<?php echo $this->journal2->settings->get('fb_meta_title'); ?>" />
<meta property="og:description" content="<?php echo $this->journal2->settings->get('fb_meta_description'); ?>" />
<meta property="og:url" content="<?php echo $this->journal2->settings->get('fb_meta_url'); ?>" />
<meta property="og:image" content="<?php echo $this->journal2->settings->get('fb_meta_image'); ?>" />
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php if ($blog_feed_url = $this->journal2->settings->get('blog_blog_feed_url')): ?>
<link rel="alternate" type="application/rss+xml" title="RSS" href="<?php echo $blog_feed_url; ?>" />
<?php endif; ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>

			<script src="catalog/view/javascript/comboproducts.js" type="text/javascript"></script>
			<style type="text/css">
				.combo-section {
					width: 100%;
					border-top: 1px solid #CCC;
				}
				
				.combo-section .combo-set {
					padding: 2px;
					width: 100%;
					min-height: 180px;
				}
				
				.combo-section .combo-set .combo-item {
					display: block;
					line-height: 14px;
					font-weight: bold;
					min-height: 14px;
					float: left;
					width: 14%;
				}
				
				.combo-item-img {	
					padding-right: 5px;
					padding-left: 5px;
					text-align: center;
				}
				
				.combo-item-name,.combo-item-price {
					text-align: center;
					font-size: smaller;
				}
				
				.combo-action {
					float:left;
					width: 25%;
				}
				
				.combo-plus, .combo-save {
					float: left;
					font-weight: bold;
				}
				
				.combo-plus {
					line-height: 100px
				}
				
				.price_discount {
					color: #900;
				}
				
				.btn-combo {
					text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.25);
					border: 1px solid #CCC;
					border-radius: 4px;
					box-shadow: 0px 1px 0px rgba(255, 255, 255, 0.2) inset, 0px 1px 2px rgba(0, 0, 0, 0.05);
				}
				
				.btn-combo-wishlist {
					background: linear-gradient(to bottom, #F7DFA5, #F0C14B) repeat scroll 0% 0% transparent;
				}
				
				.btn-combo-cart {
					color: #FFF;
					background: linear-gradient(to bottom, #23A1D1, #1F90BB) repeat-x scroll 0% 0% transparent;
				}
			</style>
		
<?php foreach ($styles as $style) { ?>
<?php $this->journal2->minifier->addStyle($style['href']); ?>
<?php } ?>
<?php foreach ($this->journal2->google_fonts->getFonts() as $font): ?>
<link rel="stylesheet" href="<?php echo $font; ?>"/>
<?php endforeach; ?>
<?php $this->journal2->minifier->addScript("catalog/view/javascript/jquery/ui/i18n/jquery.ui.datepicker-pt-BR.js"); ?>
<?php foreach ($scripts as $script) { ?>
<?php $this->journal2->minifier->addScript($script, 'header'); ?>
<?php } ?>
<?php
    $this->journal2->minifier->addStyle('catalog/view/theme/journal2/css/hint.min.css');
    $this->journal2->minifier->addStyle('catalog/view/theme/journal2/css/journal.css');
    $this->journal2->minifier->addStyle('catalog/view/theme/journal2/css/features.css');
    $this->journal2->minifier->addStyle('catalog/view/theme/journal2/css/header.css');
    $this->journal2->minifier->addStyle('catalog/view/theme/journal2/css/module.css');
    $this->journal2->minifier->addStyle('catalog/view/theme/journal2/css/pages.css');
    $this->journal2->minifier->addStyle('catalog/view/theme/journal2/css/account.css');
    $this->journal2->minifier->addStyle('catalog/view/theme/journal2/css/blog-manager.css');
    $this->journal2->minifier->addStyle('catalog/view/theme/journal2/css/side-column.css');
    $this->journal2->minifier->addStyle('catalog/view/theme/journal2/css/product.css');
    $this->journal2->minifier->addStyle('catalog/view/theme/journal2/css/category.css');
    $this->journal2->minifier->addStyle('catalog/view/theme/journal2/css/footer.css');
    $this->journal2->minifier->addStyle('catalog/view/theme/journal2/css/icons.css');
    if ($this->journal2->settings->get('responsive_design')) {
        $this->journal2->minifier->addStyle('catalog/view/theme/journal2/css/responsive.css');
    }
?>
<?php echo $this->journal2->minifier->css(); ?>
<?php if ($this->journal2->cache->getDeveloperMode() || !$this->journal2->minifier->getMinifyCss()): ?>
<link rel="stylesheet" href="index.php?route=journal2/assets/css&amp;j2v=<?php echo JOURNAL_VERSION; ?>" />
<?php endif; ?>
<?php $this->journal2->minifier->addScript('catalog/view/theme/journal2/js/journal.js', 'header'); ?>
<?php echo $this->journal2->minifier->js('header'); ?>
<!--[if (gte IE 6)&(lte IE 8)]><script src="catalog/view/theme/journal2/lib/selectivizr/selectivizr.min.js"></script><![endif]-->
<?php if (isset($stores)): /* v1541 compatibility */ ?>
<?php if ($stores) { ?>
<script type="text/javascript"><!--
$(document).ready(function() {
<?php foreach ($stores as $store) { ?>
$('body').prepend('<iframe src="<?php echo $store; ?>" style="display: none;"></iframe>');
<?php } ?>
});
//--></script>
<?php } ?>
<?php endif; /* end v1541 compatibility */ ?>
<?php echo $google_analytics; ?>
<script>
    <?php if ($this->journal2->settings->get('show_countdown', 'never') !== 'never' || $this->journal2->settings->get('show_countdown_product_page', 'on') == 'on'): ?>
    Journal.COUNTDOWN = {
        DAYS    : "<?php echo $this->journal2->settings->get('countdown_days', 'Days'); ?>",
        HOURS   : "<?php echo $this->journal2->settings->get('countdown_hours', 'Hours'); ?>",
        MINUTES : "<?php echo $this->journal2->settings->get('countdown_min', 'Min'); ?>",
        SECONDS : "<?php echo $this->journal2->settings->get('countdown_sec', 'Sec'); ?>"
    };
    <?php endif; ?>
    Journal.NOTIFICATION_BUTTONS = '<?php echo $this->journal2->settings->get('notification_buttons'); ?>';
</script>

			<!-- Facebook Conversion Pixel - Adds to Cart -->
				<script>(function() {
				  var _fbq = window._fbq || (window._fbq = []);
				  if (!_fbq.loaded) {
					var fbds = document.createElement('script');
					fbds.async = true;
					fbds.src = '//connect.facebook.net/en_US/fbds.js';
					var s = document.getElementsByTagName('script')[0];
					s.parentNode.insertBefore(fbds, s);
					_fbq.loaded = true;
				  }
				})();
				window._fbq = window._fbq || [];
				</script>
			
			<?php if (!empty($custom_audience_pixel)) { ?>
				<!-- Facebook Custom Audience Pixel -->
				<script>(function() {
				  var _fbq = window._fbq || (window._fbq = []);
				  if (!_fbq.loaded) {
					var fbds = document.createElement('script');
					fbds.async = true;
					fbds.src = '//connect.facebook.net/en_US/fbds.js';
					var s = document.getElementsByTagName('script')[0];
					s.parentNode.insertBefore(fbds, s);
					_fbq.loaded = true;
				  }
				  _fbq.push(['addPixelId', '<?php echo $custom_audience_pixel; ?>']);
				})();
				window._fbq = window._fbq || [];
				window._fbq.push(['track', 'PixelInitialized', {}]);
				</script>
				<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=<?php echo $custom_audience_pixel; ?>&amp;ev=PixelInitialized" /></noscript>
			<?php } ?>
            <?php if (!empty($pixels)) { ?>				
				<!-- Facebook Conversion Code -->
					<script>(function() {
					var _fbq = window._fbq || (window._fbq = []);
					if (!_fbq.loaded) {
					var fbds = document.createElement('script');
					fbds.async = true;
					fbds.src = '//connect.facebook.net/en_US/fbds.js';
					var s = document.getElementsByTagName('script')[0];
					s.parentNode.insertBefore(fbds, s);
					_fbq.loaded = true;
					}
					})();
					window._fbq = window._fbq || [];
					<?php foreach ($pixels as $pixel) { ?>
						window._fbq.push(['track', '<?php echo $pixel['pixel_id']; ?>', {'value':'<?php echo $pixel['value']; ?>','currency':'<?php echo $fb_currency; ?>'}]);
					<?php } ?>
					</script>
					<?php foreach ($pixels as $pixel) { ?>
						<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=<?php echo $pixel['pixel_id']; ?>&amp;cd[value]=<?php echo $pixel['value']; ?>&amp;cd[currency]=<?php echo $fb_currency; ?>&amp;noscript=1" /></noscript>			
					<?php } ?>
			<?php }	?>
		
</head>
<body>
<!--[if lt IE 9]>
<div class="old-browser"><?php echo $this->journal2->settings->get('old_browser_message', 'You are using an old browser. Please <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">upgrade to a newer version</a> or <a href="http://browsehappy.com/">try a different browser</a>.'); ?></div>
<![endif]-->
<?php if ($this->journal2->settings->get('config_header_modules')):  ?>
<?php echo $this->journal2->settings->get('config_header_modules'); ?>
<?php endif; ?>
<?php if ($this->journal2->config->admin_warnings): ?>
<div class="admin-warning"><?php echo $this->journal2->config->admin_warnings; ?></div>
<?php endif; ?>
<?php
    $header_type = $this->journal2->settings->get('header_type', 'default');
    if ($header_type === 'center') {
        if (!$this->journal2->settings->get('config_secondary_menu')) {
            $header_type = 'center.nosecond';
        } else {
            if (!$currency && !$language) {
                $header_type = 'center.nolang-nocurr';
            } else if (!$currency) {
                $header_type = 'center.nocurr';
            } else if (!$language) {
                $header_type = 'center.nolang';
            }
        }
    }

    if ($header_type === 'mega') {
        if (!$this->journal2->settings->get('config_secondary_menu')) {
            $header_type = 'mega.nosecond';
        } else {
            if (!$currency && !$language) {
                $header_type = 'mega.nolang-nocurr';
            } else if (!$currency) {
                $header_type = 'mega.nocurr';
            } else if (!$language) {
                $header_type = 'mega.nolang';
            }
        }
    }

    if ($header_type === 'default' || $header_type === 'extended') {
        $no_cart = $this->journal2->settings->get('catalog_header_cart', 'block') === 'none';
        $no_search = $this->journal2->settings->get('catalog_header_search', 'block') === 'none';
        if ($no_cart && $no_search) {
            $header_type = $header_type . '.nocart-nosearch';
        } else if ($no_cart) {
            $header_type = $header_type . '.nocart';
        } else if ($no_search) {
            $header_type = $header_type . '.nosearch';
        }
    }
    if (class_exists('VQMod')) {
        global $vqmod;
        if ($vqmod !== null) {
            require $vqmod->modCheck(DIR_TEMPLATE . $this->config->get('config_template') . "/template/journal2/headers/{$header_type}.tpl");
        } else {
            require VQMod::modCheck(DIR_TEMPLATE . $this->config->get('config_template') . "/template/journal2/headers/{$header_type}.tpl");
        }
    } else {
        require DIR_TEMPLATE . $this->config->get('config_template') . "/template/journal2/headers/{$header_type}.tpl";
    }
?>
<?php if ($this->journal2->settings->get('config_top_modules')): ?>
<div id="top-modules">
   <?php echo $this->journal2->settings->get('config_top_modules'); ?>
</div>
<?php endif; ?>

<div class="extended-container">
<div id="container" class="j-container">

<?php if(isset($error)): /* v156 compatibility */ ?>
<?php if ($error) { ?>
    <div class="warning"><?php echo $error ?><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>
<?php } ?>
<?php endif; /* end v156 compatibility */ ?>
<div id="notification"></div>