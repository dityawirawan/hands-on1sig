<form method="PUT" class="validated_form" id="form-edit" action="{{ route('handson4.updateMarker') }}">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-info card-outline">
                <div class="card-body">
                    @if (count( $errors) > 0 )
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>        
                            @endforeach
                        </div>
                    @endif
                    <style type="text/css">
                        .select2 {
                          width: 100%!important; /* overrides computed width, 100px in your demo */
                        }
                    </style>
                    <div class="row">
                        <div class="form-group col-md-12">
                            @csrf
                            <div class="form-group">
                                <label>Marker Name:</label>
                                <input type="text" name="name" class="form-control" value="{{ $marker->name }}" required>
                            </div>
                            <div class="form-group">
                                <label>Latitude:</label>
                                <input type="text" name="latitude" class="form-control" value="{{ $marker->latitude }}" required>
                            </div>
                            <div class="form-group">
                                <label>Longitude:</label>
                                <input type="text" name="longitude" class="form-control" value="{{ $marker->longitude }} required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                     <button class="btn btn-success" type="button" onclick="submitValid({{ $marker->id }})" data-toggle="tooltip" data-placement="top" title="Simpan">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-undo"></i> Kembali</button>
                </div>
            </div>
         </div>
    </div>
</form>
<script>
    function submitValid(id) {
        const form = document.getElementById('form-edit');
        form.action = `{{ route('handson4.updateMarker') }}/${id}`;
        form.submit();
    }
</script>
