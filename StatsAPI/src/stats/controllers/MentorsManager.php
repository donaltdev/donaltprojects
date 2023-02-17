<?php

namespace stats\controllers;

use pocketmine\utils\Config;
use stats\entities\mentors\Mentor;
use stats\Loader;

class MentorsManager
{

	/** @var Mentor[]  */
	public static $mentors = [];

	public static function init(Config $config)
	{
		$data = [
			"defense" => 0,
			"damage" => 0,
			"max_health" => 0,
			"superpower" => 0,
			"permission" => "none",
			"name" => "none",
			"command" => "command",
			"time-active" => 0,
			"time-countdown" => 0
		];

		$all = $config->getAll();

		foreach ($all as $name => $value) {
			if (is_array($value)) {
				$check = true;
				foreach ($data as $key => $val) {
					if (!isset($all[$name][$key])) {
						Loader::getInstance()->getLogger()->info("Erro no carregamento do mentor '$name': Missing key '$key'");
						$check = false;
					}
				}
				if ($check) {
					self::$mentors[$name] = new Mentor($name, (int)$all[$name]["damage"], (int)$all[$name]["max_health"],
						(int)$all[$name]["defense"], (int)$all[$name]["superpower"], (int)$all[$name]["time-active"],
						(int)$all[$name]["time-countdown"]);
				}
			}
		}
	}

	/**
	 * @return Mentor[]
	 */
	public static function getMentors(): array
	{
		return self::$mentors;
	}

	/**
	 * @param string $search
	 * @return Mentor|null
	 */
	public static function getMentorByName(?string $search): ?Mentor
	{
		$search = strtolower($search);
		if (!is_null($search) && isset(self::$mentors[$search])) {
			return self::$mentors[$search];
		}
		return null;
	}

}