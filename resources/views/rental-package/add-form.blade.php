 <div class="card card-primary">
    <div class="card-header">
        Ajouter un forfait
    </div>
     <form action="{{ route('rental-package.add.process') }}" method="POST">
         @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="name">Nom du forfait</label>
                <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name" id="name" placeholder="Nom du forfait">
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
                        <option value="{{ $fleet->id }}">{{ $fleet->name }}</option>
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
                <input type="text" class="form-control {{ $errors->has('validity') ? 'is-invalid' : '' }}" name="validity" placeholder="Durée de validité du forfait">
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
