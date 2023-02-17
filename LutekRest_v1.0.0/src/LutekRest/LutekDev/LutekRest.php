<?php

declare(strict_types=1);

namespace LutekRest\LutekDev;

use LutekRest\LutekDev\LutekUtils\Configs\ConfigUtils;
use LutekRest\LutekDev\LutekUtils\Managers\LicenseManager;
use LutekRest\LutekDev\LutekUtils\Managers\LoadsManager;
use pocketmine\plugin\PluginBase as PB;

class LutekRest extends PB {
    public static LutekRest $instance;

    protected function onLoad() : void {
        self::$instance = $this; new ConfigUtils();
    }

    protected function onEnable() : void {
        new LoadsManager(); LicenseManager::checkPlugin(); LoadsManager::onLoadPersonaSkinEnable();
    }

	protected function onDisable() : void {
		LoadsManager::onLoadPersonaSkinDisable();
	}

	public static function getInstance() : LutekRest {
        return self::$instance;
    }
}