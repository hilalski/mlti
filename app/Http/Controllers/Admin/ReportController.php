<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\VendorService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = $this->filteredReports($request)->latest()->paginate(10)->withQueryString();

        return view('admin.reports.index', compact('reports'));
    }

    public function export(Request $request): StreamedResponse
    {
        $reports = $this->filteredReports($request)->latest()->get();
        $filename = 'laporan-kerusakan-' . now()->format('Ymd-His') . '.xlsx';

        return response()->streamDownload(function () use ($reports): void {
            $rows = [];
            $headers = [
                'Nomor Tiket', 'Tanggal', 'Pelapor', 'Perangkat / Ruangan',
                'Jenis', 'Deskripsi', 'Status', 'Teknisi', 'Rating',
            ];
            $rows[] = '<row r="1">' . implode('', array_map(
                fn ($value, $column) => $this->xlsxTextCell($column . '1', $value, 1),
                $headers,
                range('A', 'I')
            )) . '</row>';

            foreach ($reports as $index => $report) {
                $rowNumber = $index + 2;
                $deviceOrRoom = $report->device
                    ? trim(($report->device->brand ?? '') . ' ' . ($report->device->series ?? '')) . ' (' . $report->device_id . ')'
                    : ($report->room->ruang ?? 'Ruangan tidak diketahui');

                $values = [
                    $report->ticket_id,
                    $report->created_at?->format('d-m-Y H:i'),
                    $report->reporter->name ?? '-',
                    $deviceOrRoom,
                    ucfirst($report->issue_type),
                    $report->description,
                    ucfirst($report->status),
                    $report->technician->name ?? '-',
                ];
                $cells = [];

                foreach ($values as $columnIndex => $value) {
                    $cells[] = $this->xlsxTextCell(chr(65 + $columnIndex) . $rowNumber, $value);
                }
                $cells[] = $report->rating !== null
                    ? '<c r="I' . $rowNumber . '"><v>' . (int) $report->rating . '</v></c>'
                    : $this->xlsxTextCell('I' . $rowNumber, '');

                $rows[] = '<row r="' . $rowNumber . '">' . implode('', $cells) . '</row>';
            }

            $worksheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
                . '<sheetData>' . implode('', $rows) . '</sheetData></worksheet>';

            $this->streamZip([
                '[Content_Types].xml' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                    . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
                    . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
                    . '<Default Extension="xml" ContentType="application/xml"/>'
                    . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
                    . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
                    . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
                    . '</Types>',
                '_rels/.rels' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                    . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
                    . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
                    . '</Relationships>',
                'xl/workbook.xml' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                    . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
                    . '<sheets><sheet name="Laporan" sheetId="1" r:id="rId1"/></sheets></workbook>',
                'xl/_rels/workbook.xml.rels' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                    . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
                    . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
                    . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
                    . '</Relationships>',
                'xl/styles.xml' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                    . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
                    . '<fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><sz val="11"/><name val="Calibri"/></font></fonts>'
                    . '<fills count="1"><fill><patternFill patternType="none"/></fill></fills>'
                    . '<borders count="1"><border/></borders><cellStyleXfs count="1"><xf/></cellStyleXfs>'
                    . '<cellXfs count="2"><xf fontId="0" fillId="0" borderId="0" xfId="0"/><xf fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/></cellXfs>'
                    . '</styleSheet>',
                'xl/worksheets/sheet1.xml' => $worksheet,
            ]);
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function show($ticketId)
    {
        $report = Report::with(['device.type', 'device.condition', 'reporter', 'technician', 'vendor'])
            ->where(fn ($query) => $query->where('ticket_id', $ticketId)->orWhere('id', $ticketId))
            ->firstOrFail();
        $vendors = VendorService::all();
        $technicians = User::where('is_jarkom', 1)->get();

        return view('admin.reports.show', compact('report', 'vendors', 'technicians'));
    }

    public function update(Request $request, $ticketId)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai,ditolak',
            'technician_notes' => 'nullable|string',
            'id_vendor' => 'nullable|exists:vendor_services,id',
            'handled_by' => 'nullable|exists:users,nip_lama',
        ]);

        $report = Report::where(fn ($query) => $query->where('ticket_id', $ticketId)->orWhere('id', $ticketId))->firstOrFail();

        $data = [
            'status' => $request->status,
            'technician_notes' => $request->technician_notes,
            'id_vendor' => $request->id_vendor ?: null,
            'handled_by' => $request->handled_by ?: ($report->handled_by ?: Auth::user()->nip_lama),
        ];

        if ($request->status === 'selesai') {
            $data['resolved_at'] = now();
        }

        $report->update($data);

        return redirect()->route('admin.reports.show', $report->ticket_id)->with('success', 'Laporan berhasil diperbarui.');
    }

    private function filteredReports(Request $request)
    {
        $query = Report::with(['device.type', 'reporter', 'room', 'technician']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('issue_type')) {
            $query->where('issue_type', $request->issue_type);
        }

        return $query;
    }

    private function xlsxTextCell(string $reference, mixed $value, ?int $style = null): string
    {
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', (string) $value) ?? '';
        $styleAttribute = $style === null ? '' : ' s="' . $style . '"';

        return '<c r="' . $reference . '" t="inlineStr"' . $styleAttribute . '><is><t xml:space="preserve">'
            . htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</t></is></c>';
    }

    private function streamZip(array $files): void
    {
        $offset = 0;
        $centralDirectory = '';
        $timestamp = getdate();
        $dosTime = ($timestamp['hours'] << 11) | ($timestamp['minutes'] << 5) | intdiv($timestamp['seconds'], 2);
        $dosDate = (($timestamp['year'] - 1980) << 9) | ($timestamp['mon'] << 5) | $timestamp['mday'];

        foreach ($files as $name => $contents) {
            $compressed = gzdeflate($contents);
            $crc = crc32($contents);
            $nameLength = strlen($name);
            $compressedLength = strlen($compressed);
            $uncompressedLength = strlen($contents);

            echo pack('VvvvvvVVVvv', 0x04034b50, 20, 0, 8, $dosTime, $dosDate, $crc, $compressedLength, $uncompressedLength, $nameLength, 0);
            echo $name . $compressed;

            $centralDirectory .= pack('VvvvvvvVVVvvvvvVV', 0x02014b50, 0x0314, 20, 0, 8, $dosTime, $dosDate, $crc, $compressedLength, $uncompressedLength, $nameLength, 0, 0, 0, 0, 0, $offset) . $name;
            $offset += 30 + $nameLength + $compressedLength;
        }

        echo $centralDirectory;
        echo pack('VvvvvVVv', 0x06054b50, 0, 0, count($files), count($files), strlen($centralDirectory), $offset, 0);
    }
}
