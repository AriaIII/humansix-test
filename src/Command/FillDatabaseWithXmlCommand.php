<?php

namespace App\Command;

use App\Model\OrderModel;
use App\Model\ProductModel;
use App\Model\CustomerModel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FillDatabaseWithXmlCommand extends Command
{
    protected static $defaultName = 'app:fill-database-with-xml';
    private $orderModel;
    private $customerModel;
    private $productModel;

    public function __construct(OrderModel $orderModel, CustomerModel $customerModel, ProductModel $productModel) {
        $this->orderModel = $orderModel;
        $this->productModel = $productModel;
        $this->customerModel = $customerModel;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Fill the database with xml file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (\file_exists('orders.xml')) {
            $orders = \simplexml_load_file('orders.xml');
            
            $customerIds = [];
            $productSkus = [];
            $orderIds = [];

            foreach ($orders as $order) {
                $customerId =(int) $order->customer['id'];
                
                if (!array_key_exists($customerId, $customerIds)) {
                    $this->customerModel->create($order->customer->firstname, $order->customer->lastname);                    
                    
                    $customerIds[$customerId] = $this->customerModel->getCustomer()->getId();
                }
                
                $customer = $this->customerModel->getCustomerById($customerIds[$customerId]);
                
                $orderId = (int) $order['id'];
                $this->orderModel->create($order->orderDate, $order->status, (float) $order->price, $customer);
                $orderIds[$orderId] = $this->orderModel->getOrder()->getId();

                foreach($order->cart->product as $product) {
                    $productSku = (string) $product['sku'];

                    if(!\in_array($productSku, $productSkus)) {
                        $productSkus[] = $productSku;
                        $productPrice = (float) $product->price;
                        $this->productModel->create($productSku, $product->name, $productPrice);
                    }

                    $orderedProduct = $this->productModel->getProductBySku($productSku);
                    $newOrder = $this->orderModel->getOrderById($orderIds[$orderId]);

                    $this->orderModel->createLine($orderedProduct,(int) $product->quantity, $newOrder);
                }
            }
            
            $output->writeln('succès');
            return Command::SUCCESS;
        } else {
            $output->writeln('échec');
            return Command::FAILURE;
        }
    }
}