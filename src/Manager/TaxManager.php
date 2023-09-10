<?php

declare(strict_types = 1);

namespace AmitxD\BankNote\Manager;

use AmitxD\BankNote\BankNote;

class TaxManager {

    public function __construct() {
        // NOOP
    }

    public static function getCreationRate(): float {
        $taxRate = ConfigManager::getConfigNestedValue('tax.creation-rate');
        return $taxRate;
    }
    public static function getRedemptionRate(): float {
        $taxRate = ConfigManager::getConfigNestedValue('tax.redemption-rate');
        return $taxRate;
    }

    public static function applyCreationTax(int $amount): int {
        $taxRate = self::getCreationRate();
        $amountAfterTax = self::additionPercentage($amount, $taxRate);
        return intval($amountAfterTax);
    }

    public static function applyRedemptionTax(int $amount): float {
        $taxRate = self::getRedemptionRate();
        $amountAfterTax = self::subtractPercentage($amount, $taxRate);
        return intval($amountAfterTax);
    }

    public static function additionPercentage(int $amount, float $percentage): float {
        return $amount + ($amount * $percentage);
    }

    private static function subtractPercentage(int $amount, float $percentage): float {
        return $amount - ($amount * $percentage);
    }
}