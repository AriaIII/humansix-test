<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    public static function getSubscribedServices()
    {
        return array_merge(parent::getSubscribedServices(), [
            'app.order_model' => 'App\Model\OrderModel',
            'app.customer_model' => 'App\Model\CustomerModel',
            'app.product_model' => 'App\Model\ProductModel',
        ]);
    }

    /**
     * @Route("/order", name="order_index")
     */
    public function index()
    {
        $orders = $this->get('app.order_model')->getOrders();

        return $this->render('order/index.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/order/new", name="order_new")
     */
    public function new()
    {
        $customers = $this->get('app.customer_model')->getCustomers();
        $products = $this->get('app.product_model')->getProducts();

        return $this->render('order/new.html.twig', [
            'customers' => $customers,
            'products' => $products
        ]);
    }

    /**
     * @Route("/order/create", name="order_create")
     */
    public function create(Request $request)
    {
        // dd($request->request);
        $orderPrice = 0;
        $productLines = [];
        foreach($request->request->get('product') as $productSku => $quantity) {
            if($quantity != '') {
                $product = $this->get('app.product_model')->getProductBySku($productSku);
                $price = intval($product->getPrice());
                $orderPrice += $price*$quantity;
                $productLines[$quantity] = $product;
            } 
        }

        $customer = $this->get('app.customer_model')->getCustomerById($request->request->get('customer'));

        $this->get('app.order_model')
            ->create(
                'now',
                $orderPrice,
                $customer
            );

        foreach($productLines as $quantity => $product) {
            $this->get('app.order_model')
                ->createLine(
                    $product,
                    $quantity,
                    $this->get('app.order_model')->getOrder()
                );
        }

        return $this->redirectToRoute('order_index');
    }


    /**
     * @Route("/order/{orderId}", name="order_show")
     */
    public function show($orderId)
    {
        $order = $this->get('app.order_model')->getOrderById($orderId);

        return $this->render('order/show.html.twig', [
            'order' => $order
        ]);
    }

}