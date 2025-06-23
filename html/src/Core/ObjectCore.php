<?php

namespace App\Core;

use ReflectionObject;

class ObjectCore {
    public function toArray(): array {
        $reflection = new ReflectionObject($this);
        $properties = $reflection->getProperties();
        $data = [];

        foreach ($properties as $property) {
            $data[$property->getName()] = $property->getValue($this);
        }

        return $data;
    }

}