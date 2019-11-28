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

defined('_JEXEC') or die;

// Default objects and variables
$doc = Factory::getDocument();
$layout = $params->get('layout', 'default');
$debug = Factory::getApplication()->get('debug');

// Params
$mode = $params->get('mode', 'image');
$image = $params->get('image');
$url = $params->get('url');
$page = $params->get('page');
$time_mode = $params->get('time_mode', 'cookie');
$cookie_time = $params->get('cookie_time', '1');
$include_lightbox = $params->get('include_lightbox', '1');

require_once __DIR__ . '/helper.php';

// Create helper instance
$helper = new ModBPPopupHelper($params, $module);

// If window can popup, include scripts
if ($helper->canPopup()) {
    require ModuleHelper::getLayoutPath('mod_bppopup', $layout);
}
