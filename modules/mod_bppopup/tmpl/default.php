<?php

/**
 * @package     ${package}
 *
 * @copyright   Copyright (C) ${build.year} ${copyrights},  All rights reserved.
 * @license     ${license.name}; see ${license.url}
 * @author      ${author.name}
 */

use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/**
 * @var string           $mode             Iframe or image mode.
 * @var string           $image            Popup image url.
 * @var string           $url              Iframe popup url.
 * @var string           $page             Page item_id.
 * @var string           $scroll_event     Name of the scroll event.
 * @var string           $time_mode        Mode of the time calculation.
 * @var string           $include_lightbox Include Magnific Popup assets?
 * @var string           $mode             Iframe mode.
 * @var string           $html             HTML for inline mode.
 * @var string           $text             HTML code from a text field mode.
 * @var string           $target           Target window for clicked anchor.
 * @var string           $location         Popup location.
 * @var int              $html_max_width   Maximum width for HTML popup.
 * @var int              $html_min_height  Minimum height for HTML popup.
 * @var int              $cookie_time      Time to live for a cookie.
 * @var int              $scroll_length    Scroll length (after/before reaching the distance popup will occur)
 * @var stdClass         $module           Current module instance.
 * @var HtmlDocument     $doc              System document instance.
 * @var ModBPPopupHelper $helper           Module helper instance..
 */

defined('_JEXEC') or die;

// Add required framework
JHtml::_('jquery.framework');

// Create content wrapper ID
$moduleWrapperId = 'popupWrapper' . $module->id;

// Create options
$before_display_event = ''; // What to do before pop-up is displayed
$post_display_event   = ''; // What to do after pop-up was displayed
$options              = [];

$options['callbacks'] = ['beforeOpen' => ''];
$before_display_event .= "options.callbacks.beforeOpen = function(){
    console.log(this);
    this.bgOverlay.attr('id','bppopup-bg-{$module->id}');
    this.wrap.attr('id','bppopup-wrap-{$module->id}');
}
";
if ($location !== 'center') {
    $doc->addStyleDeclaration($helper->getLocationStyle());
}

// Create popup from settings
if ($mode === 'image') {
    $options['items']['type'] = 'image';
} elseif (in_array($mode, ['iframe', 'url', 'page'])) {
    $options['items']['type'] = 'iframe';
} else {
    $options['items']['type'] = 'inline';
}

// If this is an image popup
if ($mode === 'image') {
    $options['items']['src'] = rtrim(Uri::base(true), '/') . '/' . $image;
    if (!empty($url)) {

        // Target window
        $target = (!empty($target) ? 'target="' . $target . '"' : '');

        // Popup HTML
        $options['image']['markup'] = '
            <div class="mfp-figure">
                <div class="mfp-close"></div>
                <a class="mfp-img-wrapper" href="' . $url . '" ' . $target . '><span class="mfp-img"></span></a>
                <div class="mfp-bottom-bar">
                    <div class="mfp-title"></div>
                    <div class="mfp-counter"></div>
                </div>
            </div>';
    }

// if this is an iframe, url or page popup
} elseif (in_array($mode, ['iframe', 'url', 'page'])) {
    $options['items']['src'] = ($mode === 'page' ? $url = Route::_('index.php?Itemid=' . $page) : $url);

// If this is a HTML or Text popup
} else {

    // Determine code source
    $code = $html;
    if ($mode === 'text') {
        $code = $text;
    }

    $options['items']['src']      = '<div id="' . $moduleWrapperId . '" class="bppopup-mode-' . $mode . '">' . $code . '</div>';
    $options['items']['midClick'] = true;
    $options['closeBtnInside']    = false;
    $html_max_width               = $html_max_width > 0 ? "max-width:{$html_max_width}px;" : '';
    $html_min_height              = $html_min_height > 0 ? "min-height:{$html_min_height}px;" : '';
    $doc->addStyleDeclaration("
        #$moduleWrapperId{{$html_max_width}{$html_min_height}margin: 20px auto;position: relative;};
        .mfp-content #$moduleWrapperId{{$html_max_width}{$html_min_height}};
    ");
}

// Include required assets
if ($include_lightbox) {
    $doc->addStyleSheet(ModBPPopupHelper::getAssetUrl('modules/mod_bppopup/assets/module.css'), ['version' => 'auto'],
        ['id' => 'mod-bppopup']);
    $doc->addScript(ModBPPopupHelper::getAssetUrl('modules/mod_bppopup/assets/module.js'), ['version' => 'auto'],
        ['id' => 'mod-bppopup']);
}

// Create Magnific Popup options object
$json = json_encode($options);

// Just display the popup
if ($scroll_event === 'no') {

    // Create popup instance on dom ready
    $doc->addScriptDeclaration("
    // BP Popup declaration
    jQuery(function($){
        var options = $json; 
        $before_display_event
        $.magnificPopup.open(options);
        $post_display_event
    });");

// Display popup on scroll
} else {

    // Save information in a cookie
    if ($time_mode === 'cookie') {
        $post_display_event .= "BPPopup.cookieDisplayEvent({$module->id}, $cookie_time);";

        // Save information in the session
    } elseif ($time_mode === 'session') {
        $event_store_url    = Route::_('index.php?option=com_ajax&module=bppopup&format=json&module_id=' . $module->id);
        $post_display_event .= "$.ajax({'url':'$event_store_url'});";
    }

    // When user scroll a provided amount of pixels
    if ($scroll_length > 0) {
        $scroll_condition = "$(window).scrollTop()>$scroll_length";

        // When user reaching a provided number of pixels from end of page
    } else {
        $scroll_condition = "$(window).scrollTop() + $(window).height() >= $(document).height()$scroll_length";
    }

    // Reaching end of page
    if ($scroll_event === 'end_of_page') {
        $scroll_condition = "$(window).scrollTop() + $(window).height() >= $(document).height()";
    }

    // Create popup instance on dom ready
    $doc->addScriptDeclaration("
    // BP Popup declaration
    jQuery(function($){
        $(window).scroll(function(e){
            if( $scroll_condition ) {
                var options = $json; 
                $before_display_event
                $.magnificPopup.open(options);
                $(window).unbind('scroll');
                $post_display_event
            } 
        });
    });");
}
