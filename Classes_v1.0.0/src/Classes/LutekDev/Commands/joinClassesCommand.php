<?php

namespace Classes\LutekDev\Commands;

use Classes\LutekDev\LutekUtils\Managers\ClassesManager;
use Classes\LutekDev\LutekUtils\Managers\UtilsManager;
use JsonException;
use LutekRest\LutekDev\LutekUtils\Commands\CommandBase;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class joinClassesCommand extends CommandBase {
	public function __construct() {
		parent::__construct("classe", "Comando para entrar em uma classe, criado por LutekDev#7002");
	}

	/** @throws JsonException */
	public function lutekExec(CommandSender $sender, string $label, array $args) {
		if ($sender instanceof Player) {
			if (empty($args)) {
				$sender->sendMessage(UtilsManager::getMessageLang("JOINCLASSE_COMMAND_ERROR"));
				return;
			}

			if (empty($args[0]) || !ClassesManager::existsClasse($args[0])) {
				$sender->sendMessage(UtilsManager::getMessageLang("CLASSE_NOT_EXISTS"));
				return;
			}

			if (ClassesManager::hasClasse($sender)) {
				$sender->sendMessage(UtilsManager::getMessageLang("JOINCLASSE_HASCLAN_ERROR"));
				return;
			}

			ClassesManager::joinClassePlayer($sender, $args[0]);
			$sender->sendMessage(str_replace("{classe}", $args[0], UtilsManager::getMessageLang("JOINCLASSE_JOIN_SUCESS")));
		} else {
			$sender->sendMessage(UtilsManager::getMessageLang("ERROR_CONSOLE"));
		}
	}
}