<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase {
	public function testEmailHasher(): void {
		$email = 'rollerozxa@voxelmanip.se';
		$hash = mailHash($email);

		$this->assertSame(true, mailVerify($email, $hash));
	}
}
