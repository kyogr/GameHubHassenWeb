<?php

namespace project\GameHubBundle\Repository;
use Doctrine\ORM\EntityRepository;


class evenementRepository extends EntityRepository
{
    public function findDateDQL($date)
    {
        $query=$this->getEntityManager()->createQuery("Select e from projectGameHubBundle:evenement e WHERE e.date=:datee")->setParameter('datee',$date);
        return $query->getResult();

    }

}