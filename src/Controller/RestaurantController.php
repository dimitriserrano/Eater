<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\RestaurantRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RestaurantController extends AbstractController
{
    /**
     * @Route("/restaurant/{id}", name="restaurant")
     */
    public function index(Restaurant $restaurant, ProductRepository $productRepository): Response
    {
        $products = $productRepository->getByID($restaurant->getId());
        return $this->render('restaurant/index.html.twig', [
            'products' => $products,
            'restaurants' => $restaurant,
        ]);
    }

    /**
     * @Route("/restaurant/{id}/edit/{products}", name="restaurant_edit")
     * @ParamConverter("product", options={"id" = "products"})
     */
    public function edit(Restaurant $restaurant, Product $product, Request $request): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('restaurant', ['id' => $restaurant->getId()]);
        }

        return $this->render('restaurant/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
}