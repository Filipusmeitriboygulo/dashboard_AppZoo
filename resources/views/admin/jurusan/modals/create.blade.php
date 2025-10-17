<div class="modal fade" id="tambahUserModal" tabindex="-1" aria-labelledby="tambahUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="{{ route('admin.jurusan.store') }}" id="tambahJurusanForm">
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
                        <!-- Nama Jurusan -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Nama Jurusan" required>
                                <label for="name">Nama Jurusan</label>
                                <div class="invalid-feedback" id="name-error"></div>
                            </div>
                        </div>

                        <!-- Kode Jurusan -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="code" id="code" class="form-control" placeholder="Akronim Jurusan" required maxlength="10">
                                <label for="code">Akronim    Jurusan</label>
                                <div class="invalid-feedback" id="code-error"></div>
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
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('tambahJurusanForm');
        const nameInput = document.getElementById('name');
        const codeInput = document.getElementById('code');
        const nameError = document.getElementById('name-error');
        const codeError = document.getElementById('code-error');
        const submitButton = document.getElementById('submit-button');
        
        // Validasi form
        form.addEventListener('submit', function (e) {
            let isValid = true;
            
            // Reset error messages
            nameError.textContent = '';
            codeError.textContent = '';
            nameInput.classList.remove('is-invalid');
            codeInput.classList.remove('is-invalid');
            
            // Validasi nama jurusan
            if (nameInput.value.trim() === '') {
                nameError.textContent = 'Nama jurusan harus diisi';
                nameInput.classList.add('is-invalid');
                isValid = false;
            }
            
            // Validasi kode jurusan
            if (codeInput.value.trim() === '') {
                codeError.textContent = 'Kode jurusan harus diisi';
                codeInput.classList.add('is-invalid');
                isValid = false;
            } else if (codeInput.value.length > 10) {
                codeError.textContent = 'Kode jurusan maksimal 10 karakter';
                codeInput.classList.add('is-invalid');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            } else {
                // Tampilkan konfirmasi sebelum submit
                e.preventDefault();
                if (confirm('Apakah Anda yakin ingin menyimpan data jurusan ini?')) {
                    // Ubah teks tombol menjadi loading
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
                    submitButton.disabled = true;
                    
                    // Submit form
                    form.submit();
                }
            }
        });
        
        // Reset form ketika modal ditutup
        document.getElementById('tambahUserModal').addEventListener('hidden.bs.modal', function () {
            form.reset();
            nameInput.classList.remove('is-invalid');
            codeInput.classList.remove('is-invalid');
            submitButton.innerHTML = '<i class="fas fa-save me-1"></i> Simpan Jurusan';
            submitButton.disabled = false;
        });
    });
</script>