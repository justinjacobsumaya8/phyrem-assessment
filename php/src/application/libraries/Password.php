<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Password
{
    const ALLOWED_ALGOS = [
        'default' => PASSWORD_DEFAULT,
        'bcrypt' => PASSWORD_BCRYPT
    ];

    protected $_cost = 10;

    protected $_algo = PASSWORD_DEFAULT;

    public function hash($password)
    {
        return password_hash($password, $this->_algo, ['cost' => $this->_cost]);
    }

    public function setCost($cost = 10)
    {
        $this->_cost = $cost;
        return $this;
    }

    public function setAlgo($algo = 'default')
    {
        if (!in_array($algo, array_keys(self::ALLOWED_ALGOS))) {
            throw new Exception($algo . " is not allowed algo.");
        }
        $this->_algo = self::ALLOWED_ALGOS[$algo];
        return $this;
    }

    public function verifyHash($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public function validateStrength($password)
    {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        $errors = [];
        if (!$uppercase || !$lowercase) {
            $errors[] = "Password should include at least one upper case letter";
        }

        if (!$number) {
            $errors[] = "Password should include at least one number";
        }

        if (!$specialChars) {
            $errors[] = "Password should include at least one special character";
        }

        if (strlen($password) < 10) {
            $errors[] = "Password should be at least 10 characters in length";
        }

        return $errors;
    }
}
