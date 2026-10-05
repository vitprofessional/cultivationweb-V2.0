<?php
namespace Tests\Unit;
use PHPUnit\Framework\TestCase;

class PublicDeploymentContractTest extends TestCase
{
    public function test_uploads_are_never_deleted_or_seeded_from_repository(): void
    {
        $script=file_get_contents(__DIR__.'/../../deployment/publish-public.sh');
        $this->assertStringNotContainsString('rm ',$script);
        $this->assertStringContainsString('upload/*|storage/*',$script);
        $this->assertStringContainsString('git -C "$REPOPATH" ls-files -z -- public',$script);
        $this->assertStringContainsString('Existing public mapping requires manual reconciliation',$script);
        $this->assertStringContainsString('readlink -m',$script);
        $this->assertStringContainsString('ln -s "$APPPATH/public/$name"',$script);
    }
    public function test_pipeline_separates_runtime_uploads_and_does_not_migrate_automatically(): void
    {
        $yaml=file_get_contents(__DIR__.'/../../.cpanel.yml');
        $this->assertStringNotContainsString('rm -rf',$yaml);
        $this->assertStringNotContainsString('artisan migrate',$yaml);
        $this->assertStringNotContainsString('"$REPOPATH/public/upload',$yaml);
        $this->assertStringContainsString('publish-public.sh',$yaml);
        $this->assertStringContainsString('--exclude=\'/cache/\'',$yaml);
    }
}
