<?php

namespace airdrops\utils;

use airdrops\AirDrops;
use pocketmine\utils\SingletonTrait;

class SaveData {
    use SingletonTrait;

    public const MODELS = "models";
    public const FALLING = self::MODELS . "/" . "falling";
    public const NORMAL = self::MODELS . "/" . "normal";

    public function start(): void {
        if (!is_dir(AirDrops::getInstance()->getDataFolder() . self::MODELS)) @mkdir(AirDrops::getInstance()->getDataFolder() . self::MODELS);
        if (!is_dir(AirDrops::getInstance()->getDataFolder() . self::FALLING)) @mkdir(AirDrops::getInstance()->getDataFolder() . self::FALLING);
        if (!is_dir(AirDrops::getInstance()->getDataFolder() . self::NORMAL)) @mkdir(AirDrops::getInstance()->getDataFolder() . self::NORMAL);
        foreach (
            [
            self::FALLING . "/" . "airdrop.paracaidas.shut12.geo.json",
            self::FALLING . "/" . "byshuy12.paracaidas.png",
            self::NORMAL . "/" . "airdrop.cofre.shut12.geo.json",
            self::NORMAL . "/" . "byshut12.animation.json",
            self::NORMAL . "/" . "byshuy12.paracaidas.png"
            ] as $item) {
            AirDrops::getInstance()->saveResource($item);
        }
    }
}