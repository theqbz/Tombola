<?php

namespace App\Services;

use Collective\Html\FormBuilder;
use Collective\Html\HtmlBuilder;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;

class FormMacros extends FormBuilder
{

    private $attr = array();

    public function __construct(HtmlBuilder $html, UrlGenerator $url, Factory $view, $csrfToken, Request $request = null)
    {
        parent::__construct($html, $url, $view, $csrfToken, $request);

    }

    private function setData($attributes = array())
    {
        $this->attr = array_merge(['name' => strtolower(debug_backtrace()[1]['function']) . '-' . uniqid(),
            'id' => strtolower(debug_backtrace()[1]['function']) . '-' . uniqid(),
            'class' => 'form-control', 'containerClass' => 'form-group'], $attributes);
    }

    public function WyswygEditor($attributes = array())
    {

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
                    filebrowserUploadUrl: '" . route('ckeditor.image-upload', ['_token' => csrf_token()]) . "',
                    filebrowserUploadMethod: 'form',
                     customConfig: 'custom_conf.js',
                     filebrowserBrowseUrl:'',
                     filebrowserImageBrowseUrl:''
                    });
                });</script>";

        return $this->toHtmlString($html);
    }

    public function datePicker($attributes = array())
    {
        $this->setData($attributes);

        $html = '<div class="' . $this->attr['containerClass'] . '">';

        if (isset($this->attr['label'])) {
            $html .= '<label for="' . $this->attr["name"] . '">' . $this->attr["label"] . '</label>';
        }

        if (isset($this->attr['placeholder']) && !$this->attr['value']) {
            $this->attr['value'] = 'ÉÉÉÉ-HH-NN';
        }

        $html .= '<div class="input-group date">
            <input type="date" placeholder="' . date("Y.m.d") . '" id="' . $this->attr['id'] . '" class="datepicker ' . $this->attr['class'] . '" name="' . $this->attr['name'] . '"/>';
        if (isset($this->attr['needTime'])) {
            $html .= '<input type="time" placeholder="' . date("H:i") . '" id="' . $this->attr['id'] . '_time" class="datepicker ' . $this->attr['class'] . '" name="' . $this->attr['name'] . '_time"/>';
            $value_time = (isset($this->attr['valueTime'])) ? $this->attr['valueTime'] : date('H:i');
            $html .= '<script>let default_' . $this->attr['id'] . '_time ="' . $value_time . '";$("#' . $this->attr['id'] . '_time").val("' . $value_time . '")</script>';
        }
        $html .= '</div></div>';
        $value = (isset($this->attr['value'])) ? $this->attr['value'] : date('Y-m-d');

        $html .= '<script>let default_' . $this->attr['id'] . ' ="' . $value . '"; $("#' . $this->attr['id'] . '").val("' . $value . '")</script>';

        return $this->toHtmlString($html);
    }

    public function radioList($attributes = array())
    {
        $this->setData($attributes);


        $html = '<div class="input-group">';
        if (isset($this->attr['label'])) {
            $html .= '<label style="display:block;width:100%" for="' . $this->attr["name"] . '">' . $this->attr["label"] . '</label>';

        }
        $html .= '<div class="radio-list ">';
        $idx = 0;
        $checkedValue = (isset($this->attr['checked'])) ? $this->attr['checked'] : null;
        foreach ($attributes['radios'] as $label => $value) {
            $checked = '';
            if (!is_null($checkedValue) && $checkedValue == $value) {
                $checked = ' checked ';
            }
            $html .= '<div class="form-control mb-1 ' . (isset($this->attr['inline']) ? "d-inline-block w-auto mr-2 mb-5" : "") . '"><span>' . $label . '</span>&nbsp;<input ' . $checked . ' id="' . ($this->attr["name"] . "_" . $idx) . '" class="radio-list-item" type="radio" name="' . $this->attr["name"] . '" value="' . $value . '"></div>';
            $idx++;
        }
        $html .= '</div>';

        $html .= '</div>';

        return $this->toHtmlString($html);
    }

    public function QrCodeReader($attributes = array())
    {
        $this->setData($attributes);


        $html = '<script src=' . asset('js/html5-qrcode.min.js') . '></script>';

        $html .= '<div class="' . $this->attr['containerClass'] . '">';
        $html .= '<div style="width: 100%" id="reader"></div>';

        if (isset($this->attr['label']) && !(isset($this->attr['hiddenField']))) {
            $html .= '<label for="' . $this->attr["name"] . '">' . $this->attr["label"] . '</label>';
        }
        $inputtype = (isset($this->attr['hiddenField'])) ? "hidden" : "text";
        $html .= '<input type="' . $inputtype . '" id="' . $this->attr['id'] . '" class="' . $this->attr['class'] . '" name="' . $this->attr['name'] . '">';
        $html .= '</div>';

        $html .= '<script>
        var html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", { fps: 10, qrbox: 250 });
        
            function onScanSuccess(decodedText, decodedResult) {
                document.getElementById("' . $this->attr['id'] . '").value = decodedText;
                html5QrcodeScanner.clear();';
        if (isset($this->attr['hiddenField'])) {
            $html .= '$("form").submit();';
        }
        $html .= '}

                function onScanError(errorMessage) {
                    // handle on error condition, with error message
                }
            
            html5QrcodeScanner.render(onScanSuccess);
</script>';

        return $this->toHtmlString($html);
    }


}
