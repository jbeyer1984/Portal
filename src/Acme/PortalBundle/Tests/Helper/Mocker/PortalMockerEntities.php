<?php

namespace Acme\PortalBundle\Tests\Helper\Mocker;

use Acme\PortalBundle\Entity\Article;
use Acme\PortalBundle\Entity\Client;
use Acme\PortalBundle\Tests\Helper\EntityCreator;
use Doctrine\Common\Collections\ArrayCollection;
use Mockery;

class PortalMockerEntities {

  /**
   * @param String[] $clients
   * @return mixed
   */
  public function getMockedClients(array $clients)
  {
    foreach ($clients as $client) {
      
    }
  }
  
  public function getMockedArticle($pos, $description, $tags)
  {
    /** @var Article $article */
    $article = EntityCreator::getCreatedObject('Acme\PortalBundle\Entity\Article', array(
      'pos' => $pos,
      'description' => $description,
      'client' => new Client(),
    ), array());
    foreach($tags as $tag) {
      $article->addTag($tag);
    }
    return $article;
  }


  public function getMockedClient($pos, $name)
  {
    $client = EntityCreator::getCreatedObject('Acme\PortalBundle\Entity\Client', array(
      'pos' => $pos,
      'name' => $name,
    ), array());
    return $client;
  }

  /**
   * @return mixed
   */
  public function getMockedFacade()
  {
    $clients = array();
    $clients[] = new Client();
    $facadeMock = Mockery::mock(new RepositoryFacade($this->doctrine, 'AcmePortalBundle'));
    return $facadeMock;
  }

  public function getMockedTags($arrNames)
  {
    $arrayCollection = new ArrayCollection();
    foreach ($arrNames as $name) {
      $tags = EntityCreator::getCreatedObject('Acme\PortalBundle\Entity\Tag', array(
        'name' => $name
      ), array());
      $arrayCollection->add($tags);
    }
    return $arrayCollection;
  }
}