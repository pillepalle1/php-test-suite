// Add logic to apply a 10% discount to products where price > 5, and print the discounted prices.

<?php

abstract class Product {
    protected $name;
    protected $price;
    protected $category;

    public function __construct(string $name, float $price, string $category) {
        $this->name = $name;
        $this->price = $price;
        $this->category = $category;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function getCategory(): string {
        return $this->category;
    }

    public function setPrice(float $price) {
        $this->price = $price;
    }

    abstract public function applyDiscount(): void;

    public function __toString(): string {
        return "{$this->name} ({$this->category}): $" . number_format($this->price, 2);
    }
}

class PhysicalProduct extends Product {
    public function applyDiscount(): void {
        if ($this->price > 20) {
            $this->price *= 0.85; // 15% discount
        }
    }
}

class DigitalProduct extends Product {
    public function applyDiscount(): void {
        if ($this->price > 10) {
            $this->price *= 0.90; // 10% discount
        }
    }
}

class Cart {
    private $products = [];

    public function addProduct(Product $product): void {
        $this->products[] = $product;
    }

    public function applyDiscounts(): void {
        foreach ($this->products as $product) {
            $product->applyDiscount();
        }
    }

    public function getTotalBeforeTax(): float {
        return array_reduce($this->products, fn($carry, $p) => $carry + $p->getPrice(), 0);
    }

    public function getTotalAfterTax(float $taxRate = 0.2): float {
        return $this->getTotalBeforeTax() * (1 + $taxRate);
    }

    public function getCategoryReport(): array {
        $report = [];
        foreach ($this->products as $product) {
            $cat = $product->getCategory();
            if (!isset($report[$cat])) {
                $report[$cat] = ['count' => 0, 'total' => 0.0];
            }
            $report[$cat]['count']++;
            $report[$cat]['total'] += $product->getPrice();
        }
        return $report;
    }

    public function printReceipt(): void {
        echo "==== RECEIPT ====\n";
        foreach ($this->products as $p) {
            echo $p . "\n";
        }
        echo "Subtotal: $" . number_format($this->getTotalBeforeTax(), 2) . "\n";
        echo "Total (incl. tax): $" . number_format($this->getTotalAfterTax(), 2) . "\n";
    }

    public function printCategorySummary(): void {
        echo "==== CATEGORY SUMMARY ====\n";
        foreach ($this->getCategoryReport() as $category => $data) {
            echo ucfirst($category) . ": {$data['count']} item(s), $" . number_format($data['total'], 2) . "\n";
        }
    }
}

// -------------------
// Test Execution Area
// -------------------

$cart = new Cart();

$cart->addProduct(new PhysicalProduct("Backpack", 45.00, "accessories"));
$cart->addProduct(new PhysicalProduct("Notebook", 5.00, "stationery"));
$cart->addProduct(new DigitalProduct("E-book", 15.00, "books"));
$cart->addProduct(new DigitalProduct("Online Course", 50.00, "education"));

$cart->applyDiscounts();
$cart->printReceipt();
$cart->printCategorySummary();
