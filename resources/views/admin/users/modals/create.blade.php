<div class="modal fade" id="tambahUserModal" tabindex="-1" aria-labelledby="tambahUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title" id="tambahUserModalLabel">
                        <i class="fas fa-user-plus me-2"></i> Tambah Pengguna Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- Nama -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="Nama Lengkap" required>
                                <label for="name" class="form-label">Nama Lengkap</label>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="Alamat Email" required>
                                <label for="email" class="form-label">Alamat Email</label>
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="role" id="role" class="form-select" required>
                                    <option value="">Pilih Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="kepala_upa">Kepala UPA</option>
                                    <option value="ketua_jurusan">Ketua Jurusan</option>
                                    <option value="ketua_prodi">Ketua Prodi</option>
                                    <option value="wakil_direktur">Wakil Direktur</option>
                                </select>
                                <label for="role" class="form-label">Role Pengguna</label>
                            </div>
                        </div>

                        <!-- Status Aktif -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="is_active" id="is_active" class="form-select">
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                                <label for="is_active" class="form-label">Status Akun</label>
                            </div>
                        </div>

                        <!-- Jurusan (Conditional) -->
                        <div class="col-md-6 role-dependent" style="display: none;">
                            <div class="form-floating">
                                <select name="department_id" id="department_id" class="form-select">
                                    <option value="">Pilih Jurusan</option>
                                    @foreach ($departments as $d)
                                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                                <label for="department_id" class="form-label">Jurusan</label>
                            </div>
                        </div>

                        <!-- Prodi (Conditional) -->
                        <div class="col-md-6 role-dependent" style="display: none;">
                            <div class="form-floating">
                                <select name="study_program_id" id="study_program_id" class="form-select">
                                    <option value="">Pilih Program Studi</option>
                                    @foreach ($studyPrograms as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                                <label for="study_program_id" class="form-label">Program Studi</label>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Password">
                                <label for="password" class="form-label">Password</label>
                                <small class="text-muted">Biarkan kosong untuk menggunakan password default:
                                    'password'</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script untuk menampilkan jurusan dan prodi sesuai role -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const dependentFields = document.querySelectorAll('.role-dependent');

        roleSelect?.addEventListener('change', function() {
            const role = this.value;
            const showFields = ['ketua_jurusan', 'ketua_prodi'];

            dependentFields.forEach(el => {
                el.style.display = showFields.includes(role) ? 'block' : 'none';

                // Add required attribute when visible
                const select = el.querySelector('select');
                if (select) {
                    select.required = showFields.includes(role);
                }
            });
        });

        // Initialize password field with a random password generator option
        const passwordField = document.getElementById('password');
        const generatePasswordBtn = document.createElement('button');
        generatePasswordBtn.type = 'button';
        generatePasswordBtn.className = 'btn btn-sm btn-outline-secondary mt-2';
        generatePasswordBtn.innerHTML = '<i class="fas fa-random me-1"></i> Generate Password';
        generatePasswordBtn.onclick = function() {
            const randomString = Math.random().toString(36).slice(-8);
            passwordField.value = randomString;
        };
        passwordField.parentNode.appendChild(generatePasswordBtn);
    });
</script>
