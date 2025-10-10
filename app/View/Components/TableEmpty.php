<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TableEmpty extends Component
{
    public $colspan;

    public $icon;

    public $title;

    public $message;

    public function __construct($colspan = 8, $icon = 'fas fa-inbox', $title = 'No data found', $message = 'Try adjusting your search or filters')
    {
        $this->colspan = $colspan;
        $this->icon = $icon;
        $this->title = $title;
        $this->message = $message;
    }

    public function render()
    {
        return view('components.table-empty');
    }
}
