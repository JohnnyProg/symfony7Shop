<?php
namespace App\Manager;

use App\Entity\Order;
use App\Factory\OrderFactory;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;

class CartManager
{
    private $cartSessionStorage;
    private $cartFactory;
    private $entityManager;

    public function __construct(CartSessionStorage $cartSessionStorage, OrderFactory $orderFactory, EntityManagerInterface $entityManager)
    {
        $this->cartSessionStorage = $cartSessionStorage;
        $this->cartFactory = $orderFactory;
        $this->entityManager = $entityManager;
    }

    public function getCurrentCart(): Order
    {
        $cart = $this->cartSessionStorage->getCart();
        if(!$cart) {
            $cart = $this->cartFactory->create();
        }

        return $cart;
    }

    /**
     * Persists the cart in database and session.
     *
     * @param Order $cart
     */
    public function save(Order $cart): void
    {
        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        $this->cartSessionStorage->setCart($cart);
    }
}