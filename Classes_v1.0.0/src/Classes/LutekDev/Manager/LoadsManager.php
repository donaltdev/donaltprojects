<?php

declare(strict_types=1);

namespace Classes\LutekDev\Manager;

use Classes\LutekDev\Commands\joinClassesCommand;
use Classes\LutekDev\Commands\setClassesCommand;
use Classes\LutekDev\Listeners\PlayerListener;

class LoadsManager {
	public function __construct() {
		new ConfigManager();
		self::onLoadCommands();
		self::onLoadListeners();
	}

	public function onLoadListeners() : void {
		UtilsManager::getPlugin()->getServer()->getPluginManager()->registerEvents(new PlayerListener(), UtilsManager::getPlugin());
	}

	public function onLoadCommands() : void {
		UtilsManager::getPlugin()->getServer()->getCommandMap()->register("joinClassesCommand", new joinClassesCommand());

		UtilsManager::getPlugin()->getServer()->getCommandMap()->register("setClassesCommand", new setClassesCommand());
	}
}