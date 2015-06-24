<?php

namespace Acme\PortalBundle\Tests\Utility;

use Acme\PortalBundle\Tests\Helper\Db;
use Acme\PortalBundle\Entity\Article;
use Acme\PortalBundle\Entity\Client;
use Acme\PortalBundle\Entity\Tag;
use Acme\PortalBundle\Facade\Facade;
use Doctrine\Common\Collections\ArrayCollection;
use Mockery;

class PortalMocker {
  public function getMockedArticle($pos, $description, $tags)
  {
    $article = Db::createObject('Acme\PortalBundle\Entity\Article', array(
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
    $client = Db::createObject('Acme\PortalBundle\Entity\Client', array(
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
//    $managerRegistry = Mockery::mock(new FakeManagerRegistry());
    $clients = array();
    $clients[] = new Client();
    $facadeMock = Mockery::mock(new RepositoryFacade($this->doctrine, 'AcmePortalBundle'));
//    $facadeMock->shouldReceive('getRepositoryFacade->getRepository->findByName')
//      ->andReturn($clients)
//    ;
    return $facadeMock;
  }

  public function getMockedTags($arrNames)
  {
    $arrayCollection = new ArrayCollection();
    foreach ($arrNames as $name) {
      $tags = Db::createObject('Acme\PortalBundle\Entity\Tag', array(
        'name' => $name
      ), array());
      $arrayCollection->add($tags);
    }
    return $arrayCollection;
  }
}