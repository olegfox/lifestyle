<?php

namespace Site\MainBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class PageRepository extends EntityRepository
{
    public function findAll(){
        return $this->findBy(array(), array('position' => 'ASC'));
    }
}
