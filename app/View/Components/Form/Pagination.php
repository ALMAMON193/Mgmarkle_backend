<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Pagination extends Component
{
    public $paginator;
    public $pageRange;

    public function __construct($paginator, $pageRange)
    {
        $this->paginator = $paginator;
        $this->pageRange = $pageRange;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.pagination');
    }
}
