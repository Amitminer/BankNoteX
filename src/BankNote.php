<?php

namespace AmitxD\BankNote;

use pocketmine\utils\Config;
use pocketmine\plugin\PluginBase;
use AmitxD\BankNote\command\BankNoteCommand;
use AmitxD\BankNote\Utils\Utils;
use AmitxD\BankNote\Manager\ConfigManager;
use AmitxD\BankNote\Manager\EventManager;

class BankNote extends PluginBase{

    protected static $instance;

    private Config $config;
    
    protected function onLoad(): void {
        self::$instance = $this;
    }

    protected function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->config = $this->getConfig();
        $this->registerEnchantment();
        $this->getServer()->getPluginManager()->registerEvents(new EventManager(), $this);
        $commandName = ConfigManager::getConfigNestedValue('command.name');
        $this->getServer()->getCommandMap()->register($commandName, new BankNoteCommand());
    }
    
    private function registerEnchantment(): void
    {
        $enchantment = "BankNote";
        $id = Utils::ENCHANTMENT_ID;
        Utils::createEnchantment($enchantment, $id);
    }

    public function getNoteConfig() : Config {
        return $this->config;
    }

    public static function getInstance(): self {
        return self::$instance;
    }
}