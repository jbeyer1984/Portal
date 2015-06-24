<?php
/**
 * Created by PhpStorm.
 * User: jbeyer
 * Date: 06.11.2014
 * Time: 09:09
 */

namespace Acme\PortalBundle\Utility;
use Acme\PortalBundle\DataFixtures\ORM\LoadPortalData;
use Acme\PortalBundle\Tests\Utility\PortalMocker;
use Acme\PortalBundle\Entity\Article;
use Acme\PortalBundle\Entity\Client;
use Acme\PortalBundle\Entity\Tag;
use Acme\PortalBundle\Facade\Facade;
use Acme\PortalBundle\Facade\RepositoryFacade;
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
    $this->mo = new PortalMocker();
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
        $clientsArticles[$clientPos] = array();
      }
      if (!isset($clientsArticles[$clientPos][$client])) {
        $clientsArticles[$clientPos][$client] = array();
      }
      $clientsArticles[$clientPos][$client][$articleName] = $articleName;
    }
    return $clientsArticles;
  }
  
  public function testMockery()
  {
    // visit stylebook
    $visitedArr = array(
      'visited' => array(
        'asv' => array(
          'stylebook' => 'stylebook'
        )
      )
    );
    $this->portalData->setVisitedArr($visitedArr);
    
    $mockArray = array(
      'clients' => array(
        'asv' => array(
          'stylebook' => array(
            'tags' => array('beauty', 'html', 'symfony')      
          ),
          'travelbook' => array(
            'tags' => array()
          )
        ),
        'spqc' => array(
          'qc' => array(
            'tags' => array()
          )
        )
      )
    );
    
    function grepInDepth($str, array $arr) {
      $matches = explode('.', $str);
      if (1 == sizeof($matches)) {
        return $matches[0];
      }
      foreach($matches as $match) {
        array_shift($matches);
        $str = implode('.', $matches);
        return grepInDepth($str, $arr[$match]);
      }
    }
    $any = grepInDepth('clients.asv.stylebook.tags', $mockArray);
    
    
    // used Tags that are relevant to other articles, in this case 'symfony'
    $stylebookTags = $this->mo->getMockedTags(array( // stylebook is tag specifier here
      'beauty', 'symfony', 'html'
    ));
    $travelbookTags = $this->mo->getMockedTags(array( // travelbook appears, because of relation to 'symfony'
      'symfony', 'framework'
    ));
    
    // build article for client asv
    $stylebookArticle = $this->mo->getMockedArticle(0, 'stylebook', $stylebookTags);
    $travelbookArticle = $this->mo->getMockedArticle(1, 'travelbook', $travelbookTags);

    // add articles to client asv 
    $asvClient = $this->mo->getMockedClient(0, 'asv');
    $asvClient->addArticle($travelbookArticle);
    $asvClient->addArticle($stylebookArticle);
    
    // toArray() because working with array in array_map in filterArticlesWithBlacklist()
    $mostSignificantArticlesToTagsOfStylebook = $asvClient->getArticles()->toArray();
    
    $this->portalData->setArticles($mostSignificantArticlesToTagsOfStylebook);
    
//    $this->portalData->setFacade($this->getMockedFacade());
    $this->portalData->filterArticlesWithBlacklist();
    $this->result = $this->generateClientsArticles($this->portalData->getArticles());
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
 