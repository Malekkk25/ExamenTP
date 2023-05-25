<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\InscriptionType;


class ProductController extends AbstractController
{
    private $entityManager;

    public function __construct(ManagerRegistry $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function home()
    {
        $articles = $this->entityManager->getRepository(Product::class)->findAll();
        return $this->render('index.html.twig', ['articles' => $articles]);
    }

    public function save()
    {
        $entityManager = $this->entityManager->getManager();
        $article = new Product();
        $article->setNom('Article 1');
        $article->setPrix(1000);
        $entityManager->persist($article);
        $entityManager->flush();
        return new Response('Article enregistré avec l\'id ' . $article->getId());
    }

    public function show($id)
    {
        $article = $this->entityManager->getRepository(Product::class)->find($id);
        return $this->render('show.html.twig', ['article' => $article]);
    }

    public function new(Request $request) {
      $article = new User();
      $form = $this->createForm(InscriptionType::class,$article);
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()) {
      $article = $form->getData();
      $entityManager = $this->entityManager->getManager();
      $entityManager->persist($article);
      $entityManager->flush();
      return $this->redirectToRoute('article_list');
      }
      return $this->render('new.html.twig',['form' => $form->createView()]);}

  


}
