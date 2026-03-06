        </main>

        <footer class="bg-white border-t border-gray-200 py-4 px-6 text-center text-gray-500 text-sm">
            <div class="flex flex-col md:flex-row justify-between items-center gap-2">
                <p>&copy; <?= date('Y') ?> Discover Rangsit - Smart City Platform.</p>
                <div class="flex space-x-4">
                    <a href="#" class="hover:text-primary-600">เกี่ยวกับเรา</a>
                    <a href="#" class="hover:text-primary-600">ติดต่อสอบถาม</a>
                </div>
            </div>
        </footer>
    </div>

    <script>
        let mobileSidebarOpen = false;
        function toggleMobileSidebar() {
            mobileSidebarOpen = !mobileSidebarOpen;
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            if(sidebar) sidebar.classList.toggle('active', mobileSidebarOpen);
            if(overlay) overlay.classList.toggle('hidden', !mobileSidebarOpen);
        }

        // Auto-close on resize
        window.addEventListener('resize', () => {
            if(window.innerWidth >= 1024 && mobileSidebarOpen) {
                toggleMobileSidebar();
            }
        });
    </script>
</body>
</html>