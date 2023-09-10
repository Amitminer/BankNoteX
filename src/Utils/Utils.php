<?php

declare(strict_types = 1);

namespace AmitxD\BankNote\Utils;

use pocketmine\player\Player;
use pocketmine\item\Item;
use pocketmine\item\enchantment\StringToEnchantmentParser;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\StringToItemParser;
use AmitxD\CustomItem\Utils\EnchantmentIds;

class Utils {

    public const ENCHANTMENT_ID = 155;

    public static function stringToItem(string $input): ?Item {
        $string = strtolower(str_replace([' ', 'minecraft:'], ['_', ''], trim($input)));
        $item = StringToItemParser::getInstance()->parse($string);
        return $item;
    }

    public static function createEnchantment(string $name, int $id): void {
        if ($name !== '') {
            EnchantmentIdMap::getInstance()->register($id, new Enchantment(strtolower($name), 1, ItemFlags::ALL, ItemFlags::NONE, 1));
        }
    }
}