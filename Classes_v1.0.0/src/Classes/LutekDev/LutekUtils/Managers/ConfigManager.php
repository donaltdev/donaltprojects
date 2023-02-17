<?php

declare(strict_types=1);

namespace Classes\LutekDev\LutekUtils\Managers;

use pocketmine\utils\Config;

class ConfigManager {
	public static ?Config $player = null;
	public static ?Config $classes = null;
	public static ?Config $lang;

	public function __construct() {
		self::onCreateConfig();
	}

	#Creating configs.yml files;
	public function onCreateConfig() : void {
		#Criando pastas;
		@mkdir(UtilsManager::getPlugin()->getDataFolder() . "player");
		@mkdir(UtilsManager::getPlugin()->getDataFolder() . "lang");

		#Creating Player file;
		self::$player = new Config(UtilsManager::getPlugin()->getDataFolder() . "player/" . "players.yml", Config::YAML);

		#Creating Class file;
		UtilsManager::getPlugin()->saveResource("classes.yml");
		self::$classes = new Config(UtilsManager::getPlugin()->getDataFolder() . "classes.yml", Config::YAML);

		#Creating Langfile;
		self::$lang = new Config(UtilsManager::getPlugin()->getDataFolder() . "lang/" . "PT_BR.yml", Config::YAML, ["ERROR_CONSOLE" => "§cEsse comando só pode ser usado no jogo!", "SETCLASSE_COMMAND_ERROR" => "§7Digite /setclasse §e{jogador} {classe} §7p/ setar a classe em um jogador.", "PLAYER_OFFLINE" => "§cO jogador se encontra offline.", "CLASSE_NOT_EXISTS" => "§cA classe mencionada não existe.", "CLASSE_SET_SUCESS" => "§7Você setou a classe do jogador §e{player} §7para §e{classe}.", "JOINCLASSE_COMMAND_ERROR" => "§7Digite /classe §e{classe} §7p/ entrar em uma classe.", "JOINCLASSE_HASCLAN_ERROR" => "§cVocê já está em uma classe.", "JOINCLASSE_JOIN_SUCESS" => "§aParabens§7, você entrou na classe §e{classe}, §7com sucesso!"]);
	}
}