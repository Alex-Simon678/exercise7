<?php
class Customer{
    private $name;
    private $email;
    private $phone;
    private $address;
    private $city; 
    private $zip;

    public function __construct($name, $email, $phone, $address, $city, $zip){
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
        $this->city = $city;
        $this->zip = $zip;
    }

    public function getName(){
        return $this->name;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getPhone(){
        return $this->phone;
    }
    public function getAddress(){
        return $this->address;
    }
    public function getCity(){
        return $this->city;
    }
    public function getZip(){
        return $this->zip;
    }

}
class Product{
    private $id;
    private $name;
    private $price;

    public function __construct($id, $name, $price){
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }
    public function getName(){
        return $this->name;
    }
    public function getPrice(){
        return $this->price;
    }

}
class OrderProcessor {
    private $db;
    private $mailer;
    private $logger;
    private $smsService;
    private $taxRate = 0.27;

    public function __construct($db, $mailer, $logger, $smsService) {
        $this->db = $db;
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->smsService = $smsService;
    }

    public function processOrder(Customer $customer, Product $product, $quantity){
        $total = $this->calculateTotal($product->getPrice(), $quantity);
        
        $this->saveToDb($customer, $product, $total);
        $this->sendNotifications($customer, $product, $quantity, $total);
        $this->logOrder($customer, $product, $total);
    }

    private function calculateTotal($price, $quantity){
        $subtotal = $price * $quantity;
        $tax = $subtotal * $this->taxRate;
        return $subtotal + $tax;
    }

    private function saveToDb(Customer $customer, Product $product, $total){
        $name = $customer->getName();
        $email = $customer->getEmail();
        $productName = $product->getName();

        $this->db->query("INSERT INTO orders VALUES (NULL, '$name', '$email', '$productName', $total)");
        
        $phone = $customer->getPhone();
        $address = $customer->getAddress();
        $city = $customer->getCity();
        $zip = $customer->getZip();
        
        $this->db->query("INSERT INTO customers VALUES (NULL, '$name', '$email', '$phone', '$address', '$city', '$zip')");
    }

    private function sendNotifications(Customer $customer, Product $product, $quantity, $total){
        $name = $customer->getName();
        $email = $customer->getEmail();
        $productName = $product->getName();
        $message = "Dear $name, your order for $quantity x $productName totaling $$total has been placed.";

        $this->mailer->send($email, "Order Confirmation", $message);

        $phone = $customer->getPhone();
        if ($phone !== "") {
            $this->smsService->send($phone, $message);
        }
    }

    private function logOrder(Customer $customer, Product $product, $total){
        $logData = "Order placed: " . $customer->getName() . ", " . $customer->getEmail() . ", " . $customer->getPhone() . ", " . $customer->getAddress() . ", " . $customer->getCity() . ", " . $customer->getZip() . ", " . $product->getName() . ", " . $total;
        $this->logger->log($logData);
    }
}