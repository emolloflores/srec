<?php
namespace App\Modules\Spaces\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaceEloquentModel extends Model
{
    use HasFactory;
    protected $table = 'spaces';
    protected $fillable = ['name', 'type', 'capacity'];
}
