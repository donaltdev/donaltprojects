<?php

namespace airdrops\scheduler\items\zap;

use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use airdrops\sessions\Session;
use airdrops\sessions\SessionFactory;

class ZapedScheduler extends Task {

    private Player $player;

    public function __construct(Player $player) {
        $this->player = $player;
        $this->getSession()->setZap("expire", 10);
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
        if ($session->getZap("expire") === 0) {
            $session->setZap("tag", false);
            $this->getPlayer()->sendMessage(TextFormat::GREEN . "The zap effect has expired.");
            $this->getHandler()->cancel();
        } else {
            $session->setZap("expire", $session->getZap("expire") - 1);
        }
    }
}