<?php
/**
 * User: yuan
 * Date: 16/8/19
 * Time: 13:04
 */

namespace App\Support;

use Mailgun\Mailgun as MailgunClient;

class Mailgun
{
    /**
     * @var MailgunClient
     */
    protected $mailgun;

    /**
     * @var string
     */
    protected $domain;

    public function __construct()
    {
        $this->domain = \Config::get('services.mailgun.domain');
        $key = \Config::get('services.mailgun.secret');
        $this->mailgun = new MailgunClient($key);
    }

    private function returnBody($res)
    {
        if ($res && $res->http_response_body) {
            return $res->http_response_body;
        } else {
            return null;
        }
    }

    public function sendMail($from, $to, $subject, $text)
    {
        $this->mailgun->sendMessage($this->domain, array('from' => $from,
            'to' => $to,
            'subject' => $subject,
            'text' => $text
        ));
    }

    public function log($page)
    {
        $res = $this->mailgun->get("$this->domain/log", array('limit' => 25,
            'skip' => ($page - 1) * 25));

        return $this->returnBody($res);
    }

    public function lists()
    {
        $res = $this->mailgun->get('lists/pages', [
            'limit' => 50
        ]);

        return $this->returnBody($res);
    }

    public function setMember($listAddress, $address, $name, $description, $subscribed = true)
    {
        return $result = $this->mailgun->post("lists/$listAddress/members", array(
            'address' => 'bar@example.com',
            'name' => 'Bob Bar',
            'description' => 'Developer',
            'subscribed' => true,
            'vars' => '{"age": 26}'
        ));
    }

    public function getMembers($listAddress)
    {
        $result = $this->mailgun->get("lists/$listAddress/members/pages", array(
            'subscribed' => 'yes',
            'limit' => 100
        ));

        return $this->returnBody($result);
    }

    public function getCampaigns($id)
    {
        return $this->mailgun->get("$this->domain/campaigns/$id");
    }
}
