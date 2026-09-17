<?php
class FileSaver implements SaverInterface 
{
    public function save(string $content, string $filename): void 
    {
        file_put_contents($filename, $content);
        echo strtoupper(pathinfo($filename, PATHINFO_EXTENSION)) . " report saved.\n";
    }
}