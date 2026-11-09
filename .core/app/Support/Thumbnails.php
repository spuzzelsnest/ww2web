<?php

namespace App\Support;

use RuntimeException;

/**
 * Small copies of the footage photos for the map page.
 *
 *   images/aachen01.jpg  ->  images/thumbs/aachen01-480.webp
 *                            images/thumbs/aachen01-960.webp
 *
 * WebP when the server's GD supports it, JPEG otherwise. The page only
 * uses a thumbnail when the file exists, so missing ones fall back to the
 * original picture.
 */
class Thumbnails
{
    public const WIDTHS = [480, 960];

    private const QUALITY = 75;

    public function sourceDir(): string
    {
        return public_path('images');
    }

    public function thumbDir(): string
    {
        return public_path('images/thumbs');
    }

    public function extension(): string
    {
        return function_exists('imagewebp') ? 'webp' : 'jpg';
    }

    public function path(string $name, int $width, ?string $ext = null): string
    {
        return $this->thumbDir().'/'.$name.'-'.$width.'.'.($ext ?? $this->extension());
    }

    /**
     * Names (without .jpg) that have a complete set of thumbnails,
     * mapped to their extension: ['aachen01' => 'webp', ...].
     */
    public function available(): array
    {
        $found = [];

        $small = array_merge(
            glob($this->thumbDir().'/*-'.self::WIDTHS[0].'.webp') ?: [],
            glob($this->thumbDir().'/*-'.self::WIDTHS[0].'.jpg') ?: [],
        );

        foreach ($small as $file) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            $name = substr(basename($file), 0, -strlen('-'.self::WIDTHS[0].'.'.$ext));

            if (is_file($this->path($name, self::WIDTHS[1], $ext))) {
                $found[$name] = $ext;
            }
        }

        return $found;
    }

    public function exists(string $name): bool
    {
        foreach (self::WIDTHS as $width) {
            if (! is_file($this->path($name, $width))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Make the thumbnails for images/<name>.jpg. Returns false when the
     * source picture is missing.
     */
    public function make(string $name, bool $force = false): bool
    {
        $source = $this->sourceDir().'/'.$name.'.jpg';

        if (! is_file($source)) {
            return false;
        }

        if (! $force && $this->exists($name)) {
            return true;
        }

        if (! is_dir($this->thumbDir()) && ! mkdir($this->thumbDir(), 0755, true) && ! is_dir($this->thumbDir())) {
            throw new RuntimeException('Cannot create '.$this->thumbDir());
        }

        $image = @imagecreatefromjpeg($source);

        if ($image === false) {
            throw new RuntimeException("Not a readable JPEG: {$source}");
        }

        $image = $this->applyExifOrientation($image, $source);

        try {
            foreach (self::WIDTHS as $width) {
                $resized = $this->resize($image, $width);

                $file = $this->path($name, $width);
                $tmp = $file.'.tmp';

                if ($this->extension() === 'webp') {
                    $ok = imagewebp($resized, $tmp, self::QUALITY);
                } else {
                    imageinterlace($resized, true); // progressive JPEG
                    $ok = imagejpeg($resized, $tmp, self::QUALITY);
                }

                imagedestroy($resized);

                if (! $ok || ! rename($tmp, $file)) {
                    @unlink($tmp);
                    throw new RuntimeException("Could not write {$file}");
                }
            }
        } finally {
            imagedestroy($image);
        }

        return true;
    }

    /**
     * Scale to $width px wide (never upscale). imagecopyresampled works on
     * every GD build; imagescale() returns false on some (e.g. Homebrew PHP).
     */
    private function resize(\GdImage $image, int $width): \GdImage
    {
        $srcW = imagesx($image);
        $srcH = imagesy($image);
        $w = min($width, $srcW);
        $h = max(1, (int) round($srcH * $w / $srcW));

        $resized = imagecreatetruecolor($w, $h);

        if ($resized === false || ! imagecopyresampled($resized, $image, 0, 0, 0, 0, $w, $h, $srcW, $srcH)) {
            throw new RuntimeException('GD could not resize the picture');
        }

        return $resized;
    }

    /** Phone photos are often stored sideways with an EXIF rotation flag. */
    private function applyExifOrientation(\GdImage $image, string $source): \GdImage
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        $orientation = (int) (@exif_read_data($source)['Orientation'] ?? 1);

        $angle = match ($orientation) {
            3 => 180,
            6 => -90,
            8 => 90,
            default => 0,
        };

        if ($angle === 0) {
            return $image;
        }

        $rotated = imagerotate($image, $angle, 0);

        if ($rotated === false) {
            return $image;      // keep it unrotated rather than fail
        }

        imagedestroy($image);

        return $rotated;
    }
}
