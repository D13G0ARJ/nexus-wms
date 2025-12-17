# Repositories Directory

This directory contains Repository classes for complex database queries.

Repositories abstract data access logic and make code more testable.

## Example:
```php
<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    public function getLowStockProducts(int $threshold = 10): Collection
    {
        return Product::query()
            ->whereHas('inventory', function ($query) use ($threshold) {
                $query->havingRaw('SUM(quantity) < ?', [$threshold]);
            })
            ->with(['variants', 'category'])
            ->get();
    }
}
```
