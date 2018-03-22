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
    $breadcrumbs->addItem("Mes réservations");

      $user = $this->getUser();
      // La variable reservation prend toutes les réservations récupérés par Doctrine
      $reservations = $this->getDoctrine()->getRepository('AppBundle:Reservation')->findAll();

      
      return $this->render('pages/indexR.html.twig', ['reservations' => $reservations,
      'user' => $user,
      'style' => $style]);

    }



     /**
     * @Route("/viewR/{id}", name="view_reservation_route")
     */
    public function viewReservationAction($id)
    {

        $style = 'bootstrap';
    

    	$reservations = $this->getDoctrine()->getRepository('AppBundle:Reservation')->find($id);
      $idAnnonce = $reservations->getIdAnnonce();
      $em =$this->getDoctrine()->getManager();
      $annonce = $em->getRepository('AppBundle:Annonce')->find($idAnnonce);

       $breadcrumbs = $this->get("white_october_breadcrumbs");
      $user = $this->getUser();
      // Simple example
      $breadcrumbs->addItem("Annonce", $this->get("router")->generate("View_all_annonce_route"));
      $breadcrumbs->addItem("Mes reservations", $this->get("router")->generate("View_all_reservation_route"));
      $breadcrumbs->addItem($id);

        return $this->render('pages/viewR.html.twig', [
        	'id'=> $id,
        	'reservation' => $reservations,
          'annonce' => $annonce,
          'style' => $style]);
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
