<?php

/**
 * yform
 * @author jan.kristinus[at]redaxo[dot]org Jan Kristinus
 * @author <a href="http://www.yakamara.de">www.yakamara.de</a>
 */

class rex_yform_value_submit extends rex_yform_value_abstract
{

    function init()
    {
        $this->params['submit_btn_show'] = false;
    }

    function enterObject()
    {

        $labels = $this->getElement('labels');
        if ($labels == "") {
            $labels = [$this->getElement('label')];

        } else {
            $labels = explode(",", $this->getElement('labels'));

        }

        $values = $this->getElement('values');
        if ($values == "") {
            $values = [];

        } else {
            $values = explode(",", $values);

        }

        $default_value = "";
        if ( $this->getElement('default')) {
            $default_value = $this->getElement('default');

        }

        if (in_array($this->getValue(), $labels)) {
            $key = array_search($this->getValue(), $labels);
            if (isset($values[$key])) {
                $value = $values[$key];

            } else {
                $value = $default_value;
            }

        } else {
            $value = $default_value;

        }

        $this->setValue($value);

        if (count($labels) == 0) {
            $labels = [$value];
        }

        if (count($labels) == 1 && $this->getElement('css_classes') == "") {
            $this->setElement('css_classes', 'btn-primary');

        }

        if ($this->needsOutput()) {
            $this->params['form_output'][$this->getId()] = $this->parse('value.submit.tpl.php', compact('labels'));
        }

        if (!isset($this->params['value_pool']['email'][$this->getName()]) || $this->params['value_pool']['email'][$this->getName()] == "") {
            $this->params['value_pool']['email'][$this->getName()] = $this->getValue();
        }

        if ($this->getElement('no_db') != 'no_db' && $this->getElement(3) != 'no_db') { // BC element[3]
            if (!isset($this->params['value_pool']['sql'][$this->getName()]) || $this->params['value_pool']['sql'][$this->getName()] == "") {
                $this->params['value_pool']['sql'][$this->getName()] = $this->getValue();
            }
        }

    }

    function getDescription()
    {
        return 'submit|label|labelvalue1_on_button1,labelvalue2_on_button2 | [value_1_to_save_if_clicked,value_2_to_save_if_clicked] | [no_db] | [Default-Wert] | [cssclassname1,cssclassname2]';
    }

    function getDefinitions()
    {
        return array(
                'type' => 'value',
                'name' => 'submit',
                'values' => array(
                    'name'      => array( 'type' => 'name',    'label' => rex_i18n::msg("yform_values_defaults_name")),
                    'labels'     => array( 'type' => 'text',    'label' => rex_i18n::msg("yform_values_defaults_label")),
                    'values'    => array( 'type' => 'text',    'label' => rex_i18n::msg("yform_values_submit_values")),
                    'no_db'     => array( 'type' => 'no_db',   'label' => rex_i18n::msg("yform_values_defaults_table"),  'default' => 0),
                    'default'   => array( 'type' => 'text',    'label' => rex_i18n::msg("yform_values_submit_default")),
                    'css_classes' => array( 'type' => 'text',    'label' => rex_i18n::msg("yform_values_submit_css_classes")
                ),
            ),
            'description' => rex_i18n::msg("yform_values_submit_description"),
            'dbtype' => 'text',
            'is_searchable' => false,
            'is_hiddeninlist' => true,
        );

    }

}
