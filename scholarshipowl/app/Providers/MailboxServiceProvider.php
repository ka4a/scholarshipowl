<?php namespace App\Providers;

use App\Services\MailboxService;
use Doctrine\Common\Persistence\ManagerRegistry;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

use Horde_Imap_Client_Socket;

class MailboxServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->registerImapClient();
        $this->registerMailboxService();
    }

    public function registerImapClient()
    {
        $this->app->alias(Horde_Imap_Client_Socket::class, 'horde.client');
        $this->app->singleton(Horde_Imap_Client_Socket::class, function() {
            return new Horde_Imap_Client_Socket(config('horde'));
        });
    }

    public function registerMailboxService()
    {
        $this->app->alias(MailboxService::class, 'mailbox');
        $this->app->singleton(MailboxService::class, function(Application $app) {
            return new MailboxService(
                $app->make(Horde_Imap_Client_Socket::class),
                $app->make(ManagerRegistry::class)->getManager('emails')
            );
        });
    }
}
