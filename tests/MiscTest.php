<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class MiscTest extends TestCase {
	public function testIpv6ToIpv4(): void {
		$this->assertSame('127.0.0.1', ipv6_to_ipv4('::ffff:127.0.0.1'));

		$this->assertSame('::1', ipv6_to_ipv4('::1'));
	}
}
