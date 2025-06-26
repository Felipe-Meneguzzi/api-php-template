<?php
declare(strict_types=1);

namespace App\Core;

use ReflectionObject;

readonly class IntegrityModelCore {
    protected string $value;


    protected function __construct() {
        $this->value = json_encode($this->__toArray());
    }


    public function __toArray(): array {
        $reflection = new ReflectionObject($this);
        $properties = $reflection->getProperties();
        $data = [];

        foreach ($properties as $property) {
            if ($property->getName() === 'value') {
                continue;
            }
            if (!$property->isInitialized($this)) {
                $data[$property->getName()] = null;
            } else {
                $data[$property->getName()] = $property->getValue($this);
            }
        }

        return $data;
    }


    public function __toString(): string {
        return $this->value;
    }


    public function __toJson(): string {
        return $this->value;
    }
}