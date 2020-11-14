<?php

/**
 * @package     ${package}
 *
 * @copyright   Copyright (C) ${build.year} ${copyrights},  All rights reserved.
 * @license     ${license.name}; see ${license.url}
 * @author      ${author.name}
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Registry\Registry;

defined('_JEXEC') or die;

/**
 * @var Registry $params
 * @var stdClass $module
 */

// Default objects and variables
$doc    = Factory::getDocument();
$layout = $params->get('layout', 'default');
$debug  = Factory::getApplication()->get('debug');

// Params
$mode             = $params->get('mode', 'image');
$image            = $params->get('image');
$url              = $params->get('url');
$page             = $params->get('page');
$html             = $params->get('html');
$text             = $params->get('text');
$time_mode        = $params->get('time_mode', 'cookie');
$cookie_time      = $params->get('cookie_time', '1');
$include_lightbox = $params->get('include_lightbox', '1');
$html_max_width   = (int)$params->get('html_max_width', 640);
$html_min_height  = (int)$params->get('html_min_height');
$scroll_event     = $params->get('scroll_event', 'no');
$scroll_length    = (int)$params->get('scroll_length', 1);
$target           = $params->get('target', 'self');
$location         = $params->get('location', 'center');

require_once __DIR__ . '/helper.php';

// Create helper instance
$helper = new ModBPPopupHelper($params, $module);

// If window can popup, include scripts
if ($helper->canPopup()) {
    require ModuleHelper::getLayoutPath('mod_bppopup', $layout);
}
