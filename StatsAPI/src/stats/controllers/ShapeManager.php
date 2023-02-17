<?php

namespace stats\controllers;

use InvalidArgumentException;
use pocketmine\entity\Skin;
use pocketmine\utils\Config;
use stats\entities\shape\Shape;
use stats\entities\utils\Utils;
use stats\Loader;

class ShapeManager
{

	/** @var Shape[] */
	private static $shapes = [];

	public static function init(Config $config)
	{
		$data = [
			"defense" => 0,
			"damage" => 0,
			"max_health" => 0,
			"superpower" => 0,
			"permission" => "none",
			"name" => "none",
			"command" => "none",
			"time-active" => 0,
			"time-countdown" => 0,
			"geometry-json" => "none",
			"geometry-img" => "none",
			"geometry-name" => "none"
		];

		$all = $config->getAll();

		foreach ($all as $name => $value) {
			if (is_array($value)) {
				$check = true;
				foreach ($data as $key => $val) {
					if (!isset($all[$name][$key])) {
						Loader::getInstance()->getLogger()->info("Erro no carregamento da forma '$name': Missing key '$key'");
						$check = false;
					}
				}
				if ($check) {
					$jsonPath = Loader::getInstance()->getDataFolder()."/".$value["geometry-json"];
					$imgPath = Loader::getInstance()->getDataFolder() . "/" . $value["geometry-img"];
					if (file_exists($jsonPath) && file_exists($imgPath)) {
						$json = file_get_contents($jsonPath);
						$skin = new Skin($name, Utils::skinDataFromImage(imagecreatefrompng($imgPath)), "", $value["geometry-name"], $json);
						try {
							$skin->validate();
							self::$shapes[strtolower($name)] = new Shape($name, $skin);
						} catch (InvalidArgumentException $exception) {
							Loader::getInstance()->getLogger()->info("Erro no carregamento da skin '$name': {$exception->getMessage()}");
						}
					} else {
						Loader::getInstance()->getLogger()->info("Arquivo .json ou o .png da forma '$name' não existe!");
					}
				}
			}
		}
	}

	/**
	 * @return Shape[]
	 */
	public static function getShapes(): array
	{
		return self::$shapes;
	}

	public static function getShapeByName(string $search): ?Shape
	{
		$search = strtolower($search);
		if (isset(self::$shapes[$search])) {
			return self::$shapes[$search];
		}
		return null;
	}

}