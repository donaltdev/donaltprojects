<?php

namespace stats\entities;

use pocketmine\entity\Effect;
use pocketmine\entity\projectile\Projectile;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\item\Armor;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\Player;
use stats\Loader;

class StatsPlayer extends Player
{

	/** @var Stats */
	// private $stats;

	protected function completeLoginSequence()
	{
		$this->stats = Loader::getInstance()->getDataManager()->dataToStats($this);
		parent::completeLoginSequence();
		$manager = Loader::getInstance()->getActiveShapeManager();
		$activeShape = $manager->getActiveShape($this->getLowerCaseName());
		if (!is_null($activeShape)) {
			$activeShape->playerJoin();
		}

		$managerMentor = Loader::getInstance()->getActiveMentorManager();
		$activeMentor = $managerMentor->getActiveMentor($this->getLowerCaseName());
		if (!is_null($activeMentor)) {
			$activeMentor->playerJoin();
		}
	}

	/**
	 * @return Stats
	 */
	// public function getStats(): Stats
	// {
		// return $this->stats;
	// }

	public function isAlive(): bool
	{
		return $this->getHealth() > 0;
	}

	public function getHealth(): float
	{
		return $this->stats->getHealth();
	}

	public function getMaxHealth(): int
	{
		return $this->stats->getMaxHealth();
	}

	public function setHealth(float $amount): void
	{

		$amount = (int)$amount;

		$wasAlive = $this->isAlive();

		if ($amount == $this->getHealth()) {
			return;
		}

		if ($amount <= 0) {
			if ($this->isAlive()) {
				$this->stats->setHealth(0);
				$this->kill();
			}
		} elseif ($amount <= $this->getMaxHealth() or $amount < $this->getHealth()) {
			$this->stats->setHealth($amount);
		} else {
			$this->stats->setHealth($this->getMaxHealth());
		}

		if ($this->isAlive() and !$wasAlive) {
			$this->broadcastEntityEvent(ActorEventPacket::RESPAWN);
		}

	}

	public function setMaxHealth(int $amount): void
	{
		$this->stats->setMaxHealth($amount);
	}

	public function heal(EntityRegainHealthEvent $source): void
	{
		$source->call();
		if ($source->isCancelled()) {
			return;
		}

		$heal = $source->getAmount();

		switch ($source->getRegainReason()) {
			case EntityRegainHealthEvent::CAUSE_EATING:
			case EntityRegainHealthEvent::CAUSE_SATURATION:
				$heal = ($this->getMaxHealth() * 2) / 100;
				break;
			case EntityRegainHealthEvent::CAUSE_MAGIC:
			case EntityRegainHealthEvent::CAUSE_REGEN:
				$heal = ($this->getMaxHealth() * 5) / 100;
				break;
		}

		$this->setHealth($this->getHealth() + $heal);
		$this->getStats()->sendHealthBar();
	}

	public function applyDamageModifiers(EntityDamageEvent $source) : void{
		if($this->lastDamageCause !== null and $this->attackTime > 0){
			if($this->lastDamageCause->getBaseDamage() >= $source->getBaseDamage()){
				$source->setCancelled();
			}
			$source->setModifier(-$this->lastDamageCause->getBaseDamage(), EntityDamageEvent::MODIFIER_PREVIOUS_DAMAGE_COOLDOWN);
		}

		$cause = $source->getCause();
		if($this->hasEffect(Effect::DAMAGE_RESISTANCE) and $cause !== EntityDamageEvent::CAUSE_VOID and $cause !== EntityDamageEvent::CAUSE_SUICIDE){
			$source->setModifier(-$source->getFinalDamage() * min(1, 0.2 * $this->getEffect(Effect::DAMAGE_RESISTANCE)->getEffectLevel()), EntityDamageEvent::MODIFIER_RESISTANCE);
		}

		$totalEpf = 0;
		$source->setModifier(-$source->getFinalDamage() * min(ceil(min($totalEpf, 25) * (mt_rand(50, 100) / 100)), 20) * 0.04, EntityDamageEvent::MODIFIER_ARMOR_ENCHANTMENTS);

		$source->setModifier(-min($this->getAbsorption(), $source->getFinalDamage()), EntityDamageEvent::MODIFIER_ABSORPTION);
	}

	public function attack(EntityDamageEvent $source): void
	{

		if (!$this->isAlive()) {
			return;
		}

		if ($this->isCreative() and $source->getCause() !== EntityDamageEvent::CAUSE_SUICIDE
			and $source->getCause() !== EntityDamageEvent::CAUSE_VOID) {
			$source->setCancelled();
		} else if ($this->allowFlight and $source->getCause() === EntityDamageEvent::CAUSE_FALL) {
			$source->setCancelled();
		}

		if($this->noDamageTicks > 0){
			$source->setCancelled();
		}

		if($this->hasEffect(Effect::FIRE_RESISTANCE) and (
				$source->getCause() === EntityDamageEvent::CAUSE_FIRE
				or $source->getCause() === EntityDamageEvent::CAUSE_FIRE_TICK
				or $source->getCause() === EntityDamageEvent::CAUSE_LAVA)){
			$source->setCancelled();
		}

		$this->applyDamageModifiers($source);

		if($source instanceof EntityDamageByEntityEvent and (
				$source->getCause() === EntityDamageEvent::CAUSE_BLOCK_EXPLOSION or
				$source->getCause() === EntityDamageEvent::CAUSE_ENTITY_EXPLOSION)){
			$base = $source->getKnockBack();
			$source->setKnockBack($base - min($base, $base * $this->getHighestArmorEnchantmentLevel(Enchantment::BLAST_PROTECTION) * 0.15));
		}

		if ($this->attackTime > 0) {
			$source->setCancelled();
		}

		$source->call();
		if($source->isCancelled()){
			return;
		}

		$this->setLastDamageCause($source);

		$this->setHealth($this->getHealth() - $source->getFinalDamage());

		if($source->isCancelled()){
			return;
		}

		$this->attackTime = $source->getAttackCooldown();

		if($source instanceof EntityDamageByChildEntityEvent){
			$e = $source->getChild();
			if($e !== null){
				$motion = $e->getMotion();
				$this->knockBack($e, $source->getBaseDamage(), $motion->x, $motion->z, $source->getKnockBack());
			}
		}elseif($source instanceof EntityDamageByEntityEvent){
			$e = $source->getDamager();
			if($e !== null){
				$deltaX = $this->x - $e->x;
				$deltaZ = $this->z - $e->z;
				$this->knockBack($e, $source->getBaseDamage(), $deltaX, $deltaZ, $source->getKnockBack());
			}
		}

		if($this->isAlive()){
			$this->applyPostDamageEffects($source);
			$this->doHitAnimation();
		}
	}

	protected function applyPostDamageEffects(EntityDamageEvent $source): void
	{
		$this->setAbsorption(max(0, $this->getAbsorption() + $source->getModifier(EntityDamageEvent::MODIFIER_ABSORPTION)));
		if($source instanceof EntityDamageByEntityEvent){
			$damage = 0;
			foreach($this->armorInventory->getContents() as $k => $item){
				if($item instanceof Armor and ($thornsLevel = $item->getEnchantmentLevel(Enchantment::THORNS)) > 0){
					if(mt_rand(0, 99) < $thornsLevel * 15){
						$damage += ($thornsLevel > 10 ? $thornsLevel - 10 : 1 + mt_rand(0, 3));
					}
				}
			}
			if($damage > 0){
				$source->getDamager()->attack(new EntityDamageByEntityEvent($this, $source->getDamager(), EntityDamageEvent::CAUSE_MAGIC, $damage));
			}
		}
	}

	// public function getName(): string
	// {
		// $username = $this->username;

		// if ($this->hasSpaces($username)) {
			// $username = str_replace(" ", "_", $username);

			// $this->username = $username;
			// $this->displayName = $username;
			// $this->iusername = strtolower($username);

			// return $username;
		// }

		// return $username;
	// }

	/**
	 * Retorna o nome de exibição do jogador substituindo os espaços no nome do jogador.
	 *
	 * @return string
	 */
	// public function getDisplayName(): string
	// {
		// $displayName = $this->displayName;

		// if ($this->hasSpaces($displayName)) {
			// $displayName = str_replace(" ", "_", $displayName);

			// $this->username = $displayName;
			// $this->displayName = $displayName;
			// $this->iusername = strtolower($displayName);

			// return $displayName;
		// }

		// return $displayName;
	// }

	/**
	 * Retorna o nome minúsculo do jogador substituindo os espaços no nome do jogador.
	 *
	 * @return string
	 */
	// public function getLowerCaseName(): string
	// {
		// $iusername = $this->iusername;

		// if ($this->hasSpaces($iusername)) {
			// $iusername = str_replace(" ", "_", $iusername);

			// $this->username = $iusername;
			// $this->displayName = $iusername;
			// $this->iusername = strtolower($iusername);

			// return $iusername;
		// }

		// return $iusername;
	// }

	/**
	 * Verifica se a string tem espaços ou não.
	 *
	 * @param string $string
	 * @return bool
	 */
	// private function hasSpaces(string $string): bool
	// {
		// return strpos($string, ' ') !== false;
	// }
// }