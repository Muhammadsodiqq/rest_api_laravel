<?php

namespace App\SmsService;

class SmsHelperService
{
    /**
     * Удаляет из номера телефона все символы кроме цифр.
     *
     * @param string $phone
     * @return string
     */
    public static function clearPhone($phone)
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }

    /**
     * Проверяет является ли номер узбекским.
     *
     * @param string $phone
     * @return bool
     */
    public static function isUzPhone($phone)
    {
        return preg_match('/^9989[01345789]{1}[0-9]{7}$/', $phone) == 1 ? true : false;
    }
}
