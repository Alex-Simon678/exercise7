# Exercise 04 – Code Smells in the function `d()`

| # | Smell | Category | Where it appeared | How it was addressed |
|---|-------|----------|-------------------|----------------------|
| 1 | **Mysterious Name** | Dispensable | Function `d`, parameters `$a $b $c $d`, locals `$r $t $i`, keys `'s' 'p' 'q' 'n' 'e'`. | Meaningful names: `buildStatementLines`, `$orderItems`, `$statusNote`, `$includeInStatement`, `$applyBulkDiscount`, keys `status`, `price`, `quantity`, `name`, `note`. |
| 2 | **Magic Numbers** | Dispensable | `1`, `2` (status), `100` (threshold), `0.9` (discount), `' (pending)'`. | Named constants: `ORDER_STATUS_ACTIVE`, `ORDER_STATUS_PENDING`, `DISCOUNT_THRESHOLD`, `DISCOUNT_MULTIPLIER`, `PENDING_NOTE_SUFFIX`. |
| 3 | **Deeply nested conditionals** (Conditional Complexity) | Bloater | Up to 4 levels of `if` inside a `for` loop. | Guard clause (`if (!$includeInStatement) return [];`) and flat branching. |
| 4 | **Duplicated condition** | Dispensable | `if ($c)` was repeated in both status branches. | Checked once, up front, as a guard clause. |
| 5 | **Long Function / does too much** | Bloater | Filtering, price calculation, discounting and building result rows in one function. | Extracted `buildActiveLine()`, `buildPendingLine()` and `calculateLineTotal()`. |
| 6 | **Flag Arguments** | Bloater | Booleans `$c` and `$d` changed the behaviour of the function without saying what they meant. | Self-explanatory parameter names (`$includeInStatement`, `$applyBulkDiscount`). |
| 7 | **Loose comparison** | Dispensable | `== 1`, `== 2` could match unintended values due to type juggling. | Strict comparison with `===`. |
| 8 | **Inefficient / noisy loop** | Dispensable | `for` with `count($a)` evaluated on every iteration and `$a[$i]` repeated everywhere. | `foreach ($orderItems as $orderItem)`. |
| 9 | **Primitive Obsession** (arrays with cryptic keys as data structure) | Bloater | Items and results were anonymous arrays with single-letter keys. | Named keys, documented with `array<string, mixed>` PHPDoc shapes. |
| 10 | **Missing type declarations** | Dispensable | No parameter or return types. | Typed parameters and return types (`array`, `string`, `bool`, `float`, `int`). |
| 11 | **Formatting** | Dispensable | Inconsistent style, no PSR-12. | Formatted according to PSR-12. |
