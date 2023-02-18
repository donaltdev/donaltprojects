<?php

declare(strict_types=1);

namespace Classes\LutekDev\Commands;

use Classes\LutekDev\Manager\ClassesManager;
use Classes\LutekDev\Manager\UtilsManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class setClassesCommand extends Command {
	public function __construct() {
		parent::__construct("setclasse", "Comando para setar a classe do Jogador criado por LutekDev#7002");
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
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