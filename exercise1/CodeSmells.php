<?php

class Customer
{
    private string $name;
    private string $email;
    private string $phone;
    private string $address;
    private string $city;
    private string $zip;

    public function __construct(string $name, string $email, string $phone, string $address, string $city, string $zip)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
        $this->city = $city;
        $this->zip = $zip;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPhone(): string
    {
        return $this->phone;
    }
    public function getAddress(): string
    {
        return $this->address;
    }
    public function getCity(): string
    {
        return $this->city;
    }
    public function getZip(): string
    {
        return $this->zip;
    }
}

class Product
{
    private int $id;
    private string $name;
    private float $price;

    public function __construct(int $id, string $name, float $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getPrice(): float
    {
        return $this->price;
    }
}

class OrderProcessor
{
    private mixed $db;
    private mixed $mailer;
    private mixed $logger;
    private mixed $smsService;
    private float $taxRate = 0.27;

    public function __construct(mixed $db, mixed $mailer, mixed $logger, mixed $smsService)
    {
        $this->db = $db;
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->smsService = $smsService;
    }

    public function processOrder(Customer $customer, Product $product, int $quantity): void
    {
        $total = $this->calculateTotal($product->getPrice(), $quantity);
        $this->saveToDb($customer, $product, $total);
        $this->sendNotifications($customer, $product, $quantity, $total);
        $this->logOrder($customer, $product, $total);
    }

    private function calculateTotal(float $price, int $quantity): float
    {
        $subtotal = $price * $quantity;
        return $subtotal + ($subtotal * $this->taxRate);
    }

    private function saveToDb(Customer $customer, Product $product, float $total): void
    {
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

    private function sendNotifications(Customer $customer, Product $product, int $quantity, float $total): void
    {
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

    private function logOrder(Customer $customer, Product $product, float $total): void
    {
        $logData = "Order placed: " . $customer->getName() . ", " . $customer->getEmail() . ", " . $customer->getPhone() . ", " . $customer->getAddress() . ", " . $customer->getCity() . ", " . $customer->getZip() . ", " . $product->getName() . ", " . $total;
        $this->logger->log($logData);
    }
}
