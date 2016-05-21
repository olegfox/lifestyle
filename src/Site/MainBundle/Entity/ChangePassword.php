<?php

namespace Site\MainBundle\Entity;

class ChangePassword
{
    /**
     * @var string
     */
    public $new;

    /**
     * @var string
     */
    public $currentPassword;

    public function setCurrentPassword($currentPassword)
    {
        $this->currentPassword = $currentPassword;

        return $this;
    }

    public function getCurrentPassword()
    {
        return $this->currentPassword;
    }
}
