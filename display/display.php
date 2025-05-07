<?php
$name = "Ludwigs";
$subject = "Schwanz";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link id="google-font-link" rel="stylesheet" href="">
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
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
            font-size: 5em;
            position: relative;
        }

        #fab {
            position: absolute;
            right: 50px;
            bottom: 50px;
            text-decoration: none;
            color: inherit;
            
        }
    </style>
    <style>
    .vote-button {
      position: relative;
      display: inline-flex;
      justify-content: center;
      align-items: center;
      width: 250px;
      height: 250px;
      background: #59fec5;
      background: linear-gradient(30deg, #f8ff00 0%, #59fec5 100%);
      color: #182848;
      font-family: 'Arial Black', sans-serif;
      text-align: center;
      cursor: pointer;
      border: none;
      clip-path: url(#star-shape);
      transition: transform 0.3s ease;
      box-shadow: 0 0 0 rgba(0,0,0,0);
      overflow: hidden;
    }

    .vote-button .header {
        font-family: Pacifico, sans-serif;
        font-size: 2.5rem;
        margin: 0;
        margin-bottom: 8px;
    }

    .vote-button .subtitle {
        font-size: 1em;
        padding: 0 15px;
        text-transform: uppercase;
    }

    .vote-button span {
      display: block;
      padding: 0 10px;
      line-height: 1.2;
    }

    .vote-button:hover, .vote-button:focus {
      transform: rotate(5deg) scale(1.05);
    }

    .sparkle {
      position: absolute;
      width: 20px;
      height: 20px;
      background-image: url("data:image/svg+xml,%3Csvg fill='none' xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 345 345'%3E%3Cpath d='M172.5 0L201.408 143.592L345 172.5L201.408 201.408L172.5 345L143.592 201.408L0 172.5L143.592 143.592L172.5 0Z' fill='%23fff'/%3E%3C/svg%3E");
      animation: sparkle 1s infinite ease-in-out alternate;
      opacity: 0.8;
    }

    @keyframes sparkle {
      0% { transform:  scale(1); opacity: 0.8; }
      70% { transform: scale(0.5) rotate(120deg); opacity: 0; }
      100% { transform: scale(1) rotate(0); opacity: 0; }
    }

    /* Random sparkle positions */
    .sparkle:nth-child(2) { top: 7%; left: 66%; animation-delay: 0.3s; }
    .sparkle:nth-child(3) { top: 82.1%; left: 64.3%; animation-delay: 0.6s; }
    .sparkle:nth-child(4) { top: 52%; left: 0; animation-delay: 0.9s; }

  </style>

    <?php include 'display_inner.php'; ?>

    <a id="fab" href="/voting">
        <svg width="0" height="0">
        <defs>
            <clipPath id="star-shape" clipPathUnits="objectBoundingBox">
            <path d="M0.5 0L0.584 0.107L0.703 0.044L0.737 0.176L0.872 0.167L0.849 0.301L0.976 0.348L0.901 0.461L1 0.556L0.883 0.629L0.933 0.758L0.799 0.777L0.794 0.912L0.664 0.875L0.604 1L0.5 0.91L0.396 1L0.336 0.875L0.206 0.912L0.201 0.777L0.067 0.758L0.117 0.629L0 0.556L0.099 0.461L0.024 0.348L0.151 0.301L0.128 0.167L0.263 0.176L0.297 0.044L0.416 0.107L0.5 0Z" />
            </clipPath>
        </defs>
        </svg>

        <button class="vote-button">
        <span><h3 class="header">Vote now!</h3><span class="subtitle">for next month's <i>Ganz Kleines</i></span></span>
        <div class="sparkle"></div>
        <div class="sparkle"></div>
        <div class="sparkle"></div>
        </button>
    </a>

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
  <script>
    const button = document.getElementById('fab');

    let offsetX = 0;
    let offsetY = 0;
    let targetX = 0;
    let targetY = 0;
    let currentVectorX = 0;
    let currentVectorY = 0;
    let animationFrame;
    let isHovered = false;
    let start;

    const smoothing = 0.001;

    // Function to smoothly update position
    function animate(timestamp) {
        if (start === undefined) {
            start = timestamp;
        }

        const ellapsed = (timestamp - start) * 0.005;
        start = timestamp;

        // Compute target vector
        let targetVectorX = targetX - offsetX;
        let targetVectorY = targetY - offsetY;
        const targetVectorLength = Math.hypot(targetVectorX, targetVectorY);

        if (targetVectorLength > 0) {
            targetVectorX /= targetVectorLength;
            targetVectorY /= targetVectorLength;
            targetVectorX *= Math.max(targetVectorLength, 3);
            targetVectorY *= Math.max(targetVectorLength, 3);
        }

        // Smoothly interpolate current vector towards target vector
        currentVectorX += (targetVectorX - currentVectorX) * smoothing;
        currentVectorY += (targetVectorY - currentVectorY) * smoothing;

        // Apply movement
        offsetX += currentVectorX * ellapsed;
        offsetY += currentVectorY * ellapsed;

        button.style.transform = `translate(${offsetX}px, ${offsetY}px)`;

        // If not hovered, keep animating
        if (!isHovered) {
            animationFrame = requestAnimationFrame(animate);
        }

        const distance = Math.hypot(targetX - offsetX, targetY - offsetY);

        // If distance is increasing or within 3px threshold — pick a new target
        if (distance < 3) {
            console.log("NEW TARGET")
            if(targetX == 0) {
                setRandomTarget()
            } else {
                targetX = 0;
                targetY = 0;
            }
        } else {
            lastDistance = distance;
        }
    }

    // Function to generate a new random target position
    function setRandomTarget() {
        // Random between -10px and +10px
        targetX = Math.random() * 10 - 5;
        targetX += Math.sign(targetX) * 6;
        targetY = Math.random() * 10 - 5;
        targetY += Math.sign(targetY) * 6;
    }

    // Hover events
    button.addEventListener('mouseenter', () => {
        isHovered = true;
        cancelAnimationFrame(animationFrame);
    });

    button.addEventListener('mouseleave', () => {
        isHovered = false;
        setRandomTarget();
        start = undefined;
        animationFrame = requestAnimationFrame(animate);
    });

    // Start animation
    setRandomTarget();
    animationFrame = requestAnimationFrame(animate);
  </script>
</body>
</html>