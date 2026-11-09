<?php

namespace Tests\Feature;

use App\Support\Thumbnails;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ThumbnailsTest extends TestCase
{
    private string $dir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dir = sys_get_temp_dir().'/ww2web-thumbs-'.uniqid();
        File::ensureDirectoryExists($this->dir.'/images');
        $this->app->usePublicPath($this->dir);

        $image = imagecreatetruecolor(2000, 1500);
        imagejpeg($image, $this->dir.'/images/test01.jpg');
        imagedestroy($image);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->dir);

        parent::tearDown();
    }

    public function test_it_makes_both_sizes_and_reports_them_as_available(): void
    {
        $thumbs = new Thumbnails;

        $this->assertTrue($thumbs->make('test01'));

        foreach (Thumbnails::WIDTHS as $width) {
            $file = $thumbs->path('test01', $width);
            $this->assertFileExists($file);
            $this->assertSame($width, getimagesize($file)[0]);
        }

        $this->assertSame(['test01' => $thumbs->extension()], $thumbs->available());
    }

    public function test_missing_source_is_skipped(): void
    {
        $this->assertFalse((new Thumbnails)->make('does-not-exist'));
    }

    public function test_the_command_runs(): void
    {
        $this->artisan('images:thumbs')->assertSuccessful();
        $this->assertTrue((new Thumbnails)->exists('test01'));
    }
}
