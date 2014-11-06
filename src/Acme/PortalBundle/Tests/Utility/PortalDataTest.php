<?php
/**
 * Created by PhpStorm.
 * User: jbeyer
 * Date: 06.11.2014
 * Time: 09:09
 */

namespace Acme\PortalBundle\Utility;
use Acme\PortalBundle\Facade\RepositoryFacade;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

require_once dirname(__DIR__).'/../../../../app/AppKernel.php';

class PortalDataTest extends \PHPUnit_Framework_TestCase {
//class PortalDataTest extends KernelTestCase {
  protected $kernel;
  protected $doctrine;
  protected $session;
  protected $result;
  protected $facade;
  /**
   * @var PortalData
   */
  protected $portalData;

  public function setUp()
  {
    // Boot the AppKernel in the test environment and with the debug.
    $this->kernel = new \AppKernel('test', true);
    $this->kernel->boot();
//    self::bootKernel();

    $this->doctrine = $this->kernel->getContainer()->get('doctrine');
    $this->session = $this->kernel->getContainer()->get('session');
//    $this->doctrine = static::$kernel->getContainer()->get('doctrine');
//    $this->session = static::$kernel->getContainer()->get('session');

    $this->facade = new RepositoryFacade($this->doctrine, 'AcmePortalBundle');
    $this->portalData = new PortalData();
    $this->portalData->setFacade($this->facade);
    $this->portalData->setSession($this->session);
  }
  
  public function testVisitWrongArticleName()
  {
    $this->portalData->visit('spiegel', 'change'); // wrong article !!!!!!

    // gebe alle article aus
    $this->assertTrue(isset($this->result['asv']['stylebook']));
    $this->assertTrue(isset($this->result['asv']['travelbook']));
    $this->assertTrue(isset($this->result['tdu']['vermarkter']));
    $this->assertTrue(isset($this->result['spiegel']['qc']));
  }

  public function testVisitAsvThenTdu()
  {
    $this->portalData->visit('asv', 'travelbook');
    $this->portalData->visit('tdu', 'vermarkter');
    $this->result = $this->portalData->getClientsArticles();
    $this->assertTrue(isset($this->result['asv']['stylebook']));
  }
}
 