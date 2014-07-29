<?php namespace Nurmanhabib\Message;

use Illuminate\Support\ServiceProvider;

class MessageServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind('Message', function()
        {
            return new Message;
        });

        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Message', 'Nurmanhabib\Message\Facades\Message');
        });
    }

}