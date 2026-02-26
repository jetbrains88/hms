<?php

if (!function_exists('formatPhone')) {
    /**
     * Format Pakistani phone numbers
     * 
     * @param string $phone
     * @return string
     */
    function formatPhone($phone): string
    {
        if (empty($phone)) {
            return '';
        }
        
        // Remove all non-numeric characters
        $clean = preg_replace('/[^0-9]/', '', $phone);
        
        // Format: 03XX-XXXXXXX
        if (strlen($clean) === 11 && str_starts_with($clean, '03')) {
            return substr($clean, 0, 4) . '-' . substr($clean, 4);
        }
        
        // Format: +923XXXXXXXXX
        if (strlen($clean) === 12 && str_starts_with($clean, '923')) {
            $clean = '0' . substr($clean, 2); // Convert to local format
            return substr($clean, 0, 4) . '-' . substr($clean, 4);
        }
        
        // Return original if no match
        return $phone;
    }
}

if (!function_exists('validatePakistaniPhone')) {
    /**
     * Validate Pakistani phone number
     * 
     * @param string $phone
     * @return bool
     */
    function validatePakistaniPhone($phone): bool
    {
        $clean = preg_replace('/[^0-9]/', '', $phone);
        return preg_match('/^03\d{9}$/', $clean) === 1;
    }
}

if (!function_exists('cleanPhoneNumber')) {
    /**
     * Clean phone number to digits only
     * 
     * @param string $phone
     * @return string
     */
    function cleanPhoneNumber($phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }
}

if (!function_exists('formatDate')) {
    /**
     * Safe date formatting
     * 
     * @param mixed $date
     * @param string $format
     * @return string
     */
    function formatDate($date, $format = 'd M Y'): string
    {
        try {
            if ($date instanceof \Carbon\Carbon) {
                return $date->format($format);
            }
            
            if (is_string($date) && !empty($date)) {
                return \Carbon\Carbon::parse($date)->format($format);
            }
            
            return '';
        } catch (\Exception $e) {
            return is_string($date) ? $date : '';
        }
    }
}

if (!function_exists('calculateAge')) {
    /**
     * Calculate age from date
     * 
     * @param mixed $dob
     * @return string
     */
    function calculateAge($dob): string
    {
        try {
            if ($dob instanceof \Carbon\Carbon) {
                $age = $dob->age;
            } else {
                $age = \Carbon\Carbon::parse($dob)->age;
            }
            
            return $age . ' years';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
}

if (!function_exists('validatePakistaniCNIC')) {
    /**
     * Validate Pakistani CNIC (13 digits, valid format)
     * 
     * @param string $cnic
     * @return bool
     */
    function validatePakistaniCNIC($cnic): bool
    {
        // Remove all non-numeric characters
        $clean = preg_replace('/[^0-9]/', '', $cnic);
        
        // Must be exactly 13 digits
        if (strlen($clean) !== 13) {
            return false;
        }
        
        // First digit should be 1-7 (valid regions in Pakistan)
        $firstDigit = (int) $clean[0];
        if ($firstDigit < 1 || $firstDigit > 7) {
            return false;
        }
        
        // Last digit should be 0-9
        $lastDigit = (int) $clean[12];
        if ($lastDigit < 0 || $lastDigit > 9) {
            return false;
        }
        
        // Simple format check: XXX-XX-XXXXXXX
        // You can add more complex validation if needed
        
        return true;
    }
}

if (!function_exists('formatCNIC')) {
    /**
     * Format Pakistani CNIC to standard format
     * 
     * @param string $cnic
     * @return string
     */
    function formatCNIC($cnic): string
    {
        $clean = preg_replace('/[^0-9]/', '', $cnic);
        
        if (strlen($clean) === 13) {
            // Format: XXX-XX-XXXXXXX
            return substr($clean, 0, 5) . '-' . substr($clean, 5, 7) . '-' . substr($clean, 12, 1);
        }
        
        return $cnic;
    }
}

if (!function_exists('getDesignationTitle')) {
    /**
     * Get designation title with BPS
     */
    function getDesignationTitle($designationId)
    {
        try {
            $designation = \App\Models\Designation::find($designationId);
            if ($designation) {
                $title = $designation->title;
                if ($designation->bps) {
                    $title .= ' (BPS-' . $designation->bps . ')';
                }
                if ($designation->short_form) {
                    $title .= ' - ' . $designation->short_form;
                }
                return $title;
            }
            return 'N/A';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
}

if (!function_exists('getOfficeName')) {
    /**
     * Get office name
     */
    function getOfficeName($officeId)
    {
        try {
            $office = \App\Models\Office::find($officeId);
            return $office ? $office->name : 'N/A';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
}

if (!function_exists('isNHMPPatient')) {
    /**
     * Check if patient is NHMP
     */
    function isNHMPPatient($patient)
    {
        return $patient && $patient->is_nhmp;
    }
}

if (!function_exists('getPatientNHMPDetails')) {
    /**
     * Get formatted NHMP details
     */
    function getPatientNHMPDetails($patient)
    {
        if (!$patient || !$patient->is_nhmp) {
            return null;
        }
        
        $details = [];
        
        if ($patient->designation) {
            $details['designation'] = $patient->designation->title;
            if ($patient->designation->bps) {
                $details['bps'] = 'BPS-' . $patient->designation->bps;
            }
        }
        
        if ($patient->office) {
            $details['office'] = $patient->office->name;
        }
        
        if ($patient->rank) {
            $details['rank'] = $patient->rank;
        }
        
        return $details;
    }
}

if (!function_exists('activity')) {
    /**
     * Dummy activity helper for compatibility
     */
    function activity($logName = null)
    {
        return new class {
            public function log($message) { return $this; }
            public function performedOn($model) { return $this; }
            public function causedBy($user) { return $this; }
            public function withProperties($properties) { return $this; }
            public function useLog($logName) { return $this; }
            public function event($event) { return $this; }
        };
    }
}