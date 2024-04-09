<?php

namespace App\Controller;

use App\Form\SearchProductsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(
//        #[MapQueryParameter] string $sortBy,
//        #[MapQueryParameter] string $sortDirection,
//        #[MapQueryParameter] string $search,
    Request $request
    ): Response
    {

        $form = $this->container->get('form.factory')->createNamed('', SearchProductsType::class, null, ['csrf_protection' => false]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->render('product/index.html.twig', [
                'products' => $form->getData(),
                'form' => $form->createView(),
            ]);
        }

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'form' => $form->createView()
        ]);
    }
}
