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
                                <input type="text" name="name" id="name" class="form-control" placeholder="Nama Lengkap" required>
                                <label for="name">Nama Lengkap</label>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" name="email" id="email" class="form-control" placeholder="Alamat Email" required>
                                <label for="email">Alamat Email</label>
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
                                <label for="role">Role Pengguna</label>
                            </div>
                        </div>

                        <!-- Status Aktif -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="is_active" id="is_active" class="form-select">
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                                <label for="is_active">Status Akun</label>
                            </div>
                        </div>

                        <!-- Jurusan -->
                        <div class="col-md-6" id="department-group" style="display: none;">
                            <div class="form-floating">
                                <select name="department_id" id="department_id" class="form-select">
                                    <option value="">Pilih Jurusan</option>
                                    @foreach ($departments as $d)
                                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                                <label for="department_id">Jurusan</label>
                            </div>
                        </div>

                        <!-- Prodi -->
                        <div class="col-md-6" id="study-program-group" style="display: none;">
                            <div class="form-floating">
                                <select name="study_program_id" id="study_program_id" class="form-select">
                                    <option value="">Pilih Program Studi</option>
                                    @foreach ($studyPrograms as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                                <label for="study_program_id">Program Studi</label>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="col-12">
                            <div class="form-floating position-relative">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                                <label for="password">Password</label>
                                <small class="text-muted">Biarkan kosong untuk menggunakan password default: 'password'</small>
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

{{-- Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleSelect = document.getElementById('role');
        const departmentGroup = document.getElementById('department-group');
        const studyProgramGroup = document.getElementById('study-program-group');
        const departmentSelect = document.getElementById('department_id');
        const studyProgramSelect = document.getElementById('study_program_id');

        function updateFieldVisibility() {
            const role = roleSelect.value;

            departmentGroup.style.display = 'none';
            studyProgramGroup.style.display = 'none';
            departmentSelect.required = false;
            studyProgramSelect.required = false;

            if (role === 'ketua_jurusan') {
                departmentGroup.style.display = 'block';
                departmentSelect.required = true;
            } else if (role === 'ketua_prodi') {
                departmentGroup.style.display = 'block';
                studyProgramGroup.style.display = 'block';
                departmentSelect.required = true;
                studyProgramSelect.required = true;
            }
        }

        roleSelect.addEventListener('change', updateFieldVisibility);
        updateFieldVisibility(); // On page load

        // Password Generator
        const passwordField = document.getElementById('password');
        const generatePasswordBtn = document.createElement('button');
        generatePasswordBtn.type = 'button';
        generatePasswordBtn.className = 'btn btn-sm btn-outline-secondary mt-2';
        generatePasswordBtn.innerHTML = '<i class="fas fa-random me-1"></i> Generate Password';
        generatePasswordBtn.onclick = function () {
            const randomString = Math.random().toString(36).slice(-8);
            passwordField.value = randomString;
        };
        passwordField.parentNode.appendChild(generatePasswordBtn);
    });
</script>
