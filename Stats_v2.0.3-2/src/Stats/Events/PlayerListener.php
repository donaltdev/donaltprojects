<?php

namespace Stats\Events;

use BootPvp\bot\entity\types\Bot;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use Stats\GoogStats;

class PlayerListener implements Listener {
    public function onJoin(PlayerJoinEvent $event): void
    {
        GoogStats::getSessionManager()->addSession($event->getPlayer());
    }

    public function onDamage(EntityDamageEvent $event): void
    {
        $entity = $event->getEntity();

        if ($event instanceof EntityDamageByEntityEvent) {
            $damager = $event->getDamager();
            if (GoogStats::getSessionManager()->getWorld($entity->getLevelNonNull())->isProtected() && $entity instanceof Player) {
                if (!$damager instanceof Bot) {
                    $event->setCancelled();
                    return;
                } else {
                    $event->setCancelled(false);
                }
            }

            if (!$entity instanceof Player && $damager instanceof Player) {
                $sessionD = GoogStats::getSessionManager()->getSession($damager);
                if (!$sessionD) return;

                $event->setBaseDamage($sessionD->getForce()->getTemporal());
                return;
            }

            $sessionE = GoogStats::getSessionManager()->getSession($entity);
            if (!$sessionE) return;

            if ($damager instanceof Player && $event->getCause() !== $event::CAUSE_PROJECTILE) {
                $sessionD = GoogStats::getSessionManager()->getSession($damager);
                $damage = $sessionE->getHp()->getTemporal() - $sessionD->getForce()->getTemporal();
            } else {
                $damage = $sessionE->getHp()->getTemporal() - $event->getBaseDamage();
            }

            $event->setBaseDamage(0);

            $sessionE->setPvp([true, (microtime(true) + 30)]);

            $sessionE->getHp()->setTemporal($damage);

            if ($sessionE->getHp()->getTemporal() <= 0) {
                $entity->kill();

                $sessionE->getHp()->setTemporal($sessionE->getHp()->getDefault());
            }
        } else {
            if (GoogStats::getSessionManager()->getWorld($entity->getLevelNonNull())->isProtected() && $entity instanceof Player) {
                $event->setCancelled();
                return;
            }
        }

    }

    public function onQuit(PlayerQuitEvent $event): void
    {
        GoogStats::getSessionManager()->delSession($event->getPlayer());
    }

}