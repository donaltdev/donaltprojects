<?php

namespace airdrops\scheduler\items\antimushroom;

use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use airdrops\sessions\Session;
use airdrops\sessions\SessionFactory;

class AntiMushroomPScheduler extends Task {

    private Player $player;

    public function __construct(Player $player) {
        $this->player = $player;
        $this->getSession()->setMushroom("cooldown", 60);
    }

    public function getPlayer(): Player {
        return $this->player;
    }

    public function getSession(): Session {
        return SessionFactory::getInstance()->get($this->getPlayer()->getName());
    }

    public function onRun(): void {
        $session = $this->getSession();
        if ($this->getPlayer() === null or $this->getPlayer()->isClosed() or !$this->getPlayer()->isOnline()) {
            $this->getHandler()->cancel();
            return;
        }
        if ($session->getMushroom("cooldown") === 0) {
            $session->setMushroom("used", false);
            $this->getPlayer()->sendMessage(TextFormat::GREEN . "The AntiMushroom ability cooldown has expired.");
            $this->getHandler()->cancel();
        } else if ($session->getMushroom("cooldown") === 45) {
            $session->setMushroom("tag", false);
            $this->getPlayer()->sendMessage(TextFormat::GREEN . "The AntiMushroom ability has expired.");
            $session->setMushroom("cooldown", $session->getMushroom("cooldown") - 1);
        } else {
            $session->setMushroom("cooldown", $session->getMushroom("cooldown") - 1);
        }
    }
}