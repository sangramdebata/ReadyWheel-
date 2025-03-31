<?php
session_start();
require_once '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Check if payment data is provided
if (!isset($_POST['total_amount']) || !isset($_POST['car_id'])) {
    header('Location: rent.php');
    exit();
}

$total_amount = $_POST['total_amount'];
$car_id = $_POST['car_id'];
$pickup_date = $_POST['pickup_date'];
$dropoff_date = $_POST['dropoff_date'];
$pickup_location = $_POST['pickup_location'];

// Generate QR code data
$qr_data = "upi://pay?pa=readywheel@upi&pn=ReadyWheel&am=" . $total_amount . "&cu=INR&tn=Car Rental Payment";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - ReadyWheel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="icon" type="image/png" href="../assets/logo-white.png">
    <link rel="shortcut icon" href="favicon.ico">
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <style>
        body {
            background: linear-gradient(
                to bottom,
                rgba(67, 51, 237, 0.4),
                rgba(165, 150, 247, 0.1)
            );
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .payment-container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 24px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.06);
            padding: 35px;
            backdrop-filter: blur(10px);
        }
        .amount-display {
            background: linear-gradient(135deg, #8a79f0, #2e2a40);
            color: white;
            padding: 25px;
            border-radius: 18px;
            margin-bottom: 35px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(65, 88, 208, 0.2);
            position: relative;
            overflow: hidden;
        }
        .amount-display::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
            z-index: 1;
        }
        .amount-display h3 {
            margin: 0;
            font-size: 36px;
            font-weight: 700;
            position: relative;
            z-index: 2;
        }
        .amount-display p {
            margin: 0 0 5px 0;
            font-size: 14px;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .payment-method {
            text-align: center;
            padding: 20px 15px;
            border: 2px solid #eaecef;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
        }
        .payment-method:hover {
            border-color: #4158d0;
            background-color: #f8f9ff;
            transform: translateY(-4px);
            box-shadow: 0 6px 20px rgba(65, 88, 208, 0.15);
        }
        .payment-method.selected {
            border-color: #4158d0;
            background-color: #f8f9ff;
            box-shadow: 0 6px 20px rgba(65, 88, 208, 0.15);
        }
        .payment-method img {
            width: 45px;
            height: 45px;
            margin-bottom: 12px;
            transition: transform 0.3s ease;
        }
        .payment-method:hover img {
            transform: scale(1.1);
        }
        .payment-method h6 {
            margin: 0 0 5px 0;
            font-weight: 600;
            color: #1a1f36;
        }
        .payment-method p {
            margin: 0;
            font-size: 13px;
            color: #697386;
        }
        .payment-details {
            display: none;
            margin-top: 25px;
            padding: 25px;
            background: #f8f9ff;
            border-radius: 16px;
            border: 1px solid #eaecef;
        }
        .payment-details.active {
            display: block;
            animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .qr-container {
            text-align: center;
            margin-top: 20px;
        }
        #qrcode {
            display: inline-block;
            padding: 20px;
            background: white;
            border-radius: 16px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .qr-instructions {
            color: #697386;
            font-size: 14px;
            margin-top: 15px;
        }
        .btn-pay {
            background: linear-gradient(135deg, #8a79f0, #2e2a40);
            border: none;
            padding: 16px 30px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            margin-top: 25px;
            border-radius: 12px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .btn-pay::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
            z-index: 1;
        }
        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(65, 88, 208, 0.25);
        }
        .btn-pay:active {
            transform: translateY(0);
        }
        @keyframes fadeIn {
            from { 
                opacity: 0; 
                transform: translateY(-10px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }
        .payment-gateway {
            text-align: center;
            margin-bottom: 30px;
        }
        .payment-gateway h5 {
            color: #1a1f36;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .gateway-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .gateway-option {
            padding: 20px 15px;
            border: 1px solid #eaecef;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
        }
        .gateway-option:hover {
            border-color: #4158d0;
            transform: translateY(-4px);
            box-shadow: 0 6px 20px rgba(65, 88, 208, 0.15);
        }
        .gateway-option img {
            height: 35px;
            margin-bottom: 12px;
            transition: transform 0.3s ease;
        }
        .gateway-option:hover img {
            transform: scale(1.1);
        }
        .gateway-option p {
            margin: 0;
            color: #697386;
            font-size: 13px;
            font-weight: 500;
        }
        .form-control, .form-select {
            border: 2px solid #eaecef;
            border-radius: 12px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #4158d0;
            box-shadow: 0 0 0 4px rgba(65, 88, 208, 0.1);
        }
        label {
            font-size: 14px;
            font-weight: 500;
            color: #1a1f36;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="amount-display">
            <p class="mb-1">Amount to Pay</p>
            <h3>₹<?php echo number_format($total_amount, 2); ?></h3>
        </div>

        <div class="payment-gateway">
            <h5>Select Payment Gateway</h5>
            <div class="gateway-options">
                <div class="gateway-option" onclick="selectGateway('razorpay')">
                    <img src="../assets/Razorpay_logo.png" alt="Razorpay">
                    <p>Razorpay</p>
                </div>
                <div class="gateway-option" onclick="selectGateway('paytm')">
                    <img src="../assets/Paytm_logo.jpg" alt="Paytm">
                    <p>Paytm</p>
                </div>
                <div class="gateway-option" onclick="selectGateway('sbipay')">
                    <img src="../assets/SBI-logo.svg.png" alt="SBI ePay">
                    <p>SBI ePay</p>
                </div>
                <div class="gateway-option" onclick="selectGateway('phonepe')">
                    <img src="../assets/Phone-pay.png" alt="PhonePe">
                    <p>PhonePe (You)</p>
                </div>
            </div>
        </div>

        <div class="payment-methods">
            <div class="payment-method" onclick="selectPayment('upi')">
                <img src="../assets/upi.png" alt="UPI">
                <h6>UPI</h6>
                <p>Google Pay, PhonePe, etc.</p>
            </div>
            <div class="payment-method" onclick="selectPayment('card')">
                <img src="../assets/debit.png" alt="Card">
                <h6>Card</h6>
                <p>Credit/Debit Card</p>
            </div>
            <div class="payment-method" onclick="selectPayment('netbanking')">
                <img src="../assets/net.jpg" alt="Net Banking">
                <h6>Net Banking</h6>
                <p>All Banks</p>
            </div>
        </div>

        <!-- QR Code Section -->
        <div class="payment-details" id="upi-details">
            <div class="qr-container">
                <div id="qrcode"></div>
                <p class="qr-instructions">Scan QR code with any UPI app to pay</p>
                <div class="mt-3">
                    <label for="upiId">Or enter UPI ID</label>
                    <input type="text" class="form-control" id="upiId" placeholder="Enter your UPI ID">
                </div>
            </div>
        </div>

        <!-- Card Details Section -->
        <div class="payment-details" id="card-details">
            <div class="row g-3">
                <div class="col-12">
                    <input type="text" class="form-control" placeholder="Card Number">
                </div>
                <div class="col-6">
                    <input type="text" class="form-control" placeholder="MM/YY">
                </div>
                <div class="col-6">
                    <input type="password" class="form-control" placeholder="CVV">
                </div>
            </div>
        </div>

        <!-- Net Banking Section -->
        <div class="payment-details" id="netbanking-details">
            <select class="form-select">
                <option value="">Select Bank</option>
                <option value="sbi">State Bank of India</option>
                <option value="hdfc">HDFC Bank</option>
                <option value="icici">ICICI Bank</option>
                <option value="axis">Axis Bank</option>
            </select>
        </div>

        <form id="paymentForm" action="process-payment.php" method="POST">
            <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">
            <input type="hidden" name="pickup_date" value="<?php echo $pickup_date; ?>">
            <input type="hidden" name="dropoff_date" value="<?php echo $dropoff_date; ?>">
            <input type="hidden" name="pickup_location" value="<?php echo $pickup_location; ?>">
            <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">
            <input type="hidden" name="payment_method" id="payment_method">
            <input type="hidden" name="payment_gateway" id="payment_gateway">
            <button type="submit" class="btn btn-primary btn-pay">Proceed to Pay</button>
        </form>
    </div>

    <script>
        // Generate QR code
        new QRCode(document.getElementById("qrcode"), {
            text: "<?php echo $qr_data; ?>",
            width: 180,
            height: 180
        });

        function selectPayment(method) {
            // Remove selected class from all methods
            document.querySelectorAll('.payment-method').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Hide all payment details
            document.querySelectorAll('.payment-details').forEach(details => {
                details.classList.remove('active');
            });
            
            // Show selected payment details
            const selectedOption = event.currentTarget;
            selectedOption.classList.add('selected');
            const details = document.getElementById(method + '-details');
            if (details) {
                details.classList.add('active');
            }
            
            // Set payment method
            document.getElementById('payment_method').value = method;
        }

        function selectGateway(gateway) {
            document.querySelectorAll('.gateway-option').forEach(option => {
                option.style.borderColor = '#eaecef';
            });
            event.currentTarget.style.borderColor = '#4158d0';
            document.getElementById('payment_gateway').value = gateway;
        }
    </script>
</body>
</html> 