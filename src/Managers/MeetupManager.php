<?php

namespace App\Managers;

use App\DBAL\MeetupType;
use App\Entity\Meetup;
use App\Entity\Users;
use App\Services\DataValidator;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MeetupManager extends AbstractController
{
    private EntityManagerInterface $entityManager;

    private DataValidator $dataValidator;

    public function __construct(EntityManagerInterface      $entityManager,
                                DataValidator               $dataValidator)
    {
        $this->entityManager = $entityManager;
        $this->dataValidator = $dataValidator;
    }

    public function createMeetup($request): JsonResponse {
        $meetupData = json_decode($request->getContent(), true);
        $meetupDate = new \DateTime($meetupData[MeetupType::MEETUP_DATE]);
        $meetup = new Meetup();

        $user = $this->entityManager->getRepository(Users::class)->findOneBy([
            'id' => 103
        ]);

        if (!$user) {
            return new JsonResponse([
                'Message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if (!isset($meetupData[MeetupType::MEETUP_TITLE]) ||
            !isset($meetupData[MeetupType::MEETUP_DESCRIPTION]) ||
            !isset($meetupData[MeetupType::MEETUP_DATE]) ||
            !isset($meetupData[MeetupType::MEETUP_CITY]) ||
            !isset($meetupData[MeetupType::MEETUP_REGION]) ||
            !isset($meetupData[MeetupType::MEETUP_ADDRESS]) ||
            !isset($meetupData[MeetupType::MEETUP_OWNER]) ||
            !isset($meetupData[MeetupType::MEETUP_MIN_PARTICIPANTS]) ||
            !isset($meetupData[MeetupType::MEETUP_MAX_PARTICIPANTS])) {

            return new JsonResponse([
                'Message' => 'missing data',
                'Code' => Response::HTTP_BAD_REQUEST
            ]);
        }

        if ($this->dataValidator->verifyMeetupData($meetupData)){
            $meetup->setTitle($meetupData[MeetupType::MEETUP_TITLE])
                ->setDescription($meetupData[MeetupType::MEETUP_DESCRIPTION])
                ->setDate($meetupDate)
                ->setLocation(($meetupData[MeetupType::MEETUP_LOCATION]))
                ->setCity($meetupData[MeetupType::MEETUP_CITY])
                ->setRegion($meetupData[MeetupType::MEETUP_REGION])
                ->setAddress($meetupData[MeetupType::MEETUP_ADDRESS])
                ->setMaxParticipants($meetupData[MeetupType::MEETUP_MAX_PARTICIPANTS])
                ->setMinParticipants($meetupData[MeetupType::MEETUP_MIN_PARTICIPANTS])
                ->setOwner($user)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());
            // ->setMeetupGpsCoordinates($meetupData['meetup_gps_coordinates']); TODO: Implements Geolocalisation API


            try {
                $this->entityManager->persist($meetup);
                $this->entityManager->flush();
            } catch (Exception $e) {
                return new JsonResponse([
                    'Code' => $e->getCode(),
                    'Message' => $e->getMessage(),
                ]);
            }

            return new JsonResponse([
                'Message' => 'Meetup created successfully',
                'Code' => Response::HTTP_OK,
            ]);
        };

        return new JsonResponse([
            'Message' => 'One or more illegal value(s)',
            'Code' => Response::HTTP_BAD_REQUEST
        ]);
    }
}