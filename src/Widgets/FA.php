<?php

namespace Itiden\FA\Widgets;

use Statamic\Widgets\Widget;

class FA extends Widget
{
    public static $handle = 'fa_widget';

    /**
     * The HTML that should be shown in the widget.
     *
     * @return string|\Illuminate\View\View
     */
    public function html()
    {
        return view('fa_widget::widgets.fa');
    }
}
