<?php

namespace stats\entities\task;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use stats\entities\StatsPlayer;
use stats\Loader;

// class UpdaterTask extends Task
// {

	public function onRun(int $currentTick)
	{
		Loader::getInstance()->getActiveShapeManager()->tick();
		Loader::getInstance()->getActiveMentorManager()->tick();

		// foreach (Server::getInstance()->getOnlinePlayers() as $player){
			// /** @var StatsPlayer $player */
			// $player->getStats()->updateStats();
		// }
	// }

// }