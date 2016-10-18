<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Admin;

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
        return [];
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
