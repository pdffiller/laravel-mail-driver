<?php

namespace Pdffiller\LaravelMailDriver\Transport;

use Illuminate\Mail\Transport\Transport;
use Pdffiller\MailApi\Backend\ApiHub;
use Pdffiller\MailApi\MailApiBundle;
use Swift_Mime_Message;

class PdffillerTransport extends Transport
{
    /**
     * Pdffiller api instance.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $api;

    /**
     * Create a new Pdffiller transport instance.
     *
     * @param $username
     * @param $password
     * @param $host
     * @param $schema
     *
     * @return void
     */
    public function __construct($username, $password, $host, $schema)
    {
        $mailApiBundle = new MailApiBundle();
        $client = $mailApiBundle->setHost($host)
                                ->setAuth($username, $password)
                                ->setScheme($schema)
                                ->getClient();
        $this->api = new ApiHub($client);
    }

    /**
     * {@inheritdoc}
     */
    public function send(Swift_Mime_Message $message, &$failedRecipients = null)
    {
        $to = ['email' => '', 'name' => ''];
        foreach ($message->getTo() as $key => $value) {
            $to['email'] = $key;
            $to['name'] = $value;
        }
        $from = '';
        foreach ($message->getFrom() as $item) {
            $from = $item;
        }
        $options = [
            "category_id" => 1,
            "to" => $to,
            "callback" => "",
            "callback_params" => [],
            "track" => [
                "some" => "test"
            ],
            "template_name" => "some_name",
            "message" => $message->getBody()
        ];
        $this->api->sendMail($options);

        return $this->api->getResponse();
    }

    /**
     * @param $email
     *
     * @return array
     */
    public function checkEmail($email)
    {
        return $this->api->checkEmail($email);
    }
}