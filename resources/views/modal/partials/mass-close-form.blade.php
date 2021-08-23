<div class="mb-3">
    <div class="form-check">
        <input id="closed_all" class="form-check-input" type="checkbox">
        <label style="cursor: pointer;" for="closed_all" class="form-check-label">Marquer toutes les sorties comme terminées</label>
    </div>
    <hr>
    @if($boatTrips !== null)
        @foreach($boatTrips as $key => $boatTrip)
            <h6 style="text-decoration: underline;" class="mt-1">{{ $boatTrip->name }} le
                {{ $boatTrip->startAt !== null  ? $boatTrip->startAt->format('d-m-Y') : $boatTrip->shouldStartAt->format('d-m-Y') }}
            </h6>
            <div class="form-check">
                <input name="{{ $boatTrip->id }}" id="boattrip_close_{{ $key }}" class="boattrip_close form-check-input" type="radio" value="close">
                <label style="cursor: pointer;" for="boattrip_close_{{ $key }}" class="form-check-label">Terminer</label>
            </div>
            <div class="form-check">
                <input name="{{ $boatTrip->id }}" id="boattrip_{{ $key }}" class="form-check-input" type="radio" value="delete">
                <label style="cursor: pointer;" for="boattrip_{{ $key }}" class="form-check-label">Supprimer</label>
            </div>
        @endforeach
    @endif
</div>
