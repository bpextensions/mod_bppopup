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
 * @var int $html_min_width Minimum width for HTML popup.
 * @var int $html_min_height Minimum height for HTML popup.
 * @var stdClass $module Current module instance.
 * @var HtmlDocument $doc System document instance.
 */

defined('_JEXEC') or die;

JHtml::_('jquery.framework');
$moduleWrapperId = 'popupWrapper'.$module->id;

$options = [];
if( $mode ==='image' ) {
    $options['items']['type'] = 'image';
} elseif ( in_array($mode, ['iframe','page']) ) {
    $options['items']['type'] = 'iframe';
} else {
    $options['items']['type'] = 'inline';
}
if( $mode ==='image' ) {
    $options['items']['src'] = '/'.$image;
    if( !empty($url) ) {
        $options['image']['markup'] = '
            <div class="mfp-figure">
                <div class="mfp-close"></div>
                <a class="mfp-img-wrapper" href="'.$url.'"><span class="mfp-img"></span></a>
                <div class="mfp-bottom-bar">
                    <div class="mfp-title"></div>
                    <div class="mfp-counter"></div>
                </div>
            </div>';
    }
} elseif ( in_array($mode, ['iframe','page']) ) {
    $options['items']['src'] = ($mode==='page' ? $url = Route::_('index.php?Itemid='.$page) : $url);
} else {
    $options['items']['src'] = '<div id="'.$moduleWrapperId.'">'.$html.'</div>';
    $options['items']['midClick'] = true;
    $options['closeBtnInside'] = false;
    $html_max_width = $html_max_width>0 ? "max-width:{$html_max_width}px;" : '';
    $html_min_height = $html_min_height>0 ? "min-height:{$html_min_height}px;" : '';
    $doc->addStyleDeclaration("
        #$moduleWrapperId{{$html_max_width}{$html_min_height}margin: 20px auto;position: relative;};
        .mfp-content #$moduleWrapperId{{$html_max_width}{$html_min_height}};
    ");
}

if( $include_lightbox ) {
    $doc->addStyleSheetVersion(ModBPPopupHelper::getAssetUrl('modules/mod_bppopup/assets/module.css'), ['version'=>'auto'], ['id'=>'mod-bppopup']);
    $doc->addScriptVersion(ModBPPopupHelper::getAssetUrl('modules/mod_bppopup/assets/module.js'), ['version'=>'auto'], ['id'=>'mod-bppopup']);
}
$json = json_encode($options);
$doc->addScriptDeclaration("jQuery(function($){
    $.magnificPopup.open($json);
});");
