<?php
// NO session_start() here - it should be called in each page before including this file
// Just check if session exists, but don't start it
if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Procurement System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        body {
            background: #f5f5f5;
            display: flex;
        }

        /* ========== SIDEBAR ========== */
        .sidebar {
            width: 260px;
            background: #2c1810;
            color: white;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            transition: 0.3s;
        }

        .sidebar-header {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header h3 {
            font-size: 20px;
            color: #D2691E;
        }

        .sidebar-header p {
            font-size: 12px;
            opacity: 0.7;
            margin-top: 5px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            padding: 12px 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            text-decoration: none;
            transition: 0.3s;
        }

        .menu-item:hover {
            background: #8B4513;
            padding-left: 30px;
        }

        .menu-item.active {
            background: #8B4513;
            border-left: 4px solid #D2691E;
        }

        .menu-icon {
            font-size: 20px;
            width: 25px;
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            margin-left: 260px;
            width: calc(100% - 260px);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ========== HEADER ========== */
        .top-header {
            background: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-title h2 {
            color: #2c1810;
            font-size: 22px;
        }

        .header-title p {
            color: #666;
            font-size: 13px;
            margin-top: 5px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-name {
            color: #2c1810;
            font-weight: bold;
        }

        .user-role {
            background: #D2691E;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 8px 18px;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #c82333;
        }

        /* ========== CONTENT AREA ========== */
        .content {
            padding: 30px;
            flex: 1;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .stat-info h3 {
            font-size: 28px;
            color: #2c1810;
        }

        .stat-info p {
            color: #666;
            margin-top: 5px;
        }

        .stat-icon {
            font-size: 45px;
            opacity: 0.5;
        }

        /* Quick Actions */
        .section-title {
            color: #2c1810;
            margin-bottom: 20px;
            font-size: 22px;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .action-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            transition: 0.3s;
            border: 1px solid #e0e0e0;
        }

        .action-card:hover {
            background: #8B4513;
            transform: translateY(-3px);
        }

        .action-card:hover .action-icon,
        .action-card:hover .action-title {
            color: white;
        }

        .action-icon {
            font-size: 40px;
            margin-bottom: 15px;
            color: #8B4513;
        }

        .action-title {
            font-size: 16px;
            color: #2c1810;
            font-weight: bold;
        }

        /* Tables */
        .data-table {
            background: white;
            border-radius: 10px;
            overflow-x: auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #2c1810;
            color: white;
            font-weight: 600;
        }

        .role-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            color: white;
        }

        .role-admin { background: #c0392b; }
        .role-staff { background: #2980b9; }
        .role-hod { background: #27ae60; }
        .role-dean { background: #8e44ad; }

        .edit-btn, .delete-btn {
            padding: 5px 12px;
            border-radius: 3px;
            text-decoration: none;
            font-size: 12px;
            margin: 0 3px;
        }
        .edit-btn { background: #D2691E; color: white; }
        .delete-btn { background: #dc3545; color: white; }
        .edit-btn:hover { background: #8B4513; }
        .delete-btn:hover { background: #c82333; }

        /* Forms */
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            margin: 0 auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c1810;
            font-weight: bold;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .submit-btn {
            background: #8B4513;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .submit-btn:hover { background: #2c1810; }
        .msg {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }

        /* ========== FOOTER ========== */
        .footer {
            background: #2c1810;
            color: #ccc;
            text-align: center;
            padding: 20px;
            font-size: 13px;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .sidebar { width: 70px; }
            .sidebar-header h3, .sidebar-header p, .menu-item span:not(.menu-icon) { display: none; }
            .menu-item { justify-content: center; padding: 12px; }
            .main-content { margin-left: 70px; width: calc(100% - 70px); }
            .top-header { padding: 12px 20px; flex-direction: column; gap: 10px; text-align: center; }
            .content { padding: 20px; }
        }
    </style>
</head>
<body>