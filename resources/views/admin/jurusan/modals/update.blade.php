<div class="modal fade" id="editJurusanModal" tabindex="-1" aria-labelledby="editJurusanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editJurusanForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="editJurusanModalLabel">
                        <i class="fas fa-edit me-2"></i> Edit Data Jurusan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Nama Jurusan -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="name" id="edit_name" class="form-control"
                                    placeholder="Nama Jurusan" required>
                                <label for="edit_name">Nama Jurusan</label>
                                <div class="invalid-feedback" id="edit-name-error"></div>
                            </div>
                        </div>

                        <!-- Kode Jurusan -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="code" id="edit_code" class="form-control"
                                    placeholder="Akronim Jurusan" required maxlength="10">
                                <label for="edit_code">Akronim Jurusan</label>
                                <div class="invalid-feedback" id="edit-code-error"></div>
                            </div>
                        </div>

                        <!-- Contoh -->
                        <div class="col-12">
                            <div class="example-box">
                                <h6><i class="fas fa-lightbulb me-2"></i>Contoh Penamaan:</h6>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <strong>Nama Jurusan Lengkap:</strong><br>
                                        Teknik Elektro
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Kode Jurusan:</strong><br>
                                        T.Elektro
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-warning" id="edit-submit-button">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Script untuk Modal Edit Jurusan -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editForm = document.getElementById('editJurusanForm');
        const editNameInput = document.getElementById('edit_name');
        const editCodeInput = document.getElementById('edit_code');
        const editNameError = document.getElementById('edit-name-error');
        const editCodeError = document.getElementById('edit-code-error');
        const editSubmitButton = document.getElementById('edit-submit-button');
        const editModal = document.getElementById('editJurusanModal');

        // Event listener untuk semua tombol edit
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function() {
                const jurusanId = this.getAttribute('data-id');
                const jurusanName = this.getAttribute('data-name');
                const jurusanCode = this.getAttribute('data-code');
                const url = this.getAttribute('data-url'); // route update sudah di index

                // Isi form
                editNameInput.value = jurusanName || '';
                editCodeInput.value = jurusanCode || '';

                // Set action pakai route update
                editForm.action = url;
            });
        });

        // Validasi
        function validateEditName() {
            if (editNameInput.value.trim() === '') {
                editNameError.textContent = 'Nama jurusan harus diisi';
                editNameInput.classList.add('is-invalid');
                return false;
            } else {
                editNameInput.classList.remove('is-invalid');
                return true;
            }
        }

        function validateEditCode() {
            if (editCodeInput.value.trim() === '') {
                editCodeError.textContent = 'Akronim jurusan harus diisi';
                editCodeInput.classList.add('is-invalid');
                return false;
            } else if (editCodeInput.value.length > 10) {
                editCodeError.textContent = 'Akronim jurusan maksimal 10 karakter';
                editCodeInput.classList.add('is-invalid');
                return false;
            } else {
                editCodeInput.classList.remove('is-invalid');
                return true;
            }
        }

        // Validasi real-time
        editNameInput.addEventListener('blur', validateEditName);
        editCodeInput.addEventListener('blur', validateEditCode);

        // Validasi form saat submit
        editForm.addEventListener('submit', function(e) {
            let isNameValid = validateEditName();
            let isCodeValid = validateEditCode();

            if (!isNameValid || !isCodeValid) {
                e.preventDefault();
                if (!isNameValid) {
                    editNameInput.focus();
                } else if (!isCodeValid) {
                    editCodeInput.focus();
                }
            } else {
                if (!confirm('Apakah Anda yakin ingin menyimpan perubahan data jurusan ini?')) {
                    e.preventDefault();
                } else {
                    editSubmitButton.innerHTML =
                        '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
                    editSubmitButton.disabled = true;
                }
            }
        });

        // Reset form ketika modal ditutup
        editModal.addEventListener('hidden.bs.modal', function() {
            editNameInput.classList.remove('is-invalid');
            editCodeInput.classList.remove('is-invalid');
            editSubmitButton.innerHTML = '<i class="fas fa-save me-1"></i> Simpan Perubahan';
            editSubmitButton.disabled = false;
        });
    });
</script>
