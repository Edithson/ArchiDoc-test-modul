<?php

namespace App\Models;

use App\Traits\Auditable;
use Database\Factories\ArchiveFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Archive extends Model
{
    /** @use HasFactory<ArchiveFactory> */
    use Auditable, HasFactory, LogsActivity, SoftDeletes;

    /**
     * User associated with the archive.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Department associated with the archive.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Type of archive associated with the archive.
     */
    public function archiveType(): BelongsTo
    {
        return $this->belongsTo(ArchiveType::class, 'archive_type_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'archive_type_id',
        'description',
        'date_doc',
        'emplacement',
        'emplacement2',
        'rayon',
        'travee',
        'cote',
        'format',
        'department_id',
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
     * Options for activity logging.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

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
