<?php

namespace stats\entities\views;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use stats\entities\StatsPlayer;

class StatsView extends BaseView
{

	public function createForm(Player $player)
	{
		/** @var StatsPlayer $player */
		$stats = $player->getStats();
		$form = new SimpleForm(function (Player $player, $data) {
			if(!is_null($data)){
				switch ($data) {
					case 0: $type = UpgradeView::HEALTH_TYPE; break;
					case 1: $type = UpgradeView::DAMAGE_TYPE; break;
					case 2: $type = UpgradeView::DEFENSE_TYPE; break;
					case 3: $type = UpgradeView::SUPER_TYPE; break;
					default: return;
				}
				$view = new UpgradeView($type);
				$view->sendForm($player);
			}
		});
		$form->setTitle("Stats");
		$form->setContent("Sua vida máxima atual: §c" .$stats->getMaxHealth(true) .
			" \n§fSeu dano atual: §a". $stats->getDamage(true) .
			" \n§fSua defesa atual: §b". $stats->getDefense(true) .
			" \n§fSeu SPW atual: §e". $stats->getSuperPower(true) .
			"\n \n");
		$form->addButton("Aumente sua vida máxima!\n(Clique para mais detalhes)");
		$form->addButton("Aumente seu dano!\n(Clique para mais detalhes)");
		$form->addButton("Aumente sua defesa!\n(Clique para mais detalhes)");
		$form->addButton("Aumente seu SPW!\n(Clique para mais detalhes)");

		$this->form = $form;
	}

}