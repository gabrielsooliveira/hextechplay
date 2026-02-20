export function formatStatusBet(statusBet) {
    switch (String(statusBet)) {
        case '0': return `Em andamento`;
        case '1': return `Anulada`;
        case '2': return `Cash Out`;
        case '3': return `Erro`;
        case '4': return `Acerto`;
        default: return statusBet;
    }
}

export function formatCurrencyBRL(value) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value);
}

export function formatDate(date, locale = 'pt-BR') {
    return new Intl.DateTimeFormat(locale, {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    }).format(new Date(date));
}

