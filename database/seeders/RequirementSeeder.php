<?php

namespace Database\Seeders;

use App\Models\Requirement;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Requirement::create([
            'level_id' => 4,
            'requirement' => 'Fill in Contact Person of Customer',
        ]);
        Requirement::create([
            'level_id' => 4,
            'requirement' => 'Upload Attachment RFQ or Email Request',
        ]);
        Requirement::create([
            'level_id' => 4,
            'requirement' => 'Upload Attachment Workscope',
        ]);
        Requirement::create([
            'level_id' => 4,
            'requirement' => 'Hangar & Line Slot Request',
        ]);
        Requirement::create([
            'level_id' => 3,
            'requirement' => 'Attachment of Financial Assesment Form (optional)',
        ]);
        Requirement::create([
            'level_id' => 3,
            'requirement' => 'Attachment of Maintenance Proposal for Customer',
        ]);
        Requirement::create([
            'level_id' => 3,
            'requirement' => 'Attachment of Profitability Analysis Form Signed',
        ]);
        Requirement::create([
            'level_id' => 2,
            'requirement' => 'Attachment of Customer Approval (SOW Signed / Proposal Approved)',
        ]);
        Requirement::create([
            'level_id' => 1,
            'requirement' => 'Attachment of WO/PO number Customer Document',
        ]);
        Requirement::create([
            'level_id' => 1,
            'requirement' => 'Input SO number',
        ]);
    }
}
