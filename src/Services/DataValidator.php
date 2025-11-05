<?php

namespace App\Services;

use App\DBAL\EventType;
use App\DBAL\MeetupType;
use App\DBAL\UserType;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NoSuspiciousCharacters;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Component\Validator\Validation;


class DataValidator
{
    public function verifyMeetupData($data): bool
    {
        $validator = Validation::createValidator();

        if ($eventName = $data[MeetupType::MEETUP_TITLE]) {
            $violations = $validator->validate($eventName, [
                new Length(['min' => 3]),
                new NoSuspiciousCharacters()
            ]);
        }

        if ($this->violationHandler($violations, 'Nom de l\'event : ') === false) {
            $this->violationHandler($violations, 'Nom de l\'event : ');

            return false;
        }

        if ($eventDate = $data[MeetupType::MEETUP_DATE]) {
            $anteriorDate = new DateTimeImmutable();
            $violations = $validator->validate($eventDate, [
                new Length(['min' => 3]),
                new NoSuspiciousCharacters(),
                new GreaterThanOrEqual($anteriorDate->modify('-1 day')->format('Y/m/d'))
            ]);
        }

        if ($this->violationHandler($violations, 'Date de l\'event : ') === false) {
            $this->violationHandler($violations, 'Date de l\'event : ');

            return false;
        }

        if ($eventCity = $data[MeetupType::MEETUP_CITY]) {
            $violations = $validator->validate($eventCity, [
                new Length(['min' => 3]),
                new NoSuspiciousCharacters()
            ]);
        }

        if ($this->violationHandler($violations, 'Terter : ') === false) {
            $this->violationHandler($violations, 'Terter : ');

            return false;
        }

        if ($eventRegion = $data[MeetupType::MEETUP_REGION]) {
            $violations = $validator->validate($eventRegion, [
                new Length(['min' => 3]),
                new NoSuspiciousCharacters()
            ]);
        }

        if ($this->violationHandler($violations, 'Région : ') === false) {
            $this->violationHandler($violations, 'Région : ');

            return false;
        }

        if ($eventAddress = $data[MeetupType::MEETUP_ADDRESS]) {
            $violations = $validator->validate($eventAddress, [
                new Length(['min' => 3]),
                new NoSuspiciousCharacters(),
            ]);
        }

        if ($this->violationHandler($violations, 'Adresse : ') === false) {
            $this->violationHandler($violations, 'Adresse : ');

            return false;
        }

        if ($this->violationHandler($violations, 'Type d\'event : ') === false) {
            $this->violationHandler($violations, 'Type d\'event : ');

            return false;
        }

        if ($eventMaxAttendees = $data[MeetupType::MEETUP_MAX_PARTICIPANTS]) {
            $violations = $validator->validate($eventMaxAttendees, [
                new NoSuspiciousCharacters()
            ]);

            if ($eventMaxAttendees < 2 || $eventMaxAttendees > 1000) {

                return false;
            }
        }

        if ($eventMinAttendees = $data[MeetupType::MEETUP_MIN_PARTICIPANTS]) {
            $violations = $validator->validate($eventMinAttendees, [
                new NoSuspiciousCharacters()
            ]);

            if ($eventMinAttendees < 1 || $eventMinAttendees > 50) {

                return false;
            }
        }


        if ($this->violationHandler($violations, 'Nombre max de participants : ') === false) {
            $this->violationHandler($violations, 'Nombre max de participants : ');

            return false;
        }

        return true;
    }

    public function verifyEventData($data): bool
    {
        $validator = Validation::createValidator();

        if ($eventName = $data[EventType::EVENT_TITLE]) {
            $violations = $validator->validate($eventName, [
                new Length(['min' => 3]),
                new NoSuspiciousCharacters()
            ]);
        }

        if ($this->violationHandler($violations, 'Nom de l\'event : ') === false) {
            $this->violationHandler($violations, 'Nom de l\'event : ');

            return false;
        }

        if ($eventDate = $data[EventType::EVENT_DATE]) {
            $anteriorDate = new DateTimeImmutable();
            $violations = $validator->validate($eventDate, [
                new Length(['min' => 3]),
                new NoSuspiciousCharacters(),
                new GreaterThanOrEqual($anteriorDate->modify('-1 day')->format('Y/m/d'))
            ]);
        }

        if ($this->violationHandler($violations, 'Date de l\'event : ') === false) {
            $this->violationHandler($violations, 'Date de l\'event : ');

            return false;
        }

        if ($eventCity = $data[EventType::EVENT_CITY]) {
            $violations = $validator->validate($eventCity, [
                new Length(['min' => 3]),
                new NoSuspiciousCharacters()
            ]);
        }

        if ($this->violationHandler($violations, 'Terter : ') === false) {
            $this->violationHandler($violations, 'Terter : ');

            return false;
        }

        if ($eventRegion = $data[EventType::EVENT_REGION]) {
            $violations = $validator->validate($eventRegion, [
                new Length(['min' => 3]),
                new NoSuspiciousCharacters()
            ]);
        }

        if ($this->violationHandler($violations, 'Région : ') === false) {
            $this->violationHandler($violations, 'Région : ');

            return false;
        }

        if ($eventAddress = $data[EventType::EVENT_ADDRESS]) {
            $violations = $validator->validate($eventAddress, [
                new Length(['min' => 3]),
                new NoSuspiciousCharacters(),
            ]);
        }

        if ($this->violationHandler($violations, 'Adresse : ') === false) {
            $this->violationHandler($violations, 'Adresse : ');

            return false;
        }

        if ($this->violationHandler($violations, 'Type d\'event : ') === false) {
            $this->violationHandler($violations, 'Type d\'event : ');

            return false;
        }

        if ($eventMaxAttendees = $data[EventType::EVENT_MAX_PARTICIPANTS]) {
            $violations = $validator->validate($eventMaxAttendees, [
                new NoSuspiciousCharacters()
            ]);

            if ($eventMaxAttendees < 2 || $eventMaxAttendees > 1000) {

                return false;
            }
        }

//        if ($eventMinAttendees = $data[EventType::EVENT_MIN_PARTICIPANTS]) {
//            $violations = $validator->validate($eventMinAttendees, [
//                new NoSuspiciousCharacters()
//            ]);
//
//            if ($eventMinAttendees < 1 || $eventMinAttendees > 50) {
//
//                return false;
//            }
//        }


        if ($this->violationHandler($violations, 'Nombre max de participants : ') === false) {
            $this->violationHandler($violations, 'Nombre max de participants : ');

            return false;
        }

        return true;
    }

    public function registrationDataValidation($data): bool
    {
        $validator = Validation::createValidator();

        if ($pw = $data[UserType::PASSWORD]) {
            $violations = $validator->validate($pw, [
                new Length(['min' => 8]),
                new NotCompromisedPassword(),
                new NoSuspiciousCharacters(),
                new PasswordStrength()
            ]);

            $this->violationHandler($violations, ' Mot de passe : ');
        }

        if ($lastname = $data[UserType::LASTNAME]) {
            $violations = $validator->validate($lastname, [
                new Length(['min' => 2]),
                new NoSuspiciousCharacters(),
            ]);

            $this->violationHandler($violations, 'Nom : ');
        }

        if ($firstname = $data[UserType::FIRSTNAME]) {

            $violations = $validator->validate($firstname, [
                new Length(['min' => 2]),
                new NoSuspiciousCharacters(),
            ]);

            $this->violationHandler($violations, ' Prénom : ');
        }

        if ($nickname = $data[UserType::USERNAME]) {

            $violations = $validator->validate($nickname, [
                new Length(['min' => 2]),
                new NoSuspiciousCharacters(),
            ]);

            $this->violationHandler($violations, 'Pseudo : ');
        }

        if ($email = $data[UserType::EMAIL]) {
            $violations = $validator->validate($email, [
                new Length(['min' => 8]),
                new NoSuspiciousCharacters(),
                new Constraints\Email()
            ]);

            $this->violationHandler($violations, 'Email : ');
        }

        if ($city = $data[UserType::CITY]) {
            $violations = $validator->validate($city, [
                new Length(['min' => 2]),
                new NoSuspiciousCharacters(),
            ]);

            $this->violationHandler($violations, 'Ville : ');
        }


        $terms = $data[UserType::LEGAL_TERMS];

        if ($terms !== true) {
            $violations = "Vous devez accepter les conditions générales.";
        }


        return true;
    }

    public function violationHandler($violations, $value): bool
    {
        if (0 !== count($violations)) {
            foreach ($violations as $violation) {

                echo $value . $violation->getMessage().'<br>';
            }

            return false;
        } else {

            return true;
        }
    }
}