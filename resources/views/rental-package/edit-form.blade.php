@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    Editer le forfait
                </div>
                <form action="{{ route('rental-package.edit.process', ['id' => $rentalPackage->id]) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nom du forfait</label>
                            <input value="{{ old('name', $rentalPackage->name) }}" type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name" id="name" placeholder="Nom du forfait">
                            @if ($errors->has('name'))
                                <span class="error invalid-feedback">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="select-fleet">Flottes associées</label>
                            <select id="select-fleet" class="fleets-select form-control {{ $errors->has('fleets') ? 'is-invalid' : '' }}" name="fleets[]" multiple="multiple">
                                @foreach($fleets as $fleet)
                                    <option {{ in_array( $fleet->id, old("fleets", $rentalPackage->fleets)) ? "selected":"" }} value="{{ $fleet->id }}">{{ $fleet->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('fleets'))
                                <span class="error invalid-feedback">
                                    <strong>{{ $errors->first('fleets') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="name">Durée de validité du forfait</label>
                            <select class="form-control {{ $errors->has('validity') ? 'is-invalid' : '' }}" name="validity">
                                <option {{ old("validity", $rentalPackage->validity) == 365 ? "selected": "" }} value="365">1 an</option>
                                <option {{ old("validity", $rentalPackage->validity) == 730 ? "selected": "" }} value="730">2 ans</option>
                                <option {{ old("validity", $rentalPackage->validity) == 3650 ? "selected": "" }} value="3650">10 ans</option>
                            </select>
                            @if ($errors->has('validity'))
                                <span class="error invalid-feedback">
                                    <strong>{{ $errors->first('validity') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <input type="submit" class="pull-right btn btn-primary" value="Editer le forfait"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
