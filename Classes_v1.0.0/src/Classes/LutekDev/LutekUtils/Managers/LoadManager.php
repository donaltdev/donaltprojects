<?php

declare(strict_types=1);

namespace Classes\LutekDev\LutekUtils\Managers;

use Classes\LutekDev\Commands\joinClassesCommand;
use Classes\LutekDev\Commands\setClassesCommand;
use Classes\LutekDev\LutekUtils\Events\PlayerListener;
use Classes\LutekDev\LutekUtils\Task\ClassesTask;

class LoadManager {
	public function __construct() {
		new ConfigManager();
		self::onLoadTasks();
		self::onLoadCommands();
		self::onLoadListeners();
	}

	public function onLoadListeners() : void {
		UtilsManager::getPlugin()->getServer()->getPluginManager()->registerEvents(new PlayerListener(), UtilsManager::getPlugin());
	}

	public function onLoadTasks() : void {
		UtilsManager::getPlugin()->getScheduler()->scheduleRepeatingTask(new ClassesTask(), 20);
	}

	public function onLoadCommands() : void {
		UtilsManager::getPlugin()->getServer()->getCommandMap()->register("joinClassesCommand", new joinClassesCommand());

		UtilsManager::getPlugin()->getServer()->getCommandMap()->register("setClassesCommand", new setClassesCommand());
	}
}