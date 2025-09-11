<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>World Map Viewer</title>
    <style>
        #viewer {
            width: 100%;
            height: 100vh; /*400px;*/
            overflow: hidden;
            position: relative;
            cursor: grab;
        }
        #viewer:active {
            cursor: grabbing;
        }
        .tile {
            position: absolute;
            top: 0;
            left: 0;
        }
    </style>
</head>
<body>
    <div id="viewer"></div>

    <script>
		class TiledImageViewer {
			constructor(containerId, options) {
				this.container = document.getElementById(containerId);
				this.options = Object.assign({
					tileSize: 256,
					maxZoom: 4,
					initialZoom: 1,
					imagePath: '/tiles/tile'
				}, options);

				this.zoom = this.options.initialZoom;
				this.targetZoom = this.zoom;
				this.position = { x: -8 * this.options.tileSize, y: -18 * this.options.tileSize };
				this.isDragging = false;
				this.dragStart = { x: 0, y: 0 };

				this.setupEventListeners();
				this.render();
				//this.animateZoom();
			}

			setupEventListeners() {
				this.container.addEventListener('wheel', this.handleWheel.bind(this));
				this.container.addEventListener('mousedown', this.handleMouseDown.bind(this));
				this.container.addEventListener('mousemove', this.handleMouseMove.bind(this));
				this.container.addEventListener('mouseup', this.handleMouseUp.bind(this));
				this.container.addEventListener('mouseleave', this.handleMouseUp.bind(this));
			}

			handleWheel(e) {
				e.preventDefault();
				const rect = this.container.getBoundingClientRect();
				const mouseX = e.clientX - rect.left;
				const mouseY = e.clientY - rect.top;

				const zoomFactor = e.deltaY < 0 ? 0.9 : 1.1;
				const oldZoom = this.targetZoom;
				this.targetZoom = Math.max(1, Math.min(this.options.maxZoom, this.targetZoom * zoomFactor));
				if(e.deltaY < 0) this.zoom--;
				if(e.deltaY > 0) this.zoom++;
				this.zoom = Math.max(1, Math.min(this.zoom, this.options.maxZoom));
				// Adjust position to zoom towards mouse cursor
				const zoomRatio = this.targetZoom / oldZoom;
				//this.position.x = mouseX - (mouseX - this.position.x) * zoomRatio;
				//this.position.y = mouseY - (mouseY - this.position.y) * zoomRatio;

				this.render();
			}

			handleMouseDown(e) {
				e.preventDefault();
				this.isDragging = true;
				this.dragStart = { x: e.clientX - this.position.x, y: e.clientY - this.position.y };
			}

			handleMouseMove(e) {
				if (this.isDragging) {
					this.position = {
						x: e.clientX - this.dragStart.x,
						y: e.clientY - this.dragStart.y
					};
					this.render();
				}
			}

			handleMouseUp() {
				this.isDragging = false;
			}

			// animateZoom() {
				// if (Math.abs(this.zoom - this.targetZoom) > 0.01) {
					// this.zoom += (this.targetZoom - this.zoom) * 0.1;
					// this.render();
				// }
				// requestAnimationFrame(this.animateZoom.bind(this));
			// }

			render() {
				const fallbackSrc = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';
				this.container.innerHTML = '';
				const visibleWidth = this.container.clientWidth;
				const visibleHeight = this.container.clientHeight;
				const tileSize = this.options.tileSize; // This should be 256

				const nearestZoom = Math.round(this.zoom);
				const zoomFactor = 1 / nearestZoom;
				// Calculate the visible columns and rows
				let startCol = Math.floor(-this.position.x * zoomFactor / tileSize);
				let endCol = Math.ceil((visibleWidth - this.position.x * zoomFactor) / tileSize);
				let startRow = Math.floor(-this.position.y * zoomFactor / tileSize);
				let endRow = Math.ceil((visibleHeight - this.position.y * zoomFactor) / tileSize);

				// Ensure we don't go out of bounds
				startCol = Math.max(0, startCol);
				endCol = Math.max(0, endCol);
				startRow = Math.max(0, startRow);
				endRow = Math.max(0, endRow);

				for (let row = startRow; row <= endRow; row++) {
					for (let col = startCol; col <= endCol; col++) {
						const img = document.createElement('img');
						img.src = `${this.options.imagePath}_${nearestZoom}_${col}_${row}.jpg`;
						img.className = 'tile';
						img.style.width = `${tileSize}px`;
						img.style.height = `${tileSize}px`;
						img.style.left = `${col * tileSize + this.position.x * zoomFactor}px`;
						img.style.top = `${row * tileSize + this.position.y * zoomFactor}px`;
						img.onerror = function() {
							this.onerror = null; // Prevents infinite loop if fallback also fails
							this.src = fallbackSrc;//"/tiles/blank.jpg";
						};
						this.container.appendChild(img);
					}
				}
			}
		}

		// Initialize the viewer
		new TiledImageViewer('viewer', {
			imagePath: 'tiles/tile',
			maxZoom: 4,
			tileSize: 256
		});
    </script>
</body>
</html>