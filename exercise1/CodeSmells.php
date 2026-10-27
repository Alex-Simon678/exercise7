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

class OrderResult
{
    private Customer $customer;
    private Product $product;
    private int $quantity;
    private float $subtotal;
    private float $tax;
    private float $total;

    public function __construct(
        Customer $customer,
        Product $product,
        int $quantity,
        float $subtotal,
        float $tax,
        float $total
    ) {
        $this->customer = $customer;
        $this->product = $product;
        $this->quantity = $quantity;
        $this->subtotal = $subtotal;
        $this->tax = $tax;
        $this->total = $total;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getSubtotal(): float
    {
        return $this->subtotal;
    }

    public function getTax(): float
    {
        return $this->tax;
    }

    public function getTotal(): float
    {
        return $this->total;
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

    public function processOrder(Customer $customer, Product $product, int $quantity): OrderResult
    {
        $result = $this->calculateOrder($customer, $product, $quantity);
        $total = $result->getTotal();

        $this->saveToDb($customer, $product, $total);
        $this->sendNotifications($customer, $product, $quantity, $total);
        $this->logOrder($customer, $product, $total);

        return $result;
    }

    private function calculateOrder(Customer $customer, Product $product, int $quantity): OrderResult
    {
        $subtotal = $product->getPrice() * $quantity;
        $tax = $subtotal * $this->taxRate;

        return new OrderResult($customer, $product, $quantity, $subtotal, $tax, $subtotal + $tax);
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
