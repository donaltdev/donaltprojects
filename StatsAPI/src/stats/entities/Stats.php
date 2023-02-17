<?php

namespace stats\entities;

use pocketmine\Player;
use pocketmine\Server;
use stats\controllers\BloodFactorsManager;
use stats\entities\bfs\BloodFactor;
use stats\entities\mentors\Mentor;
use stats\entities\shape\Shape;
use stats\entities\utils\Utils;
use stats\Loader;

class Stats {
	private $health;
	private $maxHealth;
	private $damage;
	private $defense;
	private $superPower;

	// private $playerName;

	private $countDown;
	private $countDownMentor;

	private $countUpgradeMh;
	private $countUpgradeDmg;
	private $countUpgradeDef;
	private $countUpgradeSp;

	private $bloodFactor;

	public function __construct(string $playerName, int $countUpgradeMh, int $countUpgradeDmg, int $countDown,
		int $countUpgradeDef, int $countUpgradeSp, int $countDownMentor, string $bloodFactor = null)
	{
		// $this->playerName = $playerName;

		$this->countUpgradeMh = $countUpgradeMh;
		$this->countUpgradeDmg = $countUpgradeDmg;
		$this->countUpgradeDef = $countUpgradeDef;
		$this->countUpgradeSp = $countUpgradeSp;

		$this->bloodFactor = $bloodFactor;

		$this->countDown = $countDown;
		$this->countDownMentor = $countDownMentor;

		$this->maxHealth = (int)(Loader::getInstance()->getConfigManager()->getValue("initial_health") ?? 999);
		$this->damage = (int)(Loader::getInstance()->getConfigManager()->getValue("initial_damage") ?? 999);
		$this->defense = (int)(Loader::getInstance()->getConfigManager()->getValue("initial_defense") ?? 999);
		$this->superPower = (int)(Loader::getInstance()->getConfigManager()->getValue("initial_superpower") ?? 999);
		$this->health = $this->maxHealth;
	}

	/**
	 * @return string
	 */
	public function getBloodFactorName(): ?string
	{
		return $this->bloodFactor;
	}

	/**
	 * @return BloodFactor|null
	 */
	public function getBloodFactor(): ?BloodFactor
	{
		return BloodFactorsManager::getBloodByName($this->getBloodFactorName());
	}

	/**
	 * @param string $bloodFactor
	 * @throws \Exception
	 */
	public function setBloodFactor(?string $bloodFactor): void
	{
		if (!is_null($bloodFactor) && is_null(BloodFactorsManager::getBloodByName($bloodFactor))) {
			throw new \Exception("Não existe blood factor com esse nome '{$bloodFactor}'!");
		}
		$this->bloodFactor = $bloodFactor;
		Loader::getInstance()->getDataManager()->saveData($this);
	}

	/**
	 * @return string
	 */
	// public function getPlayerName(): string
	// {
		// return $this->playerName;
	// }

	/**
	 * @return Player|null
	 */
	// public function getPlayer(): ?Player
	// {
		// return Server::getInstance()->getPlayerExact($this->getPlayerName());
	// }

	/**
	 * @return int
	 */
	public function getHealth(): int
	{
		return ($this->health > 0) ? $this->health : 0;
	}

	/**
	 * @return int
	 */
	public function getCountDown(): int
	{
		return $this->countDown;
	}

	/**
	 * @param int $countDown
	 */
	public function setCountDown(int $countDown): void
	{
		$this->countDown = $countDown;
		Loader::getInstance()->getDataManager()->saveData($this);
	}

	/**
	 * @return int
	 */
	public function getCountDownMentor(): int
	{
		return $this->countDownMentor;
	}

	/**
	 * @param int $countDown
	 */
	public function setCountDownMentor(int $countDown): void
	{
		$this->countDownMentor = $countDown;
		Loader::getInstance()->getDataManager()->saveData($this);
	}

	/**
	 * @param bool $originalMaxHealth
	 * @return int
	 */
	public function getMaxHealth(bool $originalMaxHealth = false): int
	{
		$maxHealth = ($this->maxHealth + $this->countUpgradeMh);
		$configHealth = (int)(Loader::getInstance()->getConfigManager()->getValue("max_health") ?? 0);
		if ($maxHealth > $configHealth) {
			$maxHealth = $configHealth;
		}
		if ($originalMaxHealth) {
			return $maxHealth;
		}
		$percentage = null;
		if (!is_null($this->getShape())) {
			$percentage += $this->getShape()->getPercentageHealth();
		}
		if (!is_null($this->getBloodFactor())) {
			$percentage += $this->getBloodFactor()->getHealth();
		}
		if (!is_null($this->getMentor())) {
			$percentage += $this->getMentor()->getHealth();
		}
		return is_null($percentage) ? $maxHealth : ($maxHealth + (($maxHealth * $percentage) / 100));
	}

	/**
	 * @return Shape|null
	 */
	public function getShape(): ?Shape
	{
		if (!is_null($this->getPlayer())) {
			$activeShape = Loader::getInstance()->getActiveShapeManager()->getActiveShape($this->getPlayer()->getLowerCaseName());
			if (!is_null($activeShape)) {
				return $activeShape->getShape();
			}
		}
		return null;
	}

	/**
	 * @return Mentor|null
	 */
	public function getMentor(): ?Mentor
	{
		if (!is_null($this->getPlayer())) {
			$activeMentor = Loader::getInstance()->getActiveMentorManager()->getActiveMentor($this->getPlayer()
				->getLowerCaseName());
			if (!is_null($activeMentor)) {
				return $activeMentor->getMentor();
			}
		}
		return null;
	}

	/**
	 * @param int $health
	 */
	public function setHealth(int $health): void
	{
		if ($health > $this->getMaxHealth()) {
			$this->health = $this->getMaxHealth();
		} else {
			$this->health = $health;
		}
	}

	/**
	 * @param int $maxHealth
	 */
	public function setMaxHealth(int $maxHealth): void
	{
		$this->maxHealth = $maxHealth;
	}

	/**
	 * @param bool $originalDamage
	 * @return int
	 */
	public function getDamage(bool $originalDamage = false): int
	{
		$damage = ($this->damage + $this->countUpgradeDmg);
		$configDamage = (int)(Loader::getInstance()->getConfigManager()->getValue("max_damage") ?? 0);
		if ($damage > $configDamage) {
			$damage = $configDamage;
		}
		if ($originalDamage) {
			return $damage;
		}
		$percentage = null;
		if (!is_null($this->getShape())) {
			$percentage += $this->getShape()->getPercentageDamage();
		}
		if (!is_null($this->getBloodFactor())) {
			$percentage += $this->getBloodFactor()->getDamage();
		}
		if (!is_null($this->getMentor())) {
			$percentage += $this->getMentor()->getDamage();
		}
		return is_null($percentage) ? $damage : ($damage + (($damage * $percentage) / 100));
	}

	/**
	 * @param bool $originalSuperPower
	 * @return int
	 */
	public function getSuperPower(bool $originalSuperPower = false): int
	{
		$superPower = ($this->superPower + $this->countUpgradeSp);
		$configSuperPower = (int)(Loader::getInstance()->getConfigManager()->getValue("max_superpower") ?? 0);
		if ($superPower > $configSuperPower) {
			$superPower = $configSuperPower;
		}
		if ($originalSuperPower) {
			return $superPower;
		}
		$percentage = null;
		if (!is_null($this->getShape())) {
			$percentage += $this->getShape()->getPercentageSuper();
		}
		if (!is_null($this->getBloodFactor())) {
			$percentage += $this->getBloodFactor()->getSuperPower();
		}
		if (!is_null($this->getMentor())) {
			$percentage += $this->getMentor()->getSuperPower();
		}
		return is_null($percentage) ? $superPower : ($superPower + (($superPower * $percentage) / 100));
	}

	/**
	 * @param bool $originalDefense
	 * @return int
	 */
	public function getDefense(bool $originalDefense = false): int
	{
		$defense = ($this->defense + $this->countUpgradeDef);
		$configDefense = (int)(Loader::getInstance()->getConfigManager()->getValue("max_defense") ?? 0);
		if ($defense > $configDefense) {
			$defense = $configDefense;
		}
		if ($originalDefense) {
			return $defense;
		}
		$percentage = null;
		if (!is_null($this->getShape())) {
			$percentage += $this->getShape()->getPercentageDefense();
		}
		if (!is_null($this->getBloodFactor())) {
			$percentage += $this->getBloodFactor()->getDefense();
		}
		if (!is_null($this->getMentor())) {
			$percentage += $this->getMentor()->getDefense();
		}
		return is_null($percentage) ? $defense : ($defense + (($defense * $percentage) / 100));
	}

	// public function sendHealthBar(): void
	// {
		// if (!is_null($this->getPlayer())) {
			// $firstLine = "§7HP: §c" . Utils::formatNumber($this->getHealth()) . "/" . Utils::formatNumber($this->getMaxHealth()) .
				// "§7Dano: §a" . Utils::formatNumber($this->getDamage());
			// $secondLine = "§7Defense: §b" . Utils::formatNumber($this->getDefense()) .
				// "§7SPW: §e" . Utils::formatNumber($this->getSuperPower());
			// $this->getPlayer()->sendTip("{$firstLine}\n§f{$secondLine}");
		// }
	// }

	public function updateStats(): void
	{
		$this->sendHealthBar();
	}

	/**
	 * @return int
	 */
	public function getCountUpgradeDmg(): int
	{
		return $this->countUpgradeDmg;
	}

	/**
	 * @return int
	 */
	public function getCountUpgradeMh(): int
	{
		return $this->countUpgradeMh;
	}

	/**
	 * @return int
	 */
	public function getCountUpgradeDef(): int
	{
		return $this->countUpgradeDef;
	}

	/**
	 * @return int
	 */
	public function getCountUpgradeSp(): int
	{
		return $this->countUpgradeSp;
	}

	public function addUpgrade(int $amount, string $type): void
	{
		switch ($type) {
			case "damage":
				$this->countUpgradeDmg += $amount;
				break;
			case "health":
				$this->countUpgradeMh += $amount;
				break;
			case "defense":
				$this->countUpgradeDef += $amount;
				break;
			case "superpower":
				$this->countUpgradeSp += $amount;
				break;
			default:
				return;
		}
		Loader::getInstance()->getDataManager()->saveData($this);
	}

}