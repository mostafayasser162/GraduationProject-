<?php

namespace App\Traits;

 trait PackageHelper
{
    /**
     * Check if the package ID is a Basic package (1 or 2)
     */
    public static function isBasicPackage(int $packageId): bool
    {
        return in_array($packageId, [1, 5 , 4, 8]);
    }

    /**
     * Check if the package ID is a Pro Marketing package (3 or 4)
     */
    public static function isProMarketingPackage(int $packageId): bool
    {
        return in_array($packageId, [2, 6 ,4 ,8]);
    }

    /**
     * Check if the package ID is a Pro Supplychain package (5 or 6)
     */
    public static function isProSupplychainPackage(int $packageId): bool
    {
        return in_array($packageId, [3, 7 ,4 , 8]);
    }

    /**
     * Check if the package ID is a Premium package (7 or 8)
     */
    public static function isPremiumPackage(int $packageId): bool
    {
        return in_array($packageId, [4, 8]);
    }

    /**
     * Get the base package ID (odd number) for any package
     */
    // public static function getBasePackageId(int $packageId): int
    // {
    //     return $packageId % 2 == 0 ? $packageId - 1 : $packageId;
    // }
} 