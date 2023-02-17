<?php

declare(strict_types=1);

namespace LutekRest\LutekDev\LutekUtils\Managers;

use LutekRest\LutekDev\LutekLibs\Tools\FormImagesFix\FormImagesFix;
use LutekRest\LutekDev\LutekLibs\Tools\PersonaSkin\PersonaSkin;
use LutekRest\LutekDev\LutekLibs\Tools\PlayerSelectors\Events\PlayerSelectorsListener;
use pocketmine\network\mcpe\convert\SkinAdapter;
use pocketmine\network\mcpe\convert\SkinAdapterSingleton;

class LoadsManager {
	protected static ?SkinAdapter $skinAdapter = null;

    public function __construct() {
        self::onLoadListener();
    }

    # Função p/ Carregar Eventos
    protected function onLoadListener() : void {
        UtilsManager::getPlugin()->getServer()->getPluginManager()->registerEvents(new ListenerManager(), UtilsManager::getPlugin());
        UtilsManager::getPlugin()->getServer()->getPluginManager()->registerEvents(new FormImagesFix(), UtilsManager::getPlugin());
        UtilsManager::getPlugin()->getServer()->getPluginManager()->registerEvents(new PlayerSelectorsListener(), UtilsManager::getPlugin());
    }

	#Função p/ Carregar o PersonaSkin ao Ligar;
	public static function onLoadPersonaSkinEnable() : void {
		self::$skinAdapter = SkinAdapterSingleton::get();
		SkinAdapterSingleton::set(new PersonaSkin());
	}

	#Função p/ Carregar o PersonaSkin ao Desligar;
	public static function onLoadPersonaSkinDisable() : void {
		if (self::$skinAdapter !== null) {
			SkinAdapterSingleton::set(self::$skinAdapter);
		}
	}
}
