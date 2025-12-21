<?php
/**
 * Event Tab Component
 * Displays event cards for settings page
 */

// Get events from database
$eventModel = new Event();
$eventsFromDb = $eventModel->getAllEvents();

// Format events for display
$events = [];
foreach ($eventsFromDb as $event) {
    // Convert date format from Y-m-d to d/m/Y
    $dateObj = DateTime::createFromFormat('Y-m-d', $event['event_date']);
    $formattedDate = $dateObj ? $dateObj->format('d/m/Y') : $event['event_date'];
    
    // Format time (remove seconds if present)
    $startTime = substr($event['start_time'], 0, 5);
    $endTime = substr($event['end_time'], 0, 5);
    
    $events[] = [
        'id' => $event['id'],
        'date' => $formattedDate,
        'date_raw' => $event['event_date'], // for edit modal
        'start_time' => $startTime,
        'end_time' => $endTime,
        'name' => $event['name'],
        'place' => $event['place'],
        'link' => $event['url'],
        'is_public' => $event['is_public'] ?? 0
    ];
}

// Build event cards HTML for slot
ob_start();
?>
<div class="mt-5">
    <?php if (empty($events)): ?>
        <div class="text-center py-8 text-white-soft">
            <p>Belum ada event yang ditambahkan</p>
        </div>
    <?php else: ?>
        <?php foreach ($events as $index => $event): ?>
            <div class="rounded-xl border border-[<?= colors('border_gray') ?>] bg-[<?= colors('bg_light_gray') ?>] py-2 px-3 flex items-center justify-between <?= $index > 0 ? 'mt-3' : '' ?>">
                <!-- Event Info -->
                <div class="flex-1">
                    <!-- Date and Time -->
                    <div class="mb-4">
                        <span class="font-bold text-[12px] text-[<?= colors('text_dark') ?>]"><?= e($event['date']) ?></span>
                        <span class="font-normal text-[12px] text-[<?= colors('black_highlight') ?>]"> • <?= e($event['start_time']) ?> - <?= e($event['end_time']) ?></span>
                    </div>
                    
                    <!-- Event Name -->
                    <div class="font-bold text-[12px] text-[<?= colors('text_dark') ?>] mb-[2px]">
                        <?= e($event['name']) ?>
                    </div>
                    
                    <!-- Place and Link -->
                    <div>
                        <span class="font-bold text-[12px] text-[<?= colors('text_dark') ?>]"><?= e($event['place']) ?></span>
                        <span class="font-normal text-[12px] text-[<?= colors('black_highlight') ?>]"> • <?= e($event['link']) ?></span>
                        <?php if (!empty($event['is_public'])): ?>
                        <span class="font-normal text-[12px] text-[<?= colors('black_highlight') ?>]"> • Terbuka untuk umum</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Edit Button -->
                <div class="ml-3">
                    <button type="button" 
                        onclick="openEditEventModal({
                            id: <?= $event['id'] ?>,
                            date: '<?= e($event['date_raw']) ?>',
                            time: '<?= e($event['start_time']) ?> - <?= e($event['end_time']) ?>',
                            name: '<?= e($event['name']) ?>',
                            place: '<?= e($event['place']) ?>',
                            url: '<?= e($event['link']) ?>',
                            is_public: <?= !empty($event['is_public']) ? 'true' : 'false' ?>
                        })"
                        class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 p-[10px] rounded-xl bg-white-secondary text-black-highlight hover:opacity-80 active:scale-95 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php
$eventCardsHtml = ob_get_clean();

// Render tab content card with events in slot
component('tab-content-card', [
    'title' => 'Kalender Events',
    'description' => 'Daftar event yang akan datang ditampilkan di halaman utama website',
    'buttonText' => 'Tambah Event',
    'buttonId' => 'btn-add-event',
    'buttonIcon' => 'plus',
    'slot' => $eventCardsHtml
]);

// Include delete confirmation modal
component('widget/modal-delete-confirmation', [
    'modalId' => 'modal-delete-event',
    'title' => 'Hapus Event',
    'description' => 'Apakah Anda yakin ingin menghapus event ini? Tindakan ini tidak dapat dibatalkan.'
]);

// Include modals
component('widget/setting/modal/modal-add-event');
component('widget/setting/modal/modal-edit-event');
