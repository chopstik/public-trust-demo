<?php

namespace Traits;

trait StringUtilities
{

    /**
     * Anonymize an email address
     *
     * @param string $email
     * @return string
     */
    public static function anonymizeEmail(string $email): string
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
            throw new InvalidArgumentException('Invalid email address');
        }

        $emailParts = explode('@', $email);
        $emailParts[0] = substr($emailParts[0], 0, 2) . '***';
        $emailParts[1] = substr($emailParts[1], 0, 2) . '***';

        return implode('@', $emailParts);
    }
}
