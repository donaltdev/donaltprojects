<?php

declare(strict_types=1);

namespace LutekRest\LutekDev\LutekUtils\Commands;

use pocketmine\command\CommandSender;

abstract class CommandSub {

    # Função para dar nome ao Comando;
    public abstract function getName() : string;

    # Função para dar a descrição do Comando;
    public abstract function getDescription() : string;

    # Função para chamar e configurar o Comando;
    public abstract function lutekExecSub(CommandSender $sender, string $label, array $args);
}