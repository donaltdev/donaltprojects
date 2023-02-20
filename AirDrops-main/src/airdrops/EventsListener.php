<?php

namespace airdrops;

use airdrops\entity\EntityFactory;
use airdrops\entity\types\FinalNPC;
use airdrops\entity\types\NPC;
use airdrops\forms\ResultInventory;
use airdrops\items\ids\CustomItemIds;
use airdrops\items\ItemFactory;
use airdrops\scheduler\items\antibuild\AntiTrappedScheduler;
use airdrops\scheduler\items\antibuild\AntiTrapperScheduler;
use airdrops\scheduler\items\antimushroom\AntiMushroomPScheduler;
use airdrops\scheduler\items\zap\ZapedScheduler;
use airdrops\scheduler\items\zap\ZapScheduler;
use airdrops\sessions\Session;
use airdrops\sessions\SessionFactory;
use JsonException;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\Entity;
use pocketmine\entity\Location;
use pocketmine\entity\Skin;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\PotionType;
use pocketmine\item\VanillaItems;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\types\entity\PropertySyncData;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\world\particle\BlockBreakParticle;

class EventsListener implements Listener {

    public function playerSession(PlayerPreLoginEvent $event): void {
        $username = $event->getPlayerInfo();
        SessionFactory::getInstance()->add(new Session($username->getUsername()));
    }

    public function playerQuit(PlayerQuitEvent $event): void {
        $player = $event->getPlayer();
        SessionFactory::getInstance()->delete($player->getName());
    }

    public function efjndiugvndwof(EntityDamageEvent $event): void {
        $npc = $event->getEntity();
        if ($npc instanceof NPC or $npc instanceof FinalNPC) {
            $event->cancel();
        }
    }

    public function jonfubiuhvrf(PlayerInteractEvent $event): void {
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        $antiMushroom = ItemFactory::getInstance()->get(CustomItemIds::ANTI_MUSHROOM);
        if ($item->getId() === $antiMushroom->getItem()->getId()) {
            if ($item->getName() === $antiMushroom->getItemFormat()) {
                    $session = SessionFactory::getInstance()->get($player->getName());
                    if ($session->getMushroom("used")) {
                        $player->sendMessage(TextFormat::RED . "You already have a cooldown of this ability.");
                        return;
                    }
                    if ($session->getMushroom("tag")) {
                        $player->sendMessage(TextFormat::RED . "You already have a tag of this ability.");
                        return;
                    }
                    if ($item->getCount() < 1) {
                        $player->getInventory()->setItemInHand(VanillaItems::AIR());
                    } else {
                        $player->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
                    }
                    $session->setMushroom("used", true);
                    $session->setMushroom("tag", true);
                    $player->sendMessage(TextFormat::GREEN . "You have successfully used your AntiMushroom ability.");
                    AirDrops::getInstance()->getScheduler()->scheduleRepeatingTask(new AntiMushroomPScheduler($player), 20);
                }
        }
    }

    public function dniuvg8y9hgfer(EntityDamageEvent $event): void {
        $player = $event->getEntity();
        if ($player instanceof Player) {
            if ($event->getCause() === EntityDamageEvent::CAUSE_FALL) {
                $session = SessionFactory::getInstance()->get($player->getName());
                if ($session->getMushroom("tag")) {
                    $event->cancel();
                }
            }
        }
    }

    public function attackByZap(EntityDamageByEntityEvent $event): void {
        $player = $event->getEntity();
        if ($player instanceof Player) {
            $damager = $event->getDamager();
            if ($damager instanceof Player) {
                $item = $damager->getInventory()->getItemInHand();
                $antiBuild = ItemFactory::getInstance()->get(CustomItemIds::ZAP);
                if ($item->getId() === $antiBuild->getItem()->getId()) {
                    if ($item->getName() === $antiBuild->getItem()->getCustomName()) {
                            $session = SessionFactory::getInstance()->get($player->getName());
                            $sessionDamager = SessionFactory::getInstance()->get($damager->getName());
                            if ($sessionDamager->getZap("used")) {
                                $damager->sendMessage(TextFormat::RED . "You already have a tag of this ability.");
                                return;
                            }
                            if ($session->getZap("tag")) {
                                $damager->sendMessage(TextFormat::RED . "This player already has an zap mark.");
                                return;
                            }
                            if ($item->getCount() < 1) {
                                $player->getInventory()->setItemInHand(VanillaItems::AIR());
                            } else {
                                $player->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
                            }
                            $session->setZap("tag", true);
                            $sessionDamager->setZap("used", true);
                            $position = $player->getPosition();
                            $packet = new AddActorPacket();
                            $packet->actorUniqueId = Entity::nextRuntimeId();
                            $packet->actorRuntimeId = 1;
                            $packet->position = $position->asVector3();
                            $packet->type = "minecraft:lightning_bolt";
                            $packet->yaw = $player->getLocation()->getYaw();
                            $packet->syncedProperties = new PropertySyncData([], []);
                            $block = $player->getWorld()->getBlock($player->getPosition()->floor()->down());
                            $particle = new BlockBreakParticle($block);
                            $player->getWorld()->addParticle($position, $particle, $player->getWorld()->getPlayers());
                            Server::getInstance()->broadcastPackets($player->getWorld()->getPlayers(), [$packet]);
                            foreach ([$player, $damager] as $item) {
                                AirDrops::getInstance()->replaySong($item, "ambient.weather.thunder");
                            }
                            $player->setOnFire(5);
                            $player->getEffects()->add(new EffectInstance(VanillaEffects::POISON(), 10, 1, true));
                            $damager->sendMessage(TextFormat::GREEN . "You have successfully used your Zap ability.");
                            $player->sendMessage(TextFormat::RED . "You got the Zap effect, now you can't build!");
                            AirDrops::getInstance()->getScheduler()->scheduleRepeatingTask(new ZapedScheduler($player), 20);
                            AirDrops::getInstance()->getScheduler()->scheduleRepeatingTask(new ZapScheduler($damager), 20);
                        }
                }
            }
        }
    }

    public function attackByAntibuild(EntityDamageByEntityEvent $event): void {
        $player = $event->getEntity();
        if ($player instanceof Player) {
            $damager = $event->getDamager();
            if ($damager instanceof Player) {
                $item = $damager->getInventory()->getItemInHand();
                $antiBuild = ItemFactory::getInstance()->get(CustomItemIds::ANTI_BUILD);
                if ($item->getId() === $antiBuild->getItem()->getId()) {
                    if ($item->getName() === $antiBuild->getItem()->getCustomName()) {
                            $session = SessionFactory::getInstance()->get($player->getName());
                            $sessionDamager = SessionFactory::getInstance()->get($damager->getName());
                            if ($sessionDamager->getAntibuild("used")) {
                                $damager->sendMessage(TextFormat::RED . "You already have a tag of this ability.");
                                return;
                            }
                            if ($session->getAntibuild("tag")) {
                                $damager->sendMessage(TextFormat::RED . "This player already has an antibuild mark.");
                                return;
                            }
                            $damager->getInventory()->setItemInHand(VanillaItems::AIR());
                            $session->setAntibuild("tag", true);
                            $sessionDamager->setAntibuild("used", true);
                            $damager->sendMessage(TextFormat::GREEN . "You have successfully used your AntiBuild ability.");
                            $player->sendMessage(TextFormat::RED . "You got the anti trapper effect, now you can't build!");
                            AirDrops::getInstance()->getScheduler()->scheduleRepeatingTask(new AntiTrappedScheduler($player), 20);
                            AirDrops::getInstance()->getScheduler()->scheduleRepeatingTask(new AntiTrapperScheduler($damager), 20);
                        }
                }
            }
        }
    }

    public function place(BlockPlaceEvent $event): void {
        $player = $event->getPlayer();
        $session = SessionFactory::getInstance()->get($player->getName());
        if ($session->getAntibuild("tag")) {
            $player->sendMessage(TextFormat::RED . "You cannot build having a brand.");
            $event->cancel();
        }
    }

    public function break(BlockBreakEvent $event): void {
        $player = $event->getPlayer();
        $session = SessionFactory::getInstance()->get($player->getName());
        if ($session->getAntibuild("tag")) {
            $player->sendMessage(TextFormat::RED . "You cannot build having a brand.");
            $event->cancel();
        }
    }

    /**
     * @throws JsonException
     */
    public function useAirdrop(BlockPlaceEvent $event): void {
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        $airdrop = ItemFactory::getInstance()->get(CustomItemIds::AIRDROP);
        $block = $event->getBlockReplaced();
        if ($item->getName() == $airdrop->getItemFormat()) {
            if (!is_null($item->getNamedTag()->getString("Airdrop")) and $item->getNamedTag()->getString("Airdrop", "false") === "true") {
                if ($item->getId() === $airdrop->getItem()->getId()) {
                    $event->cancel();
                    if ($item->getCount() > 1) {
                        $player->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
                    } else {
                        $player->getInventory()->setItemInHand(VanillaItems::AIR());
                    }
                    $blockPos = $block->getPosition();
                    $location = new Location($blockPos->getX() + 0.5, $blockPos->getY() + 7.5, $blockPos->getZ() + 0.5, $blockPos->getWorld(), $player->getLocation()->getYaw() - 10.0, $player->getLocation()->getPitch());
                    $blockPos->getWorld()->setBlock($blockPos->subtract(0, 1, 0)->asVector3(), VanillaBlocks::WOOL()->setColor(DyeColor::RED()));
                    $blockPos->getWorld()->setBlock($blockPos->asVector3(), VanillaBlocks::AIR());
                    $dir = AirDrops::getInstance()->getDataFolder() . "models/" . "falling/" . "byshuy12.paracaidas" . ".png";
                    $img = @imagecreatefrompng($dir);
                    $size = getimagesize($dir);
                    $skinbytes = "";
                    for ($y = 0; $y < $size[1]; $y++) {
                        for ($x = 0; $x < $size[0]; $x++) {
                            $colorat = @imagecolorat($img, $x, $y);
                            $a = ((~((int)($colorat >> 24))) << 1) & 0xff;
                            $r = ($colorat >> 16) & 0xff;
                            $g = ($colorat >> 8) & 0xff;
                            $b = $colorat & 0xff;
                            $skinbytes .= chr($r) . chr($g) . chr($b) . chr($a);
                        }
                    }
                    @imagedestroy($img);
                    EntityFactory::getInstance()->create($location, "Airdrop-" . Entity::nextRuntimeId(), new Skin($player->getSkin()->getSkinId(), $skinbytes, "", "geometry.byshut12.paracaidas", file_get_contents(AirDrops::getInstance()->getDataFolder() . "models/" . "falling/" . "airdrop.paracaidas.shut12.geo" . ".json")));
                    $player->sendTitle(TextFormat::GREEN . TextFormat::BOLD . "is coming");
                }
            }
        }
    }

    public function onTouch(EntityDamageByEntityEvent $event): void {
        $npc = $event->getEntity();
        $damager = $event->getDamager();
        if ($damager instanceof Player) {
            if ($npc instanceof NPC) {
                $event->cancel();
                if ($damager->hasPermission("airdrops.admin")) {
                    EntityFactory::getInstance()->eliminateByName($npc->getIdName());
                    $airdrop = ItemFactory::getInstance()->get(CustomItemIds::AIRDROP);
                    $item = $airdrop->getItem();
                    $item->setCustomName($airdrop->getItemFormat());
                    $item->setCount(1);
                    $item->getNamedTag()->setString("Airdrop", "true");
                    $damager->getInventory()->addItem($item);
                }
            } else if ($npc instanceof FinalNPC) {
                $event->cancel();
                if ($npc->getNameTag() === TextFormat::colorize("&l&6AirDrop &r&a(Opened)")) {
                    return;
                }
                $session = SessionFactory::getInstance()->get($damager->getName());
                if ($session->isAir()) {
                    $damager->sendMessage(TextFormat::RED . "Wait for the other airdrop to finish opening.");
                    return;
                }
                ResultInventory::getInstance()->send($damager, $npc);
                $session->setAir(true);
                AirDrops::getInstance()->replaySong($damager, "random.explode");
            }
        }
    }

}