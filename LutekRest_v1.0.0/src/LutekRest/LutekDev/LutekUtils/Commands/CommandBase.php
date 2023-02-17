<?php

declare(strict_types=1);

namespace LutekRest\LutekDev\LutekUtils\Commands;

use pocketmine\command\Command as lutekCommandBase;
use pocketmine\command\CommandSender;

abstract class CommandBase extends lutekCommandBase {

    # Arquivo Base para puxar os comandos para os plug-ins;
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$this->testPermission($sender)) return false;

        return $this->lutekExec($sender, $commandLabel, $args);
    }

    // Função para ser chamada nos plug-ins;
    abstract public function lutekExec(CommandSender $sender, string $label, array $args);
}