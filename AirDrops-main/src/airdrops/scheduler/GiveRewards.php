<?php

namespace airdrops\scheduler;

use airdrops\entity\EntityFactory;
use airdrops\entity\types\FinalNPC;
use airdrops\sessions\SessionFactory;
use muqsit\invmenu\InvMenu;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\world\sound\ExplodeSound;

class GiveRewards extends Task {

    private Player $player;

    private array $items;

    private FinalNPC $finalNPC;

    private int $time = 0;

    public function __construct(Player $player, array $items, FinalNPC $finalNPC) {
        $this->player = $player;
        $this->items = $items;
        $this->finalNPC = $finalNPC;
        $this->setTime(3);
    }

    public function getPlayer(): ?Player {
        return $this->player;
    }

    public function getItems(): array {
        return $this->items;
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
        $time = $this->getTime();
        $npc = $this->getFinalNPC();
        if ($player === null or !$player->isOnline() or $player->isClosed()) {
            $this->getHandler()->cancel();
            return;
        }
        if ($time === 0) {
            foreach ($this->getItems() as $item) {
                if ($player->getInventory()->canAddItem($item)) {
                    $player->getInventory()->addItem($item);
                } else {
                    $player->getWorld()->dropItem($player->getPosition(), $item);
                }
            }
            (SessionFactory::getInstance()->get($player->getName()))->setAir(false);
            $player->getWorld()->addSound($player->getPosition(), new ExplodeSound());
            EntityFactory::getInstance()->eliminateByName($npc->getIdName());
            $this->getHandler()->cancel();
        } else {
            $this->setTime($time - 1);
        }
    }
}