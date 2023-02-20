<?php

namespace LutekRest\LutekDev\LutekLibs\Menus\EasyInventoryAPI\task;

use LutekRest\LutekDev\LutekLibs\Menus\EasyInventoryAPI\block\inventory\ChestInventory;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
class DelayTask extends Task {
	public Player $player;
	public ChestInventory $inventory;

	public function __construct(Player $player, ChestInventory $inventory) {
		$this->player = $player;
		$this->inventory = $inventory;
	}

	public function onRun() : void {
		$this->player->setCurrentWindow($this->inventory);
	}
}
