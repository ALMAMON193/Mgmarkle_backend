<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SingleImageUpload extends Component
{
    public $label;

    public $name;

    public function __construct($label = 'Upload Image', $name = 'image')
    {
        $this->label = $label;
        $this->name = $name;
    }

    public function render(): View|Closure|string
    {
        return view('components.form.single-image-upload');
    }
}
