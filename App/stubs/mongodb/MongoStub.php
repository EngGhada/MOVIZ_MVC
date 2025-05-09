<?php

namespace MongoDB\BSON;
use DateTime;

class ObjectId
{
    /**
     * @param string $id
     */
    public function __construct(string $id = "") {}

    /**
     * @return string
     */
    public function __toString(): string {
        return "ObjectId";
    }
}

class UTCDateTime
{
    /**
     * @param int $milliseconds
     */
    public function __construct(int $milliseconds = 0) {}

    /**
     * @return DateTime
     */
    public function toDateTime(): DateTime {
        return new DateTime();
    }
}
