<div class="modal fade" id="editProdiModal" tabindex="-1" aria-labelledby="editProdiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editProdiForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="editProdiModalLabel">
                        <i class="fas fa-edit me-2"></i> Edit Data Prodi
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Nama Prodi -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="name" id="edit_name" class="form-control"
                                    placeholder="Nama Prodi" required>
                                <label for="edit_name">Nama Prodi</label>
                            </div>
                        </div>

                        <!-- Kode Prodi -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="code" id="edit_code" class="form-control"
                                    placeholder="Akronim Prodi" required maxlength="10">
                                <label for="edit_code">Akronim Prodi</label>
                            </div>
                        </div>

                        <!-- Jurusan -->
                        <div class="col-md-12">
                            <div class="form-floating">
                                <select name="department_id" id="edit_department" class="form-select" required>
                                    <option value="">-- Pilih Jurusan --</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                                <label for="edit_department">Jurusan</label>
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
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            const prodiName = this.getAttribute('data-name');
            const prodiCode = this.getAttribute('data-code');
            const departmentId = this.getAttribute('data-department');
            const url = this.getAttribute('data-url');

            document.getElementById('edit_name').value = prodiName;
            document.getElementById('edit_code').value = prodiCode;
            document.getElementById('edit_department').value = departmentId;

            document.getElementById('editProdiForm').action = url;
        });
    });;
</script>
