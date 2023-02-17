<?php

namespace Stats\Command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use Stats\GoogStats;

class ProtectCommand extends Command
{

    public function __construct()
    {
        parent::__construct("wpro", "World Protect with Stats");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$sender instanceof Player) return;

        if ($args[0] == "on") {
            GoogStats::getSessionManager()->getWorld($sender->getLevelNonNull())->setProtection(true);
            $sender->sendMessage(TextFormat::GREEN . "Voce ativou a protetion com o mundo");
        } else if ($args[0] == "off") {
            GoogStats::getSessionManager()->getWorld($sender->getLevelNonNull())->setProtection(false);
            $sender->sendMessage(TextFormat::RED . "Voce desativou a protetion com o mundo");
        } else {
            $sender->sendMessage(TextFormat::RED . "O comando nao existe");
        }
    }

}