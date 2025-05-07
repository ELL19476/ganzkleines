<?php
$name = "Ludwigs";
$subject = "Schwanz";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link id="google-font-link" rel="stylesheet" href="">
  <title><?php echo $name . " " . $subject ?></title>
  <?php include '../partials/head.php'; ?>
</head>
<body>
    <style>
        body {
        margin: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: white;
        font-family: Arial, sans-serif;
        font-size: 3em;
        }
    </style>
    <?php include './display_inner.php'; ?>

    <script>
        // List of emojis for the custom cursor
        const emojis = ['🍆', '🥕', '🌭', '🍌'];

        // Function to generate a data URL for a given emoji
        function createEmojiCursor(emoji) {
        const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"><text x="0" y="20" font-size="20">${emoji}</text></svg>`;
        const base64 = btoa(unescape(encodeURIComponent(svg))); // encode the SVG to base64
        return `url('data:image/svg+xml;base64,${base64}') 12 12, auto`;
        }

        // Function to set random emoji cursor
        function setRandomEmojiCursor() {
        const randomEmoji = emojis[Math.floor(Math.random() * emojis.length)];
        document.body.style.cursor = createEmojiCursor(randomEmoji);
        }

        // Set the cursor when the page loads
        setRandomEmojiCursor();
  </script>
</body>
</html>