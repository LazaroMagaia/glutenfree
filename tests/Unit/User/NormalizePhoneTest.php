<?php
namespace Tests\Unit\User;

use App\Models\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class NormalizePhoneTest extends TestCase
{
  #[DataProvider('phones')]
  public function test_normalize_phone(string $input,string $expected) :void
  {
    $this->assertSame($expected, User::normalizePhone($input));
  }
  public static function phones(): array
    {
        return [
            'só 9 dígitos' => ['841234567',        '+258841234567'],
            'com espaços'  => ['84 123 4567',      '+258841234567'],
            'com +258'     => ['+258 84 123 4567', '+258841234567'],
            'com 00258'    => ['00258841234567',   '+258841234567'],
        ];
    }
}