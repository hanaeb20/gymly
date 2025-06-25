@foreach($salle->photos as $photo)
    <img src="{{ asset('storage/' . $photo->chemin) }}" alt="Photo de la salle">
@endforeach