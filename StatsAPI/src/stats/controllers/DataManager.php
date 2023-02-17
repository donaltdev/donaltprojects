<?php


namespace stats\controllers;

use pocketmine\Player;
use SQLite3;
use stats\entities\mentors\ActiveMentor;
use stats\entities\shape\ActiveShape;
use stats\entities\Stats;

class DataManager
{

	/** @var SQLite3 */
	private $db;

	/**
	 * DataManager constructor.
	 * @param SQLite3 $db
	 */
	public function __construct(SQLite3 $db)
	{
		$this->db = $db;
	}

	// public function existsAccount(Player $player): bool
	// {
		// $name = $player->getLowerCaseName();
		// $result = $this->db->query("SELECT * FROM players WHERE name ='$name'");
		// $data = $result->fetchArray(SQLITE3_ASSOC);
		// return (isset($data["name"]) && $data["name"] == $name);
	// }

	// public function createAccount(Player $player): void
	// {
		// if(!$this->existsAccount($player)){
			// $this->db->query("INSERT INTO players(name, countDmg, countHealth, countDown, countDef, countSp, countDownMentor, bloodFactor) VALUES('"
				// .$player->getLowerCaseName()
				// ."', 0, 0, 0, 0, 0, 0, 'null')");
		// }
	// }

	// public function dataToStats(Player $player): Stats
	// {
		// if($this->existsAccount($player)){
			// $name = $player->getLowerCaseName();
			// $result = $this->db->query("SELECT * FROM players WHERE name ='$name'");
			// $data = $result->fetchArray(SQLITE3_ASSOC);
			// return new Stats($player->getName(), (int)$data["countHealth"], (int)$data["countDmg"],
				// (int)$data["countDown"], (int)$data["countDef"], (int)$data["countSp"], (int)
				// $data["countDownMentor"], ($data["bloodFactor"] == "null") ? null : $data["bloodFactor"]);
		// }
		// $this->createAccount($player);
		// return new Stats($player->getName(), 0, 0, 0, 0, 0, 0);
	// }

	public function saveData(Stats $stats): void
	{
		if(!is_null($stats->getPlayer())){
			$player = $stats->getPlayer();
			$this->db->query("INSERT OR REPLACE INTO players(name, countDmg, countHealth, countDown, countDef, countSp, countDownMentor, bloodFactor) VALUES ('"
				.$player->getLowerCaseName() ."', ".$stats->getCountUpgradeDmg().", ".$stats->getCountUpgradeMh().", '"
				.$stats->getCountDown()."', " . $stats->getCountUpgradeDef(). ", " . $stats->getCountUpgradeSp().
				", " . $stats->getCountDownMentor() . ", '" . ($stats->getBloodFactorName() ?? "null") . "')");
		}
	}

	public function saveActive(ActiveShape $activeShape)
	{
		$player = $activeShape->getPlayer();
		$shape = $activeShape->getShapeName();
		$this->db->query("INSERT OR REPLACE INTO active(name, shape, lastUsage, usageTime) VALUES('"
				. $player->getLowerCaseName() ."', '" . $shape . "', '" . $activeShape->getLastActive() . "', '" .
				$activeShape->getUsageTime(). "')");
	}

	public function existsActive(string $playerName)
	{
		$result = $this->db->query("SELECT * FROM active WHERE name ='$playerName'");
		$data = $result->fetchArray(SQLITE3_ASSOC);
		return (isset($data["name"]) && $data["name"] == $playerName);
	}

	public function removeActive(string $playerName)
	{
		if ($this->existsActive($playerName)) {
			$this->db->query("DELETE FROM active WHERE name ='$playerName'");
		}
	}

	public function saveActiveMentor(ActiveMentor $activeMentor)
	{
		$player = $activeMentor->getPlayer();
		$mentor = $activeMentor->getMentorName();
		$this->db->query("INSERT OR REPLACE INTO activeMentor(name, mentor, lastUsage, usageTime) VALUES('"
			. $player->getLowerCaseName() ."', '" . $mentor . "', '" . $activeMentor->getLastActive() . "', '" .
			$activeMentor->getUsageTime(). "')");
	}

	public function existsActiveMentor(string $playerName)
	{
		$result = $this->db->query("SELECT * FROM activeMentor WHERE name ='$playerName'");
		$data = $result->fetchArray(SQLITE3_ASSOC);
		return (isset($data["name"]) && $data["name"] == $playerName);
	}

	public function removeActiveMentor(string $playerName)
	{
		if ($this->existsActiveMentor($playerName)) {
			$this->db->query("DELETE FROM activeMentor WHERE name ='$playerName'");
		}
	}

}