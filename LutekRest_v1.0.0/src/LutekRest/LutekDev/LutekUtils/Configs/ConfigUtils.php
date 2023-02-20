<?php

declare(strict_types=1);

namespace LutekRest\LutekDev\LutekUtils\Configs;

use LutekRest\LutekDev\LutekUtils\Managers\UtilsManager;
use LutekRest\LutekDev\LutekUtils\Messages\LutekMessages;
use pocketmine\utils\Config;

class ConfigUtils {
    public static Config $lutekConfig;
    public function __construct() {
        self::createConfig();
    }

    # Criando os arquivos Config para API
    public function createConfig() : void {
        self::$lutekConfig = new Config(UtilsManager::getPlugin()->getDataFolder() . "config.yml", Config::YAML, [
           "sua-licença" => LutekMessages::LICENSE,
           "versao-da-api" => UtilsManager::getPlugin()->getDescription()->getVersion()
        ]);
    }

    # Função p/ Retornar a variavel estatica da Config;
    public static function getConfig() : Config {
        return self::$lutekConfig;
    }
}