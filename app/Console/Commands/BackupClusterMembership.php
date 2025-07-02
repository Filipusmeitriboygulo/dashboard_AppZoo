<?php

use Illuminate\Console\Command;
use App\Models\ClusterResult;

class BackupClusterMembership extends Command
{
    protected $signature = 'cluster:backup-membership';
    protected $description = 'Menyimpan membership cluster1-3 ke kolom JSON membership';

    public function handle()
    {
        $this->info('Memulai proses backup membership...');

        $rows = ClusterResult::whereNull('membership')->get();

        foreach ($rows as $row) {
            $row->membership = [
                'Membership_Cluster_1' => $row->membership_cluster1,
                'Membership_Cluster_2' => $row->membership_cluster2,
                'Membership_Cluster_3' => $row->membership_cluster3,
            ];
            $row->save();
        }

        $this->info("Backup selesai untuk {$rows->count()} baris.");
    }
}
