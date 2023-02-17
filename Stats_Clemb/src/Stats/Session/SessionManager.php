<?php

namespace Stats\Session;

use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use Stats\GoogStats;
use Stats\Provider\YamlProvider;

class SessionManager
{

    private $sessions = [];
    private $worlds = [];
    private $provider;

    public function __construct(PluginBase $plugin)
    {
        $this->provider = new YamlProvider($plugin->getDataFolder());

        foreach ($this->provider->getWorlds() as $world) {
            Server::getInstance()->loadLevel($world);

            $this->addWorld(Server::getInstance()->getLevelByName($world), true);
        }
    }

    public function addSession(Player $player): void
    {
        $this->sessions[$player->getName()] = new PlayerSession($player->getName());

        if ($this->provider->existData($player)) $this->loadSession($player);
    }

    public function loadSession(Player $player): void
    {
        $session = $this->getSession($player);
        if (!$session) return;

        $data = $this->provider->getData($player);

        $session->getHp()->setDefault($data["hp"]);
        $session->getHp()->setTemporal($data["hp"]);

        $session->getForce()->setDefault($data["force"]);
        $session->getForce()->setTemporal($data["force"]);

        $session->getShield()->setDefault($data["shield"]);
        $session->getShield()->setTemporal($data["shield"]);

        $session->getAditional()->setTemporal($data["aditional"][0]);
        $session->getAditional()->setDefault($data["aditional"][1]);

        $session->setLastEvents($data["lastEvents"]);
    }

    public function getSession(Player $player): ?PlayerSession
    {
        return $this->sessions[$player->getName()] ?? null;
    }

    public function delSession(Player $player): void
    {
        $session = $this->getSession($player);
        if (!$session) return;

        $this->provider->saveData($session);
        unset($this->sessions[$player->getName()]);
    }

    public function addWorld(Level $level, bool $protected = false): void
    {
        $this->worlds[$level->getFolderName()] = new WorldSession($protected);
    }

    public function getWorld(Level $level): ?WorldSession
    {
        if (!isset($this->worlds[$level->getFolderName()])) {
            $this->addWorld($level);
        }

        return $this->worlds[$level->getFolderName()];
    }

    /**
     * @return WorldSession[]
     */
    public function getWorlds(): array
    {
        return $this->worlds;
    }

    public function __destruct()
    {
        $config = new Config(GoogStats::getInstance()->getDataFolder() . "worlds.json", Config::JSON);

        $worlds = [];
        foreach ($this->getWorlds() as $key => $world) {
            if ($world->isProtected()) $worlds[] = $key;
        }

        $config->set("worlds", $worlds);
        $config->save();
    }

}