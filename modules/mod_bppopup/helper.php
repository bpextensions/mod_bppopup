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

/**
 * Module helper class for BP Popup.
 */
class ModBPPopupHelper
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
     * @var type
     */
    protected $module;

    /**
     * Create module helper instance and execute calculation.
     *
     * @param Registry $params Module parameters.
     */
    public function __construct(Registry $params, $module)
    {
        $this->params = $params;
        $this->module = $module;
    }

    /**
     * Can module display the popup?
     *
     * @return boolean
     */
    public function canPopup()
    {
        $can = true;

        /* @var $app CMSApplication */
        $app = Factory::getApplication();

        // If module works in cookie mode
        if ($this->params->get('time_mode', 'cookie') === 'cookie') {

            $shown          = $app->input->cookie->get('bppopup_'.$this->module->id, 0);
            $expire_in_days = $this->params->get('cookie_time', 1);
            $expire_date    = time() + ($expire_in_days * 24 * 60 * 60);
            $app->input->cookie->set('bppopup_'.$this->module->id, '1', $expire_date);

            // If module works in session mode
        } elseif ($this->params->get('time_mode', 'cookie') === 'session') {

            /* @var $session Session */
            $session = $app->getSession();
            $shown   = $session->get('bppopup_'.$this->module->id, 0);
            $session->set('bppopup_'.$this->module->id, 1);
        }

        // If popup wasn't shown, it can popup
        $can = !$shown;

        return $can;
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
        if (key_exists($url, $manifest))
        {
            $url = $manifest[$url];
        }

        return $url;
    }
}