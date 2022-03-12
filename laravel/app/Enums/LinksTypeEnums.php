<?php

namespace App\Enums;

abstract class LinksTypeEnums
{
    const PRODUCT = "product";
    const CATEGORY = "category";
    const STATIC_PAGE = "static-page";
    const CHECKOUT = "checkout";
    const HOMEPAGE = "homepage";

    public static function cases(): array
    {
        return [
            self::PRODUCT,
            self::CATEGORY,
            self::STATIC_PAGE,
            self::CHECKOUT,
            self:: HOMEPAGE
        ];
    }
}
