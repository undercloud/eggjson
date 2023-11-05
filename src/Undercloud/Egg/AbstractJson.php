<?php

namespace Undercloud\Egg;

use UnexpectedValueException;

abstract class AbstractJson
{
    /**
     * @var int
     */
    protected $throwCode = 4194304;

    /**
     * @var bool
     */
    protected $throwAnyway = false;

    /**
     * @var int
     */
    protected $depth = 512;

    /**
     * @var array
     */
    protected $options = [];

    protected function __construct()
    {
        $this->throwOnError();
        $this->invalidUtf8Ignore();
        $this->invalidUtf8Substitute();
    }

    /**
     * @param bool $mode
     * @return $this
     */
    public function throwOnError($mode = true)
    {
        return $this->resolveJsonConstByMethod(__FUNCTION__, $mode);
    }

    /**
     * @param bool $mode
     * @return $this
     */
    public function invalidUtf8Ignore($mode = true)
    {
        return $this->resolveJsonConstByMethod(__FUNCTION__, $mode);
    }

    /**
     * @param bool $mode
     * @return $this
     */

    public function invalidUtf8Substitute($mode = true)
    {
        return $this->resolveJsonConstByMethod(__FUNCTION__, $mode);
    }

    /**
     * @param string $option
     * @param bool $mode
     * @return $this
     */
    protected function resolveJsonConstByMethod($option, $mode)
    {
        return $mode ? $this->withOption($option) : $this->withoutOption($option);
    }

    /**
     * @param string $option
     * @return int|string
     */
    private function resolveJsonConst($option)
    {
        $dash = preg_replace('/[A-Z]/', '_$0', $option);

        $const = 'JSON_' . strtoupper($dash);
        if (!defined($const)) {
            if ('JSON_THROW_ON_ERROR' === $const) {
                return $this->throwCode;
            }

            return 0;
        }

        return constant($const);
    }

    /**
     * @return void
     */
    protected function clearLastError()
    {
        json_encode(null);
    }

    /**
     * @return int
     */
    protected function compileOptions()
    {
        return array_reduce($this->options, function ($carry, $item) {
            if ($item === $this->throwCode and !defined('JSON_THROW_ON_ERROR')) {
                $this->throwAnyway = true;
                $item = 0;
            }

            return $carry |= $item;
        }, 0);
    }

    /**
     * @param mixed $value
     * @return mixed
     * @throws \JsonException
     */
    protected function validateOrThrowException($value)
    {
        if (true === $this->throwAnyway and $error = json_last_error()) {
            if (!function_exists('json_last_error_msg')) {
                require_once __DIR__ . '/polyfill/json_last_error_msg.php';
            }

            $isJsonExceptionPolyfill = false;
            if (!class_exists('JsonException')) {
                $isJsonExceptionPolyfill = true;
                require_once __DIR__ . '/polyfill/JsonException.php';
            }

            $errorMsg = json_last_error_msg();
            $jsonException = new \JsonException($errorMsg, $error);

            $backtrace = debug_backtrace();

            if ($isJsonExceptionPolyfill and isset($backtrace[1])) {
                $file = $backtrace[1]['file'];
                $line = $backtrace[1]['line'];

                if ($file) {
                    $jsonException->setFile($file);
                }

                if ($line) {
                    $jsonException->setLine($line);
                }
            }

            throw $jsonException;
        }

        return $value;
    }

    /**
     * @param string $option
     * @return $this
     */
    protected function withOption($option)
    {
        $option = $this->resolveJsonConst($option);
        if ($option and !in_array($option, $this->options)) {
            $this->options[] = $option;
        }

        return $this;
    }

    /**
     * @param string $option
     * @return $this
     */
    protected function withoutOption($option)
    {
        $option = $this->resolveJsonConst($option);
        if ($option and false !== ($index = array_search($option, $this->options))) {
            unset($this->options[$index]);
        }

        return $this;
    }

    /**
     * @param string $depth
     * @return $this
     */
    protected function withDepth($depth)
    {
        $depth = (int) $depth;
        if (!$depth or $depth < 1) {
            throw new UnexpectedValueException(
                "Parameter depth must be an integer greater than zero"
            );
        }

        $this->depth = $depth;

        return $this;
    }
}
