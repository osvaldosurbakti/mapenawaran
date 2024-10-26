<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Aplikasi Penawaran' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #fdf3e1;
            font-family: Roboto, sans-serif;
            color: #343a40;
            margin: 0;
            padding: 0;
        }

        .top-urgent {
            background-color: #ffc0cb;
        }

        .urgent {
            background-color: #fff3cd;
        }

        .header-bg {
            background-color: #ffc107;
            color: #343a40;
            padding: 20px;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar {
            margin-bottom: 20px;
            background-color: #ffc107;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-light .navbar-nav .nav-link {
            color: #343a40;
            margin-right: 15px;
        }

        .navbar-light .navbar-nav .nav-link:hover {
            color: #e0a800;
        }

        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-body {
            padding: 20px;
        }

        .card-header {
            background-color: #ffc107;
            color: #343a40;
            font-weight: bold;
            text-align: center;
        }

        .btn-primary,
        .btn-secondary {
            border-radius: 0.3rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .btn-primary:hover {
            background-color: #e0a800;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .footer {
            background-color: #ffc107;
            padding: 10px;
            text-align: center;
            border-radius: 0.5rem;
            color: #343a40;
        }

        .logout-btn a {
            color: white;
            background-color: #dc3545;
            padding: 10px 20px;
            border-radius: 0.5rem;
            text-decoration: none;
        }

        .logout-btn a:hover {
            background-color: #c82333;
        }

        /* Responsivitas */
        @media (max-width: 768px) {
            .header-bg h1 {
                font-size: 24px;
                text-align: center;
            }

            .header-bg img {
                display: block;
                margin: 10px auto;
            }

            .logout-btn {
                text-align: center;
                margin-top: 10px;
            }
        }

        #penawaranTable tbody tr:hover {
            background-color: #f1f1f1;
        }

        .alert {
            border-radius: 0.3rem;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <!-- Header -->
        <header class="header-bg mb-4">
            <div class="row align-items-center">
                <div class="col-md-3 text-md-right text-center">
                    <img src="<?= base_url('/logo.jpeg'); ?>" alt="Logo" width="250">
                </div>
                <div class="col-md-6">
                    <h1><?= $header ?? 'Menu Approval Penawaran' ?></h1>
                </div>
                <?php if (!isset($isLoginPage) || !$isLoginPage): ?>
                    <div class="col-md-3 text-md-right text-center logout-btn">
                        <a href="<?= site_url('logout'); ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                <?php endif; ?>
            </div>
        </header>

        <!-- Navbar -->
        <?php if (!isset($isLoginPage) || !$isLoginPage): ?>
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <div class="navbar-nav">
                            <a class="nav-link" href="<?= site_url('dashboard'); ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                            <a class="nav-link" href="<?= site_url('history'); ?>"><i class="fas fa-history"></i> History Penawaran</a>
                        </div>
                    </div>
                </div>
            </nav>
        <?php endif; ?>

        <!-- Main Content -->
        <main class="mb-4">
            <div class="card">
                <div class="card-header">
                    PT. ASURANSI RAKSA PRATIKARA
                </div>
                <div class="card-body">
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="footer mt-4">
            <p>&copy; <?= date('Y') ?> PT Asuransi Raksa Pratikara</p>
        </footer>
    </div>

    <!-- Modal Konfirmasi Batal -->
    <div class="modal fade" id="confirmCancelModal" tabindex="-1" role="dialog" aria-labelledby="confirmCancelModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmCancelModalLabel">Konfirmasi Pembatalan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin membatalkan penawaran ini?</p>
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 50px;"></i>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmCancelButton">Ya, Batal</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Mengubah warna navbar saat digulir
        window.onscroll = function() {
            const navbar = document.querySelector('.navbar');
            if (window.pageYOffset > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        };

        function filterTable() {
            const input = document.getElementById("filterInput");
            const filter = input.value.toLowerCase();
            const table = document.getElementById("penawaranTable");
            const tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                let shouldShow = false;
                const td = tr[i].getElementsByTagName("td");

                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        const txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            shouldShow = true;
                            break;
                        }
                    }
                }

                tr[i].style.display = shouldShow ? "" : "none";
            }
        }

        function confirmCancel(id) {
            $('#confirmCancelModal').modal('show');
            $('#confirmCancelButton').off('click').on('click', function() {
                window.location.href = "<?= site_url('penawaran/cancel/'); ?>" + id;
            });
        }
    </script>
</body>

</html>