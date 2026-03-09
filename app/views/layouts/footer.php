    </main>
    <footer class="bg-slate-900 text-slate-400 py-10 text-center text-sm border-t border-white/5">
        <div class="container mx-auto px-4">
            <div class="flex flex-col items-center gap-6">
                <div class="text-center space-y-2">
                    <p class="text-white font-bold text-base">&copy; <?= date('Y') ?> <?= t('footer.platform') ?></p>
                    <p class="text-slate-500 text-xs leading-relaxed max-w-2xl mx-auto">
                        <?= t('footer.dev') ?>
                    </p>
                </div>
                <div class="flex space-x-6">
                    <a href="<?= BASE_URL ?>/privacy" class="hover:text-white transition"><?= t('footer.privacy') ?></a>
                    <a href="<?= BASE_URL ?>/terms" class="hover:text-white transition"><?= t('footer.terms') ?></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- PDPA Cookie Consent Banner -->
    <div id="cookieConsent" class="hidden fixed bottom-6 inset-x-4 md:left-auto md:right-6 md:w-[450px] bg-white/95 backdrop-blur-md rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.2)] border border-white z-[3000] p-6 animate-fade-in-up">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#0088CC] flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-cookie-bite"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-bold text-slate-800 mb-1 text-sm"><?= t('footer.cookie.title') ?></h4>
                <p class="text-slate-500 text-[11px] leading-relaxed mb-4">
                    <?= t('footer.cookie.desc') ?> <a href="<?= BASE_URL ?>/privacy" class="text-[#0088CC] underline"><?= t('footer.cookie.policy') ?></a><?= isLang('th') ? ' ของเรา' : '.' ?>
                </p>
                <div class="flex gap-2">
                    <button onclick="acceptCookies()" class="flex-1 bg-[#0088CC] text-white py-2 rounded-xl text-xs font-bold hover:bg-[#006BA8] transition"><?= t('footer.cookie.accept') ?></button>
                    <button onclick="closeCookieBanner()" class="px-4 py-2 text-slate-400 text-xs font-bold"><?= t('footer.cookie.close') ?></button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (!localStorage.getItem('cookiesAccepted')) {
                setTimeout(() => {
                    document.getElementById('cookieConsent').classList.remove('hidden');
                }, 1000);
            }
        });

        function acceptCookies() {
            localStorage.setItem('cookiesAccepted', 'true');
            closeCookieBanner();
        }

        function closeCookieBanner() {
            const banner = document.getElementById('cookieConsent');
            banner.classList.add('opacity-0', 'translate-y-4', 'transition-all', 'duration-500');
            setTimeout(() => banner.remove(), 500);
        }
    </script>

    <!-- Chart.js (Keep at bottom) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>