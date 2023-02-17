<?php


namespace stats\entities\views;


use jojoe77777\FormAPI\ModalForm;
use pocketmine\Player;
use stats\entities\mentors\ActiveMentor;
use stats\entities\StatsPlayer;

class DisableMentorView extends BaseView
{

	/** @var ActiveMentor */
	private $activeMentor;

	/**
	 * DisableMentorView constructor.
	 * @param ActiveMentor $activeMentor
	 */
	public function __construct(ActiveMentor $activeMentor)
	{
		$this->activeMentor = $activeMentor;
	}

	public function createForm(Player $player)
	{
		/** @var StatsPlayer $player */
		$stats = $player->getStats();
		$mentor = $stats->getMentor();
		if (is_null($mentor) || $mentor->getName() !== $this->activeMentor->getMentor()->getName()) {
			$player->sendMessage("§cOcorreu um erro, tente novamente!");
			return;
		}

		$form = new ModalForm(function (Player $player, $data) {
			/** @var StatsPlayer $player */
			if (!is_null($data) && !is_null($this->activeMentor)) {
				if ($data) {
					try {
						$player->sendMessage("§aDesativado o mentor com sucesso, tempo de countdown: " .
							gmdate("H:i:s!", $this->activeMentor->getMentor()->getCountdown()));
						$this->activeMentor->disableMentor();
					} catch (\Exception $exception){
						$player->sendMessage("§cErro: {$exception->getMessage()}");
					}
				} else {
					$player->sendMessage("§cDesativação do mentor cancelado!");
				}
			}
		});

		$form->setTitle("Confirmação para desativar o mentor!");
		$form->setContent("§e* Tenha em mente que desativando o mentor, você vai ter que esperar "
			. gmdate("H:i:s", $mentor->getCountdown()) .
			" para usar outra forma.\n\n\n§fVocê confirma a desativação do mentor §e'" . $mentor->getName(true) . "'§f?");
		$form->setButton1("Confirmar");
		$form->setButton2("Recusar");

		$this->form = $form;
	}

}