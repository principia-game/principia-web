<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class UseragentTest extends TestCase {
	public function testExtractPlatform(): void {
		$this->assertSame('Windows', extractPlatform('Principia/34 (Windows)'));

		$this->assertSame('Symbian', extractPlatform('MidpPrincipia/69 (Symbian)'));

		$this->assertSame('Mac OS X', extractPlatform('Principia/39 (Mac OS X)'));

		$this->assertSame('N/A', extractPlatform('curl/7.69.4'));
	}

	public function testExtractPrincipiaVersion(): void {
		$this->assertSame('34', extractPrincipiaVersion('Principia/34 (Windows)'));

		$this->assertSame('69', extractPrincipiaVersion('MidpPrincipia/69 (Symbian)'));

		$this->assertSame('N/A', extractPrincipiaVersion('curl/7.69.4'));
	}
}
