<?php

namespace stats\controllers;

use SQLite3;
use stats\entities\shape\ActiveShape;

class ActiveShapeManager
{

	private $activeShapes = [];

	public function loadActiveShape(SQLite3 $database) {
		$result = $database->query("SELECT * FROM active");
		while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$activeShape = new ActiveShape($row["name"], $row["shape"], (int)$row["lastUsage"], (int)$row["usageTime"]);
			$this->setActiveShape($activeShape);
		}
	}

	public function getActiveShape(string $playerName): ?ActiveShape
	{
		if (isset($this->activeShapes[$playerName])) {
			return $this->activeShapes[$playerName];
		}
		return null;
	}

	public function removeActiveShape(string $playerName) {
		if (isset($this->activeShapes[$playerName])) {
			unset($this->activeShapes[$playerName]);
		}
	}

	public function setActiveShape(ActiveShape $activeShape) {
		if (!isset($this->activeShapes[$activeShape->getPlayerName()])) {
			$this->activeShapes[$activeShape->getPlayerName()] = $activeShape;
		}
	}

	public function tick() {
		foreach ($this->activeShapes as $name => $activeShape) {
			$activeShape->tick();
		}
	}

}