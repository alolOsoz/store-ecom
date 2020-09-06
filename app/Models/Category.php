<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Translatable;

    protected $with = ['translations'];
    protected $translatedAttributes = ['name'];
    protected $fillable = ['parent_id', 'slug', 'is_active'];
    protected $casts = ['is_active' => 'boolean',];
    protected $hidden = ['translations'];

    public function scopeParent($q)
    {
        return $q->whereNull('parent_id');
    }

    public function getActive()
    {
        return $this->is_active == 0 ? __('admin/maincategories.deactive') :__('admin/maincategories.active');
    }

}