<div x-data="{ calendar: null }"
    x-init="
        const initCalendar = () => {
            if (!window.FullCalendar) return;
            const { Calendar, dayGridPlugin, interactionPlugin } = window.FullCalendar;
            const calendarEl = $refs.calendar;
            calendar = new Calendar(calendarEl, {
                plugins: [dayGridPlugin, interactionPlugin],
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek'
                },
                events: @js($events),
            });
            calendar.render();
        };

        if (window.FullCalendarReady) {
            initCalendar();
        } else {
            document.addEventListener('fullcalendar:ready', initCalendar, { once: true });
        }
    ">
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
        <div class="p-4 font-semibold text-slate-700">Calendario de vencimientos</div>
        <div class="border-t border-slate-100">
            <div x-ref="calendar"></div>
        </div>
    </div>
</div>
