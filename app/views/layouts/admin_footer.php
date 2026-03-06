        </main>

        <footer class="bg-white border-t border-gray-200 py-6 px-6 text-center text-gray-500 text-sm">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-left">
                    <p class="font-bold text-gray-800">&copy; <?= date('Y') ?> Discover Rangsit - Smart City Platform</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Develop by : งานสถิติและข้อมูลสารสนเทศ ฝ่ายบริการและเผยแพร่วิชาการ กองยุทธศาสตร์และงบประมาณ เทศบาลนครรังสิต</p>
                </div>
                <div class="flex space-x-4 shrink-0">
                    <a href="#" class="hover:text-primary-600 font-medium">เกี่ยวกับเรา</a>
                    <a href="#" class="hover:text-primary-600 font-medium">ติดต่อสอบถาม</a>
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