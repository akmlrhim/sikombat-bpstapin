@props(['action' => '', 'label' => 'Hapus'])

<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeleteModal"
  data-action="{{ $action }}">
  {{ $label }}
</button>

@once
  <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Konfirmasi Hapus</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          Apakah anda yakin ingin menghapus data ini?
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>

          <form id="confirmDeleteForm" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      $('#confirmDeleteModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const action = button.data('action');
        $('#confirmDeleteForm').attr('action', action);
      });
    });
  </script>
@endonce
