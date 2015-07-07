<?php
namespace ICup\Bundle\PublicSiteBundle\Controller\Admin\Overview;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ICup\Bundle\PublicSiteBundle\Services\Util;
use ICup\Bundle\PublicSiteBundle\Entity\Doctrine\User;

/**
 * List the events scheduled for a tournament
 */
class ListEventController extends Controller
{
    /**
     * List the events available for a tournament
     * @Route("/edit/event/list/{tournamentid}", name="_edit_event_list")
     * @Method("GET")
     * @Template("ICupPublicSiteBundle:Host:listevents.html.twig")
     */
    public function listAction($tournamentid) {
        /* @var $utilService Util */
        $utilService = $this->get('util');
        
        /* @var $user User */
        $user = $utilService->getCurrentUser();
        $tournament = $this->get('entity')->getTournamentById($tournamentid);
        $utilService->validateEditorAdminUser($user, $tournament->getPid());

        $host = $this->get('entity')->getHostById($tournament->getPid());
        $events = $this->get('tmnt')->listEventsByTournament($tournamentid);
        usort($events,
            function ($event1, $event2) {
                if ($event1['schedule'] == $event2['schedule']) {
                    return 0;
                }
                elseif ($event1['schedule'] > $event2['schedule']) {
                    return 1;
                }
                else {
                    return -1;
                }
            }
        );
        return array('host' => $host,
                     'tournament' => $tournament,
                     'eventlist' => $events
                );
    }
}
