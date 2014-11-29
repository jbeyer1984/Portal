<?php

namespace Acme\PortalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\PortalBundle\Entity\Tag;
use Acme\PortalBundle\Entity\Article;
use Acme\PortalBundle\Entity\Client;

class LoadPortalData implements FixtureInterface
{
  /**
   * {@inheritDoc}
   */
  public function load(ObjectManager $manager)
  {
    // structure is client[articles[tags]], filling data from inside to outside
    
    // tags
    $tagsArrNames = array(
      'cms', 'symfony', 'framework', 'gridview', 'content', 'mobil', 'beauty', 'html',
      'marketing', 'advertisement'
    );
    
    $tagsArr = array();
    foreach($tagsArrNames as $tagName) {
      $tag = new Tag();
      $tag->setName($tagName);
      $tagsArr[$tagName] = $tag;
//      $manager->persist($tag);
    }
    $stylebookTags = array(
      $tagsArr['content'], $tagsArr['mobil'], $tagsArr['beauty'], $tagsArr['html']
    );
    $travelbookTags = array( // upper line the same like stylebook
      $tagsArr['content'], $tagsArr['mobil'], $tagsArr['beauty'], $tagsArr['html'],
      $tagsArr['symfony'], $tagsArr['framework'] // extended line
    );
    $vermarkterTags = array(
      $tagsArr['marketing'], $tagsArr['cms']
    );
    $qcTags = array(
      $tagsArr['marketing'], $tagsArr['advertisement'],
      $tagsArr['symfony'], $tagsArr['framework']
    );
    
    
    // articles
    $articleArrNames = array(
      'stylebook', 'travelbook', 'vermarkter', 'qc'
    );

    $articlesArr = array();
    foreach($articleArrNames as $articleName) {
      $article = new Article();
      $article->setDescription($articleName);
      $articlesArr[$articleName] = $article;
    }

    $articlesArr['stylebook']->setPos(0);
    foreach($stylebookTags as $key => $tag) {
      $articlesArr['stylebook']->addTag($tag);
      $manager->persist($tag);
    }
    $articlesArr['travelbook']->setPos(1);
    foreach($travelbookTags as $tag) {
      $articlesArr['travelbook']->addTag($tag);
      $manager->persist($tag);
    }
    $articlesArr['vermarkter']->setPos(0);
    foreach($vermarkterTags as $tag) {
      $articlesArr['vermarkter']->addTag($tag);
      $manager->persist($tag);
    }
    $articlesArr['qc']->setPos(0);
    foreach($qcTags as $tag) {
      $articlesArr['qc']->addTag($tag);
      $manager->persist($tag);
    }
    foreach($articlesArr as $article) {
      $manager->persist($article);
    }
    $manager->flush();
    
    
    // clients
    $clientArrNames = array(
      'asv', 'tdu', 'spiegel'
    );
    $clientsArr = array();
    $index = 0;
    foreach($clientArrNames as $clientName) {
      $client = new Client();
      $client->setPos($index);
      $client->setName($clientName);
      $clientsArr[$clientName] = $client;
      $index++;
    }
    
    // 1:n relation client:article
    $clientsArr['asv']->addArticle($articlesArr['stylebook']);
    $articlesArr['stylebook']->setClient($clientsArr['asv']);
    $manager->persist($articlesArr['stylebook']);
    $clientsArr['asv']->addArticle($articlesArr['travelbook']);
    $articlesArr['travelbook']->setClient($clientsArr['asv']);
    $manager->persist($articlesArr['travelbook']);
    $clientsArr['tdu']->addArticle($articlesArr['vermarkter']);
    $articlesArr['vermarkter']->setClient($clientsArr['tdu']);
    $manager->persist($articlesArr['vermarkter']);
    $clientsArr['spiegel']->addArticle($articlesArr['qc']);
    $articlesArr['qc']->setClient($clientsArr['spiegel']);
    $manager->persist($articlesArr['qc']);
    foreach($clientsArr as $client) {
      $manager->persist($client);
    }
    $manager->flush();
  }
}