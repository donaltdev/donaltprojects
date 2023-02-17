<?php


namespace stats\entities\shape;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\entity\Skin;
use pocketmine\Player;
use pocketmine\Server;
use stats\controllers\ShapeManager;
use stats\entities\StatsPlayer;
use stats\Loader;

class ActiveShape
{

	private $playerName;
	/** @var Skin */
	private $skinPlayer = null;
	private $shapeName;
	private $lastActive = 0;
	private $usageTime = 0;

	/**
	 * ActiveShape constructor.
	 * @param $playerName
	 * @param $shapeName
	 * @param int $lastActive
	 * @param int $usageTime
	 */
	public function __construct($playerName, $shapeName, int $lastActive, int $usageTime = 0)
	{
		$this->playerName = $playerName;
		$this->shapeName = $shapeName;
		$this->lastActive = $lastActive;
		$this->usageTime = $usageTime;

		if (!is_null($this->getPlayer())) {
			$this->setSkinPlayer($this->getPlayer()->getSkin());
			$this->getPlayer()->setSkin($this->getShape()->getSkin());
			$this->getPlayer()->sendSkin();
			$command = $this->getShape()->getCommand();
			$command = str_replace("{player}", $this->getPlayerName(), $command);
			Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), $command);
		}
	}

	public function disableShape()
	{
		if (!is_null($this->skinPlayer)) {
			$this->getPlayer()->setSkin($this->getSkinPlayer());
			$this->getPlayer()->sendSkin();
		}
		$this->getPlayer()->getStats()->setCountDown((time() + $this->getShape()->getTimeCountdown()));
		Loader::getInstance()->getActiveShapeManager()->removeActiveShape($this->getPlayerName());
		Loader::getInstance()->getDataManager()->removeActive($this->getPlayerName());
	}

	public function tick()
	{
		if (!is_null($this->getPlayer()) && !is_null($this->getShape())) {
			if ($this->getCountUsage() >= $this->getShape()->getTimeActive()) {
				$this->getPlayer()->sendMessage("§cSua forma acabou! Tempo de countdown: " .
					gmdate("H:i:s", $this->getShape()->getTimeCountdown()) . ".");
				$this->disableShape();
			}
		}
	}

	public function playerJoin()
	{
		$this->lastActive = time();
		$this->setSkinPlayer($this->getPlayer()->getSkin());
		$this->getPlayer()->setSkin($this->getShape()->getSkin());
		$this->getPlayer()->sendSkin();
		$command = $this->getShape()->getCommand();
		$command = str_replace("{player}", $this->getPlayerName(), $command);
		Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), $command);
		Loader::getInstance()->getDataManager()->saveActive($this);
	}

	public function playerQuit()
	{
		$this->usageTime = $this->getCountUsage();
		$this->lastActive = time();
		$this->skinPlayer = null;
		Loader::getInstance()->getDataManager()->saveActive($this);
	}

	public function getCountUsage()
	{
		return (time() - $this->lastActive) + $this->usageTime;
	}

	/**
	 * @return int
	 */
	public function getUsageTime(): int
	{
		return $this->usageTime;
	}

	/**
	 * @return int
	 */
	public function getLastActive(): int
	{
		return $this->lastActive;
	}

	/**
	 * @return StatsPlayer|null
	 */
	public function getPlayer(): ?Player
	{
		return Server::getInstance()->getPlayer($this->getPlayerName());
	}

	/**
	 * @return Shape|null
	 */
	public function getShape(): ?Shape
	{
		return ShapeManager::getShapeByName($this->getShapeName());
	}

	/**
	 * @param Skin $skinPlayer
	 */
	public function setSkinPlayer(Skin $skinPlayer): void
	{
		$this->skinPlayer = $skinPlayer;
	}

	/**
	 * @return mixed
	 */
	public function getPlayerName()
	{
		return $this->playerName;
	}

	/**
	 * @return string
	 */
	public function getShapeName()
	{
		return $this->shapeName;
	}

	/**
	 * @return Skin|null
	 */
	public function getSkinPlayer(): ?Skin
	{
		return $this->skinPlayer;
	}

}