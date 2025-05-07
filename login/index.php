<?php
require '../database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';

    if($_POST['digit1'] && $_POST['digit2'] && $_POST['digit3'] && $_POST['digit4']) {
        $digit1 = $_POST['digit1'] ?? '';
        $digit2 = $_POST['digit2'] ?? '';
        $digit3 = $_POST['digit3'] ?? '';
        $digit4 = $_POST['digit4'] ?? '';
        try {
            $verification_code = sprintf("%s%s%s%s", $digit1, $digit2, $digit3, $digit4);
            echo $verification_code;
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND code = ?");
            $stmt->execute([$email, $verification_code]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$user) {
                $error = "NO USER FOUND for: " . htmlspecialchars($email) . " with code: " . htmlspecialchars($verification_code);
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
            exit;
        }
        if ($user) {
            echo "User found: " . htmlspecialchars($user['email']);
            try {
                // Start session and set user ID
                session_start();
                $_SESSION['user_id'] = $user['id'];
                header("Location: /voting");
                echo "Redirecting to voting page...";
                exit;
            } catch (Exception $e) {
                $error = "Error: " . $e->getMessage();
            }
        } else {
            echo "User not found";
            $error .= "Invalid verification code.";
            $verification_sent = true;
        }
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please provide a valid email.";
    } else {
        try {
            // If email already exists, just update the verification code
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Generate a random 4-digit verification code
            $verification_code = rand(1000, 9999);
            if ($user) {
                $stmt = $pdo->prepare("UPDATE users SET code = ? WHERE email = ?");
                $stmt->execute([$verification_code, $email]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO users (email, code) VALUES (?, ?)");
                $stmt->execute([$email, $verification_code]);
            }
            
            $success = "Check your email for verification.";

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
                        <h1>Gleich kannst du bei Ganz Kleines abstimmen!</h1>
                        <p>Danke für deine Registrierung! Bitte verifiziere deine Email durch Eingabe des folgendes Codes:</p>
                        <h2 style="font-size: 24px;">' . $verification_code . '</h2>
                        <p>Wenn du dich nicht bei ganzkleines.de registriert hast, kannst du diese Email ignorieren.</p>
                    </div>
                </body>

            </html>';
            try {
                if(sendEmail($to, $subject, $message)) {
                    $success = "Verification email sent to: " . htmlspecialchars($email);
                    $verification_sent = true;
                } else {
                    $error = "Failed to send verification email.";
                }
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
    <title>Vote</title>
    <?php include '../partials/head.php'; ?>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Signup to Vote</h2>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php elseif (!empty($success)): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>

                    <?php if($verification_sent) { ?>
                            <div class="card shadow-sm mt-4">
                                <div class="card-body">
                                    <h5 class="card-title text-center mb-3">Enter Verification Code</h5>
                                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                        <div class="d-flex justify-content-between mb-3">
                                            <input type="text" name="digit1" maxlength="1" class="form-control text-center fs-4 code-input" style="width: 60px;" required>
                                            <input type="text" name="digit2" maxlength="1" class="form-control text-center fs-4 code-input" style="width: 60px;" required>
                                            <input type="text" name="digit3" maxlength="1" class="form-control text-center fs-4 code-input" style="width: 60px;" required>
                                            <input type="text" name="digit4" maxlength="1" class="form-control text-center fs-4 code-input" style="width: 60px;" required>
                                        </div>
                                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

                                        <div class="d-grid">
                                            <button type="submit" name="verify_code" class="btn btn-primary">Verify</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php } else { ?>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-success">Register</button>
                        </div>

                        <div class="text-center">
                            <a href="/" class="text-decoration-none">Back to Home</a>
                        </div>
                    </form>

                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
</div>


<script>
    const inputs = document.querySelectorAll('.code-input');
    inputs.forEach((input, index) => {
      input.addEventListener('keyup', (e) => {
        if (e.key >= 0 && e.key <= 9) {
          if (index < inputs.length - 1) {
            inputs[index + 1].focus();
            inputs[index + 1].select();
          }
        } else if (e.key === 'Backspace' && index > 0 && !input.value) {
          inputs[index - 1].focus();
          inputs[index + 1].select();
        }
      });
    });
  </script>
</body>
</html>
