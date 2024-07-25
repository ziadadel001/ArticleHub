<?php

namespace App\Http\Resources;

use Illuminate\Http\Response;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => 'list of Articles',
            'status' => Response::HTTP_OK,
            'data' => $this->collection->transform(function ($articles) {
                return new ArticleResource($articles);
            })
        ];
    }
}
