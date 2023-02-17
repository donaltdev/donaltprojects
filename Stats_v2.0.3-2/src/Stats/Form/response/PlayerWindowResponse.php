<?php

namespace Stats\Form\response;

use Stats\Form\SimpleWindowForm;
use Stats\Form\WindowForm;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;

class PlayerWindowResponse extends PluginEvent
{

    /** @var Player */
    private $player;

    /** @var WindowForm */
    private $form;

    public function __construct(Player $player, $data, WindowForm $form)
    {
        $this->player = $player;
        $this->form = $form;

        if($form instanceof SimpleWindowForm) $form->response = $data;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return WindowForm
     */
    public function getForm(): WindowForm
    {
        return $this->form;
    }
}