<?php

namespace Carbon;

use Composer\Script\Event;

class Upgrade
{
    protected static $laravelLibraries = array(
        'laravel/framework',
        'laravel/cashier',
        'illuminate/support',
        'laravel/dusk',
    );

    protected static $otherLibraries = array(
        'spatie/laravel-analytics',
        'jenssegers/date',
    );

    /**
     * andersao/l5-repository
     * Zizaco/entrust
     * tymondesigns/jwt-auth
     * beberlei/DoctrineExtensions
     */

    public static function warn(Event $event)
    {
        $event->getIO()->write(array(
            '**********************************************',
            " /!\ Warning, you're using a end-of-life",
            'version of Carbon',
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
