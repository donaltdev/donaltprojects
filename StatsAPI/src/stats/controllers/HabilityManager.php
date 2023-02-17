<?php

namespace stats\controllers;

use pocketmine\utils\Config;
use stats\entities\hability\Hability;

class HabilityManager
{

	/** @var Hability[] */
	private static $habilities = [];

	public static function init(Config $config){
		$data = [
			"defense" => 0,
			"damage" => 0,
			"max_health" => 0,
			"price" => 100
		];

		$all = $config->getAll();

		foreach ($all as $name => $value) {
			if(is_array($value)) {
				$check = true;
				foreach ($data as $key => $val) {
					if (!isset($all[$name][$key])) {
						$check = false;
					}
				}
				if($check){
					self::$habilities[strtolower($name)] = new Hability($name);
				}
			}
		}
	}

	/**
	 * @return Hability[]
	 */
	public static function getHabilities(): array
	{
		return self::$habilities;
	}

	public static function getHabilityByName(string $search): ?Hability
	{
		$search = strtolower($search);
		if(isset(self::$habilities[$search])){
			return self::$habilities[$search];
		}
		return null;
	}

}