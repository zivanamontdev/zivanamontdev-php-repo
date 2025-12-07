<?php 
$pageTitle = 'Dashboard';
$currentPage = 'dashboard';
$user = auth_user();
ob_start(); 
?>

<!-- Admin Navbar Component -->
<?php component('admin-navbar', ['title' => 'Dashboard']); ?>

<!-- Search Input and Filter (below Dashboard title) -->
<div class="mb-[20px] flex items-center justify-between">
    <!-- Search Input -->
    <div class="relative w-[264px]">
        <span class="absolute left-[12px] top-1/2 -translate-y-1/2 w-4 h-4 text-white-soft pointer-events-none">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.33333 12.6667C10.2789 12.6667 12.6667 10.2789 12.6667 7.33333C12.6667 4.38781 10.2789 2 7.33333 2C4.38781 2 2 4.38781 2 7.33333C2 10.2789 4.38781 12.6667 7.33333 12.6667Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M14 14L11.1 11.1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
        <input 
            type="text" 
            name="search"
            placeholder="Cari Pendaftar"
            class="w-full pl-[36px] pr-[12px] py-[10px] bg-white-neutral border border-[#E0E0E0] rounded-[12px] text-[12px] text-black-soft placeholder:text-white-soft focus:outline-none focus:border-primary transition-colors"
        >
    </div>
    
    <!-- Period Filter Dropdown -->
    <div id="period-filter" class="relative inline-block" data-dropdown>
        <input type="hidden" name="period" value="month" data-dropdown-input>
        
        <!-- Dropdown Button -->
        <button type="button" 
                class="flex items-center gap-1 px-[10px] py-2 bg-white-neutral border border-[#E0E0E0] rounded-[8px] cursor-pointer hover:border-primary transition-colors"
                data-dropdown-trigger>
            <span class="text-[12px] font-normal text-black-highlight whitespace-nowrap" data-dropdown-label>Bulan ini</span>
            <svg class="w-3 h-3 text-black-highlight transition-transform" data-dropdown-icon width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        
        <!-- Dropdown Menu -->
        <div class="absolute top-full left-0 mt-1 bg-white-neutral border border-[#E0E0E0] rounded-[8px] shadow-lg z-50 hidden min-w-full"
             data-dropdown-menu>
            <button type="button" class="w-full px-3 py-2 text-left text-[12px] text-black-highlight hover:bg-white-secondary transition-colors first:rounded-t-[8px] last:rounded-b-[8px]" data-dropdown-option="year" data-dropdown-option-label="Tahun ini">Tahun ini</button>
            <button type="button" class="w-full px-3 py-2 text-left text-[12px] text-black-highlight hover:bg-white-secondary transition-colors first:rounded-t-[8px] last:rounded-b-[8px] hidden" data-dropdown-option="month" data-dropdown-option-label="Bulan ini">Bulan ini</button>
            <button type="button" class="w-full px-3 py-2 text-left text-[12px] text-black-highlight hover:bg-white-secondary transition-colors first:rounded-t-[8px] last:rounded-b-[8px]" data-dropdown-option="week" data-dropdown-option-label="Pekan ini">Pekan ini</button>
            <button type="button" class="w-full px-3 py-2 text-left text-[12px] text-black-highlight hover:bg-white-secondary transition-colors first:rounded-t-[8px] last:rounded-b-[8px]" data-dropdown-option="day" data-dropdown-option-label="Hari ini">Hari ini</button>
        </div>
    </div>
    
    <script>
    (function() {
        const dropdown = document.getElementById('period-filter');
        if (!dropdown) return;
        
        const trigger = dropdown.querySelector('[data-dropdown-trigger]');
        const menu = dropdown.querySelector('[data-dropdown-menu]');
        const label = dropdown.querySelector('[data-dropdown-label]');
        const icon = dropdown.querySelector('[data-dropdown-icon]');
        const input = dropdown.querySelector('[data-dropdown-input]');
        const options = dropdown.querySelectorAll('[data-dropdown-option]');
        
        let isOpen = false;
        
        function toggleDropdown() {
            isOpen = !isOpen;
            menu.classList.toggle('hidden', !isOpen);
            icon.classList.toggle('rotate-180', isOpen);
        }
        
        function closeDropdown() {
            isOpen = false;
            menu.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
        
        function selectOption(value, optionLabel) {
            label.textContent = optionLabel;
            if (input) input.value = value;
            
            options.forEach(opt => {
                if (opt.dataset.dropdownOption === value) {
                    opt.classList.add('hidden');
                } else {
                    opt.classList.remove('hidden');
                }
            });
            
            closeDropdown();
        }
        
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            toggleDropdown();
        });
        
        options.forEach(option => {
            option.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                selectOption(option.dataset.dropdownOption, option.dataset.dropdownOptionLabel);
            });
        });
        
        document.addEventListener('click', (e) => {
            if (!dropdown.contains(e.target)) {
                closeDropdown();
            }
        });
    })();
    </script>
</div>

<!-- Stats Cards -->
<?php
// Dummy data untuk stats cards
$statsCards = [
    [
        'title' => 'Total Pengunjung',
        'value' => 63,
        'change' => 5,
        'label' => 'dari bulan lalu'
    ],
    [
        'title' => 'Pengunjung Unik',
        'value' => 42,
        'change' => 12,
        'label' => 'dari bulan lalu'
    ],
    [
        'title' => 'Total Pendaftar',
        'value' => 12,
        'change' => 1,
        'label' => 'dari bulan lalu'
    ],
    [
        'title' => 'Total Pembaca Artikel',
        'value' => 4,
        'change' => -5,
        'label' => 'dari bulan lalu'
    ]
];
?>
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <?php foreach ($statsCards as $card): ?>
    <div class="h-[131px] bg-white-neutral border border-[#E1E1E1] rounded-[16px] px-[24px] py-[20px] flex flex-col items-start">
        <!-- Title -->
        <p class="text-[14px] font-normal leading-[21px] text-color-neutral mb-[20px]"><?= e($card['title']) ?></p>
        <!-- Value -->
        <p class="text-[24px] font-bold leading-[21px] text-secondary mb-[8px]"><?= number_format($card['value']) ?></p>
        <!-- Keterangan -->
        <p class="text-[12px] font-normal leading-[21px] text-white-soft">
            <span class="<?= $card['change'] >= 0 ? 'text-[#3EC441]' : 'text-[#C43E41]' ?>"><?= $card['change'] >= 0 ? '+' : '' ?><?= $card['change'] ?></span> <?= e($card['label']) ?>
        </p>
    </div>
    <?php endforeach; ?>
</div>

<!-- Halaman Terpopuler & Lokasi Section -->
<?php
// Dummy data untuk Halaman Terpopuler (sudah diurutkan dari tertinggi)
$popularPages = [
    ['page' => '/home', 'views' => 245],
    ['page' => '/articles', 'views' => 189],
    ['page' => '/registration', 'views' => 156],
    ['page' => '/profile', 'views' => 98],
    ['page' => '/activities', 'views' => 72],
];

// Dummy data untuk Lokasi (sudah diurutkan dari tertinggi)
$locationStats = [
    ['location' => 'Makassar', 'views' => 312],
    ['location' => 'Jakarta', 'views' => 187],
    ['location' => 'Surabaya', 'views' => 124],
    ['location' => 'Bandung', 'views' => 89],
    ['location' => 'Medan', 'views' => 56],
    ];
?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Halaman Terpopuler -->
    <div class="bg-white-neutral border border-border-soft rounded-[16px] px-[24px] py-[16px]">
        <div class="flex items-center justify-between mb-[20px]">
            <div>
                <h3 class="text-[16px] font-bold text-black-highlight mb-[8px]">Halaman Terpopuler</h3>
                <p class="text-[14px] font-normal leading-[21px] text-white-soft">Halaman dengan pengunjung terbanyak</p>
            </div>
            <!-- File Outline Icon -->
            <svg class="w-5 h-5 text-white-soft flex-shrink-0" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.667 1.667H5.00033C4.55829 1.667 4.1344 1.84259 3.82184 2.15515C3.50928 2.46771 3.33366 2.8916 3.33366 3.33366V16.667C3.33366 17.109 3.50928 17.5329 3.82184 17.8455C4.1344 18.158 4.55829 18.3337 5.00033 18.3337H15.0003C15.4424 18.3337 15.8663 18.158 16.1788 17.8455C16.4914 17.5329 16.667 17.109 16.667 16.667V6.667L11.667 1.667Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M11.667 1.667V6.667H16.667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M13.333 10.833H6.66699" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M13.333 14.167H6.66699" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8.33366 7.5H7.50033H6.66699" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <!-- Page List -->
        <div class="flex flex-col gap-[20px]">
            <?php 
            $maxViews = !empty($popularPages) ? max(array_column($popularPages, 'views')) : 0;
            foreach ($popularPages as $page): 
                $isHighest = $page['views'] === $maxViews;
            ?>
            <div class="flex items-center justify-between">
                <span class="text-[12px] font-bold leading-[21px] text-black-neutral"><?= e($page['page']) ?></span>
                <span class="text-[12px] leading-[21px] <?= $isHighest ? 'font-bold text-secondary' : 'font-normal text-black-highlight' ?>"><?= number_format($page['views']) ?> views</span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Lokasi -->
    <div class="bg-white-neutral border border-border-soft rounded-[16px] px-[24px] py-[16px]">
        <div class="flex items-center justify-between mb-[20px]">
            <div>
                <h3 class="text-[16px] font-bold text-black-highlight mb-[8px]">Lokasi</h3>
                <p class="text-[14px] font-normal leading-[21px] text-white-soft">Pengunjung dari kota terbanyak yang mengakses website</p>
            </div>
            <!-- Maps Outline Icon -->
            <svg class="w-5 h-5 text-white-soft flex-shrink-0" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.5 8.33333C17.5 14.1667 10 19.1667 10 19.1667C10 19.1667 2.5 14.1667 2.5 8.33333C2.5 6.34421 3.29018 4.43655 4.6967 3.03003C6.10322 1.6235 8.01088 0.833328 10 0.833328C11.9891 0.833328 13.8968 1.6235 15.3033 3.03003C16.7098 4.43655 17.5 6.34421 17.5 8.33333Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10 10.8333C11.3807 10.8333 12.5 9.71404 12.5 8.33333C12.5 6.95262 11.3807 5.83333 10 5.83333C8.61929 5.83333 7.5 6.95262 7.5 8.33333C7.5 9.71404 8.61929 10.8333 10 10.8333Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <!-- Location List -->
        <div class="flex flex-col gap-[20px]">
            <?php 
            $maxLocationViews = !empty($locationStats) ? max(array_column($locationStats, 'views')) : 0;
            foreach ($locationStats as $loc): 
                $isHighest = $loc['views'] === $maxLocationViews;
            ?>
            <div class="flex items-center justify-between">
                <span class="text-[12px] font-bold leading-[21px] text-black-neutral"><?= e($loc['location']) ?></span>
                <span class="text-[12px] leading-[21px] <?= $isHighest ? 'font-bold text-secondary' : 'font-normal text-black-highlight' ?>"><?= number_format($loc['views']) ?> views</span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Pengunjung Per Rentang Jam Section -->
<?php
// Dummy data untuk chart - 12 rentang jam
$hourlyData = [
    ['hour' => '00-02', 'views' => 12],
    ['hour' => '02-04', 'views' => 8],
    ['hour' => '04-06', 'views' => 5],
    ['hour' => '06-08', 'views' => 24],
    ['hour' => '08-10', 'views' => 56],
    ['hour' => '10-12', 'views' => 72],
    ['hour' => '12-14', 'views' => 64],
    ['hour' => '14-16', 'views' => 48],
    ['hour' => '16-18', 'views' => 52],
    ['hour' => '18-20', 'views' => 40],
    ['hour' => '20-22', 'views' => 28],
    ['hour' => '22-24', 'views' => 16],
];

// Y-axis values (dari atas ke bawah)
$yAxisValues = [80, 64, 48, 32, 16, 0];
?>
<div class="w-full bg-white-neutral border border-border-soft rounded-[16px] px-[20px] py-[16px] overflow-hidden">
    <div class="flex items-center justify-between mb-[20px]">
        <div>
            <h3 class="text-[16px] font-bold text-black-highlight mb-[8px]">Pengunjung Per Rentang Jam</h3>
            <p class="text-[14px] font-normal leading-[21px] text-white-soft">Halaman dengan pengunjung terbanyak</p>
        </div>
        <!-- Clock Outline Icon -->
        <svg class="w-5 h-5 text-white-soft flex-shrink-0" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 18.3333C14.6024 18.3333 18.3334 14.6024 18.3334 10C18.3334 5.39763 14.6024 1.66667 10 1.66667C5.39765 1.66667 1.66669 5.39763 1.66669 10C1.66669 14.6024 5.39765 18.3333 10 18.3333Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M10 5V10L13.3333 11.6667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
    
    <!-- Chart Container -->
    <?php
    $maxHourlyViews = !empty($hourlyData) ? max(array_column($hourlyData, 'views')) : 0;
    $chartHeight = 156; // Total height for chart area in px (based on Y-axis range)
    ?>
    <div class="flex items-end">
        <!-- Y-Axis Labels -->
        <div class="flex flex-col items-center mr-[24px] gap-[6px] pb-[32px]">
            <?php foreach ($yAxisValues as $value): ?>
            <span class="text-[11px] font-normal leading-[24px] text-black-highlight"><?= $value ?></span>
            <?php endforeach; ?>
        </div>
        
        <!-- Chart Area -->
        <div class="flex-1 flex items-end justify-between px-[9px]">
            <?php foreach ($hourlyData as $index => $data): 
                $isHighest = $data['views'] === $maxHourlyViews;
                $barHeight = ($data['views'] / 80) * $chartHeight; // Calculate bar height based on max Y value (80)
                $barHeight = max($barHeight, 4); // Minimum height
            ?>
            <div class="flex-1 flex flex-col items-center">
                <!-- Bar with optional value label -->
                <div class="flex flex-col items-center">
                    <?php if ($isHighest): ?>
                    <span class="text-[11px] font-normal leading-[16px] text-secondary mb-[20px]"><?= $data['views'] ?></span>
                    <?php endif; ?>
                    <div class="w-[16px] rounded-full <?= $isHighest ? 'bg-secondary' : 'bg-black-neutral' ?>" style="height: <?= $barHeight ?>px;"></div>
                </div>
                <!-- X-Axis Label -->
                <span class="text-[11px] font-normal leading-[24px] text-black-highlight mt-[8px]"><?= $data['hour'] ?></span>
            </div>
            <?php endforeach; ?>
    </div>
</div>
</div>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>
