<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class LvleditTest extends TestCase {
	public function testSetAndGet(): void {
		$testTitle = 'Cute level';
		$testDesc = "Gosh ^-^";

		lvledit(1, 'set-name', $testTitle);
		lvledit(1, 'set-description', $testDesc);

		$this->assertSame($testTitle, lvledit(1, 'get-name'));
		$this->assertSame($testDesc, lvledit(1, 'get-description'));
	}

	public function testUtterGarbage(): void {
		$garbage = '""\'löl---ßðæßøªðłæªø€łæø£$ł$£æø€ł$€æø£$ł€æøł®€æøł';

		lvledit(1, 'set-description', $garbage);

		$this->assertSame($garbage, lvledit(1, 'get-description'));
	}
}
