<?php

namespace LutekRest\LutekDev\LutekLibs\Menus\EasyInventoryAPI;

use LutekRest\LutekDev\LutekLibs\Menus\EasyInventoryAPI\block\inventory\ChestInventory;
use LutekRest\LutekDev\LutekLibs\Menus\EasyInventoryAPI\block\inventory\DoubleChestInventory;
use LutekRest\LutekDev\LutekRest;
use LutekRest\LutekDev\LutekUtils\Managers\ListenerManager;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\inventory\transaction\action\SlotChangeAction;

class EasyChestManager extends ListenerManager {
	public function createChestGUI() : ChestInventory {
		$plugin = LutekRest::getInstance();
		return new ChestInventory($plugin);
	}

	public function createDoubleChestGUI() : DoubleChestInventory {
		$plugin = LutekRest::getInstance();
		return new DoubleChestInventory($plugin);
	}

	public function onInventoryTransaction(InventoryTransactionEvent $event) : void {
		$transaction = $event->getTransaction();
		$player = $transaction->getSource();
		foreach ($transaction->getActions() as $action) {
			if ($action instanceof SlotChangeAction) {
				$inventory = $action->getInventory();
				if ($inventory instanceof ChestInventory) {
					if ($inventory->isViewOnly()) {
						$event->cancel();
					}
					$clickCallback = $inventory->getClickCallback();
					if ($clickCallback !== null) {
						$clickCallback($player, $inventory, $action->getSourceItem(), $action->getTargetItem(), $action->getSlot());
					}
				}
			}
		}
	}
}