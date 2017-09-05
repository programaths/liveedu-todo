<?php
/**
 * Created by PhpStorm.
 * User: live-edu
 * Date: 5/09/17
 * Time: 20:16
 */

namespace Programaths\LiveEdu\Todo\Services;


use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class PasswordVerifyEncoder implements PasswordEncoderInterface
{

    /**
     * Encodes the raw password.
     *
     * @param string $raw The password to encode
     * @param string $salt The salt
     *
     * @return string The encoded password
     */
    public function encodePassword($raw, $salt)
    {
        return password_hash($raw,PASSWORD_BCRYPT);
    }

    /**
     * Checks a raw password against an encoded password.
     *
     * @param string $encoded An encoded password
     * @param string $raw A raw password
     * @param string $salt The salt
     *
     * @return bool true if the password is valid, false otherwise
     */
    public function isPasswordValid($encoded, $raw, $salt)
    {
        return password_verify($raw,$encoded);
    }
}