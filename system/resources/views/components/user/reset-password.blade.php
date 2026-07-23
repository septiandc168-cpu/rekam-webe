<div>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-sm btn-outline-secondary shadow-sm rounded-circle mx-1" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" title="Reset Password"
        data-toggle="modal" data-target="#formResetPassword{{ $id }}">
        <i class="fas fa-lock-open"></i>
    </button>

    <!-- Modal -->
    <div class="modal fade" id="formResetPassword{{ $id }}" tabindex="-1"
        aria-labelledby="formResetPasswordLabel" aria-hidden="true">
        <form action="{{ route('users.reset-password') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="formResetPasswordLabel">Form Reset Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Ketika Password User dirset maka password User tersebut akan menjadi default yaitu
                            <strong>"12345678"</strong>
                        </p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Reset Password</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
