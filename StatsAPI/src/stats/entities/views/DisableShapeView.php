<?php


namespace stats\entities\views;

use jojoe77777\FormAPI\ModalForm;
use pocketmine\Player;
use stats\entities\shape\ActiveShape;
use stats\entities\StatsPlayer;

class DisableShapeView extends BaseView
{

	/** @var ActiveShape */
	private $activeShape;

	/**
	 * DisableShapeView constructor.
	 * @param ActiveShape $activeShape
	 */
	public function __construct(ActiveShape $activeShape)
	{
		$this->activeShape = $activeShape;
	}

	public function createForm(Player $player)
	{
		/** @var StatsPlayer $player */
		$stats = $player->getStats();
		$shape = $stats->getShape();
		if (is_null($shape) || $shape->getName() !== $this->activeShape->getShape()->getName()) {
			$player->sendMessage("§cOcorreu um erro, tente novamente!");
			return;
		}

		$form = new ModalForm(function (Player $player, $data) {
			/** @var StatsPlayer $player */
			if (!is_null($data) && !is_null($this->activeShape)) {
				if ($data) {
					try {
						$player->sendMessage("§aDesativada a forma com sucesso, tempo de countdown: " .
							gmdate("H:i:s!", $this->activeShape->getShape()->getTimeCountdown()));
						$this->activeShape->disableShape();
					} catch (\Exception $exception){
						$player->sendMessage("§cErro: {$exception->getMessage()}");
					}
				} else {
					$player->sendMessage("§cDesativação da forma cancelada!");
				}
			}
		});

		$form->setTitle("Confirmação para desativar a forma!");
		$form->setContent("§e* Tenha em mente que desativando a forma, você vai ter que esperar "
			. gmdate("H:i:s", $shape->getTimeCountdown()) .
			" para usar outra forma.\n\n\n§fVocê confirma a desativação da forma §e'" . $shape->getName(true) . "'§f?");
		$form->setButton1("Confirmar");
		$form->setButton2("Recusar");

		$this->form = $form;

	}

}