<footer class="bg-gray-50 py-4 border-t border-gray-200">
    <div class="container mx-auto px-4 text-center">
        <a href="/" class="cursor-pointer">
            <p class="text-gray-600 text-sm hover:text-blue-600 transition-colors duration-200">
                &copy; <?= date('Y') ?> <?= getSettings($conn)['nama_sekolah'] ?>. All rights reserved.
            </p>
        </a>
    </div>
</footer>

</html>