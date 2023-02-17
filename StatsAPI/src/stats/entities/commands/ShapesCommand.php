<?php

namespace stats\entities\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use stats\entities\StatsPlayer;
use stats\entities\views\DisableShapeView;
use stats\entities\views\ShapesView;
use stats\Loader;

class ShapesCommand extends Command
{

	public function __construct(string $name, string $description = "Shapes command", string $usageMessage = null, array
	$aliases = [])
	{
		parent::__construct($name, $description, $usageMessage, $aliases);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if($sender instanceof StatsPlayer) {
			if(is_null($sender->getStats()->getShape())) {
				if ($sender->getStats()->getCountDown() > time()) {
					$missing = $sender->getStats()->getCountDown() - time();
					$sender->sendMessage("§eFaltam " . gmdate("H:i:s", $missing) . " para ativar outra forma!");
				} else {
					$view = new ShapesView();
					$view->sendForm($sender);
				}
			}else{
				$activeShape = Loader::getInstance()->getActiveShapeManager()->getActiveShape($sender->getLowerCaseName());
				$view = new DisableShapeView($activeShape);
				$view->sendForm($sender);
			}
		}else{
			$sender->sendMessage("§cComando somente em jogo!");
		}
	}

}