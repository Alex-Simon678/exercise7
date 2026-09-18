<?php

class ReportGenerator
{
    private SaverInterface $saver;

    public function __construct(SaverInterface $saver)
    {
        $this->saver = $saver;
    }

    public function generate(FormatterInterface $formatter, array $data, string $filename): void
    {
        $content = $formatter->format($data);
        $this->saver->save($content, $filename);
    }
}
