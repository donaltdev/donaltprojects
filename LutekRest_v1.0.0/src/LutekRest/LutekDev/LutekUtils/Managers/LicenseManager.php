<?php

declare(strict_types=1);

namespace LutekRest\LutekDev\LutekUtils\Managers;

use LutekRest\LutekDev\LutekUtils\Configs\ConfigUtils;
use LutekRest\LutekDev\LutekUtils\Messages\LutekMessages;

class LicenseManager {
    # Função p/ Checar o Plugin da API
    public static function checkPlugin() : void {
        if (ConfigUtils::getConfig()->get("sua-licença") != LutekMessages::LICENSE || UtilsManager::getPlugin()->getDescription()->getVersion() != LutekMessages::VERSION) {
            UtilsManager::getPlugin()->getServer()->getLogger()->critical(LutekMessages::LutekLoggerError);
            UtilsManager::getPlugin()->getServer()->forceShutdown();
        } else {
            UtilsManager::getPlugin()->getServer()->getLogger()->info(LutekMessages::LutekLoggerSucess);
        }
    }
}