<?php

namespace Database\Seeders;

use App\Models\WorkflowState;
use App\Models\WorkflowTransition;
use Illuminate\Database\Seeder;

class WorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            ['name' => 'draft', 'label' => 'Draft', 'order_num' => 1, 'pic_role' => 'Ormawa Pengaju', 'pic_contact' => 'Ketua Ormawa / Seskab'],
            ['name' => 'submitted', 'label' => 'Diajukan ke BEM', 'order_num' => 2, 'pic_role' => 'Pengurus BEM', 'pic_contact' => 'Divisi Administrasi BEM'],
            ['name' => 'bem_approved', 'label' => 'Verifikasi BPM', 'order_num' => 3, 'pic_role' => 'Pengurus BPM', 'pic_contact' => 'Komisi Pengawasan BPM'],
            ['name' => 'ppks_review', 'label' => 'Verifikasi Satgas PPKS', 'order_num' => 4, 'pic_role' => 'Satgas PPKS ITG', 'pic_contact' => 'Satgas PPKS ITG'],
            ['name' => 'bpm_approved', 'label' => 'Verifikasi BKHM (Tahap 1)', 'order_num' => 5, 'pic_role' => 'BKHM', 'pic_contact' => 'Bapak Encep (BKHM)'],
            ['name' => 'bkhm_approved', 'label' => 'Verifikasi WR3', 'order_num' => 6, 'pic_role' => 'Wakil Rektor III', 'pic_contact' => 'Kantor WR III'],
            ['name' => 'wr3_approved', 'label' => 'Disetujui WR3 - Siap Diajukan ke Bendahara', 'order_num' => 7, 'pic_role' => 'BKHM (Tahap 2) / Bendahara', 'pic_contact' => 'Bagian Keuangan'],
            ['name' => 'to_treasurer', 'label' => 'Diajukan ke Bendahara', 'order_num' => 8, 'pic_role' => 'Bendahara Kampus', 'pic_contact' => 'Bagian Keuangan/Bendahara'],
            ['name' => 'funds_disbursed', 'label' => 'Dana Cair', 'order_num' => 9, 'pic_role' => 'Ormawa Pengaju', 'pic_contact' => 'Pelaksana Kegiatan'],
            ['name' => 'lpj_submitted', 'label' => 'LPJ Diajukan', 'order_num' => 10, 'pic_role' => 'BKHM', 'pic_contact' => 'Bapak Encep (BKHM)'],
            ['name' => 'lpj_wr3_review', 'label' => 'Verifikasi LPJ WR3', 'order_num' => 11, 'pic_role' => 'Wakil Rektor III', 'pic_contact' => 'Kantor WR III'],
            ['name' => 'completed', 'label' => 'Selesai', 'order_num' => 12, 'pic_role' => 'Sistem / Arsip', 'pic_contact' => 'Arsip Digital SKIN'],
            ['name' => 'cancelled', 'label' => 'Dibatalkan Pengaju', 'order_num' => 98, 'pic_role' => 'Ormawa Pengaju', 'pic_contact' => 'Dibatalkan oleh Pengaju'],
            ['name' => 'rejected', 'label' => 'Ditolak', 'order_num' => 99, 'pic_role' => 'Ormawa Pengaju', 'pic_contact' => 'Lihat Catatan Kendala'],
        ];

        foreach ($states as $state) {
            WorkflowState::updateOrCreate(['name' => $state['name']], $state);
        }

        // Setup transitions
        $transitions = [
            // Pengaju Ormawa (HIMA & UKM): langsung ke BEM
            ['from' => 'draft', 'to' => 'submitted', 'label' => 'Ajukan', 'role' => 'ormawa'],

            // Pengaju BEM: mulai dari BPM (lewati verifikasi BEM sendiri)
            ['from' => 'draft', 'to' => 'bem_approved', 'label' => 'Ajukan ke BPM', 'role' => 'bem'],

            // Pengaju BPM: ke Satgas PPKS (kegiatan) atau ke BKHM (non-kegiatan)
            ['from' => 'draft', 'to' => 'ppks_review', 'label' => 'Ajukan ke Satgas PPKS', 'role' => 'bpm'],
            ['from' => 'draft', 'to' => 'bpm_approved', 'label' => 'Ajukan ke BKHM', 'role' => 'bpm'],

            // BEM
            ['from' => 'submitted', 'to' => 'bem_approved', 'label' => 'Setujui', 'role' => 'bem'],
            ['from' => 'submitted', 'to' => 'rejected', 'label' => 'Tolak', 'role' => 'bem'],
            ['from' => 'submitted', 'to' => 'draft', 'label' => 'Revisi', 'role' => 'bem'],
            
            // BPM: meneruskan ke Satgas PPKS (kegiatan) atau langsung ke BKHM (permohonan dana non-kegiatan)
            ['from' => 'bem_approved', 'to' => 'ppks_review', 'label' => 'Setujui (Teruskan ke Satgas PPKS)', 'role' => 'bpm'],
            ['from' => 'bem_approved', 'to' => 'bpm_approved', 'label' => 'Setujui (Langsung ke BKHM)', 'role' => 'bpm'],
            ['from' => 'bem_approved', 'to' => 'rejected', 'label' => 'Tolak', 'role' => 'bpm'],
            ['from' => 'bem_approved', 'to' => 'draft', 'label' => 'Revisi', 'role' => 'bpm'],

            // Satgas PPKS: verifikasi dokumen izin & kepatuhan, lalu diteruskan ke BKHM
            ['from' => 'ppks_review', 'to' => 'bpm_approved', 'label' => 'Setujui Dokumen Izin', 'role' => 'satgas_ppks'],
            ['from' => 'ppks_review', 'to' => 'rejected', 'label' => 'Tolak Dokumen Izin', 'role' => 'satgas_ppks'],
            ['from' => 'ppks_review', 'to' => 'draft', 'label' => 'Revisi Dokumen Mitigasi', 'role' => 'satgas_ppks'],
            
            // BKHM
            ['from' => 'bpm_approved', 'to' => 'bkhm_approved', 'label' => 'Setujui', 'role' => 'bkhm'],
            ['from' => 'bpm_approved', 'to' => 'rejected', 'label' => 'Tolak', 'role' => 'bkhm'],
            ['from' => 'bpm_approved', 'to' => 'draft', 'label' => 'Revisi', 'role' => 'bkhm'],
            
            // WR3
            ['from' => 'bkhm_approved', 'to' => 'wr3_approved', 'label' => 'Setujui', 'role' => 'wr3'],
            ['from' => 'bkhm_approved', 'to' => 'rejected', 'label' => 'Tolak', 'role' => 'wr3'],
            ['from' => 'bkhm_approved', 'to' => 'draft', 'label' => 'Revisi', 'role' => 'wr3'],
            
            // BKHM to Treasurer
            ['from' => 'wr3_approved', 'to' => 'to_treasurer', 'label' => 'Ajukan Pencairan', 'role' => 'bkhm'],
            
            // Treasurer
            ['from' => 'to_treasurer', 'to' => 'funds_disbursed', 'label' => 'Cairkan', 'role' => 'bendahara'],
            
            // Ormawa
            ['from' => 'funds_disbursed', 'to' => 'lpj_submitted', 'label' => 'Kirim LPJ', 'role' => 'ormawa'],
            
            // BKHM verifikasi LPJ -> diteruskan ke WR3
            ['from' => 'lpj_submitted', 'to' => 'lpj_wr3_review', 'label' => 'Setujui LPJ', 'role' => 'bkhm'],
            ['from' => 'lpj_submitted', 'to' => 'funds_disbursed', 'label' => 'Revisi LPJ', 'role' => 'bkhm'],

            // WR3 verifikasi LPJ -> selesai
            ['from' => 'lpj_wr3_review', 'to' => 'completed', 'label' => 'Verifikasi LPJ', 'role' => 'wr3'],
            ['from' => 'lpj_wr3_review', 'to' => 'funds_disbursed', 'label' => 'Revisi LPJ', 'role' => 'wr3'],
        ];

        foreach ($transitions as $transition) {
            $from = WorkflowState::where('name', $transition['from'])->first();
            $to = WorkflowState::where('name', $transition['to'])->first();
            
            if ($from && $to) {
                WorkflowTransition::firstOrCreate([
                    'from_state_id' => $from->id,
                    'to_state_id' => $to->id,
                    'action_label' => $transition['label'],
                    'required_role' => $transition['role']
                ]);
            }
        }
    }
}
