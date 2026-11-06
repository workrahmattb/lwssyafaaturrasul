export function formatRupiahDigits(value) {
    const digits = String(value ?? '').replace(/[^0-9]/g, '').replace(/^0+(?=\d)/, '');
    return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

export function formatRupiahInput(input, value = input.value) {
    const formatted = formatRupiahDigits(value);
    if (input.value === formatted) return;

    const cursor = input.selectionStart;
    const digitsBeforeCursor = cursor === null ? null : input.value.slice(0, cursor).replace(/[^0-9]/g, '').length;
    input.value = formatted;

    if (digitsBeforeCursor !== null && input === document.activeElement) {
        let position = 0;
        let digits = 0;
        while (position < formatted.length && digits < digitsBeforeCursor) {
            if (/\d/.test(formatted[position])) digits++;
            position++;
        }
        input.setSelectionRange(position, position);
    }
}
