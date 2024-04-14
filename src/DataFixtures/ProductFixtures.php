<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $skuList = $this->generateUniqueSkus(20);
        $productsNames = [
            'Okulary przeciwsloneczne',
            'Słuchawki przewodowe',
            'Słuchawki bezprzewodowe',
            'Zegarek',
            'Smartfon',
            'Laptop',
            'Kamera',
            'Tablet',
            'Powerbank',
            'Głośnik Bluetooth',
            'Mysz komputerowa',
            'Klawiatura mechaniczna',
            'Monitor',
            'Telewizor',
            'Ekspres do kawy',
            'Odkurzacz bezprzewodowy',
            'Żelazko',
            'Mikrofalówka',
            'Lodówka',
            'Robot kuchenny'
        ];
        $productsPrices = [
            100, 352, 199, 250, 800, 1200, 500, 350, 50, 120, 30, 150, 400, 700, 1000, 300, 80, 200, 600, 180
        ];
        $productsImages = [
            '/giorgio-trovato-K62u25Jk6vo-unsplash.jpg',
            '/kiran-ck-LSNJ-pltdu8-unsplash.jpg',
            '/c-d-x-PDX_a_82obo-unsplash.jpg',
            '/rachit-tank-2cFZ_FB08UM-unsplash.jpg',
            '/smartphone.jpg',
            '/laptop.jpg',
            '/camera.jpg',
            '/tablet.jpg',
            '/powerbank.jpg',
            '/speaker.jpg',
            '/mouse.jpg',
            '/keyboard.jpg',
            '/monitor.jpg',
            '/tv.jpg',
            '/coffeMaker.jpg',
            '/vacuum.jpg',
            '/iron.jpg',
            '/microwave.jpg',
            '/fridge.jpg',
            '/robot.jpg',
            ];
        for ($i = 0; $i < count($skuList); $i++) {
            $product = new Product();
            $product->setSku($skuList[$i]);
            $product->setName($productsNames[$i]);
            $product->setPrice($productsPrices[$i]);
            $product->setImage($productsImages[$i]);
            $manager->persist($product);
        }

        $manager->flush();
    }

    private function generateUniqueSkus(int $amount): array
    {
        $skuList = [];
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < $amount; $i++) {
            $sku = '';
            for ($j = 0; $j < $charactersLength; $j++) {
                $sku .= $characters[rand(0, $charactersLength - 1)];
            }
            if (!in_array($sku, $skuList)) {
                $skuList[] = $sku;
            }

        }
        return $skuList;
    }
}
