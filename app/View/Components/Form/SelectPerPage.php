<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectPerPage extends Component
{
    public $options;

    public $model;

    /**
     * Create a new component instance.
     */
    public function __construct($options = [5, 10, 25, 50, 100], $model = null)
    {
        $this->options = $options;
        $this->model = $model;
    }

    public function render(): View|Closure|string
    {
        return view('components.form.select-per-page');
    }
}
