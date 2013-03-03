<?php

namespace FadoeBase\Paginator\Adapter;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Zend\Paginator\Adapter\AdapterInterface;

class DoctrineAdapter extends Paginator implements AdapterInterface
{
    /**
     * (non-PHPdoc)
     * @see \Zend\Paginator\Adapter\AdapterInterface::getItems()
     */
    public function getItems($offset, $itemCountPerPage)
    {
        $this->getQuery()->setFirstResult($offset)->setMaxResults($itemCountPerPage);
        return $this->getQuery()->getResult($this->getQuery()->getHydrationMode());
    }
}
