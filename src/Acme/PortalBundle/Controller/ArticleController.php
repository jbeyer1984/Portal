<?php

// src/Acme/PortalBundle/Controller/ArticleController.php
namespace Acme\PortalBundle\Controller;

use Acme\PortalBundle\Entity\Article;
use Acme\PortalBundle\Entity\Tag;
use Acme\PortalBundle\Form\Type\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleController extends Controller
{
  protected $repositories = array();

  protected $em;

  public function indexAction()
  {
    return $this->render('AcmePortalBundle:Default:index.html.twig', array('name' => 'test'));
  }

  /**
   * @return \Doctrine\Common\Persistence\ObjectManager|object
   */
  protected function getEm()
  {
    if (isset($this->em)) {
      return $this->em;
    }
    $this->em = $this->getDoctrine()->getManager();
    return $this->em;
  }

  /**
   * @param $identifier
   * @return EntityRepository
   */
  protected function getRepository($identifier)
  {

    if (isset($this->repositories[$identifier])) {
      return $this->repositories[$identifier];
    }
    $em = $this->getEm();
    $repository = $em->getRepository('AcmePortalBundle:' . $identifier);
    $this->repositories[$identifier] = $repository;
    return $repository;
  }

  public function newAction(Request $request) {
    $article = $this->getNewArticle();

    $form = $this->createForm(new ArticleType(), $article);
    $form->handleRequest($request);

    if ($form->isValid()) {
      $this->getEm()->persist($form);
    }
    
    $forms = array();
    $forms[] = $form->createView();

    return $this->render('AcmePortalBundle:Article:new.html.twig',
      array(
        'forms' => $forms
      )
    );
//    return $this->redirect(
//      $this->generateUrl(
//        'acme_article_show',
//        array(
//          'forms' => $forms
//        )
//      )
//    );
  }

  public function showAction() {
    $articles = $this->getRepository('Article')->findAllOrderedByDescription();

    $article = $this->getNewArticle();
    $form = $this->createForm(new ArticleType(), $article);

    $forms = array();
    foreach ($articles as $oneArticle) {
      $forms[] = $this->createForm(new ArticleType, $oneArticle)->createView();

    }
//    $forms[] = $form->createView();

    return $this->render('AcmePortalBundle:Article:new.html.twig',
      array(
        'forms' => $forms
      )
    );
  }

  /**
   * @param $articleDb
   * @param $reqArticle
   */
  protected function update($articleDb, $reqArticle) {
    $articleDb->setDescription($reqArticle->getDescription());
    $articleDb->setPos($reqArticle->getPos());
    $reqTags = $reqArticle->getTags();
    $articleDbTagsCount = $articleDb->getTags()->count();
    if (empty($articleDbTagsCount)) {
      foreach($reqArticle->getTags() as $key => $tag) {
        $articleDb->addTag($tag);
        $this->getEm()->persist($tag);
      }
    } else {
      $tagsInArticleDb = array();
      foreach($articleDb->getTags() as $key => $tag) {
        $name = $reqTags[$key]->getName();
        $tagsInArticleDb[] = $name;
        $tag->setName($name);
//        $this->getEm()->persist($tag);
      }
      if (sizeof($reqTags) > $articleDbTagsCount) {
        $start = $articleDbTagsCount;
        $end = sizeof($reqTags);
        for ($i = $start; $i < $end; $i++) {
          if (!in_array($reqTags[$i]->getName(), $tagsInArticleDb)) {
            $tag = new Tag();
            $tag->setName($reqTags[$i]->getName());
            $this->getEm()->persist($tag);
            $articleDb->addTag($tag);
          }
        }
      }
    }
    $this->getEm()->persist($articleDb);
    $this->getEm()->flush();
  }

  public function strategy($form) {
    $reqArticle = $form->getData();
//      \Doctrine\Common\Util\Debug::dump($reqArticle);
    $articleDb = $this->getRepository('Article')->findOneById($reqArticle->getId());
    if (!empty($articleDb)) {
      $this->update($articleDb, $reqArticle);
    }
    return $this->redirect(
      $this->generateUrl(
        'acme_article_new',
        array()
      )
    );
  }

  public function formAction(Request $request)
  {
    $article = $this->getNewArticle();
    // end dummy code

//    $form = $this->createForm(new ArticleType(), $article);
//    $articles = $this->getRepository('Article')->findAllOrderedByDescription();


//    \Doctrine\Common\Util\Debug::dump($articles);
//    $arr = $articles->getTags();
//    \Doctrine\Common\Util\Debug::dump($arr);

//    \Doctrine\Common\Util\Debug::dump($articles);
    $form = $this->createForm(new ArticleType(), $article);
    $form->handleRequest($request);



    if ($form->isValid()) {
      $this->strategy($form);
    }
//      $reqArticle = $form->getData();
////      \Doctrine\Common\Util\Debug::dump($reqArticle);
//      $articleDb = $this->getRepository('Article')->findOneById($reqArticle->getId());
////      \Doctrine\Common\Util\Debug::dump($article);
//      if (!empty($articleDb)) {
//
//        var_dump($reqArticle->getPos());
//        // update $article
//        $articleDb->setDescription($reqArticle->getDescription());
//        $articleDb->setPos($reqArticle->getPos());
//        $reqTags = $reqArticle->getTags(); 
//        foreach($articleDb->getTags() as $key => $tag) {
//          $tag->setName($reqTags[$key]->getName());
//          $em->persist($tag);
//        }
//        $em->persist($articleDb);
//        $em->flush();
//      } else {
//        $reqTags = $reqArticle->getTags();
//        \Doctrine\Common\Util\Debug::dump($reqTags);
//        foreach($article->getTags() as $key => $tag) {
//          $tag->setName($reqTags[$key]->getName());
//          $em->persist($tag);
////          $em->flush();
//        }
//        $em->persist($article);
//        $em->flush();
//      }
//      var_dump(empty($article));

    // ... maybe do some form processing, like saving the Article and Tag objects

    return $this->redirect(
      $this->generateUrl(
        'acme_article_show',
        array()
      )
    );
  }

  /**
   * @return Article
   */
  public function getNewArticle()
  {
    $article = new Article();
    $article->setPos(0);
    $tag1 = new Tag();
    $tag1->name = 'tag1';
    $article->getTags()->add($tag1);
    $tag2 = new Tag();
    $tag2->name = 'tag2';
    $article->getTags()->add($tag2);

    return $article;
  }
}
