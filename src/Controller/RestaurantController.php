<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\Product;
use App\Form\ProductType;
use App\Form\RestaurantType;
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
     * @Route("/restaurant/{id}/edit", name="restaurant_edit")
     */
    public function restaurant_edit(Restaurant $restaurant, Request $request): Response
    {
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('restaurant', ['id' => $restaurant->getId()]);
        }

        return $this->render('restaurant/global_edit.html.twig', [
            'restaurant' => $restaurant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/restaurant/{id}/edit/{products}", name="product_edit")
     * @ParamConverter("product", options={"id" = "products"})
     */
    public function product_edit(Restaurant $restaurant, Product $product, Request $request): Response
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

    /**
     * @Route("/restaurant/{id}/new", name="product_new")
     */
    public function product_new(Restaurant $restaurant, Product $product, Request $request): Response
    {
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $product->setRestaurants($restaurant);
            $this->getDoctrine()->getManager()->persist($product);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('restaurant', ['id' => $restaurant->getId()]);
        }

        return $this->render('restaurant/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/restaurant/{id}/delete/{products}", name="product_delete")
     * * @ParamConverter("product", options={"id" = "products"})
     */
    public function product_delete(Restaurant $restaurant, Product $product): Response
    {
        $this->getDoctrine()->getManager()->remove($product);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('restaurant', ['id' => $restaurant->getId()]);
    }
}