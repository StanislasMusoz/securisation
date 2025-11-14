@extends('layouts.app')

@section('content')
    <h1>Ajouter un crayon</h1>

    <form action="{{ route('crayons.store') }}" method="POST">
        @csrf
        <label for="nom">Nom :</label>
        <input type="text" name="nom" required>
        <br>
        <label for="quantite">Quantité :</label>
        <input type="number" name="quantite" required>
        <br>
        <button type="submit">Ajouter</button>
    </form>

    <a href="{{ route('crayons.index') }}">Retour à la liste des crayons</a>
@endsection
