<?php

namespace stats\controllers;

use SQLite3;
use stats\entities\mentors\ActiveMentor;

class ActiveMentorManager
{

	private $activeMentors = [];

	public function loadActiveShape(SQLite3 $database) {
		$result = $database->query("SELECT * FROM activeMentor");
		while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$activeMentor = new ActiveMentor($row["name"], $row["mentor"], (int)$row["lastUsage"], (int)$row["usageTime"]);
			$this->setActiveMentor($activeMentor);
		}
	}

	public function getActiveMentor(string $playerName): ?ActiveMentor
	{
		if (isset($this->activeMentors[$playerName])) {
			return $this->activeMentors[$playerName];
		}
		return null;
	}

	public function removeActiveMentor(string $playerName) {
		if (isset($this->activeMentors[$playerName])) {
			unset($this->activeMentors[$playerName]);
		}
	}

	public function setActiveMentor(ActiveMentor $activeMentor) {
		if (!isset($this->activeMentors[$activeMentor->getPlayerName()])) {
			$this->activeMentors[$activeMentor->getPlayerName()] = $activeMentor;
		}
	}

	public function tick() {
		foreach ($this->activeMentors as $name => $activeMentor) {
			$activeMentor->tick();
		}
	}

}