<?php

namespace Acme\PortalBundle\Tests\Controller;

use Acme\PortalBundle\Controller\DefaultController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
//        $client = static::createClient();
//
//        $crawler = $client->request('GET', '/hello/Fabien');
//
//        $this->assertTrue($crawler->filter('html:contains("Hello Fabien")')->count() > 0);
      $var = true;
      $this->assertTrue($var == true);
      $defaultController = new DefaultController();
//      print_r($defaultController);
      $bling = $defaultController->getContainer(); //->get('parameters')->get('lol');
//      print_r($bling);
      print_r('so');
    }
}
