<?php

namespace Alex\MultisiteBundle\Tests\Functional\DemoApp_Security\DemoBundle\Controller;

use Alex\MultisiteBundle\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TestController extends Controller
{
    /**
     * @Route(name="homepage", paths={
     *     "fr_FR"="/",
     *     "en_GB"="/",
     * })
     * @Template("AlexMultisiteDemoBundle:Test:homepage.html.twig")
     */
    public function homepageAction()
    {
        return array();
    }

    /**
     * @Route(name="login", paths={
     *     "fr_FR"="/connexion",
     *     "en_GB"="/login",
     * })
     * @Template("AlexMultisiteDemoBundle:Test:login.html.twig")
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();
        $error = null;

        if ($request->attributes->has('_security.last_error')) {
            $error = $request->attributes->get('_security.last_error');
        } elseif ($session !== null && $session->has('_security.last_error')) {
            $error = $session->get('_security.last_error');
            $session->remove('_security.last_error');
        }

        return array('error' => $error);
    }


    /**
     * @Route(name="login-check", path="/login-check")
     */
    public function loginCheckAction()
    {
        throw new \LogicException('Should not be executed.');
    }

    /**
     * @Route(name="logout", paths={
     *     "fr_FR"="/deconnexion",
     *     "en_GB"="/logout",
     * })
     */
    public function logoutAction()
    {
        throw new \LogicException('Should not be executed.');
    }
}
