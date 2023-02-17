<?php

namespace stats\entities\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use stats\entities\StatsPlayer;
use stats\entities\views\HabilitiesView;

class HabilidadesCommand extends Command
{

	public function __construct(string $name, string $description = "Habilidades command", string $usageMessage = null, array $aliases = [])
	{
		parent::__construct($name, $description, $usageMessage, $aliases);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if($sender instanceof StatsPlayer) {
			if(is_null($sender->getStats()->getHability())) {
				$view = new HabilitiesView();
				$view->sendForm($sender);
			}else{
				$sender->sendMessage("§eVocê já comprou a habilidade '{$sender->getStats()->getHability()->getName()}'!");
			}
		}else{
			$sender->sendMessage("§cComando somente em jogo!");
		}
	}

}