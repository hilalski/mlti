@extends('layouts.app')
@section('title', $type === 'zoom' ? 'Pengajuan Zoom | MLTI' : 'Pengajuan Ruang Jambi | MLTI')
@section('content')
    @if ($type === 'zoom')
        <div class="pagetitle">
            <h1>Pengajuan Zoom</h1>
            <p class="text-muted mb-0">Periksa kalender seluruh pengajuan untuk melihat ketersediaan slot.</p>
        </div>
        <section class="section">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0 fw-bold">Formulir Pengajuan Zoom</h5><a
                            href="{{ route('general.booking.history', 'zoom') }}" class="btn text-white zoom-history"><i
                                class="bi bi-clock-history me-1"></i>Riwayat Pengajuan</a>
                    </div>
                    <form method="POST" action="{{ route('general.booking.store', 'zoom') }}" id="zoom-form">@csrf
                        <div class="row g-3">
                            <div class="col-lg-5"><label class="form-label fw-semibold">Judul kegiatan *</label><input
                                    name="title" class="form-control" value="{{ old('title') }}" required></div>
                            <div class="col-lg-3"><label class="form-label fw-semibold">Perkiraan partisipan *</label>
                                <div class="input-group"><input type="number" name="participants" min="1"
                                        class="form-control" value="{{ old('participants') }}" required><span
                                        class="input-group-text">orang</span></div>
                            </div>
                            <div class="col-lg-4"><label class="form-label fw-semibold d-block">Waktu kegiatan *</label>
                                <div class="time-toggle"><input class="btn-check" type="radio" name="mode"
                                        id="mode-hours" checked><label for="mode-hours"><i
                                            class="bi bi-clock me-1"></i>Jam</label><input class="btn-check" type="radio"
                                        name="mode" id="mode-days"><label for="mode-days"><i
                                            class="bi bi-calendar-range me-1"></i>Hari</label></div>
                            </div>
                            <div class="col-md-4" id="single-date-wrap"><label class="form-label">Tanggal *</label><input
                                    type="date" id="single-date" class="form-control"></div>
                            <div class="col-md-4" id="start-time-wrap"><label class="form-label">Jam mulai *</label><input
                                    type="time" id="start-time" class="form-control" value="08:00"></div>
                            <div class="col-md-4" id="end-time-wrap"><label class="form-label">Jam selesai *</label><input
                                    type="time" id="end-time" class="form-control" value="09:00"></div>
                            <div class="col-md-6 d-none" id="start-date-wrap"><label class="form-label">Tanggal mulai
                                    *</label><input type="date" id="start-date" class="form-control"></div>
                            <div class="col-md-6 d-none" id="end-date-wrap"><label class="form-label">Tanggal selesai
                                    *</label><input type="date" id="end-date" class="form-control"></div>
                            <input type="hidden" name="starts_at" id="starts-at"><input type="hidden" name="ends_at"
                                id="ends-at">
                            <div class="col-12"><label class="streaming-option"><input class="form-check-input"
                                        type="checkbox" name="live_streaming" value="1"
                                        {{ old('live_streaming') ? 'checked' : '' }}><span><strong>Akses live
                                            streaming</strong><small>Centang jika kegiatan memerlukan akses siaran
                                            langsung.</small></span></label>
                                @error('starts_at')
                                    <small class="text-danger d-block mt-2">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12 text-end"><button class="btn text-white px-4 zoom-form-submit">Kirim
                                    Pengajuan</button></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card border-0 shadow-sm zoom-calendar-card">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-2 calendar-toolbar">
                        <div class="d-flex gap-1"><button class="btn btn-light btn-sm" id="today">Hari
                                Ini</button><button class="btn btn-light btn-sm" id="prev"><i
                                    class="bi bi-chevron-left"></i></button><button class="btn btn-light btn-sm"
                                id="next"><i class="bi bi-chevron-right"></i></button></div><strong
                            id="range"></strong>
                        <div class="d-flex align-items-center gap-3">
                            <div class="calendar-view"><button class="active" data-view="day">Hari</button><button
                                    data-view="week">Minggu</button><button data-view="month">Bulan</button></div><small
                                class="d-none d-lg-inline"><span class="badge text-bg-warning">Menunggu</span> <span
                                    class="badge text-bg-success">Disetujui</span></small>
                        </div>
                    </div>
                    <div class="calendar-scroll">
                        <div id="calendar" class="zoom-calendar"></div>
                    </div>
                </div>
            </div>
        </section>
        @push('styles')
            <style>
                .zoom-history,
                .zoom-form-submit {
                    background: linear-gradient(135deg, #ff84ba, #99c2ff);
                    border: 0
                }

                .time-toggle,
                .calendar-view {
                    display: inline-flex;
                    padding: 3px;
                    background: #fff2f8;
                    border: 1px solid #ffd5e7;
                    border-radius: 9px;
                    gap: 2px
                }

                .time-toggle label,
                .calendar-view button {
                    margin: 0;
                    border: 0;
                    background: transparent;
                    border-radius: 6px;
                    padding: 5px 11px;
                    color: #83586b;
                    font-size: .82rem;
                    font-weight: 600;
                    cursor: pointer
                }

                .time-toggle .btn-check:checked+label,
                .calendar-view button.active {
                    background: linear-gradient(135deg, #ff84ba, #99c2ff);
                    color: #fff;
                    box-shadow: 0 2px 5px rgba(255, 132, 186, .28)
                }

                .streaming-option {
                    display: flex;
                    align-items: center;
                    gap: 11px;
                    padding: 11px 14px;
                    border: 1px solid #f5d7e6;
                    border-radius: 10px;
                    background: linear-gradient(90deg, #fff8fc, #f7fbff);
                    cursor: pointer
                }

                .streaming-option input {
                    width: 18px;
                    height: 18px;
                    accent-color: #ff84ba
                }

                .streaming-option small {
                    display: block;
                    color: #87717c;
                    margin-top: 2px
                }

                .zoom-calendar-card {
                    border: 1px solid #f7d9e8 !important
                }

                .calendar-toolbar {
                    background: linear-gradient(90deg, #fff8fc, #f5f9ff)
                }

                .calendar-scroll {
                    overflow-x: auto
                }

                .zoom-calendar {
                    min-width: 900px;
                    display: grid;
                    grid-template-columns: 60px repeat(7, 1fr)
                }

                .zoom-calendar>div {
                    border-right: 1px solid #eadfe5;
                    border-bottom: 1px solid #eadfe5
                }

                .cal-head {
                    height: 62px;
                    text-align: center;
                    padding: 8px;
                    font-size: .75rem;
                    font-weight: 700;
                    background: #fffafd
                }

                .cal-head b {
                    display: block;
                    font-size: 1.2rem;
                    color: #ff84ba
                }

                .cal-time {
                    height: 52px;
                    text-align: right;
                    padding: 5px;
                    font-size: .72rem;
                    color: #a68a98
                }

                .cal-slot {
                    height: 52px;
                    position: relative
                }

                .cal-event {
                    position: absolute;
                    inset: 3px;
                    overflow: hidden;
                    border-left: 3px solid #ff84ba;
                    background: #fff1f7;
                    border-radius: 5px;
                    padding: 3px 5px;
                    font-size: .7rem;
                    line-height: 1.15
                }

                .cal-event.approved {
                    border-color: #198754;
                    background: #e9f8ef
                }

                .month-calendar {
                    min-width: 850px !important;
                    display: grid !important;
                    grid-template-columns: repeat(7, minmax(0, 1fr)) !important;
                    grid-template-rows: 62px repeat(6, 110px);
                    background: #fff
                }

                .month-calendar .cal-head {
                    height: 62px;
                    border-right: 1px solid #eadfe5;
                    border-bottom: 1px solid #eadfe5
                }

                .month-day {
                    min-width: 0;
                    min-height: 110px;
                    height: 110px;
                    padding: 8px;
                    border-right: 1px solid #eadfe5;
                    border-bottom: 1px solid #eadfe5;
                    background: #fff;
                    overflow: hidden
                }

                .month-day.muted {
                    background: #fdfafd;
                    color: #c6b5bd
                }

                .month-day b {
                    display: block;
                    color: #ff84ba;
                    font-size: .85rem
                }

                .month-event {
                    display: block;
                    margin-top: 4px;
                    padding: 3px 5px;
                    background: #fff1f7;
                    border-left: 3px solid #ff84ba;
                    border-radius: 3px;
                    font-size: .7rem;
                    overflow: hidden;
                    white-space: nowrap;
                    text-overflow: ellipsis
                }

                .month-event.approved {
                    background: #e9f8ef;
                    border-color: #198754
                }

                .month-event.pending { background: #fff7d6; border-color: #f0aa00; color: #765b00 }

                .schedule-grid { min-width: 900px; display: grid; grid-template-columns: 60px repeat(var(--day-count), minmax(150px, 1fr)); grid-template-rows: 62px 468px }
                .schedule-grid .cal-head { border-right: 1px solid #eadfe5; border-bottom: 1px solid #eadfe5 }
                .schedule-axis { position: relative; border-right: 1px solid #eadfe5; background: #fff }
                .schedule-axis span { position: absolute; right: 7px; transform: translateY(-50%); font-size: .72rem; color: #a68a98 }
                .schedule-day { position: relative; border-right: 1px solid #eadfe5; background: repeating-linear-gradient(to bottom, #fff 0, #fff 51px, #eadfe5 51px, #eadfe5 52px) }
                .timed-event { position: absolute; overflow: hidden; padding: 4px 6px; border-radius: 5px; border-left: 3px solid #f0aa00; background: #fff7d6; color: #765b00; font-size: .7rem; line-height: 1.2; z-index: 2 }
                .timed-event.approved { border-color: #198754; background: #e5f6ec; color: #155b32 }
                .timed-event small { display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis }
            </style>
        @endpush
        @push('scripts')
            <script>
                const bookings = @json($calendarBookings);
                const p = n => String(n).padStart(2, '0'),
                    dateInput = d => `${d.getFullYear()}-${p(d.getMonth()+1)}-${p(d.getDate())}`,
                    today = new Date();
                ['single-date', 'start-date', 'end-date'].forEach(id => {
                    let e = document.getElementById(id);
                    e.min = dateInput(today);
                    e.value = dateInput(today)
                });

                function mode() {
                    let days = document.getElementById('mode-days').checked;
                    ['single-date-wrap', 'start-time-wrap', 'end-time-wrap'].forEach(id => document.getElementById(id).classList
                        .toggle('d-none', days));
                    ['start-date-wrap', 'end-date-wrap'].forEach(id => document.getElementById(id).classList.toggle('d-none', !
                        days))
                }
                document.querySelectorAll('[name=mode]').forEach(e => e.onchange = mode);
                document.getElementById('zoom-form').onsubmit = e => {
                    let days = document.getElementById('mode-days').checked,
                        s = days ? document.getElementById('start-date').value + 'T00:00' : document.getElementById(
                            'single-date').value + 'T' + document.getElementById('start-time').value,
                        f = days ? document.getElementById('end-date').value + 'T23:59' : document.getElementById('single-date')
                        .value + 'T' + document.getElementById('end-time').value;
                    if (new Date(f) <= new Date(s)) {
                        e.preventDefault();
                        alert('Waktu selesai harus setelah waktu mulai.');
                        return
                    }
                    document.getElementById('starts-at').value = s;
                    document.getElementById('ends-at').value = f
                };
                let cursor = new Date();

                function monday(d) {
                    d = new Date(d);
                    d.setHours(0, 0, 0, 0);
                    d.setDate(d.getDate() - (d.getDay() + 6) % 7);
                    return d
                }

                function render() {
                    let st = monday(cursor),
                        last = new Date(st);
                    last.setDate(last.getDate() + 6), names = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'], out =
                        '<div class="cal-head"></div>';
                    document.getElementById('range').textContent = st.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short'
                    }) + ' – ' + last.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    });
                    for (let i = 0; i < 7; i++) {
                        let d = new Date(st);
                        d.setDate(d.getDate() + i);
                        out += `<div class="cal-head">${names[i]}<b>${d.getDate()}</b></div>`
                    }
                    for (let h = 7; h < 20; h++) {
                        out += `<div class="cal-time">${p(h)}.00</div>`;
                        for (let i = 0; i < 7; i++) {
                            let d = new Date(st);
                            d.setDate(d.getDate() + i);
                            let ev = bookings.filter(b => {
                                let x = new Date(b.start);
                                return x.getFullYear() == d.getFullYear() && x.getMonth() == d.getMonth() && x.getDate() ==
                                    d.getDate() && x.getHours() == h
                            });
                            out +=
                                `<div class="cal-slot">${ev.map(b=>`<span class="cal-event ${b.status==='disetujui'?'approved':''}" title="${b.title}">${b.title}</span>`).join('')}</div>`
                        }
                    }
                    document.getElementById('calendar').innerHTML = out
                }
                document.getElementById('prev').onclick = () => {
                    cursor.setDate(cursor.getDate() - 7);
                    render()
                };
                document.getElementById('next').onclick = () => {
                    cursor.setDate(cursor.getDate() + 7);
                    render()
                };
                document.getElementById('today').onclick = () => {
                    cursor = new Date();
                    render()
                };
                render();
                // Tampilan kalender: hari, minggu, dan bulan.
                let calendarView = 'day';
                const dayNames = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                const isSameDay = (a, b) => a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() ===
                    b.getDate();
                const eventMarkup = (b, cls = 'cal-event') =>
                    `<span class="${cls} ${b.status==='disetujui'?'approved':'pending'}" title="${b.title}">${b.title}</span>`;

                function drawGrid(days) {
                    let html = '<div class="cal-head"></div>';
                    days.forEach(day => html +=
                        `<div class="cal-head">${dayNames[(day.getDay()+6)%7]}<b>${day.getDate()}</b></div>`);
                    html += '<div class="schedule-axis">';
                    for (let hour = 7; hour <= 16; hour++) html += `<span style="top:${(hour-7)*52}px">${p(hour)}.00</span>`;
                    html += '</div>';
                    days.forEach(day => {
                        const startOfDay = new Date(day); startOfDay.setHours(7, 0, 0, 0);
                        const endOfDay = new Date(day); endOfDay.setHours(16, 0, 0, 0);
                        const events = bookings.filter(item => new Date(item.start) < endOfDay && new Date(item.end) > startOfDay)
                            .sort((a, b) => new Date(a.start) - new Date(b.start));
                        const active = [];
                        const blocks = events.map(item => {
                            const start = new Date(item.start), end = new Date(item.end);
                            while (active.length && active[0].end <= start) active.shift();
                            const used = active.map(entry => entry.column);
                            const column = used.includes(0) ? 1 : 0;
                            active.push({ end, column }); active.sort((a, b) => a.end - b.end);
                            const visibleStart = start > startOfDay ? start : startOfDay;
                            const visibleEnd = end < endOfDay ? end : endOfDay;
                            const top = ((visibleStart - startOfDay) / 60000) / 60 * 52;
                            const height = Math.max(22, ((visibleEnd - visibleStart) / 60000) / 60 * 52 - 4);
                            const left = column === 0 ? '2%' : '51%';
                            return `<div class="timed-event ${item.status==='disetujui'?'approved':'pending'}" style="top:${top}px;height:${height}px;left:${left};width:47%"><strong>${item.title}</strong><small>${p(start.getHours())}:${p(start.getMinutes())} - ${p(end.getHours())}:${p(end.getMinutes())}</small></div>`;
                        });
                        html += `<div class="schedule-day">${blocks.join('')}</div>`;
                    });
                    return html;
                }

                function drawMonth() {
                    const first = new Date(cursor.getFullYear(), cursor.getMonth(), 1),
                        start = monday(first),
                        calendar = document.getElementById('calendar');
                    document.getElementById('range').textContent = cursor.toLocaleDateString('id-ID', {
                        month: 'long',
                        year: 'numeric'
                    });
                    let html = '';
                    dayNames.forEach(name => html += `<div class="cal-head">${name}</div>`);
                    for (let index = 0; index < 42; index++) {
                        const day = new Date(start);
                        day.setDate(day.getDate() + index);
                        const events = bookings.filter(b => isSameDay(new Date(b.start), day));
                        html +=
                            `<div class="month-day ${day.getMonth()===cursor.getMonth()?'':'muted'}"><b>${day.getDate()}</b>${events.map(b=>eventMarkup(b,'month-event')).join('')}</div>`;
                    }
                    calendar.className = 'month-calendar';
                    calendar.innerHTML = html;
                }

                function drawCalendar() {
                    if (calendarView === 'month') return drawMonth();
                    const calendar = document.getElementById('calendar');
                    let days;
                    if (calendarView === 'day') {
                        days = [new Date(cursor)];
                        calendar.style.minWidth = '420px';
                        document.getElementById('range').textContent = cursor.toLocaleDateString('id-ID', {
                            weekday: 'long',
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        });
                    } else {
                        const start = monday(cursor),
                            end = new Date(start);
                        end.setDate(end.getDate() + 6);
                        days = Array.from({
                            length: 7
                        }, (_, index) => {
                            const day = new Date(start);
                            day.setDate(day.getDate() + index);
                            return day;
                        });
                        calendar.style.minWidth = '900px';
                        document.getElementById('range').textContent = start.toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'short'
                        }) + ' - ' + end.toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        });
                    }
                    calendar.className = 'schedule-grid';
                    calendar.style.setProperty('--day-count', days.length);
                    calendar.innerHTML = drawGrid(days);
                }

                function moveCalendar(direction) {
                    if (calendarView === 'month') cursor.setMonth(cursor.getMonth() + direction);
                    else cursor.setDate(cursor.getDate() + direction * (calendarView === 'week' ? 7 : 1));
                    drawCalendar();
                }
                document.getElementById('prev').onclick = () => moveCalendar(-1);
                document.getElementById('next').onclick = () => moveCalendar(1);
                document.getElementById('today').onclick = () => {
                    cursor = new Date();
                    drawCalendar()
                };
                document.querySelectorAll('[data-view]').forEach(button => button.onclick = () => {
                    calendarView = button.dataset.view;
                    document.querySelectorAll('[data-view]').forEach(item => item.classList.toggle('active', item ===
                        button));
                    drawCalendar();
                });
                drawCalendar();
            </script>
        @endpush
    @else
        <div class="pagetitle">
            <h1>Pengajuan Ruang Jambi</h1>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-body pt-4">
                    <form method="POST" action="{{ route('general.booking.store', $type) }}">@csrf<div class="mb-3">
                            <label class="form-label">Judul kegiatan</label><input name="title" class="form-control"
                                required>
                        </div>
                        <div class="mb-3"><label class="form-label">Ruangan</label><select name="room_id"
                                class="form-select" required>
                                <option value="">Pilih ruangan</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}">{{ $room->ruang }}</option>
                                @endforeach
                            </select></div>
                        <div class="row">
                            <div class="col"><input type="datetime-local" name="starts_at" class="form-control"
                                    required></div>
                            <div class="col"><input type="datetime-local" name="ends_at" class="form-control"
                                    required></div>
                        </div>
                        <div class="my-3">
                            <textarea name="purpose" class="form-control" required></textarea>
                        </div><button class="btn btn-primary">Kirim Pengajuan</button>
                    </form>
                </div>
            </div>
        </section>
    @endif
@endsection
