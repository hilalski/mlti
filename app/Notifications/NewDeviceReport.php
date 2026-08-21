<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDeviceReport extends Notification
{
    use Queueable;

    protected $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $device = $this->report->device;
        $reporter = $this->report->reporter;

        return (new MailMessage)
            ->subject('Laporan Kerusakan Perangkat Baru: ' . ($device ? $device->series : 'Perangkat'))
            ->greeting('Halo Tim Jarkom,')
            ->line('Ada laporan kerusakan perangkat baru yang memerlukan tindakan Anda.')
            ->line('**Detail Laporan:**')
            ->line('- **Perangkat:** ' . ($device ? $device->series : 'N/A') . ' (' . $this->report->device_id . ')')
            ->line('- **Jenis Kendala:** ' . strtoupper($this->report->issue_type))
            ->line('- **Deskripsi:** ' . $this->report->description)
            ->line('- **Pelapor:** ' . ($reporter ? $reporter->name : 'N/A') . ' (' . $this->report->reported_by . ')')
            ->action('Lihat Daftar Laporan', url('/admin/reports'))
            ->line('Terima kasih telah menjaga kelancaran operasional IT!');
    }

    public function toArray(object $notifiable): array
    {
        $device = $this->report->device;
        $reporter = $this->report->reporter;

        return [
            'report_id' => $this->report->id,
            'device_id' => $this->report->device_id,
            'device_series' => $device ? $device->series : 'N/A',
            'issue_type' => $this->report->issue_type,
            'description' => $this->report->description,
            'reporter_name' => $reporter ? $reporter->name : 'N/A',
            'reported_at' => $this->report->created_at->toDateTimeString(),
        ];
    }
}
