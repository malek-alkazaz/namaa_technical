<?php

namespace App\Models;

use App\Enums\ArticleStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';

    protected $fillable = [
        'title',
        'body',
        'status',
        'user_id',
        'approved_by',
    ];

    // ---------------------- Relationships --------------------------

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class,'approved_by');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // ---------------------- Methods --------------------------

    public function view()
    {
        $this->increment('views');
    }

    public function search($query)
    {
        return $this->where('title', 'like', '%'. $query. '%')
                    ->orWhere('body', 'like', '%'. $query. '%')
                    ->get();
    }

     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => ArticleStatus::class,
    ];
}
