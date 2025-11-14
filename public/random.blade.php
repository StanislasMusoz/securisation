<?php

$crayon = \App\Models\Crayon::inRandomOrder()->first();

echo "<p> $crayon->nom</p>";
echo "<p> Quantité : $crayon->quantite</p>";
