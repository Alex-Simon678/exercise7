# Exercise 02 – Code Smells in `ReportGenerator`

| # | Smell | Category | Where it appeared | How it was addressed |
|---|-------|----------|-------------------|----------------------|
| 1 | **Switch Statements** (`if / elseif` chain on a type) | Object-Orientation Abuser | `generate()` branched on `'pdf'`, `'csv'`, `'html'`. | Polymorphism: every format is a class implementing `FormatterInterface`. |
| 2 | **Open/Closed violation** (adding a format means editing existing code) | Change Preventer | A new format (JSON, XML) required a new `elseif` inside `generate()`. | New formats are new classes only – demonstrated by `JsonFormatter`, added without touching `ReportGenerator`. |
| 3 | **Single Responsibility violation / Divergent Change** | Change Preventer | One method formatted the content, wrote the file and printed to the screen. | Formatting (`*Formatter`), saving (`FileSaver`) and orchestration (`ReportGenerator`) are separate classes. |
| 4 | **Primitive Obsession** (string type code) | Bloater | The format was chosen with the magic strings `'pdf'`, `'csv'`, `'html'`; a typo silently did nothing. | The caller passes a formatter object instead of a string. |
| 5 | **Duplicate Code** | Dispensable | `file_put_contents(...)` and `echo "... saved."` were copy-pasted in each branch. | Written once in `FileSaver::save()`. |
| 6 | **Hard-coded values** | Dispensable | File names (`report.pdf`, ...) were baked into the logic. | The file name is a parameter of `generate()`. |
| 7 | **Tight coupling to the file system / output** | Coupler | Logic directly called `file_put_contents` and `echo`, so it could not be tested or redirected (e.g. to S3 or a DB). | `ReportGenerator` depends on `SaverInterface` (Dependency Inversion), injected via the constructor. |
| 8 | **Side effects in a "generate" method** | Dispensable | The method printed output with `echo`. | Formatters are pure (return a string); output happens only in the saver. |
| 9 | **Missing types** | Dispensable | `$type` and `$data` had no types, no return type. | Typed signatures and `array<mixed>` docblocks for PHPStan. |
