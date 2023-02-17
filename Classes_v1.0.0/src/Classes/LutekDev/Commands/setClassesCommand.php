<?php

declare(strict_types=1);

namespace Classes\LutekDev\Commands;

use Classes\LutekDev\LutekUtils\Managers\ClassesManager;
use Classes\LutekDev\LutekUtils\Managers\UtilsManager;
use JsonException;
use LutekRest\LutekDev\LutekUtils\Commands\CommandBase;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class setClassesCommand extends CommandBase {
	public function __construct() {
		parent::__construct("setclasse", "Comando para setar a classe do Jogador criado por LutekDev#7002");
	}

	/** @throws JsonException */
	public function lutekExec(CommandSender $sender, string $label, array $args) {
		if ($sender instanceof Player) {
			if (empty($args)) {
				$sender->sendMessage(UtilsManager::getMessageLang("SETCLASSE_COMMAND_ERROR"));
				return;
			}

			$player = $sender->getServer()->getPlayerExact($args[0]);

			if ($player == null) {
				$sender->sendMessage(UtilsManager::getMessageLang("PLAYER_OFFLINE"));
				return;
			}

			if (empty($args[1]) || !ClassesManager::existsClasse($args[1])) {
				$sender->sendMessage(UtilsManager::getMessageLang("CLASSE_NOT_EXISTS"));
				return;
			}

			ClassesManager::setClassePlayer($player, $args[1]);
			$sender->sendMessage(str_replace(array ("{player}", "{classe}"), array ($player->getName(), $args[1]), UtilsManager::getMessageLang("CLASSE_SET_SUCESS")));
		} else {
			$sender->sendMessage(UtilsManager::getMessageLang("ERROR_CONSOLE"));
		}
	}
}