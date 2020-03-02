<?php

namespace R2\Helpers;

use Spatie\Macroable\Macroable;

class ArrayRecordset
{
    const CASE_SENSITIVE = 1;
    const PRESERVE_KEYS = 2;

    /** @var string */
    protected $name = 'Recordset';

    use Macroable;

    /** @var array */
    protected $data;

    /** @var int */
    protected $options;

    /** @var callable[] */
    protected $comparators;

    /**
     * Chain constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [], int $options = 0)
    {
        $this->data = $data;
        $this->options = $options;
        $this->comparators = [];
    }

    /**
     * Set source data.
     *
     * @param array $data
     * @return self
     */
    public function data(array $data = []): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Simple sort.
     *
     * @param string $field
     * @param string $direction
     * @return self
     */
    public function orderBy(string $field, string $direction = 'asc'): self
    {
        $sign = strtolower($direction) === 'asc' ? 1 : -1;
        if ($this->options & self::CASE_SENSITIVE) {
            $this->comparators[] = function ($a, $b) use ($field, $sign) {
                return strnatcmp($a[$field], $b[$field]) * $sign;
            };
        } else {
            $this->comparators[] = function ($a, $b) use ($field, $sign) {
                return strnatcasecmp($a[$field], $b[$field]) * $sign;
            };
        }
        return $this;
    }

    /**
     * Get result array.
     *
     * @return array
     */
    public function get(): array
    {
        $sort = $this->options & self::PRESERVE_KEYS ? 'uasort' : 'usort';
        $sort($this->data, function ($a, $b) {
            foreach ($this->comparators as $f) {
                $result = $f($a, $b);
                if ($result !== 0) {
                    return $result;
                }
            }
            return 0;
        });
        return $this->data;
    }

    /**
     * Get first record only.
     *
     * @return array
     */
    public function first(): array
    {
        $result = $this->get();
        return reset($result);
    }

    /**
     * Get first given field value of first record only.
     *
     * @param string $field
     * @return mixed
     */
    public function value(string $field)
    {
        $result = $this->get();
        return reset($result)[$field];
    }

    /**
     * Plucka n array of values.
     *
     * @param mixed      $value
     * @param mixed|null $key
     * @return array
     */
    public function pluck($value, $key = null): array
    {
        $results = [];
        foreach ($this->get() as $item) {
            $itemValue = $item[$value];
            if (is_null($key)) {
                $results[] = $itemValue;
            } else {
                $itemKey = $item[$key];
                $results[$itemKey] = $itemValue;
            }
        }
        return $results;
    }
}
