<?php
/* @var $this yii\web\View */
/* @var $basketItems app\models\Basket[] */

use yii\helpers\Html;
use yii\helpers\Url;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="">
    <style>
        .inactive-product {
            background-color: #cc1122; /* Qizil rangda belgilangan */
            border-color: #be2132;
        }

        .cart-container {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            /*text-align: center;*/
        }

        .select-all {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .item-image img {
            max-width: 80px;
            border-radius: 8px;
            margin-right: 20px;
        }

        .item-details {
            flex-grow: 1;
        }

        .item-details h3 {
            font-size: 18px;
            margin: 0;
            font-weight: bold;
        }

        .price {
            font-size: 16px;
            color: #333;
            margin-top: 5px;
        }

        .item-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-quantity {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-quantity:hover {
            background-color: #0056b3;
        }

        .item-quantity {
            width: 50px;
            text-align: center;
            /*padding: 5px;*/
            font-size: 16px;
        }

        .delete-link {
            color: #ff5b5b;
            text-decoration: none;
            font-weight: bold;
        }

        .delete-link:hover {
            text-decoration: underline;
        }

        .order-summary {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
        }

        .btn-formalization {
            background-color: #28a745;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 18px;
        }

        .btn-formalization:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
<div class="cart-container" id="target-section">
    <h2 style="text-align: center">Your cart, <?= count($basketItems) ?> Product(s)</h2>
    <label class="select-all"><input type="checkbox" id="selectAll">&nbsp; Select all</label>

    <?php foreach ($basketItems as $item): ?>
        <?php
        $cardClass = $item->product->status == 0 ? 'inactive-product' : '';
        $disabled = $item->product->status == 0 || $item->product->count == 0;
        ?>
        <div class="cart-item <?= $cardClass ?>">
            <div class="item-image">
                <a href="<?= Url::to(['about/index', 'id' => $item->product_id]) ?>" class="image-link">
                    <?php if ($item->product->images): ?>
                        <?php foreach ($item->product->images as $image): ?>
                            <img style="height: 5rem" src="/uploads/<?= Html::encode($image->image_file_name) ?>" alt="Product Image">
                            <?php break; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </a>
            </div>
            <div class="item-details">
                <h3><?= Html::encode($item->product->name) ?></h3>
                <div class="price">
                    <span class="unit-price"><?= Html::encode($item->product->price) ?> $</span>&nbsp;&nbsp;
                    <span class="total-price"><?= Html::encode($item->product->price * $item->quantity) ?> $</span>
                </div>
            </div>
            <div class="item-actions">
                <button class="btn-quantity btn-decrement" <?= $disabled ? 'disabled' : '' ?>>-</button>
                <input type="number" value="<?= Html::encode($item->quantity) ?>" min="1"
                       class="item-quantity" data-id="<?= Html::encode($item->id) ?>"
                       data-stock="<?= Html::encode($item->product->count) ?>" <?= $disabled ? 'disabled' : '' ?> readonly>
                <button class="btn-quantity btn-increment" <?= $disabled ? 'disabled' : '' ?>>+</button>
                <a href="<?= Url::to(['basket/delete', 'id' => $item->id]) ?>" class="delete-link">Delete</a>
                <input type="checkbox" class="select-item" value="<?= Html::encode($item->id) ?>" <?= $disabled ? 'disabled' : '' ?>>
            </div>

        </div>
    <?php endforeach; ?>

    <div class="order-summary">
        <h3>Your order</h3>
        <p>Products: <span id="totalProducts"><?= count($basketItems) ?></span></p>
        <p>Total Price: <span id="grandTotal">0 $</span></p>
        <form id="checkoutForm" method="get" action="<?= Url::to('/checkout/index') ?>">
            <input type="hidden" name="items" id="selectedItems" value="">
            <button type="submit" class="btn-formalization">Go to formalization</button>
        </form>
    </div>
</div>

<script>
    // Function to calculate the total price for a single item
    function calculateTotalPrice(input) {
        let quantity = parseInt(input.value);
        let unitPrice = parseFloat(input.closest('.cart-item').querySelector('.unit-price').innerText.replace(' $', ''));
        let totalPriceElement = input.closest('.cart-item').querySelector('.total-price');
        let totalPrice = unitPrice * quantity;
        totalPriceElement.innerText = totalPrice + ' $';
    }

    // Function to calculate the grand total and total products for selected items
    function calculateGrandTotal() {
        let grandTotal = 0;
        let totalProducts = 0;

        document.querySelectorAll('.select-item:checked').forEach(function (checkbox) {
            let itemElement = checkbox.closest('.cart-item');
            let totalPrice = parseFloat(itemElement.querySelector('.total-price').innerText.replace(' $', ''));
            grandTotal += totalPrice;
            totalProducts++;
        });

        document.getElementById('grandTotal').innerText = grandTotal + ' $';
        document.getElementById('totalProducts').innerText = totalProducts;

        // Enable or disable the formalization button based on selection
        let formalizationButton = document.querySelector('.btn-formalization');
        formalizationButton.disabled = totalProducts === 0;
    }

    // Handle increment and decrement buttons
    document.querySelectorAll('.btn-increment:not([disabled])').forEach(function (btn) {
        btn.addEventListener('click', function () {
            let input = this.previousElementSibling;
            let currentValue = parseInt(input.value);
            let availableStock = parseInt(input.getAttribute('data-stock')); // Fetch available stock
            if (!isNaN(currentValue) && currentValue < availableStock) { // Check stock limit
                input.value = currentValue + 1;
                calculateTotalPrice(input);
                calculateGrandTotal();
            } else {
                alert('Cannot add more items than available in stock! Only ' + availableStock + ' items are available.');
            }
        });
    });

    document.querySelectorAll('.btn-decrement:not([disabled])').forEach(function (btn) {
        btn.addEventListener('click', function () {
            let input = this.nextElementSibling;
            let currentValue = parseInt(input.value);
            if (!isNaN(currentValue) && currentValue > 1) { // Ensure at least 1 item remains
                input.value = currentValue - 1;
                calculateTotalPrice(input);
                calculateGrandTotal();
            }
        });
    });

    // Handle the formalization button click
    document.querySelector('.btn-formalization').addEventListener('click', function (e) {
        e.preventDefault();

        // Get selected product IDs and their quantities
        let selectedItems = [];
        document.querySelectorAll('.select-item:checked:not([disabled])').forEach(function (checkbox) {
            let itemId = checkbox.value;
            let quantity = document.querySelector('.item-quantity[data-id="' + itemId + '"]').value;
            selectedItems.push(itemId + ':' + quantity); // Store ID and quantity as a pair
        });

        // Add the selected items and their quantities to the hidden input field
        document.getElementById('selectedItems').value = selectedItems.join(',');

        // Submit the form
        document.getElementById('checkoutForm').submit();
    });

    // Handle select all functionality
    document.getElementById('selectAll').addEventListener('click', function () {
        let isChecked = this.checked;
        document.querySelectorAll('.select-item:not([disabled])').forEach(function (checkbox) {
            checkbox.checked = isChecked;
        });
        calculateGrandTotal();
    });

    // Recalculate grand total and product count when selecting/deselecting individual items
    document.querySelectorAll('.select-item:not([disabled])').forEach(function (checkbox) {
        checkbox.addEventListener('change', calculateGrandTotal);
    });

    // Initial calculation of grand total and product count on page load
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.item-quantity').forEach(function (input) {
            calculateTotalPrice(input);
        });
        calculateGrandTotal();
    });

</script>


</body>
</html>
