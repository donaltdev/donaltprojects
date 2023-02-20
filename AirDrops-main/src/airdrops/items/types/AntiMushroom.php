<?php

namespace airdrops\items\types;

use airdrops\items\CustomItem;
use airdrops\items\ids\CustomItemIds;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\utils\TextFormat;

class AntiMushroom extends CustomItem {

    public function getName(): string {
        return "AntiMushroom";
    }

    public function getId(): int {
        return CustomItemIds::ANTI_MUSHROOM;
    }

    public function getItemFormat(): string {
        return TextFormat::colorize("&cAnti Mushroom");
    }

    public function getItem(): Item {
        return VanillaItems::FEATHER()->setCustomName($this->getItemFormat());
    }

    public function getCooldown(): int {
        return 15;
    }
}