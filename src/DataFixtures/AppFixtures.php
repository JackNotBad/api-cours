<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Invoice;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    // L'encodeur de mot de passe
    // @var UserPasswordHasherInterface
    private $hash;

    public function __construct(UserPasswordHasherInterface $hash) {
        $this->hash = $hash;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($u=0; $u < 10; $u++) { 
            $user = new User();

            $hash = $this->hash->hashPassword($user, 'password');
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setEmail($faker->email());    
            $user->setPassword($hash);    

            $manager->persist($user);
            
                for($c = 0; $c < mt_rand(5, 20); $c++) {
                    $chrono = 1;
                    $customer = new Customer();
                    $customer->setFirstName($faker->firstName());
                    $customer->setLastName($faker->lastName());
                    $customer->setCompany($faker->company());
                    $customer->setEmail($faker->email());
                    $customer->setUser($user);

        
                    $manager->persist($customer);
                    for($i = 0; $i < mt_rand(3, 10); $i++) {
                        $invoice = new Invoice();
                        $invoice->setAmount($faker->randomFloat(2, 150, 5000));
                        $invoice->setSentAt($faker->dateTimeBetween('-6 months'));
                        $invoice->setStatus($faker->randomElement(['SENT', 'PAID', 'CANCELLED']));
                        $invoice->setCustomer($customer);
                        $invoice->setChrono($chrono);
                        $chrono++;
            
            
                        $manager->persist($invoice);
                    }
                }
        }

        $manager->flush();
    }
}
