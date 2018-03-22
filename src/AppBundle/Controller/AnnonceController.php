<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Annonce;
use AppBundle\Entity\Reservation;
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
    
    if($request->request->get('style')){
        $style = $request->request->get('style');
        dump($style);
    }
    else{
        $style = 'bootstrap';
    }
    
    $breadcrumbs = $this->get("white_october_breadcrumbs");
    $user = $this->getUser();
    // Simple example
    $breadcrumbs->addItem("Annonce", $this->get("router")->generate("View_all_annonce_route"));

 
        // La variable annonce prend toute les annonces récupérer par Doctrine
        $annonces = $this->getDoctrine()->getRepository('AppBundle:Annonce')->findAll();

//        echo "<pre>";
//        print_r($annonces);
//        echo "</pre>";

        // Affiche la page index.html.twig 
        return $this->render('pages/index.html.twig', [
          'annonces' => $annonces,
          'style' => $style]);
    }

     /**
     * @Route("/create", name="create_annonce_route")
     */
    public function createAnnonceAction(Request $request)
    {

     if($request->request->get('style')){
        $style = $request->request->get('style');
        dump($style);
    }
    else{
        $style = 'bootstrap';
    }

    $breadcrumbs = $this->get("white_october_breadcrumbs");
    $user = $this->getUser();
    // Simple example
    $breadcrumbs->addItem("Annonce", $this->get("router")->generate("View_all_annonce_route"));
    $breadcrumbs->addItem("Créer", $this->get("router")->generate("create_annonce_route"));


       $user = $this->getUser();
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
          $annonce->setIdUser($user->getId());

//Envoi de l'entité dans Doctrine qui la créer dans la BDD
       		$em = $this->getDoctrine()->getManager();
       		$em->persist($annonce);
       		$em->flush();

       		$this->addFlash('message', 'Annonce créée avec succès !');

       		return $this->redirectToRoute('View_all_annonce_route');
       	
       }
        return $this->render('pages/create.html.twig', [
        	'form' => $form->createView(),
          'style' => $style
    ]);
    }


     /**
     * @Route("/edit/{id}", name="edit_annonce_route")
     */
    public function editAnnonceAction(Request $request, $id)
    {

       if($request->request->get('style')){
        $style = $request->request->get('style');
        dump($style);
      }
      else{
        $style = 'bootstrap';
      }

    $breadcrumbs = $this->get("white_october_breadcrumbs");
    $user = $this->getUser();
    // Simple example
    $breadcrumbs->addItem("Annonce", $this->get("router")->generate("View_all_annonce_route"));
    $breadcrumbs->addItem("Modifier");

    //Récupère les données de l'annonce dans la BDD
    $user = $this->getUser();
 		$annonces = $this->getDoctrine()->getRepository('AppBundle:Annonce')->find($id);

    if (($annonces->getIdUser()) == ($user->getId())) {
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
                  'form' => $form->createView(),
                  'style' => $style
          ]);
       }
       else {
          $this->addFlash('refus', 'Autorisation refusée !');
       }
        return $this->redirectToRoute('View_all_annonce_route');

       
    }


     /**
     * @Route("/view/{id}", name="view_annonce_route")
     */
    public function viewAnnonceAction($id,Request $request)
    {

      $style = 'bootstrap';
    	
    $breadcrumbs = $this->get("white_october_breadcrumbs");
    $user = $this->getUser();
    // Simple example
    $breadcrumbs->addItem("Annonce", $this->get("router")->generate("View_all_annonce_route"));
    $breadcrumbs->addItem("Voir");

    	$annonces = $this->getDoctrine()->getRepository('AppBundle:Annonce')->find($id);
    //	echo '<pre>';
    //	print_r($annonces);
    //	echo'</pre>';
    //	exit();
        return $this->render('pages/view.html.twig', [
        	'id'=> $id,
        	'annonce' => $annonces,
          'style' => $style ]);
    }

        /**
     * @Route("/delete/{id}", name="delete_annonce_route")
     */
    public function deleteAnnonceAction($id)
    {



    	$em =$this->getDoctrine()->getManager();
    	$annonce = $em->getRepository('AppBundle:Annonce')->find($id);
      $user = $this->getUser();

      
      if (($annonce->getIdUser()) == ($user->getId())) {
        $em->remove($annonce);
        //"Met à jour la BDD"
        $em->flush();
        $this->addFlash('message', 'Annonce Supprimée !');
      }
      else{
        $this->addFlash('refus', 'Autorisation refusée !');

      }
    	
        return $this->redirectToRoute('View_all_annonce_route');
    }


        /**
     * @Route("/createR/{id}", name="create_reservation_route")
     */
    public function createReservationAction($id)
    {

      $em =$this->getDoctrine()->getManager();
      $annonce = $em->getRepository('AppBundle:Annonce')->find($id);
      $user = $this->getUser();

      if (($annonce->getIdUser()) != ($user->getId())) {

        $reservation = new Reservation;
        //changeIdGeneratorType($em,$reservation);
        $reservation->setDatereservation(new \DateTime());
        $reservation->setIdAnnonce($annonce->getId());
        $reservation->setIdUser($user->getId());

        //echo '<pre>';
        //print_r($reservation);
        //print_r($user->getId());
        //echo'</pre>';

        $em = $this->getDoctrine()->getManager();
        $em->persist($reservation);
        $em->flush();

        $this->addFlash('message', 'Réservé !');
      }
      else{
        $this->addFlash('refus', 'Autorisation refusée !');

      }
      
        return $this->redirectToRoute('View_all_annonce_route');
    }


}
