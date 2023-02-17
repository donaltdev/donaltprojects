<?php


namespace stats\entities\commands;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use stats\entities\StatsPlayer;
use stats\entities\views\DisableMentorView;
use stats\entities\views\MentorsView;
use stats\Loader;

class MentorsCommand extends Command
{

	public function __construct(string $name, string $description = "Mentors command", string $usageMessage =
	null, array $aliases = [])
	{
		parent::__construct($name, $description, $usageMessage, $aliases);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if ($sender instanceof StatsPlayer) {

			if(is_null($sender->getStats()->getMentor())) {
				if ($sender->getStats()->getCountDownMentor() > time()) {
					$missing = $sender->getStats()->getCountDownMentor() - time();
					$sender->sendMessage("§eFaltam " . gmdate("H:i:s", $missing) . " para ativar outro mentor!");
				} else {
					$view = new MentorsView();
					$view->sendForm($sender);
				}
			}else{
				$activeMentor = Loader::getInstance()->getActiveMentorManager()->getActiveMentor($sender->getLowerCaseName());
				$view = new DisableMentorView($activeMentor);
				$view->sendForm($sender);
			}

		} else {
			$sender->sendMessage("§cComando somente em jogo!");
		}
	}

}