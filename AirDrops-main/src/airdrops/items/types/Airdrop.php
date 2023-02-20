<?php

namespace airdrops\items\types;

use airdrops\items\CustomItem;
use airdrops\items\ids\CustomItemIds;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockLegacyIds;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\types\Enchant;
use pocketmine\utils\TextFormat;

class Airdrop extends CustomItem {

    public function getName(): string {
        return "AirDrop";
    }

    public function getId(): int {
        return CustomItemIds::AIRDROP;
    }

    public function getItemFormat(): string {
        return TextFormat::colorize("&6Airdrop");
    }

    public function getItem(): Item {
        $item = BlockFactory::getInstance()->get(238, 4)->asItem();
        $item->setCustomName($this->getItemFormat());
        $item->getNamedTag()->setString("Airdrop", "true");
        return $item;
    }

    public function getCooldown(): int {
        return 0;
    }
}