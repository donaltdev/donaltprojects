<?php

namespace stats\entities\shape;

use pocketmine\entity\Skin;
use stats\Loader;

class Shape
{

	protected $name;
	protected $skin;

	/**
	 * Shape constructor.
	 * @param string $name
	 * @param Skin $skin
	 */
	public function __construct(string $name, Skin $skin)
	{
		$this->name = $name;
		$this->skin = $skin;
	}

	/**
	 * @param bool $formatted
	 * @return mixed
	 */
	public function getName(bool $formatted = false)
	{
		return $formatted ? $this->getValue("name") : $this->name;
	}

	/**
	 * @return Skin
	 */
	public function getSkin(): Skin
	{
		return $this->skin;
	}

	/**
	 * @param string $key
	 * @return int|mixed|null
	 */
	protected function getValue(string $key){
		$configAll = Loader::getInstance()->getConfigShape()->getAll();
		return isset($configAll[$this->getName()][$key]) ? $configAll[$this->getName()][$key] : 0;
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

	public function getPercentageSuper(): int
	{
		return (int)$this->getValue("superpower");
	}

	public function getPermission(): string
	{
		return (string)$this->getValue("permission");
	}

	public function getCommand(): string
	{
		return (string)$this->getValue("command");
	}

	public function getTimeActive(): int
	{
		return (int)$this->getValue("time-active");
	}

	public function getTimeCountdown(): int
	{
		return (int)$this->getValue("time-countdown");
	}

}