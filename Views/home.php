<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Expandible</title>
    <link rel="stylesheet" href="./Css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="toggle-btn" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </div>
            <nav>
                <ul>
                    <li>
                        <a href="index.php?route=products">
                            <i class="fas fa-box"></i>
                            <span class="link-text">Gestión de Productos</span>
                        </a>
                    </li>
                    <li>
                        <a href="index.php?route=clients">
                            <i class="fas fa-users"></i>
                            <span class="link-text">Gestión de Clientes</span>
                        </a>
                    </li>
                    <li>
                        <a href="index.php?route=employees">
                            <i class="fas fa-user-tie"></i>

                            <span class="link-text">Gestión de Empleados</span>
                        </a>
                    </li>
                    <li>
                        <a href="index.php?route=sales">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="link-text">Gestión de Ventas</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-cog"></i>
                            <span class="link-text">Configuración</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header>
                <span>Welcome <?= $employee_name ?>!!!</span>
                <div class="header-right">
                    <span class="date-time"><?= date("d/m/Y H:i:s") ?></span>
                    <a href="index.php?route=logout" class="logout-button">Cerrar Sesión</a>
                </div>
            </header>
            <section class="dashboard">
                <h3>Resumen General</h3>
                <div class="resume">
                    <div class="sales-card">
                        <h3>Total de Productos</h3>
                        <p><?= $totalProducts ?> registrados</p>
                    </div>
                    <div class="sales-card">
                        <h3>total de Empleados</h3>
                        <p><?= $totalEmployees ?> registrados</p>
                    </div>
                    <div class="sales-card">
                        <h3>Total de Clientes</h3>
                        <p><?= $totalClients ?> registrados</p>
                    </div>
                    <div class="sales-card">
                        <h3>Total de Ventas</h3>
                        <p><?= $totalSales ?> registrados</p>
                    </div>
                </div>
                <h3>Reportes</h3>
                <div class="reports">
                    <canvas id="salesChart" width="400" height="200"></canvas>
                </div>
            </section>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.querySelector(".sidebar").classList.toggle("expanded");
        }
        
        function updateTime() {
            let now = new Date();
            let formattedDate = now.toLocaleDateString('es-ES');
            let formattedTime = now.toLocaleTimeString('es-ES');

            document.querySelector('.date-time').innerHTML = formattedDate + ' ' + formattedTime;
        }
        setInterval(updateTime, 1000);
        updateTime();

        function toggleSidebar() {
            document.querySelector(".sidebar").classList.toggle("expanded");
        }

        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Productos', 'Empleados', 'Clientes', 'Ventas'],
                datasets: [{
                    label: 'Cantidad',
                    data: [<?= $totalProducts ?>, <?= $totalEmployees ?>, <?= $totalClients ?>, <?= $totalSales ?>],
                    backgroundColor: ['#3498db', '#2ecc71', '#e74c3c', '#f39c12'],
                    borderColor: ['#2980b9', '#27ae60', '#c0392b', '#e67e22'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false,
                animation: {
                    duration: 0
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>