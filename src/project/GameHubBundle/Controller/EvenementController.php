<?php

namespace project\GameHubBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use project\GameHubBundle\Entity\Evenement;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use project\GameHubBundle\Form\EvenementType;

class EvenementController extends Controller
{
    public function listAction(Request $request)
    {

        $em=$this->getDoctrine()->getManager();
        if($request->isMethod('POST')&&(($request->get('adresse'))!=null)){

            $evenement=$em->getRepository("projectGameHubBundle:Evenement")->findBy(array('object'=>$request->get('adresse')));
            return $this->render('@projectGameHub/evenement/backlist.html.twig',array('evt'=>$evenement));
        }


        $evenement=$em->getRepository("projectGameHubBundle:Evenement")->findAll();


        return $this->render('@projectGameHub/evenement/backlist.html.twig',array('evt'=>$evenement));
    }
    public function searchAction()
    {
        $em=$this->getDoctrine()->getManager();
        $evenement=$em->getRepository("projectGameHubBundle:Evenement")->findBy();


        return $this->render('@projectGameHub/evenement/backlist.html.twig',array('evt'=>$evenement));
    }
    public function frontlistAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        if($request->isMethod('POST')&&(($request->get('date'))!=null))
        {
            $evenement=$em->getRepository("projectGameHubBundle:Evenement")->findDateDQL($request->get('date'));
            return $this->render('@projectGameHub/evenement/list.html.twig',array('evt'=>$evenement));
        }
        $evenement=$em->getRepository("projectGameHubBundle:Evenement")->findAll();


        return $this->render('@projectGameHub/evenement/list.html.twig',array('evt'=>$evenement));
    }
    public function supprimerAction($id)
    {

        $em=$this->getDoctrine()->getManager();
        $evenement=$em->getRepository("projectGameHubBundle:Evenement")->find($id);
        $em->remove($evenement);
        $em->flush();

        return $this->redirectToRoute("list");
    }

    public function ajoutAction(Request $request)
    {
        $evenement=new Evenement();
        $Form = $this->createForm(EvenementType::class, $evenement);
        $Form->handleRequest($request);
        if($Form->isValid()){

            $em=$this->getDoctrine()->getManager();

            /** @var UploadedFile $path */

            $path = $evenement->getPath();

            // Generate a unique name for the file before saving it
            $pathName = md5(uniqid()).'.'.$path->guessExtension();

            // Move the file to the directory where brochures are stored
            $path->move(
                $this->getParameter('path_directory'),
                $pathName
            );


            $evenement->setPath($pathName);
            $em->persist($evenement);
            $em->flush();
            return $this->redirectToRoute("ajout");
        }
        return $this->render('projectGameHubBundle:evenement:ajout.html.twig',array(
            'form' =>$Form->createView()
        ));
    }
    public function modifierAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $evenement=$em->getRepository("projectGameHubBundle:Evenement")->find($id);
        $evenement->setPath(null);
        $Form = $this->createForm(EvenementType::class, $evenement);
        $Form->handleRequest($request);


        if($Form->isValid()){

            $em=$this->getDoctrine()->getManager();
            /** @var UploadedFile $path */
            $path=$evenement->getPath();
            $pathName= md5(uniqid()).'.'.$path->guessExtension();
            $path->move(
                $this->getParameter('path_directory'),
                $pathName
            );
            $evenement->setPath($pathName);

            $em->persist($evenement);
            $em->flush();
            return $this->redirectToRoute("list");
        }
        return $this->render('projectGameHubBundle:evenement:modifier.html.twig',array(
            'form' =>$Form->createView(),'evt'=>$evenement
        ));
    }

    public function rechercheAction(Request $request)
    {

        $em=$this->getDoctrine()->getManager();

        if($request->isMethod('POST')&&(($request->get('search'))!=null)){

            $evenement=$em->getRepository("projectGameHubBundle:evenement")->findBy(array('object'=>$request->get('search')));
            return $this->render('@projectGameHub/evenement/backlist.html.twig',array('evt'=>$evenement));
        }

        $evenement=$em->getRepository("projectGameHubBundle:evenement")->findAll();


        return $this->render('EspritParcBundle:Modele:recherche.html.twig',array('evt'=>$evenement));



    }
}
