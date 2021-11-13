<?php

namespace App\Services;

use Collective\Html\FormBuilder;
use Collective\Html\HtmlBuilder;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;

class FormMacros extends FormBuilder {

    private $attr = array();

    public function __construct(HtmlBuilder $html, UrlGenerator $url, Factory $view, $csrfToken, Request $request = null) {
        parent::__construct($html, $url, $view, $csrfToken, $request);

    }

    private function setData($attributes = array()) {
        $this->attr = array_merge(['name'  => strtolower(debug_backtrace()[1]['function']) . '-' . uniqid(),
                                   'id'    => strtolower(debug_backtrace()[1]['function']) . '-' . uniqid(),
                                   'class' => 'form-control', 'containerClass' => 'form-group'], $attributes);
    }

    public function WyswygEditor($attributes = array()) {

        $this->setData($attributes);

        $html = '<div class="' . $this->attr['containerClass'] . '">';
        if (isset($this->attr['label'])) {
            $html .= '<label for="' . $this->attr["name"] . '">' . $this->attr["label"] . '</label>';
        }
        $html .= '<textarea id="' . $this->attr['id'] . '" class="' . $this->attr['class'] . '" name="' . $this->attr['name'] . '">';
        if (isset($this->attr['value'])) {
            $html .= $this->attr['value'];
        }
        $html .= '</textarea>';
        $html .= '</div>';
        $html .= "<script type='text/javascript'>
                $(document).ready(function () {
                    $('#" . $this->attr['id'] . "').ckeditor({
                    language:'hu',
                    skin:'n1theme',
                    inline:'content',
                    });
                });</script>";

        return $this->toHtmlString($html);
    }

    public function datePicker($attributes = array()) {
        $this->setData($attributes);

        $html = '<div class="' . $this->attr['containerClass'] . '">';

        if (isset($this->attr['label'])) {
            $html .= '<label for="' . $this->attr["name"] . '">' . $this->attr["label"] . '</label>';
        }

        $html .= '<div class="input-group date">
            <input type="date" placeholder="' . date("Y.m.d") . '" id="' . $this->attr['id'] . '" class="datepicker ' . $this->attr['class'] . '" name="' . $this->attr['name'] . '"/>';
        if (isset($this->attr['needTime'])) {
            $html       .= '<input type="time" placeholder="' . date("H:i") . '" id="' . $this->attr['id'] . '_time" class="datepicker ' . $this->attr['class'] . '" name="' . $this->attr['name'] . '_time"/>';
            $value_time = (isset($this->attr['value_time'])) ? $this->attr['value_time'] : date('H:i');
            $html       .= '<script>$("#' . $this->attr['id'] . '_time").val("' . $value_time . '")</script>';
        }
        $html  .= '</div></div>';
        $value = (isset($this->attr['value'])) ? $this->attr['value'] : date('Y-m-d');

        $html .= '<script>$("#' . $this->attr['id'] . '").val("' . $value . '")</script>';

        return $this->toHtmlString($html);
    }

    public function radioList($attributes = array()) {
        $this->setData($attributes);

        $html = '<div class="input-group">';
        if (isset($this->attr['label'])) {
            $html .= '<label style="display:block;width:100%" for="' . $this->attr["name"] . '">' . $this->attr["label"] . '</label>';

        }
        $html         .= '<div class="radio-list ">';
        $idx          = 0;
        $checkedValue = (isset($this->attr['checked'])) ? $this->attr['checked'] : '';
        foreach ($attributes['radios'] as $label => $value) {
            $checked = '';

            if ($checkedValue && $checkedValue === $value) {
                $checked = ' checked ';
            }
            $html .= '<div class="form-control mb-1"><span>' . $label . '</span>&nbsp;<input ' . $checked . ' id="' . ($this->attr["name"] . "_" . $idx) . '" class="radio-list-item" type="radio" name="' . $this->attr["name"] . '" value="' . $value . '"></div>';
            $idx++;
        }
        $html .= '</div>';

        $html .= '</div>';

        return $this->toHtmlString($html);
    }


}
