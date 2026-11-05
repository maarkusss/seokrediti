<?php

namespace App\Models\Admincp\Faqs;

use Illuminate\Database\Eloquent\Model;

class FaqSections extends Model
{
    protected $guarded = [];

    protected $table = 'faq__sections';

    public function faq()
    {
        return $this->belongsToMany(Faqs::class, 'faq__sections', 'faq_id', 'id');
    }

    public function sectionTagFaq()
    {
        return $this->belongsTo('\App\Models\Admincp\SectionTag', 'section_tag_id');
    }

    public function sectionFaq()
    {
        return $this->belongsTo('\App\Models\Admincp\Sections\Sections', 'section_id');
    }

    public function loanTypeFaq()
    {
        return $this->belongsTo('\App\Models\Admincp\LoanTypes\LoanTypes', 'loan_type_id');
    }
}
