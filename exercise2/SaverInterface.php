<?php

interface SaverInterface
{
    public function save(string $content, string $filename): void;
}
