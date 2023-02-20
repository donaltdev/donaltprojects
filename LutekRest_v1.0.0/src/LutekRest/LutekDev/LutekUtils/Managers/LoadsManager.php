<?php

declare(strict_types=1);

namespace LutekRest\LutekDev\LutekUtils\Managers;

use LutekRest\LutekDev\LutekLibs\Tools\FormImagesFix\FormImagesFix;
use LutekRest\LutekDev\LutekLibs\Tools\PlayerSelectors\Events\PlayerSelectorsListener;

class LoadsManager {
    public function __construct() {
        self::onLoadListener();
    }

    # Função p/ Carregar Eventos
    protected function onLoadListener() : void {
        UtilsManager::getPlugin()->getServer()->getPluginManager()->registerEvents(new ListenerManager(), UtilsManager::getPlugin());
        UtilsManager::getPlugin()->getServer()->getPluginManager()->registerEvents(new FormImagesFix(), UtilsManager::getPlugin());
        UtilsManager::getPlugin()->getServer()->getPluginManager()->registerEvents(new PlayerSelectorsListener(), UtilsManager::getPlugin());
    }
}
