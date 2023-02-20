<?php

namespace airdrops\items;

use pocketmine\item\Item;

abstract class CustomItem {

    abstract public function getName(): string;

    abstract public function getId(): int;

    abstract public function getItemFormat(): string;

    abstract public function getItem(): Item;

    abstract public function getCooldown(): int;

}