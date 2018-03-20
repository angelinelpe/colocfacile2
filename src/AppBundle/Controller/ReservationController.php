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



class ReservationController extends Controller
{
	 /**
     * @Route("/reservation", name="View_all_reservation_route")
     */
    public function showAllReservationAction(Request $request)
    {

      $user = $this->getUser();
      // La variable reservation prend toutes les réservations récupérés par Doctrine
      $reservations = $this->getDoctrine()->getRepository('AppBundle:Reservation')->findAll();

      foreach ($reservations as $key => $value) {

       if($user->getId() = $reservations->getUser()){

        //        echo "<pre>";
        //        print_r($reservations);
        //        echo "</pre>";

        // Affiche la page indexR.html.twig 
        return $this->render('pages/indexR.html.twig', ['reservations' => $reservations]);

      }
      
      }
      

    }



     /**
     * @Route("/viewR/{id}", name="view_reservation_route")
     */
    public function viewReservationAction($id)
    {
    	

    	$reservations = $this->getDoctrine()->getRepository('AppBundle:Reservation')->find($id);
      $annonce = $this->getDoctrine()->getRepository('AppBundle:Annonce')->find($id);

      
    //	echo '<pre>';
    //	print_r($reservations);
    //	echo'</pre>';
    //	exit();
        return $this->render('pages/viewR.html.twig', [
        	'id'=> $id,
        	'reservation' => $reservations,
          'annonce' => $annonce ]);
    }

        /**
     * @Route("/deleteR/{id}", name="delete_reservation_route")
     */
    public function deleteReservationAction($id)
    {


    	$em =$this->getDoctrine()->getManager();
    	$reservation = $em->getRepository('AppBundle:Reservation')->find($id);
      $user = $this->getUser();

      
      if (($reservation->getIdUser()) == ($user->getId())) {
        $em->remove($reservation);
        //"Met à jour la BDD"
        $em->flush();
        $this->addFlash('message', 'Réservation Supprimée !');
      }
      else{
        $this->addFlash('refus', 'Autorisation refusée !');

      }
    	
        return $this->redirectToRoute('View_all_reservation_route');
    }


}
