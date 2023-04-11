@extends('layouts.master')

@section('styles')
    <!-- summernote -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h6 class="card-title">Add New User</h6>
        </div>
        <form role="form" method="post" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="mb-3">
                    <label for="nameUser">Name</label>
                    <input type="text" value="{{ old('nameUser') }}" class="form-control @error('nameUser') is-invalid @enderror" name="nameUser" id="nameUser" placeholder="Enter name">
                    @error('nameUser')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="text" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" name="email"
                           id="email" placeholder="Enter email">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="pass">Password</label>
                    <input type="password" value="{{ old('pass') }}" class="form-control @error('pass') is-invalid @enderror" name="pass"
                           id="pass" placeholder="Enter pass">
                    @error('pass')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="title">Role</label>
                    <select name="role" class="form-control @error('role') is-invalid @enderror">
                        <option value="">Select Once</option>
                        @foreach($roles as $item)
                            <option value="{{ $item->name }}" {{ old('role') == $item->name ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('role')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="title">Status</label>
                    <select name="status" class="form-control @error('status') is-invalid @enderror">
                        <option value="1" selected="selected">Active</option>
                        <option value="0">Blocked</option>
                    </select>
                    @error('title')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Summernote -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $('#summernote').summernote({
            placeholder: '',
            tabsize: 2,
            height: 120,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        //image upload preview
        $('#image').change(function () {
            let reader = new FileReader();

            reader.onload = (e) => {
                $('#image-preview').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0])
        })

        $(".js-tags").select2({
            tags: true
        });
    </script>

@endsection
