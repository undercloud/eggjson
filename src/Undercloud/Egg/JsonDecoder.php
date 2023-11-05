<?php

namespace Undercloud\Egg;

class JsonDecoder extends AbstractJson
{
    private $assoc = false;

    public function __construct()
    {
        parent::__construct();

        $this->bigintAsString();
    }

    public function bigintAsString($mode = true)
    {
        return $this->resolveJsonConstByMethod(__FUNCTION__, $mode);
    }

    public function objectAsArray($mode = true)
    {
        $this->assoc = null;

        return $this->resolveJsonConstByMethod(__FUNCTION__, $mode);
    }

    public function asAssoc($assoc = true)
    {
        $this->assoc = (bool) $assoc;

        return $this;
    }

    public function decode($value)
    {
        $this->clearLastError();
        $options = $this->compileOptions();

        $decoded = json_decode($value, $this->assoc, $this->depth, $options);

        return $this->validateOrThrowException($decoded);
	}
}