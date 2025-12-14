<?php
/**
 * School Management Page
 * Page for managing school information, employees, and facilities
 */

$pageTitle = 'Manajemen Sekolah';
$currentPage = 'management';

// Start output buffering
ob_start();
?>

<!-- Admin Navbar -->
<?php component('admin-navbar', ['title' => 'Manajemen Sekolah']); ?>

<!-- Tabs -->
<?php component('tabs', [
    'tabs' => [
        'Prakata',
        'Karyawan',
        'Fasilitas'
    ],
    'active' => 0
]); ?>

<!-- Tab Content Panels -->
<div class="mt-3">
    <!-- Tab 1: Prakata -->
    <div id="tab-panel-0" class="tab-panel">
        <div class="w-full bg-white-neutral border border-white-neutral rounded-[16px] px-[24px] py-[16px]">
            <!-- Header: Title and Description -->
            <div class="flex items-center justify-between">
                <!-- Title and Description -->
                <div>
                    <h3 class="font-bold text-[16px] leading-[21px] text-black-soft mb-[8px]">Prakata Sekolah</h3>
                    <p class="font-normal text-[14px] leading-[21px] text-white-soft">Perbarui kalimat prakata sekolah yang ditampilkan di website.</p>
                </div>
                
                <!-- Edit Button -->
                <div class="flex-shrink-0">
                    <?php component('button', [
                        'variant' => '8',
                        'icon' => 'edit',
                        'text' => 'Ubah Prakata Sekolah',
                        'type' => 'button',
                        'id' => 'btn-edit-prakata'
                    ]); ?>
                </div>
            </div>
            
            <!-- Prakata Content -->
            <div class="mt-4 flex items-start gap-3" id="prakata-content">
                <!-- Image -->
                <div class="w-[176px] h-[176px] rounded-lg bg-gray-placeholder flex-shrink-0 flex items-center justify-center overflow-hidden">
                    <?php if (!empty($prakata['image'])): ?>
                        <img src="<?= htmlspecialchars($prakata['image']) ?>" alt="Prakata Image" class="w-full h-full object-cover">
                    <?php else: ?>
                        <svg class="w-16 h-16 text-white-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    <?php endif; ?>
                </div>
                
                <!-- Text Content -->
                <div class="flex-1 flex flex-col justify-between h-[176px]">
                    <!-- Title and Description -->
                    <div>
                        <h4 class="font-bold text-[16px] text-text-dark mb-2"><?= htmlspecialchars($prakata['title']) ?></h4>
                        <p class="font-normal text-[14px] text-black-soft-2 leading-[24px] line-clamp-4">
                            <?= nl2br(htmlspecialchars($prakata['description'])) ?>
                        </p>
                    </div>
                    
                    <!-- Last Updated Date -->
                    <p class="font-normal text-[12px] text-black-highlight">
                        Diupdate terakhir <?= date('d F Y', strtotime($prakata['updated_at'])) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tab 2: Karyawan -->
    <div id="tab-panel-1" class="tab-panel hidden">
        <!-- Card 1: Kepala Sekolah -->
        <div class="w-full bg-white-neutral border border-white-neutral rounded-[16px] px-[24px] py-[16px]">
            <!-- Header: Title and Description -->
            <div class="flex items-center justify-between mb-4">
                <!-- Title and Description -->
                <div>
                    <h3 class="font-bold text-[16px] leading-[21px] text-black-soft mb-[8px]">Kepala Sekolah</h3>
                    <p class="font-normal text-[14px] leading-[21px] text-white-soft">Identitas kepala sekolah</p>
                </div>
            </div>
            
            <!-- Principal Tile -->
            <?php component('widget/management/management-karyawan-tile', [
                'showDragHandle' => false,
                'photo' => $kepalaSekolah['photo'] ?? '',
                'name' => $kepalaSekolah['name'] ?? 'Belum ada data',
                'role' => 'Kepala Sekolah',
                'employeeId' => $kepalaSekolah['id'] ?? '0',
                'onEdit' => 'handleEditPrincipal()'
            ]); ?>
        </div>
        
        <!-- Card 2: Karyawan (12px margin top) -->
        <div class="w-full bg-white-neutral border border-white-neutral rounded-[16px] px-[24px] py-[16px] mt-3">
            <!-- Header: Title and Description -->
            <div class="flex items-center justify-between mb-4">
                <!-- Title and Description -->
                <div>
                    <h3 class="font-bold text-[16px] leading-[21px] text-black-soft mb-[8px]">Karyawan</h3>
                    <p class="font-normal text-[14px] leading-[21px] text-white-soft">Identitas Karyawan Sekolah</p>
                </div>
                
                <!-- Add Button -->
                <div class="flex-shrink-0">
                    <?php component('button', [
                        'variant' => '8',
                        'icon' => 'plus',
                        'text' => 'Tambah Karyawan',
                        'type' => 'button',
                        'id' => 'btn-add-employee'
                    ]); ?>
                </div>
            </div>
            
            <!-- Karyawan List (Sortable) -->
            <div id="karyawan-list" class="space-y-3">
                <?php if (!empty($karyawan)): ?>
                    <?php foreach ($karyawan as $k): ?>
                        <?php component('widget/management/management-karyawan-tile', [
                            'showDragHandle' => true,
                            'photo' => $k['photo'] ?? '',
                            'name' => $k['name'] ?? '',
                            'role' => $k['role'] ?? '',
                            'employeeId' => $k['id'] ?? '',
                            'onEdit' => 'handleEditEmployee(' . ($k['id'] ?? '') . ')'
                        ]); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-white-shadow py-4">Belum ada data karyawan</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Tab 3: Fasilitas -->
    <div id="tab-panel-2" class="tab-panel hidden">
        <div class="w-full bg-white-neutral border border-white-neutral rounded-[16px] px-[24px] py-[16px]">
            <!-- Header: Title and Description -->
            <div class="flex items-center justify-between mb-4">
                <!-- Title and Description -->
                <div>
                    <h3 class="font-bold text-[16px] leading-[21px] text-black-soft mb-[8px]">Fasilitas Sekolah</h3>
                    <p class="font-normal text-[14px] leading-[21px] text-white-soft">Daftar fasilitas sekolah yang ditawarkan di website</p>
                </div>
                
                <!-- Add Button -->
                <div class="flex-shrink-0">
                    <?php component('button', [
                        'variant' => '8',
                        'icon' => 'plus',
                        'text' => 'Tambah Fasilitas Sekolah',
                        'type' => 'button',
                        'id' => 'btn-add-facility'
                    ]); ?>
                </div>
            </div>
            
            <!-- Fasilitas Content -->
            <div class="mt-4 space-y-[24px]">
                <?php if (!empty($fasilitas)): ?>
                    <?php foreach ($fasilitas as $f): ?>
                    <div>
                        <!-- Header with Title and Edit Button -->
                        <div class="flex items-center mb-[12px]">
                            <div class="flex-1">
                                <h4 class="font-bold text-[12px] leading-[100%] text-text-dark"><?= e($f['name']) ?></h4>
                            </div>
                            <div class="flex-shrink-0">
                                <?php component('button', [
                                    'variant' => '9',
                                    'icon' => 'edit',
                                    'type' => 'button',
                                    'id' => 'btn-edit-fasilitas-' . $f['id'],
                                    'class' => 'btn-edit-fasilitas',
                                    'attrs' => [
                                        'data-fasilitas-id' => $f['id'],
                                        'data-fasilitas-name' => $f['name'],
                                        'data-fasilitas-image' => $f['image'] ?? ''
                                    ]
                                ]); ?>
                            </div>
                        </div>
                        
                        <!-- Galeri Fasilitas Label -->
                        <p class="font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">Galeri Fasilitas</p>
                        
                        <!-- Gallery Images with horizontal scroll -->
                        <div class="flex items-center gap-[12px] overflow-x-auto pb-2">
                            <!-- Add Button -->
                            <button 
                                class="btn-gallery-fasilitas w-[80px] h-[80px] rounded-xl bg-gray-placeholder flex items-center justify-center flex-shrink-0 hover:opacity-80 transition-opacity cursor-pointer"
                                data-fasilitas-id="<?= $f['id'] ?>"
                                data-fasilitas-name="<?= e($f['name']) ?>"
                            >
                                <svg class="w-[16px] h-[16px] text-black-highlight" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                            
                            <!-- Sampul (Cover Image) - Always first position -->
                            <?php if (!empty($f['cover_image'])): ?>
                                <div class="relative w-[80px] h-[80px] flex-shrink-0 cursor-pointer gallery-image-item-fasilitas"
                                    data-gallery-id="<?= $f['cover_image']['id'] ?? '' ?>"
                                    data-fasilitas-id="<?= $f['id'] ?>"
                                    data-image-path="<?= e($f['cover_image']['image_path'] ?? '') ?>"
                                    data-description="<?= e($f['cover_image']['description'] ?? '') ?>"
                                    data-is-cover="1">
                                    <img 
                                        src="<?= e($f['cover_image']['image_path'] ?? '') ?>" 
                                        alt="Sampul <?= e($f['name']) ?>" 
                                        class="w-full h-full rounded-xl object-cover hover:opacity-80 transition-opacity"
                                        onerror="console.error('Failed to load cover image:', this.src); this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                                    >
                                    <div class="hidden absolute inset-0 w-full h-full rounded-xl bg-gray-placeholder flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute top-1 right-1 bg-primary text-white text-[8px] font-bold px-1.5 py-0.5 rounded">
                                        SAMPUL
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Fasilitas Main Image (if exists and not used as cover) -->
                            <?php if (!empty($f['image']) && empty($f['cover_image'])): ?>
                                <div class="relative w-[80px] h-[80px] flex-shrink-0">
                                    <img 
                                        src="<?= e($f['image']) ?>" 
                                        alt="<?= e($f['name']) ?>" 
                                        class="w-full h-full rounded-xl object-cover"
                                        onerror="console.error('Failed to load fasilitas main image:', this.src); this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                                    >
                                    <div class="hidden absolute inset-0 w-full h-full rounded-xl bg-gray-placeholder flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Gallery Images (exclude the one marked as cover) -->
                            <?php if (!empty($f['gallery']) && is_array($f['gallery'])): ?>
                                <?php foreach ($f['gallery'] as $galleryItem): ?>
                                    <?php if (empty($galleryItem['is_cover']) || $galleryItem['is_cover'] == 0): ?>
                                        <div class="relative w-[80px] h-[80px] flex-shrink-0 cursor-pointer gallery-image-item-fasilitas"
                                            data-gallery-id="<?= $galleryItem['id'] ?>"
                                            data-fasilitas-id="<?= $f['id'] ?>"
                                            data-image-path="<?= e($galleryItem['image_path']) ?>"
                                            data-description="<?= e($galleryItem['description'] ?? '') ?>"
                                            data-is-cover="0">
                                            <img 
                                                src="<?= e($galleryItem['image_path']) ?>" 
                                                alt="Gallery <?= e($f['name']) ?>" 
                                                class="w-full h-full rounded-xl object-cover hover:opacity-80 transition-opacity"
                                                onerror="console.error('Failed to load gallery image:', this.src); this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                                            >
                                            <div class="hidden absolute inset-0 w-full h-full rounded-xl bg-gray-placeholder flex items-center justify-center">
                                                <svg class="w-8 h-8 text-white-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-white-shadow py-8">Belum ada data fasilitas</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<?php component('widget/management/modals/modal-edit-prakata'); ?>
<?php component('widget/management/modals/modal-add-karyawan'); ?>
<?php component('widget/management/modals/modal-edit-karyawan'); ?>
<?php component('widget/management/modals/modal-edit-kepala-sekolah'); ?>
<?php component('widget/management/modals/modal-add-fasilitas'); ?>
<?php component('widget/management/modals/modal-edit-fasilitas'); ?>
<?php component('widget/management/modals/modal-gallery-fasilitas'); ?>
<?php component('widget/management/modals/modal-edit-gallery-fasilitas'); ?>

<!-- Tab Switching Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('[data-tab-index]');
    const tabPanels = document.querySelectorAll('.tab-panel');
    
    // Get color values from component
    const selectedBg = '<?= colors("white_neutral") ?>';
    const selectedShadow = '0px 2px 5.5px 0px rgba(0,0,0,0.07)';
    
    // Restore active tab from localStorage
    const activeTabIndex = localStorage.getItem('activeManagementTabIndex') || '0';
    
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
            localStorage.setItem('activeManagementTabIndex', index);
            
            // Update UI
            updateTabButtons(index);
            showActiveTab(index);
        });
    });
    
    // Edit Prakata Button Click Handler
    const btnEditPrakata = document.getElementById('btn-edit-prakata');
    if (btnEditPrakata) {
        btnEditPrakata.addEventListener('click', function() {
            // Fetch current prakata data
            fetch('/admin/management/prakata/get')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data) {
                        openEditPrakataModal(data.data);
                    } else {
                        showToast('Gagal memuat data prakata', 'error', 3000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan saat memuat data', 'error', 3000);
                });
        });
    }
    
    // Initialize drag and drop for Karyawan list
    const karyawanList = document.getElementById('karyawan-list');
    if (karyawanList) {
        // Simple drag and drop implementation
        let draggedElement = null;
        
        karyawanList.addEventListener('dragstart', function(e) {
            if (e.target.closest('.drag-handle')) {
                draggedElement = e.target.closest('[data-employee-id]');
                draggedElement.style.opacity = '0.5';
                e.dataTransfer.effectAllowed = 'move';
            }
        });
        
        karyawanList.addEventListener('dragend', function(e) {
            if (draggedElement) {
                draggedElement.style.opacity = '1';
                draggedElement = null;
            }
        });
        
        karyawanList.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            
            const afterElement = getDragAfterElement(karyawanList, e.clientY);
            if (afterElement == null) {
                karyawanList.appendChild(draggedElement);
            } else {
                karyawanList.insertBefore(draggedElement, afterElement);
            }
        });
        
        // Make tiles draggable
        const tiles = karyawanList.querySelectorAll('[data-employee-id]');
        tiles.forEach(tile => {
            tile.setAttribute('draggable', 'true');
        });
        
        function getDragAfterElement(container, y) {
            const draggableElements = [...container.querySelectorAll('[data-employee-id]:not(.opacity-50)')];
            
            return draggableElements.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;
                
                if (offset < 0 && offset > closest.offset) {
                    return { offset: offset, element: child };
                } else {
                    return closest;
                }
            }, { offset: Number.NEGATIVE_INFINITY }).element;
        }
    }
});

// Edit Principal Handler
function handleEditPrincipal() {
    console.log('handleEditPrincipal called'); // Debug log
    // Fetch principal data
    fetch('/admin/management/kepala-sekolah/get')
        .then(response => {
            console.log('Response status:', response.status); // Debug log
            console.log('Response ok:', response.ok); // Debug log
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.text(); // Get as text first to see raw response
        })
        .then(text => {
            console.log('Raw response text:', text); // Debug log
            try {
                const data = JSON.parse(text);
                console.log('Parsed JSON data:', data); // Debug log
                if (data.success) {
                    // Open modal even if data is empty/null
                    openEditKepalaSekolahModal(data.data || {});
                } else {
                    showToast(data.message || 'Gagal memuat data kepala sekolah', 'error', 3000);
                }
            } catch (parseError) {
                console.error('JSON parse error:', parseError);
                console.error('Failed to parse text:', text);
                showToast('Gagal memproses data dari server', 'error', 3000);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            showToast('Terjadi kesalahan saat memuat data', 'error', 3000);
        });
}

// Edit Employee Handler
function handleEditEmployee(id) {
    // Fetch employee data
    fetch(`/admin/management/karyawan/${id}/get`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                openEditKaryawanModal(data.data);
            } else {
                showToast('Gagal memuat data karyawan', 'error', 3000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat memuat data', 'error', 3000);
        });
}

// Add Employee Handler
const btnAddEmployee = document.getElementById('btn-add-employee');
if (btnAddEmployee) {
    btnAddEmployee.addEventListener('click', function() {
        openAddKaryawanModal();
    });
}

// Edit Principal Button Handler
const btnEditPrincipal = document.getElementById('btn-edit-principal');
if (btnEditPrincipal) {
    btnEditPrincipal.addEventListener('click', handleEditPrincipal);
}

// Add Facility Button Handler
const btnAddFacility = document.getElementById('btn-add-facility');
if (btnAddFacility) {
    btnAddFacility.addEventListener('click', function() {
        openAddFasilitasModal();
    });
}

// Edit Facility Buttons
document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-edit-fasilitas')) {
        const btn = e.target.closest('.btn-edit-fasilitas');
        const fasilitasId = btn.dataset.fasilitasId;
        
        // Fetch fasilitas data including cover image from gallery
        fetch(`/admin/management/fasilitas/${fasilitasId}/get`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    openEditFasilitasModal({
                        id: data.data.id,
                        name: data.data.name,
                        image: data.data.cover_image // Use cover_image from gallery
                    });
                } else {
                    showToast('Gagal memuat data fasilitas', 'error', 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat memuat data', 'error', 3000);
            });
    }
    
    // Gallery Facility Buttons
    if (e.target.closest('.btn-gallery-fasilitas')) {
        const btn = e.target.closest('.btn-gallery-fasilitas');
        const fasilitasId = btn.dataset.fasilitasId;
        const fasilitasName = btn.dataset.fasilitasName;
        
        openAddGalleryFasilitasModal(fasilitasId);
    }
    
    // Gallery Image Items Click Handler
    if (e.target.closest('.gallery-image-item-fasilitas')) {
        const item = e.target.closest('.gallery-image-item-fasilitas');
        const galleryData = {
            id: item.dataset.galleryId,
            fasilitas_id: item.dataset.fasilitasId,
            image_path: item.dataset.imagePath,
            description: item.dataset.description,
            is_cover: item.dataset.isCover
        };
        
        // Call the openEditGalleryFasilitasModal function
        if (typeof window.openEditGalleryFasilitasModal === 'function') {
            window.openEditGalleryFasilitasModal(galleryData);
        }
    }
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
