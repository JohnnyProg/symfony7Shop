<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AddToCartType;
use App\Form\SearchProductsType;
use App\Manager\CartManager;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    private $productRepository;
    private $cartManager;
    function __construct(ProductRepository $productRepository, CartManager $cartManager)
    {
        $this->productRepository = $productRepository;
        $this->cartManager = $cartManager;
    }

    #[Route('/product', name: 'app_product')]
    public function index(
        Request $request,
        #[MapQueryParameter] string $sortDir = 'ASC',
        #[MapQueryParameter] string $sortBy = 'price',
        #[MapQueryParameter] int $page = 1
    ): Response
    {
        $form = $this->container->get('form.factory')->createNamed('', SearchProductsType::class, null, ['csrf_protection' => false, 'allow_extra_fields' => true]);
        $form->handleRequest($request);
        $page = max(1, $page);

        $paginator = $this->productRepository->getProductPaginatorFiltered($page, $sortDir, $sortBy);

        $lastPage = intval(count($paginator) / $this->productRepository::PAGINATOR_PER_PAGE) + 1;

        return $this->render('product/index.html.twig', [
            'form' => $form->createView(),
            'products' => $paginator,
            'previous' => ($page - 1 > 0) ? $this->generateUrl('app_product', array_merge($request->query->all(), ['page' => $page - 1])) : null,
            'next' => ($page + 1 > $lastPage) ? null : $this->generateUrl('app_product', array_merge($request->query->all(), ['page' => $page + 1]))
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_show')]
    public function productDetails(Product $product, Request $request): Response
    {

        $form = $this->createForm(AddToCartType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $item->setProduct($product);
            $cart = $this->cartManager->getCurrentCart();
            $cart
                ->addItem($item)
                ->setUpdatedAt(new \DateTime());
            $this->cartManager->save($cart);
            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }


        return $this->render('product/details.html.twig', [
            'product' => $product,
            'form' => $form
        ]);
    }
}
