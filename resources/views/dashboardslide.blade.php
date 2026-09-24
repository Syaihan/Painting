<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Slideshow</title>
    <!-- Masukkan Tailwind CSS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: #000;
        }
        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body class="relative w-full h-full bg-black overflow-hidden">
    <a href="/" 
       class="fixed bottom-5 right-5 z-50 flex items-center gap-2 bg-red-600/80 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold text-sm shadow-lg backdrop-blur-md opacity-30 hover:opacity-100 transition-all duration-300 transform hover:scale-105">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        Exit Slide
    </a>
    <iframe id="dashboardFrame" src="{{ route('dashboard.coating') }}?embed=true"></iframe>
    <!-- Tombol Exit menggunakan Tailwind CSS -->
      <script>
        const pages = [
            "{{ route('dashboard.coating') }}?embed=true",
            "{{ route('dashboard.sealing') }}?embed=true"
        ];
        let currentIndex = 0;
        const intervalTime = 10000; // Waktu berganti dalam milidetik (10000 ms = 10 detik)
        setInterval(() => {
            currentIndex = (currentIndex + 1) % pages.length;
            document.getElementById('dashboardFrame').src = pages[currentIndex];
        }, intervalTime);
    </script>

</body>
</html>