<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Entity\Category;
use App\Repository\ArticleRepository;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo )
    {
        $articles = $repo->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles'=>$articles
        ]);
    }
    /**
     * @Route("/",name="home")
     */

    public function home(){
        return $this->render('blog/home.html.twig');
    }
    /**
     * @Route("/blog/new",name="blog_create")
     * @Route("/blog/{id}/edit",name="blog_edit")
     */
    public function create(Article $article = null , HttpFoundationRequest $request){
        if(!$article){
            $article = new Article(); 
        }
         
         $form = $this->createFormBuilder($article)
                ->add('title',TextType::class)
                ->add('content',TextareaType::class)
                ->add('image')
                ->add('category',EntityType::class,['class'=>Category::class])
                ->add('save',SubmitType::class,array(
                    'label'=>'Create'
                ))
                ->getForm();
                

                    $form->handleRequest($request);
                    if($form->isSubmitted()&& $form->isValid()){
                        $article = $form->getData();
                        if(!$article->getId()){
                            $article->setCreatedAt(new \DateTime());
                        }
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($article);
                        $entityManager->flush();
                   return $this->redirectToRoute('blog_show',['id'=>$article->getId()]);
    }

                    return $this->render('blog/create.html.twig', array(
                        'form'=>$form->createView()));
    }

    // /**
    //  * @Route("/blog/{id}/edit",name="blog_edit")
    //  */
    // public function edit(Article $article = null , HttpFoundationRequest $request, $id){
        
    //         $article = new Article(); 
    //         $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
         
    //      $form = $this->createFormBuilder($article)
    //             ->add('title',TextType::class)
    //             ->add('content',TextareaType::class)
    //             ->add('image')
    //             ->add('category',EntityType::class,['class'=>Category::class])
    //             ->add('save',SubmitType::class,array(
    //                 'label'=>'Create'
    //             ))
    //             ->getForm();
                

    //                 $form->handleRequest($request);
    //                 if($form->isSubmitted()&& $form->isValid()){
                       
    //                     if(!$article->getId()){
    //                         $article->setCreatedAt(new \DateTime());
    //                     }
                        
                        
    //                     $entityManager = $this->getDoctrine()->getManager();
    //                     $entityManager->flush();
    //                return $this->redirectToRoute('blog_show',['id'=>$article->getId()]);
    // }

    //                 return $this->render('blog/edit.html.twig', array(
    //                     'form'=>$form->createView()));
    //     if($request->request->count()>0){
    //         $article = new Article();
    //         $article->setTitle($request->request->get('title'))
    //                 ->setContent($request->request->get('content'))
    //                 ->setImage($request->request->get('image'))
    //                 ->setCreatedAt(new \DateTime());
    //         $manager->getManager()->persist($article);
    //         $manager->getManager()->flush();

    //         return $this->redirectToRoute('blog_show',['id'=>$article->getId()]);

        
    // }

    /**
     * @Route("blog/show/{id}",name="blog_show")
     */
    public function show(Article $article){
        
        return $this->render('blog/show.html.twig',[
            'article'=>$article
        ]);
    }
   
}
