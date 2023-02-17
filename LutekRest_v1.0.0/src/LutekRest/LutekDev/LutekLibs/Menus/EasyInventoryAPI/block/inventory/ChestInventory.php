<?php

namespace LutekRest\LutekDev\LutekLibs\Menus\EasyInventoryAPI\block\inventory;

use LutekRest\LutekDev\LutekRest;
use pocketmine\block\inventory\BlockInventory;
use pocketmine\block\inventory\BlockInventoryTrait;
use pocketmine\block\tile\Nameable;
use pocketmine\block\VanillaBlocks;
use pocketmine\inventory\SimpleInventory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\convert\RuntimeBlockMapping;
use pocketmine\network\mcpe\protocol\BlockActorDataPacket;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\player\Player;
use pocketmine\world\Position;

class ChestInventory extends SimpleInventory implements BlockInventory {
	use BlockInventoryTrait;
	public LutekRest $main;
	protected string $name = "Chest";
	protected bool $viewOnly = false;
	protected mixed $clickCallback = null;
	protected mixed $closeCallback = null;

	public function __construct(LutekRest $main, int $size = 27) {
		$this->main = $main;
		parent::__construct($size);
	}

	public function getName() : string {
		return $this->name;
	}

	public function setName(string $value) {
		$this->name = $value;
	}

	public function setViewOnly(bool $value = true) {
		$this->viewOnly = $value;
	}

	public function isViewOnly() : bool {
		return $this->viewOnly;
	}

	public function getClickCallback() {
		return $this->clickCallback;
	}

	public function setClickCallback(?callable $callable) {
		$this->clickCallback = $callable;
	}

	public function getCloseCallback() {
		return $this->closeCallback;
	}

	public function setCloseCallback(?callable $callable) {
		$this->closeCallback = $callable;
	}

	public function onClose(Player $player) : void {
		parent::onClose($player);
		// Real block
		$packet = UpdateBlockPacket::create(BlockPosition::fromVector3($this->holder), RuntimeBlockMapping::getInstance()->toRuntimeId($player->getWorld()->getBlock($this->holder)->getFullId()), UpdateBlockPacket::FLAG_NETWORK, UpdateBlockPacket::DATA_LAYER_NORMAL);
		$player->getNetworkSession()->sendDataPacket($packet);
		$closeCallback = $this->getCloseCallback();
		if ($closeCallback !== null) {
			$closeCallback($player, $this);
		}
	}

	public function send(Player $player) {
		// Set holder
		$this->holder = new Position((int) $player->getPosition()->getX(), (int) $player->getPosition()->getY() + 3, (int) $player->getPosition()->getZ(), $player->getWorld());

		// Fake block
		$packet = UpdateBlockPacket::create(BlockPosition::fromVector3($this->holder), RuntimeBlockMapping::getInstance()->toRuntimeId(VanillaBlocks::CHEST()->getFullId()), UpdateBlockPacket::FLAG_NETWORK, UpdateBlockPacket::DATA_LAYER_NORMAL);
		$player->getNetworkSession()->sendDataPacket($packet);

		// Fake tile
		$nbt = new CompoundTag();
		$nbt->setString(Nameable::TAG_CUSTOM_NAME, $this->getName());

		//
		$packet = BlockActorDataPacket::create(BlockPosition::fromVector3($this->holder), new CacheableNbt($nbt));
		$player->getNetworkSession()->sendDataPacket($packet);

		// Set current window
		$player->setCurrentWindow($this);
	}
}
