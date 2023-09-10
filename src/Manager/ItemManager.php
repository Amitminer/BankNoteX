<?php

declare(strict_types = 1);

namespace AmitxD\BankNote\Manager;

use AmitxD\BankNote\BankNote;
use AmitxD\BankNote\Utils\Utils;
use AmitxD\BankNote\Manager\ConfigManager;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\Item;
use InvalidArgumentException;

class ItemManager
{

    private function __construct() {
        //NOOP
    }

    public static function getNoteItem(string $playerName, int $amount, int $count = 1): Item
    {
        $itemId = ConfigManager::getConfigNestedValue('banknote-item.itemId');
        $itemName = ConfigManager::getConfigNestedValue('banknote-item.itemName');
        $itemTag = ConfigManager::getConfigNestedValue('banknote-item.itemTag');
        $itemLore = self::getItemLore($amount, $playerName);
        $item = Utils::stringToItem($itemId);
        $item->setCustomName($itemName);
        $item->setLore($itemLore);
        self::setEnchantment($item, Utils::ENCHANTMENT_ID);
        $item->getNamedTag()->setInt('Amount', intval($amount));
        $item->getNamedTag()->setString($itemTag, $itemTag);
        return $item->setCount($count);
    }

    private static function getItemLore(int $amount, string $playerName): array
    {
        $itemLore = ConfigManager::getConfigNestedValue('banknote-item.itemLore');

        foreach ($itemLore as &$line) {
            $line = str_replace('{Amount}', number_format($amount, 2), $line);
            $line = str_replace('{CREATOR}', $playerName, $line);
        }

        return $itemLore;
    }

    public static function setEnchantment(Item $item, int $enchantment, int $level = 1): Item {
        $enchant = EnchantmentIdMap::getInstance()->fromId($enchantment);

        if ($enchant === null) {
            throw new InvalidArgumentException("An error occurred while adding an enchantment to the item.");
        }
        $enchantmentInstance = new EnchantmentInstance($enchant, $level);
        $item->addEnchantment($enchantmentInstance);

        return $item;
    }
}