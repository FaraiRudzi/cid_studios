<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class CaseModel extends Model
{
    use HasFactory;

    protected $table = 'cases';

    protected $fillable = [
        'scene_reference_number',
        'reference_number',
        'station_id',
        'photographer_id',
        'case_type',
        'circumstances',
        'cause_of_death',
    ];

    // 1. Define the possible Case Types as Constants
    const TYPE_SUDDEN_DEATH = 'Sudden Death';
    const TYPE_MURDER = 'Murder';
    const TYPE_ID_PARADE = 'ID Parade';
    const TYPE_INDICATIONS = 'Indications';

    // Relationships
    public function station(): BelongsTo { return $this->belongsTo(Station::class); }
    public function photographer(): BelongsTo { return $this->belongsTo(Photographer::class); }
    public function media(): HasMany { return $this->hasMany(Media::class, 'case_id'); }

    public function people(): BelongsToMany {
        return $this->belongsToMany(Person::class, 'case_person', 'case_id', 'person_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Relationship to Case Logs
     * Matches the case_id column in your case_logs table
     */
    public function logs(): HasMany {
        return $this->hasMany(CaseLog::class, 'case_id')->latest();
    }

    // Accessors for easier access to specific roles
    public function getDeceasedAttribute() { return $this->people()->wherePivot('role', 'deceased')->first(); }
    public function getAccusedAttribute() { return $this->people()->wherePivot('role', 'accused')->get(); }
    public function getInformantAttribute() { return $this->people()->wherePivot('role', 'informant')->first(); }
    public function getComplainantAttribute() { return $this->people()->wherePivot('role', 'complainant')->first(); }

    /**
     * Optimized Forensic Logging Helper
     * Detects guard automatically and maps to your specific table schema:
     * (user_id, action, role, description, metadata)
     */
    public function recordLog($action, $description, $metadata = null)
    {
        $userId = null;
        $role = 'SYSTEM';

        // 1. Check if a Photographer is logged in (Photographer Guard)
        if (Auth::guard('photographer')->check()) {
            $userId = Auth::guard('photographer')->id();
            $role = 'PHOTOGRAPHER';
        }
        // 2. Check if an Admin/Staff is logged in (Web Guard)
        elseif (Auth::check()) {
            $userId = Auth::id();
            $role = Auth::user()->role ?? 'ADMIN';
        }

        // 3. Create the log entry
        return $this->logs()->create([
            'user_id'     => $userId,
            'action'      => strtoupper($action),
            'role'        => $role,
            'description' => $description,
            // Ensure metadata is stored as JSON string if passed as array
            'metadata'    => is_array($metadata) ? json_encode($metadata) : $metadata,
        ]);
    }

    /**
     * Logic: Define which roles are REQUIRED for each case type
     */
    public function getRequiredRoles()
    {
        return match($this->case_type) {
            self::TYPE_SUDDEN_DEATH => ['informant', 'deceased'],
            self::TYPE_MURDER       => ['informant', 'deceased', 'accused'],
            self::TYPE_ID_PARADE    => ['complainant', 'accused'],
            self::TYPE_INDICATIONS  => ['accused'],
            default                 => ['complainant', 'accused'],
        };
    }
}
