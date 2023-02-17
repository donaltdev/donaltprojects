<?php

namespace Stats\Events;

use onebone\economyapi\EconomyAPI;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use Stats\Form\response\PlayerWindowResponse;
use Stats\Form\SimpleWindowForm;
use Stats\GoogStats;

class FormListener implements Listener
{

    public function onResponse(PlayerWindowResponse $event): void
    {
        $player = $event->getPlayer();
        $form = $event->getForm();

        if (!$form instanceof SimpleWindowForm) return;

        $session = GoogStats::getSessionManager()->getSession($player);
        if (!$session) return;

        if ($form->getName() === "stats_goog") {

            if ($form->getClickedButton()->getName() === "hp") {
                $price = $session->getLastEvent("hp") + 5;

                $window = new SimpleWindowForm("hp_goog", "Aumento da vida", "Preco por uppar: " . TextFormat::GOLD . $price . " tp");
                $window->addButton("1", TextFormat::DARK_GRAY . "Quantidade: 1 \n (Preco: " . TextFormat::GOLD . $price * 1 . TextFormat::DARK_GRAY . ")");
                $window->addButton("10", TextFormat::DARK_GRAY . "Quantidade: 10 \n (Preco: " . TextFormat::GOLD . $price * 10 . TextFormat::DARK_GRAY . ")");
                $window->addButton("100", TextFormat::DARK_GRAY . "Quantidade: 100 \n (Preco: " . TextFormat::GOLD . $price * 100 . TextFormat::DARK_GRAY . ")");
                $window->addButton("1000", TextFormat::DARK_GRAY . "Quantidade: 1000 \n (Preco: " . TextFormat::GOLD . $price * 1000 . TextFormat::DARK_GRAY . ")");
                $window->showTo($player);
            } else if ($form->getClickedButton()->getName() === "force") {
                $price = $session->getLastEvent("force") + 5;

                $window = new SimpleWindowForm("force_goog", "Aumento do dano!", "Preço por upgrade: " . TextFormat::GOLD . $price . " tp");
                $window->addButton("1", TextFormat::DARK_GRAY . "Quantidade: 1 \n (Preco: " . TextFormat::GOLD . $price * 1 . TextFormat::DARK_GRAY . ")");
                $window->addButton("10", TextFormat::DARK_GRAY . "Quantidade: 10 \n (Preco: " . TextFormat::GOLD . $price * 10 . TextFormat::DARK_GRAY . ")");
                $window->addButton("100", TextFormat::DARK_GRAY . "Quantidade: 100 \n (Preco: " . TextFormat::GOLD . $price * 100 . TextFormat::DARK_GRAY . ")");
                $window->addButton("1000", TextFormat::DARK_GRAY . "Quantidade: 1000 \n (Preco: " . TextFormat::GOLD . $price * 1000 . TextFormat::DARK_GRAY . ")");
                $window->showTo($player);
            } else if ($form->getClickedButton()->getName() === "shield") {
                $price = $session->getLastEvent("shield") + 5;

                $window = new SimpleWindowForm("shield_goog", "Aumento do resistencia!", "Preço por upgrade: " . TextFormat::GOLD . $price . " tp");
                $window->addButton("1", TextFormat::DARK_GRAY . "Quantidade: 1 \n (Preco: " . TextFormat::GOLD . $price * 1 . TextFormat::DARK_GRAY . ")");
                $window->addButton("10", TextFormat::DARK_GRAY . "Quantidade: 10 \n (Preco: " . TextFormat::GOLD . $price * 10 . TextFormat::DARK_GRAY . ")");
                $window->addButton("100", TextFormat::DARK_GRAY . "Quantidade: 100 \n (Preco: " . TextFormat::GOLD . $price * 100 . TextFormat::DARK_GRAY . ")");
                $window->addButton("1000", TextFormat::DARK_GRAY . "Quantidade: 1000 \n (Preco: " . TextFormat::GOLD . $price * 1000 . TextFormat::DARK_GRAY . ")");
                $window->showTo($player);
            } else {
                $price = $session->getLastEvent("aditional") + 10;

                $window = new SimpleWindowForm("aditional_goog", "Aumento do chakra!", "Preço por upgrade: " . TextFormat::GOLD . $price . " tp");
                $window->addButton("1", TextFormat::DARK_GRAY . "Quantidade: 1 \n (Preco: " . TextFormat::GOLD . $price * 1 . TextFormat::DARK_GRAY . ")");
                $window->addButton("10", TextFormat::DARK_GRAY . "Quantidade: 10 \n (Preco: " . TextFormat::GOLD . $price * 10 . TextFormat::DARK_GRAY . ")");
                $window->addButton("100", TextFormat::DARK_GRAY . "Quantidade: 100 \n (Preco: " . TextFormat::GOLD . $price * 100 . TextFormat::DARK_GRAY . ")");
                $window->addButton("1000", TextFormat::DARK_GRAY . "Quantidade: 1000 \n (Preco: " . TextFormat::GOLD . $price * 1000 . TextFormat::DARK_GRAY . ")");
                $window->showTo($player);
            }
            return;
        }

        if ($form->getName() === "hp_goog") {
            $session = GoogStats::getSessionManager()->getSession($player);
            if (!$session) return;

            $lastEvent = $session->getLastEvent("hp");
            $upgrade = ($lastEvent + 5) * intval($form->getClickedButton()->getName());

            if (EconomyAPI::getInstance()->myMoney($player) < $upgrade) {
                $player->sendMessage(TextFormat::RED . "Voce nao tem suficiente tp!");
                return;
            }

            EconomyAPI::getInstance()->reduceMoney($player, $upgrade);
            $session->getHp()->setDefault(intval($form->getClickedButton()->getName()) + $session->getHp()->getDefault());
            $session->getHp()->setTemporal(intval($form->getClickedButton()->getName()) + $session->getHp()->getTemporal());
            $session->setLastEvent("hp", (intval($form->getClickedButton()->getName()) * 0.1) + $lastEvent);

            $player->sendMessage(TextFormat::GREEN . "Voce comprou " . intval($form->getClickedButton()->getName()) . " do hp com um costo do " . $upgrade);
            return;
        }

        if ($form->getName() === "force_goog") {
            $session = GoogStats::getSessionManager()->getSession($player);
            if (!$session) return;

            $lastEvent = $session->getLastEvent("force");
            $upgrade = ($lastEvent + 5) * intval($form->getClickedButton()->getName());

            if (EconomyAPI::getInstance()->myMoney($player) < $upgrade) {
                $player->sendMessage(TextFormat::RED . "Voce nao tem suficiente tp!");
                return;
            }

            EconomyAPI::getInstance()->reduceMoney($player, $upgrade);
            $session->getForce()->setDefault(intval($form->getClickedButton()->getName()) + $session->getForce()->getDefault());
            $session->getForce()->setTemporal(intval($form->getClickedButton()->getName()) + $session->getForce()->getTemporal());
            $session->setLastEvent("force", (intval($form->getClickedButton()->getName()) * 0.1) + $lastEvent);

            $player->sendMessage(TextFormat::GREEN . "Voce comprou " . intval($form->getClickedButton()->getName()) . " do dano com um costo do " . $upgrade);
            return;
        }

        if ($form->getName() === "shield_goog") {
            $session = GoogStats::getSessionManager()->getSession($player);
            if (!$session) return;
            $lastEvent = $session->getLastEvent("shield");
            $upgrade = ($lastEvent + 5) * intval($form->getClickedButton()->getName());


            if (EconomyAPI::getInstance()->myMoney($player) < $upgrade) {
                $player->sendMessage(TextFormat::RED . "Voce nao tem suficiente tp!");
                return;
            }

            EconomyAPI::getInstance()->reduceMoney($player, $upgrade);
            $session->getShield()->setDefault(intval($form->getClickedButton()->getName()) + $session->getShield()->getDefault());
            $session->getShield()->setTemporal(intval($form->getClickedButton()->getName()) + $session->getShield()->getTemporal());
            $session->setLastEvent("shield", (intval($form->getClickedButton()->getName()) * 0.1) + $lastEvent);

            $player->sendMessage(TextFormat::GREEN . "Voce comprou " . intval($form->getClickedButton()->getName()) . " do resistencia com um costo do " . $upgrade);
            return;
        }

        if ($form->getName() === "aditional_goog") {
            $session = GoogStats::getSessionManager()->getSession($player);
            if (!$session) return;
            $lastEvent = $session->getLastEvent("aditional");
            $upgrade = ($lastEvent + 10) * intval($form->getClickedButton()->getName());

            if (EconomyAPI::getInstance()->myMoney($player) < $upgrade) {
                $player->sendMessage(TextFormat::RED . "Voce nao tem suficiente tp!");
                return;
            }

            EconomyAPI::getInstance()->reduceMoney($player, $upgrade);
            $session->getAditional()->setDefault(intval($form->getClickedButton()->getName()) + $session->getAditional()->getDefault());
            $session->getAditional()->setTemporal(intval($form->getClickedButton()->getName()) + $session->getAditional()->getTemporal());
            $session->setLastEvent("aditional", (intval($form->getClickedButton()->getName()) * 0.1) + $lastEvent);

            $player->sendMessage(TextFormat::GREEN . "Voce comprou " . intval($form->getClickedButton()->getName()) . " do chakra com um costo do " . $upgrade);
            return;
        }

    }

}