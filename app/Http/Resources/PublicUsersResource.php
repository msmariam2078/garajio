<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PublicUsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (string) $this->id,
            'email' => $this->email,
            'name' => $this->first_name,
        ];
    }
}
