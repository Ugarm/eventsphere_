<?php

namespace App\Controller\API;

use App\Managers\AttendeeManager;
use App\Managers\EventManager;
use App\Repository\EventRepository;
use App\Repository\MeetupRepository;
use App\Repository\UserRepository;
use App\Services\ErrorHandler;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

class EventController extends AbstractController
{
    public function __Construct(
        private readonly EventManager          $eventManager,
        private readonly AttendeeManager       $attendeeManager,
        private readonly EventRepository $eventRepository,
        private readonly MeetupRepository $meetupRepository,
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws Exception
     */
    #[Route('/api/events', name: 'app_event')]
    public function createEvent(Request $request): JsonResponse
    {
        try {
            $response = $this->eventManager->createEvent($request);
        } catch (\Exception $e) {
            return ErrorHandler::handleException($e);
        }

        return $response;
    }

    #[Route('/api/event_feedbacks', name: 'app_event_feedback')]
    public function addEventFeedback(Request $request) {}

    #[Route('/api/event_attendees', name: 'app_event_attendees', methods: [Request::METHOD_POST])]
    public function addEventAttendee(Request $request): JsonResponse {
        $eventId    = (int) $request->toArray()['event_id'];
        $meetupId   = (int) $request->toArray()['meetup_id'];
        $userId     = (int) $request->toArray()['user_id'];
        $action     = $request->toArray()['action'];

        try {
            $this->entityManager->beginTransaction();

            $object = $this->meetupRepository->find($meetupId) ?? $this->eventRepository->findOrDie($eventId);
            $this->attendeeManager->objectAttendees(
                object: $object,
                user: $this->userRepository->findOrDie($userId),
                action: $action,
            );

            $this->entityManager->commit();

            $reflectionObject = new ReflectionClass($object);

            return new JsonResponse([
                'Message' => 'User successfully ' . ($action === 'join' ? 'joined' : 'left' ) . ' the ' . lcfirst($reflectionObject->getShortName())
            ]);
        } catch (Throwable $e) {
            $this->entityManager->rollback();

            return ErrorHandler::handleException($e);
        }
    }
}
