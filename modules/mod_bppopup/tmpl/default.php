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
 * @var string $mode Iframe mode
 */

defined('_JEXEC') or die;

JHtml::_('jquery.framework');

/* @var $doc HtmlDocument */
$type = $mode==='image' ? 'image':'iframe';
$url = $mode==='page' ? $url = Route::_('index.php?Itemid='.$page) : $url;
$src = $mode === 'image' ? '/'.$image : $url;

if( $include_lightbox ) {
    $doc->addStyleSheetVersion(ModBPPopupHelper::getAssetUrl('modules/mod_bppopup/assets/module.css'), ['version'=>'auto'], ['id'=>'mod-bppopup']);
    $doc->addScriptVersion(ModBPPopupHelper::getAssetUrl('modules/mod_bppopup/assets/module.js'), ['version'=>'auto'], ['id'=>'mod-bppopup']);
}
$doc->addScriptDeclaration('jQuery(function($){
    $.magnificPopup.open({
        items: {
            src: "'.$src.'",
            type: "'.$type.'"
       }
    });
});');
