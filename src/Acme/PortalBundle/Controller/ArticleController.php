<?php

// src/Acme/PortalBundle/Controller/ArticleController.php
namespace Acme\PortalBundle\Controller;

use Acme\PortalBundle\Entity\Article;
use Acme\PortalBundle\Entity\Tag;
//use Doctrine\Common\Collections\ArrayCollection;
use Acme\PortalBundle\Facade\FacadeControllerInterface;
use Acme\PortalBundle\Facade\RepositoryFacade;
use Acme\PortalBundle\Form\Type\ArticleType;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleController extends Controller implements FacadeControllerInterface
{
  /**
   * @var RepositoryFacade
   */
  protected $facade;

  public function setFacade(ManagerRegistry $doctrine)
  {
    $this->facade = new RepositoryFacade($doctrine, 'AcmePortalBundle');
  }

  public function indexAction()
  {
    return $this->render('AcmePortalBundle:Default:index.html.twig', array('name' => 'test'));
  }

  public function newAction(Request $request) {
    $article = $this->getNewArticle();

    $form = $this->createForm(new ArticleType(), $article);
    $form->handleRequest($request);

    if ($form->isValid()) {
      $article = $form->getData();
      foreach($article->getTags() as $tag) {
        $article->addTag($tag);
//        $article->setClient(null);
        $this->facade->getEm()->persist($tag);
      }
      $this->facade->getEm()->persist($article);
      $this->facade->getEm()->flush();
    }

    $articles = $this->facade->getRepository('Article')->findAllOrderedByDescription();
    
    $form = $form->createView();

    return $this->render('AcmePortalBundle:Article:new.html.twig',
      array(
        'articles' => $articles,
        'form' => $form
      )
    );
  }

  public function showAction() {
    $articles = $this->facade->getRepository('Article')->findAllOrderedByDescription();

    $article = $this->getNewArticle();
    $form = $this->createForm(new ArticleType(), $article);

    $forms = array();
    foreach ($articles as $article) {
      $forms[] = $this->createForm(new ArticleType, $article)->createView();
    }
//    $forms[] = $form->createView();

    return $this->render('AcmePortalBundle:Article:show.html.twig',
      array(
        'articles' => $articles,
        'forms' => $forms
      )
    );
  }

  public function editAction(Request $request)
  {
    $article = $this->getNewArticle();
    $form = $this->createForm(new ArticleType(), $article);

    $form->handleRequest($request);
    
    if ($form->isValid()) {
      $articleReq = $form->getData();
      $articleDb = $this->facade->getRepository('Article')->findById($articleReq->getId());
      if (sizeof($articleDb) > 0) {
        $article = $articleDb[0];
        $article->setDescription($articleReq->getDescription());
        $article->setPos($articleReq->getPos());
        // remove first the tags from array
        foreach($article->getTags() as $tag) {
          $tag->getArticles()->removeElement($article);
          $this->facade->getEm()->persist($tag);
        }
        $this->facade->getEm()->persist($article);
        $tagsReq = $articleReq->getTags();
        // add tag to array
        foreach($tagsReq as $tag) {
          $article->addTag($tag);
          $this->facade->getEm()->persist($tag);
        }
        $this->facade->getEm()->persist($article);
        $this->facade->getEm()->flush();
      }
    }

    return $this->redirect(
      $this->generateUrl(
        'acme_article_show'
      )
    );
  }

  public function deleteAction($id)
  {
    $articleDb = $this->facade->getRepository('Article')->findById($id);
    if (sizeof($articleDb) > 0) {
      $article = $articleDb[0];
      $this->facade->getEm()->remove($article);
      $this->facade->getEm()->flush();
    }

    return $this->redirect(
      $this->generateUrl(
        'acme_article_show'
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
  protected function getNewArticle()
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
