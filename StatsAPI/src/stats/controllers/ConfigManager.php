<?php


namespace stats\controllers;


use pocketmine\utils\Config;

class ConfigManager
{

	/** @var Config */
	private $config;

	/**
	 * ConfigManager constructor.
	 * @param Config $config
	 */
	public function __construct(Config $config)
	{
		$this->config = $config;
		$this->loadConfig();
	}

	private function loadConfig(): void
	{
		$cfg = $this->config->getAll();

		/** load utils config */
		$default = [
			"initial_health" => 10000,
			"initial_damage" => 500,
			"initial_defense" => 10000,
			"initial_superpower" => 500,
			"max_health" => 500000,
			"max_damage" => 50000,
			"max_defense" => 500000,
			"max_superpower" => 50000,
			"price_upgrade" => 5
		];
		foreach ($default as $key => $value) {
			if (is_null($this->getValue($key))) {
				$cfg[$key] = $value;
			}
		}

		$this->config->setAll($cfg);
		$this->config->save();
	}

	public function getValue(string $key)
	{
		if ($this->config->exists($key)) {
			return $this->config->get($key);
		}
		return null;
	}

	public function getKeyValue(string $key, string $value)
	{
		$cfg = $this->config->getAll();
		if (isset($cfg[$key][$value])) {
			return $cfg[$key][$value];
		}
		return null;
	}

}