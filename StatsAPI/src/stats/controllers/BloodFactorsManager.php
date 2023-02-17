<?php


namespace stats\controllers;


use pocketmine\utils\Config;
use stats\entities\bfs\BloodFactor;
use stats\Loader;

class BloodFactorsManager
{

	/** @var BloodFactor[]  */
	public static $bloodFactors = [];

	public static function init(Config $config)
	{
		$data = [
			"defense" => 0,
			"damage" => 0,
			"max_health" => 0,
			"superpower" => 0,
			"permission" => "none",
			"name" => "none"
		];

		$all = $config->getAll();

		foreach ($all as $name => $value) {
			if (is_array($value)) {
				$check = true;
				foreach ($data as $key => $val) {
					if (!isset($all[$name][$key])) {
						Loader::getInstance()->getLogger()->info("Erro no carregamento da bf '$name': Missing key '$key'");
						$check = false;
					}
				}
				if ($check) {
					self::$bloodFactors[$name] = new BloodFactor($name, (int)$all[$name]["damage"], (int)$all[$name]["max_health"],
						(int)$all[$name]["defense"], (int)$all[$name]["superpower"]);
				}
			}
		}
	}

	/**
	 * @return BloodFactor[]
	 */
	public static function getBloodFactors(): array
	{
		return self::$bloodFactors;
	}

	/**
	 * @param string $search
	 * @return BloodFactor|null
	 */
	public static function getBloodByName(?string $search): ?BloodFactor
	{
		$search = strtolower($search);
		if (!is_null($search) && isset(self::$bloodFactors[$search])) {
			return self::$bloodFactors[$search];
		}
		return null;
	}

}