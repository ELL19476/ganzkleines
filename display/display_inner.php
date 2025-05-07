    <style>
    .text {
      text-align: center;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .highlight-wrapper {
      display: inline-block;
      overflow: hidden;
      position: relative;
      display: inline-block;
      margin-left: 0.3em;

      height: auto;
      text-align: left;

    }
    .highlight {
      display: inline-block;
      transition: transform 0.3s ease;
      font-style: italic;
    }

    .highlight-wrapper:hover .highlight {
      transform: scale(0.4); /* Zoom out */
    }
  </style>
  <div class="text">
    <span id="name"><?php echo $name; ?></span> 
    <span class="highlight-wrapper"><span id="subject" class="highlight"><?php echo $subject; ?></span></span>
  </div>

  <script>
    // Optional: log when hovered for debug
    const subject = document.getElementById('subject');

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
        subject.style.fontFamily = `"${fonts[index]}"`;
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

  function updateSubjectWidth() {
      // change width to amount of letters (e.g. 5 letters = 5ch)
      const text = subject.textContent;
      highlightWrapper.style.transition = 'width 0.3s ease';
      const width = text.length + 4;
      highlightWrapper.style.width = `${width}ch`;
  }
  updateSubjectWidth();

</script>