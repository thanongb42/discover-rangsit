<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>

<!-- Hero Section -->
<section class="bg-[#0088CC] text-white py-20 px-4 relative overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <div class="absolute inset-0 bg-gradient-to-br from-[#33AADC] to-[#005A8E]"></div>
    </div>
    <div class="container mx-auto text-center relative z-10">
        <h1 class="text-5xl md:text-6xl font-black mb-6 tracking-tight animate-fade-in">
            <?= t('home.hero.title') ?>
        </h1>
        <p class="text-xl text-white/80 mb-10 max-w-2xl mx-auto leading-relaxed">
            <?= t('home.hero.desc') ?>
        </p>

        <!-- Quick Search -->
        <div class="max-w-5xl mx-auto relative group px-4">
            <!-- Animated Glow -->
            <div class="absolute -inset-1 bg-gradient-to-r from-blue-300 to-[#0088CC] rounded-[3rem] blur-xl opacity-20 group-hover:opacity-40 transition duration-1000"></div>

            <div class="relative flex flex-col md:flex-row gap-2 bg-white p-3 rounded-[2rem] md:rounded-[4rem] shadow-[0_20px_50px_rgba(0,0,0,0.15)] transition-all duration-500 border-none">
                <div class="flex-1 flex items-center px-6 md:px-10">
                    <i class="fas fa-search text-[#0088CC] text-xl mr-5"></i>
                    <input type="text" id="homeSearch" class="w-full border-none focus:ring-0 text-slate-800 text-lg font-bold placeholder:text-slate-300 bg-transparent py-4 md:py-6" placeholder="<?= t('home.search.placeholder') ?>">
                </div>
                <button onclick="filterLandingPlaces()" class="bg-[#006BA8] hover:bg-[#005A8E] text-white px-12 py-4 md:py-2 rounded-[1.5rem] md:rounded-[3.5rem] font-black text-xl transition-all duration-300 shadow-xl shadow-blue-900/10 flex items-center justify-center min-w-[180px]">
                    <?= t('home.search.btn') ?>
                </button>
            </div>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4 flex-wrap">
            <a href="<?= BASE_URL ?>/city-map" class="inline-flex items-center text-blue-100 hover:text-white font-bold transition">
                <i class="fas fa-map-marked-alt mr-2"></i> <?= t('home.map.link') ?>
            </a>

            <a href="<?= BASE_URL ?>/pr" class="inline-flex items-center gap-2 bg-yellow-400 hover:bg-yellow-300 text-blue-900 font-extrabold text-sm px-5 py-2.5 rounded-full shadow-lg shadow-yellow-400/30 transition-all hover:-translate-y-0.5">
                🏪 ฝากร้านของคุณ — ฟรี!
            </a>

            <a href="<?= BASE_URL ?>/presentation.html" target="_blank" class="inline-flex items-center gap-2 bg-white/15 hover:bg-white/25 text-white font-bold text-sm px-5 py-2.5 rounded-full border border-white/30 backdrop-blur transition-all hover:-translate-y-0.5">
                <i class="fas fa-chalkboard"></i> นำเสนอสำหรับผู้ประกอบการ
            </a>

            <!-- Weather Widget -->
            <div id="weatherWidget" class="hidden items-center gap-2 bg-white/10 backdrop-blur border border-white/20 rounded-2xl px-4 py-2.5">
                <i id="weatherIcon" class="fas fa-sun text-yellow-300 text-lg"></i>
                <span id="weatherTemp" class="text-white font-black text-lg">--°</span>
                <span id="weatherDesc" class="text-blue-100 text-xs font-medium"></span>
                <span class="text-white/30">·</span>
                <i class="fas fa-tint text-blue-200 text-xs"></i>
                <span id="weatherHumid" class="text-blue-100 text-xs font-medium">--%</span>
            </div>

            <!-- PM2.5 Realtime Widget -->
            <div id="aqiWidget" class="hidden items-center gap-3 bg-white/10 backdrop-blur border border-white/20 rounded-2xl px-4 py-2.5">
                <div id="aqiDot" class="w-2.5 h-2.5 rounded-full flex-shrink-0 animate-pulse"></div>
                <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 text-sm">
                    <span class="text-blue-100 font-medium text-xs"><?= isLang('en') ? 'PM2.5' : 'ฝุ่น PM2.5' ?></span>
                    <span id="aqiPM25" class="text-white font-black">--</span>
                    <span class="text-blue-200 text-xs">μg/m³</span>
                    <span class="text-white/30">·</span>
                    <span class="text-blue-100 font-medium text-xs">AQI</span>
                    <span id="aqiVal" class="text-white font-black">--</span>
                    <span id="aqiLabel" class="text-[10px] font-black px-2 py-0.5 rounded-full text-white"></span>
                </div>
                <div class="text-blue-200 text-[10px] font-medium hidden sm:block" id="aqiTime"></div>
            </div>
        </div>

        <!-- Mood / Contextual Search Chips -->
        <div class="mt-6 flex flex-wrap items-center justify-center gap-2" id="moodChips">
            <?php
            $moods = isLang('en') ? [
                ['icon' => 'fa-pray',       'label' => 'Temple',    'kw' => 'temple'],
                ['icon' => 'fa-coffee',     'label' => 'Café',      'kw' => 'cafe'],
                ['icon' => 'fa-utensils',   'label' => 'Food',      'kw' => 'food'],
                ['icon' => 'fa-leaf',       'label' => 'Nature',    'kw' => 'nature'],
                ['icon' => 'fa-shopping-bag','label'=> 'Shopping',  'kw' => 'shop'],
                ['icon' => 'fa-baby',       'label' => 'Family',    'kw' => 'family'],
                ['icon' => 'fa-running',    'label' => 'Sport',     'kw' => 'sport'],
                ['icon' => 'fa-moon',       'label' => 'Nightlife', 'kw' => 'night'],
            ] : [
                ['icon' => 'fa-pray',       'label' => 'ไหว้พระ',   'kw' => 'วัด'],
                ['icon' => 'fa-coffee',     'label' => 'คาเฟ่',     'kw' => 'คาเฟ่'],
                ['icon' => 'fa-utensils',   'label' => 'อาหาร',    'kw' => 'อาหาร'],
                ['icon' => 'fa-leaf',       'label' => 'ธรรมชาติ',  'kw' => 'ธรรมชาติ'],
                ['icon' => 'fa-shopping-bag','label'=> 'ช้อปปิ้ง',  'kw' => 'ช้อปปิ้ง'],
                ['icon' => 'fa-baby',       'label' => 'ครอบครัว',  'kw' => 'ครอบครัว'],
                ['icon' => 'fa-running',    'label' => 'กีฬา',     'kw' => 'กีฬา'],
                ['icon' => 'fa-moon',       'label' => 'กลางคืน',  'kw' => 'กลางคืน'],
            ];
            foreach ($moods as $mood): ?>
                <button onclick="applyMoodSearch('<?= addslashes($mood['kw']) ?>', this)"
                    data-kw="<?= htmlspecialchars($mood['kw']) ?>"
                    class="mood-chip inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/30 border border-white/20 text-white text-xs font-bold px-3 py-1.5 rounded-full transition-all duration-200 backdrop-blur-sm">
                    <i class="fas <?= $mood['icon'] ?> text-[10px]"></i>
                    <?= $mood['label'] ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- PR Banner -->
    <div class="container mx-auto px-4 mt-8 relative z-10">
        <a href="<?= BASE_URL ?>/pr" class="group flex flex-col sm:flex-row items-center justify-between gap-4 bg-gradient-to-r from-yellow-400 via-yellow-300 to-yellow-400 hover:from-yellow-300 hover:to-yellow-300 text-blue-900 rounded-2xl px-6 py-4 shadow-xl shadow-yellow-400/30 transition-all hover:-translate-y-0.5">
            <div class="flex items-center gap-4">
                <span class="text-3xl">🏪</span>
                <div class="text-left">
                    <div class="font-extrabold text-lg leading-tight">มีร้านในรังสิต? มาฝากร้านกับเรา — ฟรี!</div>
                    <div class="text-blue-800/70 text-sm font-medium">แสดงบนแผนที่ · รับรีวิว · เชื่อมต่อ LINE · ไม่มีค่าใช้จ่าย</div>
                </div>
            </div>
            <div class="flex items-center gap-2 bg-blue-900 text-white text-sm font-extrabold px-5 py-2.5 rounded-xl group-hover:bg-blue-800 transition-colors whitespace-nowrap shrink-0">
                โพสต์เลย <i class="fas fa-arrow-right text-xs ml-1"></i>
            </div>
        </a>
    </div>
</section>

<!-- For You Section (logged-in users with interest history) -->
<?php if (!empty($data['has_interests']) && !empty($data['recommendations'])): ?>
<section class="bg-gradient-to-b from-slate-50 to-white pt-12 pb-4">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-black text-slate-800 flex items-center gap-2">
                    <span class="text-2xl">✨</span>
                    <?= isLang('en') ? 'Recommended for You' : 'แนะนำสำหรับคุณ' ?>
                </h2>
                <p class="text-slate-400 text-sm mt-0.5">
                    <?= isLang('en') ? 'Based on your browsing interests' : 'จากความสนใจของคุณ' ?>
                </p>
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <?php foreach ($data['recommendations'] as $rec): ?>
            <a href="<?= BASE_URL ?>/place/<?= htmlspecialchars($rec->slug) ?>"
               class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:border-blue-100 transition-all duration-300 overflow-hidden flex flex-col">
                <div class="h-32 overflow-hidden">
                    <img src="<?= BASE_URL ?>/uploads/covers/<?= htmlspecialchars($rec->cover_image ?: 'default.jpg') ?>"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="">
                </div>
                <div class="p-3 flex-1">
                    <p class="font-bold text-slate-800 text-sm truncate"><?= htmlspecialchars($rec->name) ?></p>
                    <div class="flex items-center gap-1 mt-1 text-yellow-500 text-xs">
                        <i class="fas fa-star"></i>
                        <span class="font-bold text-slate-600"><?= number_format($rec->rating_avg, 1) ?></span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Content Section -->
<div class="container mx-auto px-4 py-16">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 mb-10">
        <div>
            <h2 class="text-3xl font-black text-slate-800"><?= t('home.popular.title') ?></h2>
            <p class="text-slate-500 mt-1"><?= t('home.popular.subtitle') ?></p>
        </div>
        
        <!-- Controls -->
        <div class="flex flex-wrap items-center gap-4 bg-white p-2 rounded-2xl border border-slate-100 shadow-sm">
            <!-- Category Filter -->
            <select id="catFilter" onchange="filterLandingPlaces()" class="bg-slate-50 border-none rounded-xl px-4 py-2 text-sm font-bold text-slate-600 focus:ring-2 focus:ring-[#0088CC] cursor-pointer">
                <option value=""><?= t('home.all_categories') ?></option>
                <?php foreach($data['categories'] as $cat): ?>
                    <option value="<?= $cat->id ?>"><?= htmlspecialchars($cat->name) ?></option>
                <?php endforeach; ?>
            </select>

            <div class="h-8 w-px bg-slate-100"></div>

            <!-- View Toggle -->
            <div class="flex gap-1 p-1 bg-slate-50 rounded-lg">
                <button onclick="switchView('grid')" id="btnGridView" class="w-10 h-10 flex items-center justify-center rounded-md transition duration-200 bg-white shadow-sm text-[#0088CC]">
                    <i class="fas fa-th-large"></i>
                </button>
                <button onclick="switchView('list')" id="btnListView" class="w-10 h-10 flex items-center justify-center rounded-md transition duration-200 text-slate-400 hover:bg-slate-100">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Places Container -->
    <div id="landingPlacesContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 transition-all duration-500">
        <!-- populated by JS -->
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="hidden py-20 text-center bg-white rounded-[3rem] border-2 border-dashed border-slate-100">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
            <i class="fas fa-search text-3xl"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-800"><?= t('home.no_results') ?></h3>
        <p class="text-slate-400"><?= t('home.no_results.desc') ?></p>
    </div>
</div>

<script>
    const allPlaces = <?= json_encode($data['places']) ?>;
    const HOME_LANG = {
        seeMore:    '<?= addslashes(isLang('en') ? 'See details' : 'ดูรายละเอียด') ?>',
        noDesc:     '<?= addslashes(isLang('en') ? 'No description available' : 'ไม่มีคำอธิบายข้อมูล') ?>',
        views:      '<?= addslashes(t('home.views')) ?>',
    };
    let currentView = 'grid';

    document.addEventListener('DOMContentLoaded', () => {
        // Detect mobile and set default view to list
        if (window.innerWidth < 768) {
            switchView('list');
        } else {
            renderLandingPlaces(allPlaces);
        }
        
        // Enter key for search
        document.getElementById('homeSearch').addEventListener('keyup', (e) => {
            if(e.key === 'Enter') filterLandingPlaces();
        });
    });

    function switchView(view) {
        currentView = view;
        const container = document.getElementById('landingPlacesContainer');
        const btnGrid = document.getElementById('btnGridView');
        const btnList = document.getElementById('btnListView');

        if (view === 'grid') {
            container.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8';
            btnGrid.className = 'w-10 h-10 flex items-center justify-center rounded-md transition duration-200 bg-white shadow-sm text-navy-800';
            btnList.className = 'w-10 h-10 flex items-center justify-center rounded-md transition duration-200 text-slate-400 hover:bg-slate-100';
        } else {
            container.className = 'flex flex-col gap-4 max-w-5xl mx-auto';
            btnList.className = 'w-10 h-10 flex items-center justify-center rounded-md transition duration-200 bg-white shadow-sm text-navy-800';
            btnGrid.className = 'w-10 h-10 flex items-center justify-center rounded-md transition duration-200 text-slate-400 hover:bg-slate-100';
        }
        
        filterLandingPlaces(); // Re-render with new layout
    }

    let activeMoodKw = '';

    function applyMoodSearch(kw, btn) {
        const chips = document.querySelectorAll('.mood-chip');
        if (activeMoodKw === kw) {
            // deselect
            activeMoodKw = '';
            chips.forEach(c => c.classList.remove('bg-white/40', 'ring-2', 'ring-white/60'));
            document.getElementById('homeSearch').value = '';
        } else {
            activeMoodKw = kw;
            chips.forEach(c => c.classList.remove('bg-white/40', 'ring-2', 'ring-white/60'));
            btn.classList.add('bg-white/40', 'ring-2', 'ring-white/60');
            document.getElementById('homeSearch').value = kw;
        }
        filterLandingPlaces();
        // Scroll to results
        document.querySelector('.container').scrollIntoView({ behavior: 'smooth' });
    }

    function filterLandingPlaces() {
        const keyword = document.getElementById('homeSearch').value.toLowerCase();
        const catId = document.getElementById('catFilter').value;

        const filtered = allPlaces.filter(p => {
            const matchKeyword = p.name.toLowerCase().includes(keyword) ||
                                 (p.description || '').toLowerCase().includes(keyword) ||
                                 (p.category_name || '').toLowerCase().includes(keyword);
            const matchCat = catId === "" || p.category_id == catId;
            return matchKeyword && matchCat;
        });

        renderLandingPlaces(filtered);
    }

    function renderLandingPlaces(data) {
        const container = document.getElementById('landingPlacesContainer');
        const emptyState = document.getElementById('emptyState');
        container.innerHTML = '';

        if (data.length === 0) {
            container.classList.add('hidden');
            emptyState.classList.remove('hidden');
            return;
        }

        container.classList.remove('hidden');
        emptyState.classList.add('hidden');

        data.forEach(p => {
            if (currentView === 'grid') {
                container.appendChild(createGridItem(p));
            } else {
                container.appendChild(createListItem(p));
            }
        });
    }

    function createGridItem(p) {
        const div = document.createElement('div');
        div.className = 'group bg-white rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:border-teal-100 transition-all duration-500 overflow-hidden flex flex-col';
        div.innerHTML = `
            <div class="relative h-56 overflow-hidden">
                <img src="<?= BASE_URL ?>/uploads/covers/${p.cover_image || 'default.jpg'}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                <div class="absolute top-4 left-4">
                    <span class="bg-white/90 backdrop-blur text-navy-800 text-[10px] font-black uppercase px-3 py-1 rounded-full shadow-sm border border-white">
                        ${p.category_name}
                    </span>
                </div>
            </div>
            <div class="p-6 flex flex-col flex-1">
                <h3 class="text-xl font-bold text-slate-800 mb-2 truncate">${p.name}</h3>
                <div class="flex items-center gap-3 text-xs font-bold text-slate-400 mb-4">
                    <div class="flex items-center text-yellow-500">
                        <i class="fas fa-star mr-1"></i> ${p.rating_avg}
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-phone-alt mr-1"></i> ${p.phone || '-'}
                    </div>
                </div>
                <p class="text-slate-500 text-sm mb-6 line-clamp-2 leading-relaxed flex-1">
                    ${p.description || 'ไม่มีคำอธิบายข้อมูล'}
                </p>
                <a href="<?= BASE_URL ?>/place/${p.slug}" class="bg-slate-900 group-hover:bg-teal-600 text-white text-center py-3 rounded-2xl font-black text-sm transition-colors duration-300">
                    ${HOME_LANG.seeMore}
                </a>
            </div>
        `;
        return div;
    }

    function createListItem(p) {
        const div = document.createElement('div');
        div.className = 'group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-teal-100 transition-all duration-300 p-4 flex gap-6 items-center';
        div.innerHTML = `
            <div class="w-32 h-32 md:w-40 md:h-40 rounded-2xl overflow-hidden bg-slate-100 flex-shrink-0">
                <img src="<?= BASE_URL ?>/uploads/covers/${p.cover_image || 'default.jpg'}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex justify-between items-start mb-1">
                    <h3 class="text-xl font-bold text-slate-800 truncate">${p.name}</h3>
                    <span class="bg-teal-50 text-teal-600 text-[10px] font-black uppercase px-2 py-0.5 rounded-full border border-teal-100">
                        ${p.category_name}
                    </span>
                </div>
                <div class="flex items-center gap-4 text-xs font-bold text-slate-400 mb-3">
                    <div class="flex items-center text-yellow-500">
                        <i class="fas fa-star mr-1"></i> ${p.rating_avg}
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt mr-1 text-teal-500"></i> ${p.address.substring(0, 40)}...
                    </div>
                </div>
                <p class="text-slate-500 text-sm hidden md:block mb-4 line-clamp-1">
                    ${p.description || 'ไม่มีคำอธิบายข้อมูล'}
                </p>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-bold text-navy-800"><i class="fas fa-phone-alt mr-1"></i> ${p.phone || '-'}</span>
                    <a href="<?= BASE_URL ?>/place/${p.slug}" class="bg-slate-50 text-navy-800 hover:bg-teal-600 hover:text-white px-6 py-2 rounded-xl font-bold text-xs transition duration-300">
                        ${HOME_LANG.seeMore}
                    </a>
                </div>
            </div>
        `;
        return div;
    }
</script>

<script>
// Weather Widget
(async function loadWeather() {
    try {
        const res  = await fetch('<?= BASE_URL ?>/api/weather');
        const data = await res.json();
        if (!data.success) return;

        const isEN = <?= isLang('en') ? 'true' : 'false' ?>;
        document.getElementById('weatherIcon').className    = `fas ${data.icon} text-lg`;
        document.getElementById('weatherIcon').style.color  = data.color;
        document.getElementById('weatherTemp').textContent  = data.temp_c + '°C';
        document.getElementById('weatherDesc').textContent  = isEN ? data.desc_en : data.desc_th;
        document.getElementById('weatherHumid').textContent = data.humidity + '%';

        const w = document.getElementById('weatherWidget');
        w.classList.remove('hidden');
        w.classList.add('flex');
    } catch(e) {}
})();

// PM2.5 Realtime Widget
(async function loadAQI() {
    try {
        const res  = await fetch('<?= BASE_URL ?>/api/air-quality');
        const data = await res.json();
        if (!data.success) return;

        const isEN  = <?= isLang('en') ? 'true' : 'false' ?>;
        const label = isEN ? data.labelEN : data.labelTH;

        document.getElementById('aqiDot').style.backgroundColor   = data.color_hex;
        document.getElementById('aqiPM25').textContent             = data.pm25_value;
        document.getElementById('aqiVal').textContent              = data.pm25_aqi;
        document.getElementById('aqiLabel').textContent            = label;
        document.getElementById('aqiLabel').style.backgroundColor  = data.color_hex;
        document.getElementById('aqiTime').textContent             = data.date + ' ' + data.time;

        const widget = document.getElementById('aqiWidget');
        widget.classList.remove('hidden');
        widget.classList.add('flex');
    } catch(e) { /* silently fail — widget stays hidden */ }
})();
</script>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>