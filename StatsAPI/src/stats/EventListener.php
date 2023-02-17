<?php


namespace stats;

use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerQuitEvent;
use stats\entities\StatsPlayer;

class EventListener implements Listener
{

	public function onCreation(PlayerCreationEvent $event)
	{
		$event->setPlayerClass(StatsPlayer::class);
	}

	public function onQuit(PlayerQuitEvent $event)
	{
		$manager = Loader::getInstance()->getActiveShapeManager();
		$activeShape = $manager->getActiveShape($event->getPlayer()->getLowerCaseName());
		if (!is_null($activeShape)) {
			$activeShape->playerQuit();
		}

		$managerMentor = Loader::getInstance()->getActiveMentorManager();
		$activeMentor = $managerMentor->getActiveMentor($event->getPlayer()->getLowerCaseName());
		if (!is_null($activeMentor)) {
			$activeMentor->playerQuit();
		}
	}

	/**
	 * @param EntityDamageEvent $event
	 * @ignoreCancelled true
	 * @priority HIGHEST
	 * 
	 * @return void
	 */
	public function onDamage(EntityDamageEvent $event) : void {
		$entity = $event->getEntity();
		$damage = $event->getBaseDamage();

		if($event instanceof EntityDamageByChildEntityEvent && $event->getCause() === $event::CAUSE_PROJECTILE) {
			$damager = $event->getDamager();

			if($damager instanceof StatsPlayer) {
				$damage += $damager->getStats()->getSuperPower();
			}
		}
		elseif($event instanceof EntityDamageByEntityEvent) {
			$damager = $event->getDamager();

			if($damager instanceof StatsPlayer) {
				$damage += $damager->getStats()->getDamage();
			}
		}

		if($entity instanceof StatsPlayer) {
			if($event->getCause() === $event::CAUSE_VOID) {
				$event->setBaseDamage(($entity->getMaxHealth() * 25) / 100);
				return; //void
			}
			$stats = $entity->getStats();

			if(($defense = $stats->getDefense()) > 0) {
				$damage = max(0, $damage - $defense);
			}
		}
		$event->setBaseDamage($damage);
	}

}