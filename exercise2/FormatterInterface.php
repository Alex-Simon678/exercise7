<?php

interface FormatterInterface
{
    public function format(array $data): string;
}
