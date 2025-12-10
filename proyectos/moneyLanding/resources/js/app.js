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
        const chartDataset = incomeCtx.dataset.chart ? JSON.parse(incomeCtx.dataset.chart) : null;
        if (chartDataset) {
            if (incomeChartInstance) incomeChartInstance.destroy();
            incomeChartInstance = new window.Chart(incomeCtx, {
                type: 'line',
                data: {
                    labels: chartDataset.labels,
                    datasets: [
                        {
                            label: 'Prestado',
                            data: chartDataset.lent,
                            borderColor: '#4dabf7',
                            backgroundColor: '#4dabf733',
                            tension: 0.35,
                        },
                        {
                            label: 'Cobrado',
                            data: chartDataset.collected,
                            borderColor: '#10b981',
                            backgroundColor: '#10b98133',
                            tension: 0.35,
                        }
                    ]
                },
                options: { plugins: { legend: { display: true } } }
            });
        }
    }

    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        const statusData = statusCtx.dataset.status ? JSON.parse(statusCtx.dataset.status) : null;
        if (statusData) {
            if (statusChartInstance) statusChartInstance.destroy();
            const values = Object.values(statusData);
            statusChartInstance = new window.Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Activos', 'Morosos', 'Completados'],
                    datasets: [{
                        data: values,
                        backgroundColor: ['#4dabf7', '#f59e0b', '#10b981']
                    }]
                },
                options: { plugins: { legend: { position: 'bottom' } } }
            });
        }
    }
};

document.addEventListener('charts:ready', () => window.renderCharts());
document.addEventListener('charts-refresh', () => window.renderCharts());
document.addEventListener('livewire:load', () => window.renderCharts());
document.addEventListener('livewire:navigated', () => window.renderCharts());

Alpine.start();
