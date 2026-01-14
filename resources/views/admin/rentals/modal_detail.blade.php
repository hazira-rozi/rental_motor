<div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Rincian Transaksi #{{ $item->id }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="strukContent{{ $item->id }}">
                <div class="text-center">
                    <h5 class="font-weight-bold">STRUK RENTAL MOTOR</h5>
                    <p class="small">UKK RPL 2026 - Jl. Contoh No. 123</p>
                    <hr style="border-top: 1px dashed black;">
                </div>
                <table class="table table-sm table-borderless small">
                    <tr><td>Pelanggan</td><td>: {{ $item->user->name }}</td></tr>
                    <tr><td>Motor</td><td>: {{ $item->motor->merk }} ({{ $item->motor->nopol }})</td></tr>
                    <tr><td>Tgl Pinjam</td><td>: {{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y H:i') }}</td></tr>
                    @if($item->tgl_kembali)
                        <tr><td>Tgl Kembali</td><td>: {{ \Carbon\Carbon::parse($item->tgl_kembali)->format('d/m/Y H:i') }}</td></tr>
                    @endif
                    <tr><td>Status</td><td>: {{ strtoupper($item->status_transaksi) }}</td></tr>
                </table>
                <hr style="border-top: 1px dashed black;">
                <div class="d-flex justify-content-between px-2 font-weight-bold">
                    <span>TOTAL:</span>
                    <span>Rp {{ number_format($item->total_bayar ?? 0) }}</span>
                </div>
                <hr style="border-top: 1px dashed black;">
                <div class="text-center small">
                    <p>Terima Kasih Atas Kunjungan Anda</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                @if($item->status_transaksi != 'menunggu' && $item->status_transaksi != 'ditolak')
                    <button type="button" class="btn btn-primary" onclick="printStruk('{{ $item->id }}')">
                        <i class="fas fa-print mr-1"></i> Cetak
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>