<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link id="google-font-link" rel="stylesheet" href="">
  <title>Ludwigs Schwanz</title>
  <?php include '../partials/head.php'; ?>
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

    .text {
      text-align: center;
      display: flex;
      justify-content: space-between;
    }

    .highlight-wrapper {
      display: inline-block;
      overflow: hidden;
      position: relative;
      display: inline-block;
      margin-left: 0.3em;
      margin-bottom: 1em;

      width: 272px;
      height: 3em;
      text-align: left;

    }
    .highlight {
      color: black;
      display: inline-block;
      transition: transform 0.3s ease;
      font-style: italic;
    }

    .highlight-wrapper:hover .highlight {
      transform: scale(0.4); /* Zoom out */
    }
  </style>
</head>
<body>
  <div class="text">
    <span>Ludwigs</span> 
    <span class="highlight-wrapper"><span id="schwanz" class="highlight">Schwanz</span></span>
  </div>

  <script>
    // Optional: log when hovered for debug
    const schwanz = document.getElementById('schwanz');
    schwanz.addEventListener('mouseover', () => {
      console.log('Hovering over Schwanz');
    });

    const fonts = [
      'Roboto',
      'Lobster',
      'Mea Culpa',
      'Pacifico',
      'Playfair Display',
      'Bebas Neue',
      'Oswald',
      'Monoton',
      'Roboto Mono',
      'Orbitron',
      'Abril Fatface'
    ];

    let interval;
    let index = 0;

      function cycleFont() {
        schwanz.style.fontFamily = `"${fonts[index]}"`;
        index = (index + 1) % fonts.length;
      }

    // Add all fonts to document head
    fonts.forEach(font => {
      const link = document.createElement('link');
      link.rel = 'stylesheet';
      const fontName = font.replace(/ /g, '+');
      link.href = `https://fonts.googleapis.com/css2?family=${fontName}&display=swap`;
      document.head.appendChild(link);
    });

    // Wait until all fonts are loaded using FontFaceSet API
    Promise.all(
      fonts.map(font => document.fonts.load(`1em "${font}"`))
    ).then(() => {
      console.log('All fonts loaded');

      // Start cycling every second
      cycleFont(); // Initial
      interval = setInterval(cycleFont, 120);
    }).catch(err => {
      console.error('Font loading error:', err);
    });

    // On Hover stop cycling
    const highlightWrapper = document.querySelector('.highlight-wrapper');
    highlightWrapper.addEventListener('mouseover', () => {
      clearInterval(interval);
    });
    highlightWrapper.addEventListener('mouseout', () => {
      interval = setInterval(cycleFont, 120);
    });
  </script>
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