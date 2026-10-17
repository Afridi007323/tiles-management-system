<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiles & Marbles POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <style>
    body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; overflow-x: hidden; }
    .wrapper-container { display: flex; width: 100%; align-items: stretch; }
    .sidebar { min-height: 100vh; background-color: #212529; color: white; width: 250px; transition: all 0.3s; flex-shrink: 0; position: sticky; top: 0; height: 100vh; overflow-y: auto; z-index: 1000; }
    .sidebar-brand { font-size: 1.2rem; font-weight: bold; padding: 20px; text-align: center; border-bottom: 1px solid #495057; }
    .sidebar a { color: #ced4da; text-decoration: none; padding: 12px 20px; display: block; font-size: 15px; }
    .sidebar a:hover { color: #fff; background-color: #343a40; border-left: 4px solid #0d6efd; }
    .main-content { flex-grow: 1; padding: 20px; overflow-x: hidden; width: 100%; }
    .stat-card { border-radius: 10px; padding: 20px; color: white; margin-bottom: 20px; transition: transform 0.2s; }
    .stat-card:hover { transform: translateY(-5px); }
    .navbar-top { border-radius: 8px; background: white; padding: 15px 20px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }

    /* MOBILE RESPONSIVE TOGGLE STYLES */
    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            left: -250px;
            top: 0;
            height: 100%;
        }
        .sidebar.active {
            left: 0;
        }
        .main-content {
            width: 100%;
            padding: 10px;
        }
    }
</style>
</head>
<body>
<div class="d-flex">