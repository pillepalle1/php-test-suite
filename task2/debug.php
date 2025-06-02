/*
TASK:
Fix the function `calculateCartSummary()` so that it correctly:
1. Calculates subtotal per category
2. Applies category-specific discounts:
   - 'electronics' => 10% off if subtotal > $100
   - 'books' => Buy 2 get 1 free (cheapest free)
3. Returns total, total after discounts, and breakdown
*/

<?php

function calculateCartSummary($items) {
    $summary = [
        "subtotal" => 0,
        "discounts" => [],
        "total" => 0,
        "by_category" => []
    ];

    foreach ($items as $item) {
        if (!isset($item["name"]) || !isset($item["price"]) || !isset($item["category"]) || !isset($item["quantity"])) {
            continue;
        }

        $price = $item["price"];
        $qty = $item["quantity"];
        $cat = $item["category"];

        if (!is_numeric($price) || !is_int($qty)) {
            continue;
        }

        $lineTotal = $price * $qty;
        $summary["subtotal"] += $lineTotal;

        if (!array_key_exists($cat, $summary["by_category"])) {
            $summary["by_category"][$cat] = [
                "items" => [],
                "subtotal" => 0
            ];
        }

        $summary["by_category"][$cat]["items"][] = $item;
        $summary["by_category"][$cat]["subtotal"] += $lineTotal;
    }

    foreach ($summary["by_category"] as $cat => $catData) {
        $discount = 0;

        if ($cat === "electronics" && $catData["subtotal"] > 100) {
            $discount = $catData["subtotal"] * 0.10;
        }

        if ($cat === "books") {
            $allBooks = [];

            foreach ($catData["items"] as $book) {
                for ($i = 0; $i < $book["quantity"]; $i++) {
                    $allBooks[] = $book["price"];
                }
            }

            sort($allBooks);

            for ($i = 2; $i < count($allBooks); $i += 3) {
                $discount += $allBooks[$i] ?? 0;
            }
        }

        $summary["discounts"][$cat] = $discount;
        $summary["total"] += $catData["subtotal"] - $discount;
    }

    return $summary;
}

// Sample data to debug against
$cart = [
    ["name" => "Headphones", "price" => 50, "category" => "electronics", "quantity" => 1],
    ["name" => "Bluetooth Speaker", "price" => 60, "category" => "electronics", "quantity" => 1],
    ["name" => "Book A", "price" => 10, "category" => "books", "quantity" => 2],
    ["name" => "Book B", "price" => 15, "category" => "books", "quantity" => 1],
    ["name" => "Notebook", "price" => "5", "category" => "stationery", "quantity" => "two"], // Invalid data
    ["name" => "Lamp", "price" => 30, "category" => "home", "quantity" => 1],
    ["name" => "Book C", "price" => 8, "category" => "books", "quantity" => 2],
    ["price" => 100, "quantity" => 1], // Missing name and category
];

$result = calculateCartSummary($cart);

echo "==== SUMMARY ====\n";
echo "Subtotal: $" . number_format($result["subtotal"], 2) . "\n";
echo "Total Discounts: $" . number_format(array_sum($result["discounts"]), 2) . "\n";
echo "Total After Discounts: $" . number_format($result["total"], 2) . "\n\n";

echo "Category Breakdown:\n";
foreach ($result["by_category"] as $cat => $data) {
    echo "- $cat: $" . number_format($data["subtotal"], 2);
    if (isset($result["discounts"][$cat])) {
        echo " (-$" . number_format($result["discounts"][$cat], 2) . ")";
    }
    echo "\n";
}
