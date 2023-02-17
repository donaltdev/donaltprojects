<?php


namespace stats\entities\views;


use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use stats\entities\StatsPlayer;
use stats\Loader;

class UpgradeView extends BaseView
{

	const DAMAGE_TYPE = "damage";
	const HEALTH_TYPE = "health";
	const DEFENSE_TYPE = "defense";
	const SUPER_TYPE = "superpower";

	private $type;
	private $content;

	public function __construct(string $type, string $content = null)
	{
		$this->type = $type;
		$this->content = $content;
	}

	/**
	 * @return string|null
	 */
	public function getContent(): ?string
	{
		return $this->content;
	}

	/**
	 * @param Player $player
	 * @return int
	 */
	private function getCountUpgrade(Player $player): int
	{
		/** @var StatsPlayer $player */
		$stats = $player->getStats();
		switch ($this->type) {
			case self::DAMAGE_TYPE:
				return $stats->getCountUpgradeDmg();
				break;
			case self::HEALTH_TYPE:
				return $stats->getCountUpgradeMh();
				break;
			case self::DEFENSE_TYPE:
				return $stats->getCountUpgradeDef();
				break;
			case self::SUPER_TYPE:
				return $stats->getCountUpgradeSp();
				break;
			default:
				return 0;
				break;
		}
	}

	/**
	 * @param Player $player
	 * @return int
	 */
	private function getOriginalValue(Player $player): int
	{
		/** @var StatsPlayer $player */
		$stats = $player->getStats();
		switch ($this->type) {
			case self::DAMAGE_TYPE:
				return $stats->getDamage(true);
				break;
			case self::HEALTH_TYPE:
				return $stats->getMaxHealth(true);
				break;
			case self::DEFENSE_TYPE:
				return $stats->getDefense(true);
				break;
			case self::SUPER_TYPE:
				return $stats->getSuperPower(true);
				break;
			default:
				return 0;
				break;
		}
	}

	/**
	 * @param Player $player
	 * @return bool
	 */
	private function inMax(Player $player): bool
	{
		$configMan = Loader::getInstance()->getConfigManager();
		return $this->getOriginalValue($player) >= $configMan->getValue("max_" . $this->type);
	}

	/**
	 * @param Player $player
	 * @param int $value
	 * @return bool
	 */
	private function canUpgrade(Player $player, int $value): bool
	{
		$configMan = Loader::getInstance()->getConfigManager();
		return ($this->getOriginalValue($player) + $value) <= $configMan->getValue("max_" . $this->type);
	}

	private function getTitle(){
		switch ($this->type) {
			case self::DAMAGE_TYPE:
				return "do dano!";
				break;
			case self::HEALTH_TYPE:
				return "da vida máxima!";
				break;
			case self::DEFENSE_TYPE:
				return "da defesa!";
				break;
			case self::SUPER_TYPE:
				return "do super poder!";
				break;
			default:
				return "indefinido";
				break;
		}
	}

	public function createForm(Player $player)
	{
		/** @var StatsPlayer $player */
		$configMan = Loader::getInstance()->getConfigManager();
		$multiply = $this->getCountUpgrade($player);
		$multiply = ($multiply * 0.1) < 0 ? 1 : ((int)$multiply * 0.1);
		$price = ($configMan->getValue("price_upgrade") ?? 5) + $multiply;
		$options = [
			"0" => 1,
			"1" => 10,
			"2" => 100,
			"3" => 1000
		];
		$buttons = 0;
		$form = new SimpleForm(function (Player $player, $data) use ($configMan, $price, $options) {
			/** @var StatsPlayer $player */
			if (!is_null($data) && is_numeric($data)) {
				if ($this->inMax($player)) {
					$player->sendMessage("§eVocê chegou no máximo  para esse tipo de upgrade.");
				} else if (isset($options["{$data}"])) {
					$count = $options["{$data}"];
					$nowPrice = ($count * $price);
					$playerMoney = Loader::getInstance()->getEconomy()->myMoney($player);
					$view = null;
					if ($nowPrice > $playerMoney) {
						$view = new UpgradeView($this->type,"§cVocê não tem tp suficiente para a compra dos upgrades.\n");
					} else {
						if (Loader::getInstance()->getEconomy()->reduceMoney($player, $nowPrice) == 1) {
							$player->getStats()->addUpgrade($count, $this->type);
							$view = new UpgradeView($this->type,"§aVocê comprou com sucesso {$count} upgrades!\n");
						} else {
							$view = new UpgradeView($this->type,"§cOcorreu um erro na compra dos upgrades. Tente novamente!\n");
						}
					}
					if (!is_null($view)) {
						$view->sendForm($player);
					}
				}
			}
		});
		$form->setTitle("Aumento " . $this->getTitle());
		$form->setContent(($this->getContent() ?? "") . "§fPreço por upgrade: §6" . $price . " tp§f.");
		foreach ($options as $key => $value) {
			if ($this->canUpgrade($player, $value)) {
				$form->addButton("Quantidade: " . $value . "\n§8(Preço: §6" . ($value * $price) . "tp§8)");
				$buttons++;
			}
		}

		if ($buttons == 0) {
			$form->addButton("Você excedeu a quantidade de upgrades!");
		}

		$this->form = $form;
	}

}