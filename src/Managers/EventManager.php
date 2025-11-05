<?php

namespace App\Managers;

use App\DBAL\EventType;
use App\Entity\Event;
use App\Entity\Users;
use App\Services\DataValidator;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class EventManager extends AbstractController
{
    private EntityManagerInterface $entityManager;

    private DataValidator $dataValidator;

    public function __construct(EntityManagerInterface      $entityManager,
                                DataValidator               $dataValidator)
    {
        $this->entityManager = $entityManager;
        $this->dataValidator = $dataValidator;
    }

    public function createEvent($request): JsonResponse {
        $eventData = json_decode($request->getContent(), true);
        $eventDate = new \DateTime($eventData[EventType::EVENT_DATE]);
        $event = new Event();

        $user = $this->entityManager->getRepository(Users::class)->findOneBy([
            'id' => 103
        ]);

        if (!$user) {
            return new JsonResponse([
                'Message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if (!isset($eventData[EventType::EVENT_TITLE]) ||
            !isset($eventData[EventType::EVENT_DESCRIPTION]) ||
            !isset($eventData[EventType::EVENT_DATE]) ||
            !isset($eventData[EventType::EVENT_CITY]) ||
            !isset($eventData[EventType::EVENT_REGION]) ||
            !isset($eventData[EventType::EVENT_ADDRESS]) ||
            !isset($eventData[EventType::EVENT_OWNER]) ||
            !isset($eventData[EventType::EVENT_MAX_PARTICIPANTS])) {

            return new JsonResponse([
                'Message' => 'missing data',
                'Code' => Response::HTTP_BAD_REQUEST
            ]);
        }

        if ($this->dataValidator->verifyEventData($eventData)){
            $event->setTitle($eventData[EventType::EVENT_TITLE])
                ->setDescription($eventData[EventType::EVENT_DESCRIPTION])
                ->setDate($eventDate)
                ->setLocation(($eventData[EventType::EVENT_LOCATION]))
                ->setCity($eventData[EventType::EVENT_CITY])
                ->setRegion($eventData[EventType::EVENT_REGION])
                ->setAddress($eventData[EventType::EVENT_ADDRESS])
                ->setMaxParticipants($eventData[EventType::EVENT_MAX_PARTICIPANTS])
                ->setOwner($user)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());
            // ->setEventGpsCoordinates($eventData['event_gps_coordinates']); TODO: Implements Geolocalisation API


            try {
                $this->entityManager->persist($event);
                $this->entityManager->flush();
            } catch (Exception $e) {
                return new JsonResponse([
                    'Code' => $e->getCode(),
                    'Message' => $e->getMessage(),
                ]);
            }

            return new JsonResponse([
                'Message' => 'Event created successfully',
                'Code' => Response::HTTP_OK,
            ]);
        };

        return new JsonResponse([
            'Message' => 'One or more illegal value(s)',
            'Code' => Response::HTTP_BAD_REQUEST
        ]);
    }
}