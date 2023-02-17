<?php

namespace stats\entities\mentors;

use stats\Loader;

class Mentor
{

	/** @var string */
	protected $name;

	/** @var int */
	protected $damage;
	/** @var int */
	protected $health;
	/** @var int */
	protected $defense;
	/** @var int */
	protected $superPower;

	/** @var int */
	protected $activeTime;
	/** @var int */
	protected $countdown;

	/**
	 * Mentor constructor.
	 * @param string $name
	 * @param int $damage
	 * @param int $health
	 * @param int $defense
	 * @param int $superPower
	 * @param int $activeTime
	 * @param int $countdown
	 */
	public function __construct(string $name, int $damage, int $health, int $defense, int $superPower, int $activeTime, int $countdown)
	{
		$this->name = $name;
		$this->damage = $damage;
		$this->health = $health;
		$this->defense = $defense;
		$this->superPower = $superPower;
		$this->activeTime = $activeTime;
		$this->countdown = $countdown;
	}

	/**
	 * @param bool $formatted
	 * @return string
	 */
	public function getName(bool $formatted = false)
	{
		return $formatted ? $this->getValue("name") : $this->name;
	}

	/**
	 * @return int|mixed
	 */
	public function getPermission()
	{
		return $this->getValue("permission");
	}

	/**
	 * @return string
	 */
	public function getCommand()
	{
		return $this->getValue("command");
	}

	/**
	 * @param string $key
	 * @return int|mixed
	 */
	protected function getValue(string $key)
	{
		$configAll = Loader::getInstance()->getConfigMentors()->getAll();
		return isset($configAll[$this->getName()][$key]) ? $configAll[$this->getName()][$key] : 0;
	}

	/**
	 * @return int
	 */
	public function getDamage(): int
	{
		return $this->damage;
	}

	/**
	 * @return int
	 */
	public function getHealth(): int
	{
		return $this->health;
	}

	/**
	 * @return int
	 */
	public function getDefense(): int
	{
		return $this->defense;
	}

	/**
	 * @return int
	 */
	public function getSuperPower(): int
	{
		return $this->superPower;
	}

	/**
	 * @return int
	 */
	public function getActiveTime(): int
	{
		return $this->activeTime;
	}

	/**
	 * @return int
	 */
	public function getCountdown(): int
	{
		return $this->countdown;
	}


}