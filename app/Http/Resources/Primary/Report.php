<?php

namespace App\Http\Resources\Primary;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Foreign\UsersResource as FUR;
class Report extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
    //    return parent::toArray($request);
    return [
        "id" => $this->id,
        "report" => $this->report,
        "reason" => $this->reason,
        "level" => $this->level,
        "user_report" => new FUR($this->user_report),
        "report_user" => new FUR($this->report_user),
        "created_at" => $this->created_at->diffForHumans()
     ];
    }
}
