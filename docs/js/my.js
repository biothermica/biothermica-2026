function fillWithHexagons(selector, options = {}) {
  const container = document.querySelector(selector);

  if (!container) {
    console.error(`Container not found: ${selector}`);
    return;
  }

  const {
    hexWidth = 211.2,
    hexHeight = 240,
    spacing = 0,
    startX = 0,
    startY = 0,
    className = 'hexagon'
  } = options;

  // Ensure container can position children
  if (getComputedStyle(container).position === 'static') {
    container.style.position = 'relative';
  }

  container.style.overflow = 'hidden';

  const containerWidth = container.clientWidth;
  const containerHeight = container.clientHeight;

  // Honeycomb geometry with adjustable spacing
  const horizontalStep = hexWidth + spacing;
  const verticalStep = (hexHeight * 0.75) + spacing;

  const cols = Math.ceil((containerWidth - startX) / horizontalStep) + 3;
  const rows = Math.ceil((containerHeight - startY) / verticalStep) + 3;

  for (let row = 0; row < rows; row++) {

    // Offset every other row
    const rowOffset = (row % 2) * (horizontalStep / 2);

    for (let col = 0; col < cols; col++) {

      const hex = document.createElement('div');
      hex.className = className;

      hex.style.position = 'absolute';

      const x = (col * horizontalStep + rowOffset) + startX;
      const y = (row * verticalStep) + startY;

      hex.style.left = `${x}px`;
      hex.style.top = `${y}px`;

      if (Math.random() < (rows - row) / rows * col / cols * 1.4) {
        container.appendChild(hex);
      }
    }
  }
}

fillWithHexagons('#main-wrapper', {spacing: 14, startX: -100, startY: -100});
