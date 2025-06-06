<?php

namespace App\Http\Filters\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Filters\V1\QueryFilter;

class TicketFilter extends QueryFilter
{
  protected $sortable = [
    'id',
    'status',
    'title',
    'createdAt' => 'created_at',
    'updatedAt' => 'updated_at'
  ];
  public function include($value)
  {
    return $this->builder->with($value);
  }
  public function status($value)
  {
    return $this->builder->whereIn('status', explode(',', $value));
  }

  public function title($value)
  {
    $likeStr = str_replace('*', '', $value);
    return $this->builder->where('title', 'like', '%' . $likeStr . '%');
  }

  public function createdAt($value)
  {
    $dates = explode(',', $value);

    if (count($dates) > 1) {
      return $this->builder->whereBetween('created_at', $dates);
    }
    return $this->builder->whereDate('created_at', $value);
  }

  public function updatedAt($value)
  {
    $dates = explode(',', $value);

    if (count($dates) > 1) {
      return $this->builder->whereBetween('updated_at', $dates);
    }
    return $this->builder->whereDate('updated_at', $value);
  }

}