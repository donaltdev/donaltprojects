<?php

declare(strict_types=1);

namespace LutekRest\LutekDev\LutekUtils\Managers;

use LutekRest\LutekDev\LutekRest;
use pocketmine\network\mcpe\protocol\ToastRequestPacket;
use pocketmine\player\Player;

class UtilsManager {
    # Verifica se o Jogador é Operador;
    # Check if the Player is an Operator;
    public static function isPlayerOp(Player $player): bool {
        return $player->getServer()->isOp($player->getName());
    }

    # Verifica se o Jogador tem Permissão;
    # Check if the Player has Permission;
    public static function isPermOP(Player $player, string $permissao) : bool {
        return $player->hasPermission($permissao) || self::isPlayerOp($player);
    }

    # API Criada para enviar conquistas aos Jogadores;
    # API Created to send achievements to Players;
    public static function sendConquest(Player $player, string $title, string $body) : void {
        $player->getNetworkSession()->sendDataPacket(ToastRequestPacket::create($title, $body));
    }

    # API para criar Números Aleátorios;
    # API to create Random Numbers;
    public static function randomNumber() : string {
        $quantia = 4;
        $characters = '0123456789';
        $randomString = '';

        for ($i = 0; $i < $quantia; $i++) {
            $index = rand(0, strlen($characters));
            $randomString .= $characters[$index];
        }
        return $randomString;
    }

    # API para criar Códigos Aleatorios;
    # API to create Random Codes;
    public static function randomCode() : string {
        $quantia = 8;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $quantia; $i++) {
            $index = rand(0, strlen($characters));
            $randomString .= $characters[$index];
        }
        return $randomString;
    }

    # Função p/ pegar a instância do Plugin;
    public static function getPlugin() : LutekRest {
        return LutekRest::getInstance();
    }
}

/*
 *  💻 Credit's for some APIs: 💻
 *  - ClembArcade
 *  - Ad5001
 *  - andresbytes
 *  - muqsit
 *  - SOFe
 *  - CortexPE
 *  - AllahSupporter
 *  - LutekDev
 */