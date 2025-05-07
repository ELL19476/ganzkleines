<?php
require '../database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Both fields required.";
    } else {
        $hashedPassword = password_hash(password: $password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (email, password, is_verified) VALUES (?, ?, 0)");
        try {
            $stmt->execute([$email, $hashedPassword]);
            $success = "Check your email for verification.";
            $verification_code = rand(1000, 9999);
            $stmt = $pdo->prepare("UPDATE users SET verification_code = ? WHERE email = ?");
            $stmt->execute([$verification_code, $email]);
            $verification_sent = true;

            require '../mail.php';
            // Send verification email
            $to = $email;
            $subject = "Email Verification";
            $message = '<!DOCTYPE html>
                <html lang="en">

                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Email</title>
                </head>

                <body>
                    <div style="text-align: center; padding: 20px;">
                        <h1>Welcome to Our Service</h1>
                        <p>Thank you for registering! Please verify your email address by entering the code below:</p>
                        <h2 style="font-size: 24px;">' . $verification_code . '</h2>
                        <p>If you did not register, please ignore this email.</p>
                    </div>
                </body>

            </html>';
            try {
                sendEmail($to, $subject, $message);
                $success = "Verification email sent.";
            } catch (Exception $e) {
                $error = "Error sending email: " . $e->getMessage();
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <?php include '../partials/head.php'; ?>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Register</h2>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php elseif (!empty($success)): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <?php if($verification_sent) { ?>
                            <div class="card shadow-sm mt-4">
                                <div class="card-body">
                                    <h5 class="card-title text-center mb-3">Enter Verification Code</h5>
                                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                        <div class="d-flex justify-content-between mb-3">
                                            <input type="text" name="digit1" maxlength="1" class="form-control text-center fs-4" style="width: 60px;" required>
                                            <input type="text" name="digit2" maxlength="1" class="form-control text-center fs-4" style="width: 60px;" required>
                                            <input type="text" name="digit3" maxlength="1" class="form-control text-center fs-4" style="width: 60px;" required>
                                            <input type="text" name="digit4" maxlength="1" class="form-control text-center fs-4" style="width: 60px;" required>
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" name="verify_code" class="btn btn-primary">Verify</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        <?php } ?>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-success">Register</button>
                        </div>

                        <div class="text-center">
                            <a href="/" class="text-decoration-none">Back to Home</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS and Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
