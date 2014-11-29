<?php
namespace Acme\PortalBundle\Utility;

use Acme\PortalBundle\Facade\FacadeUtilityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Acme\PortalBundle\Facade\Facade;
//use \Acme\PortalBundle\Facade\FacadeInterface;
//use Acme\PortalBundle\Facade\RepositoryFacade;
//use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class PortalData implements FacadeUtilityInterface
{
  /**
   * @var Facade
   */
  protected $facade;
  /**
   * @var Session
   */
  protected $session;
  /**
   * @var ArrayCollection
   */
  protected $articles;
  /**
   * @var array
   */
  protected $visitedArr;
  /**
   * @var array
   */
  protected $visitedBlacklist;
  
  public function __construct()
  {
//    $this->session = new Session();
//    $this->session->start();
    $this->articles = array();
    $this->visitedArr = array();
    $this->visitedBlacklist = array();
  }

  /**
   * @param Facade $facade
   * @return mixed|void
   */
  public function setFacade(Facade $facade)
  {
    $this->facade = $facade->getRepositoryFacade();
  }

  public function setSession(Session $session)
  {
    $this->session = $session;
  }

  /**
   * @param $client String
   * @param $article String
   */
  public function visit($client, $article)
  {
    $this->equipVisitedArr($client, $article);
    $articleDb = $this->facade->getRepository('Article')->findByDescription($article);
    if (empty($articleDb)) {
      $this->articles = $this->facade->getRepository('Article')->findAllOrderedByDescription();

      // !!!!!!!!! have to implement logging, wrong article !!!!!!!!!!!!
      return;
    } else {
      $articleDb = $articleDb[0];
    }
    $tags = $articleDb->getTags();
    
    $this->fillBlacklist($client, $article);
    $this->articles = $this->getMostSignificantArticlesToTags($tags); // create also $this->clientsArticles

    $this->filterArticleWithBlacklist();
  }

  /**
   * @param $client
   * @param $article
   */
  protected function equipVisitedArr($client, $article)
  {
    $sessionArr = $this->session->get('overview');
    if (isset($sessionArr)) {
      $this->visitedArr = $sessionArr;
      $this->addVisit($client, $article);
      $this->session->set('overview', $this->getVisitedArr());
    } else {
      $this->generateVisit($client, $article);
      $this->session->set('overview', $this->getVisitedArr());
    }
  }

  public function fillBlacklist($client, $article)
  {
    $sessionArr = $this->session->get('blacklist');
    if (isset($sessionArr)) {
      $this->visitedBlacklist = $sessionArr;
    }
    if (!isset($this->visitedBlacklist[$client])) {
      $this->visitedBlacklist[$client] = array();
    }
    $this->visitedBlacklist[$client][$article] = $article;
    $this->session->set('blacklist', $this->visitedBlacklist);
  }
  
  /**
   * @param $tags
   * @return mixed|ArrayCollection
   */
  public function getMostSignificantArticlesToTags($tags)
  {
    $tagNames = array();
    foreach($tags as $tag) {
      $tagNames[] = $tag->getName();
    }
//    $tagNames = array('marketing', 'cms');
//    $tagNames = array('marketing');
    $articlesDb = $this->facade->getRepository('Article')->findSignificantArticleToTags($tagNames);
    
    return $articlesDb;
//    return $articles;
  }

  public function generateVisit($client, $article){
    $this->visitedArr['visited'] = [];
    $this->visitedArr['visited'][$client] = [];
    $this->visitedArr['visited'][$client][$article] = $article;
  }

  /**
   * @param $client String
   * @param $article String
   */
  public function addVisit($client, $article)
  {
    if (!isset($this->visitedArr['visited'][$client])) {
      $this->visitedArr['visited'][$client] = [];
    }
    if (!isset($this->visitedArr['visited'][$client][$article])) {
      $this->visitedArr['visited'][$client][$article] = [];
    }
    $this->visitedArr['visited'][$client][$article] = $article;
  }

  /**
   * @return void
   */
  public function filterArticleWithBlacklist()
  {
    $clientsVisited = array_keys($this->visitedArr['visited']);
    $clientsTagged = array_map(function ($article) {
      return $article->getClient()->getName();
    }, $this->articles);
    $clientsToAdd = array_diff($clientsVisited, $clientsTagged);
    $clients = $this->facade->getRepositoryFacade()->getRepository('Client')->findByName($clientsToAdd);
    foreach($clients as $client) {
      foreach ($client->getArticles() as $article) {
        $this->articles[] = $article;
      }
    }

    $this->articles = array_filter($this->articles, function ($article) {
      $clientName = $article->getClient()->getName();

      $clientsTagged[] = $clientName;
      $articleName = $article->getDescription();

      if (isset($this->visitedBlacklist[$clientName])
        && isset($this->visitedBlacklist[$clientName][$articleName])
      ) {
        $count = $article->getTags()->count();
        return false;
      }
      return true;
    });
  }
  
  /**
   * @return ArrayCollection
   */
  public function getArticles()
  {
    return $this->articles;
  }

  /**
   * @param ArrayCollection $articles
   */
  public function setArticles($articles)
  {
    $this->articles = $articles;
  }

  /**
   * @return array
   */
  public function getClientsArticles()
  {
    return $this->clientsArticles;
  }

  /**
   * @param array $clientsArticles
   */
  public function setClientsArticles($clientsArticles)
  {
    $this->clientsArticles = $clientsArticles;
  }
  
  /**
   * @return array
   */
  public function getVisitedBlacklist()
  {
    return $this->visitedBlacklist;
  }

  /**
   * @param array $visitedBlacklist
   */
  public function setVisitedBlacklist($visitedBlacklist)
  {
    $this->visitedBlacklist = $visitedBlacklist;
  }
  
  /**
   * @return array
   */
  public function getVisitedArr()
  {
    return $this->visitedArr;
  }

  /**
   * @param array $visitArr
   */
  public function setVisitedArr($visitArr)
  {
    $this->visitedArr = $visitArr;
  }
}