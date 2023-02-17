<?php

namespace stats\entities\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use stats\entities\StatsPlayer;
use stats\entities\views\BloodFactorsView;
use stats\entities\views\DisableBloodFactorView;

class BfsCommand extends Command
{

	public function __construct(string $name, string $description = "Blood factors command", string $usageMessage =
	null, array $aliases = [])
	{
		parent::__construct($name, $description, $usageMessage, $aliases);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if ($sender instanceof StatsPlayer) {

			if (is_null($sender->getStats()->getBloodFactor())) {
				$view = new BloodFactorsView();
				$view->sendForm($sender);
			} else {
				$view = new DisableBloodFactorView();
				$view->sendForm($sender);
			}

		} else {
			$sender->sendMessage("§cComando somente em jogo!");
		}
	}

}