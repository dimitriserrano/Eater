<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/list_user", name="list_user")
     */
    public function list_user(UserRepository $userRepository): Response
    {
        $user = $userRepository->findAll();
        return $this->render('admin/liste.user.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $user
        ]);
    }

    /**
     * @Route("/admin/list_restaurant", name="list_restaurant")
     */
    public function list_restaurant(RestaurantRepository $restaurantRepository): Response
    {
        $restaurants = $restaurantRepository->findAll();
        return $this->render('admin/liste.restaurant.html.twig', [
            'controller_name' => 'AdminController',
            'restaurants' => $restaurants
        ]);
    }
}