<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }


    /**
     * @Route("/user/test", name="TestRoleUser")
     */
    public function testRoleUserAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('/Exemples_Roles/hello_world.html.twig');
    }

      /**
     * @Route("/admin/test", name="TestRoleAdmin")
     */
    public function testRoleAdminAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('/Exemples_Roles/hello_world-admin.html.twig');
    }
}
