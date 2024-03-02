<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MateriaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'imagem' => $this->imagem,
            'data_de_publicacao' => $this->data_de_publicacao,
            'texto_completo' => $this->when(!is_null($this->texto_completo), $this->texto_completo)
        ];
    }
}
