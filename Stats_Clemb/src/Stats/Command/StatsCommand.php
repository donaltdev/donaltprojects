<?php

namespace Stats\Command;

use Modos\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use Stats\Form\SimpleWindowForm;

class StatsCommand extends Command
{

    public function __construct()
    {
        parent::__construct("stats", "Uppar stats");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$sender instanceof Player) return;

        if (Server::getInstance()->getPluginManager()->getPlugin("Modos")) {
            $playerArmor = Main::getInstance()->getArmorManager()->getPlayerArmor($sender);
            if ($playerArmor->getCountArmors() > 0) {
                $sender->sendMessage(TextFormat::RED . "Voce nao pode executar este comando com um modo ativado.");
                return;
            }
        }

        $window = new SimpleWindowForm("stats_goog", "Stats", "");
        $window->addButton("hp", TextFormat::DARK_GRAY . "Aumente sua vida!\n(Clique para mais detalhes)");
        $window->addButton("force", TextFormat::DARK_GRAY . "Aumente seu dano!\n(Clique para mais detalhes)");
        //$window->addButton("shield", TextFormat::DARK_GRAY . "Aumente seu resistencia!\n(Clique para mais detalhes)");
        $window->addButton("aditional", TextFormat::DARK_GRAY . "Aumente seu chakra!\n(Clique para mais detalhes)");
        $window->showTo($sender);
    }

}