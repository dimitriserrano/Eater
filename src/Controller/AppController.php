<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/app", name="app")
     */
    public function index(): Response
    {
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(RestaurantRepository $restaurantRepository): Response
    {
        $restaurants = $restaurantRepository->findAll();
        return $this->render('app/home.html.twig', [
            'restaurants' => $restaurants,
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_show")
     */
    public function show(Restaurant $restaurant, ProductRepository $productRepository): Response
    {
        $products = $productRepository->getByID($restaurant->getId());
        return $this->render('app/show.html.twig', [
            'products' => $products,
            'restaurants' => $restaurant,
        ]);
    }

    /**
     * @Route("/restaurant/home", name="home_restaurant")
     */
    public function home_restaurant(RestaurantRepository $restaurantRepository): Response
    {
        $restaurants = $restaurantRepository->findAll();
        return $this->render('app/home.restaurant.html.twig', [
            'restaurants' => $restaurants,
        ]);
    }
}
