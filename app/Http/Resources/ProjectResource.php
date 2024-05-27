<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $info = $this->info;

        return [
            'id' => $this->id,
            'author' => $info['owner']['login'],
            'stargazers_count' => $info['stargazers_count'],
            'watchers_count' => $info['watchers_count'],
            'link' => $info['html_url'],
        ];
    }
}
