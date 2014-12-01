<?php

namespace ICup\Bundle\PublicSiteBundle\Controller\General;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ICup\Bundle\PublicSiteBundle\Services\Doctrine\TournamentSupport;
use ICup\Bundle\PublicSiteBundle\Entity\Contact;
use DateTime;
use Swift_Message;

class FrontpageController extends Controller
{
    /**
     * @Route("/", name="_icup")
     * @Template("ICupPublicSiteBundle:General:frontpage.html.twig")
     */
    public function rootAction()
    {
        $tournaments = $this->get('logic')->listAvailableTournaments();
        $tournamentList = array();
        $keyList = array(
            TournamentSupport::$TMNT_ENROLL => 'enroll',
            TournamentSupport::$TMNT_GOING => 'active',
            TournamentSupport::$TMNT_DONE => 'done'
        );
        $statusList = array(
            'enroll' => array(),
            'active' => array(),
            'done' => array()
        );
        $today = new DateTime();
        foreach ($tournaments as $tournament) {
            $stat = $this->get('tmnt')->getTournamentStatus($tournament->getId(), $today);
            if ($stat != TournamentSupport::$TMNT_HIDE) {
                $tournamentList[$tournament->getId()] = array('tournament' => $tournament, 'status' => $stat);
                $statusList[$keyList[$stat]][] = $tournament;
            }
        }

        $form = $this->makeContactForm(new Contact());
        $request = $this->getRequest();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->sendMail($form->getData());
        }
        return array('form' => $form->createView(), 'tournaments' => $tournamentList, 'statuslist' => $statusList);
    }
    
    private function makeContactForm(Contact $contact) {
        $formDef = $this->createFormBuilder($contact);
        $formDef->add('name', 'text', array('label' => 'FORM.FRONTPAGE.NAME', 'phonestyle' => true, 'required' => false, 'disabled' => false, 'translation_domain' => 'club'));
        $formDef->add('club', 'text', array('label' => 'FORM.FRONTPAGE.CLUB', 'phonestyle' => true, 'required' => false, 'disabled' => false, 'translation_domain' => 'club'));
        $formDef->add('phone', 'text', array('label' => 'FORM.FRONTPAGE.PHONE', 'phonestyle' => true, 'required' => false, 'disabled' => false, 'translation_domain' => 'club'));
        $formDef->add('email', 'text', array('label' => 'FORM.FRONTPAGE.EMAIL', 'phonestyle' => true, 'required' => false, 'disabled' => false, 'translation_domain' => 'club'));
        $formDef->add('msg', 'textarea', array('label' => 'FORM.FRONTPAGE.MSG', 'phonestyle' => true, 'required' => false, 'disabled' => false, 'translation_domain' => 'club'));
        $formDef->add('send', 'submit', array('label' => 'FORM.FRONTPAGE.SUBMIT',
                                                'translation_domain' => 'club',
                                                'icon' => 'fa fa-envelope'));
        return $formDef->getForm();
    }
    
    private function sendMail(Contact $contact) {
        $from = array($this->container->getParameter('mailer_user') => "icup.dk support");
        $admins = $this->get('logic')->listAdminUsers();
        if (count($admins) < 1) {
            $recv = $from;
        }
        else {
            $recv = array($admins[0]->getEmail() => $admins[0]->getName());
        }
        $mailbody = $this->renderView('ICupPublicSiteBundle:Email:infomail.html.twig', $contact->getArray());
        $message = Swift_Message::newInstance()
            ->setSubject($this->get('translator')->trans('FORM.INFOEMAIL.TITLE', array(), 'admin'))
            ->setFrom($from)
            ->setTo($recv)
            ->setBody($mailbody, 'text/html');
        $this->get('mailer')->send($message);
    }
}
