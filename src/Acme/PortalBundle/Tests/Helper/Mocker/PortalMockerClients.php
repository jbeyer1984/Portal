<?php

namespace Acme\PortalBundle\Tests\Helper\Mocker;

use Acme\PortalBundle\Entity\Article;
use Acme\PortalBundle\Entity\Client;
use Acme\PortalBundle\Entity\Tag;
use Exception;

class PortalMockerClients implements MockerObjectInterface { 
  
  public function getMocked($str)
  {
    $portalMockerFactory = PortalMockerFactory::init();
    $portalMocker = $portalMockerFactory->getPortalMockerEntities();
    
    $clientData = $portalMockerFactory->getClientData();
    $client = $clientData['clients'][$str];
    // default client, for debugging quick
    $newClient = $portalMocker->getMockedClient('0', 'default');
    
    $newClient = $portalMocker->getMockedClient(
      $client['pos'],
      $client['name']
    );
    foreach ($client['articles'] as $articleName => $article) {
      /** @var $newMockedArticle Article */
      $newMockedArticle = $portalMocker->getMockedArticle(
        $article['pos'],
        $article['description'],
        $portalMocker->getMockedTags($article['tags'])
      );
      $newMockedArticle->setClient($newClient);
      $newClient->addArticle($newMockedArticle);
    }
    if ('default' == $newClient->getName()) {
      throw new Exception ("couldn't find client".$str);
    }
    return $newClient;
  }
}