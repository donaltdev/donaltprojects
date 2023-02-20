<?php

namespace airdrops\forms;

use airdrops\AirDrops;
use airdrops\entity\types\FinalNPC;
use airdrops\scheduler\InvRandomizeScheduler;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat;

class ResultInventory {
    use SingletonTrait;

    public function send(Player $player, FinalNPC $finalNPC): void {
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_HOPPER);
        $menu->setName(TextFormat::colorize("&l&6AirDrop"));
        $menu->setListener(InvMenu::readonly());
        $menu->send($player);
        AirDrops::getInstance()->getScheduler()->scheduleRepeatingTask(new InvRandomizeScheduler($player, $menu, $finalNPC), 20);
    }
}