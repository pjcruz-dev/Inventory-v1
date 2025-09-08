@extends('layouts.app')

@section('auth')
    @include('layouts.navbars.auth.topnav', ['title' => 'SweetAlert2 Test'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>SweetAlert2 Modal Test</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="p-4">
                        <button type="button" class="btn btn-primary" onclick="showSuccessAlert()">Success Alert</button>
                        <button type="button" class="btn btn-danger" onclick="showErrorAlert()">Error Alert</button>
                        <button type="button" class="btn btn-warning" onclick="showConfirmAlert()">Confirm Alert</button>
                        <button type="button" class="btn btn-info" onclick="showInputAlert()">Input Alert</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth.footer')
@endsection

@push('scripts')
<script>
    function showSuccessAlert() {
        Swal.fire({
            title: 'Success!',
            text: 'Your operation was completed successfully.',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    }

    function showErrorAlert() {
        Swal.fire({
            title: 'Error!',
            text: 'Something went wrong.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }

    function showConfirmAlert() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                );
            }
        });
    }

    function showInputAlert() {
        Swal.fire({
            title: 'Enter your name',
            input: 'text',
            inputPlaceholder: 'Your name here...',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            inputValidator: (value) => {
                if (!value) {
                    return 'You need to write something!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(`Hello, ${result.value}!`);
            }
        });
    }
</script>
@endpush