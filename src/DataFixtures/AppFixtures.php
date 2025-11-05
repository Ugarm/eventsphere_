<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Meetup;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $users = [];
        // Create users
        for ($i = 0; $i < 10; $i++) {
            $user = new Users();
            $user->setEmail('test' . $i . '@test.com');
            $user->setRole('ROLE_USER');
            $user->setUsername('Utilisateur ' . $i);
            $user->setPassword('password' . $i);
            $user->setLastname('Lastname' . $i);
            $user->setFirstname('Firstname' . $i);
            $user->setAddress($i . ' Rue de la Paix');
            $user->setCity('City' . $i);
            $user->setRememberToken('toto');
            $user->setPostalCode('13000');
            $user->setIpAddress('127.0.0.1');

            $now = new \DateTimeImmutable();
            $user->setCreatedAt($now);
            $user->setUpdatedAt($now);
            $manager->persist($user);
            $users[] = $user;
        }

        // Create meetups
        for ($i = 0; $i < 10; $i++) {
            $meetup = new Meetup();
            $meetup->setTitle('Meetup' . $i);
            $meetup->setDate(new \DateTimeImmutable());
            $meetup->setCity('Paris');
            $meetup->setRegion('Île-de-France' . $i);
            $meetup->setAddress($i . ' Boulevard de Sébastopol');
            $meetup->setLocation('Le Petit Club');
            $meetup->setDescription('Tournois Magic au City Wok');
            $meetup->setOwner($users[array_rand($users)]);
            $meetup->setMaxParticipants(10);
            $meetup->setMinParticipants(2);
            $meetup->setCreatedAt(new \DateTimeImmutable());
            $meetup->setUpdatedAt(new \DateTimeImmutable());

            $manager->persist($meetup);
        }

        // Create events
        for ($i = 0; $i < 10; $i++) {
            $event = new Event();
            $event->setTitle('Event' . $i);
            $event->setDate(new \DateTimeImmutable());
            $event->setCity('Paris' . $i);
            $event->setOwner($users[array_rand($users)]);
            $event->setRegion('Nord' . $i);
            $event->setAddress($i . ' Rue de la Place');
            $event->setMaxParticipants(800);
            $event->setLocation('Le Moyen Rex');
            $event->setDescription("Plongez dans un monde de mélodies envoûtantes et de rythmes enivrants lors du Festival Harmonies Éthérées ! Ce week-end promet d'être une célébration exceptionnelle de la musique, réunissant des artistes émergents et des légendes de la scène musicale.");

            // Create attendees collection
//            $attendees = new ArrayCollection();
//
//            for ($j = 0; $j < 10; $j++) {
//                $attendee = new Attendees();
//                // Set the user for this attendee (using user references)
//                $userReference = $manager->getRepository(Users::class)->find($j + 1);
//                $attendee->setUserId($userReference);
//
//                $attendees->add($attendee);
//                $manager->persist($attendee);
//            }
//
//            $event->setAttendees($attendees);
            $event->setMaxParticipants(800);
            $event->setCreatedAt(new \DateTimeImmutable());
            $event->setUpdatedAt(new \DateTimeImmutable());

            $manager->persist($event);
        }

        $manager->flush();
    }
}
