<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase {
	public function testUserfields(): void {
		$userfields = userfields();

		$this->assertSame(true, str_starts_with($userfields, 'u.id u_id,'));
	}
}
