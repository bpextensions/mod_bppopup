<?php

/**
 * @package     ${package}
 *
 * @copyright   Copyright (C) ${build.year} ${copyrights},  All rights reserved.
 * @license     ${license.name}; see ${license.url}
 * @author      ${author.name}
 */

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Session\Session;
use Joomla\Registry\Registry;

defined('_JEXEC') or die;

/**
 * Module helper class for BP Popup.
 */
final class ModBPPopupHelper
{
    /**
     * Module params.
     *
     * @var Registry
     */
    protected $params;

    /**
     * Module instance.
     *
     * @var object
     */
    protected $module;

    /**
     * Create module helper instance and execute calculation.
     *
     * @param   Registry  $params  Module parameters.
     * @param   object    $module  Module instance.
     */
    public function __construct(Registry $params, object $module)
    {
        $this->params = $params;
        $this->module = $module;
    }

    /**
     * Get asset url.
     *
     * @param   string  $url  Asset regular url.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function getAssetUrl(string $url): string
    {
        $manifest = json_decode(file_get_contents(JPATH_SITE . '/modules/mod_bppopup/assets/manifest.json'), true);

        $url = ltrim($url, '/');
        if (key_exists($url, $manifest)) {
            $url = $manifest[$url];
        }

        return $url;
    }

    /**
     * Save session/cookie information about popup being displayed.
     *
     * @throws Exception
     *
     * @since 1.2.0
     */
    public static function getAjax(): void
    {
        static::saveSessionDisplayEvent(Factory::getApplication()->input->get('module_id'));
    }

    /**
     * Save information about popup being displayed.
     *
     * @param   int  $module_id  Module ID.
     *
     * @throws Exception
     *
     * @since 1.2.0
     */
    protected static function saveSessionDisplayEvent(int $module_id): void
    {
        /* @var $session Session */
        $session = Factory::getApplication()->getSession();
        $session->set('bppopup_' . $module_id, 1);
    }

    /**
     * Can module display the popup?
     *
     * @return boolean
     *
     * @throws Exception
     *
     * @since 1.0.0
     */
    public function canPopup(): bool
    {
        $shown = true;

        /* @var $app CMSApplication */
        $app          = Factory::getApplication();
        $time_mode    = $this->params->get('time_mode', 'cookie');
        $scroll_event = $this->params->get('scroll_event', 'no');

        // If module works in cookie mode
        if ($time_mode === 'cookie') {

            $shown          = $app->input->cookie->get('bppopup_' . $this->module->id, 0);
            $expire_in_days = $this->params->get('cookie_time', 1);
            $expire_date    = time() + ($expire_in_days * 24 * 60 * 60);

            // Popup is about to be displayed, dont show again
            if ($scroll_event === 'no') {
                $app->input->cookie->set('bppopup_' . $this->module->id, '1', $expire_date);
            }

            // If module works in session mode
        } elseif ($time_mode === 'session') {

            /* @var $session Session */
            $session = $app->getSession();
            $shown   = $session->get('bppopup_' . $this->module->id, 0);

            // Popup is about to be displayed, dont show again
            if ($scroll_event === 'no') {
                $session->set('bppopup_' . $this->module->id, 1);
            }
        } elseif ($time_mode === 'view') {
            $shown = 0;
        }

        return !$shown;
    }
}