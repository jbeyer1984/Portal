<?php
/**
 * Created by PhpStorm.
 * User: jbeyer
 * Date: 06.11.2014
 * Time: 09:09
 */

namespace Acme\PortalBundle\Utility;
use Acme\PortalBundle\DataFixtures\ORM\LoadPortalData;
use Acme\PortalBundle\Facade\RepositoryFacade;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;

require_once dirname(__DIR__).'/../../../../app/AppKernel.php';

class PortalDataTest extends \PHPUnit_Framework_TestCase {
//class PortalDataTest extends KernelTestCase {
  protected $kernel;
  protected $doctrine;
  protected $session;
  protected $loader;
  protected $fixtures;
  protected $result;
  protected $facade;
  protected $clientsArticles;
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
    
    
    $this->fixtures = array(
      new LoadPortalData()
    );
    $this->purgeDatabase($this->fixtures);
//    $this->doctrine = static::$kernel->getContainer()->get('doctrine');
//    $this->session = static::$kernel->getContainer()->get('session');

    $this->facade = new RepositoryFacade($this->doctrine, 'AcmePortalBundle');
    $this->portalData = new PortalData();
    $this->portalData->setFacade($this->facade);
    $this->portalData->setSession($this->session);
  }

  /**
   * @param $fixtures array(FixtureInterface)
   */
  public function purgeDatabase($fixtures)
  {
    $loader = new Loader();
    foreach ($fixtures as $fixture) {
      $loader->addFixture($fixture);
    }

    $purger = new ORMPurger($this->doctrine->getManager());
    $executor = new ORMExecutor($this->doctrine->getManager(), $purger);
    $executor->execute($loader->getFixtures());
  }

  /**
   * Load and execute fixtures from a directory
   *
   * @param string $directory
   */
  protected function loadFixturesFromDirectory($directory)
  {
    $this->loader = new Loader();
    $this->loader->loadFromDirectory($directory);
    $this->executeFixtures($this->loader);
  }

  /**
   * to generate viewable debugging data from articles not articlesSorted
   * @param array $articles
   * @return array
   */
  public function generateClientsArticles(Array $articles)
  {
    $clientsArticles  = array();
    foreach ($articles as $article) {
      $client = $article->getClient()->getName();
      $clientPos = $article->getClient()->getPos();
      $articleName = $article->getDescription();
      if (!isset($clientsArticles[$clientPos])) {
        $clientsArticles[$clientPos] = array();
      }
      if (!isset($clientsArticles[$clientPos][$client])) {
        $clientsArticles[$clientPos][$client] = array();
      }
      $clientsArticles[$clientPos][$client][$articleName] = $article;
    }
    return $clientsArticles;
  }
  
  public function testVisitWrongArticleName()
  {
    $this->portalData->visit('spiegel', 'change'); // wrong article !!!!!!
    $this->result = $this->portalData->getArticlesSorted();

    // gebe alle article aus
    $this->assertTrue(isset($this->result[0]['asv']['stylebook']));
    $this->assertTrue(isset($this->result[0]['asv']['travelbook']));
    $this->assertTrue(isset($this->result[1]['tdu']['vermarkter']));
    $this->assertTrue(isset($this->result[2]['spiegel']['qc']));
  }

  public function testVisitSpiegel()
  {
    $this->portalData->visit('spiegel', 'qc');
    $this->result = $this->portalData->getArticlesSorted();
    $this->assertTrue(isset($this->result[0]['asv']['travelbook']));
    $this->assertTrue(isset($this->result[0]['asv']['stylebook']));
    $this->assertTrue(isset($this->result[1]['tdu']['vermarkter']));
  }

  public function testVisitSpiegelThenVermarkter()
  {
    $this->portalData->visit('spiegel', 'qc');
    $this->portalData->visit('tdu', 'vermarkter');
    $this->result = $this->portalData->getArticlesSorted();
    ob_start();
    \Doctrine\Common\Util\Debug::dump($this->result);
    $print = ob_get_clean();
    error_log('$$this->result = ' . $print, 0, '/tmp/error.log');
    $this->assertTrue(empty($this->result));
  }

//  public function testVisitAsvThenTdu()
//  {
//    $this->portalData->visit('asv', 'travelbook');
//    $this->portalData->visit('tdu', 'vermarkter');
//    $this->result = $this->generateClientsArticles($this->portalData->getArticles());
//    $this->assertTrue(isset($this->result[0]['asv']['stylebook']));
//  }
}
 