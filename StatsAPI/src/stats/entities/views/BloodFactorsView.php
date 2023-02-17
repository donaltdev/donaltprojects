<?php

namespace stats\entities\views;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use stats\controllers\BloodFactorsManager;
use stats\entities\bfs\BloodFactor;
use stats\entities\StatsPlayer;

class BloodFactorsView extends BaseView
{

	public function createForm(Player $player)
	{
		/** @var StatsPlayer $player */
		$bloodFactors = $this->getBloodFactors($player);

		if (count($bloodFactors) == 0) {
			$player->sendMessage("§cNão tem nenhum blood factor disponivel para o seu uso!");
			return;
		}

		$form = new SimpleForm(function (Player $player, $data) use ($bloodFactors){
			if (!is_null($data) && isset($bloodFactors[$data])) {
				$bloodFactor = BloodFactorsManager::getBloodByName($bloodFactors[$data]);
				$view = new ActiveBloodFactorView($bloodFactor);
				$view->sendForm($player);
			}
		});

		$form->setTitle("Blood Factors");
		$form->setContent("§e* Ative um Blood Factor para ganhar stats!");
		foreach ($bloodFactors as $bloodFactorName) {
			$bloodFactor = BloodFactorsManager::getBloodByName($bloodFactorName);
			$form->addButton($bloodFactor->getName(true) . "\n(Clique para ativar)");
		}

		$this->form = $form;
	}

	/**
	 * @param Player $player
	 * @return BloodFactor[]
	 */
	private function getBloodFactors(Player $player): array
	{
		$bloodFactors = [];
		foreach (BloodFactorsManager::getBloodFactors() as $name => $bloodFactor) {
			if($player->hasPermission($bloodFactor->getPermission())) {
				$bloodFactors[] = $name;
			}
		}
		return $bloodFactors;
	}


}