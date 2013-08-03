<?php
namespace ICup\Bundle\PublicSiteBundle\Controller\Edit;

use ICup\Bundle\PublicSiteBundle\Entity\Doctrine\Host;
use ICup\Bundle\PublicSiteBundle\Entity\Doctrine\Tournament;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * List the tournaments available
 */
class EditTournamentController extends Controller
{
    /**
     * List the tournaments available
     * @Route("/edit/host/list", name="_edit_host_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("ICupPublicSiteBundle:Edit:listtournament.html.twig")
     */
    public function listAction() {
        $this->get('util')->setupController($this);
        $em = $this->getDoctrine()->getManager();

        $hosts = $em->getRepository('ICup\Bundle\PublicSiteBundle\Entity\Doctrine\Host')
                            ->findAll();
        
        $tournaments = $em->getRepository('ICup\Bundle\PublicSiteBundle\Entity\Doctrine\Tournament')
                            ->findAll();

        $hostList = array();
        foreach ($tournaments as $tournament) {
            $hostList[$tournament->getPid()][] = $tournament;
        }
        
        return array('tournaments' => $hostList, 'hosts' => $hosts);
    }

    /**
     * Add new host
     * @Route("/edit/host/add", name="_edit_host_add")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("ICupPublicSiteBundle:Edit:edithost.html.twig")
     */
    public function addAction() {
        $this->get('util')->setupController($this);
        
        $host = new Host();
        $form = $this->makeHostForm($host);
        return array('form' => $form->createView(), 'action' => 'add', 'host' => $host);
    }
    
    /**
     * Change information of an existing host
     * @Route("/edit/host/chg/{hostid}", name="_edit_host_chg")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("ICupPublicSiteBundle:Edit:edithost.html.twig")
     */
    public function chgAction($hostid) {
        $this->get('util')->setupController($this);
        $em = $this->getDoctrine()->getManager();
        
        $host = $em->getRepository('ICup\Bundle\PublicSiteBundle\Entity\Doctrine\Host')
                            ->find($hostid);
        $form = $this->makeHostForm($host);
        return array('form' => $form->createView(), 'action' => 'chg', 'host' => $host);
    }
    
    /**
     * Remove host from the register - including all related tournaments and match results
     * @Route("/edit/host/del/{hostid}", name="_edit_host_del")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("ICupPublicSiteBundle:Edit:edithost.html.twig")
     */
    public function delAction($hostid) {
        $this->get('util')->setupController($this);
        $em = $this->getDoctrine()->getManager();
        
        $host = $em->getRepository('ICup\Bundle\PublicSiteBundle\Entity\Doctrine\Host')
                            ->find($hostid);
        $form = $this->makeHostForm($host);
        return array('form' => $form->createView(), 'action' => 'del', 'host' => $host);
    }
    
    /**
     * Add, update or remove the host information
     * @Route("/edit/host/{action}", name="_edit_host_post")
     * @Method("POST")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("ICupPublicSiteBundle:Edit:edithost.html.twig")
     */
    public function hostPostAction($action) {
        $this->get('util')->setupController($this);
        $em = $this->getDoctrine()->getManager();

        $form = $this->makeHostForm(array());
        $request = $this->getRequest();
        $form->bind($request);
        if ($form->isValid()) {
            $formData = $form->getData();
            switch ($action) {
                case 'add':
                    $host = new Host();
                    $host->setName($formData['name']);
                    $em->persist($host);
                    break;
                case 'chg':
                    $hostid = $formData['id'];
                    $host = $em->getRepository('ICup\Bundle\PublicSiteBundle\Entity\Doctrine\Host')
                                    ->find($hostid);
                    if ($host != null) {
                        $host->setName($formData['name']);
                        $em->persist($host);
                    }
                    break;
                case 'del':
                    $hostid = $formData['id'];
                    $host = $em->getRepository('ICup\Bundle\PublicSiteBundle\Entity\Doctrine\Host')
                                    ->find($hostid);
                    if ($host != null) {
                        $em->remove($host);
                    }
                    break;
                default:
                    break;
            }
            $em->flush();
        }
        return array('form' => $form->createView(), 'action' => $action, 'host' => $host);
    }
    
    private function makeHostForm($host) {
        $formDef = $this->createFormBuilder($host);
        $formDef->add('id', 'hidden');
        $formDef->add('name', 'text', array('label' => 'FORM.HOST.NAME', 'required' => false));
        $formDef->add('save', 'submit');
        return $formDef->getForm();
    }
    
    /**
     * Add new tournament to a host
     * @Route("/edit/tournament/add/{hostid}", name="_edit_tournament_add")
     * @Template("ICupPublicSiteBundle:Edit:edittournament.html.twig")
     */
    public function addTournamentAction($hostid) {
        $this->get('util')->setupController($this);
        $em = $this->getDoctrine()->getManager();
        
        $host = $em->getRepository('ICup\Bundle\PublicSiteBundle\Entity\Doctrine\Host')->find($hostid);
        if ($host == null) {
            return $this->redirect($this->generateUrl('_edit_host_list'));
        }
                
        $tournament = new Tournament();
        $tournament->setPid($host->getId());
        $form = $this->makeTournamentForm($tournament, 'add');
        $request = $this->getRequest();
        $form->handleRequest($request);
        if ($form->get('cancel')->isClicked()) {
            return $this->redirect($this->generateUrl('_edit_host_list'));
        }
        if ($form->isValid()) {
            $em->persist($tournament);
            $em->flush();
            return $this->redirect($this->generateUrl('_edit_host_list'));
        }
        return array('form' => $form->createView(), 'action' => 'add', 'tournament' => $tournament);
    }
    
    /**
     * Change information of an existing tournament
     * @Route("/edit/tournament/chg/{tournamentid}", name="_edit_tournament_chg")
     * @Template("ICupPublicSiteBundle:Edit:edittournament.html.twig")
     */
    public function chgTournamentAction($tournamentid) {
        $this->get('util')->setupController($this);
        $em = $this->getDoctrine()->getManager();
        
        $tournament = $em->getRepository('ICup\Bundle\PublicSiteBundle\Entity\Doctrine\Tournament')->find($tournamentid);
        if ($tournament == null) {
            return $this->redirect($this->generateUrl('_edit_host_list'));
        }
        
        $form = $this->makeTournamentForm($tournament, 'chg');
        $request = $this->getRequest();
        $form->handleRequest($request);
        if ($form->get('cancel')->isClicked()) {
            return $this->redirect($this->generateUrl('_edit_host_list'));
        }
        if ($form->isValid()) {
            $em->persist($tournament);
            $em->flush();
            return $this->redirect($this->generateUrl('_edit_host_list'));
        }
        return array('form' => $form->createView(), 'action' => 'chg', 'tournament' => $tournament);
    }
    
    /**
     * Remove tournament from the register - all match results and tournament information is lost
     * @Route("/edit/tournament/del/{tournamentid}", name="_edit_tournament_del")
     * @Template("ICupPublicSiteBundle:Edit:edittournament.html.twig")
     */
    public function delTournamentAction($tournamentid) {
        $this->get('util')->setupController($this);
        $em = $this->getDoctrine()->getManager();
        
        $tournament = $em->getRepository('ICup\Bundle\PublicSiteBundle\Entity\Doctrine\Tournament')->find($tournamentid);
        if ($tournament == null) {
            return $this->redirect($this->generateUrl('_edit_host_list'));
        }
        
        $form = $this->makeTournamentForm($tournament, 'del');
        $request = $this->getRequest();
        $form->handleRequest($request);
        if ($form->get('cancel')->isClicked()) {
            return $this->redirect($this->generateUrl('_edit_host_list'));
        }
        if ($form->isValid()) {
            $em->remove($tournament);
            $em->flush();
            return $this->redirect($this->generateUrl('_edit_host_list'));
        }
        return array('form' => $form->createView(), 'action' => 'del', 'tournament' => $tournament);
    }
    
    private function makeTournamentForm($tournament, $action) {
        $formDef = $this->createFormBuilder($tournament);
//        $formDef->setAction($this->generateUrl('_edit_tournament_post', array('action' => $action)));
//        $formDef->add('id', 'hidden', array('mapped' => false));
//        $formDef->add('pid', 'hidden', array('mapped' => false));
        $formDef->add('name', 'text', array('label' => 'FORM.TOURNAMENT.NAME', 'required' => false));
        $formDef->add('key', 'text', array('label' => 'FORM.TOURNAMENT.KEY', 'required' => false));
        $formDef->add('edition', 'text', array('label' => 'FORM.TOURNAMENT.EDITION', 'required' => false));
        $formDef->add('cancel', 'submit', array('label' => 'FORM.TOURNAMENT.CANCEL.'.strtoupper($action)));
        $formDef->add('save', 'submit', array('label' => 'FORM.TOURNAMENT.SUBMIT.'.strtoupper($action)));
        return $formDef->getForm();
    }
}
