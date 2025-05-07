<?php
require 'database.php';

// CHECK IF USER IS LOGGED IN
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login");
    exit;
}
// CHECK IF USER HAS ALREADY VOTED
$stmt = $pdo->prepare("SELECT * FROM votes WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$vote = $stmt->fetch(PDO::FETCH_ASSOC);

try {
    $votes = [];
    // GET ALL VOTES
    $stmt = $pdo->query("SELECT * FROM votes ORDER BY created_at DESC");    
    $stmt->execute();
    $votes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $names = array_column($votes, 'name');
    $subjects = array_column($votes, 'subject');
    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $name = $_POST['name'] ?? '';
        $subject = $_POST['subject'] ?? '';
        $user_id = $_SESSION['user_id'] ?? null;

        if(!empty($vote)) {
            $stmt = $pdo->prepare("UPDATE votes SET name = ?, subject = ? WHERE user_id = ?");
            $stmt->execute([$name, $subject, $user_id]);

            $success = "Vote updated successfully!";
            echo "Redirecting to home page...";
            header("Location: /");
            exit;
        } else {
            if (!empty($name) && !empty($subject) && $user_id) {
                $stmt = $pdo->prepare("INSERT INTO votes (user_id, name, subject) VALUES (?, ?, ?)");
                $stmt->execute([$user_id, $name, $subject]);

                $success = "Vote submitted successfully!";
                echo "Redirecting to home page...";
                header("Location: /");
                exit;
            } else {
                $error = "Please provide a name and a subject.";
            }
        }
    }
} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Voting Page</title>
    <?php include './partials/head.php'; ?>
</head>

<body>
<div class="container py-5">

  <div class="card shadow-sm border-0 rounded-3 mb-5">
    <div class="card-body p-5">
      <h1 class="mb-4 text-center">Vote for a Subject</h1>

      <form method="POST" action="voting.php" class="row g-3">
        <?php if (!empty($error)): ?>
          <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error); ?>
          </div>
        <?php elseif (!empty($success)): ?>
          <div class="alert alert-success" role="alert">
            <?php echo htmlspecialchars($success); ?>
          </div>
        <?php endif; ?>

        <?php if(!empty($vote)): ?>
          <div class="alert alert-warning" role="alert">
            You have already voted for <strong><?php echo htmlspecialchars($vote['name']); ?> <?php echo htmlspecialchars($vote['subject']); ?></strong>. <br>
            If you want to change your vote, submit the voting form again.
        </div>
        <?php endif; ?>

        <div class="col-md-6 position-relative">
          <input id="nameSearch" type="text" name="name" class="form-control rounded-2 px-4 py-3" placeholder="Name" autocomplete="off" required>
          <div id="nameList" class="dropdown-menu custom-dropdown mt-2 w-100"></div>
        </div>

        <div class="col-md-6 position-relative">
          <input id="subjectSearch" type="text" name="subject" class="form-control rounded-2 px-4 py-3" placeholder="Subject" autocomplete="off" required>
          <div id="subjectList" class="dropdown-menu custom-dropdown mt-2 w-100"></div>
        </div>

        <div class="col-12 d-grid mt-3">
          <button type="submit" class="btn btn-primary btn-lg rounded-2">Vote</button>
        </div>
      </form>

      <div class="text-center mt-4">
        <a href="/" class="btn btn-link">Back to Home</a>
      </div>

    </div>
  </div>
  <div class="card shadow-sm border-0 rounded-3 mb-5">
    <div class="card-body p-5" style="font-size: 2em;">
        <?php include './display/display_inner.php'; ?>
    </div>
  </div>

  <style>
    .custom-dropdown {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      max-height: 220px;
      overflow-y: auto;
      padding: 0.75rem;
      border-radius: 0.75rem;
      box-shadow: 0 0.75rem 1.5rem rgba(0,0,0,0.1);
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    .custom-dropdown.hide-dropdown {
      display: none;
    }
    .custom-dropdown.show-dropdown {
      opacity: 1;
    }

    .custom-badge {
      display: inline-block;
      padding: 0.5rem 1rem;
      margin: 0.3rem;
      font-size: 0.95rem;
      cursor: pointer;
      border-radius: 50rem;
      background: linear-gradient(135deg, #dee2e6, #f8f9fa);
      color: #212529;
      box-shadow: 0 0.35rem 0.75rem rgba(0,0,0,0.05);
      transition: all 0.25s ease;
      opacity: 1;
    }
    .custom-badge.blue:hover {
      background: linear-gradient(135deg, #0d6efd, #0dcaf0);
      color: #fff;
      transform: scale(1.05);
    }
    .custom-badge.red:hover {
      background: linear-gradient(135deg, #dc3545, #fd7e14);
      color: #fff;
      transform: scale(1.05);
    }

    .custom-dropdown > .custom-badge {
      transition: all 0.3s ease;
    }
  </style>

  <script>
    function setupSearch(inputId, listId, dataArray, blueClass = false) {
      const input = document.getElementById(inputId);
      const list = document.getElementById(listId);

      function populateList() {
        list.innerHTML = '';
        dataArray.forEach(item => {
          const badge = document.createElement('span');
          badge.className = 'custom-badge ' + (blueClass ? 'blue' : 'red');
          badge.textContent = item;
          badge.addEventListener('click', function() {
            input.value = this.textContent.trim();
            input.dispatchEvent(new Event('input')); // Trigger input event
            list.classList.remove('show-dropdown');
            list.classList.add('hide-dropdown');
          });
          list.appendChild(badge);
        });
      }

      populateList();

      input.addEventListener('focus', () => {
        list.classList.remove('hide-dropdown');
        list.classList.add('show-dropdown');
      });

      input.addEventListener('blur', () => {
        list.classList.remove('show-dropdown');
        setTimeout(() => {if(!list.classList.contains('show-dropdown')) list.classList.add('hide-dropdown'); }, 300);
      });

      input.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const badges = list.getElementsByClassName('custom-badge');

        Array.from(badges).forEach(function(badge) {
          const text = badge.textContent.toLowerCase();
          if (text.includes(filter)) {
            badge.style.display = 'inline-block';
          } else {
            badge.style.display = 'none';
          }
        });
      });
    }

    function setupPreview(inputId, targetId, callback = null) {
        const input = document.getElementById(inputId);
        const target = document.getElementById(targetId);
        input.addEventListener('input', function() {
            target.textContent = this.value;
            if (callback) {
                callback();
            }
        })
    }

    // PHP arrays passed via JSON
    const names = <?php echo json_encode($names); ?>;
    const subjects = <?php echo json_encode($subjects); ?>;

    setupSearch('nameSearch', 'nameList', names, true);
    setupPreview('nameSearch', 'name');
    setupSearch('subjectSearch', 'subjectList', subjects, false);
    setupPreview('subjectSearch', 'subject', updateSubjectWidth);
  </script>

</div>
</body>

</html>