<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">จัดการผู้ใช้งาน</h1>
    <p class="text-gray-500 text-sm">ดูแลสิทธิ์การเข้าถึงและบทบาทของบุคลากรและเจ้าของธุรกิจในระบบ</p>
</div>

<?php if(isset($_SESSION['success'])): ?>
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 shadow-sm rounded-r-lg">
        <p class="text-sm text-green-700"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    </div>
<?php endif; ?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">ผู้ใช้งาน</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">บทบาท</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">วันที่เข้าร่วม</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach($data['users'] as $user): ?>
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-bold mr-3 border border-primary-200">
                                    <?= strtoupper(substr($user->name, 0, 1)) ?>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800"><?= htmlspecialchars($user->name) ?></p>
                                    <p class="text-xs text-gray-400"><?= htmlspecialchars($user->email) ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <form action="<?= BASE_URL ?>/admin/users/update-role" method="POST" class="flex items-center">
                                <input type="hidden" name="user_id" value="<?= $user->id ?>">
                                <select name="role_id" onchange="this.form.submit()" class="text-sm bg-gray-50 border border-gray-200 rounded-lg px-2 py-1.5 focus:ring-primary-500 focus:border-primary-500">
                                    <?php foreach($data['roles'] as $role): ?>
                                        <option value="<?= $role->id ?>" <?= $user->role_id == $role->id ? 'selected' : '' ?>>
                                            <?= ucfirst($role->role_name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-400">
                            <?= date('d/m/Y', strtotime($user->created_at)) ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if($user->id != $_SESSION['user_id']): ?>
                                <form action="<?= BASE_URL ?>/admin/users/delete" method="POST" onsubmit="return confirm('ยืนยันการลบผู้ใช้งานนี้?')">
                                    <input type="hidden" name="user_id" value="<?= $user->id ?>">
                                    <button class="text-gray-400 hover:text-red-500 transition-colors p-2">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-xs font-bold border border-blue-100">คุณ</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>