<?php

/**
 * yform
 * @author jan.kristinus[at]redaxo[dot]org Jan Kristinus
 * @author <a href="http://www.yakamara.de">www.yakamara.de</a>
 */

class rex_yform_value_float extends rex_yform_value_abstract
{

    function enterObject()
    {
        if ($this->getValue() == '' && !$this->params['send']) {
            $this->setValue($this->getElement('default'));
        }

        $this->setValue(self::formatValue($this->getValue(), $this->getElement('scale')));

        if ($this->needsOutput()) {
            $this->params['form_output'][$this->getId()] = $this->parse(array('value.float.tpl.php', 'value.text.tpl.php'));
        }

        $this->params['value_pool']['email'][$this->getName()] = $this->getValue();
        if ($this->getElement('no_db') != 'no_db') {
            $this->params['value_pool']['sql'][$this->getName()] = $this->getValue();
        }

    }

    function getDescription()
    {
        return 'float|name|label|scale|defaultwert|[no_db]';
    }

    function getDefinitions()
    {
        return array(
            'type' => 'value',
            'name' => 'float',
            'values' => array(
                'name'      => array( 'type' => 'name',    'label' => rex_i18n::msg("yform_values_defaults_name")),
                'label'     => array( 'type' => 'text',    'label' => rex_i18n::msg("yform_values_defaults_label")),
                'scale'     => array( 'type' => 'text',    'label' => rex_i18n::msg("yform_values_float_scale")),
                'default'   => array( 'type' => 'text',    'label' => rex_i18n::msg("yform_values_float_default")),
                'no_db'     => array( 'type' => 'no_db',   'label' => rex_i18n::msg("yform_values_defaults_table"),  'default' => 0),
                'notice'    => array( 'type' => 'text',    'label' => rex_i18n::msg("yform_values_defaults_notice")),
            ),
            'description' => rex_i18n::msg("yform_values_float_description"),
            'dbtype' => 'varchar(255)',
        );

    }

    public static function getSearchField($params)
    {
        $params['searchForm']->setValueField('text', array('name' => $params['field']->getName(), 'label' => $params['field']->getLabel()));
    }

    public static function getSearchFilter($params)
    {
        $sql = rex_sql::factory();

        $value = $params['value'];
        $field =  $sql->escapeIdentifier($params['field']->getName());

        if ($value == '(empty)') {
            return ' (' . $field . ' = "" or ' . $field . ' IS NULL) ';
        } elseif ($value == '!(empty)') {
            return ' (' . $field . ' <> "" and ' . $field . ' IS NOT NULL) ';
        }

        $scale = $params['field']->getElement('scale');
        $float = '-?\d*(?:[,.]\d+)?';
        if (preg_match('/^\s*(' . $float . ')\s*\.\.\s*(' . $float . ')\s*$/', $value, $match)) {
            $match[1] = self::formatValue($match[1], $scale);
            $match[2] = self::formatValue($match[2], $scale);
            return ' ' . $field . ' BETWEEN ' . $match[1] . ' AND ' . $match[2];
        } else {
            preg_match('/^\s*(<|<=|>|>=|<>|!=)?\s*(.*)$/', $value, $match);
            $comparator = $match[1] ?: '=';
            $value = self::formatValue($match[2], $scale);
            return ' ' . $field . ' ' . $comparator . ' ' . $value;
        }
    }

    protected static function formatValue($value, $scale)
    {
        return number_format((double) strtr($value, ',', '.'), (int) $scale, '.', '');
    }

}
