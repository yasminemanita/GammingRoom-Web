<?php

namespace App\EventSubscriber;

use App\Repository\EvenementRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $evenementRepository;
    private $router;
    private $security;

    public function __construct(
        Security $security,
        EvenementRepository $evenementRepository,
        UrlGeneratorInterface $router
    ) {
        $this->evenementRepository = $evenementRepository;
        $this->router = $router;
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
       /* $evenements = $this->evenementRepository
            ->createQueryBuilder('Evenement')
            ->where('Evenement.datedeb BETWEEN :start and :end OR Evenement.datefin BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;*/
        //TODO: get id from current connected member
        $idM=$this->security->getUser()->getId();
        $evenements = $this->evenementRepository->getEventPart($idM,$start,$end);

        foreach ($evenements as $evenement) {
            // this create the events with your data (here booking data) to fill calendar
            $evenementEvent = new Event(
                $evenement->getNomevent(),
                $evenement->getDatedeb(),
                $evenement->getDatefin() // If the end date is null or not defined, a all day event is created.
            );


            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

            $evenementEvent->setOptions([
                'backgroundColor' => '#20B2AA',
                'borderColor' => '#556B2F',

            ]);
            //'textColor'=>'purple'
            $evenementEvent->addOption(
                'url',
                $this->router->generate('show_EventFront', [
                    'id' => $evenement->getIdevent(),
                ])
            );

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($evenementEvent);
        }
    }
}