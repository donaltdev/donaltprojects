<?php

namespace airdrops\commands;

use airdrops\items\ids\CustomItemIds;
use airdrops\items\ItemFactory;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class AirdropCommand extends Command {

    public function __construct() {
        parent::__construct("airdrop", "Airdrops Plugin By: iJonyMx", null, ["adrps"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if ($sender instanceof Player) {
            if (empty($args[0])) {
                $sender->sendMessage(TextFormat::RED . "Usage: /airdrop help");
                return;
            }
            switch ($args[0]) {
                case "help":
                    $sender->sendMessage(TextFormat::colorize(
                        "&b/airdrop help: Help Commands" . TextFormat::EOL .
                        "&b/airdrop author: Author of the Plugin" . TextFormat::EOL .
                        "&b/airdrop give: Give airdrops to players"
                    ));
                    break;
                case "author":
                    $sender->sendMessage(TextFormat::colorize(
                        "&cAirdrops Plugin (Private)" . TextFormat::EOL .
                        "&cAuthor: iJonyMx" . TextFormat::EOL .
                        "&cDiscord: iJony#1571" . TextFormat::EOL .
                        "&cGithub: JonyGamesYT9"
                    ));
                    break;
                case "give":
                    if ($sender->hasPermission("airdrops.command.give")) {
                        if (empty($args[1]) or empty($args[2])) {
                            $sender->sendMessage(TextFormat::RED . "Usage: /airdrop give (player) (amount)");
                            return;
                        }
                        $target = Server::getInstance()->getPlayerByPrefix($args[1]);
                        if (is_null($target)) {
                            $sender->sendMessage(TextFormat::RED . "Player is not online.");
                            return;
                        }
                        if (!is_numeric($args[2])) {
                            $sender->sendMessage(TextFormat::RED . "Amount is not numeric.");
                            return;
                        }
                        $airdrop = ItemFactory::getInstance()->get(CustomItemIds::AIRDROP);
                        $item = $airdrop->getItem();
                        $item->setCount($args[2]);
                        $target->getInventory()->addItem($item);
                        $sender->sendMessage(TextFormat::GREEN . "You gived airdrops to: " . $target->getName() . " (" . $args[2] . ")");
                        $target->sendMessage(TextFormat::GREEN . "You received " . $args[2] . " airdrops. Good luck.");
                    } else {
                        $sender->sendMessage(TextFormat::RED . "You no have permissions to execute this command.");
                    }
                    break;
            }
        }
    }
}