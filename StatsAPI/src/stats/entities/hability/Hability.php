<?php

namespace stats\entities\hability;

use stats\Loader;

class Hability
{

	protected $name;

	/**
	 * Hability constructor.
	 * @param $name
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $key
	 * @return int|mixed|null
	 */
	protected function getValue(string $key){
		return Loader::getInstance()->getConfigManager()->getKeyValue($this->getName(), $key) ?? 0;
	}

	/**
	 * @param int $maxHealth
	 * @return int
	 */
	public function getIncreaseHealth(int $maxHealth): int
	{
		return ($maxHealth * $this->getPercentageHealth()) / 100;
	}

	/**
	 * @param int $damage
	 * @return int
	 */
	public function getIncreaseDamage(int $damage): int
	{
		return ($damage * $this->getPercentageDamage()) / 100;
	}

	/**
	 * @param int $receivedDamage
	 * @return int
	 */
	public function applyReducedDamage(int $receivedDamage): int{
		return ($this->getPercentageDefense() > 0) ? (($receivedDamage * $this->getPercentageDefense()) / 100) :
			$receivedDamage;
	}

	public function getPercentageDamage(): int
	{
		return (int)$this->getValue("damage");
	}

	public function getPercentageHealth(): int
	{
		return (int)$this->getValue("max_health");
	}

	public function getPercentageDefense(): int
	{
		return (int)$this->getValue("defense");
	}

	public function getPrice(): int
	{
		return (int)$this->getValue("price");
	}

}