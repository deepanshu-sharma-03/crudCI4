<!DOCTYPE html>
<html>

<head>
    <title>Checkout</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fc;
        }

        .checkout-card {
            background: #fff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 25px;
            color: #111827;
        }

        .summary-box {
            background: #111827;
            color: white;
            border-radius: 20px;
            padding: 25px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .15);
        }

        .cart-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .cart-left img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 12px;
        }

        .cart-info h6 {
            margin: 0;
            font-size: 15px;
        }

        .cart-info small {
            color: #cbd5e1;
        }

        input {
            border-radius: 12px !important;
            padding: 13px !important;
        }

        .place-btn {
            width: 100%;
            border: none;
            padding: 14px;
            border-radius: 12px;
            background: linear-gradient(45deg, #2563eb, #1d4ed8);
            color: #fff;
            font-weight: 600;
        }

        .cancel-btn {
            width: 100%;
            border: none;
            padding: 14px;
            border-radius: 12px;
            background: #dc2626;
            color: #fff;
            font-weight: 600;
        }

        .cancel-btn:hover {
            background: #b91c1c;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>


</head>

<body>

    <div class="container py-5">

        <div class="row g-4">

            <!-- LEFT SIDE -->

            <div class="col-lg-8">

                <div class="checkout-card">

                    <h2 class="section-title">
                        Checkout Details
                    </h2>

                    <form>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <input
                                    type="text"
                                    class="form-control name"
                                    placeholder="Full Name" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <input
                                    type="email"
                                    class="form-control email"
                                    placeholder="Email Address" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <input
                                    type="text"
                                    class="form-control number"
                                    placeholder="Phone Number" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Pincode" required>
                            </div>

                            <div class="col-12 mb-3">
                                <textarea
                                    class="form-control address"
                                    placeholder="Enter your full address..."
                                    rows="2" required></textarea>
                            </div>

                        </div>

                        <h5 class="mt-4 mb-3 fw-bold">
                            Payment Method
                        </h5>

                        <div class="form-check mb-2">
                            <input class="form-check-input"
                                type="radio"
                                name="payment"
                                value="cod">

                            <label class="form-check-label">
                                Cash On Delivery
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input"
                                type="radio"
                                name="payment"
                                value="online">

                            <label class="form-check-label">
                                UPI / Debit / Credit Card
                            </label>
                        </div>

                    </form>

                </div>

            </div>


            <!-- RIGHT SIDE -->

            <div class="col-lg-4">

                <div class="summary-box">

                    <h3 class="mb-4">
                        Order Summary
                    </h3>

                    <?php
                    $subtotal = 0;
                    $cartIds = [];
                    ?>
                    <?php if (!empty($data)) : ?>
                        <?php foreach ($data['data'] as $item): ?>

                            <?php
                            $cartIds[] = $item['id'];
                            $total = $item['price'] * $item['quantity'];
                            $subtotal += $total;
                            ?>

                            <div class="cart-item">

                                <div class="cart-left">

                                    <img
                                        src="<?= base_url($item['product_image']) ?>">

                                    <div class="cart-info">

                                        <h6>
                                            <?= ($item['product_name']) ?>
                                        </h6>

                                        <small>
                                            ₹<?= ($item['price']) ?>
                                            ×
                                            <?= ($item['quantity']) ?>
                                        </small>

                                    </div>

                                </div>

                                <strong>
                                    ₹<?= number_format($total) ?>
                                </strong>

                            </div>

                    <?php endforeach;
                    endif; ?>


                    <?php
                    $shipping = 50;

                    $grandTotal = $subtotal + $shipping;
                    ?>


                    <div class="d-flex justify-content-between mt-4">
                        <span>Subtotal</span>
                        <span class="sub-total">₹<?= number_format($subtotal) ?></span>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <span>Shipping</span>
                        <span>₹<?= $shipping ?></span>
                    </div>

                    <hr class="text-light">

                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total</span>
                        <span class="total-amount">
                            ₹<?= number_format($grandTotal) ?>
                        </span>
                    </div>

                    <button type="button" class="place-btn mt-4" onclick=placeOrder()>
                        PLACE ORDER
                    </button>

                    <button
                        onclick="window.history.back()"
                        type="button"
                        class="cancel-btn mt-3">

                        CANCEL

                    </button>

                </div>

            </div>

        </div>

    </div>
    <!-- <h1><?= "ORD" . date('YmdHis') ?></h1> -->
    <script>
        function placeOrder() {

            // Payment Mode
            let paymentMode = document.querySelector('input[name="payment"]:checked')?.value;

            // Address
            let address = document.querySelector(".address").value;

            // Totals
            let subTotal = document.querySelector(".sub-total").innerText.trim();

            let totalAm = document.querySelector(".total-amount").innerText.trim();

            // Cart IDs
            let cartIds = <?= json_encode($cartIds) ?>;

            // Validation

            if (!paymentMode) {
                alert("Select Payment Method");
                return;
            }

            if (!address) {
                alert("Address Required");
                return;
            }

            let payload = {
                payment_mode: paymentMode,
                address: address,
                subtotal: subTotal,
                total_amount: totalAm,
                cart_ids: cartIds
            };

            // ---------- COD FLOW ----------
            if (paymentMode === "cod") {
                $.ajax({
                    url: "<?= base_url('checkout/placeorder') ?>",
                    type: "POST",
                    data: payload,
                    success: function(response) {
                        Swal.fire({
                            title: "Order Placed!",
                            icon: "success",
                            draggable: true
                        }).then(() => {
                            window.location.href =
                                "<?= base_url('user/view-products') ?>";
                        });

                    },
                    error: function() {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "something went wrong",
                        });
                    }
                });
            }

            // ---------- ONLINE FLOW ----------
            else if (paymentMode === "online") {
                $.ajax({
                    url: "<?= base_url('checkout/startPayment') ?>",
                    type: "POST",
                    data: payload,
                    success: function(response) {
                        console.log(response);
                        // gateway logic
                        if (response.status) {
                            let name = document.querySelector('.name').value;
                            let number = document.querySelector('.number').value;
                            let email = document.querySelector('.email').value;
                            var options = {
                                key: "<?= env('RAZORPAY_KEY_ID') ?>",
                                amount: response.amount,
                                currency: 'INR',
                                name: 'My Project',
                                description: 'Order Payment',
                                order_id: response.order_id,
                                prefill: {
                                    name: name,
                                    email: email,
                                    contact: number
                                },
                                retry: {

                                    enabled: false

                                },
                                handler: function(payment) {
                                    Swal.fire({
                                        title: "Order Placed!",
                                        icon: "success",
                                        draggable: true
                                    }).then(() => {
                                        $.ajax({
                                            url: "<?= base_url('/checkout/successPayment') ?>",
                                            type: 'POST',
                                            data: {
                                                'payment-data': payment,
                                                'payload': payload
                                            },
                                            success: function(response) {
                                                if (response.status) {
                                                    window.location.href =
                                                        "<?= base_url('user/view-products') ?>";
                                                }
                                            }
                                        })

                                    });
                                    console.log(payment);
                                },
                                modal: {
                                    ondismiss: function() {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Payment Cancelled',
                                            timer: 1500,
                                            showConfirmButton: false
                                        }).then(() => {
                                            window.location.href = "<?= base_url('/cart') ?>";
                                        });


                                    }
                                }
                            };
                            var rzp = new Razorpay(options);
                            rzp.on('payment.failed', function(response) {

                                console.log(response.error);
                                let paymentId = response.error.metadata.payment_id;
                                let orderId = response.error.metadata.order_id;
                                console.log("payment id : ", paymentId);
                                console.log("Order id :", orderId);

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Payment Failed',
                                    timer: 1500,
                                    text: response.error.description,

                                }).then(() => {
                                    $.ajax({
                                        url: "<?= base_url('/checkout/failedPayment') ?>",
                                        type: 'POST',
                                        data: {
                                            'payment-data': response.error,
                                            'payload': payload
                                        },
                                        success: function(response) {
                                            if (response.status) {
                                                window.location.href =
                                                    "<?= base_url('/cart') ?>";
                                            }
                                        }
                                    })
                                });

                            });
                            rzp.open();
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "something went wrong",
                        });
                    }
                });
            }
        }
    </script>
</body>

</html>