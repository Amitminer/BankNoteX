<?php

declare(strict_types = 1);

namespace AmitxD\BankNote\libs\davidglitch04\libEco;

use Closure;
use cooldogedev\BedrockEconomy\api\legacy\ClosureContext;
use cooldogedev\BedrockEconomy\api\version\BetaBEAPI;
use pocketmine\promise\Promise;
use pocketmine\promise\PromiseResolver;
use onebone\economyapi\EconomyAPI;
use pocketmine\player\Player;
use pocketmine\Server as PMServer;

class libEco
{
    public const ECONOMYAPI = "EconomyAPI";
	
	public const BEDROCKECONOMYAPI = "BedrockEconomyAPI";
	
    private static function getEconomy(): array
    {
        $api = PMServer::getInstance()->getPluginManager()->getPlugin('EconomyAPI');
        if ($api !== null) {
            return [self::ECONOMYAPI,
                $api];
        } else {
            $api = PMServer::getInstance()->getPluginManager()->getPlugin('BedrockEconomy');
            if ($api !== null) {
                return [self::BEDROCKECONOMYAPI,
                    $api];
            } else {
                return [null];
            }
        }
    }

    public function isInstall(): bool
    {
        return !is_null($this->getEconomy()[0]);
    }

    /**
    * @return int
    */
    public static function myMoney(Player $player, Closure $callback): void
    {
        if (self::getEconomy()[0] === self::ECONOMYAPI) {
            $money = self::getEconomy()[1]->myMoney($player);
            assert(is_float($money));
            $callback($money);
        } elseif (self::getEconomy()[0] === self::BEDROCKECONOMYAPI) {
            self::getEconomy()[1]->getAPI()->getPlayerBalance($player->getName(), ClosureContext::create(static function (?int $balance) use ($callback): void {
                $callback($balance ?? 0);
            }));
        }
    }

    public static function addMoney(Player $player, int $amount): void
    {
        if (self::getEconomy()[0] === self::ECONOMYAPI) {
            self::getEconomy()[1]->addMoney($player, $amount);
        } elseif (self::getEconomy()[0] === self::BEDROCKECONOMYAPI) {
            self::getEconomy()[1]->getAPI()->addToPlayerBalance($player->getName(), (int) $amount);
        }
    }

    public static function reduceMoney(Player $player, int $amount, Closure $callback): void
    {
        if (self::getEconomy()[0] === self::ECONOMYAPI) {
            $callback(self::getEconomy()[1]->reduceMoney($player, $amount) === EconomyAPI::RET_SUCCESS);
        } elseif (self::getEconomy()[0] === self::BEDROCKECONOMYAPI) {
            self::getEconomy()[1]->getAPI()->subtractFromPlayerBalance($player->getName(), (int) ceil($amount), ClosureContext::create(static function (bool $success) use ($callback): void {
                $callback($success);
            }));
        }
    }
    public static function getAllBalance(int $limit, int $offset): void{
        $accountManager = BetaBEAPI::getInstance();
        $accountManager->getSortedBalances($limit, $offset)->onCompletion(
            function (array $data) {
                var_dump($data);
            },
            function () {
                echo "Failed to retrieve balances.";
            }
        );
    }
}