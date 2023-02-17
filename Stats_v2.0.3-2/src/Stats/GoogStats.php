<?php

namespace Stats;

use pocketmine\plugin\PluginBase;
use Stats\Command\ProtectCommand;
use Stats\Command\StatsCommand;
use Stats\Events\FormListener;
use Stats\Events\PlayerListener;
use Stats\Session\SessionManager;
use Stats\Task\PlayerTask;

class GoogStats extends PluginBase
{

    /** @var SessionManager|null */
    private static $sessionManager;

    /** @var GoogStats */
    private static $instance;

    public function onEnable(): void
    {
        self::$sessionManager = new SessionManager($this);
        self::$instance = $this;

        $this->getScheduler()->scheduleRepeatingTask(new PlayerTask(), 20);
        $this->getServer()->getCommandMap()->register("stats", new StatsCommand());
        $this->getServer()->getCommandMap()->register("wpro", new ProtectCommand());
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new FormListener(), $this);
    }

    /**
     * @return SessionManager
     */
    public static function getSessionManager(): SessionManager
    {
        return self::$sessionManager;
    }

    /**
     * @return GoogStats
     */
    public static function getInstance(): GoogStats
    {
        return self::$instance;
    }

    public function onDisable(): void
    {
        foreach ($this->getServer()->getOnlinePlayers() as $player) {
            self::$sessionManager->delSession($player);
        }
    }

}