<footer class="bg-gray-50 py-6 border-t border-gray-200">
    <div class="container mx-auto px-4 text-center">
        <a href="/" class="cursor-pointer block">
            <p class="text-gray-600 text-sm hover:text-blue-700 transition-colors duration-300">
                &copy; <?= date('Y') ?> <?= getSettings($conn)['nama_sekolah'] ?>. All rights reserved.
            </p>
        </a>
    </div>
</footer>

</html>