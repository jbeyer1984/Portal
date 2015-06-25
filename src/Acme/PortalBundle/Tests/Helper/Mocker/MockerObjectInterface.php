<?php

namespace Acme\PortalBundle\Tests\Helper\Mocker;

interface MockerObjectInterface {
  /**
   * @param $str String
   * @return mixed
   */
  public function getMocked($str);
}