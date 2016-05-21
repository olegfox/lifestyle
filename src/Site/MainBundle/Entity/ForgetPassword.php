<?php

namespace Site\MainBundle\Entity;

class ForgetPassword
{
    /**
     * @var string
     */
    public $email;

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }
}
