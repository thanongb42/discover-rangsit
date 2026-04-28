<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">เพิ่มธุรกิจใหม่</h1>
    <p class="text-gray-500 text-sm">ระบุข้อมูลธุรกิจของคุณให้ครบถ้วนเพื่อความรวดเร็วในการตรวจสอบและอนุมัติ</p>
</div>

<div class="max-w-4xl">
    <?php if(isset($_SESSION['error'])): ?>
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 shadow-sm rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Data Entry Guideline Alert -->
    <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-2xl shadow-sm">
        <div class="flex gap-3">
            <i class="fas fa-info-circle text-blue-500 mt-1"></i>
            <div class="text-sm text-blue-800 leading-relaxed">
                <p class="font-bold mb-1">ข้อแนะนำในการนำเข้าข้อมูล:</p>
                <p>1. ข้อมูลที่นำเข้าควรเป็นข้อมูลสาธารณะที่สามารถเปิดเผยได้ เพื่อการประชาสัมพันธ์เมือง</p>
                <p>2. รูปภาพที่มีข้อมูลส่วนบุคคล (เช่น ใบหน้าบุคคล, ป้ายทะเบียน) จะต้องทำการอำพรางหรือได้รับอนุญาตจากผู้ที่อยู่ในเหตุการณ์ก่อนทุกครั้ง</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8">
            <form action="<?= BASE_URL ?>/dashboard/add-place" method="POST" enctype="multipart/form-data" id="addPlaceForm">
                <input type="hidden" name="cover_base64" id="coverBase64">
                <input type="hidden" name="qr_base64" id="qrBase64">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">ชื่อธุรกิจ</label>
                        <input type="text" name="name" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-primary-500 focus:border-primary-500 transition" required placeholder="เช่น ร้านก๋วยเตี๋ยวเรือโกฮับ">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">หมวดหมู่</label>
                        <select name="category_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-primary-500 focus:border-primary-500 transition" required>
                            <option value="">เลือกหมวดหมู่</option>
                            <?php foreach($data['categories'] as $cat): ?>
                                <option value="<?= $cat->id ?>"><?= htmlspecialchars($cat->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">รายละเอียดธุรกิจ</label>
                    <textarea name="description" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-primary-500 focus:border-primary-500 transition" rows="4" required placeholder="บรรยายเกี่ยวกับธุรกิจ จุดเด่น สินค้า หรือบริการของคุณ..."></textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">ที่อยู่ / ตำแหน่ง</label>
                    <input type="text" name="address" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-primary-500 focus:border-primary-500 transition mb-4" required placeholder="ระบุที่อยู่ เลขที่บ้าน ถนน ซอย...">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">ละติจูด (Latitude)</label>
                            <input type="number" step="any" name="latitude" id="latInput" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">ลองจิจูด (Longitude)</label>
                            <input type="number" step="any" name="longitude" id="lngInput" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm" required>
                        </div>
                    </div>

                    <div class="flex gap-2 mb-4">
                        <button type="button" class="bg-primary-50 text-primary-600 px-4 py-2 rounded-lg text-sm font-bold hover:bg-primary-100 transition flex items-center" onclick="getLocation()">
                            <i class="fas fa-location-crosshairs mr-2"></i> ระบุตำแหน่งปัจจุบัน
                        </button>
                        <button type="button" class="bg-blue-50 text-blue-600 px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-100 transition flex items-center" id="btnShowMap">
                            <i class="fas fa-map-marker-alt mr-2"></i> เลือกบนแผนที่
                        </button>
                    </div>

                    <div id="locationPickerMap" class="border border-gray-200 rounded-xl overflow-hidden d-none mb-2" style="height: 350px;"></div>
                    <p id="mapHelpText" class="text-xs text-blue-500 d-none"><i class="fas fa-info-circle mr-1"></i> คลิกที่แผนที่หรือลากหมุดเพื่อระบุพิกัดที่ถูกต้อง</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">เบอร์โทรศัพท์ติดต่อ</label>
                        <input type="text" name="phone" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-primary-500 focus:border-primary-500 transition" placeholder="เช่น 02-123-4567">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">เว็บไซต์ / ลิงก์ธุรกิจ</label>
                        <input type="url" name="website" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-primary-500 focus:border-primary-500 transition" placeholder="https://www.facebook.com/yourpage">
                    </div>
                </div>

                <div class="mb-8 pt-6 border-t border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-share-nodes text-primary-500 mr-2"></i> ช่องทางการติดต่อโซเชียลมีเดีย
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 mb-1">LINE ID</label>
                            <input type="text" name="line" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm" placeholder="@lineid">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 mb-1">Facebook URL</label>
                            <input type="url" name="facebook" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm" placeholder="https://facebook.com/page">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 mb-1">Instagram URL</label>
                            <input type="url" name="instagram" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm" placeholder="https://instagram.com/user">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 mb-1">TikTok URL</label>
                            <input type="url" name="tiktok" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm" placeholder="https://tiktok.com/@user">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">รูปภาพหน้าปก</label>
                        <div id="coverFrame" class="relative mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-primary-400 transition cursor-pointer bg-gray-50/50 overflow-hidden min-h-[160px]" onclick="document.getElementById('fileInput').click()">
                            <div id="coverPlaceholder" class="space-y-1 text-center">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                <div class="flex text-sm text-gray-600">
                                    <span class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500">อัปโหลดรูปหน้าปก</span>
                                </div>
                                <p class="text-xs text-gray-400">PNG, JPG ไม่เกิน 5MB</p>
                            </div>
                            <img id="coverPreview" class="hidden absolute inset-0 w-full h-full object-cover">
                            <div id="coverOverlay" class="hidden absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 hover:opacity-100 transition">
                                <i class="fas fa-sync text-white text-2xl"></i>
                            </div>
                        </div>
                        <input id="fileInput" name="cover_image" type="file" accept="image/*" onchange="handlePreview(this, 'coverPreview', 'coverPlaceholder', 'coverOverlay', 'หน้าปก')" style="position:absolute;opacity:0;width:0.1px;height:0.1px;overflow:hidden;pointer-events:none;">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">LINE QR Code</label>
                        <div id="qrFrame" class="relative mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-green-400 transition cursor-pointer bg-gray-50/50 overflow-hidden min-h-[160px]" onclick="document.getElementById('lineQrInput').click()">
                            <div id="qrPlaceholder" class="space-y-1 text-center">
                                <i class="fas fa-qrcode text-gray-400 text-3xl mb-2"></i>
                                <div class="flex text-sm text-gray-600">
                                    <span class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500">อัปโหลด QR Code</span>
                                </div>
                                <p class="text-xs text-gray-400">รูปภาพสำหรับเพิ่มเพื่อน</p>
                            </div>
                            <img id="qrPreview" class="hidden absolute inset-0 w-full h-full object-contain p-2">
                            <div id="qrOverlay" class="hidden absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 hover:opacity-100 transition">
                                <i class="fas fa-sync text-white text-2xl"></i>
                            </div>
                        </div>
                        <input id="lineQrInput" name="line_qr" type="file" accept="image/*" onchange="handlePreview(this, 'qrPreview', 'qrPlaceholder', 'qrOverlay', 'LINE QR')" style="position:absolute;opacity:0;width:0.1px;height:0.1px;overflow:hidden;pointer-events:none;">
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                    <a href="<?= BASE_URL ?>/dashboard" class="text-gray-500 hover:text-gray-700 font-bold px-4">ยกเลิก</a>
                    <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-8 py-3 rounded-xl font-bold transition shadow-lg shadow-primary-500/30">
                        ส่งข้อมูลอนุมัติ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let pickerMap, pickerMarker;

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            document.getElementById('latInput').value = lat.toFixed(6);
            document.getElementById('lngInput').value = lng.toFixed(6);
            
            if(pickerMap) {
                updateMarker(lat, lng);
                pickerMap.setView([lat, lng], 16);
            }
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}

document.getElementById('btnShowMap').addEventListener('click', function() {
    const mapDiv = document.getElementById('locationPickerMap');
    const helpText = document.getElementById('mapHelpText');
    mapDiv.classList.remove('d-none');
    helpText.classList.remove('d-none');
    this.classList.add('hidden'); // Tailwind use hidden

    initPickerMap();
    // Force leaflet to resize properly when shown from d-none
    setTimeout(() => { pickerMap.invalidateSize(); }, 200);
});

function initPickerMap() {
    if(pickerMap) return;

    // Default to Rangsit center
    const defaultLat = 13.9840;
    const defaultLng = 100.6125;
    
    const latVal = parseFloat(document.getElementById('latInput').value) || defaultLat;
    const lngVal = parseFloat(document.getElementById('lngInput').value) || defaultLng;

    pickerMap = L.map('locationPickerMap').setView([latVal, lngVal], 14);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(pickerMap);

    // Initial Marker
    pickerMarker = L.marker([latVal, lngVal], {
        draggable: true
    }).addTo(pickerMap);

    // Update inputs on drag end
    pickerMarker.on('dragend', function(event) {
        const position = pickerMarker.getLatLng();
        updateInputs(position.lat, position.lng);
    });

    // Update marker and inputs on map click
    pickerMap.on('click', function(e) {
        updateMarker(e.latlng.lat, e.latlng.lng);
        updateInputs(e.latlng.lat, e.latlng.lng);
    });

    // Handle manual input changes
    document.getElementById('latInput').addEventListener('change', () => syncMapFromInputs());
    document.getElementById('lngInput').addEventListener('change', () => syncMapFromInputs());
}

function updateMarker(lat, lng) {
    if(pickerMarker) {
        pickerMarker.setLatLng([lat, lng]);
    }
}

function updateInputs(lat, lng) {
    document.getElementById('latInput').value = lat.toFixed(6);
    document.getElementById('lngInput').value = lng.toFixed(6);
}

function syncMapFromInputs() {
    const lat = parseFloat(document.getElementById('latInput').value);
    const lng = parseFloat(document.getElementById('lngInput').value);
    if(!isNaN(lat) && !isNaN(lng)) {
        updateMarker(lat, lng);
        pickerMap.panTo([lat, lng]);
    }
}

function handlePreview(input, previewId, placeholderId, overlayId, label) {
    if (!input.files || !input.files[0]) return;

    const file = input.files[0];
    const ext  = file.name.split('.').pop().toLowerCase();

    if (ext === 'eps') {
        Swal.fire('ไม่รองรับ', 'ไฟล์ .eps ไม่สามารถประมวลผลได้บนเบราว์เซอร์ กรุณาใช้ .jpg, .png หรือ .heic', 'error');
        input.value = '';
        return;
    }

    const fileMB    = file.size / (1024 * 1024);
    const objectUrl = URL.createObjectURL(file); // สร้างใน user-gesture context ทันที

    function doProcess() {
        Swal.fire({ title: 'กำลังประมวลผลรูปภาพ...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        const img = new Image();

        img.onerror = function() {
            URL.revokeObjectURL(objectUrl);
            Swal.fire('ข้อผิดพลาด', 'ไม่สามารถโหลดรูปภาพได้ อาจเป็นรูปแบบที่เบราว์เซอร์ไม่รองรับในอุปกรณ์นี้', 'error');
            input.value = '';
        };

        img.onload = function() {
            const canvas = document.createElement('canvas');
            let width  = img.width;
            let height = img.height;
            const max_size = label === 'หน้าปก' ? 1200 : 800;

            if (width > height) {
                if (width > max_size) { height *= max_size / width; width = max_size; }
            } else {
                if (height > max_size) { width *= max_size / height; height = max_size; }
            }

            canvas.width  = Math.round(width);
            canvas.height = Math.round(height);
            canvas.getContext('2d').drawImage(img, 0, 0, canvas.width, canvas.height);
            URL.revokeObjectURL(objectUrl);

            const MAX_BYTES = 800 * 1024;
            let quality = 0.82;

            function tryBlob() {
                canvas.toBlob(function(blob) {
                    if (blob.size > MAX_BYTES && quality > 0.3) {
                        quality = Math.max(quality - 0.1, 0.3);
                        tryBlob();
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        if (label === 'หน้าปก') {
                            document.getElementById('coverBase64').value = ev.target.result;
                        } else {
                            document.getElementById('qrBase64').value = ev.target.result;
                        }
                        input.value = ''; // ล้างไฟล์ต้นฉบับ ไม่ให้ส่งซ้ำกับ base64
                    };
                    reader.readAsDataURL(blob);

                    document.getElementById(previewId).src = URL.createObjectURL(blob);
                    document.getElementById(previewId).classList.remove('hidden');
                    document.getElementById(placeholderId).classList.add('hidden');
                    document.getElementById(overlayId).classList.remove('hidden');

                    const kb = Math.round(blob.size / 1024);
                    Swal.fire({
                        icon: 'success',
                        title: 'เตรียมรูป' + label + 'แล้ว',
                        text: `ปรับขนาดเสร็จสิ้น · ขนาดไฟล์: ${kb} KB`,
                        timer: 2000, showConfirmButton: false, toast: true, position: 'top-end'
                    });
                }, 'image/jpeg', quality);
            }
            tryBlob();
        };

        img.src = objectUrl;
    }

    if (fileMB > 2) {
        Swal.fire({
            icon: 'warning',
            title: 'ขนาดไฟล์ใหญ่เกินไป',
            text: `ไฟล์มีขนาด ${fileMB.toFixed(1)} MB ระบบจะทำการลดขนาดเพื่อให้สามารถอัปโหลดได้`,
            showCancelButton: true,
            confirmButtonText: 'OK ดำเนินการต่อ',
            cancelButtonText: 'ยกเลิก',
            confirmButtonColor: '#2795F5',
        }).then(result => {
            if (result.isConfirmed) doProcess();
            else { URL.revokeObjectURL(objectUrl); input.value = ''; }
        });
    } else {
        doProcess();
    }
}

document.getElementById('addPlaceForm').onsubmit = function() {
    Swal.fire({
        title: 'กำลังส่งข้อมูล...',
        text: 'กรุณารอสักครู่ ระบบกำลังประมวลผลข้อมูลและรูปภาพ',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
};
</script>

<!-- CSS fix for Leaflet d-none -->
<style>.d-none { display: none !important; }</style>
<!-- Leaflet JS needed for picker -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>