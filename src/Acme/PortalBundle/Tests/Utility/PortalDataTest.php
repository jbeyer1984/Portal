<?php
/**
 * Created by PhpStorm.
 * User: jbeyer
 * Date: 06.11.2014
 * Time: 09:09
 */

namespace Acme\PortalBundle\Utility;
use Acme\PortalBundle\DataFixtures\ORM\LoadPortalData;
use Acme\PortalBundle\Tests\Helper\Mocker\PortalMockerEntities;
use Acme\PortalBundle\Tests\Helper\Mocker\PortalMockerClients;
use Acme\PortalBundle\Entity\Article;
use Acme\PortalBundle\Entity\Client;
use Acme\PortalBundle\Entity\Tag;
use Acme\PortalBundle\Facade\Facade;
use Acme\PortalBundle\Facade\RepositoryFacade;
use Acme\PortalBundle\Tests\Helper\Mocker\PortalMockerFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Acme\PortalBundle\Tests\Helper\Db;
use Mockery;

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
  protected $mo;
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
//    $this->purgeDatabase($this->fixtures);
//    $this->doctrine = static::$kernel->getContainer()->get('doctrine');
//    $this->session = static::$kernel->getContainer()->get('session');

    $this->facade = new RepositoryFacade($this->doctrine, 'AcmePortalBundle');
    $this->portalData = new PortalData();
    $this->portalData->setFacade($this->facade);
    $this->portalData->setSession($this->session);
    $this->mo = new PortalMockerEntities();
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
  public function generateClientsArticles($articles)
  {
    $clientsArticles  = array();
    foreach ($articles as $article) {
      $client = $article->getClient()->getName();
      $clientPos = $article->getClient()->getPos();
      $articleName = $article->getDescription();
      if (!isset($clientsArticles[$clientPos])) {
        $clientsArticles[$client] = array();
        $clientsArticles[$client]['articles'] = array();
      }
      $clientsArticles[$client]['articles'][$articleName] = $articleName;
    }
    return $clientsArticles;
  }
  
  public function grepInDepth($str, $arr)
  {
    $matches = explode('.', $str);
    foreach ($matches as $match) {
      $arr = $arr[$match];
    }
    return $arr;
  }
  
  public function testFilterArticlesWithBlacklist()
  {
    // visit travelbook
    $visitedArr = array(
      'visited' => array(
        'asv' => array(
          'travelbook' => 'travelbook'
        )
      )
    );
    $this->portalData->setVisitedArr($visitedArr);
    $this->portalData->setVisitedBlacklist($visitedArr['visited']);
    
    $portalMockerClients = new PortalMockerClients();
    $clients = array(
      $portalMockerClients->getMocked('asv'),
      $portalMockerClients->getMocked('spiegel')  
    );
    
    $articlesArrays = array_map(function($client) {
      return $client->getArticles()->toArray();
    }, $clients);
    $mergedArticles = array();
    foreach ($articlesArrays as $articlesArr) {
      $mergedArticles = array_merge($mergedArticles, $articlesArr);
    }
    $mostSignificantArticlesToTagsOfTravelbook = $mergedArticles;
    
    $this->portalData->setArticles($mostSignificantArticlesToTagsOfTravelbook);
    
    $this->portalData->filterArticlesWithBlacklist();
    $this->result = $this->generateClientsArticles($this->portalData->getArticles());
    $this->assertArrayHasKey('stylebook', $this->grepInDepth('asv.articles', $this->result));
    $this->assertArrayNotHasKey('travelbook', $this->grepInDepth('asv.articles', $this->result));
    $this->assertArrayHasKey('qc', $this->grepInDepth('spiegel.articles', $this->result));
  }
  
  public function testVisitWrongArticleName()
  {
    $this->portalData->visit('spiegel', 'change'); // wrong article !!!!!!
    $this->result = $this->portalData->getArticlesSorted();

    // show all articles
    $dump = Mockery::mock("PortalData");
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
    $this->assertTrue(empty($this->result));
  }

  public function testVisitAsvThenTdu()
  {
    $this->portalData->visit('asv', 'travelbook');
    $this->portalData->visit('tdu', 'vermarkter');
    $this->result = $this->generateClientsArticles($this->portalData->getArticles());
    $this->assertTrue(isset($this->result[0]['asv']['stylebook']));
  }
}
 