<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
<<<<<<< HEAD

class Service extends Model
{
    use HasFactory, HasUuids;
=======
use App\Traits\HasBranch;

class Service extends Model
{
    use HasFactory, HasUuids, HasBranch;
>>>>>>> 9513f9d9f392a3b2852b4daca22d48e1b98290df

    protected $guarded = ['id'];

    public function materials()
    {
        return $this->hasMany(ServiceMaterial::class);
    }
}
