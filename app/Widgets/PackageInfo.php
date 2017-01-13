<?php

namespace App\Widgets;

use App\Entities\Package;
use Arrilot\Widgets\AbstractWidget;

class PackageInfo extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'package' => null,
        'config' => ''
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function run()
    {
        return view("widgets.packages.{$this->config['package']->provider}", [
            'config' => $this->config,
            'package' => $this->config['package'],
        ]);
    }
}
