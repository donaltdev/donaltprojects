<?php

namespace airdrops\items\types;

use airdrops\items\CustomItem;
use airdrops\items\ids\CustomItemIds;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\utils\TextFormat;

class Zap extends CustomItem {

    public function getName(): string {
        return "Zap";
    }

    public function getId(): int {
        return CustomItemIds::ZAP;
    }

    public function getItemFormat(): string {
        return TextFormat::colorize("&bZap");
    }

    public function getItem(): Item {
        return VanillaItems::RED_DYE()->setCustomName($this->getItemFormat());
    }

    public function getCooldown(): int {
        return 20;
    }
}