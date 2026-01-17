<?php

namespace App\View\Components;

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

    public function render()
    {
        return view('components.pagination');
    }
}
