<?php
namespace ICup\Bundle\PublicSiteBundle\Controller\User;

use ICup\Bundle\PublicSiteBundle\Services\Util;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class LoginController extends Controller
{
    /**
     * Login users and administrators
     * @Route("/login", name="_admin_login")
     * @Method("GET")
     */
    public function loginAction()
    {
        /* @var $utilService Util */
        $utilService = $this->get('util');
        $utilService->setupController($this);

        $request = $this->getRequest();
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        
        $twig = 'ICupPublicSiteBundle:Edit:login.html.twig';
        $requestedPath = $request->getSession()->get('_security.secured_area.target_path', '');
        $startpos = strripos($requestedPath, $request->getBaseUrl());
        $basePath = substr($requestedPath, $startpos);
        if ($basePath === $this->generateUrl('_club_enroll_list')) {
            $twig = 'ICupPublicSiteBundle:User:ausr_enroll.html.twig';
        }

        $formDef = $this->createFormBuilder(array('username' => $request->getSession()->get(SecurityContext::LAST_USERNAME)));
        $formDef->setAction($this->generateUrl('_security_check'));
        $formDef->add('username', 'text', array('label' => 'FORM.LOGIN.USERNAME', 'translation_domain' => 'admin', 'required' => false));
        $formDef->add('password', 'password', array('label' => 'FORM.LOGIN.PASSWORD', 'translation_domain' => 'admin', 'required' => false));
        $formDef->add('login', 'submit', array('label' => 'FORM.LOGIN.LOGIN', 'translation_domain' => 'admin'));
        $form = $formDef->getForm();
        $form->handleRequest($request);

        $tournament = $utilService->getTournament($this);
        return $this->render($twig, array(
            'form'          => $form->createView(),
            'tournament'    => $tournament,
            'error'         => $error
        ));
    }

    /**
     * @Route("/login_check", name="_security_check")
     */
    public function securityCheckAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @Route("/logout", name="_admin_logout")
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
    }
}
