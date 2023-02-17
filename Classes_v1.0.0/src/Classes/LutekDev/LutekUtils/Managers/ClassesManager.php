<?php

declare(strict_types=1);

namespace Classes\LutekDev\LutekUtils\Managers;

use Ifera\ScoreHud\event\PlayerTagsUpdateEvent;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use JsonException;
use pocketmine\permission\PermissionAttachment;
use pocketmine\player\Player;

class ClassesManager {
	public static array $attachmentCache = [];

	#Function to see if the player has class;
	public static function hasClasse(Player $player) : bool {
		return ConfigManager::$player->exists(strtolower($player->getName()));
	}

	#Function to get a player's class;
	public static function getClasse(Player $player) {
		return self::hasClasse($player) ? ConfigManager::$player->get(strtolower($player->getName()))["classe"] : "Sem Classe";
	}

	#Function to see if the class really exists;
	public static function existsClasse(string $classe) : bool {
		return ConfigManager::$classes->exists($classe);
	}


	#Function to get class permissions;
	public static function getClassePerms(string $classe) {
		return ConfigManager::$classes->get($classe)["perms"];
	}

	#Function to get the permissions cache;
	public static function getAttachment(Player $player) : PermissionAttachment {
		if (!isset(self::$attachmentCache[$index = strtolower($player->getName())])) {
			self::$attachmentCache[$index] = $player->addAttachment(UtilsManager::getPlugin());
		}
		return self::$attachmentCache[$index];
	}

	#Function to update player's clan permissions;
	public static function updateClassesPerms(Player $player) : void {
		if (self::hasClasse($player)) {
			$classe = self::getClasse($player);

			$attach = self::getAttachment($player);
			$attach->clearPermissions();

			foreach (self::getClassePerms($classe) as $perm) {
				$attach->setPermission($perm, true);
			}
		}
	}

	/** @throws JsonException */
	#Function to join the class in player;
	public static function joinClassePlayer(Player $player, string $classe) : void {
		if (self::hasClasse($player)) return;

		ConfigManager::$player->set(strtolower($player->getName()), ["classe" => $classe]);
		ConfigManager::$player->save();
		self::updateClassesPerms($player);
	}

	/** @throws JsonException */
	#Function to set the class in a player;
	public static function setClassePlayer(Player $player, string $classe) : void {
		if (self::hasClasse($player)) {
			ConfigManager::$player->remove(strtolower($player->getName()));
			ConfigManager::$player->save();
		}

		ConfigManager::$player->set(strtolower($player->getName()), ["classe" => $classe]);
		ConfigManager::$player->save();

		self::updateClassesPerms($player);
	}

	#Function to update ScoreHud
	public static function sendUpdate(Player $player) : void {
		(new PlayerTagsUpdateEvent($player, [new ScoreTag("classes.name", self::getClasse($player))]))->call();
	}
}