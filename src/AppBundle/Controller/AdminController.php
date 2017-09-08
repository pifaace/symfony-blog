<?php
/**
 * Created by PhpStorm.
 * User: joassymaxime
 * Date: 08/09/2017
 * Time: 13:44
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * @Route("/admin/login", name="admin-login")
     */
    public function adminLoginAction()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }
}
