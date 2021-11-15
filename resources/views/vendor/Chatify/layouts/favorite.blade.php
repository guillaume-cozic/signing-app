<div class="favorite-list-item">
    <div data-id="{{ $user->id }}" data-action="0" class="avatar av-m"
        style="background-image: url('{{ $user->adminlte_image() }}');">
    </div>
    <p>{{ strlen($user->surname) > 5 ? substr($user->surname,0,6).'..' : $user->surname }}</p>
</div>
