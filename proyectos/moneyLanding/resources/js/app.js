import './bootstrap';
import Alpine from 'alpinejs';
import Sortable from 'sortablejs';
import Chart from 'chart.js/auto';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

window.Alpine = Alpine;
window.Sortable = Sortable;
window.Chart = Chart;
window.FullCalendar = { Calendar, dayGridPlugin, interactionPlugin };
window.FullCalendarReady = true;
document.addEventListener('DOMContentLoaded', () => {
    document.dispatchEvent(new CustomEvent('charts:ready'));
    document.dispatchEvent(new CustomEvent('fullcalendar:ready'));
});

// Atajo global CMD/CTRL + K para búsqueda
document.addEventListener('keydown', (event) => {
    if ((event.metaKey || event.ctrlKey) && (event.key === 'k' || event.key === 'K')) {
        event.preventDefault();
        document.dispatchEvent(new CustomEvent('open-search'));
    }
});

const setThemeClass = (theme) => {
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
};

const storedTheme = localStorage.getItem('theme');
const preferredTheme = storedTheme ?? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
setThemeClass(preferredTheme);

Alpine.store('theme', {
    current: preferredTheme,
    toggle() {
        this.current = this.current === 'dark' ? 'light' : 'dark';
        localStorage.setItem('theme', this.current);
        setThemeClass(this.current);
    },
});

window.initKanbanSortables = () => {
    const columns = document.querySelectorAll('[data-kanban-column]');
    columns.forEach((col) => {
        const list = col.querySelector('[data-kanban-list]');
        if (!list || list.dataset.sortableBound) return;
        list.dataset.sortableBound = '1';
        new window.Sortable(list, {
            group: 'loans',
            animation: 150,
            onEnd: (event) => {
                const loanId = event.item.dataset.loanId;
                const status = event.to?.dataset?.kanbanColumn;
                const componentId = col.closest('[data-kanban-id]')?.dataset?.kanbanId;
                if (componentId && window.Livewire?.find(componentId)) {
                    window.Livewire.find(componentId).call('updateStatus', loanId, status);
                }
            },
        });
    });
};

document.addEventListener('alpine:init', () => {
    window.initKanbanSortables();
});

document.addEventListener('livewire:navigated', () => window.initKanbanSortables());
document.addEventListener('livewire:load', () => {
    window.initKanbanSortables();
    Livewire.hook('morph.updated', () => window.initKanbanSortables());
});

// Charts rendering helper
let incomeChartInstance = null;
let statusChartInstance = null;
window.renderCharts = () => {
    if (!window.Chart) return;

    const incomeCtx = document.getElementById('incomeChart');
    if (incomeCtx) {
        let chartDataset = null;
        try {
            chartDataset = incomeCtx.dataset.chart ? JSON.parse(incomeCtx.dataset.chart) : null;
        } catch (e) {
            console.error('Chart dataset inválido', e);
        }
        if (chartDataset) {
            if (incomeChartInstance) incomeChartInstance.destroy();
            incomeChartInstance = new window.Chart(incomeCtx, {
                type: 'line',
                data: {
                    labels: chartDataset.labels || [],
                    datasets: [
                        {
                            label: 'Prestado',
                            data: chartDataset.lent || [],
                            borderColor: '#4dabf7',
                            backgroundColor: 'rgba(77, 171, 247, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                        },
                        {
                            label: 'Cobrado',
                            data: chartDataset.collected || [],
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: { size: 12 }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { size: 13 },
                            bodyFont: { size: 12 },
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': $' + context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        let statusData = null;
        try {
            statusData = statusCtx.dataset.status ? JSON.parse(statusCtx.dataset.status) : null;
        } catch (e) {
            console.error('Status dataset inválido', e);
        }
        if (statusData) {
            if (statusChartInstance) statusChartInstance.destroy();

            // Extraer valores en el orden correcto
            const values = [
                statusData.active || 0,
                statusData.delinquent || 0,
                statusData.completed || 0
            ];

            // Si todos son 0, mostrar mensaje
            const total = values.reduce((a, b) => a + b, 0);
            if (total === 0) {
                values[0] = 1; // Mostrar algo visual
            }

            statusChartInstance = new window.Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Activos', 'Morosos', 'Completados'],
                    datasets: [{
                        data: values,
                        backgroundColor: ['#4dabf7', '#f59e0b', '#10b981'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 10,
                                font: { size: 11 }
                            }
                        },
                        tooltip: {
                            enabled: total > 0
                        }
                    }
                }
            });
        }
    }
};

// Renderizar charts inicialmente
document.addEventListener('charts:ready', () => window.renderCharts());
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => window.renderCharts(), 200);
});

// Livewire hooks
document.addEventListener('livewire:init', () => {
    // Escuchar evento de Livewire 3
    Livewire.on('charts-refresh', () => {
        setTimeout(() => window.renderCharts(), 200);
    });
});

document.addEventListener('livewire:navigated', () => {
    setTimeout(() => window.renderCharts(), 200);
});

// Hook para detectar actualizaciones del componente
Livewire.hook('morph.updated', ({el, component}) => {
    if (el.querySelector('#incomeChart') || el.querySelector('#statusChart')) {
        setTimeout(() => window.renderCharts(), 250);
    }
});

Alpine.start();
