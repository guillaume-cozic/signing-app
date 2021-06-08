<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <x-adminlte-callout theme="info" icon="fas fa-lg fa-info-circle" title="Gestion des tarifs au forfait">
            Laisser le champs vide si vous ne souhaitez pas proposer de tarif pour une heure donnée
        </x-adminlte-callout>
        <form action="{{ route('fleet.edit.rent', ['fleetId' => $fleet->id]) }}" class="form-horizontal" method="POST">
            @csrf
            @for($i=1; $i<11; $i++)
                <div class="form-group row">
                    <div class="col-2">
                        <label for="i_{{$i}}">Prix pour {{$i}} heure(s)</label>
                    </div>
                    <div class="col-10">
                        <input name="rents[hours][{{$i}}]"
                               class="form-control {{ $errors->has('rents.hours.'.$i) ? 'is-invalid' : '' }}"
                               id="i_{{$i}}" type="number" value="{{ old('rents.hours.'.$i, $fleet->rents['hours'][$i] ?? null) }}" />
                            @if ($errors->has('rents.hours.'.$i))
                                <span class="error invalid-feedback">
                                    <strong>{{ $errors->first('rents.hours.'.$i) }}</strong>
                                </span>
                            @endif
                    </div>
                </div>
            @endfor
            <button type="submit" class="btn btn-primary">Sauvegarder les tarifs</button>
        </form>
    </div>
</div>
