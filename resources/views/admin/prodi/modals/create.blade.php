<div class="modal fade" id="tambahUserModal" tabindex="-1" aria-labelledby="tambahUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="{{ route('admin.prodi.store') }}" id="tambahProdiForm">

                @csrf
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title" id="tambahUserModalLabel">
                        <i class="fas fa-user-plus me-2"></i> Tambah Jurusan Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- Nama Prodi -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="Nama Prodi" required>
                                <label for="name">Nama Prodi</label>
                                <div class="invalid-feedback" id="name-error"></div>
                            </div>
                        </div>

                        <!-- Kode Prodi -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="code" id="code" class="form-control"
                                    placeholder="Kode Prodi" required maxlength="10">
                                <label for="code">Kode Prodi</label>
                                <div class="invalid-feedback" id="code-error"></div>
                            </div>
                        </div>

                        <!-- Pilih Jurusan -->
                        <div class="col-md-12 mt-3">
                            <div class="form-floating">
                                <select name="department_id" id="department_id" class="form-select" required>
                                    <option value="" disabled selected>Pilih Jurusan</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                <label for="department_id">Jurusan</label>
                                <div class="invalid-feedback" id="department-error"></div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="submit-button">
                        <i class="fas fa-save me-1"></i> Simpan Jurusan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('tambahProdiForm');
        const nameInput = document.getElementById('name');
        const codeInput = document.getElementById('code');
        const departmentInput = document.getElementById('department_id');
        const nameError = document.getElementById('name-error');
        const codeError = document.getElementById('code-error');
        const departmentError = document.getElementById('department-error');
        const submitButton = document.getElementById('submit-button');

        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Reset error messages
            nameError.textContent = '';
            codeError.textContent = '';
            departmentError.textContent = '';
            nameInput.classList.remove('is-invalid');
            codeInput.classList.remove('is-invalid');
            departmentInput.classList.remove('is-invalid');

            // Validasi nama prodi
            if (nameInput.value.trim() === '') {
                nameError.textContent = 'Nama prodi harus diisi';
                nameInput.classList.add('is-invalid');
                isValid = false;
            }

            // Validasi kode prodi
            if (codeInput.value.trim() === '') {
                codeError.textContent = 'Kode prodi harus diisi';
                codeInput.classList.add('is-invalid');
                isValid = false;
            } else if (codeInput.value.length > 10) {
                codeError.textContent = 'Kode prodi maksimal 10 karakter';
                codeInput.classList.add('is-invalid');
                isValid = false;
            }

            // Validasi jurusan
            if (departmentInput.value === '') {
                departmentError.textContent = 'Jurusan harus dipilih';
                departmentInput.classList.add('is-invalid');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            } else {
                e.preventDefault();
                if (confirm('Apakah Anda yakin ingin menyimpan data prodi ini?')) {
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
                    submitButton.disabled = true;
                    form.submit();
                }
            }
        });

        document.getElementById('tambahUserModal').addEventListener('hidden.bs.modal', function() {
            form.reset();
            nameInput.classList.remove('is-invalid');
            codeInput.classList.remove('is-invalid');
            departmentInput.classList.remove('is-invalid');
            submitButton.innerHTML = '<i class="fas fa-save me-1"></i> Simpan Prodi';
            submitButton.disabled = false;
        });
    });
</script>
