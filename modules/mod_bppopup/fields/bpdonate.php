<?php

/**
 * @package     ${package}
 * @subpackage  ${subpackage}
 *
 * @copyright   Copyright (C) ${build.year} ${copyrights}, All rights reserved.
 * @license     ${license.name}; see ${license.url}
 */

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Text;

/**
 * A donate button field.
 *
 * @since  ${version}
 */
class JFormFieldBPDonate extends FormField
{
    /**
     * Session status variable name.
     *
     * @var string
     */
    const SESSION_VAR_NAME = 'bpextensions_donation';

    /**
     * The form field type.
     *
     * @var    string
     * @since  ${version}
     */
    public $type = 'BPDonate';

    /**
     * Donate url.
     *
     * @var    string
     * @since  ${version}
     */
    protected $url;

    /**
     * Button text.
     *
     * @var    string
     * @since  ${version}
     */
    protected $button_text;

    /**
     * Donate intro text.
     *
     * @var    string
     * @since  ${version}
     */
    protected $intro_text;

    /**
     * Method to attach a JForm object to the field.
     *
     * @param   \SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
     * @param   mixed              $value    The form field value to validate.
     * @param   string             $group    The field name group control value. This acts as an array container for the field.
     *                                       For example if the field has name="foo" and the group value is set to "bar" then the
     *                                       full field name would end up being "bar[foo]".
     *
     * @return  boolean  True on success.
     *
     * @see     FormField::setup()
     * @since   ${version}
     */
    public function setup(\SimpleXMLElement $element, $value, $group = null)
    {
        $result = parent::setup($element, $value, $group);

        if ($result === true) {
            $this->url         = $this->element['url'] ?? '${donate.url}';
            $this->button_text = Text::_($this->element['button_text'] ?? 'BPEXTENSIONS_BUTTON_DONATE_TEXT');
            $this->intro_text  = Text::_($this->element['intro_text'] ?? 'BPEXTENSIONS_DONATE_INTRO_TEXT');
        }

        return $result;
    }

    /**
     * Method to get the field input markup for button.
     *
     * @return  string  The field input markup.
     *
     * @throws Exception
     * @since   ${version}
     *
     */
    protected function getInput()
    {

        // Show popup if needed
        $session = Factory::getSession();
        if (!$session->get(static::SESSION_VAR_NAME)) {

            // Make a notice
            Factory::getApplication()->enqueueMessage($this->getDonateMessage(), 'notice');

            // Disable popup in this session
            $session->Set(static::SESSION_VAR_NAME, true);
        }

        return '';
    }

    /**
     * Get donation pop-up message content.
     *
     * @return string
     */
    protected function getDonateMessage(): string
    {
        return "<p>{$this->intro_text}</p>
        <span class=\"btn-wrapper\">
            <a href=\"{$this->url}\" target=\"_blank\" class=\"btn\">
                <span class=\"icon-thumbs-up\" aria-hidden=\"true\" style=\"border-radius: 3px 0 0 3px;border-right: 1px solid #b3b3b3;height: auto;line-height: inherit;margin: 0 6px 0 -10px;opacity: 1;text-shadow: none;width: 28px;\"></span>
                {$this->button_text}
            </a>
        </span>";
    }

    /**
     * Method to get the field input markup for button.
     *
     * @return  string  The field input markup.
     *
     * @since   ${version}
     */
    protected function getLabel()
    {
        return '';
    }

}
