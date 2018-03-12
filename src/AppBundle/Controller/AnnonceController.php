<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Annonce;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;



class AnnonceController extends Controller
{
	 /**
     * @Route("/annonce", name="View_all_annonce_route")
     */
    public function showAllAnnonceAction(Request $request)
    {
        // replace this example code with whatever you need
        $annonces = $this->getDoctrine()->getRepository('AppBundle:Annonce')->findAll();
//        echo "<pre>";
//        print_r($annonces);
//        echo "</pre>";
        return $this->render('pages/index.html.twig', ['annonces' => $annonces]);
    }

     /**
     * @Route("/create", name="create_annonce_route")
     */
    public function createAnnonceAction(Request $request)
    {
       $annonce = new Annonce;
       $form = $this->createFormBuilder($annonce)
       ->add('datedebut', DateType::class, array('attr' => array('class' => 'form-control')))
       ->add('ville', TextType::class, array('attr' => array('class' => 'form-control')))
       ->add('nbplace', NumberType::class, array('attr' => array('class' => 'form-control')))
       ->add('prix', NumberType::class, array('attr' => array('class' => 'form-control')))
       ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control')))
       ->add('duree', NumberType::class, array('attr' => array('class' => 'form-control')))
       ->add('save', SubmitType::class, array('label' => 'Créer', 'attr' => array('class' => 'btn btn-primary')))
       ->getForm();

       $form->handleRequest($request);
       if($form->isSubmitted() && $form -> isValid()){
       		$datedebut = $form['datedebut']->getData();
       		$ville = $form['ville']->getData();
       		$nbplace = $form['nbplace']->getData();
       		$prix = $form['prix']->getData();
       		$description = $form['description']->getData();
       		$duree = $form['duree']->getData();

       		$annonce->setDatedebut($datedebut);
       		$annonce->setVille($ville);
       		$annonce->setNbplace($nbplace);
       		$annonce->setprix($prix);
       		$annonce->setDescription($description);
       		$annonce->setDuree($duree);

       		$em = $this->getDoctrine()->getManager();
       		$em->persist($annonce);
       		$em->flush();

       		$this->addFlash('message', 'Annonce créée avec succès !');

       		return $this->redirectToRoute('View_all_annonce_route');
       	
       }
        return $this->render('pages/create.html.twig', [
        	'form' => $form->createView()
    ]);
    }


     /**
     * @Route("/edit/{id}", name="edit_annonce_route")
     */
    public function editAnnonceAction(Request $request, $id)
    {
 		$annonces = $this->getDoctrine()->getRepository('AppBundle:Annonce')->find($id);

 		$annonces->setDatedebut($annonces->getDatedebut());
 		$annonces->setVille($annonces->getVille());
 		$annonces->setNbplace($annonces->getNbplace());
 		$annonces->setPrix($annonces->getPrix());
 		$annonces->setDescription($annonces->getDescription());
 		$annonces->setDuree($annonces->getDuree());

 		$form = $this->createFormBuilder($annonces)
 		->add('datedebut', DateType::class, array('attr' => array('class' => 'form-control')))
       ->add('ville', TextType::class, array('attr' => array('class' => 'form-control')))
       ->add('nbplace', NumberType::class, array('attr' => array('class' => 'form-control')))
       ->add('prix', NumberType::class, array('attr' => array('class' => 'form-control')))
       ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control')))
       ->add('duree', NumberType::class, array('attr' => array('class' => 'form-control')))
       ->add('save', SubmitType::class, array('label' => 'Modifier', 'attr' => array('class' => 'btn btn-primary')))
       ->getForm();

        $form->handleRequest($request);

       if($form->isSubmitted() && $form -> isValid()){

       		$datedebut = $form['datedebut']->getData();
       		$ville = $form['ville']->getData();
       		$nbplace = $form['nbplace']->getData();
       		$prix = $form['prix']->getData();
       		$description = $form['description']->getData();
       		$duree = $form['duree']->getData();

			$em = $this->getDoctrine()->getManager();
       		$em = $em->getRepository('AppBundle:Annonce')->find($id);

       		$annonces->setDatedebut($datedebut);
       		$annonces->setVille($ville);
       		$annonces->setNbplace($nbplace);
       		$annonces->setprix($prix);
       		$annonces->setDescription($description);
       		$annonces->setDuree($duree);

       		$em = $this->getDoctrine()->getManager();

       		$em->flush();
       		$this->addFlash('message', 'Annonce modifiée !');

       		return $this->redirectToRoute('View_all_annonce_route');
       }

        return $this->render('pages/edit.html.twig',[
        	'id' => $id,
    		'form' => $form->createView()
    	]);
    }


     /**
     * @Route("/view/{id}", name="view_annonce_route")
     */
    public function viewAnnonceAction($id)
    {
    	

    	$annonces = $this->getDoctrine()->getRepository('AppBundle:Annonce')->find($id);
    //	echo '<pre>';
    //	print_r($annonces);
    //	echo'</pre>';
    //	exit();
        return $this->render('pages/view.html.twig', [
        	'id'=> $id,
        	'annonce' => $annonces ]);
    }

        /**
     * @Route("/delete/{id}", name="delete_annonce_route")
     */
    public function deleteAnnonceAction($id)
    {
    	$em =$this->getDoctrine()->getManager();
    	$annonce = $em->getRepository('AppBundle:Annonce')->find($id);
    	$em->remove($annonce);
    	$em->flush();
    	$this->addFlash('message', 'Annonce Supprimée !');

        return $this->redirectToRoute('View_all_annonce_route');
    }

}
