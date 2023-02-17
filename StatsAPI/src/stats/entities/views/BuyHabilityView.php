<?php

namespace stats\entities\views;

use jojoe77777\FormAPI\ModalForm;
use pocketmine\Player;
use stats\entities\hability\Hability;
use stats\entities\StatsPlayer;
use stats\Loader;

class BuyHabilityView extends BaseView
{

	/** @var Hability */
	private $hability;

	/**
	 * BuyHabilityView constructor.
	 * @param Hability $hability
	 */
	public function __construct(Hability $hability)
	{
		$this->hability = $hability;
	}

	/**
	 * @return Hability
	 */
	public function getHability(): Hability
	{
		return $this->hability;
	}

	public function createForm(Player $player)
	{
		$form = new ModalForm(function (Player $player, $data){
			/** @var StatsPlayer $player */
			if(!is_null($data) && is_bool($data)){
				if($data){
					$playerMoney = Loader::getInstance()->getEconomy()->myMoney($player);
					$priceHability = $this->getHability()->getPrice();
					if($priceHability>$playerMoney){
						$player->sendMessage("§cVocê não tem tp suficiente para a compra da habilidade.");
					}else{
						if(Loader::getInstance()->getEconomy()->reduceMoney($player, $priceHability)==1){
							$player->getStats()->setHabilityName(strtolower($this->getHability()->getName()));
							// save data
							$player->sendMessage("§aVocê comprou com sucesso a habilidade '{$this->getHability()->getName()}'.");
						}else{
							$player->sendMessage("§cOcorreu um erro na compra da habilidade. Tente novamente!");
						}
					}
				}else{
					$view = new HabilitiesView();
					$view->sendForm($player);
				}
			}
		});
		$form->setTitle("Confirmação da compra");
		$car = null;
		$hab = $this->getHability();
		if($hab->getPercentageDamage() > 0){
			$car .= "\n  * Aumento do dano em §7" . $hab->getPercentageDamage() . " porcento§f.";
		}
		if($hab->getPercentageHealth() > 0){
			$car .= "\n  * Aumento da vida máxima em §7" . $hab->getPercentageHealth() . " porcento§f.";
		}
		if($hab->getPercentageDefense() > 0){
			$car .= "\n  * Redução de §7". $hab->getPercentageDefense() . " porcento §fno dano sofrido.";
		}
		$form->setContent("Você confirma a compra da habilidade §e'{$this->getHability()->getName()}'§f? \n" .
			($car ?? "") . "\n  §f* Preço: §6{$hab->getPrice()}tp");
		$form->setButton1("Confirmar");
		$form->setButton2("Recusar");
		$this->form = $form;
	}

}