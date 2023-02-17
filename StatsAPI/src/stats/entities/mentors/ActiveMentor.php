<?php


namespace stats\entities\mentors;


use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\Server;
use stats\controllers\MentorsManager;
use stats\entities\StatsPlayer;
use stats\Loader;

class ActiveMentor
{

	private $playerName;

	private $mentorName;
	private $lastActive = 0;
	private $usageTime = 0;

	/**
	 * ActiveMentorView constructor.
	 * @param $playerName
	 * @param $mentorName
	 * @param int $lastActive
	 * @param int $usageTime
	 */
	public function __construct($playerName, $mentorName, int $lastActive, int $usageTime = 0)
	{
		$this->playerName = $playerName;
		$this->mentorName = $mentorName;
		$this->lastActive = $lastActive;
		$this->usageTime = $usageTime;

		if (!is_null($this->getPlayer())) {
			$command = $this->getMentor()->getCommand();
			$command = str_replace("{player}", $this->getPlayerName(), $command);
			Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), $command);
		}
	}

	public function disableMentor()
	{
		$this->getPlayer()->getStats()->setCountDownMentor((time() + $this->getMentor()->getCountdown()));
		Loader::getInstance()->getActiveMentorManager()->removeActiveMentor($this->getPlayerName());
		Loader::getInstance()->getDataManager()->removeActiveMentor($this->getPlayerName());
	}

	public function tick()
	{
		if (!is_null($this->getPlayer()) && !is_null($this->getMentor())) {
			if ($this->getCountUsage() >= $this->getMentor()->getActiveTime()) {
				$this->getPlayer()->sendMessage("§cSeu mentor acabou! Tempo de countdown: " .
					gmdate("H:i:s", $this->getMentor()->getCountdown()) . ".");
				$this->disableMentor();
			}
		}
	}

	public function playerJoin()
	{
		$this->lastActive = time();
		$command = $this->getMentor()->getCommand();
		$command = str_replace("{player}", $this->getPlayerName(), $command);
		Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), $command);
		Loader::getInstance()->getDataManager()->saveActiveMentor($this);
	}

	public function playerQuit()
	{
		$this->usageTime = $this->getCountUsage();
		$this->lastActive = time();
		Loader::getInstance()->getDataManager()->saveActiveMentor($this);
	}

	public function getCountUsage()
	{
		return (time() - $this->lastActive) + $this->usageTime;
	}

	/**
	 * @return StatsPlayer|null
	 */
	public function getPlayer(): ?Player
	{
		return Server::getInstance()->getPlayer($this->getPlayerName());
	}

	/**
	 * @return Mentor|null
	 */
	public function getMentor(): ?Mentor
	{
		return MentorsManager::getMentorByName($this->getMentorName());
	}

	/**
	 * @return mixed
	 */
	public function getPlayerName()
	{
		return $this->playerName;
	}

	/**
	 * @return mixed
	 */
	public function getMentorName()
	{
		return $this->mentorName;
	}

	/**
	 * @return int
	 */
	public function getLastActive(): int
	{
		return $this->lastActive;
	}

	/**
	 * @return int
	 */
	public function getUsageTime(): int
	{
		return $this->usageTime;
	}

}