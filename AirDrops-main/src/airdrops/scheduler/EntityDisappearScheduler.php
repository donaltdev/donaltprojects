<?php

namespace airdrops\scheduler;

use airdrops\AirDrops;
use airdrops\entity\EntityFactory;
use airdrops\entity\types\FinalNPC;
use airdrops\entity\types\NPC;
use JsonException;
use pocketmine\entity\Entity;
use pocketmine\entity\Skin;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\world\particle\ExplodeParticle;
use pocketmine\world\sound\AnvilFallSound;

class EntityDisappearScheduler extends Task {

    private NPC $npc;

    private int $time;

    public function __construct(NPC $npc) {
        $this->npc = $npc;
        $this->setTime(9);
    }

    public function getNpc(): NPC {
        return $this->npc;
    }

    public function getTime(): int {
        return $this->time;
    }

    public function setTime(int $time): void {
        $this->time = $time;
    }

    /**
     * @throws JsonException
     */
    public function onRun(): void {
        $npc = $this->getNpc();
        $npc->setNameTag(TextFormat::colorize("&l&6AirDrop"));
        $time = $this->getTime();
        if ($time === 1) {
            EntityFactory::getInstance()->eliminateByName($npc->getIdName());
            $dir = AirDrops::getInstance()->getDataFolder() . "models/" . "normal/" . "byshuy12.paracaidas" . ".png";
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
            $idName = "Chest-" . Entity::nextRuntimeId();
            EntityFactory::getInstance()->createChest($npc->getLocation(), $idName, new Skin($npc->getSkin()->getSkinId(), $skinbytes, "", "geometry.byshut12.paracaidas", file_get_contents(AirDrops::getInstance()->getDataFolder() . "models/" . "normal/" . "airdrop.cofre.shut12.geo" . ".json")));
            foreach ($npc->getPosition()->getWorld()->getEntities() as $entity) {
                if ($entity instanceof FinalNPC) {
                    if ($entity->getIdName() === $idName) {
                        $position = $entity->getPosition();
                        $world = $position->getWorld();
                        $world->addParticle($position->asVector3(), new ExplodeParticle());
                        $world->addParticle($position->asVector3(), new ExplodeParticle());
                        $world->addParticle($position->add(1, 0, 0)->asVector3(), new ExplodeParticle());
                        $world->addParticle($position->add(0, 1, 0)->asVector3(), new ExplodeParticle());
                        $world->addParticle($position->add(0, 0, 1)->asVector3(), new ExplodeParticle());
                        $world->addSound($position->asVector3(), new AnvilFallSound());
                    }
                }
            }
            $this->setTime($time - 1);
            $npc->setHasGravity();
        } else if ($time === 0) {
            $this->getHandler()->cancel();
        } else {
            $npc->setHasGravity(false);
            $motion = $npc->getMotion()->subtract(0, (2 / 20 - $npc->getMotion()->y) / 5, 0);
            $npc->setMotion($motion);
            $this->setTime($time - 1);
        }
    }
}