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
document.addEventListener('DOMContentLoaded', () => {
    document.dispatchEvent(new CustomEvent('charts:ready'));
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
                window.Livewire.dispatchTo('loans.loan-kanban', 'updateStatus', { loanId, status });
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

Alpine.start();
