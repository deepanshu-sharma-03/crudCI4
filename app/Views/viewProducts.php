<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Products</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fc;
        }

        .product-container {
            margin-top: 50px;
        }

        .card-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }

        .table img {
            border-radius: 10px;
            object-fit: cover;
        }

        .heading {
            font-weight: bold;
            color: #1e293b;
        }

        .btn-action {
            display: flex;
            gap: 10px;
        }

        .table th {
            background: #0d6efd;
            color: white;
            text-align: center;
        }

        .table td {
            vertical-align: middle;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="container product-container">

        <div class="card-box">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h2 class="heading"> Products</h2>

            </div>

            <table class="table table-bordered table-hover">

                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    <?php $index = 1;

                    foreach ($products as $product): ?>

                        <tr>

                            <td>
                                <?= $index++      ?>
                            </td>

                            <td>
                                <img
                                    src="<?= base_url($product['product_image']) ?>"
                                    width="120"
                                    height="100">
                            </td>

                            <td>
                                <?= $product['product_name'] ?>
                            </td>

                            <td>
                                ₹<?= $product['price'] ?>
                            </td>
                            <td>

                                <a
                                    href="<?= base_url('/user-cart') ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="">
                                    Add to cart
                                </a>


                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>


</body>

</html>