<?php
namespace App\Modules\Reservations\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationEloquentModel extends Model
{
    use HasFactory;
    protected $table = 'reservations';
    protected $fillable = ['user_id', 'space_id', 'start_time', 'end_time', 'status'];
    protected $casts = ['start_time' => 'datetime', 'end_time' => 'datetime'];
}
