import './bootstrap';

import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

window.Livewire = Livewire;

// Chart initialization helpers - defined on window so they work reliably with Alpine x-init
window.initMonthlyChart = function (el, labels, donations, campaigns) {
    if (!el || !labels || !labels.length) return;
    try {
        const ctx = el.getContext('2d');
        if (!ctx) return;
        // Destroy previous chart instance if exists (prevents memory leaks)
        if (el.__chart) el.__chart.destroy();
        el.__chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Donasi Umum',
                        data: donations,
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: '#10b981',
                        borderWidth: 1,
                        borderRadius: 4,
                    },
                    {
                        label: 'Donasi Kampanye',
                        data: campaigns,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: '#3b82f6',
                        borderWidth: 1,
                        borderRadius: 4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 16,
                            font: { size: 11 },
                        },
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                return 'Rp ' + Number(ctx.raw).toLocaleString('id-ID');
                            },
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) => {
                                if (value >= 1000000) return 'Rp' + (value / 1000000).toFixed(1) + 'jt';
                                if (value >= 1000) return 'Rp' + (value / 1000).toFixed(0) + 'rb';
                                return 'Rp' + value;
                            },
                            font: { size: 10 },
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' },
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } },
                    },
                },
            },
        });
    } catch (e) {
        console.warn('Chart init error (monthly):', e);
    }
};

window.initYearlyChart = function (el, labels, donations, campaigns) {
    if (!el || !labels || !labels.length) return;
    try {
        const ctx = el.getContext('2d');
        if (!ctx) return;
        // Destroy previous chart instance if exists (prevents memory leaks)
        if (el.__chart) el.__chart.destroy();
        el.__chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Donasi Umum',
                        data: donations,
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: '#10b981',
                        borderWidth: 1,
                        borderRadius: 4,
                    },
                    {
                        label: 'Donasi Kampanye',
                        data: campaigns,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: '#3b82f6',
                        borderWidth: 1,
                        borderRadius: 4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 16,
                            font: { size: 11 },
                        },
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                return 'Rp ' + Number(ctx.raw).toLocaleString('id-ID');
                            },
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) => {
                                if (value >= 1000000) return 'Rp' + (value / 1000000).toFixed(1) + 'jt';
                                if (value >= 1000) return 'Rp' + (value / 1000).toFixed(0) + 'rb';
                                return 'Rp' + value;
                            },
                            font: { size: 10 },
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' },
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } },
                    },
                },
            },
        });
    } catch (e) {
        console.warn('Chart init error (yearly):', e);
    }
};

window.initCategoryChart = function (el, labels, amounts, colors) {
    if (!el || !labels || !labels.length) return;
    try {
        const ctx = el.getContext('2d');
        if (!ctx) return;
        // Destroy previous chart instance if exists (prevents memory leaks)
        if (el.__chart) el.__chart.destroy();
        el.__chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: amounts,
                    backgroundColor: colors,
                    borderColor: '#ffffff',
                    borderWidth: 3,
                    hoverOffset: 8,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 16,
                            font: { size: 11 },
                        },
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = total > 0 ? ((ctx.raw / total) * 100).toFixed(1) : 0;
                                return 'Rp ' + Number(ctx.raw).toLocaleString('id-ID') + ' (' + pct + '%)';
                            },
                        },
                    },
                },
            },
        });
    } catch (e) {
        console.warn('Chart init error (category):', e);
    }
};
