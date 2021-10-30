<div class="card card-primary card-outline">
    <div class="card-header">
        Ajouter un forfait
    </div>
     <form action="{{ route('rental-package.add.process') }}" method="POST">
         @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="name">Nom du forfait</label>
                <input value="{{ old('name') }}" type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name" id="name" placeholder="Nom du forfait">
                @if ($errors->has('name'))
                    <span class="error invalid-feedback">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                <label for="select-fleet">Flottes associées</label>
                <select id="select-fleet"
                        data-placeholder="Selectionner une ou plusieurs flottes"
                        class="fleets-select select2-purple form-control {{ $errors->has('fleets') ? 'is-invalid' : '' }}"
                        name="fleets[]" multiple="multiple">
                    @foreach($fleets as $fleet)
                        <option {{ in_array( $fleet->id, old("fleets", [])) ? "selected":"" }} value="{{ $fleet->id }}">{{ $fleet->name }}</option>
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
                    <option value="365">1 an</option>
                    <option selected value="730">2 ans</option>
                    <option value="3650">10 ans</option>
                </select>
                @if ($errors->has('validity'))
                    <span class="error invalid-feedback">
                        <strong>{{ $errors->first('validity') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="card-footer">
            <input type="submit" class="pull-right btn btn-primary" value="Créer le forfait"/>
        </div>
    </form>
</div>
