<?php


namespace stats\entities\views;


use jojoe77777\FormAPI\ModalForm;
use pocketmine\Player;
use stats\entities\bfs\BloodFactor;
use stats\entities\StatsPlayer;

class ActiveBloodFactorView extends BaseView
{

	/** @var BloodFactor */
	private $bloodFactor;

	/**
	 * ActiveBloodFactorView constructor.
	 * @param BloodFactor $bloodFactor
	 */
	public function __construct(BloodFactor $bloodFactor)
	{
		$this->bloodFactor = $bloodFactor;
	}

	/**
	 * @return BloodFactor|null
	 */
	public function getBloodFactor(): ?BloodFactor
	{
		return $this->bloodFactor;
	}


	public function createForm(Player $player)
	{

		$form = new ModalForm(function (Player $player, $data) {
			if (!is_null($data) && is_bool($data)) {
				/** @var StatsPlayer $player */
				if ($data) {
					try {
						$player->getStats()->setBloodFactor($this->getBloodFactor()->getName());
						$player->sendMessage("§aVocê ativou o blood factor '{$this->getBloodFactor()->getName(true)}' com sucesso!");
					} catch (\Exception $exception) {
						$player->sendMessage("§cErro: " . $exception->getMessage());
					}
				}
			} else {
				$view = new BloodFactorsView();
				$view->sendForm($player);
			}
		});

		$bf = $this->getBloodFactor();

		$form->setTitle("Ativação Blood Factor");
		$content = null;
		if ($bf->getDamage() > 0) {
			$content .= "\n  * Aumento do dano em §7" . $bf->getDamage() . " porcento§f.";
		}
		if ($bf->getHealth() > 0) {
			$content .= "\n  * Aumento da vida máxima em §7" . $bf->getHealth() . " porcento§f.";
		}
		if ($bf->getDefense() > 0) {
			$content .= "\n  * Aumento da defesa em §7" . $bf->getDefense() . " porcento §f.";
		}
		if ($bf->getSuperPower() > 0) {
			$content .= "\n  * Aumento do super power em §7" . $bf->getSuperPower() . " porcento §f.";
		}

		$form->setContent("Você confirma a ativação do Blood Factor §e'{$bf->getName(true)}'§f? \n" . ($content ?? ""));
		$form->setButton1("Confirmar");
		$form->setButton2("Recusar");

		$this->form = $form;
	}
}