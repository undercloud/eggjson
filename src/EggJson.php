<?php
/**
 * Class EggJson
 */
class EggJson
{
    /**
     * @var int
     */
	protected $depth = 512;

    /**
     * @var array
     */
	protected $options = array();

    /**
     * @var mixed
     */
	protected $value;

    /**
     * EggJson constructor.
     * @param mixed $value
     */
	private function __construct($value)
	{
		$this->value = $value;
	}

    /**
     * @param string $option
     * @return string|void
     */
	private function resolveJsonConst($option)
	{
		$dash = preg_replace_callback('/[A-Z]/','_$1',$option);

		$const = 'JSON_' . strtoupper($dash);
		if (false === defined($const)) {
			return;
		}

		return $const;
	}

    /**
     * @return void
     */
	private function clearLastError()
	{
		json_encode(null);
	}

    /**
     * @return int
     */
	private function compileOptions()
	{
		return array_reduce($this->options, function ($carry, $item) {
			return $carry |= $item;
		}, 0);
	}

    /**
     * @param mixed $value
     * @return mixed
     * @throws JsonException
     */
	private function validateOrThrowException($value)
	{
		if ($error = json_last_error()) {
			if (function_exists('json_last_error_msg')) {
				$errorMsg = json_last_error_msg();
			} else {
				static $errors = array(
					JSON_ERROR_DEPTH          => 'Maximum stack depth exceeded',
					JSON_ERROR_STATE_MISMATCH => 'State mismatch (invalid or malformed JSON)',
					JSON_ERROR_CTRL_CHAR      => 'Control character error, possibly incorrectly encoded',
					JSON_ERROR_SYNTAX         => 'Syntax error',
					JSON_ERROR_UTF8           => 'Malformed UTF-8 characters, possibly incorrectly encoded'
				);

            	$errorMsg = isset($errors[$error]) ? $errors[$error] : 'Unknown error';
			}

			if (in_array('JSON_THROW_ON_ERROR', $this->options, true)) {
				if (!class_exists('JsonException')) {
				    require_once __DIR__ . '/JsonException.php';
				}

				throw new JsonException($errorMsg, $error);
			} else {
				trigger_error($errorMsg);
			}
		}

		return $value;
	}

    /**
     * @param mixed $value
     * @return EggJson
     */
	public static function eat($value)
	{
		return new self($value);
	}

    /**
     * @param string $option
     * @return $this
     */
	public function withOption($option)
	{
		$option = $this->resolveJsonConst($option);
		if (!in_array($option, $this->options, true)) {
			$this->options[] = $option;
		}

		return $this;
	}

    /**
     * @param array $options
     * @return $this
     */
	public function withOptions(array $options)
	{
		foreach ($options as $option) {
			$this->withOption($option);
		}

		return $this;
	}

    /**
     * @param string $option
     * @return $this
     */
	public function withoutOption($option)
	{
		$option = $this->resolveJsonConst($option);
		if (false !== ($index = array_search($option, $this->options, true))) {
			unset($this->options[$index]);
		}

		return $this;
	}

    /**
     * @param array $options
     * @return $this
     */
	public function withoutOptions(array $options)
	{
		foreach ($options as $option) {
			$this->withoutOption($option);
		}

		return $this;
	}

    /**
     * @param int $depth
     * @return $this
     */
	public function withDepth($depth)
	{
		$depth = (int) $depth;
		if (!$depth) {
			throw new UnexpectedValueException(
                'The depth parameter must be greater than 0'
            );
		}

		$this->depth = $depth;

		return $this;
	}

    /**
     * @return mixed
     * @throws JsonException
     */
	public function encode()
	{
		$this->clearLastError();

		$options = $this->compileOptions();

		$value = json_encode($this->value, $options, $this->depth);
	
		return $this->validateOrThrowException($value);
	}

    /**
     * @param false $assoc
     * @return mixed
     * @throws JsonException
     */
	public function decode($assoc = false)
	{
		$this->clearLastError();

		$assoc   = (bool) $assoc; 
		$options = $this->compileOptions();

		$value = json_decode($this->value, $assoc, $this->depth, $options);

		return $this->validateOrThrowException($value);
	}
}
