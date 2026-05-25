<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Products</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    <style>
        body {
            background: linear-gradient(135deg, #eef2ff, #dbeafe, #e0f2fe);
            font-family: 'Segoe UI', sans-serif;
        }

        .product-container {
            margin-top: 50px;
        }

        .card-box {
            background: rgba(255, 255, 255, .9);
            backdrop-filter: blur(12px);

            border-radius: 25px;
            padding: 35px;

            box-shadow:
                0 15px 35px rgba(0, 0, 0, .08);
        }

        .heading {
            font-weight: 700;
            color: #1e293b;
        }

        .table {
            border-radius: 18px;
            overflow: hidden;
        }

        .table th {
            background: linear-gradient(90deg, #2563eb, #7c3aed);
            color: white;
            text-align: center;
            border: none;
        }

        .table td {
            vertical-align: middle;
            text-align: center;
        }

        tbody tr {
            transition: .25s;
        }

        tbody tr:hover {
            background: #f8fafc;
            transform: scale(1.01);
        }

        .product-img {
            border-radius: 14px;
            object-fit: cover;
            border: 2px solid #e2e8f0;
        }

        .qty-box {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .qty-box button {

            width: 30px;
            height: 30px;

            border: none;
            border-radius: 50%;

            background: #2563eb;
            color: white;

            font-size: 14px;
            font-weight: bold;

            transition: .3s;
        }

        .qty-box button:hover {
            background: #1d4ed8;
            transform: scale(1.1);
        }

        .qty-box input {

            width: 45px;
            height: 32px;

            border-radius: 8px;
            border: 1px solid #cbd5e1;

            text-align: center;
            font-weight: 700;
        }

        .cart-btn {

            background: linear-gradient(90deg,
                    #16a34a,
                    #059669);

            color: white;
            border: none;

            padding: 9px 14px;

            border-radius: 12px;

            font-size: 14px;
            font-weight: 600;

            transition: .3s;
        }

        .cart-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(22, 163, 74, .25);
        }

        .cart-btn:disabled {
            background: #94a3b8;
        }

        .cart-top-btn {

            background: linear-gradient(90deg,
                    #2563eb,
                    #7c3aed);

            border: none;
            border-radius: 12px;

            padding: 10px 18px;

            font-weight: 600;
        }

        .badge {
            padding: 8px 12px;
            border-radius: 12px;
            font-size: 13px;
        }

        .small-icon {
            font-size: 12px;
            margin-right: 5px;
        }

        .top-buttons {
            display: flex;
            justify-content: flex-start;
            gap: 12px;
            margin-bottom: 15px;
        }

        .cart-top-btn {
            background: #2563eb;
            border-radius: 8px;
            padding: 8px 18px;
            font-weight: 500;
        }

        .cart-top-btn:hover {
            background: #1d4ed8;
        }

        .small-icon {
            margin-right: 6px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <div class="container product-container">

        <div class="card-box">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h2 class="heading">
                    <i class="fa-solid fa-bag-shopping small-icon"></i>
                    Products
                </h2>

                <div class="top-buttons">

                    <button onclick="showCart()" class="btn text-white cart-top-btn">
                        <i class="fa-solid fa-cart-shopping small-icon"></i>
                        Cart
                    </button>

                    <button onclick="backward()" class="btn text-white cart-top-btn">
                        <i class="fa-solid fa-arrow-left small-icon"></i>
                        Back
                    </button>

                </div>

            </div>

            <table class="table table-hover align-middle">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody>

                    <?php
                    $index = 1;
                    foreach ($products as $product):
                    ?>

                        <tr>

                            <td><?= $index++ ?></td>

                            <td>

                                <img
                                    src="<?= base_url($product['product_image']) ?>"
                                    width="95"
                                    height="85"
                                    class="product-img">

                            </td>

                            <td class="fw-semibold">
                                <?= $product['product_name'] ?>
                            </td>

                            <td class="fw-bold text-primary">
                                ₹<?= $product['price'] ?>
                            </td>

                            <td>

                                <div class="qty-box">

                                    <button
                                        onclick="decrease(<?= $product['id'] ?>)">

                                        <i class="fa-solid fa-minus"></i>

                                    </button>

                                    <input
                                        type="text"
                                        readonly
                                        value="1"
                                        id="qty-<?= $product['id'] ?>">

                                    <button
                                        onclick="increase(<?= $product['id'] ?>)">

                                        <i class="fa-solid fa-plus"></i>

                                    </button>

                                </div>

                            </td>

                            <td>

                                <?php if ($product['product_qty'] > 0): ?>

                                    <span class="badge bg-success">
                                        <i class="fa-solid fa-circle-check small-icon"></i>
                                        In Stock
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-danger">
                                        <i class="fa-solid fa-circle-xmark small-icon"></i>
                                        Out of Stock
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <button
                                    class="cart-btn"
                                    onclick="addToCart(<?= $product['id'] ?>)"

                                    <?= $product['product_qty'] <= 0 ? 'disabled' : '' ?>>

                                    <i class="fa-solid fa-cart-plus small-icon"></i>

                                    Add Cart

                                </button>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>
    </div>

    <script>
        function increase(id) {

            let qty = document.getElementById("qty-" + id);

            qty.value++;

        }

        function decrease(id) {

            let qty = document.getElementById("qty-" + id);

            if (qty.value > 1) {
                qty.value--;
            }

        }

        function addToCart(productId) {

            let quantity =
                document.getElementById(
                    "qty-" + productId
                ).value;

            fetch(
                    "<?= base_url('add-cart') ?>", {
                        method: "POST",

                        headers: {
                            "Content-Type": "application/json"
                        },

                        body: JSON.stringify({

                            product_id: productId,

                            quantity: quantity,
                            price: <?= $product['price'] ?>

                        })
                    })

                .then(
                    response => response.json()
                )

                .then(data => {

                    if (data.status) {

                        Swal.fire({
                            title: "Added Successfully!",
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });

                    } else {

                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: data.message,
                        });

                    }

                });

        }

        function showCart() {

            window.location.href =
                "<?= base_url('/cart') ?>";

        }

        function backward() {
            window.history.back();
        }
    </script>

</body>

</html>