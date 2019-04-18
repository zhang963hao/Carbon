<?php

namespace Carbon;

use Composer\Script\Event;

class Upgrade
{
    protected static $laravelLibraries = array(
        'laravel/framework' => '5.8.0',
        'laravel/cashier' => '9.0.1',
        'illuminate/support' => '5.8.0',
        'laravel/dusk' => '5.0.0',
    );

    protected static $otherLibraries = array(
        'spatie/laravel-analytics' => '3.6.4',
        'jenssegers/date' => '3.5.0',
    );

    public static function warn(Event $event)
    {
        $event->getIO()->write(array(
            '**********************************************',
            " /!\ Warning, you're using a end-of-life",
            ' ¨¨¨ version of Carbon',
            '**********************************************',
        ));
    }

    public static function upgrade(Event $event)
    {
        $package = dirname($event->getComposer()->getConfig()->get('vendor-dir')).'/composer.json';
        $data = json_decode(file_get_contents($package));

        foreach (array('', '-dev') as $environment) {
            if (isset($data["require$environment"], $data["require$environment"]['nesbot/carbon'])) {
                $data["require$environment"]['nesbot/carbon'] = '^2.0';
            }
        }

        file_put_contents($package, json_encode($data, JSON_PRETTY_PRINT));
    }
}
