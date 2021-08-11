<div class="card card-primary">
    <div class="card-header">
        Ajouter un forfait client
    </div>
    <form action="{{ route('sailor-rental-package.add') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="name">Nom du client <small>Il est préférable de préciser le nom et prénom du client pour éviter des confusions</small></label>
                <input value="{{ old('name') }}"
                       type="search"
                       class=" autocomplete-sailor-name form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                       name="name" placeholder="Nom du client"
                        autocomplete="off"
                >
                <input type="hidden" name="sailor_id" value="" class="input_sailor_id"/>
                @if ($errors->has('name'))
                    <span class="error invalid-feedback">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                <label for="select-rental-package">Forfait</label>
                <select id="rental-package" class="form-control {{ $errors->has('rental_package_id') ? 'is-invalid' : '' }}" name="rental_package_id">
                    @foreach($rentalPackages as $rentalPackage)
                        <option value="{{ $rentalPackage->id }}">{{ $rentalPackage->name }}</option>
                    @endforeach
                </select>
                @if ($errors->has('rental_package_id'))
                    <span class="error invalid-feedback">
                        <strong>{{ $errors->first('rental_package_id') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                <label for="name">Nombre d'heures</label>
                <select class="form-control {{ $errors->has('hours') ? 'is-invalid' : '' }}" name="hours">
                    <option value="3">3 heures</option>
                    <option value="5">5 heures</option>
                    <option value="10">10 heures</option>
                </select>
                @if ($errors->has('hours'))
                    <span class="error invalid-feedback">
                        <strong>{{ $errors->first('hours') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="card-footer">
            <input type="submit" class="pull-right btn btn-primary" value="Créer le forfait client"/>
        </div>
    </form>
</div>
