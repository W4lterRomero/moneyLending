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
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek'
                },
                events: @js($events),
                eventClick(info) {
                    if (info.event.url) {
                        window.location.href = info.event.url;
                        info.jsEvent.preventDefault();
                    }
                },
            });
            calendar.render();
        };

        if (window.FullCalendarReady) {
            initCalendar();
        } else {
            document.addEventListener('fullcalendar:ready', initCalendar, { once: true });
        }
    ">
    <div class="panel-apple">
        <div class="p-4 flex items-center justify-between">
            <div>
                <div class="text-xs uppercase text-slate-500">Calendario de vencimientos</div>
                <h3 class="text-lg font-semibold text-slate-800">Próximas cuotas</h3>
            </div>
            <div class="flex items-center gap-3 text-xs text-slate-500">
                <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-sky-500"></span>Pendiente</span>
                <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-amber-500"></span>Vencido</span>
                <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-emerald-500"></span>Pagado</span>
            </div>
        </div>
        <div class="border-t border-slate-100" wire:ignore>
            <div x-ref="calendar"></div>
        </div>
    </div>
</div>
