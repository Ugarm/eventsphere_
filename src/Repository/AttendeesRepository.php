<?php

namespace App\Repository;

use App\Entity\Attendees;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Attendees>
 */
class AttendeesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attendees::class);
    }

    public function deleteAttendeeById(int $attendeeId): bool
    {
        $entityManager = $this->getEntityManager();
        $attendee = $this->find($attendeeId);

        if ($attendee) {
            try {
                $entityManager->remove($attendee);
                $entityManager->flush();
                return true;
            } catch (\Exception $e) {
                // Handle any exception or logging here
                return false;
            }
        }

        // Return false if attendee not found
        return false;
    }
}
