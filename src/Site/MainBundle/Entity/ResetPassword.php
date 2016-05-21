<?php

namespace Site\MainBundle\Entity;

class ResetPassword
{
    /**
     * @var string
     */
    public $password;

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }
}
