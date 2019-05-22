<?php

namespace Carbon;

use Composer\Composer;
use Composer\EventDispatcher\Event;
use Composer\IO\IOInterface;
use Composer\Script\Event as ScriptEvent;
use UpdateHelper\UpdateHelperInterface;

class Upgrade implements UpdateHelperInterface
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

    /**
     * @param \Composer\Installer\PackageEvent|\Composer\Script\Event $event
     * @param \Composer\IO\IOInterface                               $io
     * @param \Composer\Composer                                     $composer
     */
    public function check(Event $event, IOInterface $io, Composer $composer)
    {
        $io->write(array(
            '**********************************************',
            " /!\ Warning, you're using a deprecated",
            ' ¨¨¨ version of Carbon, we will soon stop',
            '     providing support and update for 1.x',
            '     versions, please upgrade to Carbon 2.',
            '**********************************************',
        ));
    }

    public static function upgrade(ScriptEvent $event)
    {
        $package = dirname($event->getComposer()->getConfig()->get('vendor-dir')).'/composer.json';
        $data = json_decode(file_get_contents($package), JSON_OBJECT_AS_ARRAY);

        foreach (array('', '-dev') as $environment) {
            if (isset($data["require$environment"], $data["require$environment"]['nesbot/carbon'])) {
                $data["require$environment"]['nesbot/carbon'] = '^2.0';
            }
        }

        file_put_contents($package, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");
    }
}
