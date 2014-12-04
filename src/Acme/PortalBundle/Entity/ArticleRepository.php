<?php

namespace Acme\PortalBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{
  public function findAllOrderedByDescription()
  {
    return $this->getEntityManager()
      ->createQuery(
        'SELECT t FROM AcmePortalBundle:Article t'
//        . ' WHERE t.id in (SELECT at.id FROM AcmePortalBundle:Article at)'
        . ' ORDER BY t.client, t.pos, t.description ASC'
      )
      ->getResult();
  }

  public function findSignificantArticlesToTags($tags)
  {
    $query = $this->getEntityManager()->getRepository('AcmePortalBundle:Article')
      ->createQueryBuilder('a')
      ->join('a.tags', 't')
    ;
    $i = 0;
    $parameters = array();
    foreach ($tags as $tagName) {
      $query->orWhere('t.name LIKE ?' . $i++);
      $parameters[$i-1] = '%' . $tagName . '%';
    }
    $query->setParameters($parameters);
    $dgl = $query->getQuery()->getSQL();
//    ob_start();
//    \Doctrine\Common\Util\Debug::dump($dgl);
//    $print = ob_get_clean();
//    error_log('dump:$VAR$ = ' . $print, 0, '/tmp/error.log');


    return $query->getQuery()->getResult();
  }
}

