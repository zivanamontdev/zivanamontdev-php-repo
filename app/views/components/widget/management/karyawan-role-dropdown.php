<?php
// Shared options keep add/edit employee forms in sync.
component('dropdown', [
    'id' => $inputId . '-dropdown',
    'inputId' => $inputId,
    'name' => 'karyawan_role',
    'variant' => 'form',
    'class' => 'w-full',
    'selected' => '',
    'options' => [
        '' => 'Isi jabatan karyawan',
        'Guru Kelas' => 'Guru Kelas',
        'Guru Pendamping' => 'Guru Pendamping',
        'Guru Daycare' => 'Guru Daycare',
        'Staff Administrasi' => 'Staff Administrasi',
        'Staff Kebersihan' => 'Staff Kebersihan',
        'Penjaga Sekolah' => 'Penjaga Sekolah',
    ],
]);
