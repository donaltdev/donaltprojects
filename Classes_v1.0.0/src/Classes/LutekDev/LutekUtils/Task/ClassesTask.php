<?php

declare(strict_types=1);

namespace Classes\LutekDev\LutekUtils\Task;

use Classes\LutekDev\LutekUtils\Managers\ClassesManager;
use Classes\LutekDev\LutekUtils\Managers\ConfigManager;
use Classes\LutekDev\LutekUtils\Managers\UtilsManager;
use pocketmine\scheduler\Task;

class ClassesTask extends Task {
	public function onRun() : void {
		foreach (UtilsManager::getPlugin()->getServer()->getOnlinePlayers() as $player) {
			ClassesManager::sendUpdate($player);
		}
		ConfigManager::$classes->reload();
		ConfigManager::$lang->reload();
	}
}