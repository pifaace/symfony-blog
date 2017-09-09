<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * @Route("/admin/dashboard", name="admin-dashboard")
     */
    public function indexAction()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }
}
