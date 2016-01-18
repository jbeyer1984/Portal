<?php

namespace Acme\PortalBundle\Utility\Extractor\Filter;

use Acme\PortalBundle\Entity\Article;
use Exception;

class ArticleFilter extends Filter
{
  /**
   * @param Article|FilterInterface $toFilter
   * @return bool
   * @throws Exception
   */
  public function pass($toFilter)
  {
    if ($toFilter instanceof Article) {
      return true;  
    }
    throw new Exception($toFilter." not instance of Article");
  }
}