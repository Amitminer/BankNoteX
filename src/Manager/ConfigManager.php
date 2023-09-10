<?php

declare(strict_types=1);

namespace AmitxD\BankNote\Manager;

use AmitxD\BankNote\BankNote;

class ConfigManager {

    private static $config;

    public function __construct() {
        self::$config = BankNote::getInstance()->getConfig();
    }

    public static function getConfigValue(string $value): string {
        self::initialize();
        return self::$config->get($value);
    }

    public static function getConfigNestedValue(string $nestedKey): mixed {
        self::initialize();
        return self::$config->getNested($nestedKey);
    }

    private static function initialize(): void {
        if (self::$config === null) {
            self::$config = BankNote::getInstance()->getConfig();
        }
    }
}