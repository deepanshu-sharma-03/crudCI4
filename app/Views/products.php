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

        .product-switch {
            position: relative;
            display: inline-block;
            width: 55px;
            height: 28px;
        }

        .product-switch input {
            display: none;
        }

        .product-slider {
            position: absolute;
            cursor: pointer;
            background-color: #dc3545;
            border-radius: 34px;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            transition: .4s;
        }

        .product-slider::before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            border-radius: 50%;
            transition: .4s;
        }

        .product-switch input:checked+.product-slider {
            background-color: #198754;
        }

        .product-switch input:checked+.product-slider::before {
            transform: translateX(27px);
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>

    <div class="container product-container">

        <div class="card-box">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h2 class="heading">All Products</h2>

                <a href="/add-product" class="btn btn-primary">
                    + Add Product
                </a>

            </div>

            <table class="table table-bordered table-hover">

                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Status</th>
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

                                <label class="product-switch">

                                    <input
                                        type="checkbox"
                                        class="toggleStatus"
                                        data-id="<?= $product['id'] ?>"
                                        data-status="<?= $product['status'] ?>"
                                        <?= ($product['status'] == 'active') ? 'checked' : '' ?>>

                                    <span class="product-slider"></span>

                                </label>

                            </td>
                            <td>

                                <div class="btn-action justify-content-center">

                                    <a
                                        href="<?= base_url('edit/' . $product['id']) ?>"
                                        class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <a
                                        href="<?= base_url('delete-product/' . $product['id']) ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this product?')">
                                        Delete
                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>
    <script>
        $(document).on('change', '.toggleStatus', function() {

            let toggle = $(this);

            let productId = toggle.data('id');

            let currentStatus = toggle.attr('data-status');

            $.ajax({

                url: "<?= base_url('product/toggle-status') ?>",

                type: "POST",

                data: {
                    id: productId,
                    status: currentStatus
                },

                success: function(response) {

                    toggle.attr('data-status', response.status);

                    if (response.status == 'active') {
                        toggle.prop('checked', true);
                    } else {
                        toggle.prop('checked', false);
                    }

                }

            });

        });
    </script>

</body>

</html>