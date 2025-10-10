<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MultipleImageUpload extends Component
{
    public $label;

    public $name;

    public $max;

    public function __construct($label = 'Upload Images', $name = 'images[]', $max = 20)
    {
        $this->label = $label;
        $this->name = $name;
        $this->max = $max;
    }

    public function render(): View|Closure|string
    {
        return view('components.form.multiple-image-upload');
    }
}
