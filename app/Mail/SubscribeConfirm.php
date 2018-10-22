<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Mail;

use Cache;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscribeConfirm extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    protected $mail;

    /**
     * Create a new message instance.
     *
     * @param $mail
     */
    public function __construct($mail)
    {
        $this->mail = $mail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $slug = str_random();
        Cache::put("mail:$slug", $this->mail, 35 * 24 * 60);

        return $this->view('emails.subscribe.confirm')
            ->with('confirm_url', $this->_sign(url('subscribe/confirm?slug='.$slug), 31));
    }

    /**
     * UrlSigner
     */
    private function _sign() {
        // TODO
    }
}
