<?php

/*
 * This file is part of develophub.net.
 *
 * (c) DevelopHub <master@develophub.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Admin;

use App\Entities\Repos;
use App\Http\Controllers\Controller;
use App\Support\Mailgun;

class MailController extends Controller
{
    /**
     * @var Mailgun
     */
    protected $mailgun;

    public function __construct()
    {
        $this->mailgun = new Mailgun();
    }

    public function template()
    {
        return view('admin.mail.template');
    }

    public function template_data()
    {
        return \Datatables::of(Repos::query())

            ->addColumn('action', function($repos){
                return '<a href="#edit-'.$repos->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
            })
            ->editColumn('id', 'ID: {{ $id }}')
            ->make(true);
    }

    public function subscriber()
    {
        $subscriber = $this->mailgun->lists();
        $subscriber = $subscriber ? $subscriber->items : [];

        return view('admin.mail.subscriber', compact('subscriber'));
    }

    public function members($address)
    {
        $members = $this->mailgun->getMembers($address);
        $members = $members ? $members->items : [];

        return view('admin.mail.members', compact('members'));
    }

    public function publish()
    {
        return view('admin.mail.publish');
    }
}
