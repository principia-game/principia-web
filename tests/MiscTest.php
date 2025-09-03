<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class MiscTest extends TestCase {
	public function testExtractPlatform(): void {
		$this->assertSame('Windows', extractPlatform('Principia/34 (Windows)'));

		$this->assertSame('Symbian', extractPlatform('MidpPrincipia/69 (Symbian)'));

		$this->assertSame('N/A', extractPlatform('curl/7.69.4'));
	}

	public function testIpv6ToIpv4(): void {
		$this->assertSame('127.0.0.1', ipv6_to_ipv4('::ffff:127.0.0.1'));

		$this->assertSame('::1', ipv6_to_ipv4('::1'));
	}
}
