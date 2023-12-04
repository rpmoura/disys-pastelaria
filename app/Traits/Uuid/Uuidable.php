<?php

namespace App\Traits\Uuid;

use Ramsey\Uuid\Uuid as RamseyUuid;

trait Uuidable
{
    public function getUuidColumnName()
    {
        return property_exists($this, 'uuidColumnName') ? $this->uuidColumnName : 'uuid';
    }

    /**
     * @return string|void
     */
    public function getUuid()
    {
        if (!empty($this->getUuidColumnName())) {
            return (string)$this->{$this->getUuidColumnName()};
        }
    }

    /**
     * @param $value
     * @return void
     */
    public function setUuid($value): void
    {
        if (!empty($this->getUuidColumnName())) {
            $this->{$this->getUuidColumnName()} = $value;
        }
    }

    public function generateUuid(): string
    {
        return RamseyUuid::uuid4()->toString();
    }
}
