<?php

namespace computy\chipselect\widgets;

use computy\chipselect\ChipSelectAsset;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

class ChipSelect extends Widget
{
    /** @var string|null The ID to identify this ChipSelect widget by. */
    public ?string $id = null;

    /** @var string|null The input name. */
    public ?string $name = null;

    /** @var array $data The selectable options, as a map from value to display name. */
    public array $data = [];

    /** @var array $value The initially selected options. */
    public array $value = [];

    /** @var string|JsExpression|null $jsOnChange The JS logic to run on change. Triggered whenever an item is selected/deselected. */
    public JsExpression|string|null $jsOnChange = null;

    /** @var array $options The HTML options to apply to all selectable items. */
    public array $options = [];

    /** @var array $inputOptions The HTML options to apply to the hidden input holding the selected items. */
    public array $inputOptions = [];

    /** @var array $containerOptions The HTML options to apply to the element containing the chip select. */
    public array $containerOptions = [];

    /**
     * @inheritdoc
     */
    public function run()
    {
        ChipSelectAsset::register($this->getView());
        parent::run();
        $this->renderWidget();
    }

    /**
     * Render the ChipSelect widget and registers the relevant JS.
     * @return void
     * @throws InvalidConfigException
     */
    protected function renderWidget(): void
    {
        $this->setEmptyValues();

        $this->renderChips();

        $id = $this->containerOptions['id'];
        $options = [];
        if (!empty($this->jsOnChange)) {
            if (!$this->jsOnChange instanceof JsExpression) {
                $this->jsOnChange = new JsExpression($this->jsOnChange);
            }
            $options['onChange'] = $this->jsOnChange;
        }
        $options = Json::encode($options);
        $this->view->registerJs(
            <<<JS
initChipSelect($('#${id}'), ${options});
JS
        );
    }

    /**
     * Render the widget.
     * @return void
     */
    protected function renderChips() {
        $data = $this->data;
        $optionsUnselected = $this->options;
        $optionsUnselected['class'] .= ' chip-unselected';
        $optionsSelected = $this->options;
        $optionsSelected['class'] .= ' chip-selected';
        echo Html::beginTag('div', $this->containerOptions);

        echo Html::beginTag('div', ['class' => 'unselected-container']);
        foreach ($data as $value => $display) {
            $visible = !in_array($value, $this->value, true);
            $options = $optionsUnselected;
            if (!$visible) {
                $style = $options['style'] ?? '';
                $style = 'display: none; ' . $style;
                $options['style'] = $style;
            }
            $options['data-value'] = $value;
            $display .= ' <i class="fas fa-fw fa-plus"></i>';
            echo Html::tag('div', $display, $options);
        }
        echo Html::endTag('div');

        echo Html::beginTag('div', ['class' => 'selected-container']);
        foreach ($data as $value => $display) {
            $visible = in_array($value, $this->value);
            $options = $optionsSelected;
            $inputOptions = $this->inputOptions;
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
                $display . Html::hiddenInput($this->name . '[]', $value, $inputOptions),
                $options
            );
        }
        echo Html::endTag('div');

        echo Html::endTag('div');
    }

    /**
     * Set all unset/empty values that should be set.
     * @return void
     * @throws InvalidConfigException
     */
    protected function setEmptyValues(): void
    {
        if (empty($this->name)) {
            throw new InvalidConfigException('Name must be set!');
        }
        if (empty($this->id)) {
            $this->id = 'chip_select_' . random_int(100000, 999999);
        }

        $class = $this->options['class'] ?? '';
        if (!str_contains($class, 'cpty-select-chip')) {
            $class .= ' cpty-select-chip';
        }
        $this->options['class'] = $class;

        $classContainer = $this->containerOptions['class'] ?? '';
        if (!str_contains($class, 'cpty-chip-select-container')) {
            $classContainer .= ' cpty-chip-select-container';
        }
        $this->containerOptions['class'] = $classContainer;
        if (!isset($this->containerOptions['id'])) {
            $this->containerOptions['id'] = $this->id . '_container';
        }
    }
}