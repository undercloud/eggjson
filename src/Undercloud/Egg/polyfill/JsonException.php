<?php

if (false === class_exists('JsonException', false)) {
    class JsonException extends Exception
    {
        /**
         * @param string $file
         * @return void
         */
        public function setFile($file)
        {
            $this->file = $file;
        }

        /**
         * @param int $line
         * @return void
         */
        public function setLine($line)
        {
            $this->line = $line;
        }
    }
}
