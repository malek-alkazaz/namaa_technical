<?php

namespace App\Traits\V1\Api;

use ReflectionClass;

trait EnumOperation
{
    public static function names(): array{
        $reflection = new ReflectionClass(static::class);
        $constants = $reflection->getConstants();

        $names = [];
        foreach ($constants as $name => $value) {
            $names[] = $name;
        }
        return $names;
    }

    public static function values(): array{
        $reflection = new ReflectionClass(static::class);
        $constants = $reflection->getConstants();

        $values = [];
        foreach ($constants as $value) {
            $values[] = $value;
        }

        return $values;
    }


    public static function options(): array{
        $reflection = new ReflectionClass(static::class);
        $constants = $reflection->getConstants();

        $options = [];
        foreach ($constants as $name => $value) {
            $options[$name] = $value;
        }
        return $options;
    }


    public static function toArray(): array
    {
        $reflection = new ReflectionClass(static::class);
        $constants = $reflection->getConstants();

        $arrays = [];
        foreach ($constants as $value) {
            $arrays[] = $value;
        }

        $arrays = array_map(function($item){
            return (array) $item;
        },$arrays);

        return $arrays;
    }

    public static function getValue($get_value){
        $reflection = new ReflectionClass(static::class);
        $constants = $reflection->getConstants();

        foreach ($constants as $value) {
            // return gettype($value->value);

            if($value->value == $get_value){
                return $value->value;
            }
        }
    }
}
