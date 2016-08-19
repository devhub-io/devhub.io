<?php
/**
 * User: yuan
 * Date: 16/8/19
 * Time: 13:17
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
