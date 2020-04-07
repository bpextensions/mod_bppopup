<?php

/**
 * @package     ${package}
 *
 * @copyright   Copyright (C) ${build.year} ${copyrights},  All rights reserved.
 * @license     ${license.name}; see ${license.url}
 * @author      ${author.name}347702
 */

use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Router\Route;

/**
 * @var string $mode Iframe or image mode.
 * @var string $image Popup image url.
 * @var string $url Iframe popup url.
 * @var string $page Page item_id.
 * @var string $include_lightbox Include Magnific Popup assets?
 * @var string $mode Iframe mode.
 * @var string $html HTML for inline mode.
 * @var string $text HTML code from a text field mode.
 * @var int $html_min_width Minimum width for HTML popup.
 * @var int $html_min_height Minimum height for HTML popup.
 * @var stdClass $module Current module instance.
 * @var HtmlDocument $doc System document instance.
 */

defined('_JEXEC') or die;

JHtml::_('jquery.framework');
$moduleWrapperId = 'popupWrapper'.$module->id;

$options = [];
if ($mode === 'image') {
    $options['items']['type'] = 'image';
} elseif (in_array($mode, ['iframe', 'page'])) {
    $options['items']['type'] = 'iframe';
} else {
    $options['items']['type'] = 'inline';
}
if ($mode === 'image') {
    $options['items']['src'] = '/' . $image;
    if (!empty($url)) {
        $options['image']['markup'] = '
            <div class="mfp-figure">
                <div class="mfp-close"></div>
                <a class="mfp-img-wrapper" href="' . $url . '"><span class="mfp-img"></span></a>
                <div class="mfp-bottom-bar">
                    <div class="mfp-title"></div>
                    <div class="mfp-counter"></div>
                </div>
            </div>';
    }
} elseif ( in_array($mode, ['iframe','page']) ) {
    $options['items']['src'] = ($mode==='page' ? $url = Route::_('index.php?Itemid='.$page) : $url);
} else {

    // Determine code source
    $code = $html;
    if ($mode === 'text') {
        $code = $text;
    }

    $options['items']['src'] = '<div id="' . $moduleWrapperId . '" class="bppopup-mode-' . $mode . '">' . $code . '</div>';
    $options['items']['midClick'] = true;
    $options['closeBtnInside'] = false;
    $html_max_width = $html_max_width > 0 ? "max-width:{$html_max_width}px;" : '';
    $html_min_height = $html_min_height > 0 ? "min-height:{$html_min_height}px;" : '';
    $doc->addStyleDeclaration("
        #$moduleWrapperId{{$html_max_width}{$html_min_height}margin: 20px auto;position: relative;};
        .mfp-content #$moduleWrapperId{{$html_max_width}{$html_min_height}};
    ");
}

if ($include_lightbox) {
    $doc->addStyleSheet(ModBPPopupHelper::getAssetUrl('modules/mod_bppopup/assets/module.css'), ['version' => 'auto'], ['id' => 'mod-bppopup']);
    $doc->addScript(ModBPPopupHelper::getAssetUrl('modules/mod_bppopup/assets/module.js'), ['version' => 'auto'], ['id' => 'mod-bppopup']);
}
$json = json_encode($options);

// Just display the popup
if ($scroll_event == 'no') {
    $doc->addScriptDeclaration("jQuery(function($){
        $.magnificPopup.open($json);
    });");

// Display popup on scroll
} else {

    // What to do after pop-up was displayed
    $post_display_event = '';

    // Save information in a cookie
    if ($time_mode === 'cookie') {
        $post_display_event = "BPPopup.cookieDisplayEvent({$module->id}, $cookie_time);";

        // Save information in the session
    } elseif ($time_mode === 'session') {
        $event_store_url = Route::_('index.php?option=com_ajax&module=bppopup&format=json&module_id=' . $module->id);
        $post_display_event = "$.ajax({'url':'$event_store_url'});";
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

    $doc->addScriptDeclaration("jQuery(function($){
        $(window).scroll(function(e){
            if( $scroll_condition ) {
                $.magnificPopup.open($json);
                $(window).unbind('scroll');
                $post_display_event
            } 
        });
    });");
}
