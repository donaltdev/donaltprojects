<?php


namespace stats\entities\views;

use jojoe77777\FormAPI\ModalForm;
use pocketmine\Player;
use stats\entities\shape\ActiveShape;
use stats\entities\shape\Shape;
use stats\entities\StatsPlayer;
use stats\Loader;

class ActiveShapeView extends BaseView
{

	/** @var Shape */
	private $Shape;

	/**
	 * BuyShapeView constructor.
	 * @param Shape $Shape
	 */
	public function __construct(Shape $Shape)
	{
		$this->Shape = $Shape;
	}

	/**
	 * @return Shape
	 */
	public function getShape(): Shape
	{
		return $this->Shape;
	}

	public function createForm(Player $player)
	{
		$form = new ModalForm(function (Player $player, $data) {
			/** @var StatsPlayer $player */
			if (!is_null($data) && is_bool($data)) {
				if ($data) {
					$activeShape = new ActiveShape($player->getLowerCaseName(), $this->getShape()->getName(), time());
					Loader::getInstance()->getActiveShapeManager()->setActiveShape($activeShape);
					$player->sendMessage("§aVocê ativou a forma '{$this->getShape()->getName(true)}' com sucesso!");
				} else {
					$view = new ShapesView();
					$view->sendForm($player);
				}
			}
		});
		$form->setTitle("Ativação da forma");
		$car = null;
		$shape = $this->getShape();
		if ($shape->getPercentageDamage() > 0) {
			$car .= "\n  * Aumento do dano em §7" . $shape->getPercentageDamage() . " porcento§f.";
		}
		if ($shape->getPercentageHealth() > 0) {
			$car .= "\n  * Aumento da vida máxima em §7" . $shape->getPercentageHealth() . " porcento§f.";
		}
		if ($shape->getPercentageDefense() > 0) {
			$car .= "\n  * Aumento da defesa em §7" . $shape->getPercentageDefense() . " porcento §f.";
		}
		if ($shape->getPercentageSuper() > 0) {
			$car .= "\n  * Aumento do super power em §7" . $shape->getPercentageSuper() . " porcento §f.";
		}

		$form->setContent("Você confirma a ativação da forma §e'{$this->getShape()->getName(true)}'§f? \n" .
			($car ?? "") . "\n  §f* Tempo ativo: §7" . gmdate("H:i:s", $shape->getTimeActive()) . " \n  §f* Countdown: §7" .
			gmdate("H:i:s", $shape->getTimeCountdown()));
		$form->setButton1("Confirmar");
		$form->setButton2("Recusar");
		$this->form = $form;
	}

}