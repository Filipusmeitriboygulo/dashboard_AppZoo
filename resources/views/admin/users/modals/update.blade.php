<!-- Modal Edit User -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editUserForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="editUserModalLabel">
                        <i class="fas fa-edit me-2"></i> Edit Data Pengguna
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Nama -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="name" id="edit_name" class="form-control"
                                    placeholder="Nama Lengkap" required>
                                <label for="edit_name">Nama Lengkap</label>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" name="email" id="edit_email" class="form-control"
                                    placeholder="Alamat Email" required>
                                <label for="edit_email">Alamat Email</label>
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="role" id="edit_role" class="form-select" required>
                                    <option value="">Pilih Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="kepala_upa">Kepala UPA</option>
                                    <option value="ketua_jurusan">Ketua Jurusan</option>
                                    <option value="ketua_prodi">Ketua Prodi</option>
                                    <option value="wakil_direktur">Wakil Direktur</option>
                                </select>
                                <label for="edit_role">Role Pengguna</label>
                            </div>
                        </div>

                        <!-- Status Aktif -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="is_active" id="edit_is_active" class="form-select">
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                                <label for="edit_is_active">Status Akun</label>
                            </div>
                        </div>

                        <!-- Jurusan (Conditional) -->
                        <div class="col-md-6 edit-role-dependent" style="display: none;">
                            <div class="form-floating">
                                <select name="department_id" id="edit_department_id" class="form-select">
                                    <option value="">Pilih Jurusan</option>
                                    @foreach ($departments as $d)
                                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                                <label for="edit_department_id">Jurusan</label>
                            </div>
                        </div>

                        <!-- Prodi (Conditional) -->
                        <div class="col-md-6 edit-role-dependent" style="display: none;">
                            <div class="form-floating">
                                <select name="study_program_id" id="edit_study_program_id" class="form-select">
                                    <option value="">Pilih Program Studi</option>
                                    @foreach ($studyPrograms as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                                <label for="edit_study_program_id">Program Studi</label>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="password" name="password" id="edit_password" class="form-control"
                                    placeholder="Password">
                                <label for="edit_password">Password</label>
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah password</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script untuk Modal Edit -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tangkap semua tombol edit
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function() {
                // Ambil data dari atribut
                const userId = this.getAttribute('data-user-id');
                const userData = JSON.parse(this.getAttribute('data-user'));

                // Isi form modal
                document.getElementById('edit_name').value = userData.name;
                document.getElementById('edit_email').value = userData.email;
                document.getElementById('edit_role').value = userData.role;
                document.getElementById('edit_is_active').value = userData.is_active ? '1' :
                '0';

                // Isi relasi jika ada
                if (userData.department_id) {
                    document.getElementById('edit_department_id').value = userData
                    .department_id;
                }
                if (userData.study_program_id) {
                    document.getElementById('edit_study_program_id').value = userData
                        .study_program_id;
                }

                // Set action form dengan ID yang benar
                document.getElementById('editUserForm').action = `/admin/users/${userId}`;

                // Tampilkan field sesuai role
                toggleRoleFields(userData.role);
            });
        });

        // Handle perubahan role di form
        document.getElementById('edit_role')?.addEventListener('change', function() {
            toggleRoleFields(this.value);
        });

        // Fungsi untuk toggle field berdasarkan role
        function toggleRoleFields(role) {
            const showFields = ['ketua_jurusan', 'ketua_prodi'];
            document.querySelectorAll('.edit-role-dependent').forEach(el => {
                el.style.display = showFields.includes(role) ? 'block' : 'none';
            });
        }
    });
</script>
