<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class PackageInfo extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        //

        return view("widgets.package_info", [
            'config' => $this->config,
        ]);
    }
}