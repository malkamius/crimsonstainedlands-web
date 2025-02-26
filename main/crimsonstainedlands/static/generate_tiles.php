<?php 
// Ensure GD library is installed
if (!extension_loaded('gd')) {
    die('GD library is not installed.');
}

// Set parameters
$imagePath = 'C:/inetpub/crimsonstainedlands/Map.jpg';
$tileSize = 256;
$maxZoomLevels = 4;

// Load the original image
$originalImage = imagecreatefromjpeg($imagePath);
if (!$originalImage) {
    die('Failed to load the image.');
}

$originalWidth = imagesx($originalImage);
$originalHeight = imagesy($originalImage);

// Generate tiles for each zoom level
for ($zoom = 1; $zoom <= $maxZoomLevels; $zoom++) {
    $scale = 1 / $zoom;
    $scaledWidth = $originalWidth * $scale;
    $scaledHeight = $originalHeight * $scale;

    $scaledImage = imagecreatetruecolor($scaledWidth, $scaledHeight);
    imagecopyresampled($scaledImage, $originalImage, 0, 0, 0, 0, $scaledWidth, $scaledHeight, $originalWidth, $originalHeight);

    for ($y = 0; $y < $scaledHeight; $y += $tileSize) {
        for ($x = 0; $x < $scaledWidth; $x += $tileSize) {
            $tileImage = imagecreatetruecolor($tileSize, $tileSize);
            imagecopy($tileImage, $scaledImage, 0, 0, $x, $y, $tileSize, $tileSize);

            $tileFilename = sprintf('tiles/tile_%d_%d_%d.jpg', $zoom, $x / $tileSize, $y / $tileSize);
            imagejpeg($tileImage, $tileFilename, 90);
            imagedestroy($tileImage);
        }
    }

    imagedestroy($scaledImage);
}

imagedestroy($originalImage);
echo "Tile generation complete.";
?>