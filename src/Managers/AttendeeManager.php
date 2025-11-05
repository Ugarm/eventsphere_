<?php

namespace App\Managers;

use App\Entity\Attendees;
use App\Entity\Event;
use App\Entity\Meetup;
use App\Entity\Users;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AttendeeManager extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function objectAttendees(
        Event|Meetup $object,
        Users $user,
        string $action,
    ): void
    {
        if ($action === 'join') {
            $attendee = new Attendees();

            $setter = $object instanceof Meetup
                ? 'setMeetupId'
                : 'setEventId';

            $attendee->$setter($object->getId());
            $attendee->setUsers($user);
            $this->entityManager->persist($attendee);
            $this->entityManager->flush();
        } elseif ($action == 'leave') {
            $this->deleteAttendee($user, $object);
        } else {
            throw new InvalidArgumentException('action ' . $action . ' not alllowed');
        }
    }

    public function deleteAttendee(Users $user, Event|Meetup $object): void
    {
        /** @var Attendees[] $attendeeToDelete */
        $attendeeToDelete = array_filter(
            array: $user->getAttendee()->toArray(),
            callback: static function ($attendee) use ($object) {
                if( $attendee instanceof Attendees) {
                    return $attendee->getEventId() === $object->getId();
                }

                return $attendee->getMeetupId() === $object->getId();
            }
        );

        if (count($attendeeToDelete) !== 1) {
            throw new Exception('Only 1 event can be linked to event ' . $object->getId() . ' with user ' . $user->getId());
        }

        $this->entityManager->remove(array_shift($attendeeToDelete));
        $this->entityManager->flush();
    }
}