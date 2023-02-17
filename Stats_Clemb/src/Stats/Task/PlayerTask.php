<?php

namespace Stats\Task;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use Stats\GoogStats;

class PlayerTask extends Task
{

    public function onRun(int $currentTick): void
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            $session = GoogStats::getSessionManager()->getSession($player);
            if (!$session) continue;

            if ($session->getHp()->getTemporal() < $session->getHp()->getDefault()) {
                if (!$session->getPvp()[0] && rand(1, 3) == 2) {
                    if ($session->getHp()->getTemporal() < $session->getHp()->getDefault()) {
                        $percent = ($session->getHp()->getDefault() * 10) / 100;
                        if (($session->getHp()->getTemporal() + $percent) >= $session->getHp()->getDefault()) {
                            $session->getHp()->setTemporal($session->getHp()->getDefault());
                        } else {
                            $session->getHp()->setTemporal($session->getHp()->getTemporal() + $percent);
                        }
                    }
                } else {
                    $time = microtime(true) - $session->getPvp()[1];
                    if ($time > 0) {
                        $session->setPvp([false, 0]);
                    }
                }
            }

            if ($session->getAditional()->getTemporal() !== $session->getAditional()->getDefault()) {
                $session->getAditional()->setTemporal($session->getAditional()->getTemporal() + 1);
            }

            $player->sendTip(
                TextFormat::RED . "Vida: " . TextFormat::GRAY . round($session->getHp()->getTemporal()) . "/" . $session->getHp()->getDefault() . "    " .
                TextFormat::GREEN . "Dano: " . TextFormat::GRAY . round($session->getForce()->getTemporal()) . "/" . $session->getForce()->getDefault() . "\n" .
                //TextFormat::YELLOW . "Resistencia: " . TextFormat::GRAY . round($session->getShield()->getTemporal()) . "/" . $session->getShield()->getDefault() . "    " .
                TextFormat::AQUA . "Chakra: " . TextFormat::GRAY . round($session->getAditional()->getTemporal())
            );
        }
    }

}