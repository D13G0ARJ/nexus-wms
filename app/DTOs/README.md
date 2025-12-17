# DTOs Directory

This directory contains Data Transfer Objects (DTOs).

DTOs are used to transfer data between application layers without exposing HTTP request objects.

## Example:
```php
<?php

namespace App\DTOs;

readonly class CreateProductDTO
{
    public function __construct(
        public string $name,
        public string $sku,
        public ?string $description,
        public int $category_id,
    ) {}
}
```
