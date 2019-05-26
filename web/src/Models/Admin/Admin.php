<?php declare(strict_types=1);

namespace Pulse\Models\Admin;

use Pulse\Components\Database;
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
    function __construct(string $accountId)
    {
        parent::__construct($accountId, AccountType::Admin);
    }

    public function retrieveMedicalCentersList()
    {
        $query = Database::query(
            "SELECT DISTINCT *
                FROM medical_centers
                       INNER JOIN medical_center_details ON medical_centers.account_id = medical_center_details.account_id
                GROUP BY medical_centers.account_id
                ORDER BY medical_center_details.creation_date DESC;",
            array()
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
        Database::delete('accounts', "account_id=:account_id",
            array('account_id' => $account->getAccountId()));
    }

    public function retractMedicalCenter(MedicalCenter $account)
    {
        Database::update('medical_centers', 'verified=:verified', "account_id=:account_id",
            array(
                'verified' => VerificationState::Default,
                'account_id' => $account->getAccountId()
            ));
    }

    public function rejectMedicalCenter(MedicalCenter $account)
    {
        Database::update('medical_centers', 'verified=:verified', "account_id=:account_id",
            array(
                'verified' => VerificationState::Rejected,
                'account_id' => $account->getAccountId()
            ));
    }

    public function verifyMedicalCenter(MedicalCenter $account)
    {
        Database::update('medical_centers', 'verified=:verified', "account_id=:account_id",
            array(
                'verified' => VerificationState::Verified,
                'account_id' => $account->getAccountId()
            ));
    }

    public function generateUserTypeData(): array
    {
        $query = Database::query("SELECT account_type, COUNT(*) as account_count FROM accounts GROUP BY account_type;", array());

        $parsed = array(
            'admin' => 0,
            'patient' => 0,
            'doctor' => 0,
            'med_center' => 0,
            'tester' => 0
        );

        if ($query == null || sizeof($query) == 0) {
            return $parsed;
        }

        foreach ($query as $entry) {
            $parsed[$entry['account_type']] = $entry['account_count'];
        }
        return $parsed;
    }
}
