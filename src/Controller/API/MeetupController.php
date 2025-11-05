<?php

namespace App\Controller\API;

use App\Services\ErrorHandler;
use Exception;
use App\Managers\MeetupManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class MeetupController extends AbstractController
{
    private MeetupManager $meetupManager;

    public function __Construct(
        MeetupManager          $meetupManager,
    ) {
        $this->meetupManager = $meetupManager;
    }

    /**
     * @throws Exception
     */
    #[Route('/api/meetup', name: 'app_meetup', methods: ['POST'])]
    public function newMeetup(Request $request): JsonResponse
    {
        // Execute meetup creation method
        try {
            return $this->meetupManager->createMeetup($request);
        } catch (Exception $e) {
            return ErrorHandler::handleException($e);
        }
    }
}
