<?php

namespace airdrops\forms;

use airdrops\AirDrops;
use airdrops\entity\types\FinalNPC;
use airdrops\items\ItemFactory;
use airdrops\scheduler\GiveRewards;
use muqsit\invmenu\InvMenu;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class LootMenu {

    public static function randomizer(Player $player, InvMenu $invMenu): void {
        $invMenu->getInventory()->setContents([
            0 => ItemFactory::getInstance()->getRandItem()->getItem(),
            1 => ItemFactory::getInstance()->getRandItem()->getItem(),
            2 => ItemFactory::getInstance()->getRandItem()->getItem(),
            3 => ItemFactory::getInstance()->getRandItem()->getItem(),
            4 => ItemFactory::getInstance()->getRandItem()->getItem()
        ]);
    }

    public static function finalRandomizer(Player $player, InvMenu $invMenu, FinalNPC $finalNPC): void {
        $finalNPC->setNameTag(TextFormat::colorize("&l&6AirDrop &r&a(Opened)"));
        $a = ItemFactory::getInstance()->getRandItem()->getItem();
        $b = ItemFactory::getInstance()->getRandItem()->getItem();
        $c = ItemFactory::getInstance()->getRandItem()->getItem();
        $d = ItemFactory::getInstance()->getRandItem()->getItem();
        $e = ItemFactory::getInstance()->getRandItem()->getItem();
        $invMenu->getInventory()->setContents([
            0 => $a,
            1 => $b,
            2 => $c,
            3 => $d,
            4 => $e
        ]);
        $invMenu->onClose($player);
        AirDrops::getInstance()->getScheduler()->scheduleRepeatingTask(new GiveRewards($player, [$a, $b, $c, $d, $e], $finalNPC), 20);
    }
}