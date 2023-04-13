<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComicDetailResource extends JsonResource
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
            'title' => $this->title,
            'prolog' => $this->prolog,
            'author' => $this->author,
            'episode' => $this->eps,
            'created_at' => date_format($this->created_at, "Y/m/d H:i:s"),
        ];
    }
}
