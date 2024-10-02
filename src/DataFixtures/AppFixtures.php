<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\User;
use App\Entity\JobOffer;
use App\Entity\LinkedInMessage;
use App\Entity\CoverLetter;
use DateTimeImmutable;

class AppFixtures extends Fixture
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadJobOffers($manager);
        $this->loadLinkedInMessages($manager);
        $this->loadCoverLetters($manager);

        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email)
                ->setPassword($this->faker->password)
                ->setFirstName($this->faker->firstName)
                ->setLastName($this->faker->lastName)
                ->setCreatedAt(new DateTimeImmutable($this->faker->dateTimeThisYear->format('Y-m-d H:i:s')))
                ->setUpdatedAt(new DateTimeImmutable($this->faker->dateTimeThisMonth->format('Y-m-d H:i:s')))
                ->setImage($this->faker->imageUrl(200, 200, 'people'))
                ->setRoles(['ROLE_USER']);

            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }
    }

    private function loadJobOffers(ObjectManager $manager): void
    {
        $statuses = ['Envoyée', 'En attente', 'Entretien programmé', 'Refusée', 'Acceptée'];

        for ($i = 0; $i < 20; $i++) {
            $jobOffer = new JobOffer();
            $jobOffer->setTitle($this->faker->jobTitle)
                ->setCompany($this->faker->company)
                ->setLink($this->faker->url)
                ->setLocation($this->faker->city)
                ->setSalary($this->faker->numberBetween(30000, 100000) . '€')
                ->setContactPerson($this->faker->name)
                ->setContactEmail($this->faker->companyEmail)
                ->setApplicationDate(new DateTimeImmutable($this->faker->dateTimeThisYear->format('Y-m-d')))
                ->setStatus($this->faker->randomElement($statuses))
                ->setAppUser($this->getReference('user_' . $this->faker->numberBetween(0, 9)));

            $manager->persist($jobOffer);
            $this->addReference('job_offer_' . $i, $jobOffer);
        }
    }

    private function loadLinkedInMessages(ObjectManager $manager): void
    {
        for ($i = 0; $i < 30; $i++) {
            $message = new LinkedInMessage();
            $message->setContent($this->faker->paragraph)
                ->setCreatedAt(new DateTimeImmutable($this->faker->dateTimeThisYear->format('Y-m-d H:i:s')))
                ->setUpdatedAt(new DateTimeImmutable($this->faker->dateTimeThisMonth->format('Y-m-d H:i:s')))
                ->setJobOffer($this->getReference('job_offer_' . $this->faker->numberBetween(0, 19)))
                ->setAppUser($this->getReference('user_' . $this->faker->numberBetween(0, 9)));

            $manager->persist($message);
        }
    }

    private function loadCoverLetters(ObjectManager $manager): void
    {
        for ($i = 0; $i < 15; $i++) {
            $coverLetter = new CoverLetter();
            $coverLetter->setContent($this->faker->paragraphs(3, true))
                ->setCreatedAt(new DateTimeImmutable($this->faker->dateTimeThisYear->format('Y-m-d H:i:s')))
                ->setUpdatedAt(new DateTimeImmutable($this->faker->dateTimeThisMonth->format('Y-m-d H:i:s')))
                ->setJobOffer($this->getReference('job_offer_' . $this->faker->numberBetween(0, 19)))
                ->setAppUser($this->getReference('user_' . $this->faker->numberBetween(0, 9)));

            $manager->persist($coverLetter);
        }
    }
}