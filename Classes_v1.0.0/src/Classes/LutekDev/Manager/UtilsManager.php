<?php

declare(strict_types=1);

namespace Classes\LutekDev\Manager;

use Classes\LutekDev\Classes;

class UtilsManager {
	public static function getPlugin() : Classes {
		return Classes::getInstance();
	}

	public static function getMessageLang(string $msg) : string {
		$file = ConfigManager::$lang;
		return $file->get($msg);
	}
}