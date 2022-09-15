<?php

namespace App\Contracts;

interface GenericResponseContract extends \JsonSerializable
{
    /**
     * @return mixed
     */
    public function getData();

    /**
     * @param mixed $val
     * @return GenericResponseContract
     */
    public function setData($val): self;

    /**
     * @return array
     */
    public function getMeta(): array;

    /**
     * @param array $val
     * @return GenericResponseContract
     */
    public function setMeta(array $val): self;

    /**
     * @return array
     */
    public function getError(): ?string;

    /**
     * Called on json_encode or when to array conversion needed
     *
     * @return array
     */
    public function jsonSerialize(): array;
}
