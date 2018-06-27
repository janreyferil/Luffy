<?php

namespace App\Http\Resources\Primary;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Foreign\UsersResource as FUR;
class Status extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      //  return parent::toArray($request);
      return [
        "id" => $this->id,
        "permission" => $this->permission,
        "report" => $this->report,
        "user" =>new FUR($this->user),
        ];
    }
}
