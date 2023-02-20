<?php

namespace airdrops;

use airdrops\commands\AirdropCommand;
use airdrops\entity\EntityFactory;
use airdrops\entity\types\FinalNPC;
use airdrops\items\ItemFactory;
use airdrops\utils\SaveData;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\InvMenuEventHandler;
use muqsit\invmenu\InvMenuHandler;
use muqsit\invmenu\type\InvMenuTypeRegistry;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;

class AirDrops extends PluginBase {
    use SingletonTrait;

    protected function onLoad(): void {
        self::setInstance($this);
    }

    protected function onEnable(): void {
        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }
        SaveData::getInstance()->start();
        EntityFactory::getInstance()->start();
        ItemFactory::getInstance()->start();
        Server::getInstance()->getCommandMap()->register("airdrop", new AirdropCommand());
        Server::getInstance()->getPluginManager()->registerEvents(new EventsListener(), $this);
    }

    protected function onDisable(): void {
        foreach (Server::getInstance()->getWorldManager()->getWorlds() as $world) {
            foreach ($world->getEntities() as $entity) {
                if ($entity instanceof FinalNPC) {
                    EntityFactory::getInstance()->eliminateByName($entity->getIdName());
                }
            }
        }
    }

    public function replaySong(Player $player, string $sound, int $volume = 10, int $pitch = 1): void {
        $packet = new PlaySoundPacket();
        $packet->x = $player->getPosition()->getX();
        $packet->y = $player->getPosition()->getY();
        $packet->z = $player->getPosition()->getZ();
        $packet->soundName = $sound;
        $packet->volume = $volume;
        $packet->pitch = $pitch;
        $player->getNetworkSession()->sendDataPacket($packet);
    }
}