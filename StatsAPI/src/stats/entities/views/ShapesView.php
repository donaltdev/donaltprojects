<?php

namespace stats\entities\views;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use stats\controllers\ShapeManager;

class ShapesView extends BaseView
{

	public function createForm(Player $player)
	{
		$shapes = $this->getShapes($player);

		if (count($shapes) == 0) {
			$player->sendMessage("§cNão tem nenhuma forma disponivel para o seu uso!");
			return;
		}

		$form = new SimpleForm(function (Player $player, $data) use ($shapes){
			if(!is_null($data) || is_numeric($data)){
				if(isset($shapes[$data])){
					$shape = ShapeManager::getShapeByName($shapes[$data]);
					$view = new ActiveShapeView($shape);
					$view->sendForm($player);
				}
			}
		});
		$form->setTitle("Formas");
		$form->setContent("§e* Ative uma forma para ganhar stats!");
		foreach ($shapes as $shapeName){
			$shape = ShapeManager::getShapeByName($shapeName);
			$form->addButton($shape->getName(true) . "\n(Clique para ativar)");
		}
		$this->form = $form;
	}

	public function getShapes(Player $player) {
		$shapes = [];
		foreach (ShapeManager::getShapes() as $name => $shape) {
			if ($player->hasPermission($shape->getPermission())) {
				$shapes[] = $name;
			}
		}
		return $shapes;
	}

}