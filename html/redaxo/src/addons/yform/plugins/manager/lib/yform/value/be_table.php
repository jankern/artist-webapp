<?php

/**
 * yform
 * @author jan.kristinus[at]redaxo[dot]org Jan Kristinus
 * @author <a href="http://www.yakamara.de">www.yakamara.de</a>
 */

class rex_yform_value_be_table extends rex_yform_value_abstract
{

    function preValidateAction()
    {

        // bc service for Version < 1.1
        if ($this->getValue() != "" && json_decode($this->getValue()) == "") {
            $rows = explode(";",$this->getValue());
            foreach($rows as $row_id => $row) {
                $rows[$row_id] = explode(",",$row);
            }
            $this->setValue(json_encode($rows));
        }

        $columns = explode(',', $this->getElement('columns'));
        if (count($columns) == 0) {
            return;
        }

        $id = $this->getId();

        // Cleanup Array

        $table_array = [];

        if (isset($_REQUEST['v'][$id])) {
            $rows = count($_REQUEST['v'][$id][0]);
            // Spalten durchgehen
            for ($c = 0; $c < count($columns); $c++) {
                for ($r = 0; $r < $rows; $r++) {
                    $table_array[$r][$c] = (isset($_REQUEST['v'][$id][$c][$r])) ? $_REQUEST['v'][$id][$c][$r] : "" ;
                }
            }
            $this->setValue(json_encode($table_array));
        }

        return;

    }

    function enterObject()
    {

        $this->params['value_pool']['email'][$this->getName()] = $this->getValue();
        if ($this->getElement(5) != 'no_db') {
            $this->params['value_pool']['sql'][$this->getName()] = $this->getValue();
        }

        if (!$this->needsOutput()) {
            return;
        }

        $columns = explode(',', $this->getElement('columns'));
        if (count($columns) == 0) {
            return;
        }

        $data = json_decode($this->getValue(),true);

        if (!is_array($data)) {
            $data = [];
        }
        $this->params['form_output'][$this->getId()] = $this->parse('value.be_table.tpl.php', compact('columns', 'data'));

    }

    function getDefinitions()
    {
        return array(
            'type' => 'value',
            'name' => 'be_table',
            'values' => array(
                'name'    => array( 'type' => 'name',   'label' => rex_i18n::msg("yform_values_defaults_name")),
                'label'   => array( 'type' => 'text',    'label' => rex_i18n::msg("yform_values_defaults_label")),
                'columns' => array( 'type' => 'text',    'label' => rex_i18n::msg("yform_values_be_table_columns")),
                'notice'    => array( 'type' => 'text',    'label' => rex_i18n::msg("yform_values_defaults_notice")),
            ),
            'description' => rex_i18n::msg("yform_values_be_table_description"),
            'formbuilder' => false,
            'dbtype' => 'text'
        );
    }


}
