<?php

/**
 * yform
 * @author jan.kristinus[at]redaxo[dot]org Jan Kristinus
 * @author <a href="http://www.yakamara.de">www.yakamara.de</a>
 */

class rex_yform_value_be_select_category extends rex_yform_value_abstract
{

    function enterObject()
    {
        if (is_array($this->getValue())) {
            $this->setValue(implode(',', $this->getValue()));
        }

        $this->params['value_pool']['email'][$this->getName()] = $this->getValue();
        if ($this->getElement(4) != 'no_db') {
            $this->params['value_pool']['sql'][$this->getName()] = $this->getValue();
        }

        if (!$this->needsOutput()) {
            return;
        }

        $multiple = $this->getElement('multiple') == 1;

        $options = array();
        if ($this->getElement('homepage')) {
            $options[0] = 'Homepage';
        }

        $ignoreOfflines = $this->getElement('ignore_offlines');
        $checkPerms = $this->getElement('check_perms');
        $clang = (int) $this->getElement('clang');

        $add = function (rex_category $cat, $level = 0) use (&$add, &$options, $ignoreOfflines, $checkPerms, $clang) {

            if (!$checkPerms || rex::getUser()->getComplexPerm('structure')->hasCategoryPerm($cat->getId())) {
                $cid = $cat->getId();
                $cname = $cat->getName();

                if (rex::getUser()->hasPerm('advancedMode[]')) {
                    $cname .= ' [' . $cid . ']';
                }

                $options[$cid] = str_repeat('&nbsp;&nbsp;&nbsp;', $level) . $cname;
                $childs = $cat->getChildren($ignoreOfflines);
                if (is_array($childs)) {
                    foreach ($childs as $child) {
                        $add($child, $level + 1);
                    }
                }
            }
        };
        if ($rootId = $this->getElement('category')) {
            if ($rootCat = rex_category::get($rootId, $clang)) {
                $add($rootCat);
            }
        } else {
            if (!$checkPerms || rex::getUser()->isAdmin() || rex::getUser()->hasPerm('csw[0]')) {
                if ($rootCats = rex_category::getRootCategories($ignoreOfflines, $clang)) {
                    foreach ($rootCats as $rootCat) {
                        $add($rootCat);
                    }
                }
            } elseif (rex::getUser()->getComplexPerm('structure')->hasMountpoints()) {
                $mountpoints = rex::getUser()->getComplexPerm('structure')->getMountpoints();
                foreach ($mountpoints as $id) {
                    $cat = rex_category::getCategoryById($id, $clang);
                    if ($cat && !rex::getUser()->getComplexPerm('structure')->hasCategoryPerm($cat->getParentId())) {
                        $add($cat);
                    }
                }
            }
        }

        if ($multiple) {
            $size = (int) $this->getElement('size');
            if ($size < 2) {
                $size = count($options);
            }
        } else {
            $size = 1;
        }

        if (!is_array($this->getValue())) {
            $this->setValue(explode(',', $this->getValue()));
        }

        $this->params['form_output'][$this->getId()] = $this->parse('value.select.tpl.php', compact('options', 'multiple', 'size'));

        $this->setValue(implode(',', $this->getValue()));
    }

    function getDefinitions()
    {
        return array(
            'type' => 'value',
            'name' => 'be_select_category',
            'values' => array(
                'name'     => array( 'type' => 'name',   'label' => rex_i18n::msg("yform_values_defaults_name")),
                'label'    => array( 'type' => 'text',    'label' => rex_i18n::msg("yform_values_defaults_label")),
                'ignore_offlines' => array( 'type' => 'boolean', 'label' => rex_i18n::msg("yform_values_be_select_category_ignore_offlines"), 'default' => 1),
                'check_perms'     => array( 'type' => 'boolean', 'label' => rex_i18n::msg("yform_values_be_select_category_check_perms"), 'default' => 1),
                'homepage'        => array( 'type' => 'boolean', 'label' => rex_i18n::msg("yform_values_be_select_category_homepage"), 'default' => 1),
                'category' => array( 'type' => 'text',    'label' => rex_i18n::msg("yform_values_be_select_category_category"), 'value' => 0),
                'clang'    => array( 'type' => 'select_sql',    'query' => 'select id, code as name from rex_clang', 'label' => rex_i18n::msg("yform_values_be_select_category_clang"), 'value' => 1),
                'multiple' => array( 'type' => 'boolean', 'label' => rex_i18n::msg("yform_values_be_select_category_multiple")),
                'size'     => array( 'type' => 'text',    'label' => rex_i18n::msg("yform_values_be_select_category_size")),
                'no_db'    => array( 'type' => 'no_db',   'label' => rex_i18n::msg("yform_values_defaults_table"),          'default' => 0),
                'attributes'   => array( 'type' => 'text',    'label' => rex_i18n::msg('yform_values_defaults_attributes'), 'notice' => rex_i18n::msg('yform_values_defaults_attributes_notice')),
                'notice'    => array( 'type' => 'text',    'label' => rex_i18n::msg("yform_values_defaults_notice")),
            ),
            'description' => rex_i18n::msg("yform_values_be_select_category_description"),
            'formbuilder' => false,
            'dbtype' => 'text'
        );

    }

    static function getListValue($params)
    {
        $return = array();

        foreach (explode(',', $params['value']) as $id) {
            if ($cat = rex_category::get($id, (int) $params['params']['field']['clang'])) {
                $return[] = $cat->getName();
            }
        }

        return implode('<br />', $return);
    }

}
