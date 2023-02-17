<?php

namespace Stats\Provider;

use pocketmine\Player;
use Stats\Session\PlayerSession;

interface IProvider
{

    public function existData(Player $player): bool;

    public function getData(Player $player): ?array;

    public function saveData(PlayerSession $playerSession): void;

}