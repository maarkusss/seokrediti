<?php

namespace App\Models\Admincp\Sections;

use App\Models\Admincp\Faqs\FaqSections;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sections extends Model
{
    protected $guarded = [];

    /**
     * Return the children of a section.
     *
     * @return HasMany
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_section_id')->where('active', true);
        //  return self::where('active', true)->where('parent_type_id', $this->id)->get();
    }

    public function faqs()
    {
        return FaqSections::where('section_id', $this->id)->select('faq_id')->get();
    }

    /**
     * Return test section faq
     *
     * @param int $faqId
     * @return bool
     */
    public function faq($faqId): bool
    {
        return FaqSections::where('section_id', $this->id)->where('faq_id', $faqId)->exists();
    }
}
