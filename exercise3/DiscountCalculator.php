<?php

interface DiscountStrategyInterface
{
    public function calculateDiscount(float $price): float;
}

class NoDiscount implements DiscountStrategyInterface
{
    public function calculateDiscount(float $price): float
    {
        return $price;
    }
}

class PercentageDiscount implements DiscountStrategyInterface
{
    private float $percentage;

    public function __construct(float $percentage)
    {
        $this->percentage = $percentage;
    }

    public function calculateDiscount(float $price): float
    {
        return $price - ($price * ($this->percentage / 100));
    }
}

class FixedAmountDiscount implements DiscountStrategyInterface
{
    private float $amount;

    public function __construct(float $amount)
    {
        $this->amount = $amount;
    }

    public function calculateDiscount(float $price): float
    {
        return max(0, $price - $this->amount);
    }
}

class Order
{
    private DiscountStrategyInterface $strategy;
    private float $price;

    public function __construct(DiscountStrategyInterface $strategy, float $price)
    {
        $this->strategy = $strategy;
        $this->price = $price;
    }

    public function setDiscountStrategy(DiscountStrategyInterface $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function getTotal(): float
    {
        return $this->strategy->calculateDiscount($this->price);
    }
}

$basePrice = 100.00;

$order = new Order(new NoDiscount(), $basePrice);
echo "No discount: $" . $order->getTotal() . "\n"; // $100

$order->setDiscountStrategy(new PercentageDiscount(20));
echo "20% discount: $" . $order->getTotal() . "\n"; // $80

$order->setDiscountStrategy(new FixedAmountDiscount(15));
echo "$15 off: $" . $order->getTotal() . "\n"; // $85
