<?php

namespace App\Testing\Traits;

use ArrayAccess;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use PHPUnit\Framework\Constraint\ArraySubset;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Util\InvalidArgumentHelper;

trait JsonResponseAsserts
{

    /**
     * Asserts that an array has a specified subset.
     *
     * @param array|ArrayAccess $subset
     * @param array|ArrayAccess $array
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     *
     * @codeCoverageIgnore
     *
     */
    public function assertArrayContains($subset, $array, bool $checkForObjectIdentity = false, string $message = ''): void
    {

        if (!(\is_array($subset) || $subset instanceof ArrayAccess)) {
            throw InvalidArgumentHelper::factory(
                1,
                'array or ArrayAccess'
            );
        }

        if (!(\is_array($array) || $array instanceof ArrayAccess)) {
            throw InvalidArgumentHelper::factory(
                2,
                'array or ArrayAccess'
            );
        }

        $constraint = new ArraySubset($subset, $checkForObjectIdentity);

        static::assertThat($array, $constraint, $message);
    }


    /**
     * @param array      $data
     * @param array|null $meta
     *
     * @return mixed
     */
    public function seeJsonSuccess(TestResponse $resp, array $data = null, array $meta = null)
    {
        $message = ['status' => 200];

        if ($data) {
            $message['data'] = $data;
        }

        if ($meta) {
            $message['meta'] = $meta;
        }

        return $this->assertJsonStringEqualsJsonString(json_encode($message), $resp->getContent());
    }

    /**
     * @param array $error
     * @param int   $status
     *
     * @return mixed
     */
    public function seeJsonError(TestResponse $resp, array $error, $status = 400)
    {
        return $this->assertJsonStringEqualsJsonString(
            json_encode(['status' => $status, 'error' => $error]),
            $resp->getContent()
        );
    }

    /**
     * @param array      $data
     * @param array|null $meta
     *
     * @return mixed
     */
    public function seeJsonSuccessSubset(TestResponse $resp, array $data = null, array $meta = null)
    {
        $message = ['status' => 200];

        if ($data) {
            $message['data'] = $data;
        }

        if ($meta) {
            $message['meta'] = $meta;
        }

        return $this->seeJsonSubset($resp, $message);
    }

    /**
     * @param TestResponse $resp
     * @param array $data
     * @return $this
     */
    protected function seeJsonSubset(TestResponse $resp, array $data)
    {
        $this->assertArrayContains($data, $this->decodeResponseJson($resp));

        return $this;
    }

    /**
     * Validate and return the decoded response JSON.
     *
     * @param TestResponse $response
     * @return array
     */
    protected function decodeResponseJson(TestResponse $response)
    {
        $decodedResponse = json_decode($response->getContent(), true);

        if (is_null($decodedResponse) || $decodedResponse === false) {
            $this->fail('Invalid JSON was returned from the route. Perhaps an exception was thrown?');
        }

        return $decodedResponse;
    }

    /**
     * Assert that the response contains the given JSON.
     *
     * @param  array  $data
     * @param  bool  $negate
     * @return $this
     */
    protected function seeJsonContains(TestResponse $resp, array $data, $negate = false)
    {
        $method = $negate ? 'assertFalse' : 'assertTrue';

        $actual = json_encode(Arr::sortRecursive(
            (array) $this->decodeResponseJson($resp)
        ));

        foreach (Arr::sortRecursive($data) as $key => $value) {
            $expected = $this->formatToExpectedJson($key, $value);

            $this->{$method}(
                Str::contains($actual, $expected),
                ($negate ? 'Found unexpected' : 'Unable to find').' JSON fragment'.PHP_EOL."[{$expected}]".PHP_EOL.'within'.PHP_EOL."[{$actual}]."
            );
        }

        return $this;
    }

    /**
     * Assert that the JSON response has a given structure.
     *
     * @param  array|null  $responseData
     * @param  array|null  $structure
     * @return $this
     */
    public function seeJsonStructure(TestResponse $resp, array $structure)
    {
        $func = function ($structure, $responseData) use (&$func) {
            foreach ($structure as $key => $value) {
                if (is_array($value) && $key === '*') {
                    $this->assertInternalType('array', $responseData);

                    foreach ($responseData as $responseDataItem) {
                        $func($structure['*'], $responseDataItem);
                    }
                } elseif (is_array($value)) {
                    $this->assertArrayHasKey($key, $responseData);
                    $func($structure[$key], $responseData[$key]);
                } else {
                    $this->assertArrayHasKey($value, $responseData);
                }
            }

            return $this;
        };

        $responseData = $this->decodeResponseJson($resp);

        return $func($structure, $responseData);
    }

    /**
     * Format the given key and value into a JSON string for expectation checks.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return string
     */
    protected function formatToExpectedJson($key, $value)
    {
        $expected = json_encode([$key => $value]);

        if (Str::startsWith($expected, '{')) {
            $expected = substr($expected, 1);
        }

        if (Str::endsWith($expected, '}')) {
            $expected = substr($expected, 0, -1);
        }

        return trim($expected);
    }
}
