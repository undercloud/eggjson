<?php

namespace Undercloud\Egg;

class JsonEncoder extends AbstractJson
{
    public function __construct()
    {
        parent::__construct();

        $this->partialOutputOnError();
    }

    /**
     * @param bool $mode
     * @return JsonEncoder
     */
    public function allHex($mode = true)
    {
        return $this
            ->hexTag($mode)
            ->hexAmp($mode)
            ->hexApos($mode)
            ->hexQuot($mode);
    }

    /**
     * @param bool $mode
     * @return JsonEncoder
     */
    public function hexTag($mode = true)
    {
        return $this->resolveJsonConstByMethod(__FUNCTION__, $mode);
    }

    /**
     * @param bool $mode
     * @return JsonEncoder
     */
    public function hexAmp($mode = true)
    {
        return $this->resolveJsonConstByMethod(__FUNCTION__, $mode);
    }

    /**
     * @param bool $mode
     * @return JsonEncoder
     */
    public function hexApos($mode = true)
    {
        return $this->resolveJsonConstByMethod(__FUNCTION__, $mode);
    }

    /**
     * @param bool $mode
     * @return JsonEncoder
     */
    public function hexQuot($mode = true)
    {
        return $this->resolveJsonConstByMethod(__FUNCTION__, $mode);
    }

    /**
     * @param bool $mode
     * @return JsonEncoder
     */
    public function forceObject($mode = true)
    {
        return $this->resolveJsonConstByMethod(__FUNCTION__, $mode);
    }

    /**
     * @param bool $mode
     * @return JsonEncoder
     */
    public function numericCheck($mode = true)
    {
        return $this->resolveJsonConstByMethod(__FUNCTION__, $mode);
    }

    /**
     * @param bool $mode
     * @return JsonEncoder
     */
    public function prettyPrint($mode = true)
    {
        return $this->resolveJsonConstByMethod(__FUNCTION__, $mode);
    }

    /**
     * @param bool $mode
     * @return JsonEncoder
     */
    public function unescapedSlashes($mode = true)
    {
        return $this->resolveJsonConstByMethod(__FUNCTION__, $mode);
    }

    /**
     * @param bool $mode
     * @return JsonEncoder
     */
    public function unescapedUnicode($mode = true)
    {
        return $this->resolveJsonConstByMethod(__FUNCTION__, $mode);
    }

    /**
     * @param bool $mode
     * @return JsonEncoder
     */
    public function partialOutputOnError($mode = true)
    {
        return $this->resolveJsonConstByMethod(__FUNCTION__, $mode);
    }

    /**
     * @param bool $mode
     * @return JsonEncoder
     */
    public function preserveZeroFraction($mode = true)
    {
        return $this->resolveJsonConstByMethod(__FUNCTION__, $mode);
    }

    /**
     * @param bool $mode
     * @return JsonEncoder
     */
    public function unescapedLineTerminators($mode = true)
    {
        return $this->resolveJsonConstByMethod(__FUNCTION__, $mode);
    }

    /**
     * @param $value
     * @return mixed
     * @throws \JsonException
     */
    public function encode($value)
    {
        $this->clearLastError();
        $options = $this->compileOptions();

        $encoded = json_encode($value, $options, $this->depth);

        return $this->validateOrThrowException($encoded);
    }
}
