<?php

declare(strict_types = 1);

namespace AmitxD\BankNote\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use AmitxD\BankNote\libs\davidglitch04\libEco\libEco;
use AmitxD\BankNote\Manager\ConfigManager;
use AmitxD\BankNote\Manager\ItemManager;
use AmitxD\BankNote\Manager\TaxManager;

class BankNoteCommand extends Command
{

    public const PERMISSION = 'banknote.command.use';

    public function __construct() {
        parent::__construct(
            ConfigManager::getConfigNestedValue('command.name'),
            ConfigManager::getConfigNestedValue('command.description'),
            ConfigManager::getConfigNestedValue('command.usage'),
            ConfigManager::getConfigNestedValue('command.aliases')
        );

        $this->setPermission(self::PERMISSION);
    }

    public function execute(CommandSender $player, string $label, array $args): void
    {
        if (!$player instanceof Player) {
            $player->sendMessage($this->getConfig('messages.no-console'));
            return;
        }

        if (count($args) < 1) {
            $player->sendMessage($this->getUsage());
            return;
        }
        
        $maxValue = 2000000000;
        if (!is_numeric($args[0]) || !ctype_digit($args[0]) || $args[0] > $maxValue) {
            $player->sendMessage($this->getConfig('messages.invalid-amount'));
            return;
        }

        $amount = (int) $args[0];
        $count = isset($args[1]) ? (int) $args[1] : 1;
        if(ConfigManager::getConfigNestedValue('tax.enabled') === true){
            $money = TaxManager::applyRedemptionTax($amount) * $count;
        }
        $money = $amount * $count;

        /** @phpstan-ignore-next-line */
        libEco::reduceMoney($player, $money, function (bool $success) use ($player, $amount, $count, $money): void {
            if ($success) {
                $this->onSuccess($player, $amount, $count, $money);
            } else {
                $this->onFailure($player);
            }
        });
    }

    private function onSuccess(Player $player,
        int $amount,
        int $count,
        int $money): void
    {
        $item = ItemManager::getNoteItem($player->getName(),
            $amount,
            $count);
        //var_dump($item);

        if ($player->getInventory()->canAddItem($item)) {
            $player->getInventory()->addItem($item);

            $percentage = $this->getTaxPercent();
            $message = $this->getPurchaseMessage($count, $money, $percentage);
            $player->sendMessage($message);
        } else {
            $invFullMessage = $this->getConfig("messages.inv-full");
            $player->sendMessage($invFullMessage);
        }
    }

    private function onFailure(Player $player): void
    {
        $player->sendMessage($this->getConfig("messages.no-money"));
    }

    private function getTaxPercent(): float {
        $creationRate = TaxManager::getCreationRate();
        $percentage = $creationRate * 100;
        return $percentage;
    }

    private function getPurchaseMessage(int $count, int $money, float $percentage): string
    {
        $message = $this->getConfig("messages.purchased");
        $message = str_replace("{count}", (string)$count, $message);
        $message = str_replace("{amount}", "$" . number_format($money, 2), $message);
        $message = str_replace("{percentage}", $percentage . "%", $message);
        return $message;
    }

    private function getConfig(string $value): string
    {
        return ConfigManager::getConfigNestedValue($value);
    }
}