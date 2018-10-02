<?php
/**
 * @package     ${package}
 *
 * @copyright   Copyright (C) ${build.year} ${copyrights},  All rights reserved.
 * @license     ${license.name}; see ${license.url}
 * @author      ${author.name}
 */

defined('_JEXEC') or die;

$layout = $params->get('layout', 'default');

require JModuleHelper::getLayoutPath('mod_bppopup', $layout);
