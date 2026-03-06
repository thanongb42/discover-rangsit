<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>

<!-- Hero Section -->
<section class="bg-navy-900 text-white py-20 px-4 relative overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <div class="absolute inset-0 bg-gradient-to-br from-teal-500 to-navy-900"></div>
    </div>
    <div class="container mx-auto text-center relative z-10">
        <h1 class="text-5xl md:text-6xl font-black mb-6 tracking-tight animate-fade-in">
            ค้นพบ <span class="text-teal-400 italic">รังสิต</span> ในแบบที่คุณไม่เคยเห็น
        </h1>
        <p class="text-xl text-teal-100/80 mb-10 max-w-2xl mx-auto leading-relaxed">
            แพลตฟอร์มรวมข้อมูลร้านค้า ร้านอาหาร สถานที่ท่องเที่ยว และบริการต่างๆ ในเมืองรังสิต ครบจบในที่เดียว
        </p>
        
        <!-- Quick Search -->
        <div class="max-w-5xl mx-auto relative group px-4">
            <!-- Animated Glow -->
            <div class="absolute -inset-1 bg-gradient-to-r from-teal-400 to-emerald-500 rounded-[3rem] blur-xl opacity-20 group-hover:opacity-40 transition duration-1000"></div>
            
            <div class="relative flex flex-col md:flex-row gap-2 bg-white p-3 rounded-[2rem] md:rounded-[4rem] shadow-[0_20px_50px_rgba(0,0,0,0.15)] transition-all duration-500 border-none">
                <div class="flex-1 flex items-center px-6 md:px-10">
                    <i class="fas fa-search text-teal-500 text-xl mr-5"></i>
                    <input type="text" id="homeSearch" class="w-full border-none focus:ring-0 text-slate-800 text-lg font-bold placeholder:text-slate-300 bg-transparent py-4 md:py-6" placeholder="ค้นหาร้านอาหาร, คาเฟ่, สถานที่สำคัญ หรือบริการในรังสิต...">
                </div>
                <button onclick="filterLandingPlaces()" class="bg-navy-800 hover:bg-teal-600 text-white px-12 py-4 md:py-2 rounded-[1.5rem] md:rounded-[3.5rem] font-black text-xl transition-all duration-300 shadow-xl shadow-navy-900/10 flex items-center justify-center min-w-[180px]">
                    ค้นหา
                </button>
            </div>
        </div>

        <div class="mt-8">
            <a href="<?= BASE_URL ?>/city-map" class="inline-flex items-center text-teal-300 hover:text-white font-bold transition">
                <i class="fas fa-map-marked-alt mr-2"></i> ดูแผนที่เมืองรังสิตแบบโต้ตอบ
            </a>
        </div>
    </div>
</section>

<!-- Content Section -->
<div class="container mx-auto px-4 py-16">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 mb-10">
        <div>
            <h2 class="text-3xl font-black text-slate-800">สถานที่ยอดนิยม</h2>
            <p class="text-slate-500 mt-1">คัดสรรธุรกิจและบริการที่ดีที่สุดสำหรับคุณ</p>
        </div>
        
        <!-- Controls -->
        <div class="flex flex-wrap items-center gap-4 bg-white p-2 rounded-2xl border border-slate-100 shadow-sm">
            <!-- Category Filter -->
            <select id="catFilter" onchange="filterLandingPlaces()" class="bg-slate-50 border-none rounded-xl px-4 py-2 text-sm font-bold text-slate-600 focus:ring-2 focus:ring-teal-500 cursor-pointer">
                <option value="">ทุกหมวดหมู่</option>
                <?php foreach($data['categories'] as $cat): ?>
                    <option value="<?= $cat->id ?>"><?= htmlspecialchars($cat->name) ?></option>
                <?php endforeach; ?>
            </select>

            <div class="h-8 w-px bg-slate-100"></div>

            <!-- View Toggle -->
            <div class="flex gap-1 p-1 bg-slate-50 rounded-lg">
                <button onclick="switchView('grid')" id="btnGridView" class="w-10 h-10 flex items-center justify-center rounded-md transition duration-200 bg-white shadow-sm text-navy-800">
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
        <h3 class="text-xl font-bold text-slate-800">ไม่พบข้อมูลที่คุณค้นหา</h3>
        <p class="text-slate-400">ลองใช้คำค้นหาอื่น หรือเลือกหมวดหมู่อื่นดูสิ</p>
    </div>
</div>

<script>
    const allPlaces = <?= json_encode($data['places']) ?>;
    let currentView = 'grid';

    document.addEventListener('DOMContentLoaded', () => {
        renderLandingPlaces(allPlaces);
        
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

    function filterLandingPlaces() {
        const keyword = document.getElementById('homeSearch').value.toLowerCase();
        const catId = document.getElementById('catFilter').value;

        const filtered = allPlaces.filter(p => {
            const matchKeyword = p.name.toLowerCase().includes(keyword) || p.description.toLowerCase().includes(keyword);
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
                    See more detail
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
                        See more detail
                    </a>
                </div>
            </div>
        `;
        return div;
    }
</script>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>