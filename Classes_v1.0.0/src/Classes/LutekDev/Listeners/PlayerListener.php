<?php

declare(strict_types=1);

namespace Classes\LutekDev\Listeners;

use Classes\LutekDev\Manager\ClassesManager;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class PlayerListener implements Listener {
	public function onJoin(PlayerJoinEvent $event) : void {
		ClassesManager::updateClassesPerms($event->getPlayer());
	}

	public function onQuit(PlayerQuitEvent $event) : void {
		$player = $event->getPlayer();

		if (isset(ClassesManager::$attachmentCache[$index = strtolower($player->getName())])) {
			$player->removeAttachment(ClassesManager::$attachmentCache[$index]);
			unset(ClassesManager::$attachmentCache[$index]);
		}
	}
}