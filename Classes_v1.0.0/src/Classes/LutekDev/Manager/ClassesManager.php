<?php

declare(strict_types=1);

namespace Classes\LutekDev\Manager;

use JsonException;
use pocketmine\permission\PermissionAttachment;
use pocketmine\Player;

class ClassesManager {
	public static array $attachmentCache = [];

	public static function hasClasse(Player $player) : bool {
		return ConfigManager::$player->exists(strtolower($player->getName()));
	}

	public static function getClasse(Player $player) {
		return self::hasClasse($player) ? ConfigManager::$player->get(strtolower($player->getName()))["classe"] : "Sem Classe";
	}

	public static function existsClasse(string $classe) : bool {
		return ConfigManager::$classes->exists($classe);
	}


	public static function getClassePerms(string $classe) {
		return ConfigManager::$classes->get($classe)["perms"];
	}

	public static function getAttachment(Player $player) : PermissionAttachment {
		if (!isset(self::$attachmentCache[$index = strtolower($player->getName())])) {
			self::$attachmentCache[$index] = $player->addAttachment(UtilsManager::getPlugin());
		}
		return self::$attachmentCache[$index];
	}

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

	/**
	 * @param Player $player
	 * @param string $classe
	 */
	public static function joinClassePlayer(Player $player, string $classe) : void {
		if (self::hasClasse($player)) return;

		ConfigManager::$player->set(strtolower($player->getName()), ["classe" => $classe]);
		ConfigManager::$player->save();
		self::updateClassesPerms($player);
	}

	/**
	 * @param Player $player
	 * @param string $classe
	 */
	public static function setClassePlayer(Player $player, string $classe) : void {
		if (self::hasClasse($player)) {
			ConfigManager::$player->remove(strtolower($player->getName()));
			ConfigManager::$player->save();
		}

		ConfigManager::$player->set(strtolower($player->getName()), ["classe" => $classe]);
		ConfigManager::$player->save();

		self::updateClassesPerms($player);
	}
}