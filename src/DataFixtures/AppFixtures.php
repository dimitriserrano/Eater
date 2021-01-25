<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Restaurant;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadRestaurants($manager);
        $this->loadProducts($manager);

        $manager->flush();
    }

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    private function loadUsers(ObjectManager $manager): void
    {
        foreach ($this->getUserData() as [$firstname, $lastname, $email, $password, $phone, $address, $city, $roles]) {
            $user = new User();
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setPhone($phone);
            $user->setAddress($address);
            $user->setCity($city);
            $user->setRoles($roles);
            $manager->persist($user);
            $this->addReference($lastname, $user);
        }
        $manager->flush();
    }

    private function loadRestaurants(ObjectManager $manager): void
    {
        foreach ($this->getRestaurantData() as [$title, $address, $city, $phone]) {
            $restaurant = new Restaurant();
            $restaurant->setName($title);
            $restaurant->setAddress($address);
            $restaurant->setCity($city);
            $restaurant->setPhone($phone);
            $manager->persist($restaurant);
            $this->addReference($title, $restaurant);
        }
        $manager->flush();
    }

    private function loadProducts(ObjectManager $manager): void
    {
        foreach ($this->getProductData() as [$productName, $productPrice]) {

            $product = new Product();
            $product->setName($productName);
            $product->setPrice($productPrice);

            for($i = 0 ; $i < 5 ; $i++) {
                $product->setRestaurants(
                    $this->getReference(
                        $this->getRestaurant()
                    )
                );
                $manager->persist($product);
                $manager->flush();
            }
        }
    }

    private function getUserData(): array
    {
        return [
            ['Hugo', 'LIEGEARD', 'hugo@livretoo.fr', 'livretoo', '0783 45 67 67', '20 Rue de Paris', 'Gonesse', ['ROLE_RESTAURATEUR']],
            ['Naël', 'FAWAL', 'nawel@livretoo.fr', 'livretoo', '0783 45 67 67', '88 Parc de la Paix', 'Gonesse', ['ROLE_RESTAURATEUR']],
            ['Maxime', 'DELAYER', 'maxime@livretoo.fr', 'livretoo', '0783 45 67 67', '88 Parc de la Paix', 'Gonesse', ['ROLE_RESTAURATEUR']],
            ['Antoine', 'MACHEDA', 'antoine@livretoo.fr', 'livretoo', '0783 45 67 67', 'Bellecour', 'Lyon', ['ROLE_USER']],
        ];
    }

    private function getRestaurantData(): array
    {
        return [
            ['Les Terrasses de Lyon', '45 Rue de la Victoire', 'Gonesse', '01 55 38 95 42'],
            ['Bouchon Les Lyonnais', '45 Rue de la Victoire', 'Gonesse', '01 55 38 95 42'],
            ['Le Book-Lard', '45 Rue de la Victoire', 'Gonesse', '01 55 38 95 42'],
            ['Le Neuvième Art', '45 Rue de la Victoire', 'Gonesse', '01 55 38 95 42'],
            ['Cercle Rouge', '45 Rue de la Victoire', 'Gonesse', '01 55 38 95 42'],
            ['Le Cocon', '45 Rue de la Victoire', 'Gonesse', '01 55 38 95 42'],
        ];
    }

    private function getProductData(): array
    {
        return [
            ['Ratatouille méridionale', 7],
            ['Quiche gourmande aux poivrons', 6],
            ['Lasagnes au saumon et aux épinards', 2],
            ['Paella de marisco', 12],
            ['Osso bucco milanaise', 22],
            ['Choucroute alsacienne', 13],
            ['Risotto aux champignons', 42],
            ['Couscous tunisien traditionnel', 17],
            ['Lasagnes à la bolognaise', 8],
            ['Tacos mexicains', 4],
            ['Velouté de Potiron et Carottes', 27],
            ['Flan de courgettes', 14],
            ['Salade de riz d\'été facile', 18],
            ['Bruschetta (Italie)', 21],
            ['Soupe à l\'oignon', 13],
            ['Saumon en papillote', 24],
            ['Pissaladière', 10],
        ];
    }

    private function getRestaurant()
    {
        $restaurants = $this->getRestaurantData();
        return $restaurants[array_rand($restaurants)][0];
    }
}
