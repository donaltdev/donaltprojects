<?php

declare(strict_types=1);

namespace Classes\LutekDev;

use Classes\LutekDev\Manager\LoadsManager;
use pocketmine\plugin\PluginBase;

class Classes extends PluginBase {
	public static Classes $instance;

	public function onLoad() {
		self::$instance = $this;
	}

	public function onEnable() {
		new LoadsManager();
	}

	public static function getInstance() : Classes {
		return self::$instance;
	}
}