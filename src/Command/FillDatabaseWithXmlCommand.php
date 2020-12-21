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

            // Je pars du principe que la BDD est vierge puisqu'on crée le projet avec le fichier xml
            // J'ai mis des id autoincrémentables. Comme on n'a pas la main dessus, il faut les récupérer en base 
            // et les enregistrer en les faisant correspondre aux id du fichier xml.
            // Je crée ici les tableaux destinés à récupérer les id des entités créés en base pour éviter les doublons
            $customerIds = [];
            $productSkus = [];
            $orderIds = [];

            foreach ($orders as $order) {
                $customerId =(int) $order->customer['id'];
                
                // On vérifie que le client créé n'existe pas déjà.
                if (!array_key_exists($customerId, $customerIds)) {
                    $this->customerModel->create($order->customer->firstname, $order->customer->lastname);                    
                    
                    // on récupère l'id du client en bdd et on le fait correspondre à l'id du fichier xml mis en clé du tableau
                    $customerIds[$customerId] = $this->customerModel->getCustomer()->getId();
                }
                
                $customer = $this->customerModel->getCustomerById($customerIds[$customerId]);
                
                $orderId = (int) $order['id'];
                $this->orderModel->create($order->orderDate, $order->status, (float) $order->price, $customer);
                $orderIds[$orderId] = $this->orderModel->getOrder()->getId();

                foreach($order->cart->product as $product) {
                    $productSku = (string) $product['sku'];

                    // On vérifie que le produit créé n'existe pas déjà.
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