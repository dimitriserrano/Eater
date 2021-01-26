<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Repository\OrderProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;

        if (!is_null($this->session->get('panier', []))) {
            $this->panier = $this->session->get('panier', []);
        } else {
            $this->panier = [];
        }
    }

    /**
     * @Route("/order", name="order")
     */
    public function index(ProductRepository $productRepository): Response
    {
        if ($this->panier != []) {
            $entityManager = $this->getDoctrine()->getManager();
            $order = new Order();
            $cost = 2.5;
            $orderProducts = [];

            foreach ($this->panier as $id => $quantity) {
                $orderProduct = new OrderProduct();
                $orderProduct->getProducts($productRepository->find($id));
                $orderProduct->getOrders($order);
                $orderProduct->setQuantity($quantity);
                $orderProducts[] = $orderProduct;
                $entityManager->persist($orderProduct);
                for ($i = 0; $i < $quantity; $i++) {
                    $cost += $productRepository->find($id)->getPrice();
                }
            }
            $order->setCost($cost);
            $user = $this->getUser();

            $order->setUsers($user);

            $entityManager->persist($order);
            $entityManager->flush();

            $this->addFlash('success', 'Your order has been completed');
            $this->session->remove('panier');
            
        }
        return $this->redirectToRoute('home');
    }
}
