<?php

namespace Pdffiller\LaravelMailDriver;

use Illuminate\Mail\Mailer as LaravelMailer;

class Mailer extends LaravelMailer
{
    /**
     * @param $email
     *
     * @return mixed
     */
    public function checkEmail($email)
    {
        return $this->swift->getTransport()->checkEmail($email);
    }
}
