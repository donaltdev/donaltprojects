<?php

namespace airdrops\scheduler;

use airdrops\entity\types\FinalNPC;
use airdrops\forms\LootMenu;
use muqsit\invmenu\InvMenu;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\world\sound\ClickSound;

class InvRandomizeScheduler extends Task {

    private Player $player;

    private InvMenu $invMenu;

    private FinalNPC $finalNPC;

    private int $time = 0;

    public function __construct(Player $player, InvMenu $invMenu, FinalNPC $finalNPC) {
        $this->player = $player;
        $this->invMenu = $invMenu;
        $this->finalNPC = $finalNPC;
        $this->setTime(5);
    }

    public function getPlayer(): ?Player {
        return $this->player;
    }

    public function getInvMenu(): ?InvMenu {
        return $this->invMenu;
    }

    public function getFinalNPC(): FinalNPC {
        return $this->finalNPC;
    }

    public function getTime(): int {
        return $this->time;
    }

    public function setTime(int $time): void {
        $this->time = $time;
    }

    public function onRun(): void {
        $player = $this->getPlayer();
        $menu = $this->getInvMenu();
        $time = $this->getTime();
        if ($player === null or !$player->isOnline() or $player->isClosed()) {
            $this->getHandler()->cancel();
            return;
        }
        if ($time === 0) {
            LootMenu::finalRandomizer($player, $menu, $this->getFinalNPC());
            $player->getWorld()->addSound($player->getPosition(), new ClickSound());
            $this->getHandler()->cancel();
        } else {
            $player->getWorld()->addSound($player->getPosition(), new ClickSound());
            $this->setTime($time - 1);
            LootMenu::randomizer($player, $menu);
        }
    }

}