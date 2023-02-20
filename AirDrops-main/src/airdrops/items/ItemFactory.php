<?php

namespace airdrops\items;

use airdrops\items\types\Airdrop;
use airdrops\items\types\AntiBuild;
use airdrops\items\types\AntiMushroom;
use airdrops\items\types\Zap;
use pocketmine\utils\SingletonTrait;

class ItemFactory {
    use SingletonTrait;

    private array $items = [];

    public function start(): void {
        foreach ([new AntiBuild(), new AntiMushroom(), new Zap(), new Airdrop()] as $items) {
            $this->add($items);
        }
    }

    public function add(CustomItem $item): void {
        $this->items[$item->getId()] = $item;
    }

    public function get(int $id): ?CustomItem {
        return $this->items[$id] ?? null;
    }

    public function getRandItem(): CustomItem {
        return $this->items[array_rand($this->items)];
    }

    /**
     * @return CustomItem[]
     */
    public function getItems(): array {
        return $this->items;
    }
}