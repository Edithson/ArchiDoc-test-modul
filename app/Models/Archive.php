<?php

namespace App\Models;

use App\Traits\Auditable;
use Database\Factories\ArchiveFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Archive extends Model
{
    /** @use HasFactory<ArchiveFactory> */
    use Auditable, HasFactory, SoftDeletes;

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
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Boot model listeners for soft deletion file purge.
     */
    protected static function booted(): void
    {
        static::deleting(function (Archive $archive) {
            $filesToDelete = array_filter([
                $archive->filepath,
                $archive->piece_jointe,
                $archive->zip_file,
            ]);

            foreach ($filesToDelete as $file) {
                if (Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }
        });
    }
}
