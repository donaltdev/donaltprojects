<?php

namespace Stats\Provider;

use pocketmine\Player;
use pocketmine\utils\Config;
use Stats\GoogStats;
use Stats\Session\PlayerSession;

class YamlProvider implements IProvider
{

    private $path;
    private $worlds;

    public function __construct(string $path)
    {
        $this->path = $path;

        @mkdir($path . "players/");

        $config = new Config($path . "worlds.json", Config::JSON);
        $this->worlds = $config->get("worlds", []);
    }

    public function existData(Player $player): bool
    {
        return file_exists($this->path . "players/" . $player->getName() . ".yml");
    }

    public function getData(Player $player): ?array
    {
        return (new Config($this->path . "players/" . $player->getName() . ".yml"))->getAll();
    }

    public function saveData(PlayerSession $playerSession): void
    {
        $config = new Config($this->path . "players/" . $playerSession->getUsername() . ".yml");
        $config->set("hp", $playerSession->getHp()->getDefault());
        $config->set("force", $playerSession->getForce()->getDefault());
        $config->set("shield", $playerSession->getShield()->getDefault());
        $config->set("aditional", [$playerSession->getAditional()->getTemporal(), $playerSession->getAditional()->getDefault()]);
        $config->set("lastEvents", $playerSession->getLastEvents());
        $config->save();
    }

    /**
     * @return array
     */
    public function getWorlds(): array
    {
        return $this->worlds;
    }

}