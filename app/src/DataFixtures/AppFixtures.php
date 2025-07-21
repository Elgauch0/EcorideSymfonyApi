<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Vehicle;
use App\Entity\Itinerary;
use App\Entity\Reservation;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private  UserPasswordHasherInterface $userPasswordHasher;
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // --- NOUVEAU : Utilisateur Admin ---
        $admin = new User();
        $admin->setFirstname('firstNameAdmin');
        $admin->setLastname('lastNameAdmin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setAdress('61 rue des admins pontoise 95000');
        $admin->setEmail('admin@ecoride.com');
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, "password"));
        $manager->persist($admin);
        // --- NOUVEAU : Utilisateur Employe ---

        $employe = new User();
        $employe->setFirstname('firstNameEmploye');
        $employe->setLastname('lastNameEMploye');
        $employe->setRoles(['ROLE_EMPLOYE']);
        $employe->setAdress('61 rue des employees pontoise 95000');
        $employe->setEmail('employe@ecoride.com');
        $employe->setPassword($this->userPasswordHasher->hashPassword($employe, "password"));
        $manager->persist($employe);

        // --- NOUVEAU : Utilisateur Chauffeur ---

        $chauffeur = new User();
        $chauffeur->setFirstname('Jean');
        $chauffeur->setLastname('Dupont');
        $chauffeur->setRoles(['ROLE_CHAUFFEUR']);
        $chauffeur->setAdress('123 rue des Chauffeurs, 75000 Paris');
        $chauffeur->setEmail('chauffeur@ecoride.com');
        $chauffeur->setPassword($this->userPasswordHasher->hashPassword($chauffeur, "password"));
        $manager->persist($chauffeur);


        // --- NOUVEAU : Utilisateur Chauffeur ------------------------
        $user = new User();
        $user->setFirstname('Marie');
        $user->setLastname('Curie');
        $user->setRoles(['ROLE_USER']);
        $user->setAdress('456 rue des users, 69000 Lyon');
        $user->setEmail('user@ecoride.com');
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
        $manager->persist($user);




        //// --- NOUVEAU : Vehicule crée ---------------------------

        $vehicle = new Vehicle();
        $vehicle->setDriver($chauffeur);
        $vehicle->setLicencePlate('AZ-123-ER');
        $vehicle->setRegistrationDate(new \DateTimeImmutable('2022-01-15'));
        $vehicle->setSeatsAvailable(4);
        $vehicle->setIsSmockingAlowed(false);
        $vehicle->setIsPetsAlowed(true);
        $vehicle->setModel('Toyota Prius');
        $vehicle->setIsGreen(true);
        $manager->persist($vehicle);





        //// --- NOUVEAU : itinéraire crée ------------------------------------------------

        $itinerary = new Itinerary();
        $itinerary->setVehicule($vehicle);
        $itinerary->setDuration(270);
        $itinerary->setPrice(45);
        $itinerary->setDatetime(new \DateTimeImmutable('2025-10-10 09:00:00'));
        $itinerary->setIsStarted(false);
        $itinerary->setIsFinished(false);
        $itinerary->setIsCancelled(false);
        $itinerary->setDepartureCity('Paris');
        $itinerary->setArrivalCity('Marseille');
        $itinerary->setPlaces($vehicle->getSeatsAvailable());
        $manager->persist($itinerary);


        ////---Nouveau : Reservation ------------------------------------------------------

        $reservation = new Reservation();
        $reservation->setClientId($user);
        $reservation->setItinerary($itinerary);
        $reservation->setStatus('confirmed');
        $reservation->setSeatsReserved(1);
        $reservation->setIsCancelled(false);
        $manager->persist($reservation);
        $manager->flush();
    }
}
