<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dompetku - Kelola Keuangan Pribadi dengan Mudah</title>
    <meta name="description"
        content="Aplikasi manajemen keuangan pribadi yang membantu Anda mencatat pemasukan, pengeluaran, transfer antar akun, dan menampilkan laporan keuangan secara visual. Gratis dan mudah digunakan." />

    @vite(['resources/css/landing.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-background text-foreground">
        <livewire:layout.landing-navigation />
        {{ $slot }}
    </div>
</body>

</html>
