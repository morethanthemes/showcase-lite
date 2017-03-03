var _svgNS = 'http://www.w3.org/2000/svg';

// Pentagon Shape
// Create svg tag for pentagon clipPath and append it to <body> tag.
var svgPentagon = document.createElementNS(_svgNS, 'svg');
svgPentagon.setAttribute('width', '0');
svgPentagon.setAttribute('height', '0');
svgPentagon.setAttribute('class', 'svg-clip-path-pentagon');
document.body.appendChild(svgPentagon);

// Create defs tag and append it to <svg> tag.
var defsPentagon = document.createElementNS(_svgNS, 'defs');
svgPentagon.appendChild(defsPentagon);

// Create clipPath tag and append it to <defs> tag.
var clippathPentagon = document.createElementNS(_svgNS, 'clipPath');
clippathPentagon.setAttributeNS(null, 'id', 'clip-pentagon');
clippathPentagon.setAttributeNS(null, 'clipPathUnits', 'objectBoundingBox');
defsPentagon.appendChild(clippathPentagon);

// Create polygon tag and append it to <clipPath> tag.
var pentagon = document.createElementNS(_svgNS, 'polygon');
pentagon.setAttributeNS(null, 'points', '0.5 0, 1 0.38, 0.82 1, 0.18 1, 0 0.38');
clippathPentagon.appendChild(pentagon);

// Circle Shape
// Create svg tag for circle clipPath and append it to <body> tag.
var svgCircle = document.createElementNS(_svgNS, 'svg');
svgCircle.setAttribute('width', '0');
svgCircle.setAttribute('height', '0');
svgCircle.setAttribute('class', 'svg-clip-path-circle');
document.body.appendChild(svgCircle);

// Create defs tag and append it to <svg> tag.
var defsCircle = document.createElementNS(_svgNS, 'defs');
svgCircle.appendChild(defsCircle);

// Create clipPath tag and append it to <defs> tag.
var clippathCircle = document.createElementNS(_svgNS, 'clipPath');
clippathCircle.setAttributeNS(null, 'id', 'clip-circle');
clippathCircle.setAttributeNS(null, 'clipPathUnits', 'objectBoundingBox');
defsCircle.appendChild(clippathCircle);

// Create circle tag and append it to <clipPath> tag.
var circle = document.createElementNS(_svgNS, 'circle');
circle.setAttributeNS(null, 'cx', '.5');
circle.setAttributeNS(null, 'cy', '.5');
circle.setAttributeNS(null, 'r', '.5');
clippathCircle.appendChild(circle);
