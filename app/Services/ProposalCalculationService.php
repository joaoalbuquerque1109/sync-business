<?php

namespace App\Services;

class ProposalCalculationService
{
    /**
     * @param array<int, array<string, float|int|string|null>> $items
     * @return array{subtotal: float, discount: float, total: float, normalized_items: array<int, array<string, float|int|string|null>>}
     */
    public function calculate(array $items): array
    {
        $subtotal = 0.0;
        $discount = 0.0;
        $normalized = [];

        foreach ($items as $index => $item) {
            $quantity = (float) ($item['quantity'] ?? 0);
            $unitPrice = (float) ($item['unit_price'] ?? 0);
            $lineDiscount = (float) ($item['discount_amount'] ?? 0);
            $lineSubtotal = $quantity * $unitPrice;
            $lineTotal = max($lineSubtotal - $lineDiscount, 0);

            $subtotal += $lineSubtotal;
            $discount += $lineDiscount;

            $normalized[] = [
                ...$item,
                'line_number' => $index + 1,
                'subtotal_amount' => round($lineSubtotal, 2),
                'total_amount' => round($lineTotal, 2),
            ];
        }

        return [
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'total' => round($subtotal - $discount, 2),
            'normalized_items' => $normalized,
        ];
    }
}
