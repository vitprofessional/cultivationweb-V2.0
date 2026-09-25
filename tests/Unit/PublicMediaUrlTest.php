<?php

namespace Tests\Unit;

use App\Services\PublicMediaUrl;
use Illuminate\Config\Repository;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PublicMediaUrlTest extends TestCase
{
    #[DataProvider('validPaths')]
    public function test_portable_url_contract(string $base, string $prefix, string $path, string $expected): void
    {
        $resolver = new PublicMediaUrl(new Repository(['media' => [
            'public_base_url' => $base, 'public_path_prefix' => $prefix,
        ], 'app' => ['url' => 'https://website.example.test', 'asset_url' => 'https://static.example.test/public']]));
        $this->assertSame($expected, $resolver->url($path));
    }

    public static function validPaths(): array
    {
        return [
            'relative' => ['https://admin.example.test', 'public', 'upload/image/a.jpg', 'https://admin.example.test/public/upload/image/a.jpg'],
            'leading and duplicate slashes' => ['https://admin.example.test/', 'public/', '/upload//image/a.jpg', 'https://admin.example.test/public/upload/image/a.jpg'],
            'local subdirectory' => ['http://localhost/cultivation', 'public', 'upload/image/a.jpg', 'http://localhost/cultivation/public/upload/image/a.jpg'],
            'base already ends public' => ['https://admin.example.test/public/', 'public', 'upload/image/a.jpg', 'https://admin.example.test/public/upload/image/a.jpg'],
            'explicit public relative prefix' => ['https://admin.example.test/public', 'public', '/public/upload/a.jpg', 'https://admin.example.test/public/upload/a.jpg'],
            'public document root' => ['https://admin.example.test', '', 'upload/image/a.jpg', 'https://admin.example.test/upload/image/a.jpg'],
            'interior public segment retained' => ['https://admin.example.test', 'public', 'archive/public/a.jpg', 'https://admin.example.test/public/archive/public/a.jpg'],
            'encoded filename' => ['https://admin.example.test', 'public', "upload/school photo'(1).jpg", 'https://admin.example.test/public/upload/school%20photo%27%281%29.jpg'],
            'port and tenant prefix' => ['http://localhost:8080/tenant/', 'public', 'upload/a.jpg', 'http://localhost:8080/tenant/public/upload/a.jpg'],
        ];
    }

    #[DataProvider('unsafePaths')]
    public function test_unsafe_or_empty_paths_fail_closed(?string $path): void
    {
        $resolver = new PublicMediaUrl(new Repository(['media' => ['public_base_url' => 'https://admin.example.test']]));
        $this->assertNull($resolver->url($path));
    }

    public static function unsafePaths(): array
    {
        return array_map(static fn ($path) => [$path], [
            null, '', ' ', '/', '../a.jpg', 'upload/../a.jpg', 'upload/./a.jpg',
            'upload/%2e%2e/a.jpg', 'upload/%252e%252e/a.jpg', 'C:/private/a.jpg',
            'C:\\private\\a.jpg', 'upload\\..\\a.jpg', "upload/a\0.jpg",
            "upload/a\n.jpg", 'https://foreign.example.test/a.jpg', 'javascript:alert(1)',
            'upload/a.jpg?token=secret', 'upload/a.jpg#fragment',
        ]);
    }

    #[DataProvider('invalidBases')]
    public function test_missing_or_invalid_media_authority_does_not_use_web_or_asset_url(?string $base): void
    {
        $resolver = new PublicMediaUrl(new Repository(['media' => ['public_base_url' => $base],
            'app' => ['url' => 'https://website.example.test', 'asset_url' => 'https://static.example.test']]));
        $this->assertNull($resolver->slider('image.jpg'));
    }

    public static function invalidBases(): array
    {
        return array_map(static fn ($base) => [$base], [
            null, '', ' ', '/relative', '//admin.example.test', 'file:///private',
            'javascript:alert(1)', 'https://user:secret@admin.example.test',
            'https://user@admin.example.test', 'https://admin.example.test?token=secret',
            'https://admin.example.test#fragment', 'https://admin.example.test/../private',
        ]);
    }

    public function test_slider_preserves_audited_uuid_filename_without_any_file_dependency(): void
    {
        $resolver = new PublicMediaUrl(new Repository(['media' => ['public_base_url' => 'https://school-a.cultivationapp.test']]));
        $filename = '11111111-1111-4111-8111-111111111111.jpg';
        $this->assertSame('https://school-a.cultivationapp.test/public/upload/image/webHomepage/'.$filename, $resolver->slider($filename));
        foreach ([null, '', '../photo.jpg', '/photo.jpg', 'folder/photo.jpg', 'folder\\photo.jpg', 'https://foreign.example.test/a.jpg'] as $unsafe) {
            $this->assertNull($resolver->slider($unsafe));
        }
    }

    public function test_unsafe_configured_prefix_fails_closed(): void
    {
        $resolver = new PublicMediaUrl(new Repository(['media' => [
            'public_base_url' => 'https://admin.example.test', 'public_path_prefix' => '../private',
        ]]));
        $this->assertNull($resolver->slider('image.jpg'));
    }
}
