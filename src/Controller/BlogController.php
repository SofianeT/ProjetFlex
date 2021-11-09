<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Cours;
use App\Form\CommentType;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(CoursRepository $repo): Response
    {
        $cours = $repo->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'cours'=>$cours
        ]);
    }

     /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig');
    }
    
     /**
     * @Route("/blog/about", name="about")
     */

    public function about()
    {
        return $this->render('blog/about.html.twig');
    }
     /**
     * @Route("/blog/contact", name="contact")
     */

    public function contact()
    {
        return $this->render('blog/contact.html.twig');
    }
    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function form(Cours $cours = null,Request $request , EntityManagerInterface $manager )
    {
        if(!$cours)
        {
        $cours = new Cours();
        }
        


        $form = $this->createForm(CoursType ::class , $cours);            
       
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if(!$cours->getId())
            {
            $cours->setCreateAt(new \DateTimeImmutable());
            }

            $manager->persist($cours);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id'=> $cours->getId()]);
        }
        return $this->render('blog/create.html.twig',[
            'formCours'=>$form->createView(),
            'editmode'=>$cours->getId() !==null
        ]);
    }


     /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Cours $cours , Request $request , EntityManagerInterface $manager)
     {
         $comment = new Comment();
         $form = $this->createForm(CommentType::class, $comment );

         $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid())
        {
            $comment->setCreateAt(new \DateTime())
                    ->setCours($cours);
           

            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute('blog_show', ['id'=> $cours->getId()]);     

        }   

        return $this->render('blog/show.html.twig',[
            'cours'=>$cours , 
            'commentForm' => $form->createView()
        ]);
      }

   

}
