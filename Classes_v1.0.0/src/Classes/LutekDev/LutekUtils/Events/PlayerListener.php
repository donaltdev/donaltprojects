<?php

declare(strict_types=1);

namespace Classes\LutekDev\LutekUtils\Events;

use Classes\LutekDev\LutekUtils\Managers\ClassesManager;
use Ifera\ScoreHud\event\TagsResolveEvent;
use LutekRest\LutekDev\LutekUtils\Managers\ListenerManager;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class PlayerListener extends ListenerManager {
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

	#Function to run the ScoreHud Tag;
	public static function onTagResolve(TagsResolveEvent $event) : void {
		$player = $event->getPlayer();
		$tag = $event->getTag();
		$tags = explode(".", $tag->getName(), 2);
		$value = "";

		if ($tags[0] !== "classes" || count($tags) < 2) return;

		if ($tags[1] == "name") {
			$value = ClassesManager::getClasse($player);
		}
	}
}