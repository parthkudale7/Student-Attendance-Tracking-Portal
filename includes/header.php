<?php
/**
 * Admin Shared Header Component
 * Student Attendance Tracking Portal
 */
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/csrf.php';

$pageTitle = $pageTitle ?? 'Admin Portal';
$activeNav = $activeNav ?? '';
$csrfToken = generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?> - Student Attendance Tracking Portal</title>
    
    <!-- Google Fonts (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome 6 Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    
    <!-- DataTables Bootstrap 5 & Buttons CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Flatpickr Custom Theme Calendar CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Chart.js Engine -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom Theme CSS with Automatic Cache Busting -->
    <link href="../assets/css/admin-theme.css?v=<?php echo time(); ?>" rel="stylesheet">

    <!-- Mandatory Ultra-High Specificity DataTables Theme Overrides -->
    <style>
        /* Force 'Show 10 entries' dropdown to match dark blue theme */
        body div.dataTables_wrapper div.dataTables_length select,
        body div.dataTables_wrapper div.dataTables_length select.form-select,
        body div.dataTables_wrapper select.form-select,
        body .dataTables_length select,
        body .dataTables_wrapper select,
        body .dataTables_length .form-select {
            background-color: #121F3D !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%234F7CFF' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right 0.6rem center !important;
            background-size: 12px 10px !important;
            border: 1px solid rgba(79, 124, 255, 0.45) !important;
            color: #FFFFFF !important;
            border-radius: 8px !important;
            padding: 0.25rem 1.8rem 0.25rem 0.65rem !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            outline: none !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
            cursor: pointer !important;
        }

        body div.dataTables_wrapper div.dataTables_length select option,
        body .dataTables_length select option {
            background-color: #0B1730 !important;
            color: #FFFFFF !important;
            padding: 0.4rem !important;
        }

        /* Force '< 1 >' Pagination buttons to be small, sleek & theme matching */
        body div.dataTables_wrapper div.dataTables_paginate ul.pagination,
        body .dataTables_wrapper .pagination {
            margin: 0 !important;
            gap: 3px !important;
            display: flex !important;
            align-items: center !important;
        }

        body div.dataTables_wrapper div.dataTables_paginate ul.pagination .page-item .page-link,
        body .dataTables_wrapper .pagination .page-item .page-link,
        body .dataTables_wrapper .pagination .page-link,
        body .dataTables_wrapper .paginate_button .page-link,
        body .dataTables_wrapper .page-item .page-link {
            background: rgba(18, 31, 61, 0.95) !important;
            border: 1px solid rgba(79, 124, 255, 0.35) !important;
            color: #94A3B8 !important;
            border-radius: 6px !important;
            padding: 0 !important;
            width: 28px !important;
            min-width: 28px !important;
            height: 28px !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25) !important;
            transition: all 0.2s ease !important;
            line-height: 1 !important;
        }

        body div.dataTables_wrapper div.dataTables_paginate ul.pagination .page-item .page-link:hover,
        body .dataTables_wrapper .pagination .page-item .page-link:hover,
        body .dataTables_wrapper .page-link:hover {
            background: rgba(79, 124, 255, 0.3) !important;
            color: #FFFFFF !important;
            border-color: #4F7CFF !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 10px rgba(79, 124, 255, 0.4) !important;
        }

        body div.dataTables_wrapper div.dataTables_paginate ul.pagination .page-item.active .page-link,
        body .dataTables_wrapper .pagination .page-item.active .page-link,
        body .dataTables_wrapper .page-item.active .page-link {
            background: linear-gradient(135deg, #4F7CFF 0%, #6366F1 100%) !important;
            color: #FFFFFF !important;
            border-color: transparent !important;
            box-shadow: 0 3px 12px rgba(79, 124, 255, 0.55) !important;
            font-weight: 700 !important;
        }

        body div.dataTables_wrapper div.dataTables_paginate ul.pagination .page-item.disabled .page-link,
        body .dataTables_wrapper .pagination .page-item.disabled .page-link,
        body .dataTables_wrapper .page-item.disabled .page-link {
            opacity: 0.35 !important;
            background: rgba(18, 31, 61, 0.4) !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
            color: #64748B !important;
            box-shadow: none !important;
            transform: none !important;
        }

        /* Force Flatpickr Date Picker Calendar Popup to Dark Navy Theme */
        body .flatpickr-calendar,
        .flatpickr-calendar {
            background: #0B1730 !important;
            border: 1px solid rgba(79, 124, 255, 0.4) !important;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.85), 0 0 25px rgba(79, 124, 255, 0.2) !important;
            border-radius: 14px !important;
            color: #FFFFFF !important;
            padding: 0.5rem !important;
        }

        body .flatpickr-calendar .flatpickr-months,
        body .flatpickr-calendar .flatpickr-month {
            background: transparent !important;
            color: #FFFFFF !important;
            fill: #FFFFFF !important;
        }

        body .flatpickr-calendar .flatpickr-current-month,
        body .flatpickr-calendar .flatpickr-current-month .flatpickr-monthDropdown-months,
        body .flatpickr-calendar .flatpickr-current-month input.cur-year {
            color: #FFFFFF !important;
            font-weight: 700 !important;
            background: transparent !important;
        }

        body .flatpickr-calendar .flatpickr-current-month .flatpickr-monthDropdown-months option {
            background: #0B1730 !important;
            color: #FFFFFF !important;
        }

        body .flatpickr-calendar .flatpickr-prev-month,
        body .flatpickr-calendar .flatpickr-next-month {
            color: #4F7CFF !important;
            fill: #4F7CFF !important;
            padding: 6px !important;
        }

        body .flatpickr-calendar .flatpickr-prev-month:hover svg,
        body .flatpickr-calendar .flatpickr-next-month:hover svg {
            fill: #FFFFFF !important;
        }

        body .flatpickr-calendar span.flatpickr-weekday {
            color: #94A3B8 !important;
            font-weight: 700 !important;
            font-size: 0.78rem !important;
            background: transparent !important;
        }

        body .flatpickr-calendar .flatpickr-day {
            color: #CBD5E1 !important;
            border-radius: 8px !important;
            font-weight: 500 !important;
            border-color: transparent !important;
        }

        body .flatpickr-calendar .flatpickr-day:hover,
        body .flatpickr-calendar .flatpickr-day:focus {
            background: rgba(79, 124, 255, 0.25) !important;
            color: #FFFFFF !important;
            border-color: rgba(79, 124, 255, 0.5) !important;
        }

        body .flatpickr-calendar .flatpickr-day.today {
            border-color: #4F7CFF !important;
            color: #4F7CFF !important;
            background: rgba(79, 124, 255, 0.12) !important;
        }

        body .flatpickr-calendar .flatpickr-day.selected,
        body .flatpickr-calendar .flatpickr-day.selected:hover,
        body .flatpickr-calendar .flatpickr-day.selected:focus {
            background: linear-gradient(135deg, #4F7CFF 0%, #6366F1 100%) !important;
            border-color: transparent !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 14px rgba(79, 124, 255, 0.5) !important;
            font-weight: 700 !important;
        }

        body .flatpickr-calendar .flatpickr-day.flatpickr-disabled,
        body .flatpickr-calendar .flatpickr-day.prevMonthDay,
        body .flatpickr-calendar .flatpickr-day.nextMonthDay {
            color: rgba(148, 163, 184, 0.28) !important;
            background: transparent !important;
        }

        body .flatpickr-calendar.arrowTop::before,
        body .flatpickr-calendar.arrowTop::after {
            border-bottom-color: #0B1730 !important;
        }

        body .flatpickr-calendar.arrowBottom::before,
        body .flatpickr-calendar.arrowBottom::after {
            border-top-color: #0B1730 !important;
        }
    </style>
</head>
<body>

<!-- Initial Page Loader Screen -->
<div id="appLoader">
    <div class="loader-content">
        <div class="loader-brand-icon">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>
        <div class="loader-spinner"></div>
    </div>
</div>
<script>
    (function() {
        function dismissLoader() {
            var loader = document.getElementById('appLoader');
            if (loader) {
                loader.style.opacity = '0';
                loader.style.pointerEvents = 'none';
                setTimeout(function() {
                    if (loader && loader.parentNode) {
                        loader.parentNode.removeChild(loader);
                    }
                }, 300);
            }
        }
        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            setTimeout(dismissLoader, 100);
        } else {
            document.addEventListener('DOMContentLoaded', dismissLoader);
            window.addEventListener('load', dismissLoader);
            setTimeout(dismissLoader, 500);
        }
    })();
</script>

<!-- Subtle Mouse Cursor Glow Follower -->
<div id="cursorGlow"></div>

<!-- Animated Background Orbs & Radial Grid Overlay -->
<div class="ambient-orbs-container">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
</div>
<div class="bg-grid-overlay"></div>
<div class="bg-grain-overlay"></div>
<div class="bg-mesh-overlay"></div>

<div id="wrapper">
