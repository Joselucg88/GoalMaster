<?php

namespace App;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class DoctrineEnumType extends Type
{
    const ENUM = 'enum'; // Nombre del tipo personalizado

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        // Convertir el valor de la base de datos a PHP
        return (string) $value;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        // Definir la declaración SQL del tipo ENUM
        return "ENUM('value1', 'value2', 'value3')"; // Define tus valores ENUM aquí
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        // Convertir el valor de PHP a la base de datos
        return $value;
    }

    public function getName()
    {
        // Nombre del tipo ENUM
        return self::ENUM;
    }
}

