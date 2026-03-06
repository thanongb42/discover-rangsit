<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>

<!-- Flatpickr for Date Picker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>

<div class="mb-8 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">ประวัติการใช้งานระบบ</h1>
        <p class="text-gray-500 text-sm">ตรวจสอบบันทึกกิจกรรมของผู้ใช้งานและการเข้าถึงหน้าต่างๆ ในระบบ</p>
    </div>
    <div class="text-right">
        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">ค้นพบทั้งหมด</span>
        <p class="text-xl font-black text-navy-800"><?= number_format($data['totalLogs']) ?> รายการ</p>
    </div>
</div>

<!-- Filters Section -->
<div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 mb-8">
    <form action="<?= BASE_URL ?>/admin/logs" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">ประเภทกิจกรรม</label>
            <select name="action" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-primary-500 focus:border-primary-500 transition">
                <option value="">ทั้งหมด (All Events)</option>
                <?php foreach($data['actions'] as $act): ?>
                    <option value="<?= $act->action ?>" <?= $data['filters']['action'] == $act->action ? 'selected' : '' ?>>
                        <?= $act->action ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">เริ่มจากวันที่/เวลา</label>
            <div class="relative">
                <input type="text" name="start_date" id="start_date" value="<?= $data['filters']['start_date'] ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm" placeholder="เลือกวันเริ่มต้น">
                <i class="fas fa-calendar absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 pointer-events-none"></i>
            </div>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">ถึงวันที่/เวลา</label>
            <div class="relative">
                <input type="text" name="end_date" id="end_date" value="<?= $data['filters']['end_date'] ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm" placeholder="เลือกวันสิ้นสุด">
                <i class="fas fa-calendar absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 pointer-events-none"></i>
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 text-white font-bold py-2 rounded-xl transition shadow-lg flex items-center justify-center" style="background-color: #2795F5; box-shadow: 0 10px 15px -3px rgba(39, 149, 245, 0.3);">
                <i class="fas fa-search mr-2"></i> ค้นหา
            </button>
            <a href="<?= BASE_URL ?>/admin/logs" class="bg-gray-100 hover:bg-gray-200 text-gray-500 font-bold py-2 px-4 rounded-xl transition flex items-center">
                <i class="fas fa-sync-alt"></i>
            </a>
        </div>
    </form>
</div>

<!-- Table Section -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase w-48">วัน-เวลา (ไทย)</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">ผู้ใช้งาน</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">กิจกรรม</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">รายละเอียด</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if(empty($data['logs'])): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center text-gray-400 italic">
                            ไม่พบข้อมูลประวัติการใช้งานในช่วงที่กำหนด
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['logs'] as $log): ?>
                        <tr class="hover:bg-gray-50/80 transition-colors text-xs">
                            <td class="px-6 py-4 text-gray-500 font-medium">
                                <?php 
                                    $date = new DateTime($log->created_at);
                                    $thai_year = $date->format('Y') + 543;
                                    echo $date->format('d/m/') . $thai_year . ' ' . $date->format('H:i:s');
                                ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if($log->user_id): ?>
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 rounded-full bg-slate-100 text-[10px] flex items-center justify-center mr-2 border border-slate-200">
                                            <?= strtoupper(substr($log->username, 0, 1)) ?>
                                        </div>
                                        <span class="font-bold text-gray-700"><?= htmlspecialchars($log->username) ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-gray-300 italic">Guest / System</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php 
                                    $bg = 'bg-gray-100 text-gray-600';
                                    if(strpos($log->action, 'LOGIN') !== false) $bg = 'bg-blue-50 text-blue-600';
                                    if(strpos($log->action, 'DELETE') !== false) $bg = 'bg-red-50 text-red-600';
                                    if(strpos($log->action, 'UPDATE') !== false) $bg = 'bg-yellow-50 text-yellow-600';
                                    if(strpos($log->action, 'APPROVE') !== false) $bg = 'bg-green-50 text-green-600';
                                    if(strpos($log->action, 'ACCESS') !== false) $bg = 'bg-slate-50 text-slate-400';
                                ?>
                                <span class="px-2 py-1 rounded text-[9px] font-black border <?= $bg ?>">
                                    <?= $log->action ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="<?= htmlspecialchars($log->description) ?>">
                                <?= htmlspecialchars($log->description) ?>
                            </td>
                            <td class="px-6 py-4 text-gray-400 font-mono">
                                <?= $log->ip_address ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination Section -->
<?php if($data['totalPages'] > 1): ?>
    <div class="flex justify-center items-center gap-2 pb-10">
        <?php if($data['currentPage'] > 1): ?>
            <a href="?page=1<?= !empty($data['filters']['action']) ? '&action='.$data['filters']['action'] : '' ?><?= !empty($data['filters']['start_date']) ? '&start_date='.$data['filters']['start_date'] : '' ?><?= !empty($data['filters']['end_date']) ? '&end_date='.$data['filters']['end_date'] : '' ?>" 
               class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-gray-200 text-gray-400 hover:bg-gray-50 transition">
                <i class="fas fa-angle-double-left"></i>
            </a>
            <a href="?page=<?= $data['currentPage'] - 1 ?><?= !empty($data['filters']['action']) ? '&action='.$data['filters']['action'] : '' ?><?= !empty($data['filters']['start_date']) ? '&start_date='.$data['filters']['start_date'] : '' ?><?= !empty($data['filters']['end_date']) ? '&end_date='.$data['filters']['end_date'] : '' ?>" 
               class="px-4 h-10 flex items-center justify-center rounded-xl bg-white border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition">
                ก่อนหน้า
            </a>
        <?php endif; ?>

        <div class="flex gap-1">
            <?php 
                $start = max(1, $data['currentPage'] - 2);
                $end = min($data['totalPages'], $data['currentPage'] + 2);
                for($i = $start; $i <= $end; $i++): 
            ?>
                <a href="?page=<?= $i ?><?= !empty($data['filters']['action']) ? '&action='.$data['filters']['action'] : '' ?><?= !empty($data['filters']['start_date']) ? '&start_date='.$data['filters']['start_date'] : '' ?><?= !empty($data['filters']['end_date']) ? '&end_date='.$data['filters']['end_date'] : '' ?>" 
                   class="w-10 h-10 flex items-center justify-center rounded-xl text-sm font-bold transition <?= $i == $data['currentPage'] ? 'text-white shadow-lg' : 'bg-white border border-gray-200 text-gray-400 hover:bg-gray-50' ?>"
                   style="<?= $i == $data['currentPage'] ? 'background-color: #2795F5; box-shadow: 0 10px 15px -3px rgba(39, 149, 245, 0.3);' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>

        <?php if($data['currentPage'] < $data['totalPages']): ?>
            <a href="?page=<?= $data['currentPage'] + 1 ?><?= !empty($data['filters']['action']) ? '&action='.$data['filters']['action'] : '' ?><?= !empty($data['filters']['start_date']) ? '&start_date='.$data['filters']['start_date'] : '' ?><?= !empty($data['filters']['end_date']) ? '&end_date='.$data['filters']['end_date'] : '' ?>" 
               class="px-4 h-10 flex items-center justify-center rounded-xl bg-white border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition">
                ถัดไป
            </a>
            <a href="?page=<?= $data['totalPages'] ?><?= !empty($data['filters']['action']) ? '&action='.$data['filters']['action'] : '' ?><?= !empty($data['filters']['start_date']) ? '&start_date='.$data['filters']['start_date'] : '' ?><?= !empty($data['filters']['end_date']) ? '&end_date='.$data['filters']['end_date'] : '' ?>" 
               class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-gray-200 text-gray-400 hover:bg-gray-50 transition">
                <i class="fas fa-angle-double-right"></i>
            </a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<script>
    // Initialize Flatpickr Thai locale
    flatpickr.localize(flatpickr.l10ns.th);
    
    const fpConfig = {
        enableTime: true,
        dateFormat: "Y-m-d H:i:S",
        time_24hr: true,
        locale: {
            firstDayOfWeek: 1
        }
    };

    flatpickr("#start_date", fpConfig);
    flatpickr("#end_date", fpConfig);
</script>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>