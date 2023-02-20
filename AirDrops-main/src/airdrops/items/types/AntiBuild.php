<?php

namespace airdrops\items\types;

use airdrops\items\CustomItem;
use airdrops\items\ids\CustomItemIds;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\utils\TextFormat;

class AntiBuild extends CustomItem {

    public function getName(): string {
        return "AntiBuild";
    }

    public function getId(): int {
        return CustomItemIds::ANTI_BUILD;
    }

    public function getItemFormat(): string {
        return TextFormat::colorize("&eAnti-Build");
    }

    public function getItem(): Item {
        return VanillaItems::FISHING_ROD()->setCustomName($this->getItemFormat());
    }

    public function getCooldown(): int {
        return 30;
    }
}