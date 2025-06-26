<?php
declare(strict_types=1);

namespace App\ValueObject;

use Stringable;

final readonly class Phone implements Stringable {

    private string $value;

    private function __construct(string $phoneNumber) {
        $normalizedPhone = self::normalize($phoneNumber);
        self::validate($normalizedPhone);

        $this->value = $normalizedPhone;
    }

    public static function fromString(?string $phoneNumber): ?self {
        if(empty($phoneNumber)) {
            return null;
        }
        return new self($phoneNumber);
    }

    private static function validate(string $phoneNumber): void {

    }

    private static function normalize(string $phoneNumber): string {
        return strtolower(trim($phoneNumber));
    }

    public function __toString(): string {
        return $this->value;
    }

    public function equals(Phone $other): bool {
        return $this->value === $other->value;
    }

}