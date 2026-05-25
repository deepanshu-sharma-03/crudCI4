<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Product</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            background: #eef3ff;
        }

        .product-form-card {
            max-width: 500px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            text-align: center;
            margin-bottom: 25px;
            font-weight: bold;
            color: #0d6efd;
        }

        .btn-submit {
            width: 100%;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>

    <div class="container">

        <div class="product-form-card">

            <h2 class="form-title">
                Edit Product
            </h2>

            <form
                id="editForm"
                method="POST"
                enctype="multipart/form-data">

                <!-- PRODUCT NAME -->
                <div class="mb-3">

                    <label class="form-label">
                        Product Name
                    </label>

                    <input
                        type="text"
                        value="<?= $product['product_name'] ?>"
                        name="product_name"
                        class="form-control"
                        placeholder="Enter Product Name"
                        required>

                </div>



                <!-- PRODUCT PRICE -->
                <div class="mb-3">

                    <label class="form-label">
                        Product Price
                    </label>

                    <input
                        type="text"
                        name="price"
                        value="<?= $product['price'] ?>"
                        class="form-control"
                        placeholder="Enter Product Price"
                        required>
                </div>
                <!-- PRODUCT Quantity -->
                <div class="mb-3">

                    <label class="form-label">
                        Product Quantity
                    </label>

                    <input
                        type="number"
                        name="quantity"
                        value="<?= $product['product_qty'] ?>"
                        class="form-control"
                        placeholder="Enter Product Quantity"
                        required>
                </div>


                <!-- PRODUCT IMAGE -->
                <div class="mb-4">

                    <label class="form-label">
                        Product Image
                    </label>
                    <img
                        src="<?= base_url($product['product_image']) ?>"
                        width="100">
                    <input
                        type="file"
                        name="product_image"

                        class="form-control"
                        accept="image/*">

                </div>


                <!-- SUBMIT BUTTON -->
                <div class="d-flex gap-3">

                    <button
                        type="submit"
                        class="btn btn-primary w-50">
                        Save
                    </button>

                    <button
                        type="button"
                        onclick="cancel()"
                        class="btn btn-success w-50">
                        Cancel
                    </button>

                </div>

            </form>

        </div>

    </div>
    <script>
        $(document).ready(function() {

            $('#editForm').submit(function(e) {

                // PAGE RELOAD STOP
                e.preventDefault();

                // FORM DATA
                let formData = new FormData(this);

                $.ajax({

                    url: "<?= base_url('/update-product/' . $product['id']) ?>",

                    type: "POST",

                    data: formData,

                    processData: false,

                    contentType: false,

                    success: function(response) {

                        if (response.status) {

                            alert(response.message);

                            // REDIRECT
                            window.location.href =
                                "<?= base_url('/products') ?>";
                        }
                    },

                    error: function(error) {

                        console.log(error);

                        alert('Something went wrong');

                    }

                });

            });

        });

        function cancel() {
            window.location.href =
                "<?= base_url('/products') ?>";
        }
    </script>

</body>

</html>