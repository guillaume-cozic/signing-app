Bonjour, <br/><br/>

Vous avez été invité à rejoindre l'espace de travail de {{$team->name}}.<br>

Cliquez sur le lien pour accepter l'invitation :
<a href="{{route('teams.accept_invite', $invite->accept_token)}}">{{route('teams.accept_invite', $invite->accept_token)}}</a>
