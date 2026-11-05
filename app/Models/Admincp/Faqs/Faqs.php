<?php

namespace App\Models\Admincp\Faqs;

use Illuminate\Database\Eloquent\Model;

class Faqs extends Model
{
    protected $guarded = [];

    protected $table = 'faq';

    /**
     * Return section's faqs
     *
     * @param int $sectionId
     * @return bool
     */
    public function faqSection(int $sectionId): bool
    {
        return FaqSections::where('section_id', $sectionId)->where('faq_id', $this->id)->exists();
    }

    /**
     * Return sectionTag's faqs
     *
     * @param int $sectionTagId
     * @return bool
     */
    public function faqSectionTag(int $sectionTagId): bool
    {
        return FaqSections::where('section_tag_id', $sectionTagId)->where('faq_id', $this->id)->exists();
    }

    /**
     * Return loanType's faqs
     *
     * @param int $loanTypeId
     * @return bool
     */
    public function faqLoanType(int $loanTypeId): bool
    {
        return FaqSections::where('loan_type_id', $loanTypeId)->where('faq_id', $this->id)->exists();
    }
}
