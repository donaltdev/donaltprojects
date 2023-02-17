<?php

declare(strict_types=1);

namespace Classes\LutekDev;

use Classes\LutekDev\LutekUtils\Managers\LoadManager;
use Classes\LutekDev\LutekUtils\Managers\UtilsManager;
use pocketmine\plugin\PluginBase as PB;

class Classes extends PB {
	public static Classes $instance;

	protected function onLoad() : void {
		self::$instance = $this;
		UtilsManager::checkAPI();
	}

	protected function onEnable() : void {
		new LoadManager();
	}

	public static function getInstance() : Classes {
		return self::$instance;
	}
}