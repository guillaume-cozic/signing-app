@forelse($suggestions as $suggestion)
    @php
        $state = $suggestion['action'] === 'finish' ? 'success' : 'info';
        $message = $suggestion['action'] === 'finish' ? 'Terminer la sortie' : 'Débuter la sortie';
        $route = $suggestion['action'] === 'finish'
        ? route('boat-trip.end', ['boatTripId' => $suggestion['boat-trip_id']])
        : route('boat-trip.start', ['boatTripId' => $suggestion['boat-trip_id']]) ;
        $class = $suggestion['action'] === 'finish' ? 'btn-end' : 'btn-start';
    @endphp
    <x-adminlte-callout theme="{{$state}}">
        {{$suggestion['name']}}<br/>
        {!! $suggestion['boats'] !!}<br/>
        <button data-href="{{ $route }}" class="{{$class}} btn btn-{{$state}}">
            {{$message}}
        </button>
    </x-adminlte-callout>
@empty
    <x-adminlte-alert theme="success">
        Aucune action nécéssite votre attention
    </x-adminlte-alert>
@endforelse
<input type="hidden" value="{{ count($suggestions) }}" id="count-suggestions"/>
