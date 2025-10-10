<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Select extends Component
{
    public $id;

    public $label;

    public $options;

    public $wireModel;

    public $error;

    public function __construct($id = '', $label = '', $options = [], $wireModel = null, $error = null)
    {
        $this->id = $id;
        $this->label = $label;
        $this->options = $options;
        $this->wireModel = $wireModel;
        $this->error = $error;
    }

    public function render()
    {
        return view('components.form.select');
    }
}
