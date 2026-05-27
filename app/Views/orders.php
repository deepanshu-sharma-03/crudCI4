<!DOCTYPE html>
<html>

<head>
    <title>My Orders</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f5f5f5;
            padding: 40px;
        }

        .container {
            max-width: 1300px;
            margin: auto;
        }

        .heading {
            font-size: 32px;
            font-weight: bold;
            color: #222;
            margin-bottom: 30px;
        }

        .order-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, #2874f0, #1c5dc9);
            color: white;
        }

        th {
            padding: 20px;
            text-align: center;
            font-size: 15px;
            letter-spacing: 0.5px;
        }

        td {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #ececec;
            transition: 0.3s;
        }

        tbody tr:hover {
            background: #fafafa;
            transform: scale(1.005);
        }

        .product-img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #ddd;
        }

        .product-name {
            font-weight: 600;
            color: #222;
            font-size: 16px;
        }

        .price {
            color: #27ae60;
            font-size: 18px;
            font-weight: bold;
        }

        .payment {
            font-weight: 600;
            color: #444;
        }

        .badge {
            padding: 10px 18px;
            border-radius: 25px;
            color: #fff;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
        }

        .success {
            background: #28a745;
        }

        .failed {
            background: #dc3545;
        }

        .online {
            color: #2874f0;
            font-weight: bold;
        }

        .cod {
            color: #ff6f00;
            font-weight: bold;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 18px;
        }

        @media(max-width:900px) {

            table,
            thead,
            tbody,
            tr,
            td,
            th {
                display: block;
                width: 100%;
            }

            thead {
                display: none;
            }

            tr {
                background: #fff;
                margin-bottom: 20px;
                border-radius: 15px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, .08);
                padding: 15px;
            }

            td {
                text-align: right;
                position: relative;
                padding-left: 50%;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                font-weight: bold;
                color: #333;
            }
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            background: #ffffff;
            color: #222;
            padding: 12px 22px;
            border-radius: 12px;
            font-weight: 600;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
        }

        .back-btn:hover {
            background: #2874f0;
            color: #fff;
            transform: translateY(-2px);
        }
    </style>

</head>

<body>

    <div class="container">

        <h1 class="heading">🛒 My Orders</h1>
        <a href="javascript:history.back()" class="back-btn">
            ← Back
        </a>


        <div class="order-card">

            <table>

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Payment Mode</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($orders)): ?>

                        <?php foreach ($orders as $index => $order): ?>

                            <tr>

                                <td data-label="S.No">
                                    <?= $index + 1 ?>
                                </td>

                                <td data-label="Image">
                                    <img class="product-img"
                                        src="<?= base_url($order['product_image']) ?>">
                                </td>

                                <td data-label="Product">
                                    <div class="product-name">
                                        <?= esc($order['product_name']) ?>
                                    </div>
                                </td>

                                <td data-label="Price">
                                    <span class="price">
                                        ₹<?= esc($order['price']) ?>
                                    </span>
                                </td>

                                <td data-label="Payment">

                                    <span class="<?= strtolower($order['payment_mode']) ?>">

                                        <?= strtoupper(esc($order['payment_mode'])) ?>

                                    </span>

                                </td>

                                <td data-label="Status">

                                    <span class="badge <?= esc($order['status']) ?>">

                                        <?= ucfirst(esc($order['status'])) ?>

                                    </span>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="empty">
                                No Orders Found 🛍️
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</body>

</html>