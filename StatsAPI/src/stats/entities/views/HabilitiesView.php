<?php

namespace stats\entities\views;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use stats\controllers\HabilityManager;

class HabilitiesView extends BaseView
{

	public function createForm(Player $player)
	{
		$form = new SimpleForm(function (Player $player, $data){
			if(!is_null($data) || is_numeric($data)){
				$habKeys = array_keys(HabilityManager::getHabilities());
				if(isset($habKeys[$data])){
					$hability = HabilityManager::getHabilityByName($habKeys[$data]);
					$view = new BuyHabilityView($hability);
					$view->sendForm($player);
				}
			}
		});
		$form->setTitle("Habilidades");
		$form->setContent("§e* Depois da compra de uma habilidade você não poderá comprar outra!");
		foreach (HabilityManager::getHabilities() as $name => $hability){
			$form->addButton($hability->getName() . "\n(Clique para comprar)");
		}
		$this->form = $form;
	}

}