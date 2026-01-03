<?php
/**
 * Modal Add Event Component
 * Modal for adding new event
 */
?>

<!-- Custom Styles for Date/Time Inputs -->
<style>
/* Hide native time picker icons */
#start-time-add::-webkit-calendar-picker-indicator,
#end-time-add::-webkit-calendar-picker-indicator,
#date-input-add::-webkit-calendar-picker-indicator {
    display: none;
    -webkit-appearance: none;
    appearance: none;
}

/* Remove default styling */
#start-time-add,
#end-time-add {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}
</style>

<!-- Modal Backdrop -->
<div id="modal-add-event" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <!-- Modal Container -->
    <div class="bg-white-neutral border border-border-soft rounded-[16px] w-[621px] px-[24px] py-[20px]">
        <!-- Header: Title and Close Button -->
        <div class="flex items-start justify-between mb-[32px]">
            <h3 class="font-bold text-[20px] leading-[100%] text-black-soft">Tambah Event</h3>
            <button type="button" id="close-modal-add-event" class="text-black-highlight hover:text-black-soft transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Form Fields -->
        <form id="form-add-event" class="space-y-[24px]">
            <!-- Tanggal and Rentang Waktu -->
            <div class="grid grid-cols-2 gap-[20px]">
                <!-- Tanggal -->
                <div class="relative">
                    <label class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                        Tanggal
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="event-date-display-add" 
                            readonly
                            placeholder="Pilih tanggal event"
                            class="w-full h-[52px] px-[24px] pr-[48px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors cursor-pointer"
                        >
                        <input type="hidden" id="event-date-add" name="event_date">
                        <span id="date-icon-add" class="absolute right-[16px] top-1/2 -translate-y-1/2 w-4 h-4 text-white-soft cursor-pointer">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.6667 2.66667H3.33333C2.59695 2.66667 2 3.26362 2 4V13.3333C2 14.0697 2.59695 14.6667 3.33333 14.6667H12.6667C13.403 14.6667 14 14.0697 14 13.3333V4C14 3.26362 13.403 2.66667 12.6667 2.66667Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10.6667 1.33333V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M5.33333 1.33333V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2 6.66667H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </div>
                    
                    <!-- Date Picker Modal -->
                    <div id="date-picker-modal-add" class="hidden absolute z-[60] mt-1 bg-white-neutral border border-border-light rounded-xl shadow-lg w-full left-0" style="min-width: 280px;">
                        <!-- Calendar Header -->
                        <div class="flex items-center justify-between p-3 border-b border-border-light">
                            <button type="button" id="prev-month-add" class="p-1 hover:bg-gray-100 rounded">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                            <div class="font-semibold text-[14px] cursor-pointer hover:text-primary" id="calendar-month-year-add"></div>
                            <button type="button" id="next-month-add" class="p-1 hover:bg-gray-100 rounded">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                        
                        <!-- Year Picker Modal -->
                        <div id="year-picker-add" class="hidden absolute inset-0 bg-white-neutral rounded-xl z-10">
                            <div class="flex items-center justify-between p-3 border-b border-border-light">
                                <button type="button" id="prev-year-range-add" class="p-1 hover:bg-gray-100 rounded">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                </button>
                                <div class="font-semibold text-[14px]" id="year-range-display-add"></div>
                                <button type="button" id="next-year-range-add" class="p-1 hover:bg-gray-100 rounded">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </button>
                            </div>
                            <div class="p-3 grid grid-cols-3 gap-2 max-h-64 overflow-y-auto" id="year-list-add"></div>
                        </div>
                        
                        <!-- Calendar Grid -->
                        <div class="p-3">
                            <!-- Day Names -->
                            <div class="grid grid-cols-7 gap-1 mb-2">
                                <div class="text-center text-[11px] font-semibold text-gray-500">Sen</div>
                                <div class="text-center text-[11px] font-semibold text-gray-500">Sel</div>
                                <div class="text-center text-[11px] font-semibold text-gray-500">Rab</div>
                                <div class="text-center text-[11px] font-semibold text-gray-500">Kam</div>
                                <div class="text-center text-[11px] font-semibold text-gray-500">Jum</div>
                                <div class="text-center text-[11px] font-semibold text-gray-500">Sab</div>
                                <div class="text-center text-[11px] font-semibold text-red-500">Min</div>
                            </div>
                            <!-- Dates Grid -->
                            <div class="grid grid-cols-7 gap-1" id="calendar-dates-add"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Rentang Waktu -->
                <div class="relative">
                    <label class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                        Rentang Waktu
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="event-time-display-add" 
                            readonly
                            placeholder="Pilih rentang waktu event"
                            class="w-full h-[52px] px-[24px] pr-[48px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors cursor-pointer"
                        >
                        <input type="hidden" id="event-time-add" name="event_time">
                        <span id="time-icon-add" class="absolute right-[16px] top-1/2 -translate-y-1/2 w-4 h-4 text-white-soft cursor-pointer">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 14.6667C11.6819 14.6667 14.6667 11.6819 14.6667 8C14.6667 4.3181 11.6819 1.33333 8 1.33333C4.3181 1.33333 1.33333 4.3181 1.33333 8C1.33333 11.6819 4.3181 14.6667 8 14.6667Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8 4V8L10.6667 9.33333" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </div>
                    
                    <!-- Time Picker Modal -->
                    <div id="time-picker-modal-add" class="hidden absolute z-[60] mt-1 bg-white-neutral border border-border-light rounded-xl shadow-lg p-4 w-full left-0">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-[12px] text-black-highlight mb-1">Waktu Mulai</label>
                                <input type="time" id="start-time-add" class="w-full px-3 py-2 border border-border-light rounded-lg text-[14px] focus:outline-none focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-[12px] text-black-highlight mb-1">Waktu Selesai</label>
                                <input type="time" id="end-time-add" class="w-full px-3 py-2 border border-border-light rounded-lg text-[14px] focus:outline-none focus:border-primary">
                            </div>
                            <button type="button" id="apply-time-add" class="w-full py-2 bg-primary text-white rounded-lg text-[14px] font-bold hover:opacity-90">Terapkan</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Nama Event -->
            <div>
                <label for="event-name-add" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Nama Event
                </label>
                <input 
                    type="text" 
                    id="event-name-add" 
                    name="event_name"
                    placeholder="Isi nama event"
                    class="w-full h-[52px] px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                >
            </div>
            
            <!-- Tempat Event -->
            <div>
                <label for="event-place-add" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Tempat Event
                </label>
                <input 
                    type="text" 
                    id="event-place-add" 
                    name="event_place"
                    placeholder="Isi tempat event"
                    class="w-full h-[52px] px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                >
            </div>
            
            <!-- URL Informasi Event -->
            <div>
                <label for="event-url-add" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    URL Informasi Event
                </label>
                <input 
                    type="url" 
                    id="event-url-add" 
                    name="event_url"
                    placeholder="Isi URL informasi event"
                    class="w-full h-[52px] px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                >
            </div>
            
            <!-- Event Terbuka untuk Umum -->
            <div>
                <label class="flex items-center cursor-pointer">
                    <input 
                        type="checkbox" 
                        id="is-public-add" 
                        name="is_public"
                        class="w-3 h-3 border-gray-300 rounded"
                        style="margin-right: 8px; accent-color: <?= colors('primary') ?>;"
                    >
                    <span class="font-normal text-[14px] leading-[21px] text-black-soft">
                        Event terbuka untuk umum
                    </span>
                </label>
            </div>
            
            <!-- Action Button -->
            <div class="pt-[8px] flex justify-center">
                <!-- Submit Button -->
                <div id="submit-button-wrapper-add-event">
                    <?php component('button', [
                        'text' => 'Tambah Event',
                        'variant' => '10',
                        'type' => 'submit',
                        'id' => 'submit-add-event-btn'
                    ]); ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-add-event');
    const closeBtn = document.getElementById('close-modal-add-event');
    const openBtn = document.getElementById('btn-add-event');
    
    // Date picker elements
    const datePicker = document.getElementById('date-picker-modal-add');
    const dateDisplay = document.getElementById('event-date-display-add');
    const dateIcon = document.getElementById('date-icon-add');
    const applyDateBtn = document.getElementById('apply-date-add');
    
    // Time picker elements
    const timePicker = document.getElementById('time-picker-modal-add');
    const timeDisplay = document.getElementById('event-time-display-add');
    const timeIcon = document.getElementById('time-icon-add');
    const applyTimeBtn = document.getElementById('apply-time-add');
    
    // Open date picker
    function openDatePicker(e) {
        e.stopPropagation();
        
        // Parse current date value if exists
        const currentValue = document.getElementById('event-date-display-add').value;
        if (currentValue) {
            // Parse dd/mm/yyyy format
            const parts = currentValue.split('/');
            if (parts.length === 3) {
                const day = parseInt(parts[0], 10);
                const month = parseInt(parts[1], 10) - 1; // Month is 0-indexed
                const year = parseInt(parts[2], 10);
                selectedDate = new Date(year, month, day);
                currentMonth = month;
                currentYear = year;
            }
        } else {
            // Reset to current date if no value
            currentMonth = new Date().getMonth();
            currentYear = new Date().getFullYear();
        }
        
        renderCalendar(currentMonth, currentYear);
        datePicker.classList.remove('hidden');
        timePicker.classList.add('hidden'); // Close time picker if open
    }
    
    if (dateDisplay) {
        dateDisplay.addEventListener('click', openDatePicker);
    }
    
    if (dateIcon) {
        dateIcon.addEventListener('click', openDatePicker);
    }
    
    // Apply date
    if (applyDateBtn) {
        applyDateBtn.addEventListener('click', function() {
            const dateValue = document.getElementById('date-input-add').value;
            
            if (dateValue) {
                // Format date to dd/mm/yyyy for display
                const [year, month, day] = dateValue.split('-');
                const displayDate = `${day}/${month}/${year}`;
                
                document.getElementById('event-date-display-add').value = displayDate;
                document.getElementById('event-date-add').value = dateValue;
                datePicker.classList.add('hidden');
                
                // Trigger validation
                const event = new Event('input', { bubbles: true });
                document.getElementById('event-date-add').dispatchEvent(event);
            }
        });
    }
    
    // Calendar functionality
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    let selectedDate = null; // Track selected date
    let yearRangeStart = currentYear - 5; // Start year for year picker
    
    function renderYearPicker() {
        const yearList = document.getElementById('year-list-add');
        const yearRangeDisplay = document.getElementById('year-range-display-add');
        yearList.innerHTML = '';
        yearRangeDisplay.textContent = `${yearRangeStart} - ${yearRangeStart + 11}`;
        
        for (let i = 0; i < 12; i++) {
            const year = yearRangeStart + i;
            const yearBtn = document.createElement('button');
            yearBtn.type = 'button';
            yearBtn.className = 'py-2 px-4 text-[13px] rounded hover:bg-primary hover:text-white transition-colors';
            yearBtn.textContent = year;
            
            // Highlight current year
            if (year === new Date().getFullYear()) {
                yearBtn.className += ' font-bold text-primary';
            }
            
            // Highlight selected year
            if (year === currentYear) {
                yearBtn.className += ' bg-primary text-white font-bold';
            }
            
            yearBtn.addEventListener('click', function() {
                currentYear = year;
                document.getElementById('year-picker-add').classList.add('hidden');
                renderCalendar(currentMonth, currentYear);
            });
            
            yearList.appendChild(yearBtn);
        }
    }
    
    function renderCalendar(month, year) {
        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mai', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        document.getElementById('calendar-month-year-add').textContent = `${monthNames[month]} ${year}`;
        
        const datesContainer = document.getElementById('calendar-dates-add');
        datesContainer.innerHTML = '';
        
        // Get first day of month (0 = Sunday, 1 = Monday, etc.)
        const firstDay = new Date(year, month, 1).getDay();
        // Get number of days in month
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        
        // Adjust for Monday start (0 = Monday, 6 = Sunday)
        const adjustedFirstDay = firstDay === 0 ? 6 : firstDay - 1;
        
        // Add empty cells for days before month starts
        for (let i = 0; i < adjustedFirstDay; i++) {
            const emptyCell = document.createElement('div');
            emptyCell.className = 'text-center py-2';
            datesContainer.appendChild(emptyCell);
        }
        
        // Add date cells
        const today = new Date();
        for (let day = 1; day <= daysInMonth; day++) {
            const dateCell = document.createElement('button');
            dateCell.type = 'button';
            dateCell.className = 'text-center py-2 text-[13px] rounded hover:bg-primary hover:text-white transition-colors';
            dateCell.textContent = day;
            
            // Check if this date is Sunday
            const currentDate = new Date(year, month, day);
            const isSunday = currentDate.getDay() === 0;
            
            // Highlight selected date
            if (selectedDate && day === selectedDate.getDate() && month === selectedDate.getMonth() && year === selectedDate.getFullYear()) {
                dateCell.className += ' bg-primary text-white font-bold';
            }
            // Highlight today (if not selected)
            else if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                dateCell.className += ' font-bold text-primary';
            }
            // Color Sunday red (if not selected or today)
            else if (isSunday) {
                dateCell.className += ' text-red-500';
            }
            
            // Click handler
            dateCell.addEventListener('click', function() {
                selectedDate = new Date(year, month, day);
                const yyyy = selectedDate.getFullYear();
                const mm = String(selectedDate.getMonth() + 1).padStart(2, '0');
                const dd = String(selectedDate.getDate()).padStart(2, '0');
                
                const dateValue = `${yyyy}-${mm}-${dd}`;
                const displayDate = `${dd}/${mm}/${yyyy}`;
                
                document.getElementById('event-date-display-add').value = displayDate;
                document.getElementById('event-date-add').value = dateValue;
                datePicker.classList.add('hidden');
                
                // Trigger validation
                const event = new Event('input', { bubbles: true });
                document.getElementById('event-date-add').dispatchEvent(event);
            });
            
            datesContainer.appendChild(dateCell);
        }
    }
    
    // Previous month button
    const prevMonthBtn = document.getElementById('prev-month-add');
    if (prevMonthBtn) {
        prevMonthBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar(currentMonth, currentYear);
        });
    }
    
    // Next month button
    const nextMonthBtn = document.getElementById('next-month-add');
    if (nextMonthBtn) {
        nextMonthBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar(currentMonth, currentYear);
        });
    }
    
    // Click month-year to open year picker
    const monthYearDisplay = document.getElementById('calendar-month-year-add');
    if (monthYearDisplay) {
        monthYearDisplay.addEventListener('click', function(e) {
            e.stopPropagation();
            yearRangeStart = currentYear - 5;
            renderYearPicker();
            document.getElementById('year-picker-add').classList.remove('hidden');
        });
    }
    
    // Previous year range button
    const prevYearRangeBtn = document.getElementById('prev-year-range-add');
    if (prevYearRangeBtn) {
        prevYearRangeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            yearRangeStart -= 12;
            renderYearPicker();
        });
    }
    
    // Next year range button
    const nextYearRangeBtn = document.getElementById('next-year-range-add');
    if (nextYearRangeBtn) {
        nextYearRangeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            yearRangeStart += 12;
            renderYearPicker();
        });
    }
    
    // Initialize calendar on first open
    let calendarInitialized = false;
    if (dateDisplay) {
        const originalOpenDatePicker = openDatePicker;
        window.openDatePicker = function(e) {
            originalOpenDatePicker(e);
            if (!calendarInitialized) {
                renderCalendar(currentMonth, currentYear);
                calendarInitialized = true;
            }
        };
    }
    
    // Prevent date picker from closing when clicking inside it
    if (datePicker) {
        datePicker.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // Open time picker
    function openTimePicker(e) {
        e.stopPropagation();
        timePicker.classList.remove('hidden');
        datePicker.classList.add('hidden'); // Close date picker if open
    }
    
    if (timeDisplay) {
        timeDisplay.addEventListener('click', openTimePicker);
    }
    
    if (timeIcon) {
        timeIcon.addEventListener('click', openTimePicker);
    }
    
    // Apply time range
    if (applyTimeBtn) {
        applyTimeBtn.addEventListener('click', function() {
            const startTime = document.getElementById('start-time-add').value;
            const endTime = document.getElementById('end-time-add').value;
            
            if (startTime && endTime) {
                const display = `${startTime} - ${endTime}`;
                document.getElementById('event-time-display-add').value = display;
                document.getElementById('event-time-add').value = display;
                timePicker.classList.add('hidden');
                
                // Trigger validation
                const event = new Event('input', { bubbles: true });
                document.getElementById('event-time-add').dispatchEvent(event);
            }
        });
    }
    
    // Close pickers when clicking outside
    document.addEventListener('click', function(e) {
        // Close date picker
        if (datePicker && !datePicker.contains(e.target) && e.target !== dateDisplay && e.target !== dateIcon && !dateIcon.contains(e.target)) {
            datePicker.classList.add('hidden');
        }
        
        // Close time picker
        if (timePicker && !timePicker.contains(e.target) && e.target !== timeDisplay && e.target !== timeIcon && !timeIcon.contains(e.target)) {
            timePicker.classList.add('hidden');
        }
    });
    
    // Prevent time picker from closing when clicking inside it
    if (timePicker) {
        timePicker.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // Open modal
    if (openBtn) {
        openBtn.addEventListener('click', function() {
            modal.classList.remove('hidden');
        });
    }
    
    // Close modal
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
            resetForm();
        });
    }
    
    // Close modal on backdrop click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            resetForm();
        }
    });
    
    // Form validation
    function validateForm() {
        const eventDate = document.getElementById('event-date-add').value.trim();
        const eventTime = document.getElementById('event-time-add').value.trim();
        const eventName = document.getElementById('event-name-add').value.trim();
        const eventPlace = document.getElementById('event-place-add').value.trim();
        const eventUrl = document.getElementById('event-url-add').value.trim();
        
        const allFilled = eventDate && eventTime && eventName && eventPlace && eventUrl;
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-add-event');
        
        if (allFilled) {
            // Change to variant 1 (primary)
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-add-event-btn" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-6 rounded-xl bg-primary text-white font-bold text-base h-[52px] hover:opacity-90 active:scale-95 cursor-pointer">
                    Tambah Event
                </button>
            `;
        } else {
            // Change to variant 10 (disabled)
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-add-event-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Tambah Event
                </button>
            `;
        }
    }
    
    // Add input listeners for form validation
    const formInputs = ['event-date-add', 'event-time-add', 'event-name-add', 'event-place-add', 'event-url-add'];
    formInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', validateForm);
        }
    });
    
    // Form submit
    const form = document.getElementById('form-add-event');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Prepare form data
            const formData = new FormData();
            formData.append('event_date', document.getElementById('event-date-add').value);
            formData.append('event_time', document.getElementById('event-time-add').value);
            formData.append('event_name', document.getElementById('event-name-add').value);
            formData.append('event_place', document.getElementById('event-place-add').value);
            formData.append('event_url', document.getElementById('event-url-add').value);
            
            // Add is_public checkbox value
            const isPublicCheckbox = document.getElementById('is-public-add');
            if (isPublicCheckbox && isPublicCheckbox.checked) {
                formData.append('is_public', '1');
            }
            
            // Send AJAX request
            fetch('/admin/settings/events/create', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modal.classList.add('hidden');
                    resetForm();
                    showToast('Event berhasil ditambahkan!', 'success', 3000);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(data.message || 'Terjadi kesalahan saat menyimpan data', 'error', 5000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat menyimpan data', 'error', 5000);
            });
        });
    }
    
    // Reset form
    function resetForm() {
        if (form) form.reset();
        
        // Reset date picker display
        document.getElementById('event-date-display-add').value = '';
        document.getElementById('date-picker-modal-add').classList.add('hidden');
        
        // Reset time picker display
        document.getElementById('event-time-display-add').value = '';
        document.getElementById('start-time-add').value = '';
        document.getElementById('end-time-add').value = '';
        document.getElementById('time-picker-modal-add').classList.add('hidden');
        
        // Reset submit button to variant 10
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-add-event');
        if (submitButtonWrapper) {
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-add-event-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Tambah Event
                </button>
            `;
        }
    }
});
</script>
