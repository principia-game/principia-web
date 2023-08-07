<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class DatabaseHelperTest extends TestCase {
	public function testInsertInto(): void {
		$result = 'INSERT INTO blarg (amanda,lol) VALUES (?,?)';

		$output = insertInto('blarg', [
			'amanda' => 'cute',
			'lol' => 'yes'
		], true);

		$this->assertSame($result, $output);
	}

	public function testUpdateRowQuery(): void {
		$result = [
			'fieldquery' => 'blarg=?,lol=?',
			'placeholders' => ['yes', 'yes']
		];

		$output = updateRowQuery([
			'blarg' => 'yes',
			'lol' => 'yes'
		]);

		$this->assertSame($result, $output);
	}

	public function testPaginate(): void {
		$this->assertSame(' LIMIT 0, 20', paginate(1, 20));
	}
}
