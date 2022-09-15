<?php

namespace App\Extensions;

use App\Contracts\GenericResponseContract;

class GenericResponse implements GenericResponseContract
{
    /**
     * The payload. The result of data fetching.
     *
     * @var mixed
     */
    protected $data;

    /**
     * Usually it's a pagination info
     *
     * @var array
     */
    protected $meta = [];

    /**
     * Any error which prevented data from being fetched
     *
     * @var string|null
     */
    protected $error = null;

    /**
     * GenericResponse constructor.
     * @param array $data
     * @param array $meta
     * @param null $error
     */
    public function __construct($data = [], $meta = [], $error = null)
    {
        $this->data = $data;
        $this->meta = $meta;
        $this->error = $error;
    }

    /**
     * @param $data
     */
    public static function populate($data)
    {
        $response = new self(
            $data['data'] ?? [],
            $data['meta'] ?? [],
            $data['error'] ?? null
        );

        return $response;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $val
     * @return GenericResponse
     */
    public function setData($val): GenericResponseContract
    {
        $this->data = $val;

        return $this;
    }

    /**
     * @return array
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * @param array $val
     * @return GenericResponse
     */
    public function setMeta(array $val): GenericResponseContract
    {
        $this->meta = $val;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * Called on json_encode or when to array conversion needed
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'data' => $this->getData(),
            'meta' => $this->getMeta(),
            'error' => $this->getError(),
        ];
    }
}
