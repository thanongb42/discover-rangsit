<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>

<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">จัดการหมวดหมู่ธุรกิจ</h1>
        <p class="text-gray-500 text-sm">เพิ่ม แก้ไข หรือลบหมวดหมู่สำหรับจัดกลุ่มธุรกิจในเมือง</p>
    </div>
    <button onclick="openAddModal()" class="bg-primary-500 hover:bg-primary-600 text-white px-5 py-2.5 rounded-xl font-bold transition shadow-lg shadow-primary-500/30 flex items-center">
        <i class="fas fa-plus mr-2"></i> เพิ่มหมวดหมู่
    </button>
</div>

<!-- Category Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="categoryTable">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase w-20">ไอคอน</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">ชื่อหมวดหมู่</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">รหัสสี</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center w-32">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="categoryTableBody">
                <tr id="loadingRow">
                    <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                        <i class="fas fa-circle-notch fa-spin mr-2"></i> กำลังโหลดข้อมูล...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="categoryModal" class="hidden fixed inset-0 bg-black/50 z-[1002] flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden animate-fade-in">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800" id="modalTitle">เพิ่มหมวดหมู่</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 p-2">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-8">
            <form id="categoryForm" class="space-y-5">
                <input type="hidden" name="id" id="catId">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">ชื่อหมวดหมู่</label>
                    <input type="text" name="name" id="catName" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-primary-500 focus:border-primary-500 transition" placeholder="เช่น ร้านอาหาร, คาเฟ่" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">ไอคอน (FontAwesome Class)</label>
                    <div class="flex gap-3">
                        <div class="relative flex-1">
                            <input type="text" name="icon" id="catIcon" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-primary-500 focus:border-primary-500 transition pl-12" placeholder="fa-icon-name" required onkeyup="updateIconPreview(this.value)">
                            <button type="button" onclick="toggleIconPicker()" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-600 transition p-1">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center text-gray-500 border border-gray-200" id="iconPreview">
                            <i class="fas fa-question"></i>
                        </div>
                    </div>
                    
                    <!-- Icon Picker Grid -->
                    <div id="iconPickerGrid" class="hidden mt-3 p-4 bg-gray-50 rounded-2xl border border-gray-200 max-h-48 overflow-y-auto">
                        <div class="mb-3">
                            <input type="text" id="iconSearch" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-1.5 text-xs" placeholder="ค้นหาไอคอน..." onkeyup="filterIcons(this.value)">
                        </div>
                        <div class="grid grid-cols-6 sm:grid-cols-8 gap-2" id="iconGridItems">
                            <!-- Icons populated by JS -->
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">สีประจำหมวดหมู่</label>
                    <div class="flex gap-3">
                        <div class="relative flex-1">
                            <input type="text" name="color" id="catColor" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-primary-500 focus:border-primary-500 transition uppercase pl-12" placeholder="#HEX" required onkeyup="updateColorPreview(this.value)">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 w-6 h-6 rounded-md border border-gray-200 overflow-hidden shadow-sm">
                                <input type="color" id="colorPicker" class="absolute -inset-2 w-[200%] h-[200%] cursor-pointer" oninput="syncColorPicker(this.value)">
                            </div>
                        </div>
                        <div class="w-12 h-12 rounded-xl border border-gray-200 shadow-inner transition-colors duration-300" id="colorPreview"></div>
                    </div>
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="closeModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-3 rounded-xl transition">ยกเลิก</button>
                    <button type="submit" class="flex-1 bg-primary-500 hover:bg-primary-600 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-primary-500/20">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let categories = [];

    document.addEventListener('DOMContentLoaded', () => {
        loadCategories();
        
        document.getElementById('categoryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveCategory();
        });
    });

    async function loadCategories() {
        try {
            const response = await fetch('<?= BASE_URL ?>/api/categories');
            categories = await response.json();
            renderTable();
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function renderTable() {
        const tbody = document.getElementById('categoryTableBody');
        tbody.innerHTML = '';

        if (categories.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">ไม่พบข้อมูลหมวดหมู่</td></tr>';
            return;
        }

        categories.forEach(cat => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50/80 transition-colors';
            row.id = `row-${cat.id}`;
            row.innerHTML = `
                <td class="px-6 py-4">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white shadow-sm" style="background-color: ${cat.color}">
                        <i class="fas ${cat.icon}"></i>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <p class="font-bold text-gray-800">${cat.name}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="text-xs font-mono font-bold px-2 py-1 bg-gray-100 rounded text-gray-500 border border-gray-200 uppercase">${cat.color}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex items-center justify-center space-x-1">
                        <button onclick="openEditModal(${cat.id})" class="text-gray-400 hover:text-blue-600 p-2 transition">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="confirmDelete(${cat.id})" class="text-gray-400 hover:text-red-600 p-2 transition">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function openAddModal() {
        document.getElementById('modalTitle').textContent = 'เพิ่มหมวดหมู่ใหม่';
        document.getElementById('categoryForm').reset();
        document.getElementById('catId').value = '';
        updateIconPreview('');
        updateColorPreview('#ffffff');
        document.getElementById('categoryModal').classList.remove('hidden');
    }

    function openEditModal(id) {
        const cat = categories.find(c => c.id == id);
        if (!cat) return;

        document.getElementById('modalTitle').textContent = 'แก้ไขหมวดหมู่';
        document.getElementById('catId').value = cat.id;
        document.getElementById('catName').value = cat.name;
        document.getElementById('catIcon').value = cat.icon;
        document.getElementById('catColor').value = cat.color;
        
        updateIconPreview(cat.icon);
        updateColorPreview(cat.color);
        document.getElementById('categoryModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('categoryModal').classList.add('hidden');
    }

    function updateIconPreview(val) {
        const preview = document.getElementById('iconPreview');
        preview.innerHTML = `<i class="fas ${val || 'fa-question'}"></i>`;
    }

    function updateColorPreview(val) {
        document.getElementById('colorPreview').style.backgroundColor = val;
        // Sync color picker if valid hex
        if(/^#[0-9A-F]{6}$/i.test(val)) {
            document.getElementById('colorPicker').value = val;
        }
    }

    function syncColorPicker(val) {
        document.getElementById('catColor').value = val.toUpperCase();
        document.getElementById('colorPreview').style.backgroundColor = val;
    }

    const availableIcons = [
        'fa-utensils', 'fa-bowl-food', 'fa-pizza-slice', 'fa-burger', 'fa-ice-cream', 'fa-coffee', 'fa-mug-hot', 'fa-glass-water',
        'fa-shopping-bag', 'fa-cart-shopping', 'fa-store', 'fa-map-marked-alt', 'fa-camera', 'fa-hotel', 'fa-landmark', 'fa-tree',
        'fa-concierge-bell', 'fa-wrench', 'fa-car', 'fa-gas-pump', 'fa-scissors', 'fa-hospital', 'fa-pills', 'fa-heart-pulse',
        'fa-basketball', 'fa-dumbbell', 'fa-swimming-pool', 'fa-graduation-cap', 'fa-bank', 'fa-bus', 'fa-train', 'fa-bicycle',
        'fa-briefcase', 'fa-phone', 'fa-envelope', 'fa-globe', 'fa-user', 'fa-users', 'fa-info-circle', 'fa-question-circle'
    ];

    function toggleIconPicker() {
        const grid = document.getElementById('iconPickerGrid');
        grid.classList.toggle('hidden');
        if(!grid.classList.contains('hidden')) {
            populateIconGrid();
        }
    }

    function populateIconGrid(filter = '') {
        const container = document.getElementById('iconGridItems');
        container.innerHTML = '';
        
        const filtered = availableIcons.filter(icon => icon.includes(filter.toLowerCase()));
        
        filtered.forEach(icon => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'w-10 h-10 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-primary-50 hover:text-primary-600 hover:border-primary-200 transition';
            btn.innerHTML = `<i class="fas ${icon}"></i>`;
            btn.onclick = () => selectIcon(icon);
            container.appendChild(btn);
        });

        if(filtered.length === 0) {
            container.innerHTML = '<div class="col-span-full text-center text-[10px] text-gray-400 py-4">ไม่พบไอคอน</div>';
        }
    }

    function filterIcons(val) {
        populateIconGrid(val);
    }

    function selectIcon(icon) {
        document.getElementById('catIcon').value = icon;
        updateIconPreview(icon);
        document.getElementById('iconPickerGrid').classList.add('hidden');
    }

    async function saveCategory() {
        const id = document.getElementById('catId').value;
        const url = id ? '<?= BASE_URL ?>/api/categories/update' : '<?= BASE_URL ?>/api/categories/add';
        const formData = new FormData(document.getElementById('categoryForm'));

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ!',
                    text: result.message,
                    timer: 1500,
                    showConfirmButton: false
                });
                closeModal();
                loadCategories(); // Reload and re-render
            } else {
                Swal.fire('ข้อผิดพลาด', result.message, 'error');
            }
        } catch (error) {
            Swal.fire('ข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้', 'error');
        }
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "ข้อมูลนี้จะถูกลบออกจากระบบอย่างถาวร!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ยืนยัน ลบเลย',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteCategory(id);
            }
        });
    }

    async function deleteCategory(id) {
        const formData = new FormData();
        formData.append('id', id);

        try {
            const response = await fetch('<?= BASE_URL ?>/api/categories/delete', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.success) {
                // DOM manipulation: remove row
                const row = document.getElementById(`row-${id}`);
                if (row) {
                    row.classList.add('opacity-0', 'transition-all', 'duration-500');
                    setTimeout(() => row.remove(), 500);
                }
                
                // Update local categories array
                categories = categories.filter(c => c.id != id);
                
                Swal.fire({
                    icon: 'success',
                    title: 'ลบข้อมูลแล้ว',
                    text: result.message,
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('ไม่สามารถลบได้', result.message, 'error');
            }
        } catch (error) {
            Swal.fire('ข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้', 'error');
        }
    }
</script>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>