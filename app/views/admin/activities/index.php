<?php
$pageTitle = 'Aktivitas Sekolah';
$currentPage = 'activities';
ob_start();

// $kelasData is now passed from controller
?>

<!-- Admin Navbar Component -->
<?php component('admin-navbar', ['title' => 'Aktivitas Sekolah']); ?>

<!-- Tabs -->
<?php component('tabs', [
    'tabs' => [
        'Informasi Kelas',
        'Program Tahun Ajaran',
        'Program Harian'
    ],
    'active' => 0
]); ?>

<!-- Tab Content Panels -->
<div class="mt-4">
    <!-- Tab 1: Informasi Kelas -->
    <div id="tab-panel-0" class="tab-panel">
        <div class="w-full bg-white-neutral border border-white-neutral rounded-[16px] px-[24px] py-[16px]">
            <!-- Header: Title, Description and Button -->
            <div class="flex items-center justify-between">
                <!-- Title and Description -->
                <div>
                    <h3 class="font-bold text-[16px] leading-[21px] text-black-soft mb-[8px]">Informasi Kelas</h3>
                    <p class="font-normal text-[14px] leading-[21px] text-white-soft">Data kelas pada sekolah</p>
                </div>
                
                <!-- Add Button -->
                <div class="flex-shrink-0">
                    <?php component('button', [
                        'text' => 'Tambah Kelas',
                        'variant' => '8',
                        'type' => 'button',
                        'id' => 'btn-add-class'
                    ]); ?>
                </div>
            </div>
            
            <!-- Class List -->
            <div class="mt-4 space-y-3">
                <?php foreach ($kelasData as $index => $kelas): ?>
                <div class="flex items-center py-[12px]">
                    <!-- Image -->
                    <div class="w-[62px] h-[62px] rounded-lg bg-gray-placeholder mr-[12px] flex-shrink-0 relative overflow-hidden">
                        <img 
                            src="<?= e($kelas['image']) ?>" 
                            alt="<?= e($kelas['title']) ?>" 
                            class="w-full h-full object-cover"
                            onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                        >
                        <svg class="hidden absolute inset-0 w-8 h-8 m-auto text-white-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    
                    <!-- Title and Info -->
                    <div class="flex-1 flex flex-col justify-center">
                        <h4 class="font-bold text-[12px] leading-[21px] text-text-dark"><?= e($kelas['title']) ?></h4>
                        <p class="font-normal text-[12px] leading-[21px] text-black-highlight">
                            <?= e($kelas['age']) ?> • <?= e($kelas['duration']) ?> • <?= e($kelas['students']) ?>
                        </p>
                    </div>
                    
                    <!-- Edit Button -->
                    <div class="flex-shrink-0">
                        <?php component('button', [
                            'variant' => '9',
                            'icon' => 'edit',
                            'type' => 'button',
                            'id' => 'btn-edit-class-' . $index,
                            'attrs' => [
                                'data-class-id' => $kelas['id'],
                                'data-class-name' => $kelas['title'],
                                'data-class-age' => $kelas['age'],
                                'data-class-duration' => $kelas['duration'],
                                'data-class-students' => $kelas['students'],
                                'data-class-image' => $kelas['image']
                            ]
                        ]); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <!-- Tab 2: Program Tahun Ajaran -->
    <div id="tab-panel-1" class="tab-panel hidden">
        <div class="w-full bg-white-neutral border border-white-neutral rounded-[16px] px-[24px] py-[16px]">
            <!-- Header: Title, Description and Button -->
            <div class="flex items-center justify-between">
                <!-- Title and Description -->
                <div>
                    <h3 class="font-bold text-[16px] leading-[21px] text-black-soft mb-[8px]">Program Tahun Ajaran</h3>
                    <p class="font-normal text-[14px] leading-[21px] text-white-soft">Daftar program tahun ajaran yang sedang berjalan</p>
                </div>
                
                <!-- Add Button -->
                <div class="flex-shrink-0">
                    <?php component('button', [
                        'text' => 'Tambah Program',
                        'variant' => '8',
                        'type' => 'button',
                        'id' => 'btn-add-program-tahun'
                    ]); ?>
                </div>
            </div>
            
            <!-- Program Tahun Ajaran Content -->
            <div class="mt-4 space-y-[24px]">
                <?php foreach ($programsTahunData as $index => $program): ?>
                <div>
                    <!-- Header with Title, Description and Edit Button -->
                    <div class="flex items-center mb-[12px]">
                        <div class="flex-1">
                            <h4 class="font-bold text-[12px] leading-[100%] text-text-dark"><?= e($program['name']) ?></h4>
                            <p class="font-normal text-[12px] leading-[20px] text-black-soft mt-1"><?= e($program['description']) ?></p>
                        </div>
                        <div class="flex-shrink-0">
                            <?php component('button', [
                                'variant' => '9',
                                'icon' => 'edit',
                                'type' => 'button',
                                'id' => 'btn-edit-program-tahun-' . $index,
                                'attrs' => [
                                    'data-program-id' => $program['id'],
                                    'data-program-name' => $program['name'],
                                    'data-program-description' => $program['description'],
                                    'data-program-image' => $program['cover_image'] ?? ''
                                ]
                            ]); ?>
                        </div>
                    </div>
                    
                    <!-- Galeri Program Label -->
                    <p class="font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">Galeri Program</p>
                    
                    <!-- Gallery Images with horizontal scroll -->
                    <div class="flex items-center gap-[12px] overflow-x-auto pb-2">
                        <!-- Add Button -->
                        <button 
                            class="btn-add-gallery w-[80px] h-[80px] rounded-xl bg-gray-placeholder flex items-center justify-center flex-shrink-0 hover:opacity-80 transition-opacity cursor-pointer"
                            data-program-id="<?= $program['id'] ?>"
                        >
                            <svg class="w-[16px] h-[16px] text-black-highlight" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </button>
                        
                        <!-- Sampul (Cover Image) - Always first position -->
                        <?php if (!empty($program['cover_image'])): ?>
                            <div class="relative w-[80px] h-[80px] flex-shrink-0">
                                <img 
                                    src="<?= e($program['cover_image']) ?>" 
                                    alt="Sampul <?= e($program['name']) ?>" 
                                    class="w-full h-full rounded-xl object-cover"
                                    onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-[80px] h-[80px] rounded-xl bg-gray-200 flex items-center justify-center\'><span class=\'text-xs text-gray-400\'>Error</span></div>';"
                                >
                                <div class="absolute top-1 right-1 bg-primary text-white text-[8px] font-bold px-1.5 py-0.5 rounded">
                                    SAMPUL
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Program Image (if exists and not used as cover) -->
                        <?php if (!empty($program['image']) && $program['cover_from_gallery']): ?>
                            <div class="relative w-[80px] h-[80px] flex-shrink-0 program-image-item cursor-pointer hover:opacity-80 transition-opacity"
                                 data-program-id="<?= $program['id'] ?>"
                                 data-program-image="<?= e($program['image']) ?>"
                                 data-description=""
                                 data-is-cover="0">
                                <img 
                                    src="<?= e($program['image']) ?>" 
                                    alt="<?= e($program['name']) ?>" 
                                    class="w-full h-full rounded-xl object-cover"
                                    onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-[80px] h-[80px] rounded-xl bg-gray-200 flex items-center justify-center\'><span class=\'text-xs text-gray-400\'>Error</span></div>';"
                                >
                            </div>
                        <?php endif; ?>
                        
                        <!-- Gallery Images (exclude the one marked as cover) -->
                        <?php if (!empty($program['gallery']) && is_array($program['gallery'])): ?>
                            <?php foreach ($program['gallery'] as $galleryImage): ?>
                                <?php if ($galleryImage['is_cover'] != 1): ?>
                                    <div class="relative w-[80px] h-[80px] flex-shrink-0 gallery-image-item cursor-pointer hover:opacity-80 transition-opacity"
                                         data-gallery-id="<?= $galleryImage['id'] ?>"
                                         data-program-id="<?= $program['id'] ?>"
                                         data-description="<?= e($galleryImage['description']) ?>"
                                         data-image-path="<?= e($galleryImage['image_path']) ?>"
                                         data-is-cover="<?= $galleryImage['is_cover'] ?>">
                                        <img 
                                            src="<?= e($galleryImage['image_path']) ?>" 
                                            alt="<?= e($galleryImage['description']) ?>" 
                                            class="w-full h-full rounded-xl object-cover"
                                            onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-[80px] h-[80px] rounded-xl bg-gray-200 flex items-center justify-center\'><span class=\'text-xs text-gray-400\'>Error</span></div>';"
                                        >
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <!-- Tab 3: Program Harian -->
    <div id="tab-panel-2" class="tab-panel hidden">
        <div class="w-full bg-white-neutral border border-white-neutral rounded-[16px] px-[24px] py-[16px]">
            <!-- Header: Title and Description (no button) -->
            <div class="flex items-center justify-between">
                <!-- Title and Description -->
                <div>
                    <h3 class="font-bold text-[16px] leading-[21px] text-black-soft mb-[8px]">Program Harian</h3>
                    <p class="font-normal text-[14px] leading-[21px] text-white-soft">Daftar program harian yang sedang berjalan</p>
                </div>
            </div>
            
            <!-- Program Harian Content - 5 Days (Senin - Jumat) -->
            <div class="mt-4 space-y-[24px]">
                <?php foreach ($programsHarianData as $programHarian): ?>
                <div>
                    <!-- Day Label -->
                    <p class="font-normal text-[12px] leading-[21px] text-black-highlight mb-[16px]"><?= e($programHarian['day_name']) ?></p>
                    
                    <!-- Header with Title, Description and Edit Button -->
                    <div class="flex items-center mb-[12px]">
                        <div class="flex-1">
                            <h4 class="font-bold text-[12px] leading-[100%] text-text-dark"><?= e($programHarian['program_name']) ?></h4>
                            <p class="font-normal text-[12px] leading-[20px] text-black-soft mt-1"><?= e($programHarian['description']) ?></p>
                        </div>
                        <div class="flex-shrink-0">
                            <?php component('button', [
                                'variant' => '9',
                                'icon' => 'edit',
                                'type' => 'button',
                                'id' => 'btn-edit-program-harian-' . $programHarian['id'],
                                'class' => 'btn-edit-program-harian',
                                'attrs' => [
                                    'data-program-id' => $programHarian['id'],
                                    'data-program-name' => $programHarian['program_name'],
                                    'data-program-description' => $programHarian['description'],
                                    'data-program-image' => $programHarian['image'] ?? ''
                                ]
                            ]); ?>
                        </div>
                    </div>
                    
                    <!-- Galeri Program Label -->
                    <p class="font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">Galeri Program</p>
                    
                    <!-- Gallery Images -->
                    <div class="flex items-center gap-[12px]">
                        <!-- Add Button -->
                        <button 
                            class="btn-add-gallery-harian w-[80px] h-[80px] rounded-xl bg-gray-placeholder flex items-center justify-center flex-shrink-0 hover:opacity-80 transition-opacity"
                            data-program-id="<?= e($programHarian['id']) ?>"
                        >
                            <svg class="w-[16px] h-[16px] text-black-highlight" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </button>
                        
                        <!-- Display cover image with SAMPUL badge if exists -->
                        <?php if ($programHarian['cover_image']): ?>
                            <?php if ($programHarian['cover_from_gallery']): ?>
                                <!-- Cover is from gallery -->
                                <?php foreach ($programHarian['gallery'] as $galleryImg): ?>
                                    <?php if ($galleryImg['is_cover'] == 1): ?>
                                        <div class="relative w-[80px] h-[80px] rounded-xl overflow-hidden flex-shrink-0">
                                            <div class="w-full h-full bg-gray-placeholder relative overflow-hidden gallery-image-item-harian cursor-pointer hover:opacity-80 transition-opacity"
                                                 data-gallery-id="<?= e($galleryImg['id']) ?>"
                                                 data-program-id="<?= e($programHarian['id']) ?>"
                                                 data-image-path="<?= e($galleryImg['image_path']) ?>"
                                                 data-description="<?= e($galleryImg['description']) ?>"
                                                 data-is-cover="1">
                                                <img 
                                                    src="<?= e($galleryImg['image_path']) ?>" 
                                                    alt="Sampul" 
                                                    class="w-full h-full object-cover"
                                                    onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                                                >
                                                <svg class="hidden absolute inset-0 w-8 h-8 m-auto text-white-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <!-- SAMPUL Badge -->
                                            <div class="absolute top-1 right-1 bg-primary text-white text-[8px] font-bold px-1.5 py-0.5 rounded">
                                                SAMPUL
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <!-- Cover is program.image -->
                                <div class="relative w-[80px] h-[80px] rounded-xl overflow-hidden flex-shrink-0">
                                    <div class="w-full h-full bg-gray-placeholder relative overflow-hidden program-image-item-harian cursor-pointer hover:opacity-80 transition-opacity"
                                         data-program-id="<?= e($programHarian['id']) ?>"
                                         data-program-image="<?= e($programHarian['image']) ?>"
                                         data-description=""
                                         data-is-cover="0">
                                        <img 
                                            src="<?= e($programHarian['image']) ?>" 
                                            alt="Program Image" 
                                            class="w-full h-full object-cover"
                                            onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                                        >
                                        <svg class="hidden absolute inset-0 w-8 h-8 m-auto text-white-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <!-- SAMPUL Badge -->
                                    <div class="absolute top-1 right-1 bg-primary text-white text-[8px] font-bold px-1.5 py-0.5 rounded">
                                        SAMPUL
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <!-- Display program.image if not cover -->
                        <?php if ($programHarian['image'] && $programHarian['cover_from_gallery']): ?>
                            <div class="w-[80px] h-[80px] rounded-xl bg-gray-placeholder relative overflow-hidden flex-shrink-0 program-image-item-harian cursor-pointer hover:opacity-80 transition-opacity"
                                 data-program-id="<?= e($programHarian['id']) ?>"
                                 data-program-image="<?= e($programHarian['image']) ?>"
                                 data-description=""
                                 data-is-cover="0">
                                <img 
                                    src="<?= e($programHarian['image']) ?>" 
                                    alt="Program Image" 
                                    class="w-full h-full object-cover"
                                    onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                                >
                                <svg class="hidden absolute inset-0 w-8 h-8 m-auto text-white-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Display other gallery images (not cover) -->
                        <?php foreach ($programHarian['gallery'] as $galleryImg): ?>
                            <?php if ($galleryImg['is_cover'] != 1): ?>
                                <div class="w-[80px] h-[80px] rounded-xl bg-gray-placeholder relative overflow-hidden flex-shrink-0 gallery-image-item-harian cursor-pointer hover:opacity-80 transition-opacity"
                                     data-gallery-id="<?= e($galleryImg['id']) ?>"
                                     data-program-id="<?= e($programHarian['id']) ?>"
                                     data-image-path="<?= e($galleryImg['image_path']) ?>"
                                     data-description="<?= e($galleryImg['description']) ?>"
                                     data-is-cover="0">
                                    <img 
                                        src="<?= e($galleryImg['image_path']) ?>" 
                                        alt="Gallery" 
                                        class="w-full h-full object-cover"
                                        onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                                    >
                                    <svg class="hidden absolute inset-0 w-8 h-8 m-auto text-white-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Tab Switching Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('[data-tab-index]');
    const tabPanels = document.querySelectorAll('.tab-panel');
    
    // Get color values from component
    const selectedBg = '<?= colors("white_neutral") ?>';
    const selectedShadow = '0px 2px 5.5px 0px rgba(0,0,0,0.07)';
    
    // Restore active tab from localStorage
    const activeTabIndex = localStorage.getItem('activeTabIndex') || '0';
    
    // Function to update tab button styles
    function updateTabButtons(activeIndex) {
        tabButtons.forEach(button => {
            if (button.dataset.tabIndex === activeIndex) {
                button.style.background = selectedBg;
                button.style.boxShadow = selectedShadow;
            } else {
                button.style.background = 'transparent';
                button.style.boxShadow = 'none';
            }
        });
    }
    
    // Function to show active tab panel
    function showActiveTab(index) {
        tabPanels.forEach(panel => {
            panel.classList.add('hidden');
        });
        const activePanel = document.getElementById('tab-panel-' + index);
        if (activePanel) {
            activePanel.classList.remove('hidden');
        }
    }
    
    // Initialize - show active tab from localStorage
    showActiveTab(activeTabIndex);
    updateTabButtons(activeTabIndex);
    
    // Add click event listeners
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const index = this.dataset.tabIndex;
            
            // Store active tab in localStorage
            localStorage.setItem('activeTabIndex', index);
            
            // Update UI
            updateTabButtons(index);
            showActiveTab(index);
        });
    });
    
    // Handle edit class button clicks
    const editButtons = document.querySelectorAll('[id^="btn-edit-class-"]');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const classData = {
                id: this.dataset.classId,
                name: this.dataset.className,
                age: this.dataset.classAge,
                duration: this.dataset.classDuration,
                maxStudents: this.dataset.classStudents,
                image: this.dataset.classImage
            };
            
            // Call the openEditClassModal function from modal-edit-class.php
            if (typeof window.openEditClassModal === 'function') {
                window.openEditClassModal(classData);
            }
        });
    });
    
    // Handle edit program tahun button clicks
    const editProgramButtons = document.querySelectorAll('[id^="btn-edit-program-tahun-"]');
    editProgramButtons.forEach(button => {
        button.addEventListener('click', function() {
            const programData = {
                id: this.dataset.programId,
                name: this.dataset.programName,
                description: this.dataset.programDescription,
                image: this.dataset.programImage
            };
            
            // Call the openEditProgramTahunModal function from modal-edit-program-tahun.php
            if (typeof window.openEditProgramTahunModal === 'function') {
                window.openEditProgramTahunModal(programData);
            }
        });
    });
    
    // Handle add gallery image button clicks
    const addGalleryButtons = document.querySelectorAll('.btn-add-gallery');
    addGalleryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const programId = this.dataset.programId;
            
            // Call the openAddGalleryImageModal function from modal-add-gallery-image.php
            if (typeof window.openAddGalleryImageModal === 'function') {
                window.openAddGalleryImageModal(programId);
            }
        });
    });
    
    // Handle gallery image click to edit
    const galleryImageItems = document.querySelectorAll('.gallery-image-item');
    galleryImageItems.forEach(item => {
        item.addEventListener('click', function() {
            const galleryData = {
                id: this.dataset.galleryId,
                program_id: this.dataset.programId,
                description: this.dataset.description,
                image_path: this.dataset.imagePath,
                is_cover: this.dataset.isCover
            };
            
            // Call the openEditGalleryImageModal function from modal-edit-gallery-image.php
            if (typeof window.openEditGalleryImageModal === 'function') {
                window.openEditGalleryImageModal(galleryData);
            }
        });
    });
    
    // Handle program image click to edit as gallery image (with empty description)
    const programImageItems = document.querySelectorAll('.program-image-item');
    programImageItems.forEach(item => {
        item.addEventListener('click', function() {
            const galleryData = {
                id: 'program-image', // Special ID to indicate this is program.image, not gallery
                program_id: this.dataset.programId,
                description: this.dataset.description || '',
                image_path: this.dataset.programImage,
                is_cover: this.dataset.isCover || '0',
                is_program_image: true // Flag to indicate this is program.image
            };
            
            // Call the openEditGalleryImageModal function
            if (typeof window.openEditGalleryImageModal === 'function') {
                window.openEditGalleryImageModal(galleryData);
            }
        });
    });
    
    // Handle edit program harian button clicks
    const editProgramHarianButtons = document.querySelectorAll('.btn-edit-program-harian');
    console.log('Found edit program harian buttons:', editProgramHarianButtons.length);
    
    editProgramHarianButtons.forEach(button => {
        button.addEventListener('click', function() {
            console.log('Edit Program Button Clicked');
            console.log('Button element:', this);
            console.log('Button dataset:', this.dataset);
            console.log('Raw attributes:');
            console.log('  - data-program-id:', this.getAttribute('data-program-id'));
            console.log('  - data-program-name:', this.getAttribute('data-program-name'));
            console.log('  - data-program-description:', this.getAttribute('data-program-description'));
            console.log('  - data-program-image:', this.getAttribute('data-program-image'));
            
            const programData = {
                id: this.dataset.programId,
                name: this.dataset.programName,
                description: this.dataset.programDescription,
                image: this.dataset.programImage
            };
            
            console.log('Program Data Object:', programData);
            
            // Call the openEditProgramHarianModal function
            if (typeof window.openEditProgramHarianModal === 'function') {
                window.openEditProgramHarianModal(programData);
            }
        });
    });
    
    // Handle add gallery harian button clicks
    const addGalleryHarianButtons = document.querySelectorAll('.btn-add-gallery-harian');
    addGalleryHarianButtons.forEach(button => {
        button.addEventListener('click', function() {
            const programId = this.dataset.programId;
            
            console.log('Add Gallery Button Clicked');
            console.log('Button element:', this);
            console.log('Dataset:', this.dataset);
            console.log('Program ID from dataset:', programId);
            
            // Call the openAddGalleryHarianModal function
            if (typeof window.openAddGalleryHarianModal === 'function') {
                window.openAddGalleryHarianModal(programId);
            }
        });
    });
    
    // Handle gallery harian image click to edit
    const galleryHarianImageItems = document.querySelectorAll('.gallery-image-item-harian');
    galleryHarianImageItems.forEach(item => {
        item.addEventListener('click', function() {
            console.log('Gallery Image Clicked');
            console.log('Item element:', this);
            console.log('Dataset:', this.dataset);
            
            const galleryData = {
                id: this.dataset.galleryId,
                program_id: this.dataset.programId,
                image_path: this.dataset.imagePath,
                description: this.dataset.description,
                is_cover: this.dataset.isCover
            };
            
            console.log('Gallery Data Object:', galleryData);
            
            // Call the openEditGalleryHarianModal function
            if (typeof window.openEditGalleryHarianModal === 'function') {
                window.openEditGalleryHarianModal(galleryData);
            }
        });
    });
    
    // Handle program harian image click to edit as gallery image
    const programHarianImageItems = document.querySelectorAll('.program-image-item-harian');
    programHarianImageItems.forEach(item => {
        item.addEventListener('click', function() {
            console.log('Program Image Clicked');
            console.log('Item element:', this);
            console.log('Dataset:', this.dataset);
            
            const galleryData = {
                id: '', // No gallery ID for program.image
                program_id: this.dataset.programId,
                image_path: this.dataset.programImage,
                description: this.dataset.description || '',
                is_cover: this.dataset.isCover || '0',
                is_program_image: true // Flag to indicate this is program.image
            };
            
            console.log('Gallery Data Object:', galleryData);
            
            // Call the openEditGalleryHarianModal function
            if (typeof window.openEditGalleryHarianModal === 'function') {
                window.openEditGalleryHarianModal(galleryData);
            }
        });
    });
});
</script>

<!-- Modals -->
<?php component('widget/activities/modals/class/modal-add-class'); ?>
<?php component('widget/activities/modals/class/modal-edit-class'); ?>
<?php component('widget/activities/modals/program_tahun/modal-add-program-tahun'); ?>
<?php component('widget/activities/modals/program_tahun/modal-edit-program-tahun'); ?>
<?php component('widget/activities/modals/gallery/modal-add-gallery-image'); ?>
<?php component('widget/activities/modals/gallery/modal-edit-gallery-image'); ?>
<?php component('widget/activities/modals/program_harian/modal-edit-program-harian'); ?>
<?php component('widget/activities/modals/program_harian/modal-add-gallery-harian'); ?>
<?php component('widget/activities/modals/program_harian/modal-edit-gallery-harian'); ?>

<?php

$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
