import { test } from 'node:test';
import assert from 'node:assert/strict';
import { formatRupiahDigits, formatRupiahInput } from '../../resources/js/rupiah-input.js';

test('formats typed, pasted and server-normalized amounts without numeric rounding', () => {
    for (const [value, expected] of [
        ['', ''], [null, ''], ['1000', '1.000'], ['1.500.000', '1.500.000'],
        ['Rp 25.000', '25.000'], ['0001500', '1.500'], ['9007199254740993', '9.007.199.254.740.993'],
    ]) assert.equal(formatRupiahDigits(value), expected);
});

test('restores formatted display after server updates while preserving editing position', () => {
    const input = { value: '1500000', selectionStart: 3, setSelectionRange(start) { this.selectionStart = start; } };
    globalThis.document = { activeElement: input };
    formatRupiahInput(input);
    assert.equal(input.value, '1.500.000');
    assert.equal(input.selectionStart, 4);
    formatRupiahInput(input, '2500000');
    assert.equal(input.value, '2.500.000');
    delete globalThis.document;
});
