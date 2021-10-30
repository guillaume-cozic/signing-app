<ul>
    @foreach($actions as $action)
        @if($action->type == 'sail')
            <li>le {{ $action->atTime->format('d-m-Y') }} navigation de {{ $action->hours }} heure(s)</li>
        @elseif($action->type == 'add')
            <li>le {{ $action->atTime->format('d-m-Y') }} ajout de {{ $action->hours }} heure(s)</li>
        @elseif($action->type == 'sub')
            <li>le {{ $action->atTime->format('d-m-Y') }} retrait de {{ $action->hours }} heure(s)</li>
        @endif
    @endforeach
</ul>
