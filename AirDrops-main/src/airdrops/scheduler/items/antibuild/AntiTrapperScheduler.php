<?php

namespace airdrops\scheduler\items\antibuild;

use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use airdrops\sessions\Session;
use airdrops\sessions\SessionFactory;

class AntiTrapperScheduler extends Task {

    private Player $player;

    public function __construct(Player $player) {
        $this->player = $player;
        $this->getSession()->setAntibuild("cooldown", 60);
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
        if ($session->getAntibuild("cooldown") === 0) {
            $session->setAntibuild("used", false);
            $this->getPlayer()->sendMessage(TextFormat::GREEN . "The AntiBuild ability cooldown has expired.");
            $this->getHandler()->cancel();
        } else {
            $session->setAntibuild("cooldown", $session->getAntibuild("cooldown") - 1);
        }
    }
}