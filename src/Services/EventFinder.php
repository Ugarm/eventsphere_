<?php

namespace App\Services;

use App\Entity\Event;
use App\Entity\Meetup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class EventFinder
{
    private EntityManagerInterface $entityManager;
    public function __construct(
        EntityManagerInterface $entityManager,
    ) {
        $this->entityManager = $entityManager;
    }
    public function findEvents($requestContent): JsonResponse {
        $today = new \DateTimeImmutable();

        if ($requestContent['type'] == 'event') {
            try {
                $query = $this->entityManager->createQueryBuilder()
                    ->select('e')
                    ->from(Event::class, 'e')
                    ->where('e.event_date >= :today')
                    ->setParameter('today', $today)
                    ->getQuery();

                $events = $query->getResult();

                return new JsonResponse([
                    "Code" => Response::HTTP_OK,
                    "Events" => $events
                ]);
            } catch (\Exception $e) {

                return new JsonResponse([
                    'Message' => $e->getMessage(),
                    'Code' => $e->getCode()
                ]);
            }

        }

        if ($requestContent['type'] == 'meetup') {
            try {
                $query = $this->entityManager->createQueryBuilder()
                    ->select('e')
                    ->from(Meetup::class, 'e')
                    ->where('e.date >= :today')
                    ->setParameter('today', $today)
                    ->getQuery();

                $meetups = $query->getResult();

                return new JsonResponse([
                    "Code" => Response::HTTP_OK,
                    "Meetups" => $meetups
                ]);
            } catch (\Exception $e) {

                return new JsonResponse([
                    'Message' => $e->getMessage(),
                    'Code' => $e->getCode()
                ]);
            }

        }
    }
}