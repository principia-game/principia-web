<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class MiscTest extends TestCase {
	public function testExtractPlatform(): void {
		$this->assertSame('Windows', extractPlatform('Principia/34 (Windows)'));

		$this->assertSame('Symbian', extractPlatform('MidpPrincipia/69 (Symbian)'));

		$this->assertSame('N/A', extractPlatform('curl/7.69.4'));
	}

	public function testGitCommitHash(): void {
		$hash = gitCommit();

		$this->assertSame(7, strlen($hash));
	}
}
