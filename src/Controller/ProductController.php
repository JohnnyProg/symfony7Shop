<?php

namespace App\Controller;

use App\Form\SearchProductsType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    private $productRepository;
    function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
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
}
