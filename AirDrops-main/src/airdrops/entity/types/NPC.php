<?php

namespace airdrops\entity\types;

use airdrops\AirDrops;
use JsonException;
use pocketmine\entity\Human;
use pocketmine\entity\Location;
use pocketmine\entity\Skin;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class NPC extends Human {

    private string $id_name;

    public function __construct(Location $location, Skin $skin, ?CompoundTag $nbt = null) {
        parent::__construct($location, $skin, $nbt);
    }

    public function initEntity(CompoundTag $nbt): void {
        parent::initEntity($nbt);
        $this->setIdName($nbt->getString("npc_type", "Unknown"));
    }

    /**
     * @throws JsonException
     */
    public function onUpdate(int $currentTick): bool {
        /*$dir = AirDrops::getInstance()->getDataFolder() . "models/" . "falling/" . "byshuy12.paracaidas" . ".png";
        $img = @imagecreatefrompng($dir);
        $size = getimagesize($dir);
        $skinbytes = "";
        for ($y = 0; $y < $size[1]; $y++) {
            for ($x = 0; $x < $size[0]; $x++) {
                $colorat = @imagecolorat($img, $x, $y);
                $a = ((~((int)($colorat >> 24))) << 1) & 0xff;
                $r = ($colorat >> 16) & 0xff;
                $g = ($colorat >> 8) & 0xff;
                $b = $colorat & 0xff;
                $skinbytes .= chr($r) . chr($g) . chr($b) . chr($a);
            }
        }
        @imagedestroy($img);
        $this->setSkin(new Skin($this->getSkin()->getSkinId(), $skinbytes, "", "geometry.byshut12.paracaidas", file_get_contents(AirDrops::getInstance()->getDataFolder() . "models/" . "falling/" . "airdrop.paracaidas.shut12.geo" . ".json")));*/
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