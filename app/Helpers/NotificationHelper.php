<?php

if (!function_exists('getNotificationColor')) {
    function getNotificationColor($type, $read = false)
    {
        $colors = [
            'surat'     => '#007bff',
            'comment'   => '#28a745',
            'pengaduan' => '#fd7e14',
            'konten'    => '#17a2b8',
            'default'   => '#6c757d',
        ];

        return $colors[$type] ?? $colors['default'];
    }
}

if (!function_exists('getNotificationIcon')) {
    function getNotificationIcon($type)
    {
        $icons = [
            'surat' => 'fas fa-envelope',
            'comment' => 'fas fa-comment',
            'pengaduan' => 'fas fa-exclamation-triangle',
            'konten' => 'fas fa-file-alt',
            'default' => 'fas fa-bell'
        ];

        return $icons[$type] ?? $icons['default'];
    }
}
