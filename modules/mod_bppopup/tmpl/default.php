<?php

/**
 * @package     ${package}
 *
 * @copyright   Copyright (C) ${build.year} ${copyrights},  All rights reserved.
 * @license     ${license.name}; see ${license.url}
 * @author      ${author.name}
 */

use Joomla\CMS\Document\HtmlDocument;

defined('_JEXEC') or die;

JHtml::_('jquery.framework');

/* @var $doc HtmlDocument */
$type = $mode==='image' ? 'image':'iframe';
$src = $mode === 'image' ? $image : $url;
$doc->addStyleSheetVersion('modules/mod_bppopup/assets/magnific-popup.'.($debug ? 'css':'min.css'), [], ['id'=>'magnific-popup']);
$doc->addScriptVersion('modules/mod_bppopup/assets/jquery.magnific-popup.'.($debug ? 'js':'min.js'), [], ['id'=>'magnific-popup']);
$doc->addScriptDeclaration('jQuery(function($){
    $.magnificPopup.open({
        items: {
            src: "'.$src.'",
            type: "'.$type.'"
       }
    });
});');
