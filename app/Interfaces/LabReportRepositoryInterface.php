<?php

namespace App\Interfaces;

interface LabReportRepositoryInterface
{
    public function getAll(array $filters = [], $perPage = 10);
    public function find(int $id);
    public function findWithRelations(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id): bool;
    public function getEligiblePatients();
    public function getAllDoctors();
    public function getAllTechnicians();
    public function getPendingReports();
    public function getByPatient(int $patientId);
    public function getReportsByDateRange(string $startDate, string $endDate);
    public function getUrgentReports();
    public function getOverdueReports();
    public function getPendingVerificationReports();
    public function getStatistics(): array;
    public function getByDateRange(string $startDate, string $endDate, array $filters = []);
}
