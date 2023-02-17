<?php

namespace stats\entities\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use stats\entities\StatsPlayer;
use stats\entities\views\StatsView;

class StatsCommand extends Command
{

	public function __construct(string $name, string $description = "Stats command", string $usageMessage = null, array
	$aliases = [])
	{
		parent::__construct($name, $description, $usageMessage, $aliases);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if($sender instanceof StatsPlayer){
			$view = new StatsView();
			$view->sendForm($sender);
		}else{
			$sender->sendMessage("§cComando disponivel somente em jogo!");
		}
	}

}