<?php

$buttonId = $counter;
$name = $this->getFieldName();
$value = htmlspecialchars($this->getValue());

$widget_params = [];
$widget_params['category'] = 0;
if ($this->getElement('category') != "") {
    $widget_params['category'] = intval($this->getElement('category'));
}
$widget_params['preview'] = $this->getElement('preview');
if ($this->getElement('types') != "") {
    $widget_params['types'] = trim($this->getElement('types'));
}

if ($this->getElement('multiple') == 1) {
    $widget = rex_var_medialist::getWidget($buttonId, $name, $value, $widget_params);

} else {
    $widget = rex_var_media::getWidget($buttonId, $name, $value, $widget_params);

}

$class_group = trim('form-group yform-element ' . $this->getHTMLClass() . ' ' . $this->getWarningClass());

$notice = array();
if ($this->getElement('notice') != "") {
    $notice[] = rex_i18n::translate($this->getElement('notice'));
}
if (isset($this->params['warning_messages'][$this->getId()]) && !$this->params['hide_field_warning_messages']) {
    $notice[] =  '<span class="text-warning">' . rex_i18n::translate($this->params['warning_messages'][$this->getId()], null, false) . '</span>'; //    var_dump();
}
if (count($notice) > 0) {
    $notice = '<p class="help-block">' . implode("<br />", $notice) . '</p>';

} else {
    $notice = '';
}

?>
<div class="<?php echo $class_group ?>" id="<?php echo $this->getHTMLId() ?>">
    <label class="control-label" for="<?php echo $this->getFieldId() ?>"><?php echo $this->getLabel() ?></label>
    <?php echo $widget; ?>
    <?php echo $notice ?>
</div>
