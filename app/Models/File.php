<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    const FILE_PNG = 'png';
    const FILE_JPG = 'jpg';
    const FILE_JPEG = 'jpeg';
    const FILE_PDF = 'pdf';
    const FILE_EML = 'eml';

    const CONTENT_TYPES = [
        self::FILE_PNG => 'image/png',
        self::FILE_JPG => 'image/jpg',
        self::FILE_JPEG => 'image/jpeg',
        self::FILE_PDF => 'application/pdf',
        self::FILE_EML => 'application/eml',
    ];

    protected $fillable = [
        'sales_requirement_id',
        'path',
    ];

    protected $appends = [
        'content_type',
    ];

    public function getContentTypeAttribute()
    {
        $path = $this->path;
        $get_extension = explode('.', $path);
        $extension = strtolower($get_extension[1]);

        return self::CONTENT_TYPES[$extension];
    }

    public function salesRequirement()
    {
        return $this->belongsTo(SalesRequirement::class, 'sales_requirement_id');
    }
}
