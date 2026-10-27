# Exercise 01 – Code Smells in `OrderProcessor`

| # | Smell | Category | Where it appeared | How it was addressed |
|---|-------|----------|-------------------|----------------------|
| 1 | **Long Method** | Bloater | `processOrder()` calculated the total, wrote two DB tables, sent an e-mail and an SMS and logged – all in one method. | Split into `calculateOrder()`, `saveToDb()`, `sendNotifications()` and `logOrder()`; `processOrder()` only orchestrates. |
| 2 | **Long Parameter List** | Bloater | `processOrder()` took 10 arguments. | Now takes 3: `Customer`, `Product`, `int $quantity`. |
| 3 | **Data Clumps** | Bloater | `$customerName, $customerEmail, $customerPhone, $customerAddress, $customerCity, $customerZip` and `$productId, $productName, $productPrice` always travelled together. | Extracted into the `Customer` and `Product` classes. |
| 4 | **Primitive Obsession** | Bloater | Customer, product and the order outcome were represented by loose strings/floats; the method returned a bare `float`. | Value objects `Customer`, `Product` and `OrderResult` (customer, product, quantity, subtotal, tax, total). `processOrder()` now returns an `OrderResult`. |
| 5 | **Divergent Change / SRP violation** | Change Preventer | One method had many reasons to change (pricing, persistence, e-mail, SMS, logging). | Each responsibility lives in its own small method (and the services are injected), so a change touches only one place. |
| 6 | **Duplicate Code** | Dispensable | The notification text was built twice (e-mail and SMS); the comment even admits the SMS logic was "duplicated from another class". | The message is built once in `sendNotifications()` and reused for both channels. |
| 7 | **Comments as deodorant** | Dispensable | Comments such as `// Calculate total`, `// Save to database` described what the code did instead of the code being self-explanatory. | Replaced by well-named methods, so the comments are no longer needed. |
| 8 | **Magic number / public configuration** | Dispensable | `public $taxRate = 0.27;` – an unexplained, publicly writable value. | `private float $taxRate`, only used inside the class. |
| 9 | **Public fields (lack of encapsulation)** | Coupler | `public $db`, `$mailer`, `$logger`, `$taxRate` could be modified from anywhere. | All properties are `private` and typed. |
| 10 | **Hard-coded dependency** | Coupler | `new SmsService()` was created inside the method, making the class impossible to test or swap. | `SmsService` (like db, mailer, logger) is injected through the constructor (dependency injection). |
| 11 | **Missing types** | Dispensable | No parameter, return or property types. | Typed parameters, return types and properties everywhere. |
| 12 | **SQL built by string interpolation** | Security smell | User data was concatenated directly into SQL strings (SQL injection risk). | *Not addressed in this exercise* – it would require prepared statements, which is outside the scope of the task. |
