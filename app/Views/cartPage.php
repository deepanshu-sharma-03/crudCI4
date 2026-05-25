<!DOCTYPE html>
<html>

<head>
    <title>My Cart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: linear-gradient(135deg, #eef2ff, #dbeafe);
            font-family: Segoe UI, sans-serif;
        }

        .cart-card {
            background: white;
            border-radius: 22px;
            padding: 30px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .08);
        }

        h2 {
            font-weight: 700;
            color: #1e293b;
        }

        table {
            border-radius: 18px;
            overflow: hidden;
        }

        thead {
            background: #0f172a;
            color: white;
        }

        td,
        th {
            vertical-align: middle;
            text-align: center;
        }

        tbody tr {
            transition: .3s;
        }

        tbody tr:hover {
            background: #f8fafc;
            transform: scale(1.01);
        }

        .product-img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
        }

        .qty-box {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .qty-btn {
            width: 38px;
            height: 38px;
            border: none;
            border-radius: 50%;
            background: #2563eb;
            color: white;
            font-size: 20px;
            font-weight: bold;
            transition: .3s;
        }

        .qty-btn:hover {
            background: #1d4ed8;
            transform: scale(1.1);
        }

        .qty-input {
            width: 60px;
            text-align: center;
            border-radius: 10px;
            border: 2px solid #cbd5e1;
            font-weight: 700;
        }

        .btn-update {
            background: #16a34a;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 8px 18px;
        }

        .btn-delete {
            background: #dc2626;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 8px 18px;
        }

        .btn-update:hover {
            background: #15803d;
        }

        .btn-delete:hover {
            background: #b91c1c;
        }

        .grand-total {
            background: #0f172a;
            color: white;
            padding: 18px;
            border-radius: 14px;
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .checkout-btn {
            background: linear-gradient(90deg, #7c3aed, #2563eb);
            border: none;
            padding: 12px 28px;
            border-radius: 12px;
            color: white;
            font-weight: 700;
            transition: .3s;
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
        }

        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;

            background: #ffffff;
            padding: 18px 24px;
            border-radius: 14px;

            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        .cart-title {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;

            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cart-icon {
            font-size: 32px;
        }

        .cart-back-btn {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;

            border: none;
            border-radius: 10px;

            padding: 10px 20px;
            font-size: 15px;
            font-weight: 600;

            display: flex;
            align-items: center;
            gap: 8px;

            cursor: pointer;
            transition: all 0.3s ease;
        }

        .cart-back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
        }
    </style>

</head>

<body>

    <div class="container py-5">

        <div class="cart-card">

            <div class="cart-header">

                <h2 class="cart-title">
                    <span class="cart-icon">🛒</span>
                    My Shopping Cart
                </h2>

                <button onclick="backward()" class="cart-back-btn">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back
                </button>

            </div>
            <?php if (!empty($cart)) : ?>
                <table class="table">

                    <thead>

                        <tr>
                            <th>Product</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php
                        $grandTotal = 0;
                        ?>

                        <?php


                        foreach ($cart as $item): ?>

                            <?php
                            $total = $item['price'] * $item['quantity'];
                            $grandTotal += $total;
                            ?>

                            <tr>

                                <td>
                                    <img src="<?= base_url($item['product_image']) ?>"
                                        class="product-img">
                                </td>

                                <td class="fw-bold">
                                    <?= $item['product_name'] ?>
                                </td>

                                <td class="text-primary fw-bold">
                                    ₹<?= $item['price'] ?>
                                </td>

                                <td>

                                    <div class="qty-box">

                                        <button
                                            class="qty-btn"
                                            onclick="decrease(<?= $item['id'] ?>)">
                                            −
                                        </button>

                                        <input
                                            type="number"
                                            class="qty-input"
                                            value="<?= $item['quantity'] ?>"
                                            min="1"
                                            id="qty-<?= $item['id'] ?>">

                                        <button
                                            class="qty-btn"
                                            onclick="increase(<?= $item['id'] ?>)">
                                            +
                                        </button>

                                    </div>

                                </td>

                                <td>

                                    <?php if ($item['product_qty'] > 0): ?>

                                        <span class="badge bg-success p-2">
                                            ✓ In Stock
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-danger p-2">
                                            ✕ Out of Stock
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td class="fw-bold text-success">
                                    ₹<?= $total ?>
                                </td>

                                <td>

                                    <button
                                        class="btn-update"
                                        onclick="updateCart(<?= $item['id'] ?>)">
                                        Update
                                    </button>

                                    <button
                                        class="btn-delete"
                                        onclick="deleteFromCart(<?= $item['id'] ?>)">
                                        Delete
                                    </button>

                                </td>

                            </tr>

                        <?php endforeach; ?>


                    </tbody>

                </table>

                <div class="grand-total">

                    <h4 class="m-0">
                        Grand Total : ₹<?= $grandTotal ?>
                    </h4>

                    <button
                        class="checkout-btn"
                        onclick="checkout()">
                        Proceed Checkout →
                    </button>

                </div>

        </div>

    </div>
<?php else : ?>
    <div style="display:flex; justify-content:center; align-items:center; width:100%;">
        <h3 style="font-weight:bold; margin:0;">
            Cart is Empty
        </h3>
    </div>

<?php endif; ?>

<script>
    function increase(id) {
        let qty = document.getElementById("qty-" + id);
        qty.value = parseInt(qty.value) + 1;
    }

    function decrease(id) {
        let qty = document.getElementById("qty-" + id);

        if (qty.value > 1) {
            qty.value = parseInt(qty.value) - 1;
        }
    }

    function updateCart(id) {

        let quantity = document.getElementById("qty-" + id).value;

        $.ajax({
            url: '<?= base_url("/update-cart") ?>',
            method: 'POST',
            dataType: 'json',

            data: {
                id: id,
                quantity: quantity
            },

            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        title: "Cart Updated Successfully!",
                        icon: "success",
                        // timer: 1500,
                        draggable: true
                    }).then(() => {
                        window.location.reload();
                    });

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: response.message,
                    });

                }

            },

        });
    }

    function deleteFromCart(id) {

        $.ajax({

            url: '<?= base_url('/cart-remove') ?>',

            method: 'POST',

            dataType: 'json',

            data: {
                id: id
            },

            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        title: "Product Removed!",
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong",
                    });
                }

            },


        });
    }

    function backward() {
        window.history.back();
    }

    function checkout() {
        $.ajax({
            url: "<?= base_url('/validate-checkout') ?>",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    $.ajax({
                        url: '<?= base_url('/checkout') ?>',
                        type: 'POST',
                        data: JSON.stringify({
                            data: response.data
                        }),
                        success: function(html) {

                            document.open();
                            document.write(html);
                            document.close();

                        }

                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Out of Stock",
                    });
                }
            }
        })
    }
</script>

</body>

</html>