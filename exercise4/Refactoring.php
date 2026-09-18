<?php

const ORDER_STATUS_ACTIVE = 1;
const ORDER_STATUS_PENDING = 2;
const DISCOUNT_THRESHOLD = 100.0;
const DISCOUNT_MULTIPLIER = 0.9;
const PENDING_NOTE_SUFFIX = ' (pending)';

function buildStatementLines(
    array $orderItems,
    string $statusNote,
    bool $includeInStatement = true,
    bool $applyBulkDiscount = false
): array {

    if (!$includeInStatement) {
        return [];
    }

    $lines = [];

    foreach ($orderItems as $orderItem) {
        if ($orderItem['status'] === ORDER_STATUS_ACTIVE) {
            $lines[] = buildActiveLine($orderItem, $statusNote, $applyBulkDiscount);
        } elseif ($orderItem['status'] === ORDER_STATUS_PENDING) {
            $lines[] = buildPendingLine($orderItem, $statusNote);
        }
    }

    return $lines;
}

function buildActiveLine(array $orderItem, string $statusNote, bool $applyBulkDiscount): array
{
    return [
        'name' => $orderItem['name'],
        'total' => calculateLineTotal($orderItem['price'], $orderItem['quantity'], $applyBulkDiscount),
        'note' => $statusNote,
    ];
}

function buildPendingLine(array $orderItem, string $statusNote): array
{
    return [
        'name' => $orderItem['name'],
        'total' => 0.0,
        'note' => $statusNote . PENDING_NOTE_SUFFIX,
    ];
}

function calculateLineTotal(float $unitPrice, int $quantity, bool $applyBulkDiscount): float
{
    $subtotal = $unitPrice * $quantity;

    if ($applyBulkDiscount && $subtotal > DISCOUNT_THRESHOLD) {
        return $subtotal * DISCOUNT_MULTIPLIER;
    }

    return $subtotal;
}
