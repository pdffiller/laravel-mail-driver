<?php

namespace Pdffiller\LaravelMailDriver;

use Illuminate\Mail\MailServiceProvider as LaravelMailServiceProvider;
use Pdffiller\LaravelMailDriver\Transport\PdffillerTransport;

class MailServiceProvider extends LaravelMailServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSwiftMailer();

        $this->app->singleton('mailer', function ($app) {
            // Once we have create the mailer instance, we will set a container instance
            // on the mailer. This allows us to resolve mailer classes via containers
            // for maximum testability on said classes instead of passing Closures.
            $mailer = new Mailer(
                $app['view'], $app['swift.mailer'], $app['events']
            );

            $this->setMailerDependencies($mailer, $app);

            // If a "from" address is set, we will set it on the mailer so that all mail
            // messages sent by the applications will utilize the same "from" address
            // on each one, which makes the developer's life a lot more convenient.
            $from = $app['config']['mail.from'];

            if (is_array($from) && isset($from['address'])) {
                $mailer->alwaysFrom($from['address'], $from['name']);
            }

            $to = $app['config']['mail.to'];

            if (is_array($to) && isset($to['address'])) {
                $mailer->alwaysTo($to['address'], $to['name']);
            }

            return $mailer;
        });
    }

    /**
     * Register the Swift Mailer instance.
     *
     * @return void
     */
    public function registerSwiftMailer()
    {
        if ($this->app['config']['mail.driver'] === 'pdffiller') {
            $this->registerPdffillerSwiftMailer();
        } else {
            parent::registerSwiftMailer();
        }
    }

    protected function registerPdffillerSwiftMailer()
    {
        $this->app['swift.mailer'] = $this->app->share(function ($app) {
            $config = $app['config']->get('services.pdffiller', []);

            return new \Swift_Mailer(new PdffillerTransport(
                $config['username'], $config['password'], $config['host'], $config['schema']));
        });
    }
}
