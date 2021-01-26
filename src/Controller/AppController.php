<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use App\Service\Cart\CartService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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

    /**
     * @Route("/user/home", name="home_user")
     */
    public function home_user(RestaurantRepository $restaurantRepository): Response
    {
        $restaurants = $restaurantRepository->findAll();
        return $this->render('app/home.user.html.twig', [
            'restaurants' => $restaurants,
        ]);
    }

    /**
     * @Route("/admin/home", name="home_admin")
     */
    public function home_admin(RestaurantRepository $restaurantRepository): Response
    {
        $restaurants = $restaurantRepository->findAll();
        return $this->render('app/home.admin.html.twig', [
            'restaurants' => $restaurants,
        ]);
    }

    /**
     * @Route("/user/panier", name="panier")
     */
    public function index_panier(CartService $cartService, ProductRepository $productRepository): Response
    {
        return $this->render('app/index.panier.html.twig', [
            "items" => $cartService->getFullCart(),
            "total" => $cartService->getTotal()
        ]);
    }

    /**
     * @Route("/user/{id}/panier/new/{restaurants}", name="panier_add")
     * * @ParamConverter("restaurant", options={"id" = "restaurants"})
     */
    public function add($id, CartService $cartService, Product $product, Restaurant $restaurant)
    {
        {
            $cartService->add($id);
    
            return $this->redirectToRoute("product_show", ['id' => $restaurant->getId()]);
        }
    }

    /**
     * @Route("/user/panier/supprimer/{id}", name="panier_remove")
     */
    public function remove($id, CartService $cartService)
    {
        $cartService->remove($id);

        return $this->redirectToRoute('panier');
    }
}
