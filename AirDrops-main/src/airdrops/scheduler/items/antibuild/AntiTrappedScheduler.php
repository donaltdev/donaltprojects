<?php

namespace airdrops\scheduler\items\antibuild;

use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use airdrops\sessions\Session;
use airdrops\sessions\SessionFactory;

class AntiTrappedScheduler extends Task {

    private Player $player;

    public function __construct(Player $player) {
        $this->player = $player;
        $this->getSession()->setAntibuild("expire", 15);
    }

    public function getPlayer(): ?Player {
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
        if ($session->getAntibuild("expire") === 0) {
            $session->setAntibuild("tag", false);
            $this->getPlayer()->sendMessage(TextFormat::GREEN . "The anti trapper effect has expired.");
            $this->getHandler()->cancel();
        } else {
            $session->setAntibuild("expire", $session->getAntibuild("expire") - 1);
        }
    }
}