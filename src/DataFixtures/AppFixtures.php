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
            $this->addReference($firstname, $user);
        }
        $manager->flush();
    }

    private function loadRestaurants(ObjectManager $manager): void
    {
        $i = 1;
        foreach ($this->getRestaurantData() as [$title, $address, $city, $phone, $picture]) {
            $restaurant = new Restaurant();
            $restaurant->setName($title);
            $restaurant->setAddress($address);
            $restaurant->setCity($city);
            $restaurant->setPhone($phone);
            $restaurant->setPicture($picture);
            $restaurant->setUsers($this->getReference(
                $this->getuser($i)
            ));
            $i = $i + 1;
            $manager->persist($restaurant);
            $this->addReference($title, $restaurant);
        }
        $manager->flush();
    }

    private function loadProducts(ObjectManager $manager): void
    {
        foreach ($this->getProductData() as [$productName, $productPrice, $productDescription, $productPicture]) {

            $product = new Product();
            $product->setName($productName);
            $product->setPrice($productPrice);
            $product->setDescription($productDescription);
            $product->setPicture($productPicture);

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
            ['Dimitri', 'Serrano', 'dimitri@tweatter.fr', 'tweatter', '07 83 45 67 67', '20 Rue de Paris', 'Paris', ['ROLE_ADMIN']],
            ['Jean', 'Dupont', 'jean@tweatter.fr', 'tweatter', '07 83 45 67 67', '20 Rue de Paris', 'Paris', ['ROLE_RESTAURATEUR']],
            ['Kevin', 'Vallin', 'kevin@tweatter.fr', 'tweatter', '07 83 45 67 67', '88 Parc de la Paix', 'Lyon', ['ROLE_RESTAURATEUR']],
            ['Aurelien', 'Robier', 'Aurelien@tweatter.fr', 'tweatter', '07 83 45 67 67', '88 Parc de la Paix', 'Bordeaux', ['ROLE_RESTAURATEUR']],
            ['Ebbane', 'Diet', 'Ebbane@tweatter.fr', 'tweatter', '07 83 45 67 67', '88 Parc de la Paix', 'Bordeaux', ['ROLE_RESTAURATEUR']],
            ['Maxence', 'Crosse', 'Maxence@tweatter.fr', 'tweatter', '07 83 45 67 67', '88 Parc de la Paix', 'Toulouse', ['ROLE_RESTAURATEUR']],
            ['Mareva', 'Gauss', 'Mareva@tweatter.fr', 'tweatter', '07 83 45 67 67', '88 Parc de la Paix', 'Lille', ['ROLE_RESTAURATEUR']],
        ];
    }

    private function getRestaurantData(): array
    {
        return [
            ['Les Terrasses de Lyon', '45 Rue de la Victoire', 'Lyon', '01 55 38 95 42', 'https://www.villaflorentine.com/media/cache/jadro_resize/rc/a1OTa5ps1600242447/jadroRoot/medias/57e518ad7a2e4/5ef4ca65b7dfc/terrasses-de-lyon-alexandre-moulard-42-.jpg'],
            ['Bouchon Les Lyonnais', '45 Rue de la Victoire', 'Lyon', '01 55 38 95 42', 'https://www.restaurant-lyonnais.com/media/photos/Restaurant/01_salle-photos-1024x683.jpg'],
            ['Le Book-Lard', '45 Rue de la Victoire', 'Lyon', '01 55 38 95 42', 'https://res.cloudinary.com/tf-lab/image/upload/w_600,h_337,c_fill,g_auto:subject,q_auto,f_auto/restaurant/fef3f78d-a0fb-4012-95e1-bcdeb49ffe59/2e8c7445-bdc9-4574-a9bb-0fd7bcc0f611.jpg'],
            ['Le Neuvième Art', '45 Rue de la Victoire', 'Lyon', '01 55 38 95 42', 'https://leneuviemeart.com/media/cache/jadro_resize/rc/dSnM931W1607359152/jadroRoot/medias/5f74537f68679/dsc_3949.jpg'],
            ['Cercle Rouge', '45 Rue de la Victoire', 'Lyon', '01 55 38 95 42', 'http://cercle-rouge.fr/wp-content/uploads/2020/03/540602_pro_002.jpg'],
            ['Le Cocon', '45 Rue de la Victoire', 'Lyon', '01 55 38 95 42', 'https://www.lecocon-restaurant.com/image/sgbelqxqr1hrv5eea0098699a7a?w=900&q_jpg=100'],
        ];
    }

    private function getProductData(): array
    {
        return [
            ['Ratatouille méridionale', 7, 'Un petit air d\'été et de méditérannée avec cette ratatouille', 'https://assets.afcdn.com/recipe/20130909/27025_w1024h768c1cx1552cy2336.webp'],
            ['Quiche gourmande aux poivrons', 6, 'Quiche gourmande aux poivrons de la régions', 'https://assets.afcdn.com/recipe/20141023/33805_w600.jpg'],
            ['Lasagnes au saumon et aux épinards', 2, 'Découvrez ce délicieux plats au saumon pecher dans la journée', 'https://assets.afcdn.com/recipe/20200219/107895_w600.jpg'],
            ['Paella de marisco', 12, 'Viva Espana', 'https://assets.afcdn.com/recipe/20161128/44148_w1024h768c1cx2722cy1815.webp'],
            ['Osso bucco milanaise', 22, 'C\'est pas mauvais en vrai', 'https://assets.afcdn.com/recipe/20200512/110973_w600.jpg'],
            ['Choucroute alsacienne', 13, 'Psartek en hiver', 'https://cdn.lacuisinedannie.com/images/45.jpg'],
            ['Risotto aux champignons', 42, 'La base de la base', 'https://assets.afcdn.com/recipe/20191128/103128_w600.jpg'],
            ['Couscous tunisien traditionnel', 17, 'Après ça tu vas rouler', 'https://assets.afcdn.com/recipe/20190916/98247_w600.jpg'],
            ['Lasagnes à la bolognaise', 8, 'Le classique simple efficace', 'https://assets.afcdn.com/recipe/20200408/109520_w600.jpg'],
            ['Tacos mexicains', 4, 'Entre potes ca passe crème', 'https://assets.afcdn.com/recipe/20190212/87658_w600.jpg'],
            ['Velouté de Potiron et Carottes', 27, 'Pas ouf', 'https://assets.afcdn.com/recipe/20160325/63237_w600.jpg'],
            ['Flan de courgettes', 14, '5 fruits et légumes par jour', 'https://assets.afcdn.com/recipe/20190529/93196_w157h157c1.webp'],
            ['Salade de riz d\'été facile', 18, 'Un plat pour supporter la canicule', 'https://assets.afcdn.com/recipe/20190704/94661_w600.jpg'],
            ['Bruschetta (Italie)', 21, 'Je sais pas ce que c\'est', 'https://assets.afcdn.com/recipe/20170112/42222_w157h157c1.webp'],
            ['Soupe à l\'oignon', 13, 'Ferme ta bouche après', 'https://assets.afcdn.com/recipe/20210104/116953_w600.jpg'],
            ['Saumon en papillote', 24, 'Le poisson c\'est bon', 'https://assets.afcdn.com/recipe/20150922/57763_w600.jpg'],
            ['Pissaladière', 10, 'Connais pas', 'https://assets.afcdn.com/recipe/20160517/22244_w600.jpg'],
        ];
    }

    private function getRestaurant()
    {
        $restaurants = $this->getRestaurantData();
        return $restaurants[array_rand($restaurants)][0];
    }

    private function getUser($i)
    {
        $user = $this->getUserData();
        return $user[$i][0];
    }
}
