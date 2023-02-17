<?php


namespace stats\entities\views;


use jojoe77777\FormAPI\ModalForm;
use pocketmine\Player;
use stats\entities\StatsPlayer;

class DisableBloodFactorView extends BaseView
{

	public function createForm(Player $player)
	{
		/** @var StatsPlayer $player */
		$bloodFactor = $player->getStats()->getBloodFactor();

		if (is_null($bloodFactor)) {
			$player->sendMessage("§cOcorreu um erro, tente novamente!");
			return;
		}

		$form = new ModalForm(function (Player $player, $data){
			if (!is_null($data)) {
				/** @var StatsPlayer $player */
				if ($data) {
					try {
						$player->getStats()->setBloodFactor(null);
						$player->sendMessage("§aDesativado o blood factor com sucesso!");
					} catch (\Exception $exception){
						$player->sendMessage("§cErro: {$exception->getMessage()}");
					}
				} else {
					$player->sendMessage("§cDesativação do blood factor cancelado!");
				}
			}
		});
		$form->setTitle("Desativação Blood Factor!");
		$form->setContent("§fVocê confirma a desativação do Blood Factor §e'" . $bloodFactor->getName(true) . "'§f?");
		$form->setButton1("Confirmar");
		$form->setButton2("Recusar");

		$this->form = $form;
	}

}