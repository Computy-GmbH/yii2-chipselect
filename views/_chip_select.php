<?php

use computy\chipselect\widgets\ChipSelect;
use yii\helpers\Html;

/**
 * @var ChipSelect $widget
 */

$data = $widget->data;
$optionsUnselected = $widget->options;
$optionsUnselected['class'] .= ' chip-unselected';
$optionsSelected = $widget->options;
$optionsSelected['class'] .= ' chip-selected';
?>

<div <?= Html::renderTagAttributes($widget->containerOptions) ?>>
    <div class="unselected-container">
        <?php foreach ($data as $value => $display) {
            $visible = !in_array($value, $widget->value);
            $options = $optionsUnselected;
            if (!$visible) {
                $style = $options['style'] ?? '';
                $style = 'display: none; ' . $style;
                $options['style'] = $style;
            }
            $options['data-value'] = $value;
            $display .= ' <i class="fas fa-fw fa-plus"></i>';
            echo Html::tag('div', $display, $options);
        } ?>
    </div>
    <div class="selected-container">
        <?php foreach ($data as $value => $display) {
            $visible = in_array($value, $widget->value);
            $options = $optionsSelected;
            $inputOptions = $widget->inputOptions;
            if (!$visible) {
                $style = $options['style'] ?? '';
                $style = 'display: none; ' . $style;
                $options['style'] = $style;
                $inputOptions['disabled'] = true;
            }
            $options['data-value'] = $value;
            $display .= ' <i class="fas fa-fw fa-minus"></i>';
            echo Html::tag(
                'div',
                $display . Html::hiddenInput($widget->name . '[]', $value, $inputOptions),
                $options
            );
        } ?>
    </div>
</div>
