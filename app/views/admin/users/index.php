<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>

<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">จัดการผู้ใช้งาน</h1>
        <p class="text-gray-500 text-sm">ดูแลสิทธิ์การเข้าถึงและบทบาทของบุคลากรและเจ้าของธุรกิจในระบบ</p>
    </div>
    <button onclick="openAddUserModal()" class="bg-primary-500 hover:bg-primary-600 text-white px-5 py-2.5 rounded-xl font-bold transition shadow-lg shadow-primary-500/30 flex items-center">
        <i class="fas fa-user-plus mr-2"></i> เพิ่มผู้ใช้งาน
    </button>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl">
                <i class="fas fa-users"></i>
            </div>
            <span class="text-xs font-bold text-gray-400 uppercase">ทั้งหมด</span>
        </div>
        <h3 class="text-2xl font-bold text-gray-800"><?= number_format($data['stats']->total_users) ?></h3>
        <p class="text-gray-400 text-xs mt-1">ผู้ใช้งานในระบบ</p>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-xl">
                <i class="fas fa-user-shield"></i>
            </div>
            <span class="text-xs font-bold text-gray-400 uppercase">แอดมิน</span>
        </div>
        <h3 class="text-2xl font-bold text-gray-800"><?= number_format($data['stats']->admin_count) ?></h3>
        <p class="text-gray-400 text-xs mt-1">ผู้ดูแลระบบสูงสุด</p>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center text-xl">
                <i class="fas fa-user-tie"></i>
            </div>
            <span class="text-xs font-bold text-gray-400 uppercase">เจ้าหน้าที่</span>
        </div>
        <h3 class="text-2xl font-bold text-gray-800"><?= number_format($data['stats']->staff_count) ?></h3>
        <p class="text-gray-400 text-xs mt-1">เจ้าหน้าที่เทศบาล</p>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-xl">
                <i class="fas fa-check-circle"></i>
            </div>
            <span class="text-xs font-bold text-gray-400 uppercase">ปกติ</span>
        </div>
        <h3 class="text-2xl font-bold text-gray-800"><?= number_format($data['stats']->active_count) ?></h3>
        <p class="text-gray-400 text-xs mt-1">สถานะพร้อมใช้งาน</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="usersTable">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">ชื่อผู้ใช้งาน / อีเมล</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">ชื่อ-นามสกุล</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">บทบาท</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center">สถานะ</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center w-48">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="userTableBody">
                <?php foreach($data['users'] as $user): ?>
                    <tr class="hover:bg-gray-50/80 transition-colors" id="user-row-<?= $user->user_id ?>">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold mr-3 border border-slate-200 overflow-hidden">
                                    <?php if($user->profile_image): ?>
                                        <img src="<?= BASE_URL ?>/../<?= $user->profile_image ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <?= strtoupper(substr($user->username, 0, 1)) ?>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800"><?= htmlspecialchars($user->username) ?></p>
                                    <p class="text-[10px] text-gray-400"><?= htmlspecialchars($user->email) ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-700"><?= htmlspecialchars($user->prefix_name . $user->first_name . ' ' . $user->last_name) ?></p>
                            <p class="text-[10px] text-gray-400"><?= htmlspecialchars($user->department_name ?: 'ไม่ระบุสังกัด') ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <?php 
                                $role_class = 'bg-gray-100 text-gray-600';
                                if($user->role == 'admin') $role_class = 'bg-blue-50 text-blue-600 border-blue-100';
                                if($user->role == 'staff') $role_class = 'bg-purple-50 text-purple-600 border-purple-100';
                            ?>
                            <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase border <?= $role_class ?>">
                                <?= $user->role ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php 
                                $status_class = 'bg-gray-100 text-gray-600';
                                if($user->status == 'active') $status_class = 'bg-green-50 text-green-600 border-green-100';
                                if($user->status == 'inactive') $status_class = 'bg-red-50 text-red-600 border-red-100';
                                if($user->status == 'suspended') $status_class = 'bg-yellow-50 text-yellow-600 border-yellow-100';
                            ?>
                            <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase border <?= $status_class ?>">
                                <?= $user->status ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-1">
                                <button onclick="viewUser(<?= $user->user_id ?>)" class="text-gray-400 hover:text-blue-500 p-2 transition" title="ดูข้อมูล">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="resetPassword(<?= $user->user_id ?>)" class="text-gray-400 hover:text-yellow-500 p-2 transition" title="รีเซ็ตรหัสผ่าน">
                                    <i class="fas fa-key"></i>
                                </button>
                                <button onclick="editUser(<?= $user->user_id ?>)" class="text-gray-400 hover:text-primary-600 p-2 transition" title="แก้ไข">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <?php if($user->user_id != $_SESSION['user_id']): ?>
                                    <button onclick="deleteUser(<?= $user->user_id ?>)" class="text-gray-400 hover:text-red-500 p-2 transition" title="ลบ">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- User Modal (Add/Edit) -->
<div id="userModal" class="hidden fixed inset-0 bg-black/50 z-[1002] flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl overflow-hidden animate-fade-in">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800" id="userModalTitle">เพิ่มผู้ใช้งาน</h3>
            <button onclick="closeUserModal()" class="text-gray-400 hover:text-gray-600 p-2">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-8 max-h-[80vh] overflow-y-auto">
            <form id="userForm" class="space-y-6">
                <input type="hidden" name="id" id="userIdField">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">ชื่อผู้ใช้งาน (Username)</label>
                        <input type="text" name="username" id="userUsernameField" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">อีเมล</label>
                        <input type="email" name="email" id="userEmailField" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 transition" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">คำนำหน้า</label>
                        <select name="prefix_id" id="userPrefixField" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5">
                            <option value="">เลือก</option>
                            <?php foreach($data['prefixes'] as $p): ?>
                                <option value="<?= $p->prefix_id ?>"><?= $p->prefix_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">ชื่อ</label>
                        <input type="text" name="first_name" id="userFirstNameField" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">นามสกุล</label>
                        <input type="text" name="last_name" id="userLastNameField" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div id="passwordWrapper">
                        <label class="block text-sm font-bold text-gray-700 mb-1">รหัสผ่าน</label>
                        <input type="password" name="password" id="userPasswordField" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5">
                        <p id="passwordHint" class="text-[10px] text-gray-400 mt-1"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">เบอร์โทรศัพท์</label>
                        <input type="text" name="phone" id="userPhoneField" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">สังกัด/กอง/สำนัก</label>
                        <select name="department_id" id="userDeptField" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5">
                            <option value="">เลือกสังกัด</option>
                            <?php foreach($data['departments'] as $d): ?>
                                <option value="<?= $d->department_id ?>"><?= $d->department_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">ตำแหน่ง</label>
                        <input type="text" name="position" id="userPositionField" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">บทบาท</label>
                        <select name="role" id="userRoleField" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5" required>
                            <option value="user">ผู้ใช้ทั่วไป (User)</option>
                            <option value="staff">เจ้าหน้าที่ (Staff)</option>
                            <option value="admin">ผู้ดูแลระบบ (Admin)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">สถานะ</label>
                        <select name="status" id="userStatusField" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5" required>
                            <option value="active">ปกติ (Active)</option>
                            <option value="inactive">ไม่ใช้งาน (Inactive)</option>
                            <option value="suspended">ระงับการใช้งาน (Suspended)</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="closeUserModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-3 rounded-xl transition">ยกเลิก</button>
                    <button type="submit" class="flex-1 bg-primary-500 hover:bg-primary-600 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-primary-500/20">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openAddUserModal() {
        document.getElementById('userModalTitle').textContent = 'เพิ่มผู้ใช้งานใหม่';
        document.getElementById('userForm').reset();
        document.getElementById('userIdField').value = '';
        document.getElementById('userUsernameField').disabled = false;
        document.getElementById('userEmailField').disabled = false;
        document.getElementById('passwordWrapper').classList.remove('hidden');
        document.getElementById('userPasswordField').required = true;
        document.getElementById('passwordHint').textContent = '';
        document.getElementById('userModal').classList.remove('hidden');
    }

    function closeUserModal() {
        document.getElementById('userModal').classList.add('hidden');
    }

    async function editUser(id) {
        try {
            const response = await fetch(`<?= BASE_URL ?>/api/admin/users/get/${id}`);
            const res = await response.json();
            if (res.success) {
                const user = res.data;
                document.getElementById('userModalTitle').textContent = 'แก้ไขข้อมูลผู้ใช้งาน';
                document.getElementById('userIdField').value = user.user_id;
                document.getElementById('userUsernameField').value = user.username;
                document.getElementById('userUsernameField').disabled = true;
                document.getElementById('userEmailField').value = user.email;
                document.getElementById('userEmailField').disabled = true;
                
                document.getElementById('userPrefixField').value = user.prefix_id || '';
                document.getElementById('userFirstNameField').value = user.first_name;
                document.getElementById('userLastNameField').value = user.last_name;
                document.getElementById('userPhoneField').value = user.phone;
                document.getElementById('userDeptField').value = user.department_id || '';
                document.getElementById('userPositionField').value = user.position;
                document.getElementById('userRoleField').value = user.role;
                document.getElementById('userStatusField').value = user.status;
                
                document.getElementById('passwordWrapper').classList.remove('hidden');
                document.getElementById('userPasswordField').required = false;
                document.getElementById('passwordHint').textContent = 'เว้นว่างไว้หากไม่ต้องการเปลี่ยนรหัสผ่าน';
                
                document.getElementById('userModal').classList.remove('hidden');
            }
        } catch (error) {
            Swal.fire('Error', 'ไม่สามารถดึงข้อมูลได้', 'error');
        }
    }

    document.getElementById('userForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('userIdField').value;
        const url = id ? '<?= BASE_URL ?>/api/admin/users/update' : '<?= BASE_URL ?>/api/admin/users/add';
        const formData = new FormData(this);
        
        try {
            const response = await fetch(url, { method: 'POST', body: formData });
            const res = await response.json();
            if (res.success) {
                Swal.fire({ icon: 'success', title: 'สำเร็จ', text: res.message, timer: 1500, showConfirmButton: false });
                closeUserModal();
                setTimeout(() => location.reload(), 1500);
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        } catch (error) {
            Swal.fire('Error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'error');
        }
    });

    async function deleteUser(id) {
        const result = await Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "ข้อมูลผู้ใช้จะถูกลบออกจากระบบอย่างถาวร!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'ยืนยันการลบ',
            cancelButtonText: 'ยกเลิก'
        });

        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id', id);
            try {
                const response = await fetch('<?= BASE_URL ?>/api/admin/users/delete', { method: 'POST', body: formData });
                const res = await response.json();
                if (res.success) {
                    Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', text: res.message, timer: 1500, showConfirmButton: false });
                    document.getElementById(`user-row-${id}`).remove();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'ไม่สามารถลบข้อมูลได้', 'error');
            }
        }
    }

    async function resetPassword(id) {
        const { value: password } = await Swal.fire({
            title: 'รีเซ็ตรหัสผ่านใหม่',
            input: 'password',
            inputLabel: 'กำหนดรหัสผ่านใหม่สำหรับผู้ใช้นี้',
            inputPlaceholder: 'รหัสผ่านใหม่',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            inputAttributes: {
                maxlength: 20,
                autocapitalize: 'off',
                autocorrect: 'off'
            }
        });

        if (password) {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('password', password);
            
            try {
                const response = await fetch('<?= BASE_URL ?>/api/admin/users/reset-password', { method: 'POST', body: formData });
                const res = await response.json();
                if (res.success) {
                    Swal.fire('สำเร็จ', res.message, 'success');
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'ไม่สามารถรีเซ็ตรหัสผ่านได้', 'error');
            }
        }
    }

    function viewUser(id) {
        window.location.href = `<?= BASE_URL ?>/admin/users/detail/${id}`;
    }
</script>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>