<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PublicDeploymentContractTest extends TestCase
{
    public function test_cpanel_delegates_to_generic_web_deployment_script(): void
    {
        $yaml = file_get_contents(__DIR__.'/../../.cpanel.yml');

        $this->assertStringContainsString(
            '/bin/bash deployment/deploy-web.sh',
            $yaml
        );

        $this->assertStringNotContainsString('/home/', $yaml);
        $this->assertStringNotContainsString('shsburichong', $yaml);
        $this->assertStringNotContainsString('mphs1969', $yaml);
        $this->assertStringNotContainsString('artisan migrate', $yaml);
    }

    public function test_complete_public_directory_boundary_is_preserved(): void
    {
        $script = file_get_contents(__DIR__.'/../../deployment/deploy-web.sh');

        $this->assertStringContainsString(
            'WEBPUBLIC="$PUBLICPATH/public"',
            $script
        );

        $this->assertStringContainsString(
            '"$REPOPATH/public/"',
            $script
        );

        $this->assertStringContainsString(
            '"$APPPATH/public/"',
            $script
        );

        $this->assertStringContainsString(
            '"$WEBPUBLIC/"',
            $script
        );

        // Public children must never be flattened into the document root.
        foreach ([
            'assets',
            'build',
            'cultivation',
            'img',
            'upload',
            'fonts',
            'back-office',
        ] as $directory) {
            $this->assertStringNotContainsString(
                '$PUBLICPATH/'.$directory,
                $script
            );
        }
    }

    public function test_runtime_uploads_are_preserved_in_both_public_trees(): void
    {
        $script = file_get_contents(__DIR__.'/../../deployment/deploy-web.sh');

        $this->assertStringContainsString(
            'mkdir -p "$APPPATH/public/upload"',
            $script
        );

        $this->assertStringContainsString(
            'mkdir -p "$WEBPUBLIC/upload"',
            $script
        );

        $this->assertGreaterThanOrEqual(
            2,
            substr_count($script, "--exclude='/upload/'")
        );

        $this->assertStringContainsString(
            'APP_UPLOAD_BEFORE',
            $script
        );

        $this->assertStringContainsString(
            'APP_UPLOAD_AFTER',
            $script
        );

        $this->assertStringContainsString(
            'WEB_UPLOAD_BEFORE',
            $script
        );

        $this->assertStringContainsString(
            'WEB_UPLOAD_AFTER',
            $script
        );
    }

    public function test_runtime_state_is_preserved_and_database_is_not_migrated_automatically(): void
    {
        $script = file_get_contents(__DIR__.'/../../deployment/deploy-web.sh');

        $this->assertStringContainsString(
            "--exclude='/cache/'",
            $script
        );

        $this->assertStringNotContainsString(
            'artisan migrate',
            $script
        );

        $this->assertStringNotContainsString(
            'rm -rf "$APPPATH/storage"',
            $script
        );

        $this->assertStringNotContainsString(
            'rm -rf "$APPPATH/vendor"',
            $script
        );

        $this->assertStringNotContainsString(
            'rm -rf "$APPPATH/.env"',
            $script
        );
    }

    public function test_production_index_is_validated_before_replacing_live_entry_point(): void
    {
        $script = file_get_contents(__DIR__.'/../../deployment/deploy-web.sh');

        $this->assertStringContainsString(
            'INDEX_TMP="$PUBLICPATH/.index.php.deploying"',
            $script
        );

        $this->assertStringContainsString(
            '"$PHP" -l "$INDEX_TMP"',
            $script
        );

        $this->assertStringContainsString(
            'mv -f "$INDEX_TMP" "$PUBLICPATH/index.php"',
            $script
        );
    }

    public function test_required_public_artifacts_are_verified(): void
    {
        $script = file_get_contents(__DIR__.'/../../deployment/deploy-web.sh');

        $this->assertStringContainsString(
            '$REPOPATH/public/build/manifest.json',
            $script
        );

        $this->assertStringContainsString(
            '$APPPATH/public/build/manifest.json',
            $script
        );

        $this->assertStringContainsString(
            '$WEBPUBLIC/build/manifest.json',
            $script
        );

        $this->assertStringContainsString(
            '$REPOPATH/public/cultivation',
            $script
        );

        $this->assertStringContainsString(
            '$APPPATH/public/cultivation',
            $script
        );

        $this->assertStringContainsString(
            '$WEBPUBLIC/cultivation',
            $script
        );
    }
}