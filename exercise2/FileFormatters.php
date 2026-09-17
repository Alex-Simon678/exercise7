<?php
class PdfFormatter implements FormatterInterface 
{
    public function format(array $data): string 
    {
        return "<pdf>" . implode(",", $data) . "</pdf>";
    }
}

class CsvFormatter implements FormatterInterface 
{
    public function format(array $data): string 
    {
        return implode("\n", $data);
    }
}

class HtmlFormatter implements FormatterInterface 
{
    public function format(array $data): string 
    {
        $content = "<html><body><ul>";
        foreach ($data as $item) {
            $content .= "<li>{$item}</li>";
        }
        $content .= "</ul></body></html>";
        return $content;
    }
}

class JsonFormatter implements FormatterInterface 
{
    public function format(array $data): string 
    {
        return json_encode($data);
    }
}

class FileSaver implements SaverInterface 
{
    public function save(string $content, string $filename): void 
    {
        file_put_contents($filename, $content);
        echo strtoupper(pathinfo($filename, PATHINFO_EXTENSION)) . " report saved.\n";
    }
}