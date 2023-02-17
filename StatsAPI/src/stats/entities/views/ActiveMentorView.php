<?php


namespace stats\entities\views;


use jojoe77777\FormAPI\ModalForm;
use pocketmine\Player;
use stats\entities\mentors\ActiveMentor;
use stats\entities\mentors\Mentor;
use stats\entities\StatsPlayer;
use stats\Loader;

class ActiveMentorView extends BaseView
{

	/** @var Mentor */
	private $mentor;

	/**
	 * ActiveMentorView constructor.
	 * @param Mentor $mentor
	 */
	public function __construct(Mentor $mentor)
	{
		$this->mentor = $mentor;
	}

	/**
	 * @return Mentor
	 */
	public function getMentor(): Mentor
	{
		return $this->mentor;
	}

	public function createForm(Player $player)
	{

		$mentor = $this->getMentor();

		$form = new ModalForm(function (Player $player, $data) use ($mentor){
			/** @var StatsPlayer $player */
			if (!is_null($data) && is_bool($data)) {
				if ($data) {
					$activeMentor = new ActiveMentor($player->getLowerCaseName(), $mentor->getName(), time());
					Loader::getInstance()->getActiveMentorManager()->setActiveMentor($activeMentor);
					$player->sendMessage("§aVocê ativou o mentor '{$mentor->getName(true)}' com sucesso!");
				} else {
					$view = new MentorsView();
					$view->sendForm($player);
				}
			}
		});
		$form->setTitle("Ativação do mentor");
		$content = null;
		if ($mentor->getDamage() > 0) {
			$content .= "\n  * Aumento do dano em §7" . $mentor->getDamage() . " porcento§f.";
		}
		if ($mentor->getHealth() > 0) {
			$content .= "\n  * Aumento da vida máxima em §7" . $mentor->getHealth() . " porcento§f.";
		}
		if ($mentor->getDefense() > 0) {
			$content .= "\n  * Aumento da defesa em §7" . $mentor->getDefense() . " porcento §f.";
		}
		if ($mentor->getSuperPower() > 0) {
			$content .= "\n  * Aumento do super power em §7" . $mentor->getSuperPower() . " porcento §f.";
		}

		$form->setContent("Você confirma a ativação do mentor §e'{$mentor->getName(true)}'§f? \n" .
			($content ?? "") . "\n  §f* Tempo ativo: §7" . gmdate("H:i:s", $mentor->getActiveTime()) . " \n  §f* Countdown: §7" .
			gmdate("H:i:s", $mentor->getCountdown()));
		$form->setButton1("Confirmar");
		$form->setButton2("Recusar");

		$this->form = $form;

	}

}