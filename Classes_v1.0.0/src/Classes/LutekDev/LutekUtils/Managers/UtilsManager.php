<?php

declare(strict_types=1);

namespace Classes\LutekDev\LutekUtils\Managers;

use Classes\LutekDev\Classes;
use Classes\LutekDev\LutekUtils\Messages\LutekMessages;

class UtilsManager {

	#Checking if the API exists;
	public static function checkAPI() : void {
		if (self::getPlugin()->getServer()->getPluginManager()->getPlugin("LutekRest")) return;
		self::getPlugin()->getLogger()->alert(LutekMessages::API_ERROR);
	}

	#Function to pull the Plugin instance;
	public static function getPlugin() : Classes {
		return Classes::getInstance();
	}

	#Function to get the messages from the pt_br.yml file
	public static function getMessageLang(string $msg) : string {
		$file = ConfigManager::$lang;
		return $file->get($msg);
	}
}