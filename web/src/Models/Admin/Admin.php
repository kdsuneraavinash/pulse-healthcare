<?php declare(strict_types=1);

namespace Pulse\Models\Admin;

use DB;
use Pulse\Models\AccountSession\Account;
use Pulse\Models\Enums\AccountType;
use Pulse\Models\Enums\VerificationState;
use Pulse\Models\MedicalCenter\MedicalCenter;


class Admin extends Account
{
    /**
     * MedicalCenter constructor.
     * @param string $accountId
     */
    protected function __construct(string $accountId)
    {
        parent::__construct($accountId, AccountType::Admin);
    }

    public function retrieveMedicalCentersList()
    {
        $query = DB::query(
            "SELECT DISTINCT *
                FROM medical_centers
                       INNER JOIN medical_center_details ON medical_centers.account_id = medical_center_details.account_id
                GROUP BY medical_centers.account_id
                ORDER BY medical_center_details.creation_date DESC;"
        );
        for ($i = 0; $i < count($query); $i++) {
            $spaced_removed = str_replace(' ', '+', $query[$i]['address']);
            $commas_removed = str_replace(',', '', $spaced_removed);
            $dots_removed = str_replace('.', '', $commas_removed);
            $query[$i]['parsed_address'] = $dots_removed;
        }
        return $query;
    }

    public function deleteMedicalCenter(MedicalCenter $account)
    {
        DB::delete('accounts', "account_id=%s", $account->getAccountId());
    }

    public function retractMedicalCenter(MedicalCenter $account)
    {
        DB::update('medical_centers', array(
            'verified' => VerificationState::Default
        ), "account_id=%s", $account->getAccountId());
    }

    public function rejectMedicalCenter(MedicalCenter $account)
    {
        DB::update('medical_centers', array(
            'verified' => VerificationState::Rejected
        ), "account_id=%s", $account->getAccountId());
    }

    public function verifyMedicalCenter(MedicalCenter $account)
    {
        DB::update('medical_centers', array(
            'verified' => VerificationState::Verified
        ), "account_id=%s", $account->getAccountId());
    }
}