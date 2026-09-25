<?php

namespace App\Models;

use Database\Factories\ArchiveFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    /** @use HasFactory<ArchiveFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'typearchive',
        'description',
        'date_doc',
        'emplacement',
        'emplacement2',
        'rayon',
        'travee',
        'cote',
        'format',
        'departement',
        'piece_jointe',
        'orientation',
        'zip_file',
        'filepath',
        'user_id',
    ];
}
