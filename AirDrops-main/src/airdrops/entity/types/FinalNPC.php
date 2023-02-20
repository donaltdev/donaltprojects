<?php

namespace airdrops\entity\types;

use pocketmine\entity\Human;
use pocketmine\entity\Location;
use pocketmine\entity\Skin;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class FinalNPC extends Human {

    private string $id_name;

    public function __construct(Location $location, Skin $skin, ?CompoundTag $nbt = null) {
        parent::__construct($location, $skin, $nbt);
    }

    public function initEntity(CompoundTag $nbt): void {
        parent::initEntity($nbt);
        $this->setIdName($nbt->getString("npc_type", "Unknown"));
    }

    public function onUpdate(int $currentTick): bool {
        $this->setNameTagAlwaysVisible();
        $this->setNameTagVisible();
        $this->setImmobile();
        if ($this->isOnFire()) {
            $this->extinguish();
        }
        return parent::onUpdate($currentTick);
    }

    public function saveNBT(): CompoundTag {
        $nbt = parent::saveNBT();
        $nbt->setString("npc_type", $this->getIdName());
        return $nbt;
    }

    public function getIdName(): string {
        return $this->id_name;
    }

    public function setIdName(string $id_name): void {
        $this->id_name = $id_name;
    }

    public function equals(string $name): bool {
        return $this->getIdName() === $name;
    }
}