<?php
/**
 * Created by PhpStorm.
 * User: jbeyer
 * Date: 26.06.2015
 * Time: 18:09
 */

namespace Acme\PortalBundle\Tests\Utility\Extractor;

use Acme\PortalBundle\Tests\Helper\Mocker\PortalMockerClients;
use Acme\PortalBundle\Utility\Extractor\ArticlesExtractor;
use Acme\PortalBundle\Utility\Extractor\Filter\ClientFilter;

class ArticlesExtractorTest extends \PHPUnit_Framework_TestCase {
  public $clients;
  
  public function setUp()
  {
    $portalMockerClients = new PortalMockerClients();
    $this->clients = array(
      $portalMockerClients->getMocked('asv'),
      $portalMockerClients->getMocked('spiegel'),
    );  
  }
  
  public function testFilterSettings()
  {
    // these guys pass
    $articlesExtractor = new ArticlesExtractor();
    $articlesExtractor->extract($this->clients, new ClientFilter());

    // setting filter form outside
    $articlesExtractor = new ArticlesExtractor();
    $articlesExtractor->setFilter(new ClientFilter());
    $articlesExtractor->extract($this->clients);
  }

  /**
   * @expectedException Exception
   * @expectedExceptionMessage filter has to be set in Acme\PortalBundle\Utility\Extractor\ArticlesExtractor
   */
  public function testNoFilterFail()
  {
    $articlesExtractor = new ArticlesExtractor();
    $articlesExtractor->extract($this->clients);
  }
}
