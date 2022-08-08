<?php

namespace Macopedia\GusIntegration\Model;

class VatIdValidator
{
    /**
     * @param string $vatId
     * @return bool
     */
    public function isVatIdNumberValid(string $vatId): bool
    {
        $cleanNumber = preg_replace("/-/", "", $vatId);
        $reg = '/^[0-9]{10}$/';

        if (preg_match($reg, $cleanNumber) == false) {
            return false;
        } else {
            $digits = str_split($cleanNumber);
            $checksum = (
                6 * (int) $digits[0] + 5 * (int) $digits[1] + 7 * (int) $digits[2] + 2 * (int) $digits[3]
                + 3 * (int) $digits[4] + 4 * (int) $digits[5] + 5 * (int) $digits[6] + 6 * (int) $digits[7]
                + 7 * (int) $digits[8]
                ) % 11;

            return ((int) $digits[9] == $checksum);
        }
    }
}
