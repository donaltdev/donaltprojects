<?php

namespace stats;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use SQLite3;
use stats\controllers\ActiveMentorManager;
use stats\controllers\ActiveShapeManager;
use stats\controllers\BloodFactorsManager;
use stats\controllers\ConfigManager;
use stats\controllers\DataManager;
use stats\controllers\MentorsManager;
use stats\controllers\ShapeManager;
use stats\entities\commands\BfsCommand;
use stats\entities\commands\MentorsCommand;
use stats\entities\commands\ShapesCommand;
use stats\entities\commands\StatsCommand;
use stats\entities\task\UpdaterTask;

class Loader extends PluginBase
{

	const ECONOMY_PLUGIN = "ApiTP";

	/** @var Loader */
	private static $instance;

	/**
	 * @return Loader
	 */
	public static function getInstance(): Loader
	{
		return self::$instance;
	}

	/** @var Config */
	private $config;
	/** @var Config */
	private $configShape;
	/** @var Config */
	private $configMentors;
	/** @var Config */
	private $configBfs;

	/** @var SQLite3 */
	private $db;

	/** @var ConfigManager */
	private $configManager;

	/** @var DataManager */
	private $dataManager;

	/** @var ActiveShapeManager */
	private $activeShapeManager;

	/** @var ActiveMentorManager */
	private $activeMentorManager;

	private $economy;

	public function onEnable()
	{
		self::$instance = $this;

		$this->economy = $this->getServer()->getPluginManager()->getPlugin(self::ECONOMY_PLUGIN);
		if (is_null($this->economy)) {
			$this->getLogger()->critical("O plugin não funciona sem o plugin '" . self::ECONOMY_PLUGIN . "'!");
			$this->getServer()->getPluginManager()->disablePlugin($this);
			return;
		}

		$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		$this->configShape = new Config($this->getDataFolder() . "formas.yml", Config::YAML);
		$this->configMentors = new Config($this->getDataFolder()."mentores.yml", Config::YAML);
		$this->configBfs = new Config($this->getDataFolder() . "bfs.yml", Config::YAML);

		// $this->db = new SQLite3($this->getDataFolder() . "database.db");

		// $this->db->query("CREATE TABLE IF NOT EXISTS players(
			// name TEXT PRIMARY KEY,
			// countDmg INTEGER NOT NULL,
			// countHealth INTEGER NOT NULL,
			// countDown INTEGER NOT NULL,
			// countDef INTEGER NOT NULL,
			// countSp INTEGER NOT NULL,
			// countDownMentor INTEGER NOT NULL,
			// bloodFactor TEXT NOT NULL
		// )");

		$this->db->query("CREATE TABLE IF NOT EXISTS active(
			name TEXT PRIMARY KEY,
			shape TEXT NOT NULL,
			lastUsage INTEGER NOT NULL,
			usageTime INTEGER NOT NULL 
		)");

		$this->db->query("CREATE TABLE IF NOT EXISTS activeMentor(
			name TEXT PRIMARY KEY,
			mentor TEXT NOT NULL,
			lastUsage INTEGER NOT NULL,
			usageTime INTEGER NOT NULL 
		)");


		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);

		// $this->getScheduler()->scheduleRepeatingTask(new UpdaterTask(), 30);

		$this->getServer()->getCommandMap()->registerAll("StatsAPI", [
			new StatsCommand("stats"),
			new ShapesCommand("formas"),
			new BfsCommand("bfs"),
			new MentorsCommand("mentores")
		]);

		ShapeManager::init($this->configShape);
		MentorsManager::init($this->configMentors);
		BloodFactorsManager::init($this->configBfs);

		$this->configManager = new ConfigManager($this->config);
		$this->dataManager = new DataManager($this->db);
		$this->activeShapeManager = new ActiveShapeManager();
		$this->activeShapeManager->loadActiveShape($this->db);
		$this->activeMentorManager = new ActiveMentorManager();
		$this->activeMentorManager->loadActiveShape($this->db);
	}

	/**
	 * @return Config
	 */
	public function getConfig(): Config
	{
		return $this->config;
	}

	/**
	 * @return Config
	 */
	public function getConfigShape(): Config
	{
		return $this->configShape;
	}

	/**
	 * @return Config
	 */
	public function getConfigBfs(): Config
	{
		return $this->configBfs;
	}

	/**
	 * @return Config
	 */
	public function getConfigMentors(): Config
	{
		return $this->configMentors;
	}

	/**
	 * @return ConfigManager
	 */
	public function getConfigManager(): ConfigManager
	{
		return $this->configManager;
	}

	/**
	 * @return DataManager
	 */
	public function getDataManager(): DataManager
	{
		return $this->dataManager;
	}

	/**
	 * @return ActiveShapeManager
	 */
	public function getActiveShapeManager(): ActiveShapeManager
	{
		return $this->activeShapeManager;
	}

	/**
	 * @return ActiveMentorManager
	 */
	public function getActiveMentorManager(): ActiveMentorManager
	{
		return $this->activeMentorManager;
	}

	public function getEconomy()
	{
		return $this->economy;
	}

}