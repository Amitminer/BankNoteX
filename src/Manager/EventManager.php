<?php

namespace AmitxD\BankNote\Manager;

use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\Listener;
use AmitxD\BankNote\BankNote;
use pocketmine\player\Player;
use pocketmine\item\Item;
use AmitxD\BankNote\Manager\ConfigManager;
use AmitxD\BankNote\libs\davidglitch04\libEco\libEco;

class EventManager implements Listener
{

    public function __construct() {
        // NOOP
    }

    public function onPlayerItemUse(PlayerItemUseEvent $event): void {
        $player = $event->getPlayer();
        $item = $event->getItem();
        $BankNoteTag = ConfigManager::getConfigNestedValue('banknote-item.itemTag');
        //var_dump($BankNoteTag);
        $itemTag = $item->getNamedTag();

        if ($itemTag->getTag($BankNoteTag) === null) {
            return;
        }

        $amount = $itemTag->getInt('Amount');
        $this->depositNoteMoney($player, $item, $amount);
    }

    public function depositNoteMoney(Player $player, Item $item, int $amount): void {
        $count = $item->getCount();
        
        if(ConfigManager::getConfigNestedValue('tax.enabled') === true){
            $money = TaxManager::applyRedemptionTax($amount) * $count;
        }
        $money = $amount * $count;
        
        /** @phpstan-ignore-next-line */
        libEco::addMoney($player, $money);
        $item->setCount($count - $count);
        $percentage = $this->getTaxPercent();
        $player->getInventory()->setItemInHand($item);
        $redeemMessage = $this->getRedeemMSG($player, $money,$percentage);
        $player->sendMessage($redeemMessage);
    }

    private function getRedeemMSG($player, $money, $percentage): string {
        $redeemMessage = ConfigManager::getConfigNestedValue('messages.redeem-success');
        $redeemMessage = str_replace("{money}", "$" . number_format($money, 2), $redeemMessage);
        $message = str_replace("{percentage}", $percentage . "%", $redeemMessage);
        return $redeemMessage;
    }
    
    private function getTaxPercent(): float{
        $creationRate = TaxManager::getRedemptionRate();
        $percentage = $creationRate * 100;
        return $percentage;
    }

}