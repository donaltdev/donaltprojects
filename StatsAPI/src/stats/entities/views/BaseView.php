<?php

namespace stats\entities\views;

use pocketmine\form\Form;
use pocketmine\Player;

abstract class BaseView
{

	/** @var Form|null */
	protected $form = null;

	abstract public function createForm(Player $player);

	public function sendForm(Player $player)
	{
		$this->createForm($player);
		if (!is_null($this->form)) {
			$player->sendForm($this->form);
		}
	}

}