<?php

interface FormatterInterface
{
    /**
     * @param array<mixed> $data
     */
    public function format(array $data): string;
}
