<?php
namespace ICup\Bundle\PublicSiteBundle\Controller\Admin\Tournament;

use ICup\Bundle\PublicSiteBundle\Entity\Doctrine\Date;
use ICup\Bundle\PublicSiteBundle\Entity\Doctrine\Match;
use ICup\Bundle\PublicSiteBundle\Entity\Doctrine\QMatchRelation;
use ICup\Bundle\PublicSiteBundle\Entity\Doctrine\User;
use ICup\Bundle\PublicSiteBundle\Entity\Doctrine\Group;
use ICup\Bundle\PublicSiteBundle\Services\Doctrine\MatchSupport;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use ICup\Bundle\PublicSiteBundle\Entity\QMatch as MatchForm;
use Symfony\Component\HttpFoundation\Request;
use DateTime;

/**
 * Maintain qualifying match prerequisites
 */
class QMatchRelationController extends Controller
{
    /**
     * Change prerequisites of an existing qualifying match
     * @Route("/edit/qmatchrel/chg/{matchid}", name="_edit_qmatchrel_chg")
     * @Template("ICupPublicSiteBundle:Host:editmatchrelation.html.twig")
     */
    public function chgAction($matchid, Request $request) {
        /* @var $utilService Util */
        $utilService = $this->get('util');
        $returnUrl = $utilService->getReferer();

        /* @var $user User */
        $user = $utilService->getCurrentUser();
        /* @var $match Match */
        $match = $this->get('entity')->getMatchById($matchid);
        $group = $this->get('entity')->getGroupById($match->getPid());
        $category = $this->get('entity')->getCategoryById($group->getPid());
        $tournament = $this->get('entity')->getTournamentById($category->getPid());
        $utilService->validateEditorAdminUser($user, $tournament->getPid());

        $matchForm = $this->copyMatchForm($match);
        $form = $this->makeMatchForm($matchForm, $category->getId(), $group->getClassification(), 'chg');
        $form->handleRequest($request);
        if ($form->get('cancel')->isClicked()) {
            return $this->redirect($returnUrl);
        }
        if ($this->checkForm($form, $matchForm)) {
            $this->chgMatch($matchForm, $match);
            return $this->redirect($returnUrl);
        }
        $playground = $this->get('entity')->getPlaygroundById($match->getPlayground());
        return array('form' => $form->createView(),
                     'category' => $category,
                     'match' => $match,
                     'playground' => $playground,
                     'action' => 'chg',
                     'schedule' => DateTime::createFromFormat(
                                        $this->container->getParameter('db_date_format').
                                        '-'.
                                        $this->container->getParameter('db_time_format'),
                                        $match->getDate().'-'.$match->getTime()));
    }
    
    /**
     * Remove match relations from the register - including related match result
     * @deprecated
     * @Route("/edit/qmatchrel/del/{matchid}", name="_edit_qmatchrel_del")
     * @Template("ICupPublicSiteBundle:Host:editmatchrelation.html.twig")
     */
    public function delAction($matchid, Request $request) {
        /* @var $utilService Util */
        $utilService = $this->get('util');
        $returnUrl = $utilService->getReferer();

        /* @var $user User */
        $user = $utilService->getCurrentUser();
        $match = $this->get('entity')->getMatchById($matchid);
        $group = $this->get('entity')->getGroupById($match->getPid());
        $category = $this->get('entity')->getCategoryById($group->getPid());
        $tournament = $this->get('entity')->getTournamentById($category->getPid());
        $utilService->validateEditorAdminUser($user, $tournament->getPid());

        $matchForm = $this->copyMatchForm($match);
        $form = $this->makeMatchForm($matchForm, $category->getId(), $group->getClassification(), 'del');
        $form->handleRequest($request);
        if ($form->get('cancel')->isClicked()) {
            return $this->redirect($returnUrl);
        }
        if ($form->isValid()) {
            $this->delMatch($match);
            return $this->redirect($returnUrl);
        }
        $playground = $this->get('entity')->getPlaygroundById($match->getPlayground());
        return array('form' => $form->createView(),
                     'category' => $category,
                     'match' => $match,
                     'playground' => $playground,
                     'action' => 'del',
                     'schedule' => DateTime::createFromFormat(
                                        $this->container->getParameter('db_date_format').
                                        '-'.
                                        $this->container->getParameter('db_time_format'),
                                        $match->getDate().'-'.$match->getTime()));
    }

    private function chgMatch(MatchForm $matchForm, Match $match) {
        $em = $this->getDoctrine()->getManager();
        $homeRel = $this->get('match')->getQMatchRelationByMatch($match->getId(), MatchSupport::$HOME);
        if ($homeRel == null) {
            $homeRel = new QMatchRelation();
            $homeRel->setPid($matchForm->getId());
            $homeRel->setAwayteam(false);
            $em->persist($homeRel);
        }
        $homeRel->setCid($matchForm->getGroupA());
        $homeRel->setRank($matchForm->getRankA());
        $awayRel = $this->get('match')->getQMatchRelationByMatch($match->getId(), MatchSupport::$AWAY);
        if ($awayRel == null) {
            $awayRel = new QMatchRelation();
            $awayRel->setPid($matchForm->getId());
            $awayRel->setAwayteam(true);
            $awayRel->setCid($matchForm->getGroupB());
            $awayRel->setRank($matchForm->getRankB());
            $em->persist($awayRel);
        }
        $awayRel->setCid($matchForm->getGroupB());
        $awayRel->setRank($matchForm->getRankB());
        $em->flush();
    }

    private function delMatch(Match $match) {
        $em = $this->getDoctrine()->getManager();
        $qhomeRel = $this->get('match')->getQMatchRelationByMatch($match->getId(), MatchSupport::$HOME);
        if ($qhomeRel != null) {
            $em->remove($qhomeRel);
        }
        $qawayRel = $this->get('match')->getQMatchRelationByMatch($match->getId(), MatchSupport::$AWAY);
        if ($qawayRel != null) {
            $em->remove($qawayRel);
        }
        $em->flush();
    }
    
    private function copyMatchForm(Match $match) {
        $matchForm = new MatchForm();
        $matchForm->setId($match->getId());
        $matchForm->setPid($match->getPId());
        $matchForm->setMatchno($match->getMatchno());
        $matchdate = Date::getDateTime($match->getDate(), $match->getTime());
        $dateformat = $this->get('translator')->trans('FORMAT.DATE');
        $matchForm->setDate(date_format($matchdate, $dateformat));
        $timeformat = $this->get('translator')->trans('FORMAT.TIME');
        $matchForm->setTime(date_format($matchdate, $timeformat));
        $matchForm->setPlayground($match->getPlayground());
        $homeRel = $this->get('match')->getQMatchRelationByMatch($match->getId(), false);
        if ($homeRel != null) {
            $matchForm->setGroupA($homeRel->getCid());
            $matchForm->setRankA($homeRel->getRank());
        }
        $awayRel = $this->get('match')->getQMatchRelationByMatch($match->getId(), true);
        if ($awayRel != null) {
            $matchForm->setGroupB($awayRel->getCid());
            $matchForm->setRankB($awayRel->getRank());
        }
        return $matchForm;
    }

    private function makeMatchForm(MatchForm $matchForm, $categoryid, $classification, $action) {
        $groups = $this->get('logic')->listGroupsByCategory($categoryid);
        $groupnames = array();
        foreach ($groups as $group) {
            if ($group->getClassification() < $classification && $group->getClassification() < Group::$BRONZE) {
                $groupnames[$group->getId()] =
                    $this->get('translator')->trans('GROUP', array(), 'tournament')." ".
                    $group->getName();
            }
        }

        $show = $action != 'del';
        
        $formDef = $this->createFormBuilder($matchForm);
        $formDef->add('groupA', 'choice', array('label' => 'FORM.MATCH.QHOME.GROUP',
            'choices' => $groupnames, 'empty_value' => 'FORM.MATCH.DEFAULT',
            'required' => false, 'disabled' => !$show, 'translation_domain' => 'admin'));
        $formDef->add('rankA', 'text', array('label' => 'FORM.MATCH.QHOME.RANK',
            'required' => false, 'disabled' => !$show, 'translation_domain' => 'admin'));
        $formDef->add('groupB', 'choice', array('label' => 'FORM.MATCH.QAWAY.GROUP',
            'choices' => $groupnames, 'empty_value' => 'FORM.MATCH.DEFAULT',
            'required' => false, 'disabled' => !$show, 'translation_domain' => 'admin'));
        $formDef->add('rankB', 'text', array('label' => 'FORM.MATCH.QAWAY.RANK',
            'required' => false, 'disabled' => !$show, 'translation_domain' => 'admin'));

        $formDef->add('cancel', 'submit', array('label' => 'FORM.MATCH.CANCEL.'.strtoupper($action),
                                                'translation_domain' => 'admin',
                                                'buttontype' => 'btn btn-default',
                                                'icon' => 'fa fa-times'));
        $formDef->add('save', 'submit', array('label' => 'FORM.MATCH.SUBMIT.'.strtoupper($action),
                                                'translation_domain' => 'admin',
                                                'icon' => 'fa fa-check'));
        return $formDef->getForm();
    }
    
    private function checkForm($form, MatchForm $matchForm) {
        if (!$form->isValid()) {
            return false;
        }
        if ($matchForm->getGroupA() == null) {
            $form->addError(new FormError($this->get('translator')->trans('FORM.MATCH.NOHOMEGROUP', array(), 'admin')));
        }
        if ($matchForm->getGroupB() == null) {
            $form->addError(new FormError($this->get('translator')->trans('FORM.MATCH.NOAWAYGROUP', array(), 'admin')));
        }
        if ($matchForm->getRankA() == null || trim($matchForm->getRankA()) == '') {
            $form->addError(new FormError($this->get('translator')->trans('FORM.MATCH.NOHOMERANK', array(), 'admin')));
        }
        if ($matchForm->getRankB() == null || trim($matchForm->getRankB()) == '') {
            $form->addError(new FormError($this->get('translator')->trans('FORM.MATCH.NOAWAYRANK', array(), 'admin')));
        }
        elseif ($matchForm->getGroupA() == $matchForm->getGroupB() &&
                $matchForm->getRankA() == $matchForm->getRankB()) {
            $form->addError(new FormError($this->get('translator')->trans('FORM.MATCH.SAMEQ', array(), 'admin')));
        }
        return $form->isValid();
    }
}
