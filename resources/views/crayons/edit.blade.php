@extends('layouts.app')

@section('content')
    <h1>Modifier un crayon</h1>

    <form action="{{ route('crayons.update', $crayon->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="nom">Nom :</label>
        <input type="text" name="nom" value="{{ $crayon->nom }}" required>
        <br>
        <label for="quantite">Quantité :</label>
        <input type="number" name="quantite" value="{{ $crayon->quantite }}" required>
        <br>
        <button type="submit">Enregistrer les modifications</button>
    </form>

    <a href="{{ route('crayons.index') }}">Retour à la liste des crayons</a>
@endsection
