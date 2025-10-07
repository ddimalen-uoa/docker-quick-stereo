<?php
 
/*
* File: SimpleImage.php
* Author: Simon Jarvis
* Copyright: 2006 Simon Jarvis
* Date: 08/11/06
* Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details:
* http://www.gnu.org/licenses/gpl.html
*
*/
 
class SimpleImage {
 
   var $image;
   var $image_type;
 
   function load($filename) {
      $info = getimagesize($filename);
      $this->image_type = $info[2];
      if ($this->image_type == IMAGETYPE_JPEG) {
         $this->image = imagecreatefromjpeg($filename);
      } elseif ($this->image_type == IMAGETYPE_GIF) {
         $this->image = imagecreatefromgif($filename);
      } elseif ($this->image_type == IMAGETYPE_PNG) {
         $this->image = imagecreatefrompng($filename);
         imagealphablending($this->image, false);
         imagesavealpha($this->image, true);
      }
   }
   function save($filename, $image_type = IMAGETYPE_JPEG, $quality = 90, $permissions = null) {
      if ($image_type == IMAGETYPE_JPEG) {
         imagejpeg($this->image, $filename, $quality); // 0–100
      } elseif ($image_type == IMAGETYPE_PNG) {
         $level = (int) round((100 - max(min($quality,100),0)) * 9 / 100); // map 0–100 → 0–9
         imagesavealpha($this->image, true);
         imagepng($this->image, $filename, $level);
      } elseif ($image_type == IMAGETYPE_GIF) {
         imagegif($this->image, $filename);
      }
      if ($permissions !== null) chmod($filename, $permissions);
   }
   function output($image_type=IMAGETYPE_JPEG) {
 
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
 
         imagegif($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         imagepng($this->image);
      }
   }
   function getWidth() {
 
      return imagesx($this->image);
   }
   function getHeight() {
 
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
 
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
 
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
 
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }
 
   function resize($width, $height) {
      $new = imagecreatetruecolor($width, $height);
      // If PNG/GIF, keep transparency
      if (in_array($this->image_type, [IMAGETYPE_PNG, IMAGETYPE_GIF], true)) {
         imagealphablending($new, false);
         imagesavealpha($new, true);
         $transparent = imagecolorallocatealpha($new, 0, 0, 0, 127);
         imagefilledrectangle($new, 0, 0, $width, $height, $transparent);
      }
      imagecopyresampled($new, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new;
   }     
   
   function merge800600() {
      $image = $this->image;
      $dest = imagecreatefrompng(__DIR__ . '/black800600.png'); // safer path
      imagealphablending($dest, false);
      imagesavealpha($dest, true);

      $width  = $this->getWidth();
      $height = $this->getHeight();
      $startX = (800 - $width) / 2;
      $startY = (600 - $height) / 2;

      // Use imagecopy to preserve alpha
      imagecopy($dest, $image, $startX, $startY, 0, 0, $width, $height);
      $this->image = $dest;
   }
 
}
?>