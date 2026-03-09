<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">ตั้งค่าแผนที่</h1>
    <p class="text-gray-500 text-sm">ปรับแต่งการแสดงผลแผนที่เมืองรังสิต สำหรับผู้ใช้งานทุกคน</p>
</div>

<div class="max-w-2xl space-y-6">

    <!-- Marker Clustering Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 bg-gray-50/40 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center">
                <i class="fas fa-layer-group text-primary-500"></i>
            </div>
            <div>
                <h2 class="font-bold text-gray-800">Marker Clustering</h2>
                <p class="text-xs text-gray-500">ควบคุมการรวมกลุ่มหมุดบนแผนที่</p>
            </div>
        </div>
        <div class="p-6 space-y-6">

            <!-- Enable/Disable Clustering -->
            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                <div>
                    <p class="font-bold text-gray-700 text-sm">เปิดใช้งาน Marker Clustering</p>
                    <p class="text-xs text-gray-400 mt-0.5">เมื่อเปิด หมุดที่อยู่ใกล้กันจะถูกรวมเป็นกลุ่ม</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="clusteringEnabled" class="sr-only peer" <?= $data['settings']['clustering_enabled'] ? 'checked' : '' ?>>
                    <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-500"></div>
                </label>
            </div>

            <!-- Disable Clustering at Zoom -->
            <div id="clusterZoomWrapper" class="<?= $data['settings']['clustering_enabled'] ? '' : 'opacity-40 pointer-events-none' ?>">
                <label class="block text-sm font-bold text-gray-700 mb-1">
                    ยกเลิกการรวมกลุ่มเมื่อ Zoom ถึงระดับ
                </label>
                <p class="text-xs text-gray-400 mb-3">เมื่อ zoom เข้าถึงระดับนี้ หมุดจะแยกออกจากกันโดยอัตโนมัติ (ค่า: 1–18)</p>
                <div class="flex items-center gap-4">
                    <input type="range" id="zoomSlider" min="10" max="18" step="1" value="<?= (int)$data['settings']['disable_clustering_at_zoom'] ?>"
                        class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-primary-500"
                        oninput="document.getElementById('zoomValue').textContent = this.value">
                    <span id="zoomValue" class="w-10 text-center bg-primary-500 text-white font-bold rounded-xl py-1 text-sm"><?= (int)$data['settings']['disable_clustering_at_zoom'] ?></span>
                </div>
                <div class="flex justify-between text-[10px] text-gray-400 px-1 mt-1">
                    <span>10 (ไกล)</span>
                    <span>14 (แนะนำ)</span>
                    <span>18 (ใกล้มาก)</span>
                </div>
            </div>

            <!-- Max Cluster Radius -->
            <div id="clusterRadiusWrapper" class="<?= $data['settings']['clustering_enabled'] ? '' : 'opacity-40 pointer-events-none' ?>">
                <label class="block text-sm font-bold text-gray-700 mb-1">
                    รัศมีการรวมกลุ่ม (Max Cluster Radius)
                </label>
                <p class="text-xs text-gray-400 mb-3">ระยะพิกเซลที่หมุดจะถูกรวมกัน ยิ่งมากยิ่งรวมกลุ่มได้ไกลขึ้น</p>
                <div class="flex items-center gap-4">
                    <input type="range" id="radiusSlider" min="20" max="120" step="10" value="<?= (int)$data['settings']['max_cluster_radius'] ?>"
                        class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-primary-500"
                        oninput="document.getElementById('radiusValue').textContent = this.value + 'px'">
                    <span id="radiusValue" class="w-16 text-center bg-primary-500 text-white font-bold rounded-xl py-1 text-sm"><?= (int)$data['settings']['max_cluster_radius'] ?>px</span>
                </div>
                <div class="flex justify-between text-[10px] text-gray-400 px-1 mt-1">
                    <span>20px (กระชับ)</span>
                    <span>50px (แนะนำ)</span>
                    <span>120px (กว้าง)</span>
                </div>
            </div>

        </div>
    </div>

    <!-- Preview Info -->
    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 flex gap-3">
        <i class="fas fa-info-circle text-blue-400 mt-0.5 shrink-0"></i>
        <div class="text-xs text-blue-700 leading-relaxed">
            <strong>หมายเหตุ:</strong> การเปลี่ยนแปลงจะมีผลทันทีสำหรับผู้ใช้ทุกคน หลังจากบันทึกแล้วให้รีเฟรชหน้าแผนที่เพื่อดูการเปลี่ยนแปลง
        </div>
    </div>

    <!-- Save Button -->
    <div class="flex justify-end">
        <button onclick="saveSettings()" id="saveBtn" class="bg-primary-500 hover:bg-primary-600 text-white px-8 py-3 rounded-xl font-bold transition shadow-lg shadow-primary-500/30 flex items-center gap-2">
            <i class="fas fa-save"></i> บันทึกการตั้งค่า
        </button>
    </div>

</div>

<script>
const BASE_URL = '<?= BASE_URL ?>';

const clusteringToggle = document.getElementById('clusteringEnabled');
const zoomWrapper = document.getElementById('clusterZoomWrapper');
const radiusWrapper = document.getElementById('clusterRadiusWrapper');

clusteringToggle.addEventListener('change', function() {
    if (this.checked) {
        zoomWrapper.classList.remove('opacity-40', 'pointer-events-none');
        radiusWrapper.classList.remove('opacity-40', 'pointer-events-none');
    } else {
        zoomWrapper.classList.add('opacity-40', 'pointer-events-none');
        radiusWrapper.classList.add('opacity-40', 'pointer-events-none');
    }
});

function saveSettings() {
    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> กำลังบันทึก...';

    const payload = {
        clustering_enabled: clusteringToggle.checked,
        disable_clustering_at_zoom: parseInt(document.getElementById('zoomSlider').value),
        max_cluster_radius: parseInt(document.getElementById('radiusSlider').value),
        spiderfy_on_max_zoom: true
    };

    fetch(BASE_URL + '/api/admin/map-settings/save', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'บันทึกสำเร็จ',
                text: 'ตั้งค่าแผนที่ได้รับการอัปเดตเรียบร้อยแล้ว',
                confirmButtonColor: '#0088CC',
                timer: 2000,
                timerProgressBar: true
            });
        } else {
            throw new Error(data.message || 'เกิดข้อผิดพลาด');
        }
    })
    .catch(err => {
        Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: err.message, confirmButtonColor: '#0088CC' });
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> บันทึกการตั้งค่า';
    });
}
</script>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>
