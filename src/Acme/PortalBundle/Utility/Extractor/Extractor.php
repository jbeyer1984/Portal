<?php

namespace Acme\PortalBundle\Utility\Extractor;

use Acme\PortalBundle\Utility\Extractor\Filter\Filter;
use Acme\PortalBundle\Utility\Validator\ValidatorCollection;
use Acme\PortalBundle\Utility\Extractor\Filter\FilterInterface;
use Exception;

abstract class Extractor implements ExtractorInterface {
  /**
   * @var FilterInterface $filter
   */
  protected $filter;

  /**
   * @param $toExtract
   * @param FilterInterface $filter
   * @return mixed
   */
  abstract public function extract($toExtract, FilterInterface $filter = null);

  /**
   * @param FilterInterface $filter
   * @return FilterInterface
   * @throws Exception
   */
  protected function validateFilter($filter)
  {
    if (!$filter instanceOf FilterInterface && !$this->filter instanceof FilterInterface) {
      throw new Exception("filter has to be set in " . get_class($this));
    }
    if (!$filter instanceof FilterInterface) {
      $filter = $this->filter;
    }
    return $filter;
  }

  /**
   * @return FilterInterface
   */
  public function getFilter()
  {
    return $this->filter;
  }

  /**
   * @param FilterInterface $filter
   */
  public function setFilter($filter)
  {
    $this->filter = $filter;
  }


}